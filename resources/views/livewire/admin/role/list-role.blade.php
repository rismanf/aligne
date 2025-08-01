
<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

    <x-card >
   
        
        <x-hr target="gotoPage" />
        <x-table
            class="text-xs"
            :headers="$t_headers"
            :rows="$roles"
            with-pagination
            {{-- per-page="[1]" --}}
            {{-- :per-page-values="[1]"  --}}
        >
             {{-- Special `actions` slot --}}
          
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Role" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-input label="Name" icon="o-user" wire:model="name" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}
         
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save"  />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteModal" title="Are you sure?" subtitle="delete this user?">
        <x-form no-separator>
     
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
                <x-button label="Confirm" class="btn-primary" @click="$wire.deleteRole({{ $selectedUserId }})" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
    
</div>
