<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use Livewire\Component;

class DetailClass extends Component
{
    public $id, $date;

    public function mount($id, $date)
    {
        $this->id = $id;
        $this->date = $date;
    }
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.detail-class')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
