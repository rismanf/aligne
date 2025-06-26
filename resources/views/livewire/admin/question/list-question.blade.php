<div>
    <x-card>
        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$quests"   with-pagination>
            {{-- Special `actions` slot --}}
            @scope('cell_action', $quests)
                @can('participant-edit')
                    <div class="flex gap-1">
                        <x-button icon="o-eye" class="btn-xs" wire:click="showDetailModal({{ $quests->id }})" />
                    </div>
                @endcan
            @endscope
        </x-table>

    </x-card>

     {{-- modal-show-muncul --}}
    <x-modal wire:model="showModal" title="Question Details" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-input label="Question" wire:model="question" />
            @if($question_options)
            <label class="block text-sm font-medium text-gray-700">Options</label>
                @foreach($question_options as $option)
                    <x-input  wire:model="option.{{ $option->id }}" value="{{ $option->option }}"/>
                @endforeach
            @endif
            <x-slot:actions>
                <x-button label="Close" @click="$wire.showModal = false" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
