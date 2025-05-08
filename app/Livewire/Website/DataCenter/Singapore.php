<?php

namespace App\Livewire\Website\DataCenter;

use Livewire\Component;

class Singapore extends Component
{
    public function render()
    {
        return view('livewire.website.data-center.singapore')->layout('components.layouts.website', [
            'title' => 'Singapore Data Center | NeutraDC',
            'description' => 'NeutraDC Has Most Reliable Network of Data Center in Indonesia.',
            'keywords' => 'Singapore, NeutraDC, Data Center, Indonesia, Uptime Institute',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
