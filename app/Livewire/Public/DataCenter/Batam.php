<?php

namespace App\Livewire\Public\DataCenter;

use App\Models\Menu;
use Livewire\Component;

class Batam extends Component
{
    public function render()
    {
        $menu = Menu::where('name', 'NeutraDC Batam')->first();

        return view('livewire.public.data-center.batam')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
