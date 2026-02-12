<div>
    <flux:heading size="xl" class="mb-6">My Library</flux:heading>

    @if(session()->has('message'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if($books->isEmpty())
        <flux:card class="p-12 text-center">
            <flux:heading size="lg" class="mb-4">No books in your library</flux:heading>
            <flux:text class="mb-6">Add books to your library from the search page.</flux:text>
            <flux:button wire:navigate href="{{ route('search-books') }}">Search Books</flux:button>
        </flux:card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
                <flux:card class="p-6 flex flex-col gap-4">
                    <div class="flex gap-4">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->name }}" class="w-24 h-32 object-cover rounded" />
                        <div class="flex-1">
                            <flux:heading size="md">{{ $book->name }}</flux:heading>
                            <flux:text class="text-sm">by {{ $book->author }}</flux:text>
                            <flux:text class="text-xs text-gray-500">ISBN: {{ $book->isbn }}</flux:text>
                            <flux:text class="text-xs text-gray-500">Publisher: {{ $book->publisher }}</flux:text>
                            <flux:text class="text-xs text-gray-500">Year: {{ $book->publish_year }}</flux:text>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <flux:button wire:click="removeBook({{ $book->id }})" variant="danger">Remove</flux:button>
                    </div>
                </flux:card>
            @endforeach
        </div>
    @endif
</div>
