<div>
    <x-card>
        <div class="flex justify-between items-center gap-2">
            {{-- Filter kiri --}}
            <div class="flex gap-1">
                <x-select wire:model="select_status" :options="$status_list" placeholder="Filter by Status"
                    wire:change="gotoPage(1)" />
              
            </div>
        </div>
        <div class="flex justify-end mb-4">
            {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
            <x-button label="Add" icon="o-plus" wire:click="showAddModal()" class="btn-primary btn-xs p-2" />
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$userproduct" with-pagination>
            {{-- Special `row_number` scope --}}

            @scope('cell_avatar', $userproduct)
                IDR {{ number_format($userproduct->total_price, 0, ',', '.') }}
            @endscope
            {{-- Special `actions` slot --}}
            @scope('cell_action', $userproduct)
                <div class="flex gap-1">
                    <x-button label="confirm" icon="o-pencil" wire:click="showEditModal({{ $userproduct->id }})" spinner
                        class="btn-xs" />
                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="editForm" title="Detail payment" class="backdrop-blur">
        <x-form wire:submit="update">
            <p>Invoice # {{ $invoice_number }}</p>
            <p>Name : {{ $name }}</p>
            <p>Membership : {{ $product }}</p>
            <p>Bank : {{ $payment_method }}</p>
            <p>Price : IDR {{ number_format($total_price, 0, ',', '.') }}</p>
            <img src="{{ '/storage/' . $payment_proof }}" alt="payment_proof" class="w-1/2" />
            <p>Payed At :{{ $paid_at }}</p>

            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Confirm" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
