<?php

namespace App\Livewire\Public;

use App\Models\Sponsor;
use Livewire\Component;
use Mary\Traits\Toast;

class SponsorRegister extends Component
{
    use Toast;

    public $first_name, $last_name, $email, $company, $phone, $job_position, $country, $industry;

    public function save(){
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'job_position' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'industry' => 'required|string|max:255',
        ]);

        $sponsor = new Sponsor();
        $sponsor->first_name = $this->first_name;
        $sponsor->last_name = $this->last_name;
        $sponsor->company = $this->company;
        $sponsor->job = $this->job_position;
        $sponsor->country = $this->country;
        $sponsor->phone = $this->phone;
        $sponsor->email = $this->email;
        $sponsor->industry = $this->industry;
        $sponsor->save();
    }
    public function render()
    {
        return view('livewire.public.sponsor-register')->layout('components.layouts.website', [
            'title' => 'Register | NeutraDC',
            'description' => 'NeutraDC is a leading data center provider offering colocation, cloud computing, and managed services in Indonesia and Southeast Asia.',
            'keywords' => 'register, neutradc,neutradc summit, data center, colocation, cloud computing, managed services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
