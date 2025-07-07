<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\Schedule;
use Livewire\Component;

class DetailClass extends Component
{
    public $id, $date, $schedule, $schedule_now;

    public function mount($id, $date)
    {
        $this->id = $id;
        $this->date = $date;

        $start = now()->startOfDay();
        $end = now()->endOfWeek()->endOfDay();

        $this->schedule = Schedule::where('class_id', $id)->whereBetween('schedule_at', [$start, $end])->get();
        $this->schedule_now = Schedule::with('trainer')->where('class_id', $id)->wheredate('schedule_at', $date)->get();
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
