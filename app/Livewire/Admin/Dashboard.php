<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;
    public function render()
    {
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("home"), // route('home') = nama route yang ada di web.php
                'label' => 'Dashboard', // label yang ditampilkan di breadcrumb
                // 'icon' => 's-dashboard',
            ],
        ];

        return view('livewire.admin.dashboard')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Home',
        ]);
    }
}
