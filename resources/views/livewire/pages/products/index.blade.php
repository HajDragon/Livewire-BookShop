<div>
    <livewire:header/>
    <livewire:mobile-navbar>

    <div class="p-6 max-w-6xl mx-auto">
        <flux:heading size="xl" class="mb-6">Shopping Cart</flux:heading>

    @if($cart && $cart->items->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart->items as $item)
                    <flux:card class="p-4">
                        <div class="flex flex-col sm:flex-row gap-4 items-stretch">
                            {{-- Book Cover --}}
                            <div class="w-24 h-32 flex-shrink-0 mx-auto sm:mx-0">
                                @if($item->book->cover_url)
                                    <img
                                        src="{{ $item->book->cover_url }}"
                                        alt="{{ $item->book->name }}"
                                        class="w-full h-full object-cover rounded"
                                    >
                                @else
                                    <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                        <flux:text class="text-gray-400 text-xs">No Cover</flux:text>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <flux:heading size="lg" class="text-base sm:text-lg">{{ $item->book->name }}</flux:heading>
                                    <flux:text class="text-sm">by {{ $item->book->author }}</flux:text>
                                    <div class="mt-2">
                                        <flux:text class="text-sm font-semibold">${{ number_format($item->price, 2) }}</flux:text>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center gap-2 mt-4 w-full">
                                    <div class="flex items-center gap-2 w-full sm:w-auto">
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                        >-
                                        </flux:button>
                                        <flux:text class="px-4">{{ $item->quantity }}</flux:text>
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                        >+
                                        </flux:button>
                                    </div>
                                    <flux:button
                                        size="sm"
                                        variant="danger"
                                        wire:click="removeItem({{ $item->id }})"
                                        class="w-full sm:w-auto"
                                    >Remove
                                    </flux:button>
                                </div>
                            </div>

                            <div class="text-right flex items-end justify-end min-w-[80px]">
                                <flux:text class="font-semibold">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </flux:text>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <flux:card class="p-6 sticky top-6">
                    <flux:heading size="lg" class="mb-4">Order Summary</flux:heading>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <flux:text>Subtotal</flux:text>
                            <flux:text>${{ number_format($cart->total(), 2) }}</flux:text>
                        </div>
                        <div class="flex justify-between">
                            <flux:text>Tax</flux:text>
                            <flux:text>${{ number_format($tax, 2) }}</flux:text>
                        </div>
                        <flux:separator/>
                        <div class="flex justify-between">
                            <flux:heading size="lg">Total</flux:heading>
                            <flux:heading size="lg">${{ number_format($cart->totalWithTax($tax), 2) }}</flux:heading>
                        </div>
                    </div>

                    <flux:button
                        wire:click="checkout"
                        variant="primary"
                        class="w-full"
                    >
                        Proceed to Checkout
                    </flux:button>

                    <flux:text size="sm" class="mt-4 text-center text-gray-500">
                        Secure payment via Stripe
                    </flux:text>
                </flux:card>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <flux:card class="p-12 text-center">
            <flux:heading size="lg" class="mb-4">Your cart is empty</flux:heading>
            <flux:text class="mb-6">Start shopping to add items to your cart</flux:text>
            <flux:button wire:navigate href="{{ route('search-books') }}">
                Browse Books
            </flux:button>
        </flux:card>
    @endif

    {{-- Flash Messages --}}
    @if(session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
    </div>
    </livewire:mobile-navbar>
</div>
