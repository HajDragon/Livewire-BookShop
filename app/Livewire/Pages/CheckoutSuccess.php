<?php

namespace App\Livewire\Pages;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Order Confirmed')]
class CheckoutSuccess extends Component
{
    public ?string $paymentId = null;
    public ?Order $order = null;

    public function mount()
    {
        $this->paymentId = request('payment_id');

        if ($this->paymentId) {
            $this->processCheckout();
        }
    }

    protected function processCheckout()
    {
        try {
            $mollie = \Mollie\Laravel\Facades\Mollie::api();
            $payment = $mollie->payments->get($this->paymentId);

            // Check if payment is successful
            if (!$payment->isPaid()) {
                session()->flash('error', 'Payment not completed');
                return;
            }

            // Check if order already exists
            $existingOrder = Order::where('stripe_payment_intent_id', $payment->id)->first();

            if ($existingOrder) {
                $this->order = $existingOrder;
                return;
            }

            // Get cart from payment metadata
            $cartId = $payment->metadata->cart_id ?? null;

            if (!$cartId) {
                return;
            }

            $cart = Cart::with('items.book')->find($cartId);

            if (!$cart) {
                return;
            }

            // Create order
            $this->order = Order::create([
                'user_id' => auth()->id(),
                'stripe_payment_intent_id' => $payment->id,
                'status' => 'completed',
                'total' => $cart->total(),
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $this->order->id,
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

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.checkout-success');
    }
}
