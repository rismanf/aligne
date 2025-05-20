<div class="h-screen flex items-center justify-center">
  <x-card class="min-w-md">
    <x-slot name="title">Login</x-slot>

    <form wire:submit="login" class="space-y-4">
      <x-input
        label="Email"
        type="email"
        wire:model.defer="email"
        placeholder="Email"
        icon="o-user"
        hint=""
        class="w-full pr-0"
        required
      />

      <x-input
        label="Password"
        type="password"
        wire:model.defer="password"
        placeholder="Password"
        icon="o-lock-closed"
        class="w-full pr-0"
        required
      />

      {{-- <x-checkbox label="Remember me" wire:model="remember" /> --}}

      <x-button label="Login" type="submit" class="btn-primary w-full" spinner="login" />
    </form>
  </x-card>
</div>
