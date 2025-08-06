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

    public $id, $name, $class_type;
    public $class_type_list = [];
    public $class_level_id;
    public $selectGroupClass;
    public $selectLevelClass; // Add level filter property
    public $description, $image; // Add missing properties

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

    public function mount()
    {
        // Initialize class type list for modals
        $this->class_type_list = GroupClass::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        })->toArray();
    }

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

        // Prepare class type options with "All" option first
        $class_type_options =
            GroupClass::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })
            ->toArray();

        // Build query with filters
        $query = Classes::orderBy('created_at', 'desc');

        // Apply group class filter if selected
        if ($this->selectGroupClass) {
            $query->where('group_class_id', $this->selectGroupClass);
        }

        // Apply level class filter if selected
        if ($this->selectLevelClass) {
            $query->where('level_class_id', $this->selectLevelClass);
        }

        // Execute query with pagination
        $class = $query->paginate(5);

        $class->getCollection()->transform(function ($val, $index) use ($class) {
            $val->row_number = ($class->currentPage() - 1) * $class->perPage() + $index + 1;
            return $val;
        });

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'group_class', 'label' => 'Group Class'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'level_class', 'label' => 'Level Class'],
            // ['key' => 'mood_class', 'label' => 'Mood Class'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        // Prepare level options with "All" option first
        $level_options =
            collect($this->class_level)->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                ];
            })
            ->toArray();

        return view('livewire.admin.class.class-list', [
            't_headers' => $t_headers,
            'class' => $class,
            'class_type_options' => $class_type_options,
            'level_options' => $level_options,
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

        // if ($this->image) {
        //     $url = $this->image->store('class', 'public');
        // }

        Classes::create([
            'name' => $this->name,
            'group_class_id' => $this->class_type,
            'group_class' => GroupClass::find($this->class_type)->name,
            'level_class_id' => $this->class_level_id,
            'level_class' => $this->class_level[array_search($this->class_level_id, array_column($this->class_level, 'id'))]['name'],
            'created_by_id' => auth()->user()->id,
        ]);

        $this->reset('name', 'class_type', 'class_level_id');
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
        $this->class_type = $class->group_class_id;
        $this->class_level_id = $class->level_class_id;
        $this->editForm = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'class_type' => 'required|integer',
            'class_level_id' => 'required|integer',
        ]);

        $class = Classes::find($this->id);

        $class->name = $this->name;
        $class->group_class_id = $this->class_type;
        $class->group_class = GroupClass::find($this->class_type)->name;
        $class->level_class_id = $this->class_level_id;
        $class->level_class = $this->class_level[array_search($this->class_level_id, array_column($this->class_level, 'id'))]['name'];
        $class->updated_by_id = auth()->user()->id;
        $class->save();

        $this->reset('name', 'class_type', 'class_level_id');
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
        $this->class_type = $class->group_class;
        $this->class_level_id = $class->level_class;
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
