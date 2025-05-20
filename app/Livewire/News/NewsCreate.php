<?php

namespace App\Livewire\News;

use Livewire\Component;

class NewsCreate extends Component
{
    public $title,    
        $description,
        $keywords,
        $author,
        $image,
        $body,
        $tags;
        
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
                'link' => route('admin.news.index'), // route('home') = nama route yang ada di web.php
                'label' => 'News',
            ],
            [
                'link' => '', // route('home') = nama route yang ada di web.php
                'label' => 'Create News',
            ],
        ];

        return view('livewire.news.news-create')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
