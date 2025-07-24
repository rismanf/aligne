<?php

namespace App\Livewire\User;

use App\Mail\PaymentNotificationMail;
use App\Models\ManageMail;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserProduk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class Order extends Component
{
    use WithFileUploads;
    public $orders;
    // public $showModal = false;
    public $selectedOrder;
    public $bank;
    public $payment_proof;
    public $order_id;
    public $invoice_number;

    public function mount()
    {
        $this->orders = UserProduk::with('product')->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->limit(30)
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
            'payment_proof' => 'required|file|max:5120',
            // 'order_id' => 'required',
        ]);
        try {

            $url = $this->payment_proof->store('payment_proof', 'public');
            $user_produk = UserProduk::find($this->order_id);
            $user_produk->update([
                'payment_method' => $this->bank,
                'payment_proof' => $url,
                'payment_status' => 'waiting payment confirmation',
                'paid_at' => now(),
                'updated_by_id' => auth()->id(),
            ]);

            try {
                $data_user = User::find(auth()->id());
                $data_send_mail = [
                    'name' => $data_user->name,
                    'phone' => $data_user->phone,
                    'email' => $data_user->email,
                    'invoice' => $user_produk->invoice_number,
                    'payment_method' => $user_produk->payment_method,
                    'total_price' => $user_produk->total_price,
                    'paid_at' => $user_produk->paid_at,
                ];

                $listMailPIC = ManageMail::where('type', 'New Payment')->pluck('email')->toArray();
                Log::info("START Sending email to PIC for type: New Payment");
                Mail::to($listMailPIC)->send(new PaymentNotificationMail($data_send_mail));

                Log::info('send mail PIC' . json_encode($data_send_mail));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email: ' . $e->getMessage());
            }

            session()->flash('success', 'Payment successfully confirmed!');
            $this->dispatch('reload-page');
        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Failed to create participant: ' . $e->getMessage());
        }
    }
}
