<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Actions\Searchbooks;
use App\Livewire\Pages\CheckoutSuccess;
use App\Livewire\Pages\MyLibrary;
use App\Livewire\Pages\MyOrders;
use App\livewire\pages\Policy;
use App\Livewire\Pages\Products;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified']);

Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('/Cart', Products\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('cart');

Route::livewire('/books/search', Searchbooks::class)
    ->middleware('auth')
    ->name('search-books');

Route::get('/checkout/success', CheckoutSuccess::class)
    ->middleware(['auth', 'verified'])
    ->name('checkout.success');

Route::get('/myorders', MyOrders::class)
    ->middleware(['auth', 'verified'])
    ->name('myorders');

Route::get('/my-library', MyLibrary::class)
    ->middleware(['auth', 'verified'])
    ->name('my-library');

Route::get('/policy', Policy::class)
    ->name('policy');


Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
    route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/settings.php';
