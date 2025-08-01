<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\Product;
use App\Models\PackageCategory;
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
        // Load categories from PackageCategory model
        $this->loadCategories();
        $this->loadProducts();
    }

    private function loadCategories()
    {
        try {
            $dbCategories = PackageCategory::active()->ordered()->get();
            
            $this->categories = ['all' => 'All Packages'];
            
            foreach ($dbCategories as $category) {
                $this->categories[$category->name] = $category->display_name;
            }
        } catch (\Exception $e) {
            // Fallback categories if database query fails
            $this->categories = [
                'all' => 'All Packages',
                'signature' => 'SIGNATURE CLASS PACK',
                'functional' => 'FUNCTIONAL CLASS PACK',
                'vip' => 'VIP MEMBERSHIP',
                'special' => 'SPECIAL PACKAGES'
            ];
        }
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

        if ($this->selectedCategory !== 'all') {
            // Filter by the actual category from database
            $query->where('category', $this->selectedCategory);
        }

        $this->products = $query->get();
    }

    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        // Group products by their actual database categories
        $groupedProducts = [];
        
        try {
            $dbCategories = PackageCategory::active()->ordered()->get();
            
            foreach ($dbCategories as $category) {
                $categoryProducts = $this->products->filter(function($product) use ($category) {
                    return $product->category === $category->name;
                });
                
                if ($categoryProducts->count() > 0) {
                    $groupedProducts[$category->name] = [
                        'display_name' => $category->display_name,
                        'description' => $category->description,
                        'products' => $categoryProducts->sortBy('name')
                    ];
                }
            }
            
            // Add products without category to 'other'
            $uncategorizedProducts = $this->products->filter(function($product) use ($dbCategories) {
                return !$dbCategories->pluck('name')->contains($product->category);
            });
            
            if ($uncategorizedProducts->count() > 0) {
                $groupedProducts['other'] = [
                    'display_name' => 'Other Packages',
                    'description' => 'Additional membership packages',
                    'products' => $uncategorizedProducts->sortBy('name')
                ];
            }
            
        } catch (\Exception $e) {
            // Fallback grouping if database query fails
            $groupedProducts = [
                'signature' => [
                    'display_name' => 'SIGNATURE CLASS PACK',
                    'description' => 'For Reformer / Chair Classes',
                    'products' => $this->products->filter(function($product) {
                        return $product->category === 'signature';
                    })
                ],
                'functional' => [
                    'display_name' => 'FUNCTIONAL CLASS PACK',
                    'description' => 'For Functional Movement Classes',
                    'products' => $this->products->filter(function($product) {
                        return $product->category === 'functional';
                    })
                ]
            ];
        }

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
