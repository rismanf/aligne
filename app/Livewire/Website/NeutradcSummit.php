<?php

namespace App\Livewire\Website;

use Livewire\Component;

class NeutradcSummit extends Component
{
    public function render()
    {
        return view('livewire.website.neutradc-summit')->layout('components.layouts.website',[
            'title' => 'NeutraDC Summit | NeutraDC',
            'description' => 'Our sincerest gratitude to all partners and participants for making NeutraDC Summit 2024 truly spectacular!',
            'keywords' => 'Our sincerest gratitude to all partners and participants for making NeutraDC Summit 2024 truly spectacular!',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
