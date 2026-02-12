<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified']);


Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('/Cart', \App\Livewire\Pages\Products\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('cart');

Route::livewire('/books/search', App\Livewire\Actions\Searchbooks::class)
    ->middleware('auth')
    ->name('search-books');

Route::get('/checkout/success', \App\Livewire\Pages\CheckoutSuccess::class)
    ->middleware(['auth', 'verified'])
    ->name('checkout.success');

Route::get('/myorders', \App\Livewire\Pages\MyOrders::class)
    ->middleware(['auth', 'verified'])
    ->name('myorders');

Route::post('/webhook/mollie', [App\Http\Controllers\MollieWebhookController::class, 'handle'])
    ->name('mollie.webhook');

require __DIR__.'/settings.php';
