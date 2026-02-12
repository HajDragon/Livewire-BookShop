<?php

namespace App\Livewire\Pages;

use App\Models\book;
use Livewire\Component;

class MyLibrary extends Component
{
    public $books = [];

    public function mount(): void
    {
        $this->books = book::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function removeBook(int $bookId): void
    {
        $book = book::where('id', $bookId)->where('user_id', auth()->id())->first();
        if ($book) {
            $book->delete();
            session()->flash('message', 'Book removed from your library.');
            $this->mount();
        }
    }

    public function render()
    {
        return view('livewire.pages.my-library');
    }
}
