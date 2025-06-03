<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Service extends Component
{
    public function render()
    {
        return view('livewire.public.service')->layout('components.layouts.website', [
            'title' => 'NeutraDC Services | NeutraDC',
            'description' => 'Our Data Center Are Managed by a Highly-Experienced Data Center Team.',
            'keywords' => 'NeutraDC, Services, Colocation, Cloud Computing, Managed Services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
