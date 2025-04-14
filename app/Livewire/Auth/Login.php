<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Login extends Component
{
    public string $email = "";
    public string $password = "";
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            "email" => ["required", "email"],
            "password" => ["required", "min:6"],
        ]);

        // Simulate login validation
        if ($this->email === "user@example.com" && $this->password === "password123") {
            session()->flash("success", "Login successful!");
            return redirect()->route("welcome");
        } else {
            $this->addError("email", "These credentials do not match our records.");
        }
    }

    public function render()
    {
        return view("livewire.auth.login")->layout("components.layouts.auth", [
            "title" => "Login"
        ]);
    }
}
