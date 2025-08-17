<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class ListUser extends Component
{
    use WithPagination;
    use WithFileUploads;
    use Toast;
    // public int $perPage = 3;

    public $selectedUserId;
    public bool $createForm = false;
    public bool $editForm = false;

    public bool $deleteModal = false;
    public $name, $email, $role, $password, $retype_password,$password_confirmation, $avatar;
    public $editUser, $editUserId, $editName, $editEmail, $editRole, $editAvatar;

    // Search and filter properties
    public $search = '';
    public $roleFilter = '';


    public function showDeleteModal($userId)
    {
        // dd($userId);
        $this->selectedUserId = $userId;
        $this->deleteModal = true;
    }

    public function showEditModal($userId)
    {
        $user = User::find($userId);

        $this->editUser = $user;
        // dd($this->editUser);
        $this->editUserId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editRole = $user->roles->first()?->name ?? '';
        $this->editForm = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->avatar = null;
    }

    public function resetEditForm()
    {
        $this->editUserId = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editRole = '';
        $this->editAvatar = null;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
            'role' => 'required',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ]);

        if ($this->password != $this->retype_password) {
            $this->toast(
                type: 'error',
                title: 'Password Not Match',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-info',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
            return;
        }

        $avatarPath = null;
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $avatarPath,
            'password' => bcrypt($this->password), // default password atau form password sendiri
        ]);

        $user->assignRole([$this->role]);

        // session()->flash('success', 'User berhasil ditambahkan.');
        $this->resetForm();
        $this->createForm = false;
        $this->toast(
            type: 'success',
            title: 'Data Saved',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: route('admin.user.index')                    // optional (uri)
        );
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|unique:users,email,' . $this->editUserId,
            'editRole' => 'required',
            'editAvatar' => 'nullable|image|max:2048', // 2MB max
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = User::find($this->editUserId);
        if ($user) {
            $avatarPath = $user->avatar;

            if ($this->editAvatar) {
                // Delete old avatar if exists
                // if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                //     Storage::disk('public')->delete($user->avatar);
                // }
                $avatarPath = $this->editAvatar->store('avatars', 'public');
            }

            if ($this->password) {
                $user->password = bcrypt($this->password);
            }

            $user->name = $this->editName;
            $user->email = $this->editEmail;
            $user->avatar = $avatarPath;
            $user->save();

            // Update role
            $user->syncRoles([$this->editRole]);

            $this->resetEditForm();
            $this->editForm = false;
            $this->toast(
                type: 'success',
                title: 'User Updated',
                description: null,
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-info',
                timeout: 3000,
                redirectTo: route('admin.user.index')
            );
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            $this->toast(
                type: 'success',
                title: 'User Deleted',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-info',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
                redirectTo: route('admin.user.index')                    // optional (uri)
            );
        }
    }

    public function canEditUser($user)
    {
        // Only admin can edit users, and admin cannot edit other admin users
        $currentUser = auth()->user();
        if (!$currentUser->hasRole('Admin')) {
            return false;
        }

        // Admin cannot edit other admin users (optional security measure)
        if ($user->hasRole('Admin') && $user->id !== $currentUser->id) {
            return false;
        }

        return true;
    }

    public function render()
    {
        $title = 'User Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("user.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Admin',
            ],
            [
                'link' => route("admin.user.index"), // route('home') = nama route yang ada di web.php
                'label' => 'User',
            ],
        ];

        $query = User::with('roles');

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply role filter
        if (!empty($this->roleFilter)) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->paginate(5);

        $users->getCollection()->transform(function ($user, $index) use ($users) {
            $user->row_number = ($users->currentPage() - 1) * $users->perPage() + $index + 1;
            return $user;
        });



        $roles = Role::all();

        // dd();

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'avatar', 'label' => 'Avatar', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'roles.0.name', 'label' => 'Role'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];
        // dd($t_headers);
        return view('livewire.admin.user.list-user', [
            't_headers' => $t_headers,
            'users' => $users,
            'roles' => $roles,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
