<?php

namespace App\Livewire\Website\DataCenter;

use Livewire\Component;

class Batam extends Component
{
    public function render()
    {
        return view('livewire.website.data-center.batam')->layout('components.layouts.website', [
            'title' => 'Batam Data Center | NeutraDC',
            'description' => 'NeutraDC Has Most Reliable Network of Data Center in Indonesia.',
            'keywords' => 'Batam, NeutraDC, Data Center, Indonesia, Uptime Institute',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
