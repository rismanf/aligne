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

    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;

    public $id, $name, $duration, $schedule_at, $kuota, $trainer;
    public $schedule_time = []; // waktu yang dipilih
    public $selectedClassId = null;
    public $schedule_times = [];

    public $schedule_data;
    public $selectedDate;
    public $availableDates = [];

    public $calases;
    public $trainer_data;
    public $all_schedule_times;

    public $scheduleInputs = [];
    public $scheduleData = [];
    public array $errorsPerTimeId = [];

    public $class_level = [
        ['id' => '1', 'name' => 'BEGINNER'],
        ['id' => '2', 'name' => 'INTERMEDIATE'],
        ['id' => '3', 'name' => 'ADVANCED'],
        ['id' => '4', 'name' => 'ALL LEVEL'],
    ];

    public $class_level_id, $trainer_id, $class_id, $quota;
    public function mount()
    {
        $this->calases = Classes::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        })->toArray();


        $this->trainer_data = Trainer::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        })->toArray();


        for ($i = 0; $i < 14; $i++) {
            $this->availableDates[] = Carbon::today()->addDays($i)->format('Y-m-d');
        }

        $this->selectedDate = $this->availableDates[0];



        $this->initScheduleInputs();
    }

    public function updatedSelectedDate()
    {
        $this->initScheduleInputs();
    }

    public function initScheduleInputs()
    {
        foreach ($this->all_schedule_times as $time) {
            $this->scheduleInputs[$time->time] = [
                'class_id' => null,
                'trainer_id' => null,
            ];
        }

        // Optional: Prefill jika data sudah ada di DB
        $existing = Schedule::where('schedule_date', $this->selectedDate)->get();
        foreach ($existing as $item) {
            if (isset($this->scheduleInputs[$item->time])) {
                $this->scheduleInputs[$item->time] = [
                    'class_id' => $item->class_id,
                    'trainer_id' => $item->trainer_id,
                ];
            }
        }
    }

    public function saveSchedule($timeId)
    {

        $this->resetErrorBag(); // optional
        unset($this->errorsPerTimeId[$timeId]); // clear error lama

        $data = $this->scheduleData[$timeId] ?? [];

        // if (empty($data['class_level_id']) || empty($data['class_id']) || empty($data['trainer_id']) || empty($data['quota'])) {
        //     $this->scheduleData[$timeId] = 'Data Belum diisi.';
        //     return;
        // }

        // Validasi opsional
        if (!isset($data['class_level_id'])) {
            $this->errorsPerTimeId[$timeId] = 'Level class harus dipilih.';
            return;
        }
        if (!isset($data['class_id'])) {
            $this->errorsPerTimeId[$timeId] = 'Class harus dipilih.';
            return;
        }
        // if (!isset($data['trainer_id'])) {
        //     $this->errorsPerTimeId[$timeId] = 'Trainer harus dipilih.';
        //     return;
        // }
        if (!isset($data['quota']) || $data['quota'] <= 0) {
            $this->errorsPerTimeId[$timeId] = 'Kuota harus diisi dan lebih dari 0.';
            return;
        }

        // Simpan ke database
        Schedule::updateOrCreate(
            [
                'schedule_date' => $this->selectedDate,
                'group_class_id' => 1,
                'group_class_name' => 'REFORMER CLASS',
                'time_id' => $timeId,
            ],
            [
                'schedule_date' => $this->selectedDate,
                'group_class_id' => 1,
                'group_class_name' => 'REFORMER CLASS',
                'trainer_id' => $timeId,
                'level_class_id' => $data['class_level_id'],
                'level_class' => $this->class_level[$data['class_level_id']]['name'] ?? null,
                'class_id' => $data['class_id'],
                'time_id' => $timeId,
                'time' => $this->all_schedule_times->firstWhere('id', $timeId)->time ?? null,
                'quota' => $data['quota'] ?? 1,
            ]
        );

        $this->toast(
            type: 'success',
            title: 'Schedule Updated',               // optional (text)
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }

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

        $this->all_schedule_times = ScheduleTime::all();
        $this->schedule_data = Schedule::with('trainer', 'classes')->where('schedule_date', $this->selectedDate)->get();

        return view('livewire.admin.schedule.schedule-list', [
            't_headers' => $t_headers,
            'schedules' => $data,
            'schedule_data' => $this->schedule_data,
            'all_schedule_times' => $this->all_schedule_times
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);

        return view('livewire.admin.schedule.schedule-list');
    }

    //Edit
    public function showEditModal($id)
    {
        $this->id = $id;
        $Schedule = Schedule::where('schedule_date', $this->selectedDate)->first();
        if (!$Schedule) {
            $this->class_level_id = $Schedule->class_level_id;
            $this->class_id = $Schedule->class_id;
            $this->trainer_id = $Schedule->trainer_id;
            $this->quota = $Schedule->quota;
        }

        // $this->photo = $trainer;
        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            'class_level_id' => 'required',
            'class_id' => 'required',
            'trainer_id' => 'required',
            'quota' => 'required',
        ]);

        Schedule::updateOrCreate(
            [
                'schedule_date' => $this->selectedDate,
                'group_class_id' => 1,
                'group_class_name' => 'REFORMER CLASS',
                'time_id' => $this->id,
            ],
            [
                'schedule_date' => $this->selectedDate,
                'group_class_id' => 1,
                'group_class_name' => 'REFORMER CLASS',
                'trainer_id' => $this->trainer_id,
                'level_class_id' => $this->class_level_id,
                'level_class' => $this->class_level[$this->class_level_id]->name ?? null,
                'class_id' => $this->class_id,
                'time_id' => $this->id,
                'time' => $this->all_schedule_times->firstWhere('id', $this->id)->time ?? null,
                'quota' => $this->quota
            ]
        );

        $this->reset();
        $this->editForm = false;
        $this->toast(
            type: 'success',
            title: 'Schedule Updated',               // optional (text)
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
