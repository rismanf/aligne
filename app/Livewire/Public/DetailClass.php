<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\Schedule;
use Carbon\Carbon;
use Livewire\Component;

class DetailClass extends Component
{
    public $id, $date, $schedule, $schedule_now;

    public function mount($id, $date)
    {
        $this->id = $id;
        $this->date = $date;

        $date = Carbon::parse($date);
        $today = Carbon::today();

        // Batasi range tanggal
        if ($date->lessThan($today->startOfWeek())) {
            $date = $today; // tidak boleh ke minggu sebelum ini
        }

        if ($date->greaterThan($today->copy()->addMonth()->endOfWeek())) {
            $date = $today->copy()->addMonth(); // tidak boleh lebih dari 1 bulan ke depan
        }

        $this->date = $date->toDateString();

        $start = $date->copy()->startOfWeek();
        $end = $date->copy()->endOfWeek();

        $this->schedule = Schedule::where('class_id', $id)
            ->whereBetween('schedule_date', [$start, $end])
            ->get();

        $this->schedule_now = Schedule::with('trainer')
            ->where('class_id', $id)
            ->whereDate('schedule_date', $this->date)
            ->get();
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
