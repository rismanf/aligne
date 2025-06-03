<?php

namespace App\Livewire\Public;

use Livewire\Component;

class TwoHandHub extends Component
{
    public function render()
    {
        return view('livewire.public.two-hand-hub')->layout('components.layouts.website',[
            'title' => 'Two Hands Hub | NeutraDC',
            'description' => 'Two Hands Hub | NeutraDC',
            'keywords' => 'Two Hands Hub | NeutraDC',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
