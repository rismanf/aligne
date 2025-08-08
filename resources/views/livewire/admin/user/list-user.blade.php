<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

    <x-card>
        <div class="flex justify-between items-center mb-4 gap-4">
            <div class="flex gap-4 flex-1">
                <x-input label="Search" placeholder="Search by name or email" wire:model.live="search" class="flex-1" />
                <x-select label="Filter by Role" wire:model.live="roleFilter" :options="$roles" option-value="name"
                    option-label="name" placeholder="All Roles" class="w-48" />
            </div>
            <x-button label="Add" icon="o-plus" @click="$wire.createForm = true" class="btn-primary btn-xs p-2" />
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$users" with-pagination {{-- per-page="[1]" --}}
            {{-- :per-page-values="[1]"  --}}>
            {{-- Special `avatar` slot --}}
            @scope('cell_avatar', $user)
                <x-avatar image="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('/image/empty-user.webp') }}"
                    class="!w-12 !h-12" />
            @endscope
            {{-- Special `actions` slot --}}
            @scope('cell_action', $user)
                <div class="flex gap-2">
                    @if ($this->canEditUser($user))
                        @if ($user->roles->first()?->name == 'Admin')
                            <x-button icon="o-pencil" wire:click="showEditModal({{ $user->id }})" spinner
                                class="btn-xs btn-primary" />
                        @endif
                    @endif
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $user->id }})" spinner
                        class="btn-xs btn-error" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New User" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-file label="Avatar" wire:model="avatar" accept="image/png, image/jpeg" crop-after-change>
                <img src="{{ asset('/image/empty-user.webp') }}" class="h-36 rounded-lg" />
            </x-file>
            <x-input label="Name" icon="o-user" wire:model="name" />
            <x-input label="Email" icon="o-at-symbol" wire:model="email" />
            <x-password label="Password" wire:model="password" password-icon="o-lock-closed"
                password-visible-icon="o-lock-open" />
            <x-password label="Retype Password" wire:model="retype_password" password-icon="o-lock-closed"
                password-visible-icon="o-lock-open" />

            <x-select label="Role" wire:model="role" :options="$roles" option-value="name" option-label="name" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-edit-muncul --}}
    <x-modal wire:model="editForm" title="Edit User" class="backdrop-blur">
        <x-form wire:submit="update">
            <x-file label="Avatar" wire:model="editAvatar" accept="image/png, image/jpeg" crop-after-change>
                @if ($editUserId)
                    @php
                        $editUser = \App\Models\User::find($editUserId);
                    @endphp
                    <img src="{{ $editUser && $editUser->avatar ? asset('storage/' . $editUser->avatar) : asset('/image/empty-user.webp') }}"
                        class="h-36 rounded-lg" />
                @else
                    <img src="{{ asset('/image/empty-user.webp') }}" class="h-36 rounded-lg" />
                @endif
            </x-file>
            <x-input label="Name" icon="o-user" wire:model="editName" />
            <x-input label="Email" icon="o-at-symbol" wire:model="editEmail" />
            <x-select label="Role" wire:model="editRole" :options="$roles" option-value="name" option-label="name" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false; $wire.resetEditForm()" />
                <x-button label="Update" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteModal" title="Are you sure?" subtitle="delete this user?">
        <x-form no-separator>

            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
                <x-button label="Confirm" class="btn-primary" @click="$wire.deleteUser({{ $selectedUserId }})"
                    spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
