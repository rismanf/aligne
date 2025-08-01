<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\ClassSchedules as ClassScheduleModel;
use App\Models\Classes;
use App\Models\GroupClass;
use App\Models\ClassBooking;
use App\Models\UserKuota;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClassSchedules extends Component
{
    public $selectedDate;
    public $selectedGroupClass = '';
    public $availableSchedules = [];
    public $userBookings = [];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadSchedules();
        $this->loadUserBookings();
    }

    public function updatedSelectedDate()
    {
        $this->loadSchedules();
    }

    public function updatedSelectedGroupClass()
    {
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $query = ClassScheduleModel::with(['classes.groupClass', 'trainer'])
            ->whereDate('date', $this->selectedDate)
            ->availableForBooking()
            ->orderBy('start_time');

        if ($this->selectedGroupClass) {
            $query->whereHas('classes', function($q) {
                $q->where('group_class', $this->selectedGroupClass);
            });
        }

        $this->availableSchedules = $query->get()->map(function($schedule) {
            return [
                'id' => $schedule->id,
                'class_name' => $schedule->classes->name,
                'group_class' => $schedule->classes->group_class,
                'level' => $schedule->classes->level_class,
                'trainer_name' => $schedule->trainer->name ?? 'TBA',
                'start_time' => $schedule->formatted_start_time,
                'end_time' => $schedule->formatted_end_time,
                'available_spots' => $schedule->available_spots,
                'max_participants' => $schedule->max_participants,
                'can_book' => $this->canUserBookClass($schedule),
                'user_has_quota' => $this->userHasQuota($schedule->classes->id),
                'booking_deadline' => Carbon::parse($schedule->date . ' ' . $schedule->start_time->format('H:i:s'))->subHour()->format('H:i'),
            ];
        })->toArray();
    }

    public function loadUserBookings()
    {
        if (!Auth::check()) {
            $this->userBookings = [];
            return;
        }

        $this->userBookings = ClassBooking::with(['classSchedule.classes', 'classSchedule.trainer'])
            ->where('user_id', Auth::id())
            ->whereHas('classSchedule', function($q) {
                $q->where('date', '>=', Carbon::today());
            })
            ->active()
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'class_name' => $booking->classSchedule->classes->name,
                    'group_class' => $booking->classSchedule->classes->group_class,
                    'trainer_name' => $booking->classSchedule->trainer->name ?? 'TBA',
                    'date' => $booking->classSchedule->date->format('Y-m-d'),
                    'start_time' => $booking->classSchedule->formatted_start_time,
                    'end_time' => $booking->classSchedule->formatted_end_time,
                    'can_cancel' => $booking->canBeCancelled(),
                    'time_until_class' => $booking->time_until_class,
                ];
            })->toArray();
    }

    public function bookClass($scheduleId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to book a class.');
            return redirect()->route('login');
        }

        try {
            $schedule = ClassScheduleModel::findOrFail($scheduleId);
            
            // Check if user can book this class
            if (!$this->canUserBookClass($schedule)) {
                session()->flash('error', 'You cannot book this class. Please check your quota or booking requirements.');
                return;
            }

            // Check if class can still be booked (1 hour rule)
            if (!$schedule->canBeBooked()) {
                session()->flash('error', 'This class cannot be booked. Booking closes 1 hour before class starts.');
                return;
            }

            // Check if class is full
            if ($schedule->isFull()) {
                session()->flash('error', 'This class is full.');
                return;
            }

            // Get user's active membership
            $userMembership = UserMembership::where('user_id', Auth::id())
                ->where('payment_status', 'paid')
                ->where('status', 'active')
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$userMembership) {
                session()->flash('error', 'You need an active membership to book classes.');
                return;
            }

            // Check and deduct quota
            $userQuota = UserKuota::where('user_id', Auth::id())
                ->where('class_id', $schedule->class_id)
                ->where('kuota', '>', 0)
                ->where('end_date', '>', Carbon::now())
                ->first();

            if (!$userQuota) {
                session()->flash('error', 'You do not have quota for this class type.');
                return;
            }

            // Create booking
            ClassBooking::create([
                'user_id' => Auth::id(),
                'user_membership_id' => $userMembership->id,
                'class_schedule_id' => $scheduleId,
                'booking_status' => 'confirmed',
                'booked_at' => Carbon::now(),
            ]);

            // Deduct quota
            $userQuota->decrement('kuota');

            // Increase participants count
            $schedule->increment('current_participants');

            session()->flash('success', 'Class booked successfully!');
            
            $this->loadSchedules();
            $this->loadUserBookings();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to book class: ' . $e->getMessage());
        }
    }

    public function cancelBooking($bookingId)
    {
        try {
            $booking = ClassBooking::findOrFail($bookingId);
            
            if ($booking->user_id !== Auth::id()) {
                session()->flash('error', 'You can only cancel your own bookings.');
                return;
            }

            $booking->cancel();
            
            session()->flash('success', 'Booking cancelled successfully!');
            
            $this->loadSchedules();
            $this->loadUserBookings();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    private function canUserBookClass($schedule)
    {
        if (!Auth::check()) {
            return false;
        }

        // Get user's active memberships
        $activeMemberships = UserMembership::where('user_id', Auth::id())
            ->active()
            ->get();

        if ($activeMemberships->isEmpty()) {
            return false;
        }

        // Check if any active membership allows booking this class type
        foreach ($activeMemberships as $membership) {
            if ($membership->canBookClassType($schedule->classes->group_class_id) && 
                $membership->getRemainingQuotaForClass($schedule->classes->group_class_id) > 0) {
                return true;
            }
        }

        return false;
    }

    private function userHasQuota($classId)
    {
        if (!Auth::check()) {
            return false;
        }

        // Get user's active memberships and check quota for the specific group class
        $activeMemberships = UserMembership::where('user_id', Auth::id())
            ->active()
            ->get();

        foreach ($activeMemberships as $membership) {
            if ($membership->getRemainingQuotaForClass($classId) > 0) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        $groupClasses = GroupClass::where('is_active', true)->get();
        
        return view('livewire.public.class-schedules', [
            'groupClasses' => $groupClasses,
        ]);
    }
}
