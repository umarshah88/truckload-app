<?php

namespace App\Http\Controllers\Api\Tracking;

use App\Models\Ride;
use App\Models\Delivery;
use App\Models\DriverLocation;
use App\Http\Controllers\Api\Auth\BaseController;
use Illuminate\Http\Request;

class TrackingController extends BaseController
{
    /**
     * Get real-time tracking for a ride
     */
    public function trackRide(Request $request, $rideId)
    {
        $ride = Ride::find($rideId);

        if (!$ride) {
            return $this->error('Ride not found', [], 404);
        }

        // Check authorization
        if ($request->user()->id !== $ride->customer_id && $request->user()->id !== $ride->driver_id) {
            return $this->error('Unauthorized', [], 403);
        }

        // Get latest driver location
        $location = DriverLocation::where('ride_id', $rideId)
            ->latest('recorded_at')
            ->first();

        return $this->success([
            'ride' => $ride,
            'driver_location' => $location,
            'driver' => $ride->driver,
        ], 'Ride tracking data retrieved successfully');
    }

    /**
     * Get real-time tracking for a delivery
     */
    public function trackDelivery(Request $request, $deliveryId)
    {
        $delivery = Delivery::find($deliveryId);

        if (!$delivery) {
            return $this->error('Delivery not found', [], 404);
        }

        // Check authorization
        if ($request->user()->id !== $delivery->sender_id && $request->user()->id !== $delivery->driver_id) {
            return $this->error('Unauthorized', [], 403);
        }

        // Get latest driver location
        $location = DriverLocation::where('delivery_id', $deliveryId)
            ->latest('recorded_at')
            ->first();

        return $this->success([
            'delivery' => $delivery,
            'driver_location' => $location,
            'driver' => $delivery->driver,
        ], 'Delivery tracking data retrieved successfully');
    }
}
