<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\InstitutionSettingController;
use App\Http\Controllers\Admin\AdminUserController;
Route::get('/', function () {
    return view('home');
});


Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.submit');
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
    Route::get('/settings', [InstitutionSettingController::class, 'edit'])
    ->name('settings.edit');
    Route::put('/settings', [InstitutionSettingController::class, 'update'])
    ->name('settings.update');
    Route::get('/administrators', [AdminUserController::class, 'index'])
    ->name('admin-users.index');
    Route::get('/administrators/create', [AdminUserController::class, 'create'])
    ->name('admin-users.create');
    Route::post('/administrators', [AdminUserController::class, 'store'])
    ->name('admin-users.store');
});
