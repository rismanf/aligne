<div>
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">📅 Schedule Monitoring |
                {{ \Carbon\Carbon::parse($scheduleDate)->translatedFormat('l, d M Y') }} </h3>
            <div class="flex gap-2">
                <x-button label="Export Report" icon="o-document-arrow-down" wire:click="openExportModal"
                    class="btn-sm btn-outline" />
                <x-select wire:model.live="selectedgroupclass" :options="$classesGroup" option-label="name" option-value="id" />
                <x-input wire:model.live="scheduleDate" type="date" placeholder="To Date" />
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @if ($schedules->count() > 0)
                @foreach ($schedules as $val)
                    @php
                        $availableSlots = $val->capacity - $val->capacity_book;
                        $scheduleDateTime = \Carbon\Carbon::parse($val->start_time);
                        $now = \Carbon\Carbon::now();
                        $diffInHours = $now->diffInHours($scheduleDateTime, false);
                        $bookingsCount = $val->bookings->where('booking_status', 'confirmed')->count();

                        // Tentukan batas minimal booking berdasarkan jam kelas
                        $hourOfClass = $scheduleDateTime->hour;
                        $minHoursBefore = $hourOfClass <= 10 ? 12 : 3;
                    @endphp

                    <x-card title="📅 {{ $scheduleDateTime->format('H:i') }}" class="h-full">
                        <div class="space-y-3">
                            <div class="schedule-class">
                                <h4 class="font-semibold text-gray-800">{{ $val->classes->name }}</h4>
                                <small class="text-gray-600">{{ $val->classes->level_class ?? 'All Levels' }}</small>
                            </div>

                            <div class="schedule-info space-y-2">
                                <div class="flex items-center text-sm">
                                    <span class="w-16 text-gray-600">Trainer:</span>
                                    <span class="font-medium">{{ $val->trainer->name }}</span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <span class="w-16 text-gray-600">Capacity:</span>
                                    <span class="font-medium">{{ $bookingsCount }}/{{ $val->capacity }}</span>
                                    @if ($bookingsCount > 0)
                                        <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            {{ $bookingsCount }} booked
                                        </span>
                                    @endif
                                </div>

                                @if ($bookingsCount > 0)
                                    <div class="mt-2">
                                        <x-button label="View Bookings ({{ $bookingsCount }})" icon="o-eye"
                                            wire:click="openDetailModal({{ $val->id }})"
                                            class="btn-xs btn-outline w-full" />
                                    </div>
                                @endif
                            </div>

                            <div class="pt-2 border-t">
                                @if ($availableSlots <= 0)
                                    <span
                                        class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs rounded-full">Full</span>
                                @elseif ($scheduleDateTime->isPast())
                                    <span
                                        class="inline-block px-3 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Expired</span>
                                @elseif ($diffInHours < $minHoursBefore)
                                    <span
                                        class="inline-block px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Too
                                        Late</span>
                                @else
                                    <x-button label="Add Booking" icon="o-plus"
                                        wire:click="openBookingModal({{ $val->id }})"
                                        class="btn-xs btn-primary w-full" />
                                @endif
                            </div>
                        </div>
                    </x-card>
                @endforeach
            @else
                <x-card title="📅 No Classes Available" class="h-full">
                    <p>There are no classes scheduled for this date. Please select another date.</p>
                </x-card>
            @endif
        </div>
    </div>

    <!-- Booking Details Modal -->
    <x-modal wire:model="showDetailModal" title="Class Booking Details" class="backdrop-blur">
        @if ($selectedSchedule)
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-lg">{{ $selectedSchedule->classes->name }}</h4>
                    <p class="text-gray-600">{{ $selectedSchedule->trainer->name }} •
                        {{ \Carbon\Carbon::parse($selectedSchedule->start_time)->format('l, d M Y - H:i') }}</p>
                    <p class="text-sm text-gray-500">Capacity:
                        {{ count($selectedScheduleBookings) }}/{{ $selectedSchedule->capacity }}</p>
                </div>

                @if (count($selectedScheduleBookings) > 0)
                    <div class="space-y-2">
                        <h5 class="font-medium text-gray-800">Booking List:</h5>
                        <div class="max-h-96 overflow-y-auto">
                            @foreach ($selectedScheduleBookings as $booking)
                            {{-- {{dd($booking)}} --}}
                                <div class="flex items-center justify-between p-3 bg-white border rounded-lg">
                                    <div class="flex-1">
                                        <p class="font-medium">{{ $booking['user']['name'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $booking['user']['email'] }}</p>
                                        @if ($booking['user_membership'] && $booking['user_membership']['membership'])
                                            <p class="text-xs text-blue-600">
                                                {{ $booking['user_membership']['membership']['name'] }}</p>
                                        @endif
                                        @if ($booking['reformer_position'])
                                            <p class="text-xs text-green-600">Position:
                                                {{ $booking['reformer_position'] }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full 
                                            {{ $booking['booking_status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($booking['booking_status']) }}
                                        </span>
                                        @if ($booking['booking_status'] === 'confirmed')
                                            <x-button icon="o-x-mark" wire:click="cancelBooking({{ $booking['id'] }})"
                                                class="btn-xs btn-error"
                                                wire:confirm="Are you sure you want to cancel this booking?" />
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No bookings yet for this class.</p>
                @endif
            </div>
        @endif
    </x-modal>

    <!-- Add Booking Modal -->
    <x-modal wire:model="showBookingModal" title="Add New Booking" class="backdrop-blur">
        @if ($selectedSchedule)
            <form wire:submit="createBooking">
                <div class="space-y-4">
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        There were errors with your submission
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold">{{ $selectedSchedule->classes->name }}</h4>
                        <p class="text-gray-600">{{ $selectedSchedule->trainer->name }} •
                            {{ \Carbon\Carbon::parse($selectedSchedule->start_time)->format('l, d M Y - H:i') }}</p>
                        <p class="text-sm text-gray-500">Available spots:
                            {{ $selectedSchedule->capacity - $selectedSchedule->capacity_book }}/{{ $selectedSchedule->capacity }}
                        </p>
                    </div>

                    <!-- Booking Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Booking Type</label>
                        <div class="space-y-2">
                            <label>
                                <input type="radio" value="member" wire:model.live="bookingType">
                                Member Booking
                            </label>

                            <label>
                                <input type="radio" value="guest" wire:model.live="bookingType">
                                Guest Booking
                            </label>
                        </div>
                    </div>

                    @if ($bookingType === 'member')
                        <!-- Member Search -->
                        <div>
                            <x-input label="Search Member" wire:model.live.debounce.300ms="memberSearch"
                                placeholder="Type member name or email..." />

                            @if (count($searchResults) > 0)
                                <div class="mt-2 max-h-40 overflow-y-auto border rounded-lg">
                                    @foreach ($searchResults as $user)
                                        <div class="p-2 hover:bg-gray-50 cursor-pointer border-b last:border-b-0"
                                            wire:click="selectMember({{ $user->id }})">
                                            <p class="font-medium">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($selectedUserId && count($userMemberships) > 0)
                                <div class="mt-3">
                                    <x-select label="Select Membership" wire:model="selectedUserMembershipId"
                                        :options="$userMemberships" option-label="membership.name" option-value="id"
                                        placeholder="Choose membership..." />
                                </div>
                            @elseif ($selectedUserId && count($userMemberships) === 0)
                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800">
                                        <strong>Warning:</strong> This member has no active memberships.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Guest Information -->
                        <div class="space-y-3">
                            <x-input label="Guest Name" wire:model="guestName" placeholder="Enter guest full name" />
                            <x-input label="Guest Email" wire:model="guestEmail" type="email"
                                placeholder="guest@example.com" />
                            <x-input label="Guest Phone (Optional)" wire:model="guestPhone"
                                placeholder="+62 xxx xxx xxxx" />
                        </div>
                    @endif

                    <!-- Reformer Position Selection -->
                    @if (
                        $selectedSchedule &&
                            $selectedSchedule->classes &&
                            $selectedSchedule->classes->groupClass &&
                            $selectedSchedule->classes->groupClass->name === 'REFORMER')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Reformer Position
                                *</label>
                            @if (count($availablePositions) > 0)
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach ($availablePositions as $position)
                                        <label
                                            class="flex items-center justify-center p-2 border rounded cursor-pointer hover:bg-gray-50 transition-colors
                                            {{ $selectedReformerPosition == $position ? 'bg-blue-100 border-blue-500 text-blue-700' : 'border-gray-300' }}">
                                            <input type="radio" wire:model="selectedReformerPosition"
                                                value="{{ $position }}" class="sr-only">
                                            <span class="text-sm font-medium">{{ $position }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-800">All reformer positions are taken for this class.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex justify-end space-x-2 pt-4">
                        <x-button label="Cancel" wire:click="$set('showBookingModal', false)" />
                        <x-button label="Create Booking" type="submit" class="btn-primary" />
                    </div>
                </div>
            </form>
        @endif
    </x-modal>

    <!-- Export Modal -->
    <x-modal wire:model="showExportModal" title="Export Booking Report" class="backdrop-blur">
        <form wire:submit="exportToExcel">
            <div class="space-y-4">
                <x-input label="From Date" wire:model="exportFromDate" type="date" required />
                <x-input label="To Date" wire:model="exportToDate" type="date" required />

                <div class="flex justify-end space-x-2 pt-4">
                    <x-button label="Cancel" wire:click="$set('showExportModal', false)" />
                    <x-button label="Export CSV" type="submit" class="btn-primary" icon="o-document-arrow-down" />
                </div>
            </div>
        </form>
    </x-modal>


    <style>
        .schedule-class h4 {
            margin: 0;
            font-size: 1.1rem;
        }

        .schedule-info {
            font-size: 0.9rem;
        }

        .book-btn {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .book-btn.full {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .book-btn.expired {
            background-color: #f3f4f6;
            color: #6b7280;
        }
    </style>

</div>
