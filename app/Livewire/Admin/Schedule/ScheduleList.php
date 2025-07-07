<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Trainer;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ScheduleList extends Component
{
    use Toast, WithPagination;

    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;

    public $id, $name, $duration, $schedule_at, $kuota, $trainer, $calases;


    public function render()
    {
        $title = 'Schedule Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Schedule',
            ],
        ];

        $data = Schedule::with('trainer', 'classes')->orderBy('created_at', 'desc')->paginate(5);

        $data->getCollection()->transform(function ($val, $index) use ($data) {
            $val->row_number = ($data->currentPage() - 1) * $data->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'schedule_at', 'label' => 'Date'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'duration', 'label' => 'Duration'],
            ['key' => 'kuota', 'label' => 'Kuota'],
            ['key' => 'trainer.name', 'label' => 'Trainer'],
            ['key' => 'classes.name', 'label' => 'Class'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        $trainer_data = Trainer::all();
        $calases_data = Classes::all();

        return view('livewire.admin.schedule.schedule-list', [
            't_headers' => $t_headers,
            'schedules' => $data,
            'trainer_data' => $trainer_data,
            'calases_data' => $calases_data,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);

        return view('livewire.admin.schedule.schedule-list');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'duration' => 'required',
            'schedule_at' => 'required',
            'kuota' => 'required',
            'trainer' => 'required',
            'calases' => 'required',
        ]);

        Schedule::create([
            'name' => $this->name,
            'duration' => $this->duration,
            'schedule_at' => $this->schedule_at,
            'kuota' => $this->kuota,
            'trainer_id' => $this->trainer,
            'class_id' => $this->calases,
        ]);

        $this->reset();
        $this->createForm = false;
        $this->toast(
            type: 'success',
            title: 'Schedule Added',               // optional (text)
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }

    //Edit
    public function showEditModal($id)
    {
        $Schedule = Schedule::where('id', $id)->firstOrFail();
        $this->id = $id;
        $this->name = $Schedule->name;
        $this->duration = $Schedule->duration;
        $this->schedule_at = $Schedule->schedule_at;
        $this->kuota = $Schedule->kuota;
        $this->trainer = $Schedule->trainer_id;
        $this->calases = $Schedule->class_id;
        // $this->photo = $trainer;
        $this->editForm = true;
    }
}
