<?php

namespace App\Livewire\Admin\Class;

use App\Models\GroupClass;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class GroupClassList extends Component
{
    use Toast, WithFileUploads, WithPagination;

    public $id, $name, $image, $image_edit, $description;

    public $class_type = [
        ['id' => '1', 'name' => 'REFORMER CLASS'],
        ['id' => '2', 'name' => 'CHAIR CLASS'],
        ['id' => '3', 'name' => 'FUNCTIONAL CLASS'],
    ];

    public $class_level = [
        ['id' => '1', 'name' => 'BEGINNER'],
        ['id' => '2', 'name' => 'INTERMEDIATE'],
        ['id' => '3', 'name' => 'ADVANCED'],
        ['id' => '4', 'name' => 'YOGA'],
        ['id' => '5', 'name' => 'STRETCH YOGA'],
        ['id' => '6', 'name' => 'MAT PILATES'],
        ['id' => '7', 'name' => 'BARRE'],
        ['id' => '8', 'name' => 'AERIAL'],
        ['id' => '9', 'name' => 'DANCE / FUSION'],
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

        $class = GroupClass::orderBy('created_at', 'desc')->paginate(5);

        $class->getCollection()->transform(function ($val, $index) use ($class) {
            $val->row_number = ($class->currentPage() - 1) * $class->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'image_original', 'label' => 'Image'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.class.group-class-list', [
            't_headers' => $t_headers,
            'class' => $class,
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255|min:10',
        ]);

        if ($this->image) {
            
            $url = $this->image->store('groupclass', 'public');
        }

        GroupClass::create([
            'name' => $this->name,
            'description' => $this->description,
            'image_original' => $url
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
        $class = GroupClass::find($id);
        $this->id = $id;
        $this->name = $class->name;
        $this->description = $class->description;
        $this->image = $class->image_original;
        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            'image_edit' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255|min:10',
        ]);

        $class = GroupClass::find($this->id);
        $url = $class->image_original;
        // dd($this->image_edit);
        if ($this->image_edit) {
             if ($class->image_original && Storage::disk('public')->exists($class->image_original)) {
                Storage::disk('public')->delete($class->image_original);
            }

            $url = $this->image_edit->store('groupclass', 'public');
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
        $class = GroupClass::find($id);
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
        GroupClass::find($this->id)->delete();
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
