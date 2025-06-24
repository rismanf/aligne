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
</div>
