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
    public ?string $sessionId = null;
    public ?Order $order = null;

    public function mount()
    {
        $this->sessionId = request('session_id');

        if ($this->sessionId) {
            $this->processCheckout();
        }
    }

    protected function processCheckout()
    {
        try {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));

            $session = \Stripe\Checkout\Session::retrieve($this->sessionId);

            // Check if payment is successful
            if ($session->payment_status !== 'paid') {
                session()->flash('error', 'Payment not completed');
                return;
            }

            // Check if order already exists
            $existingOrder = Order::where('stripe_payment_intent_id', $session->payment_intent)->first();

            if ($existingOrder) {
                $this->order = $existingOrder;
                return;
            }

            // Get cart from session metadata
            $cartId = $session->metadata->cart_id ?? null;

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
                'stripe_payment_intent_id' => $session->payment_intent,
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
