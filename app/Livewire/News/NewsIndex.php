<?php

namespace App\Livewire\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class NewsIndex extends Component
{
    use WithPagination;
    use Toast;

    public $selectedUserId;
    public bool $createForm = false;
    
    public bool $deleteModal = false;
    public $title;
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
                'link' => '', // route('home') = nama route yang ada di web.php
                'label' => 'News',
            ],
        ];

        $news = News::orderBy('id', 'DESC')->paginate(5);

        $news->getCollection()->transform(function ($new, $index) use ($news) {
            $news->row_number = ($news->currentPage() - 1) * $news->perPage() + $index + 1;
            return $new;
        });

        // dd($news);

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => 'title'],
            // ['key' => 'email', 'label' => 'Email'],
            // ['key' => 'roles.0.name', 'label' => 'Role'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-2'],
        ];

        return view('livewire.news.news-index', [
            't_headers' => $t_headers,
            'news' => $news,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
