<?php

namespace App\Livewire\Public;

use App\Mail\CheckoutMembershipMail;
use App\Models\Menu;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Invoice extends Component
{
    public $invoice;

    public function mount($id)
    {
        $this->invoice = UserMembership::with(['membership', 'user'])
            ->where('invoice_number', $id)
            ->where('user_id', auth()->id())
            ->first();
            
        if (!$this->invoice) {
            abort(404, 'Invoice not found');
        }
    }
    
    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.public.invoice')->layout('components.layouts.website', [
            'title' => $menu->title ?? 'Invoice',
            'description' => $menu->description ?? 'Your membership invoice',
            'keywords' => $menu->keywords ?? 'invoice, membership, payment',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
