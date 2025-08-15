<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\ClassSchedules;
use App\Models\GroupClass;
use Carbon\Carbon;
use Livewire\Component;

class ScheduleMonitoring extends Component
{
    public $scheduleDate;
    public $schedules;
    public $classesGroup;
    public $selectedgroupclass;

    public function mount()
    {
        // Set default date filters

        $this->scheduleDate = Carbon::now()->format('Y-m-d');

        $this->classesGroup = GroupClass::select('id', 'name')->get()->toArray();

        $this->selectedgroupclass = $this->classesGroup[0]['id'] ?? 1;

        $this->loadScheduleStatistics();
    }

    private function loadScheduleStatistics()
    {
        $this->schedules = ClassSchedules::with('trainer', 'classes')
            ->whereHas('classes', function ($query) {
                $query->where('group_class_id', $this->selectedgroupclass);
            })
            ->whereDate('start_time', $this->scheduleDate)
            ->orderby('start_time', 'asc')
            ->get();

        // dd($schedules);
    }


    public function updatedselectedgroupclass()
    {
        $this->loadScheduleStatistics();
    }

    public function updatedscheduleDate()
    {
        $this->loadScheduleStatistics();
    }

    public function render()
    {
        $breadcrumbs = [
            [
                'link' => route("admin.home"),
                'label' => 'Home',
                'icon' => 's-home',
            ],
            [
                'label' => 'Schedule Monitoring',
            ],
        ];

        return view('livewire.admin.schedule.schedule-monitoring')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Schedule Monitoring',
        ]);
    }
}
