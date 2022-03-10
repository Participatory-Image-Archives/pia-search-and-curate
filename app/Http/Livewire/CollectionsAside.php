<?php

namespace App\Http\Livewire;

use App\Models\Collection;
use Livewire\Component;

class CollectionsAside extends Component
{
    public function render()
    {
        return view('livewire.collections-aside', [
            'collections' => Collection::where('origin', 'pia')->latest()->take(20)->get(),
        ]);
    }
}
