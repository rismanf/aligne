<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class RoleShow extends Component
{
    use WithPagination;
    use Toast;
    // public int $perPage = 3;


    public $role;

    // Akan dipanggil otomatis saat komponen diakses via route (karena pakai parameter route)
    public function mount($id)
    {
        $this->role = Role::findOrFail($id);
    }

    public function render()
    {
        $title = 'Role Permission';
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
                'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Role', 
            ],
        ];
        
        $roles = Role::orderBy('id','DESC')->paginate(5);

        $roles->getCollection()->transform(function ($role, $index) use ($roles) {
            $role->row_number = ($roles->currentPage() - 1) * $roles->perPage() + $index + 1;
            return $role;
        });

        // dd($users);
 
        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            // ['key' => 'email', 'label' => 'Email'],
            // ['key' => 'roles.0.name', 'label' => 'Role'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-2'],
        ];
        return view('livewire.roles.role-index' , [
            't_headers' => $t_headers,
            'roles'=> $roles,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
