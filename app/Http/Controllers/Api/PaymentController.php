<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\StripePaymentService;
use App\Services\MailgunEmailService;
use App\Services\TwilioSmsService;
use App\Events\PaymentProcessed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Payment API Controller
 * 
 * Handles all payment-related operations
 * - Process Stripe payments
 * - Create payment intents
 * - Store payment records
 * - Send payment confirmations
 */
class PaymentController extends Controller
{
    protected $stripeService;
    protected $mailService;
    protected $smsService;

    public function __construct(
        StripePaymentService $stripeService,
        MailgunEmailService $mailService,
        TwilioSmsService $smsService
    ) {
        $this->stripeService = $stripeService;
        $this->mailService = $mailService;
        $this->smsService = $smsService;
    }

    /**
     * Get Stripe public key for frontend
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStripeKey()
    {
        return response()->json([
            'success' => true,
            'public_key' => $this->stripeService->getPublicKey(),
        ]);
    }

    /**
     * Process payment for an order
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'stripe_token' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Process payment via Stripe
        $payment = $this->stripeService->processPayment(
            (int) ($validated['amount'] * 100), // Convert to cents
            $validated['stripe_token'],
            "Order #{$order->order_id}",
            ['order_id' => $order->order_id]
        );

        if (!$payment['success']) {
            return response()->json([
                'success' => false,
                'message' => $payment['error'],
            ], 400);
        }

        // Store payment record
        $paymentRecord = Payment::create([
            'order_id' => $order->order_id,
            'payment_method' => 'stripe',
            'amount' => $validated['amount'],
            'status' => 'Completed',
            'transaction_id' => $payment['transaction_id'],
        ]);

        // Broadcast payment processed event for real-time updates
        try {
            event(new PaymentProcessed($paymentRecord));
        } catch (\Throwable $e) {
            Log::warning('Broadcast failed: '.$e->getMessage());
        }

            // Send confirmation emails and SMS (don't fail the flow if these services error)
            $user = $order->user;

            try {
                // Email confirmation (best-effort)
                $this->mailService->sendOrderConfirmation($user->email, $user->name, [
                    'order_id' => $order->order_id,
                    'total' => $validated['amount'],
                ]);
            } catch (\Throwable $e) {
                Log::warning('Mail service failed: '.$e->getMessage());
            }

            try {
                if ($user->phone) {
                    // SMS confirmation (best-effort)
                    $this->smsService->sendOrderConfirmation(
                        $user->phone,
                        $order->order_id,
                        $validated['amount']
                    );
                }
            } catch (\Throwable $e) {
                Log::warning('SMS service failed: '.$e->getMessage());
            }

        Log::info('Order Payment Completed', [
            'order_id' => $order->order_id,
            'payment_id' => $paymentRecord->payment_id,
            'amount' => $validated['amount'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'payment_id' => $paymentRecord->payment_id,
            'transaction_id' => $payment['transaction_id'],
        ]);
    }

    /**
     * Create payment intent for advanced payment flow
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPaymentIntent(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        $intent = $this->stripeService->createPaymentIntent(
            (int) ($validated['amount'] * 100),
            'usd',
            "Order #{$order->order_id}"
        );

        if (!$intent['success']) {
            return response()->json([
                'success' => false,
                'message' => $intent['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'client_secret' => $intent['client_secret'],
            'intent_id' => $intent['intent_id'],
        ]);
    }

    /**
     * Get payment status
     * 
     * @param Payment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentStatus(Payment $payment)
    {
        return response()->json([
            'success' => true,
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'transaction_id' => $payment->transaction_id,
            'created_at' => $payment->created_at,
        ]);
    }

    /**
     * Get order payments history
     * 
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderPayments(Order $order)
    {
        $payments = $order->payments()->get();

        return response()->json([
            'success' => true,
            'order_id' => $order->order_id,
            'payments' => $payments,
            'total_paid' => $payments->sum('amount'),
        ]);
    }
}
