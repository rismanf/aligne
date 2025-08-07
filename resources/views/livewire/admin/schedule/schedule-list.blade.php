<div>
    <x-card>
        <div>
            <!-- Header with Controls -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">📅 Schedule Management</h2>
                    <p class="text-sm text-gray-600">Manage class schedules and copy schedules between dates</p>
                </div>

                <div class="flex gap-2">
                    <x-button label="Copy Schedule" icon="o-document-duplicate" wire:click="showCopyScheduleModal()"
                        class="btn-outline btn-primary" />
                </div>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Class Group:</label>
                    <x-select wire:model.live="selectedgroupclass" :options="$calases_group" option-label="name"
                        option-value="id" wire:change="classChanged()" />
                </div>

                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Select Date:</label>
                    <x-input wire:model.live="selectedDate" type="date" wire:change="classChanged()" />
                </div>

                <div class="flex items-end">
                    <div class="text-sm text-gray-600">
                        <div class="font-medium">
                            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}</div>
                        <div>{{ count($schedule_data) }} classes scheduled</div>
                    </div>
                </div>
            </div>

            <hr>
            <div>
                <h1 class="text-lg font-bold mb-4">
                    Time Slots for {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}
                </h1>

                @foreach ($timeSlots as $slot)
                    <div
                        class="p-4 border rounded shadow-sm mb-3 {{ $slot['is_available'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-2">
                                <h4 class="font-bold text-lg">
                                    {{ $slot['start_time'] }} - {{ $slot['end_time'] }}
                                </h4>
                                @if (isset($slot['slot_name']) && $slot['slot_name'] !== $slot['start_time'] . ' - ' . $slot['end_time'])
                                    <p class="text-sm text-gray-600">{{ $slot['slot_name'] }}</p>
                                @endif
                            </div>

                            @if ($slot['is_available'])
                                {{-- Empty slot - show Add button --}}
                                <div class="col-span-8">
                                    <p class="text-gray-500 italic">Available slot - No class scheduled</p>
                                </div>
                                <div class="col-span-2">
                                    <button
                                        wire:click="addScheduleAtTime('{{ $slot['start_time'] }}', '{{ $slot['end_time'] }}')"
                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 w-full">
                                        + Add Class
                                    </button>
                                </div>
                            @else
                                {{-- Occupied slot - show schedule details --}}
                                <div class="col-span-8">
                                    <p><strong>Class:</strong> {{ $slot['schedule']->classes->name }}</p>
                                    <p><strong>Level:</strong> {{ $slot['schedule']->classes->level_class }}</p>
                                    <p><strong>Trainer:</strong>
                                        @if (isset($slot['schedule']->trainer->deleted_at))
                                            <strong class="text-red-500">Deleted</strong> ( {{ $slot['schedule']->trainer->name }})
                                        @else
                                            {{ $slot['schedule']->trainer->name }}
                                        @endif
                                    </p>
                                    <p><strong>Capacity:</strong>
                                        {{ $slot['schedule']->capacity_book }}/{{ $slot['schedule']->capacity }}</p>
                                    <p><strong>Status:</strong>
                                        <span
                                            class="px-2 py-1 rounded text-xs {{ $slot['schedule']->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $slot['schedule']->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-span-2">
                                    <button wire:click="showEditModal({{ $slot['schedule']->id }})"
                                        class="mb-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                                        Edit
                                    </button>
                                    <button wire:click="showDeleteModal({{ $slot['schedule']->id }})"
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 w-full">
                                        Delete
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-card>

    <x-modal wire:model="editForm" title="{{ $id ? 'Edit Schedule' : 'Add New Schedule' }}" class="backdrop-blur">
        <x-form wire:submit="update">
            {{-- Select Class --}}
            <x-select label="Class Type" wire:model="class_id" :options="$calases" option-label="name" option-value="id"
                placeholder="Select Class Type" required />

            {{-- Select Trainer --}}
            <x-select label="Trainer" wire:model="trainer_id" :options="$trainer_data" option-label="name" option-value="id"
                placeholder="Select a Trainer" required />

            {{-- Start Time --}}
            {{-- <x-input label="Start Time" wire:model="start_time" type="time" placeholder="09:00" required /> --}}

            {{-- End Time --}}
            {{-- <x-input label="End Time" wire:model="end_time" type="time" placeholder="10:00" required /> --}}

            {{-- Name --}}
            {{-- <x-input label="Schedule Name" wire:model="name" placeholder="Class Name - Time" /> --}}

            {{-- Capacity --}}
            <x-input label="Capacity" wire:model="capacity" type="number" min="1" max="20" placeholder="8"
                required />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="deleteForm" title="Are you sure?" subtitle="delete this schedule?">
        <x-form no-separator>

            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteForm = false" />
                <x-button label="Delete" class="btn-primary" wire:click="delete" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Copy Schedule Modal -->
    <x-modal wire:model="copyScheduleModal" title="Copy Schedule" class="backdrop-blur">
        <div class="space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-semibold text-blue-800 mb-2">📋 Copy Schedule Instructions</h4>
                <p class="text-sm text-blue-700">
                    This will copy all schedules from the source date to the target date for the selected class group.
                    Make sure the target date doesn't have existing schedules to avoid conflicts.
                </p>
            </div>

            <x-form wire:submit="copySchedule">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input label="Copy From Date" wire:model="copyFromDate" type="date" required />
                        <div class="text-xs text-gray-500 mt-1">
                            Source date with existing schedules
                        </div>
                    </div>

                    <div>
                        <x-input label="Copy To Date" wire:model="copyToDate" type="date" required />
                        <div class="text-xs text-gray-500 mt-1">
                            Target date (must be today or future)
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 p-3 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <strong>Warning:</strong> This action will copy all schedules from the source date.
                            If the target date already has schedules, the copy operation will be cancelled.
                        </div>
                    </div>
                </div>

                <x-slot:actions>
                    <x-button label="Cancel" @click="$wire.copyScheduleModal = false" />
                    <x-button label="Copy Schedules" class="btn-primary" type="submit" spinner="copySchedule"
                        icon="o-document-duplicate" />
                </x-slot:actions>
            </x-form>
        </div>
    </x-modal>
</div>
