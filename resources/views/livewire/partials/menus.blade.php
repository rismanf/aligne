<div>
    {{-- Menu --}}
    <x-menu class="text-xs space-y-0 p-1" active-bg-color="font-black" activate-by-route>
        <h2 class="menu-title">Menu</h2>
         @hasrole('Admin')
        <x-menu-item title="Dashboard" icon="o-chart-bar-square" link="{{ route('admin.home') }}" />
        <x-menu-item title="Schedule Monitoring" icon="o-chart-bar-square" link="{{ route('admin.schedulemonitoring') }}" />
    
            <x-menu-item title="Contact Us" icon="o-identification" link="{{ route('admin.contact.index') }}" />

            <x-menu-item title="Email" icon="o-envelope" link="{{ route('admin.email.index') }}" />
            <x-menu-item title="Transaction" icon="o-ticket" link="{{ route('admin.transaction.index') }}" />

            <x-menu-sub title="Class Management" icon="o-rectangle-group" link="#">
                <x-menu-item title="Class" icon="o-stop" link="{{ route('admin.class.index') }}" />
                <x-menu-item title="Class Group" icon="o-stop" link="{{ route('admin.groupclass.index') }}" />
            </x-menu-sub>

            <x-menu-item title="Trainer" icon="o-identification" link="{{ route('admin.trainer.index') }}" />
            <x-menu-sub title="Membership" icon="o-archive-box" link="#">
                <x-menu-item title="Paket" icon="o-stop" link="{{ route('admin.product.index') }}" />
                <x-menu-item title="Paket Group" icon="o-stop" link="{{ route('admin.categoriesproduct.index') }}" />
            </x-menu-sub>

            <x-menu-item title="Schedule" icon="o-calendar-days" link="{{ route('admin.schedule.index') }}" />
            <x-menu-item title="QR Scanner" icon="o-qr-code" link="{{ route('admin.qr-scanner') }}" />
            <x-menu-item title="Member Management" icon="o-users" link="{{ route('admin.member.index') }}" />


            <li></li>

            <x-menu-sub title="Administrator" icon="o-cog-6-tooth" link="#">
                <x-menu-item title="Users" icon="o-users" link="{{ route('admin.user.index') }}" />
                <x-menu-item title="Roles" icon="o-user-group" link="{{ route('admin.role.index') }}" />
            </x-menu-sub>
        @endhasrole
        @hasrole('Operator')
            <x-menu-item title="Transaction" icon="o-ticket" link="{{ route('admin.transaction.index') }}" />
            <x-menu-item title="Schedule" icon="o-calendar-days" link="{{ route('admin.schedule.index') }}" />
            <x-menu-item title="QR Scanner" icon="o-qr-code" link="{{ route('admin.qr-scanner') }}" />
            <x-menu-item title="Member Management" icon="o-users" link="{{ route('admin.member.index') }}" />
        @endhasrole
    </x-menu>
</div>
