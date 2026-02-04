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
            <flux:navbar.item wire:navigate href="{{ route('products.index') }}" icon="puzzle-piece">Products</flux:navbar.item>
            <flux:navbar.item wire:navigate href="#" icon="currency-dollar">Pricing</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('profile.edit') }}" icon="user">About</flux:navbar.item>
        </flux:navbar>
    </div>
    <hr>

</div>

