<?php

namespace App\Livewire\User;

use App\Models\Menu;
use App\Models\UserProduk;
use Livewire\Component;

class Order extends Component
{
    public $orders;
    // public $showModal = false;
    public $selectedOrder;
    public $bank, $payment_proof;
    public $order_id;
    public $invoice_number;

    public function mount()
    {
        $this->orders = UserProduk::with('product')->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.user.order')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }

    public function showModal($id, $invoice)
    {
        $this->order_id = $id;
        $this->invoice_number = $invoice;
        $this->dispatch('open-payment-modal');
    }

    public function save()
    {
        $this->validate([
            'bank' => 'required',
            // 'payment_proof' => 'required',
            // 'order_id' => 'required',
        ]);

        try {
            UserProduk::where('id', $this->order_id)->update([
                'payment_method' => $this->bank,
                // 'payment_proof' => $this->payment_proof,
                'payment_status' => 'waiting payment confirmation',
                'paid_at' => now(),
                'updated_by_id' => auth()->id(),
            ]);
            session()->flash('success', 'Payment successfully confirmed!');
             $this->dispatch('reload-page');
        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Failed to create participant: ' . $e->getMessage());
        }
    }
}
