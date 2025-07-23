<div>
    {{-- Menu --}}
    <x-menu class="text-xs space-y-0 p-1" active-bg-color="font-black" activate-by-route>
        <h2 class="menu-title">Menu</h2>
        <x-menu-item title="Dashboard" icon="o-chart-bar-square" link="{{ route('admin.home') }}" />
        @canany(['news-list', 'vcard-list', 'contactus-list', 'menu-list', 'email-list', 'participant-list'])
            @can('news-list')
                {{-- <x-menu-item title="News" icon="o-newspaper" link="{{ route('admin.news.index') }}" /> --}}
            @endcan
            {{-- @can('vcard-list')
                <x-menu-item title="Vcard" icon="o-device-tablet" link="{{ route('admin.vcard.index') }}" />
            @endcan --}}
            @can('contactus-list')
                <x-menu-item title="Contact Us" icon="o-identification" link="{{ route('admin.contact.index') }}" />
            @endcan
            @can('menu-list')
                {{-- <x-menu-item title="Menu" icon="o-wallet" link="{{ route('admin.menu.index') }}" /> --}}
            @endcan
            @can('email-list')
                <x-menu-item title="Email" icon="o-envelope" link="{{ route('admin.email.index') }}" />
                <x-menu-item title="Transaction" icon="o-ticket" link="{{ route('admin.transaction.index') }}" />

                <x-menu-sub title="Class Management" icon="o-rectangle-group" link="#">
                    <x-menu-item title="Class" icon="o-stop" link="{{ route('admin.class.index') }}" />
                    <x-menu-item title="Class Group" icon="o-stop" link="{{ route('admin.groupclass.index') }}" />
                </x-menu-sub>

                <x-menu-item title="Trainer" icon="o-identification" link="{{ route('admin.trainer.index') }}" />
                <x-menu-item title="Membership" icon="o-users" link="{{ route('admin.product.index') }}" />
                <x-menu-item title="Schedule" icon="o-calendar-days" link="{{ route('admin.schedule.index') }}" />
                <x-menu-item title="Visit" icon="o-qr-code" link="{{ route('admin.visit.index') }}" />
            @endcan
            {{-- @can('participant-list')
                <x-menu-item title="Participants" icon="o-user-group" link="{{ route('admin.participant.index') }}" />
            @endcan --}}
        @endcanany
        {{-- <li></li> --}}
        {{-- @canany(['participant-list', 'invoice-list', 'question-list'])
            @can('participant-list')
                <x-menu-item title="Participants" icon="o-user-group" link="{{ route('admin.participant.index') }}" />
            @endcan

            @can('question-list')
                <x-menu-item title="Questions" icon="o-question-mark-circle" link="{{ route('admin.question.index') }}" />
            @endcan
            @can('invoice-list')
                <x-menu-item title="Invoice" icon="o-banknotes" link="{{ route('admin.invoice.index') }}" />
            @endcan
        @endcanany --}}
        <li></li>
        {{-- <x-menu-item title="Messages" icon="o-envelope" link="#" /> --}}
        @canany(['user-list', 'role-list'])
            <x-menu-sub title="Administrator" icon="o-cog-6-tooth" link="#">
                <x-menu-item title="Users" icon="o-users" link="{{ route('admin.user.index') }}" />
                <x-menu-item title="Roles" icon="o-user-group" link="{{ route('admin.role.index') }}" />
            </x-menu-sub>
        @endcanany
    </x-menu>
</div>
