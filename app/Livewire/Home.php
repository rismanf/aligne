<?php

namespace App\Livewire;

use Livewire\Component;
use Mary\Traits\Toast;

class Home extends Component
{
    use Toast;


    public function render()
    {
        $breadcrumbs = [
            [
                'link' => route("home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("home"), // route('home') = nama route yang ada di web.php
                'label' => 'Dashboard', // label yang ditampilkan di breadcrumb
                // 'icon' => 's-dashboard',
            ],
        ];

        return view('livewire.home')
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => 'Home',
            ]);
    }
}
