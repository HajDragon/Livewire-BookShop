<div>
    <livewire:header/>
    <livewire:mobile-navbar>
    <div>
    <div class="p-6 max-w-6xl mx-auto">
        <flux:heading size="xl" class="mb-6">My Orders</flux:heading>

    @if($orders->isNotEmpty())
        <div class="space-y-6">
            @foreach($orders as $order)
                <flux:card class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <flux:heading size="lg">Order #{{ $order->id }}</flux:heading>
                            <flux:text size="sm" class="text-gray-600">
                                {{ $order->created_at->format('M d, Y') }}
                            </flux:text>
                        </div>
                        <div class="text-right">
                            <flux:text class="font-semibold text-green-600">{{ ucfirst($order->status) }}</flux:text>
                            <flux:heading size="lg" class="text-gray-900 dark:text-white">
                                ${{ number_format($order->total, 2) }}
                            </flux:heading>
                        </div>
                    </div>

                    <flux:separator class="my-4"/>

                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4">
                                {{-- Book Cover --}}
                                <div class="w-16 h-24 flex-shrink-0">
                                    @if($item->book && $item->book->cover_url)
                                        <img
                                            src="{{ $item->book->cover_url }}"
                                            alt="{{ $item->book_name }}"
                                            class="w-full h-full object-cover rounded"
                                        >
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                            <flux:text class="text-gray-400 text-xs">No Cover</flux:text>
                                        </div>
                                    @endif
                                </div>

                                {{-- Book Info --}}
                                <div class="flex-1">
                                    <flux:heading size="md">{{ $item->book_name }}</flux:heading>
                                    <flux:text size="sm">by {{ $item->book_author }}</flux:text>
                                    <flux:text size="sm" class="text-gray-600 mt-1">
                                        Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                    </flux:text>
                                </div>

                                {{-- Item Total --}}
                                <div class="text-right">
                                    <flux:text class="font-semibold">
                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                    </flux:text>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </flux:card>
            @endforeach
        </div>
    @else
        <flux:card class="p-12 text-center">
            <flux:heading size="lg" class="mb-4">No orders yet</flux:heading>
            <flux:text class="mb-6">Start shopping to place your first order</flux:text>
            <flux:button wire:navigate href="{{ route('search-books') }}">
                Browse Books
            </flux:button>
        </flux:card>
    @endif
    </div>
    </livewire:mobile-navbar>
</div>
