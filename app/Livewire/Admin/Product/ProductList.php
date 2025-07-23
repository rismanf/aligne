<?php

namespace App\Livewire\Admin\Product;

use App\Models\ClassMembership;
use App\Models\GroupClass;
use App\Models\Product;
use Livewire\Component;
use Mary\Traits\Toast;

class ProductList extends Component
{
    use Toast;

    public $id, $name, $description, $kuota, $price, $valid_until, $class_id;

    public bool $createForm = false;
    public bool $editForm = false;
    public bool $detailForm = false;
    public bool $deleteForm = false;

    public $class_list = [];
    public $class_kuotas = [
        ['class_id' => '', 'kuota' => '']
    ];

    public function mount()
    {
        $this->class_list = GroupClass::all();
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

        $data_all = Product::orderBy('created_at', 'desc')->paginate(5);

        $data_all->getCollection()->transform(function ($val, $index) use ($data_all) {
            $val->row_number = ($data_all->currentPage() - 1) * $data_all->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'price', 'label' => 'Price'],
            ['key' => 'kuota', 'label' => 'Kuota'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
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
        $this->createForm = true;
    }

    public function save()
    {

        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'valid_until' => 'required|numeric',
            'description' => 'required|string|max:255|min:10',
            'class_kuotas.*.class_id' => 'required|exists:classes,id',
            'class_kuotas.*.kuota' => 'required|integer|min:1',
        ]);

        $data_product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'valid_until' => $this->valid_until
        ]);

        foreach ($this->class_kuotas as $item) {
            ClassMembership::create([
                'membership_id' => $data_product->id,
                'class_id' => $item['class_id'],
                'quota' => $item['kuota'],
            ]);
        }

        $this->reset();
        $this->createForm = false;

        $this->toast(
            type: 'success',
            title: 'Product Created',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)   
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
        $this->id = $id;
        $this->class_kuotas = $classDetails->map(function ($item) {
            return [
                'class_id' => $item->class_id,
                'kuota' => $item->quota
            ];
        })->toArray();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'valid_until' => 'required|numeric',
            'description' => 'required|string|max:255|min:10',
            'class_kuotas.*.class_id' => 'required|exists:classes,id',
            'class_kuotas.*.kuota' => 'required|integer|min:1',
        ]);

        $product = Product::find($this->id);
        $product->name = $this->name;
        $product->description = $this->description;
        $product->valid_until = $this->valid_until;
        $product->price = $this->price;
        $product->save();

        ClassMembership::where('membership_id', $this->id)->delete();
        foreach ($this->class_kuotas as $item) {
            ClassMembership::create([
                'membership_id' => $this->id,
                'class_id' => $item['class_id'],
                'quota' => $item['kuota'],
            ]);
        }

        $this->reset();
        $this->editForm = false;

        $this->toast(
            type: 'success',
            title: 'Product Updated',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }

    public function showDetailModal($id)
    {
        $this->detailForm = true;
        $product = Product::find($id);
        $this->name = $product->name;
        $this->description = $product->description;
        $this->kuota = $product->kuota;
        $this->price = $product->price;
    }
    public function showDeleteModal($id)
    {
        $this->id = $id;
        $this->deleteForm = true;
    }
    public function delete()
    {
        Product::find($this->id)->delete();
        $this->reset();
        $this->deleteForm = false;
        $this->toast(
            type: 'success',
            title: 'Product Deleted',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
