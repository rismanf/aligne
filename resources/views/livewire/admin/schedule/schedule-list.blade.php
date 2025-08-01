<div>
    <x-card>
        <div>
            <div class="mb-4">
                <label class="font-semibold">Pilih Class Group:</label>
                <select wire:model="selectedgroupclass" class="border rounded p-2 w-full max-w-xs"
                    wire:change="classChanged()">
                    @foreach ($calases_group as $group)
                        <option value="{{ $group['id'] }}">
                            {{ $group['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="font-semibold">Pilih Tanggal:</label>
                <select wire:model="selectedDate" class="border rounded p-2 w-full max-w-xs"
                    wire:change="classChanged()">
                    @foreach ($availableDates as $date)
                        <option value="{{ $date }}">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <hr>
            <div>
                <h1 class="text-lg font-bold mb-4"> 
                    Time Slots for {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }} 
                </h1>

                @foreach ($timeSlots as $slot)
                    <div class="p-4 border rounded shadow-sm mb-3 {{ $slot['is_available'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-2">
                                <h4 class="font-bold text-lg">
                                    {{ $slot['start_time'] }} - {{ $slot['end_time'] }}
                                </h4>
                            </div>
                            
                            @if($slot['is_available'])
                                {{-- Empty slot - show Add button --}}
                                <div class="col-span-8">
                                    <p class="text-gray-500 italic">Available slot - No class scheduled</p>
                                </div>
                                <div class="col-span-2">
                                    <button wire:click="addScheduleAtTime('{{ $slot['start_time'] }}', '{{ $slot['end_time'] }}')"
                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 w-full">
                                        + Add Class
                                    </button>
                                </div>
                            @else
                                {{-- Occupied slot - show schedule details --}}
                                <div class="col-span-8">
                                    <p><strong>Class:</strong> {{ $slot['schedule']->classes->name }}</p>
                                    <p><strong>Level:</strong> {{ $slot['schedule']->classes->level_class }}</p>
                                    <p><strong>Trainer:</strong> {{ $slot['schedule']->trainer->name }}</p>
                                    <p><strong>Capacity:</strong> {{ $slot['schedule']->capacity_book }}/{{ $slot['schedule']->capacity }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="px-2 py-1 rounded text-xs {{ $slot['schedule']->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
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
            <x-input label="Start Time" wire:model="start_time" type="time" placeholder="09:00" required />
            
            {{-- End Time --}}
            <x-input label="End Time" wire:model="end_time" type="time" placeholder="10:00" required />
            
            {{-- Name --}}
            <x-input label="Schedule Name" wire:model="name" placeholder="Class Name - Time" />
            
            {{-- Capacity --}}
            <x-input label="Capacity" wire:model="capacity" type="number" min="1" max="20" 
                placeholder="8" required />
            
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
</div>
