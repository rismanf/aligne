<?php

namespace App\Livewire\User;

use App\Models\Menu;
use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
         $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.user.profile')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);

    }
}
