<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\News;
use Livewire\Component;

class AboutUs extends Component
{
    public $news = null;
    public function render()
    {
        $this->news = News::where('is_active', true)
            ->orderby('updated_at', 'desc')
            ->limit(3)
            ->get();

        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.about-us')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
