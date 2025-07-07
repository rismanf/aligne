<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\Product;
use Livewire\Component;
use Mary\Traits\Toast;

class Checkout extends Component
{
    use Toast;

    public $id, $product;
    public function mount($id)
    {
        $this->id = $id;
        $this->product = Product::find($id);
    }
    public function render()
    {

        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.checkout')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
