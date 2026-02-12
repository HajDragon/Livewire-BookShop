<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Policy')]

class Policy extends Component
{
    
    public function render()
    {
        return view('livewire.pages.policy');
    }
}
