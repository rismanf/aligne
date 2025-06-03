<div>
    {{-- Menu --}}
    <x-menu class="text-xs space-y-0 p-1" active-bg-color="font-black" activate-by-route>
        <h2 class="menu-title">Menu</h2>
        <x-menu-item title="Home" icon="o-home" link="{{ route('admin.home') }}" />
        <x-menu-item title="News" icon="o-newspaper" link="{{ route('admin.news.index') }}" />
        <x-menu-item title="Vcard" icon="o-device-tablet" link="{{ route('admin.vcard.index') }}" />
        <x-menu-item title="Contact Us" icon="o-identification" link="{{ route('admin.contact.index') }}" />
        <li></li>
        <h2 class="menu-title">Summit</h2>
        <x-menu-item title="Participants" icon="o-user-group" link="{{ route('admin.participant.index') }}" />
        <x-menu-item title="Invoice" icon="o-banknotes" link="{{ route('admin.invoice.index') }}" />
        <li></li>
        {{-- <x-menu-item title="Messages" icon="o-envelope" link="#" /> --}}
        <x-menu-sub title="Administrator" icon="o-cog-6-tooth" link="#">
            <x-menu-item title="Users" icon="o-users" link="{{ route('admin.user.index') }}" />
            <x-menu-item title="Roles" icon="o-user-group" link="{{ route('admin.role.index') }}" />
        </x-menu-sub>
    </x-menu>
</div>
