<?php

use Illuminate\Support\Facades\Route;

Route::view('/home', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('home');

require __DIR__.'/settings.php';
