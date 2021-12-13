<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupportController;
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

    Route::group(['prefix' => 'doctors', 'as' => 'doctors.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [UserController::class, 'delete'])->name('remove');
    });

    Route::group(['prefix' => 'my-profile', 'as' => 'me.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings.edit');
        Route::post('/settings-update', [ProfileController::class, 'settingsUpdate'])->name('settings.update');
        Route::get('/events', [ProfileController::class, 'events'])->name('events');
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
