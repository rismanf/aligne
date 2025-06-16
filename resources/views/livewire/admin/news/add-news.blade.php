<div>
    <x-card>
        <x-form wire:submit="save">
            <x-input label="Title" wire:model="title" required />
            <x-file label="Cover Image" wire:model="image" accept="image/*" required />
            <x-input label="Description" wire:model="description" />
            <x-input label="Keywords" wire:model="keywords" />
            <x-input label="Author" icon="o-user" wire:model="author" hint="If blank it will be set to Admin" />

            <x-choices label="Tags" icon="o-hashtag" wire:model="tag_list_ids" :options="$tag_list" allow-all clearable>
                <x-slot:append>
                    {{-- Add `join-item` to all appended elements --}}
                    <x-button label="Create" icon="o-plus" wire:click="showTagModal()" class="join-item btn-primary" />
                </x-slot:append>
            </x-choices>

            <div class="flex items-center gap-2">
                <x-toggle label="Publish" wire:click="togglePublished()" hint="If checked it will be published" />
                <div class="flex items-center gap-2 {{ $published ? '' : 'hidden' }}">
                    <x-datetime label="Publish date" wire:model="published_at" min="{{ now()->format('Y-m-d') }}" />
                </div>
            </div>


            <x-editor wire:model="body" label="Body" />
            <x-slot:actions>
                <x-button label="Cancel" onclick="window.history.back()" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>


    {{-- modal-tag-muncul --}}
    <x-modal wire:model="TagModal" title="New Tag" class="backdrop-blur">
        <x-form wire:submit="savetags">
            <x-input label="Tag" icon="o-hashtag" wire:model="name_tag" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.TagModal = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>

<script src="https://cdn.tiny.cloud/1/{{ config('app.tinymce') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin">
</script>
