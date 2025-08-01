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
            'title' => $menu ? $menu->title : 'Classes',
            'description' => $menu ? $menu->description : 'Browse our fitness classes',
            'keywords' => $menu ? $menu->keywords : 'fitness, classes, reformer, chair, functional',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
