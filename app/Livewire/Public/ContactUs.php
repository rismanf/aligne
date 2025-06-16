<?php

namespace App\Livewire\Public;

use App\Models\ContactUs as ModelsContactUs;
use App\Models\Menu;
use Livewire\Component;

class ContactUs extends Component
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

        ModelsContactUs::create([
            'source' => 'NeutraDC Contact Us',
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
   
        // Example: Mail::to('  )->send(new ContactUsMail($this->first_name, $this->last_name, $this->email, $this->message));

        session()->flash('success', 'Thank you for contacting us! We will get back to you as soon as possible.');
    }
    public function render()
    {
        $menu = Menu::where('name', 'Contact Us')->first();

        return view('livewire.public.contact-us')->layout('components.layouts.website', [
            'title' => $menu->title,
            'description' => $menu->description,
            'keywords' => $menu->keywords,
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
