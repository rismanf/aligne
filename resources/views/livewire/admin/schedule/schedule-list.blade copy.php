<div>
    <x-card>
        <div>
            <form wire:submit.prevent="save" class="space-y-6">
                @foreach ($dates as $date)
                    <div class="border p-4 rounded-lg shadow-sm">
                        <h2 class="font-semibold text-lg mb-3">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($all_schedule_times as $time)
                                <div class="border p-3 rounded bg-gray-50">
                                    <div class="text-sm font-medium mb-1">{{ $time->time }}</div>

                                    <select wire:model="scheduleInputs.{{ $date }}.{{ $time->time }}.class_id"
                                        class="w-full mb-1 border rounded p-1">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($calases as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>

                                    <select
                                        wire:model="scheduleInputs.{{ $date }}.{{ $time->time }}.trainer_id"
                                        class="w-full border rounded p-1">
                                        <option value="">-- Pilih Trainer --</option>
                                        @foreach ($trainer_data as $trainer)
                                            <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan
                        Jadwal</button>
                </div>

                @if (session()->has('message'))
                    <div class="text-green-600 mt-3">{{ session('message') }}</div>
                @endif
            </form>
        </div>

        <div class="flex justify-end mb-4">
            {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
            <x-button label="Add" icon="o-plus" @click="$wire.createForm = true" class="btn-primary btn-xs p-2" />
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$schedules">
            {{-- Special `row_number` scope --}}


            {{-- Special `actions` slot --}}
            @scope('cell_action', $news)
                <div class="flex gap-1">
                    <x-button icon="o-pencil" wire:click="showEditModal({{ $news->id }})" spinner class="btn-xs" />
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $news->id }})" spinner class="btn-xs" />
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $news->id }})" spinner class="btn-xs" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Schedule" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-datetime label="Date" wire:model="schedule_at" type="date" min="{{ now()->format('Y-m-d') }}" />
            <x-select label="Trainer" wire:model="trainer" :options="$trainer_data" placeholder="Select a Trainer" />

            <x-select label="Class" wire:model="selectedClassId" :options="$calases->map(fn($class) => ['id' => $class->id, 'name' => $class->name])->toArray()" placeholder="Select a Class"
                wire:change="classChanged($event.target.value)" />
            <!-- Show Times -->
            @if ($this->filteredScheduleTimes->count())
                <label class="font-semibold mb-2 block">Available Times:</label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($this->filteredScheduleTimes as $time)
                        <x-checkbox label="{{ $time->name }}" wire:model="schedule_time"
                            value="{{ $time->id }}" />
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 mt-2">Select a class to see available times.</p>
            @endif
            <x-input label="Kuota" wire:model="kuota" />
            <x-input label="Duration" wire:model="duration" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-edit-muncul --}}
    <x-modal wire:model="editForm" title="Edit Schedule" class="backdrop-blur">
        <x-form wire:submit="update">
            <x-datetime label="Date" wire:model="schedule_at" type="datetime-local" />
            <x-select label="Trainer" wire:model="trainer" :options="$trainer_data" placeholder="Select a Trainer" />

            <x-input label="Kuota" wire:model="kuota" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
