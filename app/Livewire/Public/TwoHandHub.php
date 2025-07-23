<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\News;
use Livewire\Component;

class TwoHandHub extends Component
{
    public $news;
    public function render()
    {
        $this->news= News::where('is_active', true)->orderby('updated_at', 'desc')->limit(2)->get();
        $menu = Menu::where('name', 'Two Hands Hub')->first();

        return view('livewire.public.two-hand-hub')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
