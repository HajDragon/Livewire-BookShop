<?php

namespace App\Livewire\Actions;

use App\Models\book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\OpenLibraryService;
use Livewire\Component;

class Searchbooks extends Component
{
    public string $query = '';
    public array $results = [];
    public bool $searching = false;

    public function updatedQuery(): void
    {
        $this->search();
    }

    public function search(): void
    {
        $this->searching = true;

        if (strlen($this->query) < 3) {
            $this->results = [];
            $this->searching = false;
            return;
        }

        $service = new OpenLibraryService();
        $books = $service->search($this->query);

        \Log::info('Search results', ['query' => $this->query, 'count' => count($books), 'books' => $books]);

        $this->results = array_map(
            fn($book) => $service->transformBook($book),
            $books
        );

        $this->searching = false;
    }

    public function saveBook(array $bookData): void
    {
        // Check for duplicates by ISBN or OpenLibrary key
        $exists = book::query()
            ->when($bookData['isbn'], fn($q) => $q->where('isbn', $bookData['isbn']))
            ->when($bookData['openlibrary_key'], fn($q) => $q->orWhere('openlibrary_key', $bookData['openlibrary_key']))
            ->exists();

        if ($exists) {
            session()->flash('error', 'Book already exists in your library.');
            return;
        }

        book::create([
            'name' => $bookData['title'],
            'author' => $bookData['author'],
            'rating' => 0,
            'cover_url' => $bookData['cover_url'],
            'isbn' => $bookData['isbn'],
            'openlibrary_key' => $bookData['openlibrary_key'],
            'publisher' => $bookData['publisher'],
            'publish_year' => $bookData['publish_year'],
            'pages' => $bookData['pages'],
            'price' => $bookData['price'] ?? 12.99,
        ]);

        session()->flash('message', 'Book added successfully!');
        $this->dispatch('book-saved');
    }

    public function addToCart(array $bookData): void
    {
        // Find or create the book first
        $book = book::query()
            ->when($bookData['isbn'], fn($q) => $q->where('isbn', $bookData['isbn']))
            ->when($bookData['openlibrary_key'], fn($q) => $q->orWhere('openlibrary_key', $bookData['openlibrary_key']))
            ->first();

        if (!$book) {
            $book = book::create([
                'name' => $bookData['title'],
                'author' => $bookData['author'],
                'rating' => 0,
                'cover_url' => $bookData['cover_url'],
                'isbn' => $bookData['isbn'],
                'openlibrary_key' => $bookData['openlibrary_key'],
                'publisher' => $bookData['publisher'],
                'publish_year' => $bookData['publish_year'],
                'pages' => $bookData['pages'],
                'price' => $bookData['price'] ?? 12.99,
            ]);
        }

        // Get or create user's cart
        $cart = auth()->user()->cart ?? Cart::create(['user_id' => auth()->id()]);

        // Check if book is already in cart
        $cartItem = $cart->items()->where('book_id', $book->id)->first();

        if ($cartItem) {
            // Increment quantity
            $cartItem->increment('quantity');
            session()->flash('message', 'Quantity updated in cart!');
        } else {
            // Add new item to cart
            CartItem::create([
                'cart_id' => $cart->id,
                'book_id' => $book->id,
                'quantity' => 1,
                'price' => $book->price,
            ]);
            session()->flash('message', 'Book added to cart!');
        }

        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.actions.searchbooks');
    }
}
