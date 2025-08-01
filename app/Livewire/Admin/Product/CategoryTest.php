<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;

class CategoryTest extends Component
{
    public function render()
    {
        return view('livewire.admin.product.category-test')->layout('components.layouts.app', [
            'title' => 'Category Test',
            'breadcrumbs' => []
        ]);
    }
}
