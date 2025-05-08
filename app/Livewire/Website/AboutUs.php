<?php

namespace App\Livewire\Website;

use Livewire\Component;

class AboutUs extends Component
{
    public function render()
    {
        return view('livewire.website.about-us')->layout('components.layouts.website', [
            'title' => 'About Us | NeutraDC',
            'description' => 'A Digital Ecosystem Hub for Unlimited Access to Indonesia’s Digital Economy.',
            'keywords' => 'NeutraDC, About Us, Data Center, Colocation, Cloud Computing, Managed Services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
