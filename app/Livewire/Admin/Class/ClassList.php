<?php

namespace App\Livewire\Admin\Class;

use App\Models\Classes;
use Livewire\Component;
use Mary\Traits\Toast;

class ClassList extends Component
{
    use Toast;

    public $id, $name, $description;
    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;

    public function render()
    {
        $title = 'Class Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Class',
            ],
        ];

        $news = Classes::orderBy('created_at', 'desc')->paginate(5);

        $news->getCollection()->transform(function ($val, $index) use ($news) {
            $val->row_number = ($news->currentPage() - 1) * $news->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.class.class-list', [
            't_headers' => $t_headers,
            'class' => $news,
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
            'description' => 'required|string|max:255|min:10',
        ]);

        Classes::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset();
        $this->createForm = false;

        $this->toast(
            type: 'success',
            title: 'Class Created',
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
        $class = Classes::find($id);
        $this->id = $id;
        $this->name = $class->name;
        $this->description = $class->description;
        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255|min:10',
        ]);

        $class = Classes::find($this->id);
        $class->name = $this->name;
        $class->description = $this->description;
        $class->save();

        $this->reset();
        $this->editForm = false;

        $this->toast(
            type: 'success',
            title: 'Class Updated',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
    // Detail
    public function showDetailModal($id)
    {
        $class = Classes::find($id);
        $this->name = $class->name;
        $this->description = $class->description;
        $this->detailForm = true;
    }

    //Delete
    public function showDeleteModal($id)
    {
        $this->id = $id;
        $this->deleteForm = true;
    }

    public function delete()
    {
        Classes::find($this->id)->delete();
        $this->deleteForm = false;
        $this->toast(
            type: 'success',
            title: 'Class Deleted',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
