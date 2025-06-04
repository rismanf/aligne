<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use Livewire\Component;

class Service extends Component
{
    public function render()
    {
        $menu = Menu::where('name', 'Services')->first();

        return view('livewire.public.service')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
