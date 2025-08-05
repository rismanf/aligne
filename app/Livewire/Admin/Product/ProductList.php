<?php

namespace App\Livewire\Admin\Product;

use App\Models\ClassMembership;
use App\Models\GroupClass;
use App\Models\Product;
use App\Models\PackageCategory;
use Livewire\Component;
use Mary\Traits\Toast;

class ProductList extends Component
{
    use Toast;

    public $id, $name, $description, $kuota, $price, $valid_until, $class_id, $category, $package_type, $quota_strategy;

    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;

    public $class_list = [];
    public $class_kuotas = [
        ['class_id' => '', 'kuota' => '']
    ];

    // Package categories
    public $categories = [];

    // Package types for signature category
    public $signatureTypes = [];

    public function mount()
    {
        // Initialize quota_strategy with default value
        $this->quota_strategy = 'fixed';
        
        $this->class_list = GroupClass::all()->map(function($class) {
            return [
                'id' => $class->id,
                'name' => $class->name . ' (' . ucfirst($class->category) . ')'
            ];
        })->toArray();

        // Load categories from database - with fallback
        try {
            $this->categories = PackageCategory::active()->ordered()->get()->map(function($category) {
                return [
                    'id' => $category->name,
                    'name' => $category->display_name
                ];
            })->toArray();
        } catch (\Exception $e) {
            // Fallback categories if database query fails
            $this->categories = [
                ['id' => 'signature', 'name' => 'SIGNATURE CLASS PACK'],
                ['id' => 'functional', 'name' => 'FUNCTIONAL MOVEMENT PACK'],
                ['id' => 'vip', 'name' => 'VIP MEMBERSHIP'],
                ['id' => 'special', 'name' => 'SPECIAL PACKAGES']
            ];
        }

        // Initialize signature types for select component
        $this->signatureTypes = [
            ['id' => 'core_series', 'name' => 'The Core Series'],
            ['id' => 'elevate_pack', 'name' => 'Elevate Pack'],
            ['id' => 'aligne_flow', 'name' => 'Aligné Flow']
        ];
    }

    private function getCategoryDisplayName($categoryName)
    {
        try {
            $category = PackageCategory::where('name', $categoryName)->first();
            if ($category) {
                return $category->display_name;
            }
        } catch (\Exception $e) {
            // Fallback if database query fails
        }
        
        // Fallback mapping
        $categoryMap = [
            'signature' => 'SIGNATURE CLASS PACK',
            'functional' => 'FUNCTIONAL MOVEMENT PACK',
            'vip' => 'VIP MEMBERSHIP',
            'special' => 'SPECIAL PACKAGES'
        ];
        
        return $categoryMap[$categoryName] ?? strtoupper($categoryName);
    }

    public function addClassKuota()
    {
        $this->class_kuotas[] = ['class_id' => '', 'kuota' => ''];
    }

    public function removeClassKuota($index)
    {
        unset($this->class_kuotas[$index]);
        $this->class_kuotas = array_values($this->class_kuotas); // reset index
    }

