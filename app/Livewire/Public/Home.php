<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $menu = Menu::where('name', 'Home')->first();
        
        return view('livewire.public.home')->layout('components.layouts.website',[
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
        ]);
    }
}
