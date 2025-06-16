<div>
    <x-card>


        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$contact" with-pagination {{-- per-page="[1]" --}}
            {{-- :per-page-values="[1]"  --}}>
            {{-- Special `actions` slot --}}
            @scope('cell_action', $contact)
                <div class="flex gap-1">
                    <x-button icon="o-eye" class="btn-xs" wire:click="showDetailModal({{ $contact->id }})" />
                </div>
            @endscope
        </x-table>
    </x-card>



    {{-- modal-show-muncul --}}
    <x-modal wire:model="DetailModal" title="Contact Us Details" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-slot:header>
                <h1 class="text-lg font-semibold">Contact Us Details</h1>
            </x-slot:header>
            @if (!empty($detail_contact))
                <h1 class="text-xs mb-2">Full Name: {{ $detail_contact->full_name }}</h1>
                <h1 class="text-xs mb-2">Email: {{ $detail_contact->email }}</h1>
                <h1 class="text-xs mb-2">Phone: {{ $detail_contact->phone }}</h1>
                <h1 class="text-xs mb-2">Company: {{ $detail_contact->company }}</h1>
                <h1 class="text-xs mb-2">Job Title: {{ $detail_contact->job_title }}</h1>
                <h1 class="text-xs mb-2">Message :</h1>
                <h1 class="text-xs mb-2">
                    {{ $detail_contact->message }}
                </h1>
            @endif
            <x-slot:actions>
                <x-button label="Close" @click="$wire.DetailModal = false" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
