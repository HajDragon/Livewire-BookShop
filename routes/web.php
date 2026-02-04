<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified']);


Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('/products', \App\Livewire\Pages\Products\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('products.index');

require __DIR__.'/settings.php';
