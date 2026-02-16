<?php

namespace App\Livewire\Pages;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Orders')]
class AdminOrders extends Component
{
    public Order $order;
    public string $status = '';
    public string $total = '0.00';
    public ?string $successMessage = null;
    public ?string $errorMessage = null;


    public function mount(Order $order): void
    {
        $this->authorize('view', $order);

        $this->order = $order->load('items.book', 'user');
        $this->status = $this->order->status?->value ?? OrderStatus::Pending->value;
        $this->total = number_format($this->order->total, 2, '.', '');
    }

    protected function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_map(fn($c) => $c->value, OrderStatus::cases()))],
            'total' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->order->status = OrderStatus::from($this->status);
        $this->order->total = $this->total;
        $this->order->save();

        $this->order->refresh();

            session()->flash('success', 'Order updated.');
            $this->successMessage = 'Order updated.';
            $this->errorMessage = null;
    }

    public function addItem(): void
    {
        // Add item functionality removed — manage items via separate workflow
        session()->flash('error', 'Adding items has been disabled.');
        $this->errorMessage = 'Adding items has been disabled.';
        $this->successMessage = null;
    }

    public function removeItem(int $itemId): void
    {
        $item = OrderItem::findOrFail($itemId);

        if ($item->order_id !== $this->order->id) {
            abort(404);
        }

        $item->delete();

        $this->order->refresh();
        $this->order->total = $this->order->items->sum(fn($i) => $i->price * $i->quantity);
        $this->order->save();

            session()->flash('success', 'Item removed.');
            $this->successMessage = 'Item removed.';
            $this->errorMessage = null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('pages.admin.orders', [
            'order' => $this->order,
        ]);
    }
}
