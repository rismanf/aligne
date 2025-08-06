<?php

namespace App\Livewire\Admin\Trainer;

use App\Models\Trainer;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class TrainerList extends Component
{
    use Toast, WithFileUploads, WithPagination;

    // Search and Filter properties
    public $search = '';
    public $status_filter = 'all'; // all, active, inactive
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Form properties
    public $id, $avatar, $name, $title, $x_app, $facebook, $instagram, $description;
    public $trainer;
    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;
    public $photo, $avatar_edit;

    // Detail modal properties
    public $showDetailModal = false;
    public $selectedTrainer = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function showTrainerDetail($trainerId)
    {
        $this->selectedTrainer = Trainer::find($trainerId);
        if ($this->selectedTrainer) {
            $this->showDetailModal = true;
        }
    }
    public function render()
    {
        $query = Trainer::query();

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter (you can customize this based on your trainer status logic)
        if ($this->status_filter === 'active') {
            // Assuming trainers with recent updates are active
            $query->where('updated_at', '>=', now()->subMonths(6));
        } elseif ($this->status_filter === 'inactive') {
            // Assuming trainers without recent updates are inactive
            $query->where('updated_at', '<', now()->subMonths(6));
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $trainers = $query->paginate(15);

        // Add row numbers
        $trainers->getCollection()->transform(function ($trainer, $index) use ($trainers) {
            $trainer->row_number = ($trainers->currentPage() - 1) * $trainers->perPage() + $index + 1;
            $trainer->is_active = $trainer->updated_at >= now()->subMonths(6);
            return $trainer;
        });

        $title = 'Trainer Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"),
                'label' => 'Home',
                'icon' => 's-home',
            ],
            [
                'label' => 'Trainer Management',
            ],
        ];

        return view('livewire.admin.trainer.trainer-list', [
            'trainers' => $trainers,
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
        $url = $trainer->avatar; // Keep existing avatar if no new one uploaded

        if ($this->avatar) {
            if ($trainer->avatar && Storage::disk('public')->exists($trainer->avatar)) {
                Storage::disk('public')->delete($trainer->avatar);
            }

            $url = $this->avatar->store('trainer', 'public');
        }

        $trainer->name = $this->name;
        $trainer->title = $this->title;
        $trainer->avatar = $url;
        $trainer->save();

        $this->reset(['name', 'title', 'avatar', 'avatar_edit', 'id', 'trainer']);
        $this->editForm = false;
        $this->toast(
            type: 'success',
            title: 'Trainer Updated',
            description: null,
            position: 'toast-top toast-end',
            icon: 'o-information-circle',
            css: 'alert-info',
            timeout: 3000,
            redirectTo: null
        );
    }

    // Delete functionality
    public function showDeleteModal($id)
    {
        $this->id = $id;
        $this->trainer = Trainer::find($id);
        $this->deleteForm = true;
    }

    public function delete()
    {
        $trainer = Trainer::find($this->id);
        
        if ($trainer) {
            // Delete avatar file if exists
            if ($trainer->avatar && Storage::disk('public')->exists($trainer->avatar)) {
                Storage::disk('public')->delete($trainer->avatar);
            }
            
            $trainer->delete();
            
            $this->toast(
                type: 'success',
                title: 'Trainer Deleted',
                description: 'Trainer has been successfully deleted.',
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-success',
                timeout: 3000,
                redirectTo: null
            );
        }
        
        $this->reset(['id', 'trainer']);
        $this->deleteForm = false;
    }

    // Detail functionality
    public function showDetailModal($id)
    {
        $trainer = Trainer::find($id);
        if ($trainer) {
            $this->selectedTrainer = $trainer;
            $this->name = $trainer->name;
            $this->title = $trainer->title;
            $this->description = $trainer->description ?? 'No description available';
            $this->detailForm = true;
        }
    }

    // Reset form when closing modals
    public function resetForm()
    {
        $this->reset(['name', 'title', 'avatar', 'avatar_edit', 'id', 'trainer', 'selectedTrainer', 'description']);
    }
}
