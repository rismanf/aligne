<?php

namespace App\Livewire\Public\DataCenter;

use App\Mail\ContactUsEmail;
use App\Models\ContactUs;
use App\Models\Menu;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Jakarta extends Component
{
    public $first_name,
        $last_name,
        $company,
        $job,
        $country,
        $email,
        $phone,
        $message,
        $terms;
    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'company' => 'required|string|max:100',
            'job' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:100',
            'message' => 'required|string|min:10|max:1000',
            'terms' => 'accepted',
        ]);

        ContactUs::create([
            'source' => 'NeutraDC Jakarta',
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'job_title' => $this->job,
            'country' => $this->country,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ]);

        Mail::to($this->email)->send(new ContactUsEmail());

        $this->reset([
            'first_name',
            'last_name',
            'company',
            'job',
            'country',
            'email',
            'phone',
            'message',
            'terms',
        ]);

        // Example: Mail::to('      ' )->send(new ContactUsMail($this->first_name, $this->last_name, $this->email, $this->message));

        session()->flash('success', 'Thank you for contacting us! We will get back to you as soon as possible.');
    }
    public function render()
    {
        $menu = Menu::where('name', 'NeutraDC Jakarta')->first();

        return view('livewire.public.data-center.jakarta')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
