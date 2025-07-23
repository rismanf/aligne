<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Whistleblower extends Component
{
    public function render()
    {
        return view('livewire.public.whistleblower')->layout('components.layouts.website', [
            'title' => 'Whistleblower | NeutraDC',
            'description' => 'NeutraDC is a leading data center provider offering colocation, cloud computing, and managed services in Indonesia and Southeast Asia.',
            'keywords' => 'whistleblower, neutradc,neutradc summit, data center, colocation, cloud computing, managed services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
