<?php

namespace App\Livewire\Admin\Mail;

use App\Models\Log_system;
use App\Models\ManageMail;
use App\Models\master_data;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ListPicMail extends Component
{
    use Toast;
    use WithPagination;

    public $name, $email, $type, $type_mail_id;
    public $select_id;

    public bool $createModal = false;
    public bool $detailModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;
    public $detail;

    public array $sortBy = ['column' => 'updated_at', 'direction' => 'desc'];


    public function render()
    {
        $mail_type = master_data::select('name', 'code as id')->where('type', 'mail_type')->get();

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
            'mail_type' => $mail_type,
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
        $this->createModal = false;
        $this->reset();
    }

    
    public function showDeleteModal($id)
    {
       
        $this->select_id = $id;
        $this->deleteModal = true;
    }

    public function showDetailModal($id)
    {
        $data = ManageMail::find($id);
        $this->name = $data->name;
        $this->email = $data->email;
        $this->type = $data->type;
        $this->select_id = $id;
        $this->detailModal = true;
    }

    public function showEditModal($id)
    {
        $data = ManageMail::find($id);
        $this->name = $data->name;
        $this->email = $data->email;
        $this->type_mail_id = $data->type_mail_id;
        $this->select_id = $id;
        $this->editModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
            'type_mail_id' => 'required',
        ]);

        $id = Auth::user()->id;
        $mail_type = master_data::where('code', $this->type_mail_id)->where('type', 'mail_type')->first();

        $data = ManageMail::find($this->select_id);
        $data->name = $this->name;
        $data->email = $this->email;
        $data->type =  $mail_type->name;
        $data->type_mail_id = $this->type_mail_id;
        $data->updated_by_id = $id;
        $data->save();

        Log_system::record($id, 'Mail updated successfully!', $data->name);
        $this->editModal = false;
        $this->reset();
        $this->toast('success', 'Success', 'Data has been updated');
    }

    public function delete($select_id)
    {
        
        $id = Auth::user()->id;
        $data = ManageMail::find($select_id);
   
        if ($data) {
            Log_system::record($id, 'Mail deleted successfully!', $data->name);
            $data->delete();
            $this->toast(
                type: 'success',
                title: 'User Mail Deleted',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-info',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
            );
        }
        $this->deleteModal = false;
    }
}
