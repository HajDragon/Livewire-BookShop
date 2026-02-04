<?php

use Livewire\Component;

new class extends Component
{



};
?>
<div class="hidden md:block">
    <div class="flex flex-wrap items-center gap-4">

        <flux:header class="text-3xl font-bold mb-1">{{ __('Dashboard') }}</flux:header>
        <flux:link class="text-lg mb-1" href="#books">{{ __('Scroll To See Your Books') }}</flux:link>
        <flux:header class="text-lg mb-1">{{ __('Special Offers') }}</flux:header>
        <span class="flex ml-auto"><x-desktop-user-menu  :name="auth()->user()->name" /></span>
    </div>
    <hr>
    <flux:text class="text-2xl p-4">{{ __('Welcome to the dashboard !') }}</flux:text>

</div>
