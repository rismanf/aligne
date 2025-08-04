<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/aligne_icon.png') }}" media="(prefers-color-scheme: light)"
        type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/img/aligne_icon.png') }}" media="(prefers-color-scheme: dark)"
        type="image/x-icon" />
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{--  Currency  --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"></script>
    {{-- Cropper.js --}}
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">
    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>

        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" class="bg-base-100 p-0 w-62 space-y-4">

            <x-app-brand class="mt-2 ml-2" />

            {{-- User --}}
            @if ($user = auth()->user())
                <x-menu-separator />

                <div class="mary-hideable cursor-pointer hover:bg-base-300 flex items-center pl-2 gap-2">
                    <x-dropdown>
                        <x-slot:trigger>
                            <x-avatar placeholder="{{ getInitials($user->name) }}" title="{{ $user->name }}"
                                subtitle="{{ $user->email }}" class="!w-10 pt-0 " />
                        </x-slot:trigger>

                        <x-menu-item title="Theme" icon="o-swatch" @click="$dispatch('mary-toggle-theme')" />
                    </x-dropdown>

                    {{-- Theme toggle --}}
                    <x-theme-toggle class="hidden" />
                </div>

                <div class="flex cursor-pointer  justify-center " @click="toggle">
                    <div class="display-when-collapsed hidden">
                        <x-avatar placeholder="{{ getInitials($user->name) }}" class=" !w-10 " />
                    </div>
                </div>

                <x-menu-separator />
            @endif

            <div class="flex   text-right p-1  w-full">
                <button class="mary-hideable btn ml-1.5 btn-xs btn-ghost " @click="toggle">
                    <x-icon name="o-chevron-double-left" />
                </button>

                <button class="display-when-collapsed hidden btn btn-xs btn-ghost w-full" @click="toggle">
                    <x-icon name="o-chevron-double-right" />
                </button>
            </div>

            <x-menu-separator />

            {{-- Menu --}}
            @include('livewire.partials.menus', ['title' => $title])

            <x-menu class="text-xs space-y-0 p-1">
                <x-menu-item title="Logout" link="{{ route('logout') }}" icon="o-arrow-left-start-on-rectangle" />
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content class="!p-0">
            <x-header title="{{ $title }}" class="!mb-4.5 mt-4 ml-3 " separator />

            <x-breadcrumbs :items="$breadcrumbs" class="ml-5 mb-5" />

            <div class="pl-3 pr-3 pt-2">
                {{-- content --}}
                {{ $slot }}
            </div>
        </x-slot:content>
    </x-main>

    {{-- TOAST area --}}
    <x-toast />

</body>

</html>
