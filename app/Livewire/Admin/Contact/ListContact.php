<?php

namespace App\Livewire\Admin\Contact;

use Livewire\Component;

class ListContact extends Component
{
    public function render()
    {
        $title = 'Contact Management';
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
                'label' => 'Contact Us',
            ],
        ];
        // return view('livewire.admin.contact.list-contact');
        return view('livewire.maintenance')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
