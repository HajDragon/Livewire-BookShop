<?php

namespace App\Livewire\Pages\Products;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Shopping Cart')]
class Index extends Component
{
    public ?Cart $cart = null;

    public function mount()
    {
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart()
    {
        $this->cart = auth()->user()->cart()->with('items.book')->first();
    }

    public function updateQuantity(int $cartItemId, int $quantity)
    {
        if ($quantity < 1) {
            return;
        }

        $cartItem = $this->cart->items()->find($cartItemId);
        if ($cartItem) {
            $cartItem->update(['quantity' => $quantity]);
            $this->loadCart();
        }
    }

    public function removeItem(int $cartItemId)
    {
        $cartItem = $this->cart->items()->find($cartItemId);
        if ($cartItem) {
            $cartItem->delete();
            $this->loadCart();
            session()->flash('message', 'Item removed from cart');
        }
    }

    public function checkout()
    {
        if (!$this->cart || $this->cart->items->isEmpty()) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        try {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));

            $lineItems = $this->cart->items->map(function ($item) {
                return [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->book->name,
                            'description' => $item->book->author ?? '',
                        ],
                        'unit_amount' => (int) ($item->price * 100), // Convert to cents
                    ],
                    'quantity' => $item->quantity,
                ];
            })->toArray();

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('products'),
                'metadata' => [
                    'cart_id' => $this->cart->id,
                    'user_id' => auth()->id(),
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            session()->flash('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.products.index');
    }
}
