<x-layouts::app :title="__('Dashboard')">

    <flux:header class="text-3xl font-bold">{{ __('Dashboard') }}</flux:header>
    <flux:text class="text-2xl">{{ __('Welcome to the dashboard !') }}</flux:text>

    <div class="grid grid-cols-2 gap-4">
        <flux:card class="p-6">
            <flux:heading size="lg" class="mb-4">{{ __('Card Title') }}</flux:heading>
            <flux:text>{{ __('This is a sample card body.') }}</flux:text>
        </flux:card>

    </div>

</x-layouts::app>
