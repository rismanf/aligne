<?php

namespace App\Livewire\Admin\Class;

use App\Models\Classes;
use App\Models\GroupClass;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Mary\View\Components\Group;

class ClassList extends Component
{
    use Toast, WithFileUploads, WithPagination;

    public $id, $name, $image, $image_edit, $description;
    public $class_type = [];
    public $class_level_id;

    public $class_level = [
        ['id' => '1', 'name' => 'BEGINNER'],
        ['id' => '2', 'name' => 'INTERMEDIATE'],
        ['id' => '3', 'name' => 'ADVANCED'],
        ['id' => '4', 'name' => 'ALL LEVEL'],
        ['id' => '5', 'name' => 'YOGA'],
        ['id' => '6', 'name' => 'STRETCH YOGA'],
        ['id' => '7', 'name' => 'MAT PILATES'],
        ['id' => '8', 'name' => 'BARRE'],
        ['id' => '9', 'name' => 'AERIAL'],
        ['id' => '10', 'name' => 'DANCE / FUSION'],
    ];

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
            ['key' => 'group_class', 'label' => 'Group Class'],
            // ['key' => 'level_class', 'label' => 'Level Class'],
            ['key' => 'name', 'label' => 'Name'],
            // ['key' => 'mood_class', 'label' => 'Mood Class'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];
        $this->class_type = GroupClass::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        })->toArray();
        return view('livewire.admin.class.class-list', [
            't_headers' => $t_headers,
            'class' => $news,
            'class_type' => $this->class_type,
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
            'class_type' => 'required|integer',
            'class_level_id' => 'required|integer',
        ]);

        if ($this->image) {
            $url = $this->image->store('class', 'public');
        }

        Classes::create([
            'name' => $this->name,
            'group_class_id' => $this->class_type,
            'group_class' => GroupClass::find($this->class_type)->name,
            'level_class_id' => $this->class_level_id,
            'level_class' => $this->class_level[$this->class_level_id - 1]['name'],
            'created_by_id' => auth()->user()->id,
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
        $this->image = $class->image_original;
        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255|min:10',
        ]);

        $class = Classes::find($this->id);
        $url = $class->image_original;
        if ($this->image_edit) {
            $url = $this->image->store('class', 'public');
        }
        $class->name = $this->name;
        $class->description = $this->description;
        $class->image_original = $url;
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
        $this->image = $class->image_original;
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
