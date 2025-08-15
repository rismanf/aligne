<div>
    <!-- Baris 1: Statistik Member -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">📊 Member Statistics</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat title="💳 Total Member" value="{{ number_format($total_members) }}" icon="o-user-group"
                tooltip-bottom="Total registered members" color="text-blue-600"
                wire:click="showDetailModal('Total Member')"
                class="cursor-pointer hover:shadow-lg transition-shadow bg-blue-50 border-blue-200" />

            <x-stat title="✅ Member Aktif" value="{{ number_format($active_members) }}" icon="o-check-circle"
                tooltip-bottom="Active approved members" color="text-green-600"
                wire:click="showDetailModal('Active Member')"
                class="cursor-pointer hover:shadow-lg transition-shadow bg-green-50 border-green-200" />

            <x-stat title="❌ Member Tidak Aktif" value="{{ number_format($inactive_members) }}" icon="o-x-circle"
                tooltip-bottom="Inactive or pending members" color="text-red-600"
                wire:click="showDetailModal('Inactive Member')"
                class="cursor-pointer hover:shadow-lg transition-shadow bg-red-50 border-red-200" />
        </div>
    </div>

    <!-- Baris 2: Statistik Transaksi (dengan filter tanggal) -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">💰 Transaction Statistics</h3>
            <div class="flex gap-2">
                <x-input wire:model.live="transaction_date_from" type="date" placeholder="From Date"
                    class="input-sm" />
                <x-input wire:model.live="transaction_date_to" type="date" placeholder="To Date" class="input-sm" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat title="📦 Total Transaksi" value="{{ number_format($total_transactions) }}" icon="o-document-text"
                tooltip-bottom="Total transactions in selected period" color="text-purple-600"
                wire:click="showDetailModal('Total Transaksi')"
                class="cursor-pointer hover:shadow-lg transition-shadow bg-purple-50 border-purple-200" />

            <x-stat title="💰 Paid" value="{{ number_format($paid_transactions) }}" icon="o-check-badge"
                tooltip-bottom="Successfully paid transactions" color="text-green-600"
                class="bg-green-50 border-green-200" />

            <x-stat title="⏳ Waiting Confirmation" value="{{ number_format($waiting_confirmation) }}" icon="o-clock"
                tooltip-bottom="Transactions waiting for confirmation" color="text-yellow-600"
                class="bg-yellow-50 border-yellow-200" />

            <x-stat title="🕓 Pending Payment" value="{{ number_format($pending_payment) }}"
                icon="o-exclamation-triangle" tooltip-bottom="Transactions pending payment" color="text-orange-600"
                class="bg-orange-50 border-orange-200" />
        </div>
    </div>

    <!-- Baris 3: Statistik Trainer (dengan filter tanggal) -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">👨‍🏫 Trainer Statistics</h3>
            <div class="flex gap-2">
                <x-input wire:model.live="trainer_date_from" type="date" placeholder="From Date" class="input-sm" />
                <x-input wire:model.live="trainer_date_to" type="date" placeholder="To Date" class="input-sm" />
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Total Trainer Card -->
            <div class="lg:col-span-1">
                <x-stat title="👥 Jumlah Trainer" value="{{ number_format($total_trainers) }}" icon="o-academic-cap"
                    tooltip-bottom="Total registered trainers" color="text-indigo-600"
                    class="bg-indigo-50 border-indigo-200 h-full" />
            </div>

            <!-- Trainer Classes Table -->
            <div class="lg:col-span-2">
                <x-card title="📋 Trainer Performance" class="h-full">
                    @if (count($trainer_classes) > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-sm w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="text-left">Trainer Name</th>
                                        <th class="text-center">Total Classes</th>
                                        <th class="text-left">Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trainer_classes as $trainer)
                                        <tr class="hover:bg-gray-50">
                                            <td>
                                                <div class="font-medium text-gray-900">{{ $trainer['name'] }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-primary">{{ $trainer['total_classes'] }}</span>
                                            </td>
                                            <td>
                                                <div class="text-sm text-gray-600">
                                                    <div>{{ $trainer['email'] }}</div>
                                                    @if ($trainer['phone'] !== '-')
                                                        <div>{{ $trainer['phone'] }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-user-slash text-4xl mb-3"></i>
                            <p>No trainer data available for selected period</p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>

    
    <!-- Modal Detail -->
    <x-modal wire:model="showModal" title="{{ $type }} Details" class="backdrop-blur"
        box-class="!max-w-6xl">
        @if (count($data_list) > 0)
            <x-table class="text-xs" :headers="$t_headers" :rows="$this->paginatedDataList">
                @scope('cell_status', $row)
                    @if (isset($row['status']))
                        @php
                            $statusClass = match ($row['status']) {
                                'Approved' => 'badge-success',
                                'Waiting' => 'badge-warning',
                                'Rejected' => 'badge-error',
                                'paid' => 'badge-success',
                                'waiting_confirmation' => 'badge-warning',
                                'pending' => 'badge-error',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <x-badge value="{{ ucfirst($row['status']) }}" class="{{ $statusClass }}" />
                    @endif
                @endscope

                @scope('cell_total_amount', $row)
                    @if (isset($row['total_amount']))
                        <span class="font-semibold text-green-600">
                            Rp {{ number_format($row['total_amount'], 0, ',', '.') }}
                        </span>
                    @endif
                @endscope

                @scope('cell_created_at', $row)
                    @if (isset($row['created_at']))
                        {{ \Carbon\Carbon::parse($row['created_at'])->format('d M Y H:i') }}
                    @endif
                @endscope
            </x-table>

            <!-- Pagination -->
            <div class="mt-4 flex justify-between items-center">
                <button wire:click="previousPage" class="btn btn-sm btn-outline"
                    @if ($currentPage === 1) disabled @endif>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>

                <span class="text-sm text-gray-600">
                    Page {{ $currentPage }} of {{ $this->totalPages }}
                    ({{ count($data_list) }} total records)
                </span>

                <button wire:click="nextPage" class="btn btn-sm btn-outline"
                    @if ($currentPage === $this->totalPages) disabled @endif>
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>No data available</p>
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Close" @click="$wire.showModal = false" />
        </x-slot:actions>
    </x-modal>

    <!-- Custom Styles -->
    <style>
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #374151;
            padding: 12px;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
        }

        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-primary {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-secondary {
            background-color: #f1f5f9;
            color: #475569;
        }
    </style>
</div>
