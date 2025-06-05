<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public $firstname, $lastname, $company, $job, $country, $phone, $email, $password, $password_confirmation;
    public function save()
    {
        $this->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        session()->flash('success', 'User berhasil ditambahkan.');

        // if ($this->password != $this->retype_password) {
        //     $this->toast(
        //         type: 'error',
        //         title: 'Password Not Match',
        //         description: null,                  // optional (text)
        //         position: 'toast-top toast-end',    // optional (daisyUI classes)
        //         icon: 'o-information-circle',       // Optional (any icon)
        //         css: 'alert-info',                  // Optional (daisyUI classes)
        //         timeout: 3000,                      // optional (ms)
        //         redirectTo: null                    // optional (uri)
        //     );
        //     return;
        // }

        $user = User::create([
            'name' => $this->firstname . ' ' . $this->lastname,
            'first_name' => $this->firstname,
            'last_name' => $this->lastname,
            'company' => $this->company,
            'title' => $this->job,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => bcrypt($this->password), // default password atau form password sendiri
        ]);

        $user->assignRole('User'); // Assign role 'user' to the new user
        // // Jika ingin mengirim email verifikasi atau notifikasi lainnya, bisa ditambahkan di sini
        // // Contoh: Mail::to($user->email)->send(new UserRegistered($user));
        // // Jika ingin menampilkan pesan sukses, bisa menggunakan session flash atau toast

        // // session()->flash('success', 'User berhasil ditambahkan.');
        // $this->resetForm();
        // $this->toast(
        //     type: 'success',
        //     title: 'Data Saved',
        //     description: null,                  // optional (text)
        //     position: 'toast-top toast-end',    // optional (daisyUI classes)
        //     icon: 'o-information-circle',       // Optional (any icon)
        //     css: 'alert-info',                  // Optional (daisyUI classes)
        //     timeout: 3000,                      // optional (ms)
        //     redirectTo: route('user.index')                    // optional (uri)
        // );
        // // Setelah pendaftaran berhasil, bisa diarahkan ke halaman login atau halaman lain
        // return redirect()->route('login');

        Auth::login($user);
        session()->regenerate();
        return redirect()->intended(route('admin.home'));

        // Implementasikan logika pendaftaran di sini
        // Misalnya, menyimpan data pengguna baru ke database
    }
    public function render()
    {
        return view('livewire.auth.register')->layout('components.layouts.website', [
            'title' => 'Register | NeutraDC',
            'description' => 'NeutraDC is a leading data center provider offering colocation, cloud computing, and managed services in Indonesia and Southeast Asia.',
            'keywords' => 'register, neutradc,neutradc summit, data center, colocation, cloud computing, managed services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
