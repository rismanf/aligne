<?php

namespace App\Livewire\Public;

use App\Models\Menu;
use App\Models\Product;
use App\Models\UserProduk;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class Checkout extends Component
{
    use Toast;

    public $id, $product, $uniqueCode;
    public function save()
    {
        $this->validate([
            'id' => 'required|exists:products,id',
        ]);

        $this->product = Product::find($this->id);

        if (!$this->product) {
            $this->toastError('Product not found.');
            return;
        }

        $invoiceNumber = 'INV-' . strtoupper(substr(uniqid(), 0, 5));
        UserProduk::create(
            [
                'invoice_number' => $invoiceNumber,
                'unique_code' => $this->uniqueCode,
                'status' => 'pending',
                'price' => $this->product->price,
                'total_price' => $this->product->price + $this->uniqueCode,
                'kuota' => $this->product->kuota,
                'user_id' => Auth::id(),
                'product_id' => $this->product->id,
                'payment_status' => 'unpaid', // default payment status
                'created_by_id' => Auth::id(),
            ]
        );
        $this->uniqueCode = rand(100, 999);
        session(['unique_code' => $this->uniqueCode]);
        session()->flash('success', 'Order placed successfully! Please complete your payment.');
        $this->js(<<<JS
                setTimeout(function () {
                    window.location.href = "/invoice/{$invoiceNumber}"; // Redirect to user order page after 3 seconds
                }, 3000);
            JS);
    }

    public function mount($id)
    {
        $this->id = $id;
        $this->product = Product::find($id);

        if (!session()->has('unique_code')) {
            $this->uniqueCode = rand(100, 999);
            session(['unique_code' => $this->uniqueCode]);
        } else {
            $this->uniqueCode = session('unique_code');
        }
    }
    public function render()
    {

        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.checkout')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
