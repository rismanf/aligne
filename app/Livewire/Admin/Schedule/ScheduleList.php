<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\Classes;
use App\Models\GroupClass;
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
    public bool $deleteForm = false;

    public $schedule_data;
    public $selectedgroupclass;
    public $selectedDate;
    public $availableDates = [];

    public $all_schedule_times;

    public $class_level_id, $trainer_id, $class_id, $quota, $id;
    public $class_level = [
        ['id' => '1', 'name' => 'BEGINNER'],
        ['id' => '2', 'name' => 'INTERMEDIATE'],
        ['id' => '3', 'name' => 'ADVANCED'],
        ['id' => '4', 'name' => 'ALL LEVEL'],
        ['id' => '5', 'name' => 'YOGA'],
        ['id' => '6', 'name' => 'STRETCH YOGA'],
        ['id' => '7', 'name' => 'MAT PILATES'],
        ['id' => '8', 'name' => 'BARRE'],
        ['id' => '9', 'name' => 'AERIAL'],
        ['id' => '10', 'name' => 'DANCE / FUSION'],
    ];

    public $calases_group;
    public $calases;
    public $trainer_data;

    public function mount()
    {
        $this->calases_group = GroupClass::select('id', 'name')->get()->toArray();

        $this->selectedgroupclass = $this->calases_group[0]['id'];

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
            ->where('group_class_id', $this->selectedgroupclass)
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
        $quota = 8;
        if ($this->selectedgroupclass == 1) {
            $quota = 8;
        }
        if ($this->selectedgroupclass == 2) {
            $quota = 4;
        }
        if ($this->selectedgroupclass == 3) {
            $quota = 8;
        }

        Schedule::updateOrCreate(
            [
                'schedule_date' => $this->selectedDate,
                'group_class_id' => $this->selectedgroupclass,
                'group_class_name' => $this->calases_group[$this->selectedgroupclass - 1]['name'] ?? null,
                'time_id' => $this->id,
            ],
            [
                'trainer_id' => $this->trainer_id,
                'level_class_id' => $this->class_level_id,
                'level_class' => $this->class_level[$this->class_level_id - 1]['name'] ?? null,
                'class_id' => $this->class_id,
                'time' => ScheduleTime::find($this->id)?->time,
                'quota' => $quota,
            ]
        );

        $this->reset(['editForm', 'class_level_id', 'trainer_id', 'class_id', 'quota', 'id']);
        $this->toast('success', 'Schedule Updated');
    }

    public function showDeleteModal($timeId)
    {
        $this->id = $timeId;

        $this->deleteForm = true;
    }

    public function delete()
    {
        Schedule::where('time_id', $this->id)->delete();
        $this->reset(['deleteForm', 'id']);
        $this->toast('success', 'Schedule Deleted');
    }

    public function render()
    {
        $this->all_schedule_times = ScheduleTime::where('group_class_id', $this->selectedgroupclass)->get();

        $this->schedule_data = Schedule::with('trainer', 'classes')
            ->where('group_class_id', $this->selectedgroupclass)
            ->where('schedule_date', $this->selectedDate)
            ->get();
        // echo $this->schedule_data;
        // dd($this->schedule_data);
        $this->calases = Classes::select('id', 'name')->where('group_class_id', $this->selectedgroupclass)->get()->toArray();
        return view('livewire.admin.schedule.schedule-list', [
            'schedule_data' => $this->schedule_data,
            'all_schedule_times' => $this->all_schedule_times,
            'calases' => $this->calases,
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
