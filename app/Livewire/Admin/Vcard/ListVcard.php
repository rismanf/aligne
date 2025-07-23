<?php

namespace App\Livewire\Admin\Vcard;

use App\Models\Vard;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ListVcard extends Component
{
    use Toast;
    use WithPagination;

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
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Vcard',
            ],
        ];

        $vcards = Vard::orderBy('created_at', 'desc')->paginate(5);

        $vcards->getCollection()->transform(function ($val, $index) use ($vcards) {
            $val->row_number = ($vcards->currentPage() - 1) * $vcards->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'nik', 'label' => 'Nik'],
            ['key' => 'jon_title', 'label' => 'Title'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        // return view('livewire.admin.vcard.list-vcard');
        return view('livewire.admin.vcard.list-vcard', [
            't_headers' => $t_headers,
            'vcards' => $vcards,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
