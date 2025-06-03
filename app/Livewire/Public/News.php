<?php

namespace App\Livewire\Public;

use Livewire\Component;

class News extends Component
{
    public function render()
    {
        return view('livewire.public.news')->layout('components.layouts.website', [
            'title' => 'News | NeutraDC',
            'description' => 'A Digital Ecosystem Hub for Unlimited Access to Indonesia’s Digital Economy.',
            'keywords' => 'NeutraDC, News, Data Center, Colocation, Cloud Computing, Managed Services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
