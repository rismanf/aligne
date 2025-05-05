<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    <x-main  full-width>


      
      {{-- SIDEBAR --}}
      <x-slot:sidebar drawer="main-drawer" class="bg-base-100 p-0 w-62 space-y-4" >


        {{-- Logo --}}
        {{-- <a href="{{ route('home') }}" class="flex justify-center">
          <img src="{{ asset('image/NEUTRA-logo-2.png') }}" alt="Logo" class="h-10">
        </a> --}}
        <x-app-brand class="mt-2 ml-2" />

        {{-- User --}}
        @if($user = auth()->user())
          <x-menu-separator/>
          {{-- <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="pt-0  ">
            <x-slot:actions>
              <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="Logoff" no-wire-navigate link="/logout" />
            </x-slot:actions>
          </x-list-item> --}}

          {{-- <div class="mary-hideable flex  ml-2 gap-2">
            
            <x-avatar placeholder="G" title="{{ $user->name }}" subtitle="{{ $user->email }}" class="!w-10 pt-0 " />
              <x-dropdown>
                  <x-slot:trigger>
                        <x-button icon="o-bell" class="btn-circle btn-xs" />
                  </x-slot:trigger>
              
                  <x-menu-item title="Archive" />
                  <x-menu-item title="Move" />
              </x-dropdown>
            
          </div> --}}

          <div class="mary-hideable cursor-pointer hover:bg-base-300 flex items-center pl-2 gap-2">
            <x-dropdown>
                <x-slot:trigger>
                    <x-avatar 
                        placeholder="{{ getInitials($user->name) }}" 
                        title="{{ $user->name }}" 
                        subtitle="{{ $user->email }}" 
                        class="!w-10 pt-0 " 
                    />

                    
                </x-slot:trigger>
        
                
                <x-menu-item  title="Theme" icon="o-swatch" @click="$dispatch('mary-toggle-theme')" />
                <x-menu-item title="Logout" link="{{ route('logout') }}" icon="o-arrow-left-start-on-rectangle" />
                {{-- <livewire:logout-button /> --}}
                
                
            </x-dropdown>

            {{-- Theme toggle --}}
            <x-theme-toggle class="hidden" />
        </div>
          
        <div class="flex cursor-pointer  justify-center " @click="toggle">
          <div class="display-when-collapsed hidden">
            <x-avatar placeholder="{{ getInitials($user->name) }}"  class=" !w-10 " />
          </div>
        </div>

          
          <x-menu-separator />

        @endif

        <div class="flex   text-right p-1  w-full">
          <button class="mary-hideable btn ml-1.5 btn-xs btn-ghost " @click="toggle">
            <x-icon name="o-chevron-double-left" />
          </button>

          {{-- <div class="mary-hideable ">
            Collapse
          </div> --}}
         {{-- Collapse --}}

          <button class="display-when-collapsed hidden btn btn-xs btn-ghost w-full" @click="toggle">
            <x-icon name="o-chevron-double-right" />
          </button>
        </div>

        <x-menu-separator />

        {{-- Menu --}}
        {{-- <x-menu class="text-xs space-y-0 p-1" activate-by-route>
          <x-menu-item title="Home" icon="o-home" link="{{ route('home') }}" />
          <x-menu-item title="Messages" icon="o-envelope" link="#" />
          <x-menu-sub title="Settings" icon="o-cog-6-tooth" link="#">
            <x-menu-item title="Wifi" icon="o-wifi" link="#" />
            <x-menu-item title="Archives" icon="o-archive-box" link="#" />
          </x-menu-sub>
        </x-menu> --}}

        @livewire('layouts.menus', ['title' => $title])

      </x-slot:sidebar>


      {{-- The `$slot` goes here --}}
      <x-slot:content class="!p-0">
        <x-header title="{{ $title }}" class="!mb-4.5 mt-4 ml-3 " separator />

        <x-breadcrumbs :items="$breadcrumbs" class="ml-5 mb-5" />
        
        <div class="pl-3 pr-3 pt-2">
          {{ $slot }}
        </div> 
      </x-slot:content>
    </x-main>

    {{-- TOAST area --}}
    <x-toast />
  </body>
</html>
