<div>
    <x-card>
        <div class="flex justify-between items-center gap-2">
            {{-- Filter kiri --}}
            <div class="flex gap-1">
                <x-select wire:model="select_status" :options="$status_list" placeholder="Filter by Status"
                    wire:change="gotoPage(1)" />
            </div>
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$transactions" with-pagination>
            {{-- Total Price formatting --}}
            @scope('cell_total_price', $transaction)
                IDR {{ number_format($transaction->total_price, 0, ',', '.') }}
            @endscope

            {{-- Payment Status Badge --}}
            @scope('cell_payment_status', $transaction)
                <span class="badge {{ $this->getStatusBadgeClass($transaction->payment_status) }}">
                    {{ ucfirst($transaction->payment_status) }}
                </span>
            @endscope

            {{-- Date formatting --}}
            @scope('cell_created_at', $transaction)
                {{ $transaction->created_at->format('d M Y H:i') }}
            @endscope

            {{-- Actions --}}
            @scope('cell_action', $transaction)
                <div class="flex gap-1">
                    @if($transaction->payment_status === 'pending')
                        <x-button label="Review" icon="o-eye" wire:click="showEditModal({{ $transaction->id }})" 
                            class="btn-xs btn-primary" />
                    @elseif($transaction->payment_status === 'paid')
                        <span class="text-green-600 text-xs">✓ Confirmed</span>
                    @else
                        <x-button label="View" icon="o-eye" wire:click="showEditModal({{ $transaction->id }})" 
                            class="btn-xs btn-ghost" />
                    @endif
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- Payment Review Modal --}}
    <x-modal wire:model="editForm" title="Payment Review" class="backdrop-blur">
        <x-form wire:submit="update">
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold mb-2">Transaction Details</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div><strong>Invoice:</strong> {{ $invoice_number }}</div>
                        <div><strong>Customer:</strong> {{ $name }}</div>
                        <div><strong>Package:</strong> {{ $product }}</div>
                        <div><strong>Payment Method:</strong> {{ $payment_method }}</div>
                        <div><strong>Amount:</strong> IDR {{ number_format($total_price, 0, ',', '.') }}</div>
                        <div><strong>Paid At:</strong> {{ $paid_at ? \Carbon\Carbon::parse($paid_at)->format('d M Y H:i') : '-' }}</div>
                    </div>
                </div>

                @if($payment_proof)
                    <div>
                        <h4 class="font-semibold mb-2">Payment Proof</h4>
                        <div class="border rounded-lg p-2">
                            <img src="{{ asset('storage/' . $payment_proof) }}" 
                                 alt="Payment Proof" 
                                 class="max-w-full h-auto max-h-96 mx-auto rounded" />
                        </div>
                    </div>
                @endif

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-sm text-yellow-800">
                        <strong>Note:</strong> Confirming this payment will activate the user's membership and allocate class quotas automatically.
                    </p>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Reject" class="btn-error" wire:click="rejectPayment({{ $id }})" 
                    onclick="return confirm('Are you sure you want to reject this payment?')" />
                <x-button label="Confirm Payment" class="btn-success" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