    public function render()
    {
        $title = 'Membership Package Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"),
                'label' => 'Home',
                'icon' => 's-home',
            ],
            [
                'label' => 'Membership Packages',
            ],
        ];

        try {
            $data_all = Product::with('groupClasses')->orderBy('created_at', 'desc')->paginate(10);

            $data_all->getCollection()->transform(function ($val, $index) use ($data_all) {
                $val->row_number = ($data_all->currentPage() - 1) * $data_all->perPage() + $index + 1;
                $val->category_display = $this->getCategoryDisplayName($val->category ?? 'signature');
                
                try {
                    $val->total_classes = $val->groupClasses->sum('pivot.quota');
                } catch (\Exception $e) {
                    $val->total_classes = 0;
                }
                
                $val->formatted_price = 'Rp ' . number_format($val->price ?? 0, 0, ',', '.');
                $val->validity_text = ($val->valid_until ?? 0) . ' days';
                
                // Get package type display name
                if ($val->package_type) {
                    $typeMap = [
                        'core_series' => 'The Core Series',
                        'elevate_pack' => 'Elevate Pack',
                        'aligne_flow' => 'Aligné Flow'
                    ];
                    $val->package_type_display = $typeMap[$val->package_type] ?? $val->package_type;
                } else {
                    $val->package_type_display = '-';
                }
                
                return $val;
            });
        } catch (\Exception $e) {
            $data_all = collect()->paginate(10);
        }

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Package Name'],
            ['key' => 'category_display', 'label' => 'Category'],
            ['key' => 'package_type_display', 'label' => 'Type'],
            ['key' => 'formatted_price', 'label' => 'Price'],
            ['key' => 'total_classes', 'label' => 'Total Classes'],
            ['key' => 'validity_text', 'label' => 'Validity'],
            ['key' => 'updated_at', 'label' => 'Last Updated'],
            ['key' => 'action', 'label' => 'Actions', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.product.product-list', [
            't_headers' => $t_headers,
            'products' => $data_all,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }


    public function showAddModal()
    {
        $this->resetForm();
        $this->createForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'valid_until' => 'required|numeric',
            'description' => 'required|string|max:255|min:10',
            'quota_strategy' => 'required|in:flexible,fixed',
            'class_kuotas.*.class_id' => 'required',
            'class_kuotas.*.kuota' => 'required|integer|min:1',
        ]);

        $data_product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'valid_until' => $this->valid_until,
            'category' => $this->category,
            'package_type' => $this->package_type,
            'quota_strategy' => $this->quota_strategy,
            'is_active' => true
        ]);

        // For flexible quota, all class types should have the same quota
        if ($this->quota_strategy === 'flexible') {
            $totalQuota = $this->class_kuotas[0]['kuota'] ?? 0;
            foreach ($this->class_kuotas as $item) {
                ClassMembership::create([
                    'membership_id' => $data_product->id,
                    'class_id' => $item['class_id'],
                    'quota' => $totalQuota, // Same quota for all classes in flexible packages
                ]);
            }
        } else {
            // For fixed quota, use individual quotas
            foreach ($this->class_kuotas as $item) {
                ClassMembership::create([
                    'membership_id' => $data_product->id,
                    'class_id' => $item['class_id'],
                    'quota' => $item['kuota'],
                ]);
            }
        }

        $this->reset('name', 'description', 'price', 'valid_until', 'category', 'package_type', 'quota_strategy', 'class_kuotas');
        $this->createForm = false;

        $this->toast(
            type: 'success',
            title: 'Membership Package Created',
            description: 'Package has been created successfully',
            position: 'toast-top toast-end',
            icon: 'o-check-circle',
            css: 'alert-success',
            timeout: 3000,
            redirectTo: null
        );
    }

    public function showEditModal($id)
    {
        $this->editForm = true;
        $product = Product::find($id);
        $classDetails = ClassMembership::where('membership_id', $id)->get();
        
        $this->name = $product->name;
        $this->description = $product->description;
        $this->valid_until = $product->valid_until;
        $this->price = $product->price;
        $this->category = $product->category ?? 'signature';
        $this->package_type = $product->package_type;
        $this->quota_strategy = $product->quota_strategy ?? 'fixed';
        $this->id = $id;
        
        $this->class_kuotas = $classDetails->map(function ($item) {
            return [
                'class_id' => $item->class_id,
                'kuota' => $item->quota
            ];
        })->toArray();
        
        // Ensure at least one class quota entry
        if (empty($this->class_kuotas)) {
            $this->class_kuotas = [['class_id' => '', 'kuota' => '']];
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'valid_until' => 'required|numeric',
            'description' => 'required|string|max:255|min:10',
            'quota_strategy' => 'required|in:flexible,fixed',
            'class_kuotas.*.class_id' => 'required',
            'class_kuotas.*.kuota' => 'required|integer|min:1',
        ]);

        $product = Product::find($this->id);
        $product->update([
            'name' => $this->name,
            'description' => $this->description,
            'valid_until' => $this->valid_until,
            'price' => $this->price,
            'category' => $this->category,
            'package_type' => $this->package_type,
            'quota_strategy' => $this->quota_strategy,
        ]);

        ClassMembership::where('membership_id', $this->id)->delete();
        
        // For flexible quota, all class types should have the same quota
        if ($this->quota_strategy === 'flexible') {
            $totalQuota = $this->class_kuotas[0]['kuota'] ?? 0;
            foreach ($this->class_kuotas as $item) {
                ClassMembership::create([
                    'membership_id' => $this->id,
                    'class_id' => $item['class_id'],
                    'quota' => $totalQuota, // Same quota for all classes in flexible packages
                ]);
            }
        } else {
            // For fixed quota, use individual quotas
            foreach ($this->class_kuotas as $item) {
                ClassMembership::create([
                    'membership_id' => $this->id,
                    'class_id' => $item['class_id'],
                    'quota' => $item['kuota'],
                ]);
            }
        }

         $this->reset('name', 'description', 'price', 'valid_until', 'category', 'package_type', 'quota_strategy', 'class_kuotas');
        $this->editForm = false;

        $this->toast(
            type: 'success',
            title: 'Membership Package Updated',
            description: 'Package has been updated successfully',
            position: 'toast-top toast-end',
            icon: 'o-check-circle',
            css: 'alert-success',
            timeout: 3000,
            redirectTo: null
        );
    }

    public function showDetailModal($id)
    {
        $this->detailForm = true;
        $product = Product::with('groupClasses')->find($id);
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->valid_until = $product->valid_until;
        $this->category = $product->category ?? 'signature';
        $this->package_type = $product->package_type;
        $this->quota_strategy = $product->quota_strategy ?? 'fixed';
        
        // Get class details for display
        $this->class_kuotas = $product->groupClasses->map(function($class) {
            return [
                'class_name' => $class->name,
                'class_category' => ucfirst($class->category),
                'kuota' => $class->pivot->quota
            ];
        })->toArray();
    }

    public function showDeleteModal($id)
    {
        $this->id = $id;
        $this->deleteForm = true;
    }

    public function delete()
    {
        $product = Product::find($this->id);
        
        // Delete related class memberships first
        ClassMembership::where('membership_id', $this->id)->delete();
        
        // Delete the product
        $product->delete();
        
        $this->reset();
        $this->deleteForm = false;
        
        $this->toast(
            type: 'success',
            title: 'Membership Package Deleted',
            description: 'Package and all related data have been deleted successfully',
            position: 'toast-top toast-end',
            icon: 'o-check-circle',
            css: 'alert-success',
            timeout: 3000,
            redirectTo: null
        );
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'price', 'valid_until', 'category', 'package_type']);
        $this->quota_strategy = 'fixed';
        $this->class_kuotas = [['class_id' => '', 'kuota' => '']];
    }
}
