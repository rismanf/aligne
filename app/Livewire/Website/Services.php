<?php

namespace App\Livewire\Website;

use Livewire\Component;

class Services extends Component
{
    public function render()
    {
        return view('livewire.website.services')->layout('components.layouts.website', [
            'title' => 'NeutraDC Services | NeutraDC',
            'description' => 'Our Data Center Are Managed by a Highly-Experienced Data Center Team.',
            'keywords' => 'NeutraDC, Services, Colocation, Cloud Computing, Managed Services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
