<div>
    {{-- Menu --}}
    <x-menu class="text-xs space-y-0 p-1" active-bg-color="font-black" activate-by-route>
        <x-menu-item title="Home" icon="o-home" link="{{ route('home') }}" />
        {{-- <x-menu-item title="Messages" icon="o-envelope" link="#" /> --}}
        <x-menu-sub title="Administrator" icon="o-cog-6-tooth" link="#">
          <x-menu-item title="Users" icon="o-users" link="{{ route('user.index') }}" />
          <x-menu-item title="Roles" icon="o-user-group" link="{{ route('role.index') }}" />
        </x-menu-sub>
      </x-menu>
</div>