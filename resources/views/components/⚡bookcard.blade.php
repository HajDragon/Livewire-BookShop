<?php

use Livewire\Component;
use App\Models\book;

new class extends Component
{
    public $quote = '"So many books, so little time." – Frank Zappa';
    public $username = "";
    public $title;
    public string $pageMessage = "Welcome to your book collection!";
    public bool $showDeleteModal = false;
    public ?int $pendingDeleteId = null;

    public function mount(){
        $this->username = session('username', 'Guest');
    }


    public function ShowDeleteBox(int $bookId): void{
        $this->pendingDeleteId = $bookId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->pendingDeleteId = null;
    }

    public function updatedUsername(){
        session(['username' => $this->username]);
    }

    public function with()
    {
        return [
            'books' => book::all()
        ];
    }

    public function delete(int $bookId): void
    {
        $book = book::find($bookId);

        if (!$book) {
            return;
        }

        $book->delete();
        $this->closeDeleteModal();
    }

};
?>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ">

    <div>{{ $title }}</div>
    <div class="col-span-1 sm:col-span-2">

</div>
    <div class="col-span-1 sm:col-span-2" id="books"></div>
    @foreach ($books as $book)
    <div wire:loading.remove wire:target="delete">
    <flux:card class="p-4 relative overflow-visible ">
            <span class="relative flex size-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>
            <span class="relative inline-flex size-3 rounded-full bg-sky-500"></span>
        </span>

             <flux:button class="size-12 sm:size-18 hover:scale-95  transition-transform duration-250 ease-in-out" wire:click="ShowDeleteBox({{ $book->id }})">Delete</flux:button>


        <div class="grid grid-cols-1 gap-2">
            <div class="overflow-visible mb-4 flex items-center justify-center rounded-2xl min-h-84">
                @if($book->cover_url)
                    <img
                    src="{{ $book->cover_url }}"
                    alt="Book Cover"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    class="rounded-2xl max-w-full h-84 object-contain hover:scale-130 transition-transform duration-300 ease-in-out"
                    />
                    <div style="display:none;" class="w-full h-84 flex items-center justify-center">
                        <flux:text class="text-gray-400">No Cover Available</flux:text>
                    </div>
                @else
                    <div class="w-full h-84 flex items-center justify-center">
                        <flux:text class="text-gray-400">No Cover Available</flux:text>
                    </div>
                @endif
            </div>
            {{-- <div class="overflow-visible mb-4 flex items-center justify-center">
                <img
                src="{{ $book['cover_url'] }}"
                alt="Book Cover"
                class="rounded-2xl max-w-full h-62 object-contain hover:scale-130 transition-transform duration-300 ease-in-out"
                />
            </div> --}}
        </div>

        <flux:heading size="lg" class="mb-2">{{ $book->name }}</flux:heading>
                <flux:text class="mb-2">by {{ $book->author }}</flux:text>

                <div class="text-sm text-black dark:text-white space-y-1 mb-4">
                    @if($book->publish_year)
                        <div>Year: {{ $book->publish_year }}</div>
                    @endif
                    @if($book->publisher)
                        <div>Publisher: {{ $book->publisher }}</div>
                    @endif
                    @if($book->pages)
                        <div>Pages: {{ $book->pages }}</div>
                    @endif
                    @if($book->isbn)
                        <div class="text-xs">ISBN: {{ $book->isbn }}</div>
                    @endif
                </div>
    </flux:card>
    </div>


    <div class="mt-10" wire:loading wire:target="delete">
        <div class="mx-auto w-full max-w-3xl h-80 rounded-2xl border border-blue-300 p-4">
        <div class="flex animate-pulse space-x-4 mt-2">
            <div class="size-10 rounded-full bg-gray-200"></div>
            <div class="flex-1 space-y-6 py-1">
                <div class="h-3 rounded bg-gray-200"></div>
                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-6 justify-items-center">
                        <div class="col-span-2 h-12 rounded bg-gray-200 w-full"></div>
                        <div class="col-span-1 h-6 rounded bg-gray-200 w-full"></div>
                        <div class="col-span-2 h-4 rounded bg-gray-200 w-full"></div>
                        <div class="col-span-1 h-4 rounded bg-gray-200 w-full"></div>
                        <div class="col-span-2 h-4 rounded bg-gray-200 w-full"></div>
                        <div class="col-span-1 h-4 rounded bg-gray-200 w-full"></div>
                        <div class="col-span-2 h-8 rounded bg-gray-200 w-full"></div>
                        <div class="col-span-1 h-8 rounded bg-gray-200 w-full"></div>
                    </div>
                    <div class="h-8 rounded bg-gray-200"></div>
                </div>
            </div>
        </div>
    </div>
</div>


    @endforeach

    <flux:modal
        name="confirm-book-deletion"
        wire:model="showDeleteModal"
        class="max-w-lg"
        backdrop-class="backdrop-blur-sm bg-black/40"
    >
        <div class="space-y-6">
            <div class="flex">
                <div class="flex-1">
                    <flux:heading size="lg">Are you sure?</flux:heading>

                    <flux:text class="mt-2">
                        This book will be deleted permanently.<br>
                        This action cannot be undone.
                    </flux:text>
                </div>

                <div class="-mx-2 -mt-2">

                </div>
            </div>

            <div class="flex gap-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="closeDeleteModal">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    variant="danger"
                    wire:click="delete({{ $pendingDeleteId ?? 0 }})"
                >
                    Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
