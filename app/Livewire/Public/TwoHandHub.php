<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use Livewire\Component;

class TwoHandHub extends Component
{
    public function render()
    {
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
