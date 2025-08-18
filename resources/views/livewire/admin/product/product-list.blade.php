<div>
    @php
        $config = [
            'spellChecker' => true,
            'toolbar' => ['heading', 'bold', 'italic', '|', 'preview'],
            'maxHeight' => '70px',
        ];

        $config_money = [
            'prefix' => '',
            'thousands' => ',',
            'decimal' => '.',
            'precision' => 0,
        ];
    @endphp

    <x-card>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Membership Package Management</h2>
            <div class="flex gap-2">
                <x-button label="Add New Package" icon="o-plus" wire:click="showAddModal()" class="btn-primary" />
            </div>
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$products">
            {{-- Special scopes for custom display --}}
            @scope('cell_category_display', $products)
                @if ($products->category_is_active == 1)
                    <span class="badge badge-outline badge-sm">{{ $products->category_display }}</span>
                @else
                    <span class="badge badge-error badge-sm">{{ $products->category_display }}</span>
                @endif
            @endscope

            @scope('cell_formatted_price', $products)
                <span class="font-semibold text-green-600">{{ $products->formatted_price }}</span>
            @endscope

            @scope('cell_total_classes', $products)
                <span class="badge badge-primary badge-sm">{{ $products->total_classes }}x</span>
            @endscope

            @scope('cell_validity_text', $products)
                <span class="text-sm text-gray-600">{{ $products->validity_text }}</span>
            @endscope
            @scope('cell_is_active', $products)
                @if ($products->is_active)
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-error">Inactive</span>
                @endif
            @endscope

            @scope('cell_is_popular', $products)
                @if ($products->is_popular)
                    <span class="badge badge-success">Popular</span>
                @else
                    -
                @endif
            @endscope

            @scope('cell_updated_at', $products)
                <span class="text-sm text-gray-500">{{ $products->updated_at->format('d M Y') }}</span>
            @endscope

            {{-- Special `actions` slot --}}
            @scope('cell_action', $products)
                <div class="flex gap-1 justify-center">
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $products->id }})" spinner
                        class="btn-xs btn-info" tooltip="View Details" />
                    <x-button icon="o-pencil" wire:click="showEditModal({{ $products->id }})" spinner
                        class="btn-xs btn-warning" tooltip="Edit Package" />
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $products->id }})" spinner
                        class="btn-xs btn-error" tooltip="Delete Package" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- Add pagination --}}
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="Create New Membership Package" class="backdrop-blur">
        <x-form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Package Name" wire:model="name"
                    placeholder="e.g., Elevate Pack 8x Reformer / Chair Class" />
                <x-select label="Package Category" wire:model="category" :options="$categories"
                    placeholder="Select Category" />
            </div>

            @if ($category === 'signature')
                <x-select label="Package Type" wire:model="package_type" :options="$signatureTypes"
                    placeholder="Select Package Type" />
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Price (IDR)" wire:model="price" placeholder="e.g., 1200000" />
                <x-input label="Validity Period" wire:model="valid_until" suffix="days" placeholder="e.g., 30" />
            </div>
            <x-checkbox label="Active" wire:model="isActive" />
            <x-checkbox label="Popular" wire:model="isPopular" />

            <div class="divider">Quota Strategy & Class Configuration</div>

            {{-- Quota Strategy Selection --}}
            <div class="alert alert-info mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="stroke-current shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-bold">Quota Strategy</h3>
                    <div class="text-xs">
                        <strong>Flexible:</strong> Total quota dapat digunakan untuk semua jenis kelas (contoh: Aligné
                        Flow 12x bisa untuk Reformer ATAU Chair)<br>
                        <strong>Fixed:</strong> Quota terpisah per jenis kelas (contoh: Aligné Lumina 4x Reformer DAN 2x
                        Chair)
                    </div>
                </div>
            </div>

            <x-radio label="Quota Strategy" wire:model.live="quota_strategy" :options="[
                ['id' => 'flexible', 'name' => 'Flexible - Shared quota across all class types'],
                ['id' => 'fixed', 'name' => 'Fixed - Specific quota per class type'],
            ]" />

            @if ($quota_strategy === 'flexible')
                <div class="alert alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="font-bold">Flexible Quota Mode</div>
                        <div class="text-xs">Semua kelas akan menggunakan quota yang sama. User dapat memilih kelas mana
                            saja yang tersedia.</div>
                    </div>
                </div>
            @elseif($quota_strategy === 'fixed')
                <div class="alert alert-warning mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <div>
                        <div class="font-bold">Fixed Quota Mode</div>
                        <div class="text-xs">Setiap jenis kelas memiliki quota terpisah. User hanya bisa menggunakan
                            quota sesuai jenis kelas.</div>
                    </div>
                </div>
            @endif

            @foreach ($class_kuotas as $index => $ck)
                <div class="border rounded-lg p-4 mb-4 bg-base-100">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold">Class {{ $index + 1 }}</h4>
                        @if ($index > 0)
                            <x-button icon="o-trash" wire:click="removeClassKuota({{ $index }})"
                                class="btn-xs btn-error" />
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-select label="Class Type" wire:model="class_kuotas.{{ $index }}.class_id"
                            :options="$class_list" placeholder="Select Class" />
                        @if ($quota_strategy === 'flexible')
                            <x-input label="Total Quota (Shared)" wire:model="class_kuotas.{{ $index }}.kuota"
                                placeholder="e.g., 12" hint="Quota ini akan dibagi untuk semua jenis kelas" />
                        @else
                            <x-input label="Quota for This Class Type"
                                wire:model="class_kuotas.{{ $index }}.kuota" placeholder="e.g., 4"
                                hint="Quota khusus untuk jenis kelas ini" />
                        @endif
                    </div>
                </div>
            @endforeach

            <x-button label="Add Another Class" icon="o-plus" wire:click="addClassKuota"
                class="btn-outline btn-sm mb-4" />

            <x-markdown label="Package Description" wire:model="description" :config="$config"
                placeholder="Describe the benefits and features of this package..." />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Create Package" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-edit-muncul --}}
    <x-modal wire:model="editForm" title="Edit Membership Package" class="backdrop-blur">
        <x-form wire:submit="update">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Package Name" wire:model="name" />
                <x-select label="Package Category" wire:model="category" :options="$categories" />
            </div>

            @if ($category === 'signature')
                <x-select label="Package Type" wire:model="package_type" :options="$signatureTypes" />
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Price (IDR)" wire:model="price" />
                <x-input label="Validity Period" wire:model="valid_until" suffix="days" />
            </div>
            <x-checkbox label="Active" wire:model="isActive" />
            <x-checkbox label="Popular" wire:model="isPopular" />
            <div class="divider">Quota Strategy & Class Configuration</div>

            {{-- Quota Strategy Selection --}}
            <div class="alert alert-info mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="stroke-current shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-bold">Quota Strategy</h3>
                    <div class="text-xs">
                        <strong>Flexible:</strong> Total quota dapat digunakan untuk semua jenis kelas (contoh: Aligné
                        Flow 12x bisa untuk Reformer ATAU Chair)<br>
                        <strong>Fixed:</strong> Quota terpisah per jenis kelas (contoh: Aligné Lumina 4x Reformer DAN 2x
                        Chair)
                    </div>
                </div>
            </div>

            <x-radio label="Quota Strategy" wire:model.live="quota_strategy" :options="[
                ['id' => 'flexible', 'name' => 'Flexible - Shared quota across all class types'],
                ['id' => 'fixed', 'name' => 'Fixed - Specific quota per class type'],
            ]" />

            @if ($quota_strategy === 'flexible')
                <div class="alert alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="font-bold">Flexible Quota Mode</div>
                        <div class="text-xs">Semua kelas akan menggunakan quota yang sama. User dapat memilih kelas
                            mana saja yang tersedia.</div>
                    </div>
                </div>
            @elseif($quota_strategy === 'fixed')
                <div class="alert alert-warning mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <div>
                        <div class="font-bold">Fixed Quota Mode</div>
                        <div class="text-xs">Setiap jenis kelas memiliki quota terpisah. User hanya bisa menggunakan
                            quota sesuai jenis kelas.</div>
                    </div>
                </div>
            @endif

            @foreach ($class_kuotas as $index => $ck)
                <div class="border rounded-lg p-4 mb-4 bg-base-100">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold">Class {{ $index + 1 }}</h4>
                        @if ($index > 0)
                            <x-button icon="o-trash" wire:click="removeClassKuota({{ $index }})"
                                class="btn-xs btn-error" />
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-select label="Class Type" wire:model="class_kuotas.{{ $index }}.class_id"
                            :options="$class_list" />
                        @if ($quota_strategy === 'flexible')
                            <x-input label="Total Quota (Shared)" wire:model="class_kuotas.{{ $index }}.kuota"
                                hint="Quota ini akan dibagi untuk semua jenis kelas" />
                        @else
                            <x-input label="Quota for This Class Type"
                                wire:model="class_kuotas.{{ $index }}.kuota"
                                hint="Quota khusus untuk jenis kelas ini" />
                        @endif
                    </div>
                </div>
            @endforeach

            <x-button label="Add Another Class" icon="o-plus" wire:click="addClassKuota" />

            <x-markdown label="Package Description" wire:model="description" :config="$config" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Update Package" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-detail-muncul --}}
    <x-modal wire:model="detailForm" title="Package Details" class="backdrop-blur">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700">Package Name</label>
                    <p class="text-lg">{{ $name }}</p>
                </div>
                <div>
                    <label class="font-semibold text-gray-700">Category</label>
                    <p><span class="badge badge-outline">
                            @php
                                $categoryMap = [
                                    'signature' => 'SIGNATURE CLASS PACK',
                                    'functional' => 'FUNCTIONAL MOVEMENT PACK',
                                    'vip' => 'VIP MEMBERSHIP',
                                    'special' => 'SPECIAL PACKAGES',
                                ];
                            @endphp
                            {{ $categoryMap[$category ?? 'signature'] ?? 'SIGNATURE CLASS PACK' }}
                        </span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700">Price</label>
                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($price ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <label class="font-semibold text-gray-700">Validity Period</label>
                    <p>{{ $valid_until ?? 0 }} days</p>
                </div>
            </div>

            @if ($package_type)
                <div>
                    <label class="font-semibold text-gray-700">Package Type</label>
                    <p>
                        @php
                            $typeMap = [
                                'core_series' => 'The Core Series',
                                'elevate_pack' => 'Elevate Pack',
                                'aligne_flow' => 'Aligné Flow',
                            ];
                        @endphp
                        {{ $typeMap[$package_type] ?? $package_type }}
                    </p>
                </div>
            @endif

            <div>
                <label class="font-semibold text-gray-700">Quota Strategy</label>
                <p>
                    @if (isset($quota_strategy))
                        @if ($quota_strategy === 'flexible')
                            <span class="badge badge-success">Flexible - Shared quota across all class types</span>
                        @else
                            <span class="badge badge-warning">Fixed - Specific quota per class type</span>
                        @endif
                    @else
                        <span class="badge badge-neutral">Not Set</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="font-semibold text-gray-700">Included Classes</label>
                <div class="mt-2 space-y-2">
                    @foreach ($class_kuotas as $class)
                        <div class="flex justify-between items-center p-3 bg-base-100 rounded-lg">
                            <div>
                                <span class="font-medium">{{ $class['class_name'] ?? 'N/A' }}</span>
                                <span class="badge badge-sm ml-2">{{ $class['class_category'] ?? 'N/A' }}</span>
                            </div>
                            <span class="badge badge-primary">{{ $class['kuota'] ?? 0 }}x Classes</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="font-semibold text-gray-700">Description</label>
                <div class="prose max-w-none mt-2">
                    {!! $description !!}
                </div>
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Close" @click="$wire.detailForm = false" />
        </x-slot:actions>
    </x-modal>

    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteForm" title="Delete Membership Package"
        subtitle="This action cannot be undone. All related data will be permanently deleted.">
        <div class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <span>Warning: This will permanently delete the package and all associated class configurations!</span>
        </div>

        <x-form no-separator>
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteForm = false" />
                <x-button label="Delete Package" class="btn-error" wire:click="delete" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
