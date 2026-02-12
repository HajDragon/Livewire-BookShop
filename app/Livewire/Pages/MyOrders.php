<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Orders')]
class MyOrders extends Component
{
    public function render()
    {
        $orders = auth()->user()->orders()->with('items.book')->get();

        return view('livewire.pages.my-orders', [
            'orders' => $orders,
        ]);
    }
}
