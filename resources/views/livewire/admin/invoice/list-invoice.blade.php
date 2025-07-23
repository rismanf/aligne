<div>
    <x-card>


        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$invoice">
            {{-- Special `actions` slot --}}
            @scope('cell_total_price', $invoice)
                Rp {{ number_format($invoice->total_price, 0, ',', '.') }}
            @endscope

            @scope('cell_action', $invoice)
                @if ('{{ $invoice->status }}' === 'pending')
                    <div class="flex gap-1">
                        <x-button icon="o-pencil"
                            onclick="window.location.href='{{ route('admin.participant.edit', $invoice->id) }}'"
                            class="btn-xs" />
                        <x-button icon="o-trash" wire:click="showDeleteModal({{ $invoice->id }})" spinner class="btn-xs" />
                    </div>
                @endif

                <div class="flex gap-1">
                    <x-button icon="o-eye" class="btn-xs" wire:click="showDetailModal({{ $invoice->id }})" />
                </div>
            @endscope
        </x-table>

    </x-card>

    {{-- modal-confrim-muncul --}}
    <x-modal wire:model="detailModal" title="Your Invoice Details" class="backdrop-blur">
        <x-form no-separator>
            <div class="mb-4">
                <h1>#{{ $invoice_code }}</h1>
            </div>
            <div class="text-xs mb-4">
                <h4>Invoice Number: {{ $invoice_code }}</h4>
                <h1>Total Participants: {{ $total_participant }}</h1>
                <h1>Total Price: Rp {{ number_format($total_price, 0, ',', '.') }}</h1>
                <hr class="my-2" />
                <h2>Detail Participants:</h2>
                <ul>
                    @foreach ($detail_participants as $key => $participant)
                        <li>
                            {{ $key + 1 }}. {{ $participant['full_name'] }} - {{ $participant['email'] }} -
                            {{ $participant['status'] }}
                        </li>
                    @endforeach
                </ul>
            </div>
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.detailModal = false" />
                @if ($invoice_status == 'unpaid')
                    <x-button label="Confirm" class="btn-primary" @click="$wire.confirm({{ $invoice_id }})"
                        spinner />
                @endif
                @if ($invoice_status == 'Waiting Payment Confirmation')
                    @can('invoice-approve')
                        <x-button label="Approve" class="btn-success" @click="$wire.approve({{ $invoice_id }})"
                            spinner />
                    @endcan
                @endif
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
