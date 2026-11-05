<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\InstitutionSettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAuditLogController;
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
    ->middleware('can:manage-institution-settings')
    ->name('settings.edit');
    Route::put('/settings', [InstitutionSettingController::class, 'update'])
    ->middleware('can:manage-institution-settings')
    ->name('settings.update');
    Route::get('/administrators', [AdminUserController::class, 'index'])
    ->middleware('can:manage-administrators')
    ->name('admin-users.index');
    Route::get('/administrators/create', [AdminUserController::class, 'create'])
    ->middleware('can:manage-administrators')
    ->name('admin-users.create');
    Route::post('/administrators', [AdminUserController::class, 'store'])
    ->middleware('can:manage-administrators')
    ->name('admin-users.store');
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])
    ->middleware('can:manage-administrators')
    ->name('audit-logs.index');
});
