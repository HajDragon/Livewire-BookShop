<div x-data="{ showEdit: false }">
    <livewire:header />
    <livewire:mobile-navbar>
        <div class="p-6 max-w-6xl mx-auto">
            <flux:button wire:navigate href="{{ route('admin.dashboard') }}">Go Back</flux:button>
            <flux:heading size="xl" class="mb-6">Order #{{ $order->id }}</flux:heading>

            <flux:card class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <flux:heading size="lg">Order #{{ $order->id }}</flux:heading>
                        <flux:text size="sm" class="text-gray-600">
                            {{ $order->created_at->format('M d, Y') }}
                        </flux:text>
                        <flux:button @click.prevent="showEdit = true" size="sm" class="mt-2">Edit Order</flux:button>
                    </div>
                    <div class="text-right">
                        <flux:text class="font-semibold text-green-600">{{ $order->status->label() }}</flux:text>
                        <flux:heading size="lg" class="text-gray-900 dark:text-white">
                            ${{ number_format($order->total, 2) }}
                        </flux:heading>
                    </div>
                </div>

                <flux:separator class="my-4" />

                <div class="space-y-4">
                    @forelse($order->items as $item)
                        <div class="flex gap-4">
                            {{-- Book Cover --}}
                            <div class="w-16 h-24 flex-shrink-0">
                                @if($item->book && $item->book->cover_url)
                                    <img
                                        src="{{ $item->book->cover_url }}"
                                        alt="{{ $item->book?->name ?? $item->book_name }}"
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
                                <flux:heading size="md">{{ $item->book?->name ?? $item->book_name }}</flux:heading>
                                <flux:text size="sm">by {{ $item->book?->author ?? $item->book_author }}</flux:text>
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
                    @empty
                        <flux:text class="text-gray-500">No items found for this order.</flux:text>
                    @endforelse
                </div>
            </flux:card>

            <!-- Inline Edit Modal -->
            <div x-cloak x-show="showEdit" x-transition class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/50" @click="showEdit = false"></div>

                <div class="relative w-full max-w-2xl mx-auto p-6">
                    <flux:card class="p-6">
                        <flux:heading size="lg" class="mb-4">Edit Order #{{ $order->id }}</flux:heading>

                        @if($successMessage)
                            <div class="mb-4">
                                <flux:callout variant="filled" class="bg-green-600" heading="{{ $successMessage }}" />
                            </div>
                        @endif

                        @if($errorMessage)
                            <div class="mb-4">
                                <flux:callout variant="danger" heading="{{ $errorMessage }}" />
                            </div>
                        @endif

                        <form wire:submit.prevent="save">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model.defer="status" class="mt-1 block w-full rounded border-gray-300">
                                    @foreach(\App\Enums\OrderStatus::cases() as $status)
                                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Items</label>
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-16 flex-shrink-0">
                                                    @if($item->book && $item->book->cover_url)
                                                        <img src="{{ $item->book->cover_url }}" alt="{{ $item->book?->name ?? $item->book_name }}" class="w-full h-full object-cover rounded" />
                                                    @else
                                                        <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                                            <flux:text class="text-gray-400 text-xs">No Cover</flux:text>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div>
                                                    <flux:heading size="sm">{{ $item->book?->name ?? $item->book_name }}</flux:heading>
                                                    <flux:text size="sm" class="text-gray-600">{{ $item->quantity }} × ${{ number_format($item->price, 2) }}</flux:text>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <flux:button type="button" wire:click.prevent="removeItem({{ $item->id }})" variant="danger" size="sm">Remove</flux:button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Add item removed: admin adds items via separate workflow -->

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Total</label>
                                <flux:input wire:model.defer="total" name="total" type="number" step="0.01" class="mt-1" />
                            </div>

                            <div class="flex items-center gap-3">
                                <flux:button type="submit">Save Changes</flux:button>

                                <flux:button @click.prevent="showEdit = false" variant="subtle">Cancel</flux:button>
                            </div>
                        </form>
                    </flux:card>
                </div>
            </div>
        </div>
    </livewire:mobile-navbar>
</div>
