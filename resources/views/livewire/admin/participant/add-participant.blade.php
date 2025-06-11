<div>
    <x-card>
        <x-form wire:submit="save">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="First Name" wire:model="first_name" />
                <x-input label="Last Name" wire:model="last_name" />
                <x-input label="Company" wire:model="company" />
                <x-input label="Job Title" wire:model="job_title" />
                <x-input label="Country" wire:model="country" />
                <x-input label="Phone" wire:model="phone" inputmode="numeric" pattern="[0-9]+" />
                <x-input label="Email" type="email" wire:model="email" />
            </div>
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}
            @foreach ($questions as $question)
                <hr class="border-gray-300 my-4" />
                @error("answers.{$question->id}")
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <div class="mb-4">
                    <h2 class="text-sm font-semibold mb-2">{{ $question->question }}</h2>

                    @if ($question->question_type === 'multiple')
                        @foreach ($question->options as $option)
                            <x-checkbox label="{{ $option->option }}"
                                wire:model="answers.{{ $question->id }}.{{ $option->id }}" />
                        @endforeach
                    @else
                        @foreach ($question->options as $option)
                            <label class="flex items-center space-x-2 mb-1">
                                <input type="radio" name="question_{{ $question->id }}" value="{{ $option->id }}"
                                    wire:model="answers.{{ $question->id }}" class="radio" />
                                <span>{{ $option->option }}</span>
                            </label>
                        @endforeach
                    @endif


                </div>
            @endforeach
            <x-slot:actions>
                <x-button label="Cancel" onclick="window.history.back()" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
