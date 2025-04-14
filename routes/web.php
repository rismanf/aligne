<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Welcome;
use App\Livewire\Home;
use App\Livewire\Auth\Login;

Route::get("/welcome", Welcome::class)->name("welcome");

Route::get("/", Home::class)->name("home");

Route::get("/login", Login::class)->name("login");
