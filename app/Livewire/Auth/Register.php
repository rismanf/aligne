<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Register extends Component
{
    public function render()
    {
        return view('livewire.auth.register')->layout('components.layouts.website', [
            'title' => 'About Us | NeutraDC',
            'description' => 'A Digital Ecosystem Hub for Unlimited Access to Indonesia’s Digital Economy.',
            'keywords' => 'NeutraDC, About Us, Data Center, Colocation, Cloud Computing, Managed Services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
