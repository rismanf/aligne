<?php

use App\Livewire\Admin\Contact\ListContact;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Invoice\ListInvoice;
use App\Livewire\Admin\Mail\ListPicMail;
use App\Livewire\Admin\Menu\ListMenu;
use App\Livewire\Admin\News\AddNews;
use App\Livewire\Admin\News\EditNews;
use App\Livewire\Admin\News\ListNews;
use App\Livewire\Admin\News\NewsShow;
use App\Livewire\Admin\Participant\AddParticipant;
use App\Livewire\Admin\Participant\ListParticipant;
use App\Livewire\Admin\Participant\PaymentParticipant;
use App\Livewire\Admin\Question\ListQuestion;
use App\Livewire\Admin\Role\ListRole;
use App\Livewire\Admin\Role\RoleShow;
use App\Livewire\Admin\Sponsor\ListSponsor;
use App\Livewire\Admin\User\ListUser;
use App\Livewire\Admin\Vcard\AddVcard;
use App\Livewire\Admin\Vcard\EditVcard;
use App\Livewire\Admin\Vcard\ListVcard;
use App\Livewire\Layouts\AppLayout;
use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Login;
use App\Livewire\Public\AboutUs;
use App\Livewire\Public\ContactUs;
use App\Livewire\Public\DataCenter\Batam;
use App\Livewire\Public\DataCenter\Jakarta;
use App\Livewire\Public\DataCenter\Singapore;
use App\Livewire\Public\Home;
use App\Livewire\Public\NeutradcSummit;
use App\Livewire\Public\News;
use App\Livewire\Public\NewsDetail;
use App\Livewire\Public\Register;
use App\Livewire\Public\Service;
use App\Livewire\Public\SponsorRegister;
use App\Livewire\Public\TwoHandHub;
use App\Livewire\Public\Vcard;
use App\Livewire\Public\Whistleblower;
use App\Models\User;

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
Route::get('/', Home::class)->name('/');
Route::get('/neutradc-summit', NeutradcSummit::class)->name('neutradc-summit');
// Route::get('/data-center', DataCenterHome::class);
Route::get('/data-center/jakarta-hq', Jakarta::class)->name('data-center.jakarta-hq');
Route::get('/data-center/batam', Batam::class)->name('data-center.batam');
Route::get('/data-center/singapore', Singapore::class)->name('data-center.singapore');
Route::get('/services', Service::class)->name('services');
Route::get('/about-us', AboutUs::class)->name('about-us');
Route::get('/news', News::class)->name('news');
Route::get('/news/{id}/{slug}', NewsDetail::class)->name('news.detail');
Route::get('/contact-us', ContactUs::class)->name('contact-us');
Route::get('/two-hands-hub', TwoHandHub::class)->name('two-hands-hub');
Route::get('/register', Register::class)->name('register');
Route::get('/sponsor-register', SponsorRegister::class)->name('sponsor-register');
Route::get('/whistleblower', Whistleblower::class)->name('whistleblower');
Route::get('/vcard', Vcard::class)->name('vcard');

// });


// Route::get('/', function () {
//     return auth()->check()
//         ? redirect()->route('home')
//         : redirect()->route('login');
// });

// Route yang butuh login & domain valid

Route::prefix('user')->name('user.')->middleware(['auth', 'domainCheck'])->group(function () {
    Route::get('/dashboard', App\Livewire\User\Home::class)->name('home');

    Route::get('/participant', App\Livewire\User\Participant\AddParticipant::class)->name('participant.index');
    Route::get('/participant/create', AddParticipant::class)->name('participant.create');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'domainCheck'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('home');

    Route::get('/news', ListNews::class)->name('news.index');
    Route::get('/news/create', AddNews::class)->name('news.create');
    Route::get('/news/{id}', NewsShow::class)->name('news.show');
    Route::get('/news/{id}/edit', EditNews::class)->name('news.edit');

    Route::get('/vcard', ListVcard::class)->name('vcard.index');
    Route::get('/vcard/create', AddVcard::class)->name('vcard.create');
    Route::get('/vcard/{id}/edit', EditVcard::class)->name('vcard.edit');

    Route::get('/menu', ListMenu::class)->name('menu.index');

    Route::get('/sponsor', ListSponsor::class)->name('sponsor.index');

    Route::get('/email', ListPicMail::class)->name('email.index');


    Route::get('/participant', ListParticipant::class)->name('participant.index');
    Route::get('/participant/create', AddParticipant::class)->name('participant.create');
    Route::get('/participant/{id}/edit', App\Livewire\Admin\Participant\EditParticipant::class)->name('participant.edit');
    Route::get('/participant/payment', PaymentParticipant::class)->name('participant.payment');

    Route::get('/contact', ListContact::class)->name('contact.index');
    Route::get('/invoice', ListInvoice::class)->name('invoice.index');
    Route::get('/question', ListQuestion::class)->name('question.index');
    Route::get('/user', ListUser::class)->name('user.index');

    Route::get('/role', ListRole::class)->name('role.index');
    Route::get('/role/{id}', RoleShow::class)->name('role.show');
});
