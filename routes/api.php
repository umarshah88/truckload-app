<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Ride\RideController;
use App\Http\Controllers\Api\Delivery\DeliveryController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Driver\DriverController;
use App\Http\Controllers\Api\Tracking\TrackingController;

Route::prefix('v1')->group(function () {
    // Public auth routes
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

        // Rides
        Route::prefix('rides')->group(function () {
            Route::post('/estimate-fare', [RideController::class, 'estimateFare']);
            Route::post('/', [RideController::class, 'bookRide']);
            Route::get('/my-rides', [RideController::class, 'myRides']);
            Route::get('/{id}', [RideController::class, 'getRide']);
            Route::post('/{id}/cancel', [RideController::class, 'cancelRide']);
        });

        // Deliveries
        Route::prefix('deliveries')->group(function () {
            Route::post('/', [DeliveryController::class, 'createDelivery']);
            Route::get('/my-deliveries', [DeliveryController::class, 'myDeliveries']);
            Route::get('/{id}', [DeliveryController::class, 'getDelivery']);
        });

        // Payments
        Route::prefix('payments')->group(function () {
            Route::get('/history', [PaymentController::class, 'getHistory']);
            Route::post('/topup-wallet', [PaymentController::class, 'topupWallet']);
            Route::get('/verify/{paymentId}', [PaymentController::class, 'verifyPayment']);
            Route::get('/wallet-balance', [PaymentController::class, 'getWalletBalance']);
        });

        // Driver routes
        Route::middleware('driver')->prefix('driver')->group(function () {
            Route::get('/profile', [DriverController::class, 'getProfile']);
            Route::post('/location', [DriverController::class, 'updateLocation']);
            Route::get('/available-jobs', [DriverController::class, 'getAvailableJobs']);
            Route::post('/accept-job/{jobId}', [DriverController::class, 'acceptJob']);
            Route::get('/documents', [DriverController::class, 'getDocuments']);
            Route::post('/documents/upload', [DriverController::class, 'uploadDocument']);
            Route::get('/earnings', [DriverController::class, 'getEarnings']);
        });

        // Tracking
        Route::prefix('tracking')->group(function () {
            Route::get('/ride/{rideId}', [TrackingController::class, 'trackRide']);
            Route::get('/delivery/{deliveryId}', [TrackingController::class, 'trackDelivery']);
        });
    });
});
