<?php

use Livewire\Component;

new class extends Component
{



};
?>
<div class="hidden md:block">
    <div class="flex flex-wrap items-center gap-4">
        <flux:brand href="{{ route('home') }}" class="inline-flex transition-transform duration-300 ease-in-out hover:scale-120">
            <div class="inline-flex transition-transform duration-300 ease-in-out hover:scale-120">
                <img
                    src="{{ asset('storage/Logo/BookLogo.jpg') }}"
                    class="h-12 w-auto rounded-lg"
                    alt="Logo"
                >
            </div>
        </flux:brand>

        <flux:navbar>
            <flux:navbar.item wire:navigate href="{{ route('home') }}" icon="home">Home</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('cart') }}" icon="shopping-cart">Cart</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('search-books') }}" icon="puzzle-piece">Books</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('myorders') }}" icon="document-text">Orders</flux:navbar.item>
            <flux:navbar.item wire:navigate href="{{ route('policy') }}" icon="calendar">Policy</flux:navbar.item>
            @if (auth()->user()->is_admin)
                <flux:navbar.item wire:navigate href="{{ route('admin.dashboard') }}" icon="shield-check">Admin Panel</flux:navbar.item>
            @endif
        </flux:navbar>
        <flux:dropdown position="bottom" align="start">
            <flux:profile avatar="" />
            <flux:menu>
                <flux:menu.item
                    wire:navigate href="{{ route('profile.edit') }}" icon="user">
                    Profile Settings
                </flux:menu.item>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </div>
    <hr>

</div>

