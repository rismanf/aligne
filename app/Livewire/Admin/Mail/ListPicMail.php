<?php

namespace App\Livewire\Admin\Mail;

use App\Models\ManageMail;
use App\Models\master_data;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ListPicMail extends Component
{
    use Toast;
    use WithPagination;

    public $name, $email, $type;
    public $select_id;
    public $createForm = false;
    public $mail_type = [];
    public function mount()
    {
        // $this->mail_type = master_data::where('type', 'mail_type')->pluck('name','name' )->toArray();
        $this->mail_type = master_data::select('name', 'code as id')->where('type', 'mail_type')->get();
  
    }
    public function render()
    {

        $title = 'Recipient Mail Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                'link' => route("admin.email.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Recipient Mail',
            ],
        ];

        $manages_mails = ManageMail::orderby('id')->paginate(10);

        $manages_mails->getCollection()->transform(function ($manages_mail, $index) use ($manages_mails) {
            $manages_mail->row_number = ($manages_mails->currentPage() - 1) * $manages_mails->perPage() + $index + 1;
            return $manages_mail;
        });

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'type', 'label' => 'Type'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.mail.list-pic-mail', [
            't_headers' => $t_headers,
            'manages_mails' => $manages_mails,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
            'type' => 'required',
        ]);

        ManageMail::create([
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
        ]);

        $this->toast('success', 'Success', 'Data has been saved');
        $this->createForm = false;
        $this->name = '';
        $this->email = '';
        $this->type = '';
    }
}
