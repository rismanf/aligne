<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <a href="{{ route('/') }}" target="_blank" >
                <!-- Hidden when collapsed -->
                <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
                    <div class="flex text-justify gap-2">
                        <div x-data="{ isDark: document.documentElement.getAttribute('data-theme') === 'dark' }" x-init="
                        new MutationObserver(() => {
                            isDark = document.documentElement.getAttribute('data-theme') === 'dark'
                        }).observe(document.documentElement, { attributes: true })
                    ">
                        <!-- Logo untuk Light Theme -->
                        <img x-show="!isDark" src="{{ asset('assets/img/logo-main.webp') }}" alt="Logo Light" class="h-10">

                        <!-- Logo untuk Dark Theme -->
                        <img x-show="isDark" src="{{ asset('assets/img/logo-main.webp') }}" alt="Logo Dark" class="h-10">
                    </div>                        
                    </div>
                </div>

                <!-- Display when collapsed -->
                <div class="display-when-collapsed hidden flex items-center justify-center mb-3 h-fit mt-2 gap-2 pl-2 pr-2">
                     <img src="{{ asset('assets/img/logo-fav.webp') }}" alt="mini-Logo" class="h-15 w-auto object-contain" />
                </div>
            </a>
        HTML;
    }
}
