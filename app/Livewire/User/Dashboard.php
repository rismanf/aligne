<?php

namespace App\Livewire\User;

use App\Models\UserMembership;
use App\Models\ClassBooking;
use App\Models\UserKuota;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $activeMemberships;
    public $upcomingBookings;
    public $quotaSummary;
    public $membershipStats;

    public function mount()
    {
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $user = Auth::user();

        // Get active memberships
        $this->activeMemberships = UserMembership::with(['membership', 'membership.classes'])
            ->where('user_id', $user->id)
            ->active()
            ->get();

        // Get upcoming bookings
        $this->upcomingBookings = ClassBooking::with(['classSchedule.classes', 'classSchedule.trainer'])
            ->where('user_id', $user->id)
            ->whereHas('classSchedule', function($query) {
                $query->where('start_time', '>', now());
            })
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get quota summary
        $this->quotaSummary = UserKuota::selectRaw('
                class_id,
                SUM(kuota) as total_quota,
                (SELECT name FROM group_classes WHERE id = user_kuotas.class_id) as class_name,
                (SELECT category FROM group_classes WHERE id = user_kuotas.class_id) as class_category
            ')
            ->where('user_id', $user->id)
            ->where('end_date', '>', now())
            ->groupBy('class_id')
            ->having('total_quota', '>', 0)
            ->get();

        // Calculate membership stats
        $this->membershipStats = [
            'total_memberships' => UserMembership::where('user_id', $user->id)->count(),
            'active_memberships' => $this->activeMemberships->count(),
            'total_bookings' => ClassBooking::where('user_id', $user->id)->count(),
            'upcoming_bookings' => $this->upcomingBookings->count(),
        ];
    }

    public function cancelBooking($bookingId)
    {
        $booking = ClassBooking::where('user_id', Auth::id())
            ->where('id', $bookingId)
            ->first();

        if ($booking && $booking->classSchedule->start_time > now()->addHours(24)) {
            $booking->cancel();
            
            $this->loadUserData(); // Refresh data
            
            session()->flash('success', 'Booking cancelled successfully. Your quota has been restored.');
        } else {
            session()->flash('error', 'Cannot cancel booking less than 24 hours before class time.');
        }
    }

    public function render()
    {
        return view('livewire.user.dashboard')->layout('components.layouts.app', [
            'title' => 'My Dashboard',
            'breadcrumbs' => [
                [
                    'link' => route('user.dashboard'),
                    'label' => 'Dashboard',
                    'icon' => 's-home',
                ],
            ],
        ]);
    }
}
