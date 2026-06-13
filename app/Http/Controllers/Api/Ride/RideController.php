<?php

namespace App\Http\Controllers\Api\Ride;

use App\Models\Ride;
use App\Models\City;
use App\Models\VehicleType;
use App\Models\PricingRule;
use App\Http\Controllers\Api\Auth\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RideController extends BaseController
{
    /**
     * Estimate fare for a ride
     */
    public function estimateFare(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'pickup_latitude' => 'required|numeric|between:-90,90',
            'pickup_longitude' => 'required|numeric|between:-180,180',
            'dropoff_latitude' => 'required|numeric|between:-90,90',
            'dropoff_longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $city = City::find($request->city_id);
        $vehicleType = VehicleType::find($request->vehicle_type_id);

        // Calculate distance using Haversine formula
        $distance = $this->calculateDistance(
            $request->pickup_latitude,
            $request->pickup_longitude,
            $request->dropoff_latitude,
            $request->dropoff_longitude
        );

        // Get pricing rule
        $pricing = PricingRule::where('city_id', $request->city_id)
            ->where('vehicle_type_id', $request->vehicle_type_id)
            ->where('is_active', true)
            ->first();

        if (!$pricing) {
            $pricing = (object) [
                'base_fare' => $city->base_fare,
                'per_km_rate' => $city->per_km_rate,
                'per_minute_rate' => $city->per_minute_rate,
                'minimum_fare' => 100,
            ];
        }

        // Calculate fare
        $baseFare = $pricing->base_fare;
        $distanceCharge = $distance * $pricing->per_km_rate;
        $estimatedMinutes = ($distance / 40) * 60; // Assume 40 km/h avg speed
        $timeCharge = $estimatedMinutes * $pricing->per_minute_rate;
        $subtotal = $baseFare + $distanceCharge + $timeCharge;
        $totalFare = max($subtotal, $pricing->minimum_fare);

        return $this->success([
            'distance_km' => round($distance, 2),
            'estimated_duration_minutes' => round($estimatedMinutes),
            'base_fare' => $baseFare,
            'distance_charge' => round($distanceCharge, 2),
            'time_charge' => round($timeCharge, 2),
            'estimated_total' => round($totalFare, 2),
            'currency' => 'PKR',
        ], 'Fare estimated successfully');
    }

    /**
     * Book a ride
     */
    public function bookRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'pickup_address' => 'required|string|max:500',
            'pickup_latitude' => 'required|numeric|between:-90,90',
            'pickup_longitude' => 'required|numeric|between:-180,180',
            'dropoff_address' => 'required|string|max:500',
            'dropoff_latitude' => 'required|numeric|between:-90,90',
            'dropoff_longitude' => 'required|numeric|between:-180,180',
            'passenger_count' => 'nullable|integer|min:1|max:8',
            'notes' => 'nullable|string|max:500',
            'promo_code' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $user = $request->user();

        // Create ride
        $ride = Ride::create([
            'customer_id' => $user->id,
            'city_id' => $request->city_id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'pickup_address' => $request->pickup_address,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_latitude' => $request->dropoff_latitude,
            'dropoff_longitude' => $request->dropoff_longitude,
            'status' => 'requested',
            'passenger_count' => $request->passenger_count ?? 1,
            'notes' => $request->notes,
            'promo_code' => $request->promo_code,
            'requested_at' => now(),
        ]);

        // TODO: Broadcast event to available drivers
        // broadcast(new RideRequested($ride));

        return $this->success([
            'ride' => $ride,
        ], 'Ride booked successfully', 201);
    }

    /**
     * Get ride details
     */
    public function getRide(Request $request, $id)
    {
        $ride = Ride::with([
            'customer',
            'driver',
            'vehicle',
            'vehicleType',
            'city',
        ])->find($id);

        if (!$ride) {
            return $this->error('Ride not found', [], 404);
        }

        // Check authorization
        if ($request->user()->id !== $ride->customer_id && $request->user()->id !== $ride->driver_id) {
            return $this->error('Unauthorized', [], 403);
        }

        return $this->success(['ride' => $ride], 'Ride retrieved successfully');
    }

    /**
     * Get user's rides
     */
    public function myRides(Request $request)
    {
        $rides = Ride::where('customer_id', $request->user()->id)
            ->with(['vehicle', 'vehicleType', 'driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->success(['rides' => $rides], 'Rides retrieved successfully');
    }

    /**
     * Cancel a ride
     */
    public function cancelRide(Request $request, $id)
    {
        $ride = Ride::find($id);

        if (!$ride) {
            return $this->error('Ride not found', [], 404);
        }

        if ($request->user()->id !== $ride->customer_id) {
            return $this->error('Unauthorized', [], 403);
        }

        if (!in_array($ride->status, ['requested', 'searching', 'accepted'])) {
            return $this->error('Cannot cancel ride in current status', [], 400);
        }

        $ride->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
            'cancellation_by' => 'customer',
            'cancelled_at' => now(),
        ]);

        return $this->success(['ride' => $ride], 'Ride cancelled successfully');
    }

    /**
     * Calculate distance using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // Earth's radius in kilometers
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }
}
