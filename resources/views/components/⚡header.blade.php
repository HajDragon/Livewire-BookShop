<?php

use Livewire\Component;

new class extends Component
{



};
?>
<div class="hidden md:block">
    <div class="flex flex-wrap items-center gap-4">
        <flux:header class="text-3xl font-bold mb-1">{{ __('Dashboard') }}</flux:header>
        <flux:navbar>
            <flux:navbar.item wire:navigate href="{{ route('home') }}" icon="home">Home</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('cart') }}" icon="shopping-cart">Cart</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('search-books') }}" icon="puzzle-piece">Books</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('myorders') }}" icon="document-text">Orders</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('profile.edit') }}" icon="user">Profile</flux:navbar.item>
        </flux:navbar>
    </div>
    <hr>

</div>

