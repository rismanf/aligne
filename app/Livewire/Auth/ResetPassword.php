<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Mary\Traits\Toast;

class ResetPassword extends Component
{
    use Toast;

    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $tokenValid = false;
    public bool $passwordReset = false;

    public static function middleware()
    {
        return ['guest'];
    }

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->get('email', '');
        
        // Validate token and check if it's not expired (30 minutes)
        $this->validateToken();
    }

    public function validateToken()
    {
        if (empty($this->email)) {
            $this->tokenValid = false;
            return;
        }

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->first();

        if (!$tokenRecord) {
            $this->tokenValid = false;
            return;
        }

        // Check if token is expired (30 minutes)
        $tokenAge = now()->diffInMinutes($tokenRecord->created_at);
        if ($tokenAge > 30) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('email', $this->email)->delete();
            $this->tokenValid = false;
            return;
        }

        // Verify token
        if (Hash::check($this->token, $tokenRecord->token)) {
            $this->tokenValid = true;
        } else {
            $this->tokenValid = false;
        }
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Validate token again before resetting
        $this->validateToken();

        if (!$this->tokenValid) {
            session()->flash('error', 'Token tidak valid. Silakan minta link reset password yang baru.');
          
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            session()->flash('error', 'Email tidak ditemukan dalam sistem.');
          
            return;
        }

        try {
            // Update user password
            $user->update([
                'password' => Hash::make($this->password),
            ]);

            // Delete the used token
            DB::table('password_reset_tokens')->where('email', $this->email)->delete();

            $this->passwordReset = true;

            session()->flash('Password Anda telah berhasil diubah. Silakan login dengan password baru.');
         

        } catch (\Exception $e) {
           Log::error('Error resetting password: ' . $e->getMessage()); // Log the error.error($e)
        }
    }

    public function goToLogin()
    {
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.reset-password')
            ->layout('components.layouts.website', [
                'title' => 'Reset Password | Aligné'
            ]);
    }
}
