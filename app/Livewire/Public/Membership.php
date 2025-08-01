<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\Product;
use App\Models\GroupClass;
use Livewire\Component;

class Membership extends Component
{
    public $title, $description, $keywords;
    public $products;
    public $selectedCategory = 'all';
    public $categories;

    public function mount()
    {
        // Updated categories to match the new system
        $this->categories = [
            'all' => 'All Packages',
            'signature' => 'SIGNATURE CLASS PACK',
            'functional' => 'FUNCTIONAL CLASS PACK'
        ];

        $this->loadProducts();
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
        $this->loadProducts();
    }

    private function loadProducts()
    {
        $query = Product::with(['groupClasses' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true);

        if ($this->selectedCategory === 'signature') {
            // Only show packages that include Reformer/Chair classes and no Functional classes
            $query->signatureClassPack();
        } elseif ($this->selectedCategory === 'functional') {
            // Only show packages that include Functional classes
            $query->functionalClassPack();
        }

        $this->products = $query->get();
    }

    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        // Group products by their actual category and specific types
        $groupedProducts = [
            'signature' => $this->products->filter(function($product) {
                return $product->isSignatureClassPack();
            })->sortBy(function($product) {
                // Sort signature packages: Core Series, Elevate Pack, Aligné Flow
                $order = [
                    'The Core Series' => 1,
                    'Elevate Pack' => 2,
                    'Aligné Flow' => 3
                ];
                return $order[$product->name] ?? 999;
            }),
            'functional' => $this->products->filter(function($product) {
                return $product->includesFunctionalClasses();
            }),
            'other' => $this->products->filter(function($product) {
                return !$product->isSignatureClassPack() && !$product->includesFunctionalClasses();
            })
        ];

        return view('livewire.public.membership', [
            'groupedProducts' => $groupedProducts,
            'categories' => $this->categories,
            'selectedCategory' => $this->selectedCategory
        ])->layout('components.layouts.website', [
            'title' => $menu->title ?? 'Membership Packages',
            'description' => $menu->description ?? 'Choose your fitness membership package',
            'keywords' => $menu->keywords ?? 'membership, fitness, reformer, chair, functional',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
