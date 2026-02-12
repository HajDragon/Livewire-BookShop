<div class="p-6 max-w-4xl mx-auto">
    <livewire:header/>

    <div class="text-center py-12">
        @if($order)
            <div class="mb-6">
                <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <flux:heading size="xl" class="mb-4">Order Confirmed!</flux:heading>
            <flux:text class="mb-6">Thank you for your purchase. Your order has been successfully placed.</flux:text>

            <flux:card class="max-w-2xl mx-auto p-6 text-left">
                <flux:heading size="lg" class="mb-4">Order Details</flux:heading>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between">
                        <flux:text class="font-semibold">Order ID:</flux:text>
                        <flux:text>#{{ $order->id }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text class="font-semibold">Total:</flux:text>
                        <flux:text>${{ number_format($order->total, 2) }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text class="font-semibold">Status:</flux:text>
                        <flux:text class="text-green-600">{{ ucfirst($order->status) }}</flux:text>
                    </div>
                </div>

                <flux:separator class="my-4"/>

                <flux:heading size="md" class="mb-4">Items</flux:heading>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center">
                            <div>
                                <flux:text class="font-semibold">{{ $item->book_name }}</flux:text>
                                <flux:text size="sm" class="text-gray-600">by {{ $item->book_author }}</flux:text>
                                <flux:text size="sm" class="text-gray-500">Qty: {{ $item->quantity }}</flux:text>
                            </div>
                            <flux:text class="font-semibold">${{ number_format($item->price * $item->quantity, 2) }}</flux:text>
                        </div>
                    @endforeach
                </div>
            </flux:card>

            <div class="mt-8 flex gap-4 justify-center">
                <flux:button wire:navigate href="{{ route('myorders') }}" variant="primary">
                    View All Orders
                </flux:button>
                <flux:button wire:navigate href="{{ route('search-books') }}" variant="ghost">
                    Continue Shopping
                </flux:button>
            </div>
        @else
            <flux:heading size="lg" class="mb-4">Processing your order...</flux:heading>
            <flux:text>Please wait while we confirm your payment.</flux:text>
        @endif
    </div>
    </div>
</div>
