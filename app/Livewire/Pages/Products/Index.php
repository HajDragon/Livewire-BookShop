<?php

namespace App\Livewire\Pages\Products;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Products')]
class Index extends Component
{
    public string $pageMessage = "Browse all available products";

    public function render()
    {
        return view('livewire.pages.products.index');
    }
}
