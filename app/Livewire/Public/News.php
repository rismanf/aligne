<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\News as ModelsNews;
use Livewire\Component;

class News extends Component
{
    public function render()
    {
        $news = ModelsNews::where('is_active', true)
            ->orderby('updated_at', 'desc')
            ->paginate(6);

        $news->getCollection()->transform(function ($val, $index) use ($news) {
            $val->row_number = ($news->currentPage() - 1) * $news->perPage() + $index + 1;
            return $val;
        });

        $menu = Menu::where('name', 'News')->first();

        return view('livewire.public.news', [
            'news' => $news,
        ])->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
