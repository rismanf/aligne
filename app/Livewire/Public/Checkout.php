<?php

namespace App\Livewire\Public;

use App\Mail\CheckoutMembershipMail;
use App\Models\Menu;
use App\Models\Product;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        // Check if user already has active membership for this product
        $existingMembership = UserMembership::where('user_id', Auth::id())
            ->where('membership_id', $this->product->id)
            ->where('payment_status', 'paid')
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($existingMembership) {
            $this->toastError('You already have an active membership for this package.');
            return;
        }

        $invoiceNumber = 'INV-' . strtoupper(substr(uniqid(), 0, 5));
        
        // Calculate expiration date
        $expiresAt = null;
        if ($this->product->valid_until) {
            $expiresAt = now()->addDays($this->product->valid_until);
        }

        $data = UserMembership::create([
            'invoice_number' => $invoiceNumber,
            'user_id' => Auth::id(),
            'membership_id' => $this->product->id,
            'unique_code' => $this->uniqueCode,
            'price' => $this->product->price,
            'total_price' => $this->product->price + $this->uniqueCode,
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'expires_at' => $expiresAt,
            'created_by_id' => Auth::id(),
        ]);

        try {
            $userEmail = Auth::user()->email;
            Mail::to($userEmail)->send(new CheckoutMembershipMail($data->id));
            Log::info('Email sent to: ' . $userEmail);
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }

        session()->flash('success', 'Order placed successfully! Please complete your payment.');
        
        $this->js(<<<JS
                setTimeout(function () {
                    window.location.href = "/invoice/{$invoiceNumber}";
                }, 3000);
            JS);
    }

    public function mount($id)
    {
        $this->id = $id;
        $this->product = Product::with('classes')->find($id);

        if (!$this->product) {
            abort(404, 'Product not found');
        }

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
            'title' => $menu->title ?? 'Checkout',
            'description' => $menu->description ?? 'Complete your membership purchase',
            'keywords' => $menu->keywords ?? 'membership, fitness, checkout',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
