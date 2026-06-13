<?php

namespace App\Http\Controllers\Api\Driver;

use App\Models\Vehicle;
use App\Models\DriverLocation;
use App\Models\DriverDocument;
use App\Http\Controllers\Api\Auth\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends BaseController
{
    /**
     * Get driver profile
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();

        return $this->success([
            'driver' => $user->load(['profile', 'vehicles', 'wallet', 'documents']),
        ], 'Driver profile retrieved successfully');
    }

    /**
     * Update location
     */
    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'heading' => 'nullable|integer|between:0,360',
            'speed' => 'nullable|numeric|min:0',
            'ride_id' => 'nullable|exists:rides,id',
            'delivery_id' => 'nullable|exists:deliveries,id',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $user = $request->user();

        // Store location
        $location = DriverLocation::create([
            'driver_id' => $user->id,
            'ride_id' => $request->ride_id,
            'delivery_id' => $request->delivery_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'heading' => $request->heading,
            'speed' => $request->speed,
            'recorded_at' => now(),
        ]);

        // TODO: Broadcast location update to customer

        return $this->success(['location' => $location], 'Location updated successfully');
    }

    /**
     * Get available rides/deliveries
     */
    public function getAvailableJobs(Request $request)
    {
        $type = $request->query('type', 'all'); // ride, delivery, all
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $radius = $request->query('radius', 10); // km

        // TODO: Query rides/deliveries within radius
        // For now, return empty list

        return $this->success(['jobs' => []], 'Available jobs retrieved successfully');
    }

    /**
     * Accept a job
     */
    public function acceptJob(Request $request, $jobId, $type = 'ride')
    {
        $validator = Validator::make([
            'type' => $type,
        ], [
            'type' => 'required|in:ride,delivery',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        // TODO: Accept ride or delivery
        // Update status to 'accepted'
        // Assign driver and vehicle

        return $this->success([], 'Job accepted successfully');
    }

    /**
     * Get driver documents
     */
    public function getDocuments(Request $request)
    {
        $documents = DriverDocument::where('driver_id', $request->user()->id)
            ->get()
            ->groupBy('document_type');

        return $this->success(['documents' => $documents], 'Documents retrieved successfully');
    }

    /**
     * Upload document
     */
    public function uploadDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:license,cnic,registration,insurance,inspection',
            'document_number' => 'nullable|string|max:100',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'expires_at' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $user = $request->user();
        $file = $request->file('document_file');
        $path = $file->store('driver-documents', 'public');

        $document = DriverDocument::create([
            'driver_id' => $user->id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'file_url' => $path,
            'status' => 'pending',
            'expires_at' => $request->expires_at,
        ]);

        return $this->success(['document' => $document], 'Document uploaded successfully', 201);
    }

    /**
     * Get earnings
     */
    public function getEarnings(Request $request)
    {
        $period = $request->query('period', 'daily'); // daily, weekly, monthly
        $user = $request->user();

        // TODO: Calculate earnings based on completed rides/deliveries

        return $this->success([
            'total_earnings' => $user->wallet->total_earned ?? 0,
            'available_balance' => $user->wallet->balance ?? 0,
            'period' => $period,
        ], 'Earnings retrieved successfully');
    }
}
