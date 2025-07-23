<div>
    <x-card>
        <div>
            <div class="mb-4">
                <label class="font-semibold">Pilih Class:</label>
                <select wire:model="selectedgroupclass" class="border rounded p-2 w-full max-w-xs"
                    wire:change="classChanged($event.target.value)">
                    @foreach ($calases_group as $name)
                        <option value="{{ $name['id'] }}">
                            {{ $name['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="font-semibold">Pilih Tanggal:</label>
                <select wire:model="selectedDate" class="border rounded p-2 w-full max-w-xs"
                    wire:change="classChanged($event.target.value)">
                    @foreach ($availableDates as $date)
                        <option value="{{ $date }}">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div>
                <h1> Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }} </h1>


                @foreach ($all_schedule_times as $time)
                    <div class="p-4 border rounded shadow-sm bg-gray-50">
                        <div class="grid grid-cols-12 gap-4 items-start mb-4">
                            <div class="col-span-2">
                                <h4 class="font-bold">{{ $time->name }}</h4>
                            </div>
                            <div class="col-span-8">
                                @php
                                    $schedule_data_tmp = $schedule_data->where('time_id', $time->id)->first();

                                @endphp
                                @if (isset($schedule_data_tmp))
                                    <p>Class Level: {{ $schedule_data_tmp->level_class }}
                                    </p>
                                    <p>Class Type: {{ $schedule_data_tmp->classes->name }}
                                    </p>
                                    <p>Trainer: {{ $schedule_data_tmp->trainer->name }}
                                    </p>
                                    {{-- <p>{{ $schedule_data_tmp->quota }}
                                    </p> --}}
                                @else
                                    <p>Tidak ada data</p>
                                @endif
                            </div>

                            <div class="col-span-2">
                                <button wire:click="showEditModal({{ $time->id }})"
                                    class="mt-2 px-3 py-1 bg-blue-500 text-white rounded">Update</button>
                                @if (isset($schedule_data_tmp))
                                    <button wire:click="showDeleteModal({{ $time->id }})"
                                        class="mt-2 px-3 py-1 bg-amber-600 text-white rounded">Remove</button>
                                @endif
                                {{-- Tampilkan error jika ada --}}
                                @if (isset($errorsPerTimeId[$time->id]))
                                    <div class="text-danger mt-1">
                                        {{ $errorsPerTimeId[$time->id] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-card>

    <x-modal wire:model="editForm" title="Edit Schedule" class="backdrop-blur">
        <x-form wire:submit="update">
            <x-select label="Class Level" wire:model="class_level_id" :options="$class_level" placeholder="Select Level" />
            {{-- Select Class --}}
            <x-select label="Class Type" wire:model="class_id" :options="$calases" option-label="name" option-value="id"
                placeholder="Select Class Type" required />
            {{-- Select Trainer --}}
            <x-select label="Trainer" wire:model="trainer_id" :options="$trainer_data" placeholder="Select a Trainer" />
            {{-- <x-input label="Qouta" wire:model="quota" placeholder="Kuota" class="w-full mt-2" /> --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
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
