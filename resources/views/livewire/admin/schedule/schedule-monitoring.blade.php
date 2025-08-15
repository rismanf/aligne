<div>
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">📅 Schedule Monitoring |
                {{ \Carbon\Carbon::parse($scheduleDate)->translatedFormat('l, d M Y') }} </h3>
            <div class="flex gap-2">
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

                        // Tentukan batas minimal booking berdasarkan jam kelas
                        $hourOfClass = $scheduleDateTime->hour;
                        $minHoursBefore = $hourOfClass <= 10 ? 12 : 3;
                    @endphp

                    <x-card title="📅 {{ $scheduleDateTime->format('h:m A') }}" class="h-full">

                        <div class="schedule-class">
                            {{ $val->classes->name }} - (<small>{{ $val->classes->level_class }})</small>
                        </div>


                        <div class="schedule-info">
                            <div>

                                Trainer : {{ $val->trainer->name }}
                            </div>

                            <div class="capacity-info">
                                Member Join : {{ $val->capacity_book }}
                            </div>

                            <div>
                                @if ($availableSlots == 0)
                                    <span class="book-btn full">Full</span>
                                @elseif ($availableSlots == 1)
                                    <x-button label="View Details" icon="o-eye"
                                        wire:click="showDetailModal({{ $val->id }})" spinner class="btn-xs" />
                                    @php
                                        $minutesAfterStart = $now->diffInMinutes($scheduleDateTime, false);
                                    @endphp

                                    @if ($minutesAfterStart < -5)
                                        {{-- Nilai negatif artinya sudah lewat dari start time --}}
                                        <span class="book-btn expired">Expired</span>
                                    @else
                                        <x-button label="Book Now" icon="o-plus"
                                            wire:click="showBookingModal({{ $val->id }})" spinner class="btn-xs" />
                                    @endif
                                @elseif ($scheduleDateTime->isPast())
                                    <span class="book-btn expired">Expired</span>
                                @elseif ($diffInHours < $minHoursBefore)
                                    <span class="book-btn expired">Too Late</span>
                                @else
                                    <x-button label="Book Now" icon="o-plus"
                                        wire:click="showBookingModal({{ $val->id }})" spinner class="btn-xs" />
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
</div>
