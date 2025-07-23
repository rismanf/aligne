<div>
    <form wire:submit.prevent="search">
        <input type="text" wire:model.defer="search_code" placeholder="Masukkan kode pengguna" class="form-control mb-3">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    @if ($user)
        <x-card>
            <div class="mt-4 card p-3">
                <h5>Detail Pengguna</h5>
                <p><strong>Nama:</strong> {{ $user->user->name }}</p>
                <p><strong>Email:</strong> {{ $user->user->email }}</p>
                <p><strong>Date:</strong> {{ $user->schedule->schedule_date }}</p>
                <p><strong>Time:</strong> {{ $user->schedule->time }}</p>
                <p><strong>Class:</strong> {{ $user->schedule->classes->name }}</p>
                <p><strong>Level:</strong> {{ $user->schedule->level_class }}</p>
                <p><strong>Kode:</strong> {{ $user->code }}</p>
            </div>
        </x-card>
    @elseif ($search_code)
        <x-card>
            <div class="mt-3 alert alert-danger">Pengguna dengan kode tersebut tidak ditemukan.</div>
        </x-card>
    @endif
</div>
