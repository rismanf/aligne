<?php

namespace App\Livewire\Public;

use App\Models\GroupClass;
use App\Models\Menu;
use Livewire\Component;

class Classes extends Component
{
    public $title, $description, $keywords;
    public $classes;
    public function mount()
    {
        $this->classes = GroupClass::all();
    }
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.classes')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
