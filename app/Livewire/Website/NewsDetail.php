<?php

namespace App\Livewire\Website;

use Livewire\Component;

class NewsDetail extends Component
{
    public $id;

    // Akan dipanggil otomatis saat komponen diakses via route (karena pakai parameter route)
    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        return view('livewire.website.news-detail')->layout('components.layouts.website',[
            'title' => 'Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC
            Provides AI Technology Training for SMEs in Mandalika | NeutraDC',
            'description' => 'Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC Provides AI Technology Training for SMEs in Mandalika',
            'keywords' => 'NeutraDC, AI Technology, Training, SMEs, Mandalika',
            'image' => asset('images/website/news-detail.jpg'),
        ]);
    }
}
