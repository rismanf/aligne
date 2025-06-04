<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use Livewire\Component;

class ContactUs extends Component
{
    public function render()
    {
        $menu = Menu::where('name', 'Contact Us')->first();
        
        return view('livewire.public.contact-us')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
