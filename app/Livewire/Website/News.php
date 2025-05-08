<?php

namespace App\Livewire\Website;

use Livewire\Component;

class News extends Component
{
    public function render()
    {
        return view('livewire.website.news')->layout('components.layouts.website', [
            'title' => 'News | NeutraDC',
            'description' => 'A Digital Ecosystem Hub for Unlimited Access to Indonesia’s Digital Economy.',
            'keywords' => 'NeutraDC, News, Data Center, Colocation, Cloud Computing, Managed Services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
