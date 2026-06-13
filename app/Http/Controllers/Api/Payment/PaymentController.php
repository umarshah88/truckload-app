<?php

namespace App\Http\Controllers\Api\Payment;

use App\Models\Payment;
use App\Models\Wallet;
use App\Http\Controllers\Api\Auth\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    /**
     * Get payment history
     */
    public function getHistory(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->success(['payments' => $payments], 'Payment history retrieved successfully');
    }

    /**
     * Top up wallet
     */
    public function topupWallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100|max:100000',
            'payment_method' => 'required|in:card,bank_transfer,jazz_cash,easypaisa',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $user = $request->user();
        $amount = $request->amount;

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'type' => 'wallet_topup',
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'amount' => $amount,
            'net_amount' => $amount,
            'currency' => 'PKR',
            'reference_number' => 'TUP-' . time() . '-' . $user->id,
        ]);

        // TODO: Process payment via gateway
        // Redirect to payment gateway or return payment URL

        return $this->success([
            'payment' => $payment,
            'payment_url' => 'https://payment-gateway.com/process/' . $payment->id,
        ], 'Payment initiated', 201);
    }

    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, $paymentId)
    {
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return $this->error('Payment not found', [], 404);
        }

        if ($request->user()->id !== $payment->user_id) {
            return $this->error('Unauthorized', [], 403);
        }

        // TODO: Verify payment status with gateway
        // If successful, update wallet balance

        if ($payment->status === 'completed') {
            $wallet = Wallet::where('user_id', $payment->user_id)->first();
            $wallet->addBalance($payment->amount);
        }

        return $this->success(['payment' => $payment], 'Payment verified');
    }

    /**
     * Get wallet balance
     */
    public function getWalletBalance(Request $request)
    {
        $wallet = Wallet::where('user_id', $request->user()->id)->first();

        return $this->success(['wallet' => $wallet], 'Wallet balance retrieved successfully');
    }
}
