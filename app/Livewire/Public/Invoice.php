<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\UserProduk;
use Livewire\Component;

class Invoice extends Component
{
    public $invoice;

    public function mount($id)
    {
       $this->invoice = UserProduk::with('product')->where('invoice_number', $id)->where('user_id', auth()->id())->first();
    //    dd($this->invoice);
    }
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.invoice')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
