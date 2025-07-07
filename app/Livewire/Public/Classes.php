<?php

namespace App\Livewire\Public;

use App\Models\Classes as ModelsClasses;
use App\Models\Menu;
use Livewire\Component;

class Classes extends Component
{
    public $title, $description, $keywords;
    public $classes;
    public function mount()
    {
        $this->classes = ModelsClasses::all();
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
