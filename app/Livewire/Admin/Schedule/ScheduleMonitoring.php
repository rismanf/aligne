<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\ClassSchedules;
use App\Models\GroupClass;
use App\Models\ClassBooking;
use App\Models\User;
use App\Models\UserKuota;
use App\Models\UserMembership;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ScheduleMonitoring extends Component
{
    use WithPagination;

    public $scheduleDate;
    public $schedules;
    public $classesGroup;
    public $selectedgroupclass;

    // Modal states
    public $showDetailModal = false;
    public $showBookingModal = false;
    public $showExportModal = false;

    // Selected schedule for modals
    public $selectedSchedule;
    public $selectedScheduleBookings = [];

    // Booking form data
    public $bookingType = 'member'; // 'member' or 'guest'
    public $selectedUserId;
    public $selectedUserMembershipId;
    public $guestName;
    public $guestEmail;
    public $guestPhone;
    public $selectedReformerPosition;
    public $availablePositions = [];

    // Export data
    public $exportFromDate;
    public $exportToDate;

    // Search for members
    public $memberSearch = '';
    public $searchResults = [];
    public $userMemberships = [];

    public function mount()
    {
        // Set default date filters
        $this->scheduleDate = Carbon::now()->format('Y-m-d');
        $this->exportFromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->exportToDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        $this->classesGroup = GroupClass::select('id', 'name')->get()->toArray();
        $this->selectedgroupclass = $this->classesGroup[0]['id'] ?? 1;

        $this->loadScheduleStatistics();
    }

    private function loadScheduleStatistics()
    {
        $this->schedules = ClassSchedules::with(['trainer', 'classes.groupClass', 'bookings.user', 'bookings.userMembership.membership'])
            ->whereHas('classes', function ($query) {
                $query->where('group_class_id', $this->selectedgroupclass);
            })
            ->whereDate('start_time', $this->scheduleDate)
            ->orderby('start_time', 'asc')
            ->get();
    }

    public function updatedselectedgroupclass()
    {
        $this->loadScheduleStatistics();
    }

    public function updatedscheduleDate()
    {
        $this->loadScheduleStatistics();
    }

    public function openDetailModal($scheduleId)
    {
        $this->selectedSchedule = ClassSchedules::with(['trainer', 'classes.groupClass', 'bookings.user', 'bookings.userMembership.membership'])
            ->find($scheduleId);

        if ($this->selectedSchedule) {
            $this->selectedScheduleBookings = $this->selectedSchedule->bookings
                ->where('booking_status', 'confirmed')
                ->values()
                ->toArray();
        }

        $this->showDetailModal = true;
    }

    public function openBookingModal($scheduleId)
    {
        $this->selectedSchedule = ClassSchedules::with(['trainer', 'classes.groupClass'])
            ->find($scheduleId);

        // Reset form
        $this->reset(['bookingType', 'selectedUserId', 'selectedUserMembershipId', 'guestName', 'guestEmail', 'guestPhone', 'selectedReformerPosition', 'memberSearch', 'searchResults', 'userMemberships']);

        // Set default booking type
        $this->bookingType = 'member';

        // Load available reformer positions if it's a reformer class
        if (
            $this->selectedSchedule &&
            $this->selectedSchedule->classes &&
            $this->selectedSchedule->classes->groupClass &&
            $this->selectedSchedule->classes->groupClass->name === 'REFORMER'
        ) {
            $this->loadAvailablePositions();
        }

        $this->showBookingModal = true;
    }

    private function loadAvailablePositions()
    {
        $totalPositions = range(1, $this->selectedSchedule->capacity);
        $bookedPositions = ClassBooking::where('class_schedule_id', $this->selectedSchedule->id)
            ->where('booking_status', 'confirmed')
            ->whereNotNull('reformer_position')
            ->pluck('reformer_position')
            ->toArray();

        $this->availablePositions = array_diff($totalPositions, $bookedPositions);
    }

    public function updatedMemberSearch()
    {
        if (strlen($this->memberSearch) >= 2) {
            $this->searchResults = User::where('name', 'like', '%' . $this->memberSearch . '%')
                ->orWhere('email', 'like', '%' . $this->memberSearch . '%')
                ->limit(10)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectMember($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::find($userId);
        $this->memberSearch = $user->name . ' (' . $user->email . ')';
        $this->searchResults = [];

        // Load user's active memberships
        $this->loadUserMemberships($userId);
    }

    private function loadUserMemberships($userId)
    {
        // Get all active memberships for the user
        $memberships = UserMembership::with('membership')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where('payment_status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        // Filter memberships that have available quota
        $this->userMemberships = $memberships->filter(function ($membership) {
            // Check if membership has any available quota
            if ($membership->isFlexibleQuota()) {
                // For flexible quota packages, check shared quota
                return $membership->getRemainingFlexibleQuota() > 0;
            } else {
                // For fixed quota packages, check if any class type has quota
                $hasQuota = UserKuota::where('user_id', $membership->user_id)
                    ->where('invoice_number', $membership->invoice_number)
                    ->where('end_date', '>', now())
                    ->where('kuota', '>', 0)
                    ->exists();
                
                return $hasQuota;
            }
        });
    }

    public function createBooking()
    {
        // Custom validation with better error messages
        $rules = [
            'bookingType' => 'required|in:member,guest',
        ];

        if ($this->bookingType === 'member') {
            $rules['selectedUserId'] = 'required';
            $rules['selectedUserMembershipId'] = 'required';
        } else {
            $rules['guestName'] = 'required|max:255';
            $rules['guestEmail'] = 'required|email';
            $rules['guestPhone'] = 'nullable|string|max:20';
        }

        // Add reformer position validation if needed
        if (
            $this->selectedSchedule &&
            $this->selectedSchedule->classes &&
            $this->selectedSchedule->classes->groupClass &&
            $this->selectedSchedule->classes->groupClass->name === 'REFORMER'
        ) {
            $rules['selectedReformerPosition'] = 'required|integer|min:1';
        }

        $this->validate($rules, [
            'selectedUserId.required' => 'Please select a member.',
            'selectedUserMembershipId.required' => 'Please select a membership.',
            'guestName.required' => 'Guest name is required.',
            'guestEmail.required' => 'Guest email is required.',
            'guestEmail.email' => 'Please enter a valid email address.',
            'selectedReformerPosition.required' => 'Please select a reformer position.',
        ]);

        try {
            // Check capacity
            if ($this->selectedSchedule->capacity_book >= $this->selectedSchedule->capacity) {
                $this->addError('capacity', 'Class is already full.');
                return;
            }

            $bookingData = [
                'class_schedule_id' => $this->selectedSchedule->id,
                'booking_status' => 'confirmed',
                'booked_at' => now(),
                'created_by_id' => auth()->id(),
            ];

            if ($this->bookingType === 'member') {
                $existingBooking = ClassBooking::where('user_id', $this->selectedUserId)
                    ->where('class_schedule_id', $this->selectedSchedule->id)
                    ->where('booking_status', 'confirmed')
                    ->first();

                if ($existingBooking) {
                    $this->addError('booking', 'This member already has a confirmed booking for this class.');
                    return;
                }

                // Check if user has available quota for this membership
                $selectedMembership = UserMembership::find($this->selectedUserMembershipId);
                if (!$selectedMembership) {
                    $this->addError('booking', 'Selected membership not found.');
                    return;
                }

                $hasQuota = false;
                if ($selectedMembership->isFlexibleQuota()) {
                    $hasQuota = $selectedMembership->getRemainingFlexibleQuota() > 0;
                } else {
                    // For fixed quota packages, check specific class quota
                    $classId = $this->selectedSchedule->classes->group_class_id;
                    $hasQuota = UserKuota::where('user_id', $this->selectedUserId)
                        ->where('invoice_number', $selectedMembership->invoice_number)
                        ->where('class_id', $classId)
                        ->where('end_date', '>', now())
                        ->where('kuota', '>', 0)
                        ->exists();
                }

                if (!$hasQuota) {
                    $this->addError('booking', 'This member does not have quota available for this class type.');
                    return;
                }
                $bookingData['user_id'] = $this->selectedUserId;
                $bookingData['user_membership_id'] = $this->selectedUserMembershipId;
            } else {
                // For guest booking, create a guest user
                $guestUser = User::firstOrCreate(
                    ['email' => $this->guestEmail],
                    [
                        'name' => $this->guestName,
                        'phone' => $this->guestPhone ?? null,
                        'password' => bcrypt('temporary123'),
                        'is_guest' => true,
                    ]
                );
                $bookingData['user_id'] = $guestUser->id;
                // Guest bookings don't have membership
                $bookingData['user_membership_id'] = null;
            }

            // Add reformer position if selected
            if ($this->selectedReformerPosition) {
                $bookingData['reformer_position'] = $this->selectedReformerPosition;
            }

            // Create the booking
            ClassBooking::create($bookingData);

            // Update capacity
            $this->selectedSchedule->increment('capacity_book');

            // Decrease quota for member bookings
            if ($this->bookingType === 'member') {
                $selectedMembership = UserMembership::find($this->selectedUserMembershipId);
                if ($selectedMembership->isFlexibleQuota()) {
                    // Decrease flexible quota
                    $selectedMembership->decreaseFlexibleQuota();
                } else {
                    // Decrease specific class quota
                    $classId = $this->selectedSchedule->classes->group_class_id;
                    $quota = UserKuota::where('user_id', $this->selectedUserId)
                        ->where('invoice_number', $selectedMembership->invoice_number)
                        ->where('class_id', $classId)
                        ->where('end_date', '>', now())
                        ->where('kuota', '>', 0)
                        ->first();
                    
                    if ($quota) {
                        $quota->decrement('kuota');
                    }
                }
            }

            // Success message and cleanup
            session()->flash('success', 'Booking created successfully!');
            $this->showBookingModal = false;
            $this->loadScheduleStatistics();

            // Reset form
            $this->reset(['bookingType', 'selectedUserId', 'selectedUserMembershipId', 'guestName', 'guestEmail', 'guestPhone', 'selectedReformerPosition', 'memberSearch', 'searchResults', 'userMemberships']);
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to create booking: ' . $e->getMessage());
            Log::error('Booking creation failed: ' . $e->getMessage(), [
                'schedule_id' => $this->selectedSchedule->id,
                'booking_type' => $this->bookingType,
                'user_id' => $this->selectedUserId,
                'guest_email' => $this->guestEmail,
            ]);
        }
    }

    public function cancelBooking($bookingId)
    {
        try {
            $booking = ClassBooking::with(['userMembership', 'classSchedule.classes'])->find($bookingId);
            if ($booking) {
                $booking->update([
                    'booking_status' => 'cancelled',
                    'cancelled_at' => now(),
                    'updated_by_id' => auth()->id(),
                ]);

                // Update capacity
                $booking->classSchedule->decrement('capacity_book');

                // Return quota to user (only for member bookings, not guest bookings)
                if ($booking->user_membership_id && $booking->userMembership) {
                    $membership = $booking->userMembership;
                    
                    if ($membership->isFlexibleQuota()) {
                        // Return quota to flexible quota pool
                        $quota = UserKuota::where('user_id', $booking->user_id)
                            ->where('class_id', 0) // Shared quota
                            ->where('invoice_number', $membership->invoice_number)
                            ->where('end_date', '>', now())
                            ->first();
                        
                        if ($quota) {
                            $quota->increment('kuota');
                        }
                    } else {
                        // Return quota to specific class quota
                        $classId = $booking->classSchedule->classes->group_class_id;
                        $quota = UserKuota::where('user_id', $booking->user_id)
                            ->where('invoice_number', $membership->invoice_number)
                            ->where('class_id', $classId)
                            ->where('end_date', '>', now())
                            ->first();
                        
                        if ($quota) {
                            $quota->increment('kuota');
                        }
                    }
                }

                session()->flash('success', 'Booking cancelled successfully and quota returned!');
                $this->loadScheduleStatistics();

                // Refresh detail modal if open
                if ($this->showDetailModal) {
                    $this->openDetailModal($booking->class_schedule_id);
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cancel booking: ' . $e->getMessage());
            Log::error('Booking cancellation failed: ' . $e->getMessage(), [
                'booking_id' => $bookingId,
            ]);
        }
    }

    
    public function openExportModal()
    {
        $this->showExportModal = true;
    }

    public function exportToExcel()
    {
        $this->validate([
            'exportFromDate' => 'required|date',
            'exportToDate' => 'required|date|after_or_equal:exportFromDate',
        ]);

        try {
            // For now, we'll create a simple CSV export
            // Later we can add Laravel Excel package
            $schedules = ClassSchedules::with(['trainer', 'classes.groupClass', 'bookings.user', 'bookings.userMembership.membership'])
                ->whereHas('classes', function ($query) {
                    $query->where('group_class_id', $this->selectedgroupclass);
                })
                ->whereBetween('start_time', [$this->exportFromDate, $this->exportToDate])
                ->get();

            $fileName = 'schedule-booking-report-' . $this->exportFromDate . '-to-' . $this->exportToDate . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            $callback = function () use ($schedules) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Date', 'Time', 'Class Name', 'Trainer', 'Member Name', 'Email', 'Booking Status', 'Position']);

                foreach ($schedules as $schedule) {
                    foreach ($schedule->bookings as $booking) {
                        fputcsv($file, [
                            $schedule->start_time->format('Y-m-d'),
                            $schedule->start_time->format('H:i'),
                            $schedule->classes->name,
                            $schedule->trainer->name,
                            $booking->user->name ?? 'N/A',
                            $booking->user->email ?? 'N/A',
                            $booking->booking_status,
                            $booking->reformer_position ?? 'N/A',
                        ]);
                    }
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to export data: ' . $e->getMessage());
        }
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
