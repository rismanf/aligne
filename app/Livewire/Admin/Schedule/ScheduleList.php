<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\Classes;
use App\Models\GroupClass;
use App\Models\ClassSchedules;
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
    public bool $copyScheduleModal = false;

    public $schedule_data;
    public $selectedgroupclass;
    public $selectedDate;
    public $availableDates = [];
    public $copyFromDate;
    public $copyToDate;

    public $name, $start_time, $end_time, $trainer_id, $class_id, $capacity, $duration, $id;
    public $class_level = [
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
    ];

    public $calases_group;
    public $calases;
    public $trainer_data;

    public function mount()
    {
        $this->calases_group = GroupClass::select('id', 'name')->get()->toArray();

        $this->selectedgroupclass = $this->calases_group[0]['id'] ?? 1;

        $this->trainer_data = Trainer::select('id', 'name')->orderby('name')->get()->toArray();

        for ($i = 0; $i < 14; $i++) {
            $this->availableDates[] = Carbon::today()->addDays($i)->format('Y-m-d');
        }

        $this->selectedDate = $this->availableDates[0] ?? now()->format('Y-m-d');
    }

    public function classChanged()
    {
        $this->reset(['editForm', 'start_time', 'end_time', 'trainer_id', 'class_id', 'capacity', 'id']);
    }

    public function showEditModal($scheduleId = null)
    {
        $this->id = $scheduleId;

        if ($scheduleId) {
            $schedule = ClassSchedules::find($scheduleId);

            if ($schedule) {
                $this->name = $schedule->name;
                $this->start_time = $schedule->start_time->format('H:i');
                $this->end_time = $schedule->end_time->format('H:i');
                $this->class_id = $schedule->class_id;
                $this->trainer_id = $schedule->trainer_id;
                $this->capacity = $schedule->capacity;
            }
        } else {
            $this->reset(['name', 'start_time', 'end_time', 'trainer_id', 'class_id', 'capacity']);
        }

        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            // 'name' => 'required|string|max:200',
            // 'start_time' => 'required',
            // 'end_time' => 'required',
            'class_id' => 'required',
            'trainer_id' => 'required',
            'capacity' => 'required|integer|min:1',
        ]);

        // Set default capacity based on group class if not provided
        if (!$this->capacity) {
            if ($this->selectedgroupclass == 1) { // REFORMER
                $this->capacity = 8;
            } elseif ($this->selectedgroupclass == 2) { // CHAIR
                $this->capacity = 4;
            } else { // FUNCTIONAL
                $this->capacity = 6;
            }
        }

        $startDateTime = Carbon::parse($this->selectedDate . ' ' . $this->start_time);
        $endDateTime = Carbon::parse($this->selectedDate . ' ' . $this->end_time);
        $duration = $startDateTime->diffInMinutes($endDateTime);

        // Generate name if not provided
        // if (!$this->name) {
        $class = Classes::find($this->class_id);
        $this->name = $class->name . ' - ' . $startDateTime->format('H:i');
        // }

        if ($this->id) {
            // Update existing schedule
            $schedule = ClassSchedules::find($this->id);
            $schedule->update([
                'name' => $this->name,
                'class_id' => $this->class_id,
                'trainer_id' => $this->trainer_id,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'duration' => $duration,
                'capacity' => $this->capacity,
                'is_active' => true,
            ]);
        } else {
            // Create new schedule
            ClassSchedules::create([
                'name' => $this->name,
                'class_id' => $this->class_id,
                'trainer_id' => $this->trainer_id,
                'date' => $this->selectedDate,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'duration' => $duration,
                'capacity' => $this->capacity,
                'capacity_book' => 0,
                'is_active' => true,
            ]);
        }

        $this->reset(['editForm', 'name', 'start_time', 'end_time', 'trainer_id', 'class_id', 'capacity', 'id']);
        $this->toast('success', 'Schedule Updated');
    }

    public function showDeleteModal($scheduleId)
    {
        $this->id = $scheduleId;
        $this->deleteForm = true;
    }

    public function delete()
    {
        ClassSchedules::find($this->id)->delete();
        $this->reset(['deleteForm', 'id']);
        $this->toast('success', 'Schedule Deleted');
    }

    public function addScheduleAtTime($startTime, $endTime)
    {
        $this->reset(['name', 'trainer_id', 'class_id', 'capacity', 'id']);
        $this->start_time = $startTime;
        $this->end_time = $endTime;
        $this->editForm = true;
    }

    public function showCopyScheduleModal()
    {
        $this->copyFromDate = $this->selectedDate;
        $this->copyToDate = '';
        $this->copyScheduleModal = true;
    }

    public function copySchedule()
    {
        $this->validate([
            'copyFromDate' => 'required|date',
            'copyToDate' => 'required|date|after_or_equal:today',
        ]);

        // Get schedules from the source date
        $sourceSchedules = ClassSchedules::with('trainer', 'classes')
            ->whereHas('classes', function ($query) {
                $query->where('group_class_id', $this->selectedgroupclass);
            })
            ->whereDate('start_time', $this->copyFromDate)
            ->get();

        if ($sourceSchedules->isEmpty()) {
            $this->toast('warning', 'No schedules found on the selected date');
            return;
        }

        // Check if target date already has schedules
        $existingSchedules = ClassSchedules::whereHas('classes', function ($query) {
            $query->where('group_class_id', $this->selectedgroupclass);
        })
            ->whereDate('start_time', $this->copyToDate)
            ->count();

        if ($existingSchedules > 0) {
            $this->toast('warning', 'Target date already has schedules. Please choose a different date.');
            return;
        }

        $copiedCount = 0;
        foreach ($sourceSchedules as $schedule) {
            // Calculate new datetime for target date
            $originalDateTime = Carbon::parse($schedule->start_time);
            $newStartTime = Carbon::parse($this->copyToDate)
                ->setTime($originalDateTime->hour, $originalDateTime->minute, $originalDateTime->second);

            $originalEndDateTime = Carbon::parse($schedule->end_time);
            $newEndTime = Carbon::parse($this->copyToDate)
                ->setTime($originalEndDateTime->hour, $originalEndDateTime->minute, $originalEndDateTime->second);

            // Create new schedule
            ClassSchedules::create([
                'name' => $schedule->name,
                'class_id' => $schedule->class_id,
                'trainer_id' => $schedule->trainer_id,
                'date' => $this->copyToDate,
                'start_time' => $newStartTime,
                'end_time' => $newEndTime,
                'duration' => $schedule->duration,
                'capacity' => $schedule->capacity,
                'capacity_book' => 0,
                'is_active' => true,
            ]);

            $copiedCount++;
        }

        $this->copyScheduleModal = false;
        $this->toast('success', "Successfully copied {$copiedCount} schedules to " . Carbon::parse($this->copyToDate)->format('d M Y'));

        // Update selected date to show the copied schedules
        $this->selectedDate = $this->copyToDate;
    }

    public function render()
    {
        $title = 'Schedule Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"),
                'label' => 'Home',
                'icon' => 's-home',
            ],
            [
                'label' => 'Schedule',
            ],
        ];

        // Get schedules for selected group class and date
        $this->schedule_data = ClassSchedules::with('trainer', 'classes')
            ->whereHas('classes', function ($query) {
                $query->where('group_class_id', $this->selectedgroupclass);
            })
            ->whereDate('start_time', $this->selectedDate)
            ->orderBy('start_time')
            ->get();

        // Get classes for selected group
        $this->calases = Classes::select('id', 'name')
            ->where('group_class_id', $this->selectedgroupclass)
            ->orderBy('name')
            ->get()
            ->toArray();

        // Get time slots from ScheduleTime based on selected group class
        $scheduleTimeSlots = \App\Models\ScheduleTime::where('group_class_id', $this->selectedgroupclass)
            ->orderBy('time')
            ->get();

        $timeSlots = [];
        foreach ($scheduleTimeSlots as $slot) {
            $startTime = \Carbon\Carbon::parse($slot->time)->format('H:i');
            // Calculate end time (assuming 1 hour duration, can be customized)
            $endTime = \Carbon\Carbon::parse($slot->time)->addHour()->format('H:i');

            // Check if this time slot has a schedule
            $existingSchedule = $this->schedule_data->first(function ($schedule) use ($startTime) {
                return $schedule->start_time->format('H:i') === $startTime;
            });

            $timeSlots[] = [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'schedule' => $existingSchedule,
                'is_available' => !$existingSchedule,
                'slot_name' => $slot->name,
                'slot_id' => $slot->id
            ];
        }

        // If no schedule times found, fallback to default time slots
        if (empty($timeSlots)) {
            for ($hour = 8; $hour <= 19; $hour++) {
                $startTime = sprintf('%02d:00', $hour);
                $endTime = sprintf('%02d:00', $hour + 1);

                // Check if this time slot has a schedule
                $existingSchedule = $this->schedule_data->first(function ($schedule) use ($startTime) {
                    return $schedule->start_time->format('H:i') === $startTime;
                });

                $timeSlots[] = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'schedule' => $existingSchedule,
                    'is_available' => !$existingSchedule,
                    'slot_name' => $startTime . ' - ' . $endTime
                ];
            }
        }

        return view('livewire.admin.schedule.schedule-list', [
            'schedule_data' => $this->schedule_data,
            'calases' => $this->calases,
            'timeSlots' => $timeSlots,
            't_headers' => [],
            'schedules' => [],
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
