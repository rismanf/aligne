<?php

namespace App\Livewire\Auth;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;

class ForgotPassword extends Component
{
    use Toast;

    public string $email = '';
    public bool $emailSent = false;

    public static function middleware()
    {
        return ['guest'];
    }

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan dalam sistem kami.',
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Email tidak ditemukan dalam sistem kami.');
            return;
        }

        // Check if user is active
        if (!$user->is_active) {
            $this->addError('email', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
            return;
        }

        // Delete any existing password reset tokens for this email
        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        // Generate new token
        $token = Str::random(64);

        // Store the token in database
        DB::table('password_reset_tokens')->insert([
            'email' => $this->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        try {
            // Send email
            Mail::to($this->email)->send(new ForgotPasswordMail($user, $token));

            $this->emailSent = true;
            session()->flash('Link reset password telah dikirim ke email Anda. Periksa inbox atau folder spam.');
        } catch (\Exception $e) {
            Log::error('Gagal kirim email: ' . $e->getMessage());
        }
    }

    public function backToLogin()
    {
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('components.layouts.website', [
                'title' => 'Lupa Password | Aligné'
            ]);
    }
}
