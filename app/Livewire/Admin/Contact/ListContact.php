<?php

namespace App\Livewire\Admin\Contact;

use App\Models\ContactUs;
use Livewire\Component;

class ListContact extends Component
{
    public $detail_contact = [];
    public bool $DetailModal = false;
    public function showDetailModal($id_user)
    {
        $data = ContactUs::find($id_user);
        if (!$data) {
            $this->dispatch('error', 'Contact not found.');
            return;
        }
        $this->detail_contact = $data;
        $this->DetailModal = true;
    }
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
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Contact Us',
            ],
        ];

        $contact = ContactUs::paginate(5);

        $contact->getCollection()->transform(function ($val, $index) use ($contact) {
            $val->row_number = ($contact->currentPage() - 1) * $contact->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'job_title', 'label' => 'Job Title'],
            ['key' => 'phone', 'label' => 'Phone'],
            ['key' => 'created_at', 'label' => 'Created At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.contact.list-contact', [
            't_headers' => $t_headers,
            'contact' => $contact,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
