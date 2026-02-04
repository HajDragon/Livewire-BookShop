<?php

use Livewire\Component;
use App\Models\book;

new class extends Component
{
    public $quote = '"So many books, so little time." – Frank Zappa';
    public $username = "";
    public $title;
    public string $pageMessage = "Welcome to your book collection!";

    public function mount(){
        $this->username = session('username', 'Guest');
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

    public function delete(book $book)
    {
        sleep(1); // Remove this after testing
        echo "Deleting book with ID: " . $book->id . "\n";
        $book->delete();
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

        <div class="absolute top-2 right-2">
            <flux:button class="size-12 sm:size-18 hover:scale-95  transition-transform duration-250 ease-in-out" wire:click="delete({{ $book->id }})">Delete</flux:button>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div class="overflow-visible mb-4 flex items-center justify-center ">
                <img
                src="https://picsum.photos/200/300?random={{ $book->id }}"
                alt="Book Cover"
                class="rounded-2xl max-w-full h-62 object-contain hover:scale-130 transition-transform duration-300 ease-in-out"
                />
            </div>
            <div class="overflow-visible mb-4 flex items-center justify-center">
                <img
                src="https://picsum.photos/200/300?random={{ $book->id }}"
                alt="Book Cover"
                class="rounded-2xl max-w-full h-62 object-contain hover:scale-130 transition-transform duration-300 ease-in-out"
                />
            </div>
        </div>

        <flux:header class="text-sm mb-1 sm:text-xl">This book is called&nbsp;<span class="font-bold">{{ $book->name }}</span></flux:header>
        <hr>
        <flux:header class="text-sm sm:text-xl font-bold mb-1">Written by&nbsp;<span class="font-bold font-mono">{{ $book->author }}</span></flux:header>
        <flux:text class="text-base italic">"{{ $quote }}"</flux:text>
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
</div>
