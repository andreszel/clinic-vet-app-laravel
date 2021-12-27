<?php

use App\Http\Controllers\Admin\AdditionalServiceController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\MedicalController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\VisitController;
use App\Mail\TempPassChange;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
/* Route::get('send-mail', function () {
    return new TempPassChange();
}); */

// Panel administracyjny
Route::group(['middleware' => ['auth', 'forcechangepass'], 'prefix' => 'admin'], function () {
    Route::get('/', [AdminHomeController::class, 'index'])->name('home');
    Route::get('/test-jquery', [AdminHomeController::class, 'testJquery']);
    Route::get('/test-pdf', [AdminHomeController::class, 'createPDF']);
    Route::post('/testajax', [AdminHomeController::class, 'testAjax'])->name('test.ajax');

    Route::group(['middleware' => 'can:admin-level', 'prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [UserController::class, 'delete'])->name('remove');
        Route::post('/change-status/{id}', [UserController::class, 'changeStatus'])->name('change_status');
    });

    Route::group(['prefix' => 'my-profile', 'as' => 'me.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings.edit');
        Route::post('/settings-update', [ProfileController::class, 'settingsUpdate'])->name('settings.update');
        Route::get('/events', [ProfileController::class, 'events'])->name('events');
    });

    Route::group(['prefix' => 'medicals', 'as' => 'medicals.'], function () {
        Route::get('/', [MedicalController::class, 'index'])->name('list');
        Route::get('/create', [MedicalController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [MedicalController::class, 'edit'])->name('edit');
        Route::post('/store', [MedicalController::class, 'store'])->name('store');
        Route::put('/update/{id}', [MedicalController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [MedicalController::class, 'destroy'])->name('remove');
        Route::post('/change-status/{id}', [MedicalController::class, 'changeStatus'])->name('change_status');
    });

    Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('list');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::post('/store', [CustomerController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CustomerController::class, 'destroy'])->name('remove');
    });

    Route::group(['prefix' => 'additional-services', 'as' => 'additionalservices.'], function () {
        Route::get('/', [AdditionalServiceController::class, 'index'])->name('list');
        Route::get('/create', [AdditionalServiceController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [AdditionalServiceController::class, 'edit'])->name('edit');
        Route::post('/store', [AdditionalServiceController::class, 'store'])->name('store');
        Route::put('/update/{id}', [AdditionalServiceController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [AdditionalServiceController::class, 'destroy'])->name('remove');
        Route::post('/change-status/{id}', [AdditionalServiceController::class, 'changeStatus'])->name('change_status');
        Route::post('/change-status-drive-to-customer/{id}', [AdditionalServiceController::class, 'changeStatusDriveToCustomer'])->name('change_status_drive_to_customer');
    });

    Route::group(['prefix' => 'visits', 'as' => 'visits.'], function () {
        Route::get('/', [VisitController::class, 'index'])->name('list');

        // Create new visit
        Route::post('/store_new_visit/{customerId}', [VisitController::class, 'store_new_visit'])->name('store_new_visit');

        // Step 1 -  - klient, forma płatności, data wizyty
        Route::get('/step1/{id}', [VisitController::class, 'step1'])->name('step1');
        Route::put('/store-step-1/{id}', [VisitController::class, 'store_step1'])->name('store_step1');

        // Step 2 - leki
        Route::get('/step2/{id}', [VisitController::class, 'step2'])->name('step2');
        Route::post('/add_medical/{id}/{medical_id}', [VisitController::class, 'add_medical'])->name('add_medical');

        // Step 3 - usługi dodatkowe
        Route::get('/step3/{id}', [VisitController::class, 'step3'])->name('step3');
        Route::post('/add_additional_service/{id}/{additional_service_id}', [VisitController::class, 'add_additional_service'])->name('add_additional_service');

        // Step 4 - podsumowanie - zatwierdzenie wizyty
        Route::get('/summary/{id}', [VisitController::class, 'summary'])->name('summary');
        Route::put('/store-summary/{id}', [VisitController::class, 'store_summary'])->name('store_summary');

        Route::get('/edit/{id}', [VisitController::class, 'edit'])->name('edit');
        //Route::put('/update/{id}', [VisitController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [VisitController::class, 'destroy'])->name('remove');
    });

    Route::group(['prefix' => 'support', 'as' => 'support.'], function () {
        Route::get('/doctors', [SupportController::class, 'doctors'])->name('doctors');
    });
});

Auth::routes([
    'register' => true, // Registration Routes...
    'reset' => true, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);
