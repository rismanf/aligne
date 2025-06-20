<?php

namespace App\Livewire\Admin\Vcard;

use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class AddVcard extends Component
{
    use Toast, WithFileUploads;
    public $foto;
    public $certificates = [];

    public function mount()
    {
        $this->certificates = [
            ['name' => '', 'image' => null]
        ];
    }

    public function addCertificate()
    {
        $this->certificates[] = ['name' => '', 'image' => null];
    }

     public function removeCertificate($index)
    {
        unset($this->certificates[$index]);
        $this->certificates = array_values($this->certificates); // reindex
    }
    
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
                'link' => route("admin.vcard.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Vcard',
            ],
            [
                'link' => '', // route('home') = nama route yang ada di web.php
                'label' => 'Add Vcard',
            ],
        ];

        return view('livewire.admin.vcard.add-vcard')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
