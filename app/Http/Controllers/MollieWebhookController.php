<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class MollieWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $paymentId = $request->input('id');

        if (!$paymentId) {
            return response()->json(['error' => 'Invalid payment ID'], 400);
        }

        try {
            $mollie = \Mollie\Laravel\Facades\Mollie::api();
            $payment = $mollie->payments->get($paymentId);

            // Only process paid payments
            if (!$payment->isPaid()) {
                return response()->json(['status' => 'not_paid']);
            }

            // Check if order already exists
            $existingOrder = Order::where('stripe_payment_intent_id', $payment->id)->first();

            if ($existingOrder) {
                return response()->json(['status' => 'already_processed']);
            }

            // Get cart from metadata
            $cartId = $payment->metadata->cart_id ?? null;
            $userId = $payment->metadata->user_id ?? null;

            if (!$cartId || !$userId) {
                return response()->json(['error' => 'Missing metadata'], 400);
            }

            $cart = Cart::with('items.book')->find($cartId);

            if (!$cart) {
                return response()->json(['error' => 'Cart not found'], 404);
            }

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'stripe_payment_intent_id' => $payment->id,
                'status' => 'completed',
                'total' => $cart->total(),
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'book_name' => $item->book->name,
                    'book_author' => $item->book->author,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
