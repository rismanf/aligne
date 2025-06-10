<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class ListNews extends Component
{

    use WithPagination;
    use Toast;

    public function mount() {}
    public function render()
    {
        $title = 'News Management';
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
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'News',
            ],
        ];

        $news = News::paginate(5);

        $news->getCollection()->transform(function ($val, $index) use ($news) {
            $val->row_number = ($news->currentPage() - 1) * $news->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'author', 'label' => 'Author'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.news.list-news', [
            't_headers' => $t_headers,
            'news' => $news,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);

    }
}
