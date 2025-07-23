<?php

namespace App\Livewire\User;

use App\Models\Menu;
use App\Models\UserSchedule;
use Livewire\Component;

class Booking extends Component
{
    public $userschedule;
    public $order_id;
    public function mount()
    {
        $this->userschedule = UserSchedule::with('schedule', 'user')->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function render()
    {

        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.user.booking')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }

    public function showModal($id)
    {
        $this->order_id = $id;
        $this->dispatch('open-payment-modal');
    }
}
