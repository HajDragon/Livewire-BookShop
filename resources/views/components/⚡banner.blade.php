<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div x-data="{ visible: !localStorage.getItem('bannerClosed') }">
    <div x-show="visible" x-cloak class="absolute h-24 top-0 left-0 right-0 w-full z-50">
        <div class="h-relative w-full">
            <div
                class="grid-rows-1 banner-inner h-full w-full bg-linear-to-r from-gray-700 to-gray-300 text-white px-4 py-3 shadow-md"
                role="alert"
                >
                <div class="flex items-center justify-start w-full">
                    <div class="flex-1 flex items-baseline gap-4">
                        <p class="font-aria text-xl sm:text-3xl text-left">
                            {{ $slot }}
                        </p>
                        <p class="ml-14 text-md">Enjoy exploring the world of books</p>
                    </div>
                    <button
                        @click="localStorage.setItem('bannerClosed', 'true'); visible = false"
                        type="button"
                        class="ml-4 text-black hover:text-gray-200 transition-colors p-4 border border-transparent hover:border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        aria-label="Close"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacer for fixed banner -->
    <div x-show="visible" class="h-16"></div>
</div>
