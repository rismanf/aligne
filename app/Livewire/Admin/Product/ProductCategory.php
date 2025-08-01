<?php

namespace App\Livewire\Admin\Product;

use App\Models\PackageCategory;
use Livewire\Component;
use Mary\Traits\Toast;

class ProductCategory extends Component
{

    use Toast;

    public $id, $name, $display_name, $description, $sort_order;
    public bool $is_active = true;

    public bool $createForm = false;
    public bool $editForm = false;
    public bool $deleteForm = false;

    public function render()
    {
        $title = 'Membership Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Membership',
            ],
        ];


        $data_all = PackageCategory::orderBy('created_at', 'desc')->paginate(5);

        $data_all->getCollection()->transform(function ($val, $index) use ($data_all) {
            $val->row_number = ($data_all->currentPage() - 1) * $data_all->perPage() + $index + 1;
            return $val;
        });

        $t_headers = [
            ['key' => 'sort_order', 'label' => 'Order', 'class' => 'w-16'],
            ['key' => 'display_name', 'label' => 'Display Name'],
            ['key' => 'name', 'label' => 'System Name'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'is_active', 'label' => 'Status', 'class' => 'w-20'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-32'],
        ];

        return view('livewire.admin.product.product-category', [
            't_headers' => $t_headers,
            'categories_data' => $data_all,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);

        // return view('livewire.admin.product.product-category');

    }

    public function showAddModal()
    {
        $this->resetForm();
        $this->createForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:50|unique:package_categories,name',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
        ]);

        try {
            PackageCategory::create([
                'name' => $this->name,
                'display_name' => $this->display_name,
                'description' => $this->description,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ]);

            $this->resetForm();
            $this->createForm = false;

            $this->toast(
                type: 'success',
                title: 'Category Created',
                description: 'Package category has been created successfully',
                position: 'toast-top toast-end',
                icon: 'o-check-circle',
                css: 'alert-success',
                timeout: 3000,
            );
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to create category: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000,
            );
        }
    }

    public function showEditModal($id)
    {
        try {
            $this->editForm = true;
            $category = PackageCategory::find($id);

            $this->id = $id;
            $this->name = $category->name;
            $this->display_name = $category->display_name;
            $this->description = $category->description;
            $this->sort_order = $category->sort_order;
            $this->is_active = $category->is_active;
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to load category: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000,
            );
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:50|unique:package_categories,name,' . $this->id,
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
        ]);

        try {
            $category = PackageCategory::find($this->id);
            $category->update([
                'name' => $this->name,
                'display_name' => $this->display_name,
                'description' => $this->description,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ]);

            $this->resetForm();
            $this->editForm = false;

            $this->toast(
                type: 'success',
                title: 'Category Updated',
                description: 'Package category has been updated successfully',
                position: 'toast-top toast-end',
                icon: 'o-check-circle',
                css: 'alert-success',
                timeout: 3000,
            );
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to update category: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000,
            );
        }
    }

    public function showDeleteModal($id)
    {
        $this->id = $id;
        $this->deleteForm = true;
    }

    public function delete()
    {
        try {
            $category = PackageCategory::find($this->id);

            // Check if category has products
            if ($category->products()->count() > 0) {
                $this->toast(
                    type: 'error',
                    title: 'Cannot Delete',
                    description: 'This category has products associated with it. Please reassign or delete the products first.',
                    position: 'toast-top toast-end',
                    icon: 'o-x-circle',
                    css: 'alert-error',
                    timeout: 5000,
                );
                $this->deleteForm = false;
                return;
            }

            $category->delete();

            $this->resetForm();
            $this->deleteForm = false;

            $this->toast(
                type: 'success',
                title: 'Category Deleted',
                description: 'Package category has been deleted successfully',
                position: 'toast-top toast-end',
                icon: 'o-check-circle',
                css: 'alert-success',
                timeout: 3000,
            );
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to delete category: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000,
            );
        }
    }

    public function toggleStatus($id)
    {
        try {
            $category = PackageCategory::find($id);
            $category->update(['is_active' => !$category->is_active]);

            $status = $category->is_active ? 'activated' : 'deactivated';
            $this->toast(
                type: 'success',
                title: 'Status Updated',
                description: "Category has been {$status}",
                position: 'toast-top toast-end',
                icon: 'o-check-circle',
                css: 'alert-success',
                timeout: 2000,
            );
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to update status: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-x-circle',
                css: 'alert-error',
                timeout: 5000,
            );
        }
    }

    private function resetForm()
    {
        $this->reset(['id', 'name', 'display_name', 'description', 'sort_order']);
        $this->is_active = true;
    }
}
