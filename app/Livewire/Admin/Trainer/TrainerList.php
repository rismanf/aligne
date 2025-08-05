<?php

namespace App\Livewire\Admin\Trainer;

use App\Models\Trainer;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class TrainerList extends Component
{
    use Toast, WithFileUploads;

    public $id, $avatar, $name, $title, $x_app, $facebook, $instagram, $description;
    public $trainer;
    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;
    public $photo, $avatar_edit;
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
            'title' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:1024',
        ]);
        $url = null;
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
        $trainer = Trainer::where('id', $id)->firstOrFail();
        $this->trainer = $trainer;
        $this->id = $id;
        $this->name = $trainer->name;
        $this->title = $trainer->title;
        $this->avatar_edit = $trainer->avatar;
        // $this->photo = $trainer;
        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:1024',
        ]);

        $trainer = Trainer::find($this->id);

        if ($this->avatar) {
            if ($trainer->avatar && Storage::disk('public')->exists($trainer->avatar)) {
                Storage::disk('public')->delete($trainer->avatar);
            }

            $url = $this->avatar->store('trainer', 'public');
            $trainer->avatar = $url;
        }

        $trainer->name = $this->name;
        $trainer->title = $this->title;
        $trainer->avatar = $url;
        $trainer->save();

        $this->reset();
        $this->editForm = false;
        $this->toast(
            type: 'success',
            title: 'Trainer Updated',               // optional (text)
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
