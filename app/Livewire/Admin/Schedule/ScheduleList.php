<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\Classes;
use App\Models\Schedule;
use App\Models\ScheduleTime;
use App\Models\Trainer;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ScheduleList extends Component
{
    use Toast, WithPagination;

    public bool $editForm = false;

    public $schedule_data;
    public $selectedDate;
    public $availableDates = [];

    public $all_schedule_times;

    public $class_level_id, $trainer_id, $class_id, $quota, $id;
    public $class_level = [
        ['id' => '1', 'name' => 'BEGINNER'],
        ['id' => '2', 'name' => 'INTERMEDIATE'],
        ['id' => '3', 'name' => 'ADVANCED'],
        ['id' => '4', 'name' => 'ALL LEVEL'],
    ];

    public $calases;
    public $trainer_data;

    public function mount()
    {
        $this->calases = Classes::select('id', 'name')->get()->toArray();
        $this->trainer_data = Trainer::select('id', 'name')->get()->toArray();

        for ($i = 0; $i < 14; $i++) {
            $this->availableDates[] = Carbon::today()->addDays($i)->format('Y-m-d');
        }

        $this->selectedDate = $this->availableDates[0] ?? now()->format('Y-m-d');
    }

    public function classChanged()
    {
        $this->reset(['editForm', 'class_level_id', 'trainer_id', 'class_id', 'quota', 'id']);
    }

    public function showEditModal($timeId)
    {
        $this->id = $timeId;

        $schedule = Schedule::where('schedule_date', $this->selectedDate)
            ->where('time_id', $timeId)
            ->first();

        if ($schedule) {
            $this->class_level_id = $schedule->level_class_id;
            $this->class_id = $schedule->class_id;
            $this->trainer_id = $schedule->trainer_id;
            $this->quota = $schedule->quota;
        } else {
            $this->reset(['class_level_id', 'trainer_id', 'class_id', 'quota']);
        }

        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            'class_level_id' => 'required',
            'class_id' => 'required',
            'trainer_id' => 'required',
            'quota' => 'required|integer|min:1',
        ]);

        Schedule::updateOrCreate(
            [
                'schedule_date' => $this->selectedDate,
                'group_class_id' => 1,
                'group_class_name' => 'REFORMER CLASS',
                'time_id' => $this->id,
            ],
            [
                'trainer_id' => $this->trainer_id,
                'level_class_id' => $this->class_level_id,
                'level_class' => $this->class_level[$this->class_level_id - 1]['name'] ?? null,
                'class_id' => $this->class_id,
                'time' => ScheduleTime::find($this->id)?->time,
                'quota' => $this->quota,
            ]
        );

        $this->reset(['editForm', 'class_level_id', 'trainer_id', 'class_id', 'quota', 'id']);
        $this->toast('success', 'Schedule Updated');
    }

    public function render()
    {
        $this->all_schedule_times = ScheduleTime::all();
        $this->schedule_data = Schedule::with('trainer', 'classes')
            ->where('schedule_date', $this->selectedDate)
            ->get();

        return view('livewire.admin.schedule.schedule-list', [
            'schedule_data' => $this->schedule_data,
            'all_schedule_times' => $this->all_schedule_times,
            't_headers' => [], // kosongkan jika tak dipakai
            'schedules' => [],
        ])->layout('components.layouts.app', [
            'breadcrumbs' => [
                ['link' => route("admin.home"), 'label' => 'Home', 'icon' => 's-home'],
                ['label' => 'Schedule'],
            ],
            'title' => 'Schedule Management',
        ]);
    }
}
