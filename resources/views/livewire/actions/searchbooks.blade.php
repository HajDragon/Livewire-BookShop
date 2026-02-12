<div>
    <livewire:mobile-navbar>
    <livewire:header/>

    <div class="p-6">
    <flux:heading size="xl" class="mb-6">Search Books</flux:heading>

    {{-- Search Input --}}
    <div class="mb-6">
        <flux:input
            wire:model.live.debounce.500ms="query"
            placeholder="Search for books (min 3 characters)..."
            class="w-full"
        />
        <flux:text size="sm" class="mt-2 text-gray-500">
            Search by title, author, or ISBN from OpenLibrary
        </flux:text>
    </div>

    {{-- Loading State --}}
    @if($searching)
        <div class="text-center py-12">
            <flux:text>Searching...</flux:text>
        </div>
    @endif

    {{-- Results Grid --}}
    @if(!empty($results) && !$searching)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($results as $book)
                <div class="border rounded-lg p-4 hover:shadow-lg transition">
                    {{-- Cover Image --}}
                    <div class="w-full h-64 rounded mb-4 flex items-center justify-center overflow-visible">
                        @if($book['cover_url'])
                            <img
                                src="{{ $book['cover_url'] }}"
                                alt="{{ $book['title'] }}"
                                class="rounded-2xl max-w-full h-64 object-contain hover:scale-130 transition-transform duration-300 ease-in-out"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div style="display:none;" class="w-full h-full flex items-center justify-center bg-gray-200">
                                <flux:text class="text-gray-400">No Cover</flux:text>
                            </div>
                        @else
                            <flux:text class="text-gray-400">No Cover</flux:text>
                        @endif
                    </div>

                    {{-- Book Info --}}
                    <flux:heading size="lg" class="mb-2">{{ $book['title'] }}</flux:heading>
                    <flux:text class="mb-2">by {{ $book['author'] }}</flux:text>

                    <div class="text-sm text-black dark:text-white space-y-1 mb-4">
                        @if($book['publish_year'])
                            <div>Year: {{ $book['publish_year'] }}</div>
                        @endif
                        @if($book['publisher'])
                            <div>Publisher: {{ $book['publisher'] }}</div>
                        @endif
                        @if($book['pages'])
                            <div>Pages: {{ $book['pages'] }}</div>
                        @endif
                        @if($book['isbn'])
                            <div class="text-xs">ISBN: {{ $book['isbn'] }}</div>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="mb-4">
                        <flux:heading size="lg" class="text-green-600 dark:text-green-400">
                            $12.99
                        </flux:heading>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        <flux:button
                            wire:click="addToCart({{ json_encode($book) }})"
                            variant="primary"
                            class="flex-1"
                        >
                            Add to Cart
                        </flux:button>
                        <flux:button
                            wire:click="saveBook({{ json_encode($book) }})"
                            variant="ghost"
                            class="flex-1"
                        >
                            Save to Library
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Empty State --}}
    @if(empty($results) && strlen($query) >= 3 && !$searching)
        <div class="text-center py-12">
            <flux:text class="text-gray-500">No books found. Try a different search.</flux:text>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if(session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg">
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg">
            {{ session('error') }}
        </div>
    @endif
    </div>
    </livewire:mobile-navbar>
</div>
