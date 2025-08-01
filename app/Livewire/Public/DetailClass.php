<?php

namespace App\Livewire\Public;

use App\Models\GroupClass;
use App\Models\Classes;
use App\Models\ClassSchedules;
use App\Models\Menu;
use Carbon\Carbon;
use Livewire\Component;

class DetailClass extends Component
{
    public $id, $date, $schedule, $schedule_now;
    public $class_name, $groupClass;
    
    public function mount($id, $date)
    {
        $this->id = $id;
        $this->date = $date;

        // Get group class information
        $this->groupClass = GroupClass::find($id);
        $this->class_name = $this->groupClass->name;
        
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

        // Get schedules for the week using ClassSchedules model
        $this->schedule = ClassSchedules::with(['classes', 'trainer'])
            ->whereHas('classes', function($query) {
                $query->where('group_class_id', $this->id);
            })
            ->whereBetween('start_time', [$start, $end->endOfDay()])
            ->get();

        // Get schedules for the specific date
        $this->schedule_now = ClassSchedules::with(['classes', 'trainer'])
            ->whereHas('classes', function($query) {
                $query->where('group_class_id', $this->id);
            })
            ->whereDate('start_time', $this->date)
            ->orderBy('start_time', 'asc')
            ->get();
    }
    
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.detail-class')->layout('components.layouts.website', [
            'title' => $menu->title ?? 'Class Detail',
            'description' => $menu->description ?? 'Class schedule details',
            'keywords' => $menu->keywords ?? 'fitness, class, schedule',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
