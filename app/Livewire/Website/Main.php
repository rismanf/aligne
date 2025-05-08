<?php

namespace App\Livewire\Website;

use Livewire\Component;

class Main extends Component
{
    public function render()
    {
        return view('livewire.website.main.index')->layout('components.layouts.website',[
            'title' => 'NeutraDC | Your Digital Ecosystem Partner in Southeast Asia’s Fastest Growing Economy',
            'description' => 'Your Digital Ecosystem Partner in Southeast Asia’s Fastest Growing Economy',
            'keywords' => 'Main',
            'image' => asset('images/logo.png'),
        ]);
    }
}
