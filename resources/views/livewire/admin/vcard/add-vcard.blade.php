<div>
    <x-card>
        <div class="grid gap-5 lg:grid-cols-2">
            <div>
                <x-form wire:submit="save">
                    <x-file label="Avatar" wire:model="foto" accept="image/png, image/jpeg" crop-after-change>
                        <img src="{{ $user->foto ?? '/image/empty-user.webp' }}" class="h-36 rounded-lg" />
                    </x-file>
                    <x-input label="Name" wire:model="name" required />
                    <x-input label="NIK" wire:model="nik" required />
                    <x-input label="Job Title" wire:model="job_title" required />
                    <x-input label="Phone" wire:model="phone" />
                    <x-input label="Email" wire:model="email" />
                    <hr />
                    <label class="text-lg font-semibold">Certificates</label>
                    @foreach ($certificates as $index => $certificate)
                        <div class="bg-base-200 p-4 rounded-xl space-y-2 shadow-md">

                            <x-input label="Nama Sertifikat" wire:model="certificates.{{ $index }}.name"
                                placeholder="Contoh: Sertifikat AWS" />

                            <x-file label="Gambar Sertifikat" wire:model="certificates.{{ $index }}.image"
                                accept="image/*" />

                            @if ($certificate['image'])
                                <img src="{{ $certificate['image']->temporaryUrl() }}"
                                    class="w-32 h-auto rounded shadow" />
                            @endif

                            <x-button color="error" icon="o-trash" wire:click="removeCertificate({{ $index }})"
                                type="button">
                                Hapus Sertifikat
                            </x-button>
                        </div>
                    @endforeach

                    <x-button color="primary" icon="o-plus" wire:click="addCertificate" type="button">
                        Tambah Sertifikat
                    </x-button>

                    <x-slot:actions>
                        <x-button label="Cancel" onclick="window.history.back()" />
                        <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-form>
            </div>
        </div>
    </x-card>



</div>
