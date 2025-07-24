<?php

namespace App\Livewire\Public;

use App\Models\GroupClass;
use App\Models\Menu;
use App\Models\News;
use Livewire\Component;

class Home extends Component
{
    public $news = null;
    public $classes;
    public function mount()
    {
        $this->classes = GroupClass::all();
    }
    public function render()
    {
        $this->news = News::where('is_active', true)
            ->orderby('updated_at', 'desc')
            ->limit(3)
            ->get();

        $menu = Menu::where('name', 'Home')->first();

        return view('livewire.public.home')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
        ]);
    }
}
