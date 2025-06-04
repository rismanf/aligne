<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use Livewire\Component;

class NeutradcSummit extends Component
{
    public function render()
    {
        $menu = Menu::where('name', 'NeutraDC Summit')->first();

        return view('livewire.public.neutradc-summit')->layout('components.layouts.website',[
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
