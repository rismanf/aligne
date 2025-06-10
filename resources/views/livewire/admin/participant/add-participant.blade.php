<div>
    <x-card>
        <x-form wire:submit="save">
            <x-input label="First Name" wire:model="firstname" />
            <x-input label="Last Name" wire:model="lastname" />
            <x-input label="Company" wire:model="company" />
            <x-input label="Job Title" wire:model="job" />
            <x-input label="Country" wire:model="country" />
            <x-input label="Phone" wire:model="phone" inputmode="numeric" pattern="[0-9]+" />
            <x-input label="Email" type="email" wire:model="email" />

            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}
            @foreach ($questions as $key => $question)
                <span label="{{ $question->question }}" class="text-sm font-semibold">{{ $question->question }}</span>
                @foreach ($question->options as $options)
                    <x-checkbox label="{{ $options->option }}" wire:model="answers.{{ $options->id }}" />
                @endforeach
            @endforeach
            <x-slot:actions>
                <x-button label="Cancel" onclick="window.history.back()" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
