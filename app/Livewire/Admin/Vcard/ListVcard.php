<?php

namespace App\Livewire\Admin\Vcard;

use Livewire\Component;

class ListVcard extends Component
{
    public function render()
    {
        $title = 'Vcard Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("user.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Admin',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Vcard',
            ],
        ];

        // return view('livewire.admin.vcard.list-vcard');
        return view('livewire.maintenance')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
