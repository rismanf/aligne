<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class ListUser extends Component
{
    use WithPagination;
    use Toast;
    // public int $perPage = 3;

    public $selectedUserId;
    public bool $createForm = false;

    public bool $deleteModal = false;
    public $name, $email, $role, $password, $retype_password;


    public function showDeleteModal($userId)
    {
        // dd($userId);
        $this->selectedUserId = $userId;
        $this->deleteModal = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->role = '';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
            'role' => 'required',
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

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password), // default password atau form password sendiri
        ]);

        $user->assignRole([$this->role]);

        // session()->flash('success', 'User berhasil ditambahkan.');
        $this->resetForm();
        $this->toast(
            type: 'success',
            title: 'Data Saved',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: route('user.index')                    // optional (uri)
        );
        $this->showForm = false;
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
                redirectTo: route('user.index')                    // optional (uri)
            );
        }
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

        $users = User::with('roles')
            ->paginate(5);

        $users->getCollection()->transform(function ($user, $index) use ($users) {
            $user->row_number = ($users->currentPage() - 1) * $users->perPage() + $index + 1;
            return $user;
        });

        

        $roles = Role::all();

        // dd();

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
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
