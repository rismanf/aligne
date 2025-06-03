<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.public.home')->layout('components.layouts.website',[
            'title' => 'NeutraDC | Your Digital Ecosystem Partner in Southeast Asia’s Fastest Growing Economy',
            'description' => 'Your Digital Ecosystem Partner in Southeast Asia’s Fastest Growing Economy',
            'keywords' => 'Main',
            'image' => asset('images/logo.png'),
        ]);
    }
}
