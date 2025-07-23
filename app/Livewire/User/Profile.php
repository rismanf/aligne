<?php

namespace App\Livewire\User;

use App\Models\Menu;
use App\Models\User;
use App\Models\UserKuota;
use App\Models\UserProduk;
use Livewire\Component;

class Profile extends Component
{
    public $user;
    public $member;
    public function mount()
    {
        $this->user = auth()->user();
        $this->member = UserKuota::with('product')->where('user_id', $this->user->id)->where('is_active', 1)->get();
        dd($this->member);
    }
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
