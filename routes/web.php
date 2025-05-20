<?php

use App\Livewire\Layouts\AppLayout;
use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\News\Newscreate;
use App\Livewire\News\NewsCreate as NewsNewsCreate;
use App\Livewire\News\NewsIndex;
use App\Livewire\Website\AboutUs;
use App\Livewire\Website\ContactUs;
use App\Livewire\Website\DataCenter\Batam;
use App\Livewire\Website\DataCenter\JakartaHq;
use App\Livewire\Website\DataCenter\Singapore;
use App\Livewire\Website\Main;
use App\Livewire\Website\NeutradcSummit;
use App\Livewire\Website\News;
use App\Livewire\Website\NewsDetail;
use App\Livewire\Website\Services;
use App\Livewire\Website\TwoHandsHub;
use App\Livewire\Website\WebsiteIndex;

// Route bawaan login (Livewire)
Route::get('/login', Login::class)->name('login');

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

// Route::middleware(['domainCheck'])->group(function () {
    // Redirect '/' tergantung status login
    Route::get('/', Main::class);
    Route::get('/two-hands-hub', TwoHandsHub::class);
    Route::get('/neutradc-summit', NeutradcSummit::class);
    Route::get('/data-center/jakarta-hq', JakartaHq::class);
    Route::get('/data-center/batam', Batam::class);
    Route::get('/data-center/singapore', Singapore::class);
    Route::get('/services', Services::class);
    Route::get('/about-us', AboutUs::class);
    Route::get('/news', News::class);
    Route::get('/news/{id}', NewsDetail::class);
    Route::get('/contact-us', ContactUs::class);
// });


// Route::get('/', function () {
//     return auth()->check()
//         ? redirect()->route('home')
//         : redirect()->route('login');
// });

// Route yang butuh login & domain valid
Route::prefix('admin')->name('admin.')->middleware(['auth', 'domainCheck'])->group(function () {
    Route::get('/home', Home::class)->name('home');

    Route::get('/news', NewsIndex::class)->name('news.index');
    Route::get('/news/create', NewsCreate::class)->name('news.create');
    Route::get('/user', \App\Livewire\Users\UserIndex::class)->name('user.index');

    Route::get('/role', \App\Livewire\Roles\RoleIndex::class)->name('role.index');
    Route::get('/role/{id}', \App\Livewire\Roles\RoleShow::class)->name('role.show');
});

