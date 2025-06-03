<?php

namespace App\Livewire\Public\DataCenter;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.public.data-center.home')->layout('components.layouts.website', [
            'title' => ' Data Center | NeutraDC',
            'description' => 'NeutraDC Has Most Reliable Network of Data Center in Indonesia.',
            'keywords' => 'Data Center Indonesia, NeutraDC, Data Center, Indonesia, Uptime Institute',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
