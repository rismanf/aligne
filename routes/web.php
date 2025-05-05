<?php

use App\Livewire\Layouts\AppLayout;
use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Auth\Login;

// Route bawaan login (Livewire)
Route::get('/login', Login::class)->name('login');

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
 
    return redirect('/');
})->name('logout');

// Redirect '/' tergantung status login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

// Route yang butuh login & domain valid
Route::middleware(['auth', 'domainCheck'])->group(function () {
    Route::get('/home', Home::class)->name('home');


    Route::get('/user', \App\Livewire\Users\UserIndex::class)->name('user.index');

    Route::get('/role', \App\Livewire\Roles\RoleIndex::class)->name('role.index');
    Route::get('/role/{id}', \App\Livewire\Roles\RoleShow::class)->name('role.show');

});