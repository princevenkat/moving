<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\VendorController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InquiryController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/vendor', function (Request $request) {
    return $request->vendor();
});

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('dashboard');

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');


Route::post('stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['verified'])->group(function () {
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/failure', [StripeController::class, 'failure'])->name('stripe.failure');

    Route::post('/become-a-vendor', [VendorController::class, 'store'])->name('vendor.store');

    Route::post('/stripe/connect', [StripeController::class, 'connect'])->name('stripe.connect')
    ->middleware(['role:' . \App\Enums\RolesEnum::Vendor->value]);
});

require __DIR__.'/auth.php';



Route::get('/terms', function () {
    return Inertia::render('Terms');
})->name('terms');


Route::get('/about', function () {
    return Inertia::render('About');
});



Route::get('/inquiry/start', [InquiryController::class, 'create'])->name('inquiry.start');
Route::post('/inquiry/store', [InquiryController::class, 'store'])->name('inquiry.store');


//Route::get('/inquiry/{id}/current-home', [InquiryController::class, 'currentHome'])->name('inquiry.current_home');
//Route::post('/inquiry/{id}/current-home/update', [InquiryController::class, 'updateCurrentHome'])->name('inquiry.update_current_home');
//Route::get('/inquiry/{id}/new-home', [InquiryController::class, 'editNewHome'])->name('inquiry.edit_new_home');
//Route::post('/inquiry/{id}/new-home', [InquiryController::class, 'updateNewHome'])->name('inquiry.update_new_home');





Route::get('/inquiry/{inquiry}/step2', [InquiryController::class, 'step2'])->name('inquiry.step2');
Route::post('/inquiry/{inquiry}/step2', [InquiryController::class, 'step2Store'])->name('inquiry.step2.store');

Route::get('/inquiry/{inquiry}/step3', [InquiryController::class, 'step3'])->name('inquiry.step3');
Route::post('/inquiry/{inquiry}/step3', [InquiryController::class, 'step3Store'])->name('inquiry.step3.store');





