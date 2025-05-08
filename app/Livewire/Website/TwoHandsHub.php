<?php

namespace App\Livewire\Website;

use Livewire\Component;

class TwoHandsHub extends Component
{
    public function render()
    {
        return view('livewire.website.two-hands-hub')->layout('components.layouts.website',[
            'title' => 'Two Hands Hub | NeutraDC',
            'description' => 'Two Hands Hub | NeutraDC',
            'keywords' => 'Two Hands Hub | NeutraDC',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
