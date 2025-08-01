<?php

namespace App\Livewire\Public;

use App\Models\ClassBooking;
use App\Models\ClassSchedules;
use App\Models\GroupClass;
use App\Models\UserKuota;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class CheckoutClassEnhanced extends Component
{
    use Toast;

    public $scheduleId;
    public $schedule;
    public $class;
    public $userQuota = 0;
    public $hasActiveMembership = false;
    public $existingBooking = null;
    public $canBook = false;

    public function mount($id)
    {
        $this->scheduleId = $id;
        $this->schedule = ClassSchedules::with(['classes', 'trainer'])->find($id);
        
        if (!$this->schedule) {
            abort(404, 'Class schedule not found');
        }

        $this->class = $this->schedule->classes;
        $this->checkUserEligibility();
    }

    public function checkUserEligibility()
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Check if user has active membership for this class type
        $this->hasActiveMembership = $user->hasActiveMembershipForClass($this->class->id);

        // Get remaining quota for this class
        $this->userQuota = $user->getRemainingQuotaForClass($this->class->id);

        // Check if user already booked this specific schedule
        $this->existingBooking = ClassBooking::where('user_id', $user->id)
            ->where('class_schedule_id', $this->scheduleId)
            ->where('booking_status', 'confirmed')
            ->first();

        // Check if class is full
        $isClassFull = $this->schedule->capacity_book >= $this->schedule->capacity;

        // Check if class time has passed
        $isClassPassed = $this->schedule->start_time <= now();

        // Determine if user can book
        $this->canBook = $this->hasActiveMembership 
            && $this->userQuota > 0 
            && !$this->existingBooking 
            && !$isClassFull 
            && !$isClassPassed
            && $this->schedule->is_active;
    }

    public function bookClass()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$this->canBook) {
            $this->toastError('Unable to book this class. Please check your membership and quota.');
            return;
        }

        try {
            // Create booking
            $booking = ClassBooking::create([
                'user_id' => Auth::id(),
                'user_membership_id' => $this->getUserActiveMembership()->id,
                'class_schedule_id' => $this->scheduleId,
                'booking_status' => 'confirmed',
                'booked_at' => now(),
                'created_by_id' => Auth::id(),
            ]);

            // Deduct quota
            $this->deductUserQuota();

            // Increment class capacity
            $this->schedule->increment('capacity_book');

            $this->toastSuccess('Class booked successfully!');
            
            // Redirect to booking invoice page with the new booking ID
            $this->js(<<<JS
                setTimeout(function () {
                    window.location.href = "/user/my-bookings/{$booking->id}";
                }, 2000);
            JS);

        } catch (\Exception $e) {
            $this->toastError('Failed to book class: ' . $e->getMessage());
        }
    }

    private function getUserActiveMembership()
    {
        return UserMembership::where('user_id', Auth::id())
            ->whereHas('membership.classes', function($query) {
                $query->where('group_classes.id', $this->class->id);
            })
            ->active()
            ->first();
    }

    private function deductUserQuota()
    {
        $userQuota = UserKuota::where('user_id', Auth::id())
            ->where('class_id', $this->class->id)
            ->where('end_date', '>', now())
            ->where('kuota', '>', 0)
            ->first();

        if ($userQuota) {
            $userQuota->decrement('kuota');
        }
    }

    public function render()
    {
        return view('livewire.public.checkout-class-enhanced')->layout('components.layouts.website', [
            'title' => 'Book Class - ' . $this->class->name,
            'description' => 'Book your fitness class',
            'keywords' => 'class booking, fitness, ' . $this->class->name,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
