<div>
    <!-- Header with Filters -->
    <x-card>
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">🏋️ Trainer Management</h2>
                <p class="text-sm text-gray-600">Manage and view all trainers</p>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <x-input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search trainers..." 
                    icon="o-magnifying-glass"
                    class="w-full sm:w-64" />
                
                <x-select 
                    wire:model.live="status_filter" 
                    :options="[
                        ['id' => 'all', 'name' => 'All Status'],
                        ['id' => 'active', 'name' => 'Active Trainers'],
                        ['id' => 'inactive', 'name' => 'Inactive Trainers']
                    ]"
                    class="w-full sm:w-40" />
                
                <x-button 
                    label="Add Trainer" 
                    icon="o-plus" 
                    wire:click="showAddModal()" 
                    class="btn-primary btn-sm" />
            </div>
        </div>
    </x-card>

    <!-- Trainers Table -->
    <x-card class="mt-4">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left">#</th>
                        <th class="text-left">Avatar</th>
                        <th class="text-left">
                            <button wire:click="sortBy('name')" class="flex items-center gap-1 hover:text-primary">
                                Name
                                @if($sortBy === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                @endif
                            </button>
                        </th>
                        <th class="text-left">
                            <button wire:click="sortBy('title')" class="flex items-center gap-1 hover:text-primary">
                                Title
                                @if($sortBy === 'title')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                @endif
                            </button>
                        </th>
                        <th class="text-center">Status</th>
                        <th class="text-center">
                            <button wire:click="sortBy('updated_at')" class="flex items-center gap-1 hover:text-primary">
                                Last Updated
                                @if($sortBy === 'updated_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                @endif
                            </button>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainers as $trainer)
                        <tr class="hover:bg-gray-50">
                            <td class="text-center">{{ $trainer->row_number }}</td>
                            <td>
                                <x-avatar 
                                    image="{{ $trainer->avatar ? asset('storage/' . $trainer->avatar) : asset('/image/empty-user.webp') }}"
                                    class="!w-12 !h-12" />
                            </td>
                            <td>
                                <div>
                                    <div class="font-semibold">{{ $trainer->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $trainer->id }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm">{{ $trainer->title ?? 'No title' }}</span>
                            </td>
                            <td class="text-center">
                                @if($trainer->is_active)
                                    <span class="badge badge-success gap-1">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-error gap-1">
                                        <i class="fas fa-times-circle"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="text-sm">
                                    {{ $trainer->updated_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $trainer->updated_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="flex gap-1 justify-center">
                                    <x-button 
                                        icon="o-eye" 
                                        wire:click="showDetailModal({{ $trainer->id }})" 
                                        class="btn-xs btn-outline btn-primary"
                                        tooltip="View Details" />
                                    <x-button 
                                        icon="o-pencil" 
                                        wire:click="showEditModal({{ $trainer->id }})" 
                                        class="btn-xs btn-outline btn-warning"
                                        tooltip="Edit" />
                                    <x-button 
                                        icon="o-trash" 
                                        wire:click="showDeleteModal({{ $trainer->id }})" 
                                        class="btn-xs btn-outline btn-error"
                                        tooltip="Delete" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="text-gray-500">
                                    <i class="fas fa-user-tie text-4xl mb-3"></i>
                                    <p>No trainers found</p>
                                    @if($search || $status_filter !== 'all')
                                        <p class="text-sm">Try adjusting your filters</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($trainers->hasPages())
            <div class="mt-4">
                {{ $trainers->links() }}
            </div>
        @endif
    </x-card>

    <!-- Create Modal -->
    <x-modal wire:model="createForm" title="Add New Trainer" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-file label="Avatar" wire:model="avatar" accept="image/png, image/jpeg" crop-after-change>
                <img src="{{ asset('/image/empty-user.webp') }}" class="h-36 rounded-lg" />
            </x-file>

            <x-input label="Name" wire:model="name" required />
            <x-input label="Title" wire:model="title" placeholder="e.g., Fitness Trainer, Yoga Instructor" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false; $wire.resetForm()" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Edit Modal -->
    <x-modal wire:model="editForm" title="Edit Trainer" class="backdrop-blur">
        <x-form wire:submit="update">
            <x-file label="Avatar" wire:model="avatar" accept="image/png, image/jpeg" crop-after-change>
                <img src="{{ $trainer && $trainer->avatar ? asset('storage/' . $trainer->avatar) : asset('/image/empty-user.webp') }}" 
                     class="h-36 rounded-lg" />
            </x-file>

            <x-input label="Name" wire:model="name" required />
            <x-input label="Title" wire:model="title" placeholder="e.g., Fitness Trainer, Yoga Instructor" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false; $wire.resetForm()" />
                <x-button label="Update" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Detail Modal -->
    <x-modal wire:model="detailForm" title="Trainer Details" class="backdrop-blur" box-class="!max-w-2xl">
        @if($selectedTrainer)
            <div class="space-y-6">
                <!-- Trainer Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center gap-4">
                        <x-avatar 
                            image="{{ $selectedTrainer->avatar ? asset('storage/' . $selectedTrainer->avatar) : asset('/image/empty-user.webp') }}"
                            class="!w-20 !h-20" />
                        <div>
                            <h3 class="text-xl font-semibold">{{ $selectedTrainer->name }}</h3>
                            <p class="text-gray-600">{{ $selectedTrainer->title ?? 'No title specified' }}</p>
                            <p class="text-sm text-gray-500">Trainer ID: {{ $selectedTrainer->id }}</p>
                            <p class="text-sm text-gray-500">
                                Status: 
                                @if($selectedTrainer->is_active)
                                    <span class="text-green-600 font-medium">Active</span>
                                @else
                                    <span class="text-red-600 font-medium">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Created</h4>
                        <p class="text-sm">{{ $selectedTrainer->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $selectedTrainer->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Last Updated</h4>
                        <p class="text-sm">{{ $selectedTrainer->updated_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $selectedTrainer->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Description</h4>
                    <p class="text-sm text-gray-600">{{ $description }}</p>
                </div>
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Close" @click="$wire.detailForm = false; $wire.resetForm()" />
        </x-slot:actions>
    </x-modal>

    <!-- Delete Modal -->
    <x-modal wire:model="deleteForm" title="Delete Trainer" subtitle="Are you sure you want to delete this trainer?" class="backdrop-blur">
        @if($trainer)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-3">
                    <x-avatar 
                        image="{{ $trainer->avatar ? asset('storage/' . $trainer->avatar) : asset('/image/empty-user.webp') }}"
                        class="!w-12 !h-12" />
                    <div>
                        <p class="font-semibold text-red-800">{{ $trainer->name }}</p>
                        <p class="text-sm text-red-600">{{ $trainer->title ?? 'No title' }}</p>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                This action cannot be undone. All data associated with this trainer will be permanently deleted.
            </p>
        @endif

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.deleteForm = false; $wire.resetForm()" />
            <x-button label="Delete" class="btn-error" wire:click="delete" spinner="delete" />
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
        
        .badge-error {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-outline {
            background-color: transparent;
            border: 1px solid #d1d5db;
            color: #6b7280;
        }

        .btn-xs {
            padding: 4px 8px;
            font-size: 12px;
            min-height: 24px;
            height: 24px;
        }

        .btn-outline {
            background-color: transparent;
            border-width: 1px;
        }

        .btn-outline:hover {
            background-color: currentColor;
            color: white;
        }
    </style>
</div>
