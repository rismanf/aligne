<div>
    <x-card>
        <x-hr target="gotoPage" />
        <div class="flex gap-1">
            <x-select wire:model="select_status" :options="$status" placeholder="Filter by Status"
                wire:change="gotoPage(1)" />
            <x-select wire:model="select_type_participant" :options="$type_participant" placeholder="Filter by Type"
                wire:change="gotoPage(1)" />
            <x-select wire:model="select_topic" :options="$options_topic" placeholder="Filter by Topic"
                wire:change="gotoPage(1)" />
            <x-select wire:model="select_golf" :options="$options_golf" placeholder="Filter by Golf"
                wire:change="gotoPage(1)" />
        </div>
        <p class="text-xs text-gray-500">
            @php
                $filters = [];

                if ($select_status) {
                    $filters[] = $select_status;
                }
                if ($select_type_participant) {
                    $filters[] = $select_type_participant;
                }
                if ($select_topic) {
                    $filters[] = $select_topic;
                }
                if ($select_golf) {
                    $filters[] = $select_golf;
                }
            @endphp

            @if (count($filters) > 0)
                <p class="text-xs text-gray-500">
                    Filter: {{ implode(' | ', $filters) }}
                </p>
            @endif
        </p>
        <x-table class="text-xs" :headers="$t_headers" :rows="$participants" :sort-by="$sortBy" with-pagination>
            {{-- Special `actions` slot --}}

            @scope('cell_topic_answers', $participants)
                {{ $participants->answers->where('question_id', 5)->pluck('answer')->implode(', ') }}
            @endscope
            @scope('cell_golf_answers', $participants)
                {{ $participants->answers->where('question_id', 6)->pluck('answer')->implode(', ') }}
            @endscope
            @scope('cell_action', $participants)
                <div class="flex gap-1">
                    @can('participant-edit')
                        @if ($participants->status == 'Waiting')
                            <x-button icon="o-check-circle" class="btn-xs btn-success"
                                wire:click="showConfirmModal({{ $participants->id }}, 'Approved')" tooltip="Approve" />

                            <x-button icon="o-x-circle" class="btn-xs btn-error"
                                wire:click="showConfirmModal({{ $participants->id }}, 'Rejected')" tooltip="Reject" />
                        @endif
                    @endcan
                    <x-button icon="o-eye" class="btn-xs" wire:click="showDetailModal({{ $participants->id }})"
                        tooltip="Detail" />
                </div>
            @endscope
        </x-table>

    </x-card>

    {{-- modal-show-muncul --}}
    <x-modal wire:model="showModal" title="Participant Details" class="backdrop-blur">
        <x-form wire:submit="save">
            @if (!empty($detail_participants))
                <h1 class="text-xs mb-2">Type: {{ $detail_participants->user_type }}</h1>
                <h1 class="text-xs mb-2">Full Name: {{ $detail_participants->full_name }}</h1>
                <h1 class="text-xs mb-2">Email: {{ $detail_participants->email }}</h1>
                <h1 class="text-xs mb-2">Phone: {{ $detail_participants->phone }}</h1>
                <h1 class="text-xs mb-2">Company: {{ $detail_participants->company }}</h1>
                <h1 class="text-xs mb-2">Job Title: {{ $detail_participants->job_title }}</h1>
                <h1 class="text-xs mb-2">Country: {{ $detail_participants->country }}</h1>
                <h1 class="text-xs mb-2">Industry: {{ $detail_participants->industry }}</h1>
                @if ($detail_participants->user_type_id == 1)
                    <hr>
                    <h1 class="text-xs mb-2">Answer:</h1>
                    @foreach ($detail_participants->answers as $val)
                        <h1 class="text-xs mb-2">{{ $val->question }}: {{ $val->answer }}</h1>
                    @endforeach
                @endif

            @endif
            <x-slot:actions>
                <x-button label="Close" @click="$wire.showModal = false" />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteModal" title="Are you sure?" subtitle="delete this user?">
        <x-form no-separator>
            @if (!empty($detail_participants))
                <h1 class="text-xs mb-2">Full Name: {{ $detail_participants->full_name }}</h1>
                <h1 class="text-xs mb-2">Email: {{ $detail_participants->email }}</h1>
            @endif
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
                <x-button label="Confirm" class="btn-primary" @click="$wire.deleteParticipant({{ $selectedUserId }})"
                    spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-confrim-muncul --}}
    <x-modal wire:model="confirmModal" title="Are you sure {{ $status_participant }}?" subtitle="please confirm">
        <x-form no-separator>
            <div class="text-xs  mb-4">
                @if (!empty($detail_participants))
                    <h1 class="text-xs mb-2">Type: {{ $detail_participants->user_type }}</h1>
                    <h1 class="text-xs mb-2">Full Name: {{ $detail_participants->full_name }}</h1>
                    <h1 class="text-xs mb-2">Email: {{ $detail_participants->email }}</h1>
                    <h1 class="text-xs mb-2">Phone: {{ $detail_participants->phone }}</h1>
                    <h1 class="text-xs mb-2">Company: {{ $detail_participants->company }}</h1>
                    <h1 class="text-xs mb-2">Job Title: {{ $detail_participants->job_title }}</h1>
                    <h1 class="text-xs mb-2">Country: {{ $detail_participants->country }}</h1>
                    <h1 class="text-xs mb-2">Industry: {{ $detail_participants->industry }}</h1>
                @endif
            </div>
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.confirmModal = false" />
                <x-button label="Confirm" class="btn-primary"
                    @click="$wire.confirm({{ $selectedUserId }}, '{{ $status_participant }}')" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
