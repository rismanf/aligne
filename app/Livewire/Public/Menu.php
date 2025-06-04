<?php

namespace App\Livewire\Public;

use App\Models\Menu as ModelsMenu;
use Livewire\Component;

class Menu extends Component
{
    public $menus;

    public function mount()
    {
        $this->menus = ModelsMenu::whereNull('parent_id')
        ->where('is_active', true)
        ->with('children') // Ambil child-nya sekaligus
        ->orderBy('id')
        ->get();
    }
    public function render()
    {
        return view('livewire.public.menu');
    }
}
