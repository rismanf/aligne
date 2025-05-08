<?php

namespace App\Livewire\Website\DataCenter;

use Livewire\Component;

class JakartaHq extends Component
{
    public function render()
    {
        return view('livewire.website.data-center.jakarta-hq')->layout('components.layouts.website', [
            'title' => 'Jakarta HQ Data Center | NeutraDC',
            'description' => 'NeutraDC Has Most Reliable Network of Data Center in Indonesia.',
            'keywords' => 'Jakarta HQ, NeutraDC, Data Center, Indonesia, Uptime Institute',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
