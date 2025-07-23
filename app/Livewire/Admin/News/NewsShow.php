<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;

class NewsShow extends Component
{
    public  $data_news = null,
        $news = null;

    public function mount($id)
    {
        $this->data_news = News::where('slug', $id)->first();
        $this->news = News::where('slug', '!=', $id)
            ->where('is_active', true)
            ->orderby('updated_at', 'desc')
            ->limit(3)
            ->get();
    }
    public function render()
    {
        return view('livewire.admin.news.news-show')->layout('components.layouts.website', [
            'title' => 'Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC
            Provides AI Technology Training for SMEs in Mandalika | NeutraDC',
            'description' => 'Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC Provides AI Technology Training for SMEs in Mandalika',
            'keywords' => 'NeutraDC, AI Technology, Training, SMEs, Mandalika',
            'image' => asset('images/website/news-detail.jpg'),
        ]);
    }
}
