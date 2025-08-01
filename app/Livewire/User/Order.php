<?php

namespace App\Livewire\User;

use App\Mail\PaymentNotificationMail;
use App\Models\ManageMail;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class Order extends Component
{
    use WithFileUploads;
    public $orders;
    public $selectedOrder;
    public $bank;
    public $payment_proof;
    public $order_id;
    public $invoice_number;

    public function mount()
    {
        $this->orders = UserMembership::with(['membership', 'user'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();
    }
    
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.user.order')->layout('components.layouts.website', [
            'title' => $menu->title ?? 'My Orders',
            'description' => $menu->description ?? 'View your membership orders',
            'keywords' => $menu->keywords ?? 'orders, membership, payment',
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
        ]);
        
        try {
            $url = $this->payment_proof->store('payment_proof', 'public');
            $userMembership = UserMembership::find($this->order_id);
            
            $userMembership->update([
                'payment_method' => $this->bank,
                'payment_proof' => $url,
                'payment_status' => 'pending',
                'paid_at' => now(),
                'updated_by_id' => auth()->id(),
            ]);

            try {
                $data_user = User::find(auth()->id());
                $data_send_mail = [
                    'name' => $data_user->name,
                    'phone' => $data_user->phone,
                    'email' => $data_user->email,
                    'invoice' => $userMembership->invoice_number,
                    'payment_method' => $userMembership->payment_method,
                    'total_price' => $userMembership->total_price,
                    'paid_at' => $userMembership->paid_at,
                ];

                $listMailPIC = ManageMail::where('type', 'New Payment')->pluck('email')->toArray();
                if (!empty($listMailPIC)) {
                    Log::info("START Sending email to PIC for type: New Payment");
                    Mail::to($listMailPIC)->send(new PaymentNotificationMail($data_send_mail));
                    Log::info('send mail PIC' . json_encode($data_send_mail));
                }
            } catch (\Exception $e) {
                Log::error('Gagal kirim email: ' . $e->getMessage());
            }

            session()->flash('success', 'Payment proof uploaded successfully! Please wait for admin confirmation.');
            $this->dispatch('reload-page');
            
        } catch (\Exception $e) {
            Log::error('Payment upload error: ' . $e->getMessage());
            session()->flash('error', 'Failed to upload payment proof: ' . $e->getMessage());
        }
    }
}
