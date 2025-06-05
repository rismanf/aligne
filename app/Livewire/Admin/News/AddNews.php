<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;

class AddNews extends Component
{
    public $title,
        $description,
        $keywords,
        $author,
        $image,
        $body,
        $tags;

    public function save()
    {
        // $slug=str_pad(date('m'), 2, '0', STR_PAD_LEFT);
        $title_slug = strtolower(str_replace(' ', '-', $this->title));
        dd($title_slug);

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'keywords' => 'nullable|string|max:255',
            'author' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048', // max 2MB
            'body' => 'required|string',
            'tags' => 'nullable|string|max:255',
        ]);

        // Save the news article logic here
        // For example, you can create a new News model instance and save it to the database
        // Generate a slug from the title
        $slug = substr(date('Y'), -1) . str_pad(date('m'), 2, '0', STR_PAD_LEFT) . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

        $news = new News();
        $news->slug = $slug;
        $news->title_slug = $title_slug;
        $news->title = $this->title;
        $news->description = $this->description;
        $news->keywords = $this->keywords;
        $news->author = $this->author;
        $news->body = $this->body;
        $news->tags = $this->tags;
        if ($this->image) {
            $news->image = $this->image->store('news_images', 'public'); // Store the image in the public disk
        }
        $news->save();
        // Optionally, you can reset the form fields after saving
        $this->reset([
            'title',
            'description',
            'keywords',
            'author',
            'image',
            'body',
            'tags',
        ]);

        session()->flash('message', 'News article created successfully.');
    }
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

        return view('livewire.admin.news.add-news')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
