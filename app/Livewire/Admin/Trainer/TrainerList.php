<?php

namespace App\Livewire\Admin\Trainer;

use App\Models\Trainer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class TrainerList extends Component
{
    use Toast,WithFileUploads;

    public $id, $avatar, $name, $title, $x_app, $facebook, $instagram, $description;
    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;
    public $photo;
    public function render()
    {
        $title = 'Trainer Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Trainer',
            ],
        ];

        $news = Trainer::orderBy('created_at', 'desc')->paginate(5);

        $news->getCollection()->transform(function ($val, $index) use ($news) {
            $val->row_number = ($news->currentPage() - 1) * $news->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'avatar', 'label' => 'Avatar'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'title', 'label' => 'title'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.trainer.trainer-list', [
            't_headers' => $t_headers,
            'trainers' => $news,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }

    //  Add
    public function showAddModal()
    {
        $this->createForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'avatar' => 'required|image|max:1024',
        ]);
        if ($this->avatar) {
            $url = $this->avatar->store('trainer', 'public');
        }

        $trainer = new Trainer();
        $trainer->name = $this->name;
        $trainer->title = $this->title;
        $trainer->avatar = $url;
        $trainer->save();
        $this->createForm = false;
        $this->toast(
            type: 'success',
            title: 'Trainer Added',               // optional (text)
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }

    //Edit
    public function showEditModal($id)
    {
        $trainer = Trainer::find($id);
        $this->id = $id;
        $this->name = $trainer->name;
        $this->title = $trainer->title;
        $this->avatar = $trainer->avatar;
        // $this->photo = $trainer;
        $this->editForm = true;
    }
}
