<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class AddNews extends Component
{
    use Toast;
    use WithFileUploads;

    public $title,
        $description,
        $keywords,
        $author,
        $image,
        $body;

    public function save()
    {


        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'keywords' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:100',
            'image' => 'required|image|max:2048', // max 2MB
            'body' => 'required|string',
        ]);

        if (!$this->image->isValid()) {
            session()->flash('error', 'Gagal upload gambar.');
            return;
        }

        $manager = new ImageManager(new GdDriver());
        $filename = Str::uuid()->toString();
        $path = 'news-covers/';
        $ext = 'webp';
        $imageData = $this->image->get();

        $original = $manager->read($imageData)->toWebp(90);
        Storage::disk('s3')->put("{$path}{$filename}_original.{$ext}", (string)$original);

        $medium = $manager->read($imageData)->scale(width: 600)->toWebp(80);
        Storage::disk('s3')->put("{$path}{$filename}_medium.{$ext}", (string)$medium);

        $small = $manager->read($imageData)->scale(width: 300)->toWebp(80);
        Storage::disk('s3')->put("{$path}{$filename}_small.{$ext}", (string)$small);

        $data = [
            'image_original' => $path . $filename . '_original.webp',
            'image_medium' => $path . $filename . '_medium.webp',
            'image_small' => $path . $filename . '_small.webp',
        ];

        if ($this->author == null) {
            $this->author = 'Admin';
        }

        // Generate slug based on current year and month, and a random number
        $slug = substr(date('Y'), -1) . str_pad(date('m'), 2, '0', STR_PAD_LEFT) . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        $title_slug = Str::slug($this->title);

        $news = new News();
        $news->slug = $slug;
        $news->title_slug = $title_slug;
        $news->title = $this->title;
        $news->description = $this->description;
        $news->keywords = $this->keywords;
        $news->author = $this->author;
        $news->body = $this->body;
        $news->image_original = $data['image_original'];
        $news->image_medium = $data['image_medium'];
        $news->image_small = $data['image_small'];
        $news->created_by_id = auth()->id();
        $news->updated_by_id = auth()->id();
        $news->save();

        // Reset the form fields after saving
        $this->reset([
            'title',
            'description',
            'keywords',
            'author',
            'image',
            'body',
            // 'tags', // Uncomment if you have tags field
        ]);

        $this->success(
            'It is working with redirect',
            'You were redirected to another url ...',
            redirectTo: route('admin.news.index') // Redirect to the news index page after saving
        );
        // return redirect()->route('admin.news.index');
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
