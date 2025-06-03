<?php

namespace App\Livewire\Admin\Invoice;

use Livewire\Component;

class ListInvoice extends Component
{
    public function render()
    {
        $title = 'Invoice Management';
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
                'label' => 'Invoice',
            ],
        ];
        // return view('livewire.admin.invoice.list-invoice');
        return view('livewire.maintenance')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
