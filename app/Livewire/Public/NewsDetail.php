<?php

namespace App\Livewire\Public;

use Livewire\Component;

class NewsDetail extends Component
{
    public function render()
    {
        return view('livewire.public.news-detail')->layout('components.layouts.website',[
            'title' => 'Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC
            Provides AI Technology Training for SMEs in Mandalika | NeutraDC',
            'description' => 'Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC Provides AI Technology Training for SMEs in Mandalika',
            'keywords' => 'NeutraDC, AI Technology, Training, SMEs, Mandalika',
            'image' => asset('images/website/news-detail.jpg'),
        ]);
    }
}
