<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Wallet;
use App\Models\UserProfile;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Send OTP to phone number
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'type' => 'required|in:registration,login,password_reset',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $phone = $request->phone;
        $type = $request->type;

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in database
        OtpVerification::updateOrCreate(
            ['phone' => $phone, 'type' => $type],
            [
                'otp' => $otp,
                'is_verified' => false,
                'attempts' => 0,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        // TODO: Send OTP via Twilio SMS
        // Twilio::sendMessage($phone, "Your Sawaari OTP is: $otp");

        return $this->success(
            ['message' => 'OTP sent successfully', 'otp' => $otp], // Remove in production
            'OTP sent to ' . substr($phone, -4)
        );
    }

    /**
     * Verify OTP and register/login user
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'otp' => 'required|string|size:6',
            'type' => 'required|in:registration,login,password_reset',
            'name' => 'required_if:type,registration|string|max:255',
            'email' => 'nullable|email:rfc,dns',
            'role' => 'nullable|in:customer,driver',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $phone = $request->phone;
        $otp = $request->otp;
        $type = $request->type;

        // Verify OTP
        $otpRecord = OtpVerification::where('phone', $phone)
            ->where('type', $type)
            ->first();

        if (!$otpRecord) {
            return $this->error('Invalid OTP', [], 401);
        }

        if ($otpRecord->isExpired()) {
            return $this->error('OTP Expired', [], 401);
        }

        if ($otpRecord->otp !== $otp) {
            $otpRecord->increment('attempts');
            if ($otpRecord->attempts >= 3) {
                $otpRecord->delete();
                return $this->error('Too many attempts. Please request a new OTP', [], 429);
            }
            return $this->error('Invalid OTP', [], 401);
        }

        // Mark OTP as verified
        $otpRecord->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        if ($type === 'registration') {
            return $this->handleRegistration($request, $phone);
        } elseif ($type === 'login') {
            return $this->handleLogin($phone);
        }

        return $this->success(['message' => 'OTP verified'], 'OTP verified successfully');
    }

    /**
     * Handle user registration
     */
    private function handleRegistration(Request $request, $phone)
    {
        // Check if user already exists
        $existingUser = User::where('phone', $phone)->first();
        if ($existingUser) {
            return $this->error('Phone number already registered', [], 409);
        }

        // Create new user
        $user = User::create([
            'phone' => $phone,
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->phone), // Temporary password
            'role' => $request->role ?? 'customer',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // Create user profile
        UserProfile::create(['user_id' => $user->id]);

        // Create wallet
        Wallet::create(['user_id' => $user->id]);

        // Generate token
        $token = $user->createToken('sawaari-app')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'User registered successfully', 201);
    }

    /**
     * Handle user login
     */
    private function handleLogin($phone)
    {
        $user = User::where('phone', $phone)
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return $this->error('User not found', [], 404);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Generate token
        $token = $user->createToken('sawaari-app')->plainTextToken;

        return $this->success([
            'user' => $user->load(['profile', 'wallet']),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success([], 'Logged out successfully');
    }

    /**
     * Get current user profile
     */
    public function me(Request $request)
    {
        return $this->success([
            'user' => $request->user()->load(['profile', 'wallet', 'vehicles']),
        ], 'User profile retrieved successfully');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email:rfc,dns',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $user = $request->user();

        // Update basic info
        $user->update($request->only(['name', 'email']));

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar_url' => $path]);
        }

        return $this->success(['user' => $user], 'Profile updated successfully');
    }
}
