<?php

namespace App\Livewire\Website;

use Livewire\Component;

class ContactUs extends Component
{
    public function render()
    {
        return view('livewire.website.contact-us')->layout('components.layouts.website', [
            'title' => 'Contact Us | NeutraDC',
            'description' => 'A Digital Ecosystem Hub for Unlimited Access to Indonesia’s Digital Economy.',
            'keywords' => 'NeutraDC, Contact Us, Data Center, Colocation, Cloud Computing, Managed Services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
