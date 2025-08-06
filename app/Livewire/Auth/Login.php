<?php

namespace App\Livewire\Auth;

use App\Models\Log_login;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public static function middleware()
    {
        return ['guest'];
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $email = trim(strtolower($this->email));
        $password = trim($this->password);

        // if (env('LOGIN_TYPE', 'local') == 'local') {
        // if (strpos($email, '@neutradc.com') > 0) {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            if (Auth::user()->hasRole('Admin')) {
                session()->regenerate();
                Log_login::logUserAttempt('Auth-Login', Carbon::now(), $email, 'OK');
                return redirect()->intended(route('admin.home'));
            } else {
                session()->regenerate();
                return redirect()->intended(route('user.profile'));
            }
        }
        // }
        // if ($email == $password) {
        //     session()->regenerate();
        //     return redirect()->intended(route('home'));
        // }
        // }

        // if ($this->ldapLogin($email, $password)) {
        //     $username = str_replace('@neutradc.com', '', $email);
        //     $user = User::where('ad_name', '=', $username)->first();
        //     Auth::login($user);
        //     session()->regenerate();
        //     Log_login::logUserAttempt('Auth-Login', Carbon::now(), $email, 'OK');
        //     return redirect()->intended(route('admin.home'));
        // }

        Log_login::logUserAttempt('Auth-Login', Carbon::now(), $email, 'Fail');
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function ldapLogin($email, $password)
    {
        // Implementasikan fungsi LDAP kamu di sini
        return false; // placeholder
    }

    public function render()
    {
        return view("livewire.auth.login")
            ->layout("components.layouts.website", [
                "title" => "Login"
            ]);
    }
}
