<?php

namespace App\Livewire\Admin\Menu;

use App\Models\Menu;
use Livewire\Component;
use Mary\Traits\Toast;

class ListMenu extends Component
{

    use Toast;

    public $showModal = false;
    public $name, $title, $description, $keywords;
    public $selectmenu_id;

    public function showDetailModal($id_menu)
    {
        $data_menu = Menu::find($id_menu);

        $this->name = $data_menu->name;
        $this->title = $data_menu->title;
        $this->description = $data_menu->description;
        $this->keywords = $data_menu->keywords;
        $this->selectmenu_id = $id_menu;
        $this->showModal = true;
    }

    public function updatemenu()
    {
        $data_menu = Menu::find($this->selectmenu_id);
        $data_menu->name = $this->name;
        $data_menu->title = $this->title;
        $data_menu->description = $this->description;
        $data_menu->keywords = $this->keywords;
        $data_menu->save();
        $this->showModal = false;
        $this->toast('success', 'Menu Updated');
    }

    public function render()
    {
        $title = 'Menu Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                'link' => route("admin.menu.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Menu',
            ],
        ];

        $menus = Menu::orderby('id')->paginate(15);

        $menus->getCollection()->transform(function ($menu, $index) use ($menus) {
            $menu->row_number = ($menus->currentPage() - 1) * $menus->perPage() + $index + 1;
            return $menu;
        });

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'title', 'label' => 'Title'],
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
