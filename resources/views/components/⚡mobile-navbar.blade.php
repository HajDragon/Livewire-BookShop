<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\book;

new class extends Component
{
    public $user;
    public $bookCount;
    public string $pageMessage = "";

    public function mount(String $pageMessage="")
    {
        $this->user = Auth::user();
        $this->bookCount = book::count();
        $this->pageMessage = $pageMessage;
    }




};
?>


<div class="md:hidden lg:hidden min-h-screen bg-white dark:bg-zinc-800 antialiased ">
    <flux:sidebar sticky collapsible="mobile" class="md:hidden lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="#"
                logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Acme Inc."
            />

            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.search placeholder="Search..." />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('home') }}" wire:navigate>Home</flux:sidebar.item>
            <flux:sidebar.item icon="inbox" badge='{{ $bookCount }}' href="{{ route('products.index') }}" wire:navigate>Products</flux:sidebar.item>
            <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>

            <flux:sidebar.group expandable heading="Favorites" class="grid">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile avatar="" name={{ auth()->user()->name }} />

            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>{{ auth()->user()->name }}</flux:menu.radio>
                    <flux:menu.radio></flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:header class="md:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" alignt="start">
            <flux:profile avatar="" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                <flux:avatar
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                />
                <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>

            <flux:menu.item :href="route('home')" icon="home" wire:navigate>
                {{ __('Home') }}
            </flux:menu.item>

            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <flux:main class="shadow-md">
        <div class="shadow-md p-4">

            <flux:heading  size="xl" level="1">Good afternoon, {{ auth()->user()->name }}</flux:heading>

            <flux:text class="mb-6 mt-2 text-base">{{ $pageMessage }}</flux:text>
        </div>

        {{ $slot }}

        <flux:separator variant="subtle" />
    </flux:main>

    @fluxScripts
</div>
