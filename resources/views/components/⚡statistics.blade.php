<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\book;
use App\Models\User;

new class extends Component
{
    public $user;
    public $bookCount;
    public $userCount;


    public function mount()
    {
        $this->user = Auth::user();
        $this->bookCount = book::count();
        $this->userCount = User::count();
    }
};
?>

<div>
    <flux:heading size="xl" class="mt-4">Statistics</flux:heading>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
        <flux:card class="p-4">
            <flux:heading size="lg" class="mb-2">Total Books</flux:heading>
            <flux:text class="text-3xl font-bold">{{ $bookCount }}</flux:text>
        </flux:card>

        <flux:card class="p-4">
            <flux:heading size="lg" class="mb-2">Total Users</flux:heading>
            <flux:text class="text-3xl font-bold">{{ $userCount }}</flux:text>
        </flux:card>
    </div>
</div>
