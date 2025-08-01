<?php

use App\Livewire\Admin\Class\ClassList;
use App\Livewire\Admin\Class\GroupClassList;
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
use App\Livewire\Admin\Product\ProductList;
use App\Livewire\Admin\Product\ProductCategory;
use App\Livewire\Admin\Product\CategoryManager;
use App\Livewire\Admin\Question\ListQuestion;
use App\Livewire\Admin\Role\ListRole;
use App\Livewire\Admin\Role\RoleShow;
use App\Livewire\Admin\Schedule\ScheduleList;
use App\Livewire\Admin\Sponsor\ListSponsor;
use App\Livewire\Admin\Trainer\TrainerList;
use App\Livewire\Admin\Transaction\TransactionList;
use App\Livewire\Admin\User\ListUser;
use App\Livewire\Admin\Vcard\AddVcard;
use App\Livewire\Admin\Vcard\EditVcard;
use App\Livewire\Admin\Vcard\ListVcard;
use App\Livewire\Admin\Visit;
use App\Livewire\Layouts\AppLayout;
use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Login;
use App\Livewire\Public\AboutUs;
use App\Livewire\Public\Checkout;
use App\Livewire\Public\CheckoutClass;
use App\Livewire\Public\Classes;
use App\Livewire\Public\ContactUs;
use App\Livewire\Public\DetailClass;
use App\Livewire\Public\Home;
use App\Livewire\Public\Invoice;
use App\Livewire\Public\Membership;
use App\Livewire\Public\NeutradcSummit;
use App\Livewire\Public\News;
use App\Livewire\Public\NewsDetail;
use App\Livewire\Public\Register;
use App\Livewire\Public\Service;
use App\Livewire\Public\SponsorRegister;
use App\Livewire\Public\TwoHandHub;
use App\Livewire\Public\Vcard;
use App\Livewire\Public\Whistleblower;
use App\Models\GroupClass;
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
Route::get('/about-us', AboutUs::class)->name('about-us');
Route::get('/classes', Classes::class)->name('classes');
Route::get('/classes-detail/{id}/{date}', DetailClass::class)->name('detail-class');
Route::get('/class-schedules', App\Livewire\Public\ClassSchedules::class)->name('class-schedules');
Route::get('/checkout_class/{id}', CheckoutClass::class)->name('checkout_class');
Route::get('/book-class/{id}', App\Livewire\Public\CheckoutClassEnhanced::class)->name('book-class');
Route::get('/membership', Membership::class)->name('membership');
Route::get('/checkout/{id}', Checkout::class)->name('checkout');
Route::get('/invoice/{id}', Invoice::class)->name('invoice');
Route::get('/contact-us', ContactUs::class)->name('contact-us');
Route::get('/news', News::class)->name('news');
Route::get('/news/{id}/{slug}', NewsDetail::class)->name('news.detail');
Route::get('/register', Register::class)->name('register');
// Route::get('/vcard', Vcard::class)->name('vcard');

// });
Route::get('/storage-link', function () {
    $targetForder = storage_path('app/public');
    $linkPublic = $_SERVER['DOCUMENT_ROOT'] . '/storage';
    symlink($targetForder, $linkPublic);
});


// Route::get('/', function () {
//     return auth()->check()
//         ? redirect()->route('home')
//         : redirect()->route('login');
// });

// Route yang butuh login & domain valid

Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/profile', App\Livewire\User\Profile::class)->name('profile');
    Route::get('/order', App\Livewire\User\Order::class)->name('order');
    Route::get('/booking', App\Livewire\User\Booking::class)->name('booking');
    Route::get('/my-bookings/{bookingId?}', App\Livewire\User\BookingInvoice::class)->name('my-bookings');
    Route::get('/dashboard', App\Livewire\User\Dashboard::class)->name('dashboard');

    Route::get('/participant', App\Livewire\User\Participant\AddParticipant::class)->name('participant.index');
    // Route::get('/participant/create', AddParticipant::class)->name('participant.create');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('home');

    Route::get('/news', ListNews::class)->name('news.index');
    Route::get('/news/create', AddNews::class)->name('news.create');
    Route::get('/news/{id}', NewsShow::class)->name('news.show');
    Route::get('/news/{id}/edit', EditNews::class)->name('news.edit');

    Route::get('/groupclass', GroupClassList::class)->name('groupclass.index');
    Route::get('/class', ClassList::class)->name('class.index');
    Route::get('/trainer', TrainerList::class)->name('trainer.index');
    Route::get('/transaction', TransactionList::class)->name('transaction.index');
    Route::get('/product', ProductList::class)->name('product.index');
    Route::get('/categoriesproduct', ProductCategory::class)->name('categoriesproduct.index');
    Route::get('/schedule', ScheduleList::class)->name('schedule.index');
    Route::get('/qr-scanner', App\Livewire\Admin\QRScanner::class)->name('qr-scanner');


    // Route::get('/vcard', ListVcard::class)->name('vcard.index');
    // Route::get('/vcard/create', AddVcard::class)->name('vcard.create');
    // Route::get('/vcard/{id}/edit', EditVcard::class)->name('vcard.edit');

    Route::get('/menu', ListMenu::class)->name('menu.index');


    Route::get('/email', ListPicMail::class)->name('email.index');


    Route::get('/contact', ListContact::class)->name('contact.index');
    Route::get('/user', ListUser::class)->name('user.index');

    Route::get('/role', ListRole::class)->name('role.index');
    Route::get('/role/{id}', RoleShow::class)->name('role.show');
    Route::get('/visit', Visit::class)->name('visit.index');
});
