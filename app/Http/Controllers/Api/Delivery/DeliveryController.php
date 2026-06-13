<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Models\Delivery;
use App\Http\Controllers\Api\Auth\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends BaseController
{
    /**
     * Create a delivery order
     */
    public function createDelivery(Request $request)
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
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'description' => 'required|string|max:500',
            'weight_kg' => 'nullable|numeric|min:0.1',
            'item_type' => 'nullable|string|max:100',
            'payment_method' => 'required|in:wallet,card,cash,cod',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $user = $request->user();

        // Create delivery
        $delivery = Delivery::create([
            'sender_id' => $user->id,
            'city_id' => $request->city_id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'pickup_address' => $request->pickup_address,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'dropoff_address' => $request->dropoff_address,
            'dropoff_latitude' => $request->dropoff_latitude,
            'dropoff_longitude' => $request->dropoff_longitude,
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'description' => $request->description,
            'weight_kg' => $request->weight_kg,
            'item_type' => $request->item_type,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        // TODO: Broadcast event to available drivers

        return $this->success([
            'delivery' => $delivery,
        ], 'Delivery order created successfully', 201);
    }

    /**
     * Get delivery details
     */
    public function getDelivery(Request $request, $id)
    {
        $delivery = Delivery::with([
            'sender',
            'driver',
            'vehicle',
            'vehicleType',
            'city',
        ])->find($id);

        if (!$delivery) {
            return $this->error('Delivery not found', [], 404);
        }

        // Check authorization
        if ($request->user()->id !== $delivery->sender_id && $request->user()->id !== $delivery->driver_id) {
            return $this->error('Unauthorized', [], 403);
        }

        return $this->success(['delivery' => $delivery], 'Delivery retrieved successfully');
    }

    /**
     * Get user's deliveries
     */
    public function myDeliveries(Request $request)
    {
        $status = $request->query('status');
        $query = Delivery::where('sender_id', $request->user()->id)
            ->with(['vehicle', 'vehicleType', 'driver']);

        if ($status) {
            $query->where('status', $status);
        }

        $deliveries = $query->orderBy('created_at', 'desc')->paginate(15);

        return $this->success(['deliveries' => $deliveries], 'Deliveries retrieved successfully');
    }
}
