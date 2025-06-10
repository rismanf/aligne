<div>
    <x-card>
        <x-form wire:submit="save">
            <x-input label="Title" wire:model="title" />
            <x-file label="Cover Image" wire:model="image" accept="image/*" />
            <x-input label="Description" wire:model="description" />
            <x-input label="Keywords" wire:model="keywords" />
            <x-input label="Author" wire:model="author" hint="If blank it will be set to Admin" />


            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}
            <x-editor wire:model="body" label="Body" />
            <x-slot:actions>
                <x-button label="Cancel" onclick="window.history.back()" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>

</div>

<script src="https://cdn.tiny.cloud/1/{{ config('app.tinymce') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin">
</script>
