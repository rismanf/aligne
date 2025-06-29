<?php

namespace App\Livewire\Public;

use App\Mail\ContactUsEmail;
use App\Models\ContactUs as ModelsContactUs;
use App\Models\Menu;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactUs extends Component
{

    public $name,
        $email,
        $subject,
        $message;
    public function save()
    {
        $this->validate([
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:100',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|min:10|max:1000',
        ]);
        ModelsContactUs::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        Mail::to($this->email)->send(new ContactUsEmail());
        
        session()->flash('success', 'Thank you for contacting us! We will get back to you as soon as possible.');
        $this->reset();

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
