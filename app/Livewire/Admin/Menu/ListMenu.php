<?php

namespace App\Livewire\Admin\Menu;

use App\Models\Menu;
use Livewire\Component;

class ListMenu extends Component
{
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

        $menus = Menu::paginate(3);

        $menus->getCollection()->transform(function ($menu, $index) use ($menus) {
            $menu->row_number = ($menus->currentPage() - 1) * $menus->perPage() + $index + 1;
            return $menu;
        });

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'roles.0.name', 'label' => 'Role'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.menu.list-menu', [
            't_headers' => $t_headers,
            'menus' => $menus,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
