<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Payment;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:10',
            'phone' => ['required','string','min:8','max:20'],
            'payment_method' => 'required|in:card,cash',
            'stripe_token' => 'nullable|string',
        ]);

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('menuItem')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('menu')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->menuItem->price * $item->quantity;
        });
        $total = $subtotal + 250;

        DB::beginTransaction();

        try {
            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'Pending'
            ]);

            // Create Order Items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'item_id' => $cartItem->item_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->menuItem->price
                ]);
            }

            // If card payment, process with Stripe
            $transactionId = null;
            $paymentStatus = $request->payment_method === 'card' ? 'Completed' : 'Pending';

            if ($request->payment_method === 'card') {
                $stripeResult = $this->stripeService->processPayment((int) ($total * 100), $request->input('stripe_token'), "Order #{$order->order_id}", ['order_id' => $order->order_id]);

                if (!$stripeResult['success']) {
                    DB::rollBack();
                    return back()->with('error', 'Payment failed: ' . ($stripeResult['error'] ?? 'Unable to charge card'));
                }

                $transactionId = $stripeResult['transaction_id'] ?? null;
                $paymentStatus = $stripeResult['status'] ?? 'Completed';
            }

            // Create Payment record
            Payment::create([
                'order_id' => $order->order_id,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => $paymentStatus,
                'transaction_id' => $transactionId,
            ]);

            // Clear Cart
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('order.success', ['order' => $order->order_id])->with('success', 'Your order was placed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
