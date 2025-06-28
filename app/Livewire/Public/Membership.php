<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use Livewire\Component;

class Membership extends Component
{
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.membership')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
