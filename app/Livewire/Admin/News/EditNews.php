<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\Tags;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class EditNews extends Component
{
    use Toast;
    use WithFileUploads;

    public $id, $title,
        $description,
        $keywords,
        $author,
        $image,
        $body,
        $oldCover;

    public $published;
    public $published_at;
    public $published_date_show = false;
    public $TagModal = false;
    public $name_tag;
    public $tag_list = [];
    public $tag_list_ids = [];

    public function showTagModal()
    {
        $this->TagModal = true;
    }

    public function savetags()
    {
        $this->validate([
            'name_tag' => 'required|string|max:255',
        ]);

        Tags::create([
            'name' => $this->name_tag,
            'slug' => Str::slug($this->name_tag),
            'created_by_id' => auth()->id(),
            'updated_by_id' => auth()->id(),
            // You can add more fields if needed
        ]);

        // Reset the name_tag field after saving
        $this->name_tag = '';
        // Refresh the tag list
        $this->tag_list = Tags::all()->map(function ($tag) {
            return ['id' => $tag->id, 'name' => $tag->name];
        })->toArray();

        // Here you can save the tag or perform any action you need
        $this->success('Tag Created');
        $this->TagModal = false; // Close the modal after saving
    }

    public function togglePublished()
    {
        if ($this->published) {
            $this->published_date_show = true;
        } else {
            $this->published_date_show = false;
        }
        $this->published_at = $this->published_at ? now()->format('Y-m-d') : null;
    }
    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'keywords' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:5120', // 5MB max
            'body' => 'required|string',
        ]);

        $news_update = News::find($this->id);
        if (!$news_update) {
            session()->flash('error', 'News not found.');
            return;
        }

        if ($this->image) {
            session()->flash('error', 'Gagal upload gambar.');
            return;


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
        } else {
            // If no new image is uploaded, use the old cover image
            $data = [
                'image_original' => $news_update->image_original,
                'image_medium' => $news_update->image_medium,
                'image_small' => $news_update->image_small,
            ];
        }

        if ($this->author == null) {
            $this->author = 'Admin';
        }

        // Generate slug based on current year and month, and a random number
        $title_slug = Str::slug($this->title);

        // if ($this->published) {
        //     $published_at = $news_update->published_at;
        // } else {
            $published_at = $this->published_at;
        // }

        $news_update->update([
            'title' => $this->title,
            'title_slug' =>  $title_slug,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'author' => $this->author,
            'image_original' => $data['image_original'],
            'image_medium' => $data['image_medium'],
            'image_small' => $data['image_small'],
            'body' => $this->body,
            'is_active' => $this->published ? 1 : 0,
            'published_at' => $published_at,
            'updated_by_id' => auth()->id(),
        ]);

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

    public function mount($id)
    {

        $data = News::find($id);
        $this->title = $data->title;
        $this->description = $data->description;
        $this->keywords = $data->keywords;
        $this->author = $data->author;
        $this->image = $data->image;
        $this->body = $data->body;
        if ($data->is_active == 1) {
            $this->published_date_show = true;
        } else {
            $this->published_date_show = false;
        }
        $this->published = $data->is_active;
        $this->published_at = $data->published_at;
        $this->oldCover = $data->image_small;
    }
    public function render()
    {
        $this->tag_list = Tags::all()->map(function ($tag) {
            return ['id' => $tag->id, 'name' => $tag->name];
        })->toArray();
        // $this->tag_list = [
        //     ['id' => 1, 'name' => 'Joe'],
        // ];
        // dd($this->tag_list);
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
                'label' => 'Edit News',
            ],
        ];

        return view('livewire.admin.news.edit-news')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
