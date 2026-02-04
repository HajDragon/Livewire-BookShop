<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>


    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <livewire:banner>
            Welcome back, {{ auth()->user()->name }}!
        </livewire:banner>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden" x-data x-bind:class="!localStorage.getItem('bannerClosed') ? 'mt-16' : 'mt-0'">

            <flux:spacer />


        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
