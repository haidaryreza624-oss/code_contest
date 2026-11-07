<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\InstitutionSettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\ProblemController;
use App\Http\Controllers\Admin\ProblemTestCaseController;

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
    ->middleware('can:manage-institution-settings')
    ->name('settings.update');


    Route::get('/administrators', [AdminUserController::class, 'index'])
    ->name('admin-users.index');

    Route::get('/administrators/create', [AdminUserController::class, 'create'])
    ->middleware('can:manage-administrators')
    ->name('admin-users.create');

    Route::get('/administrators/edit/{id}', [AdminUserController::class, 'showUpdate'])
    ->middleware('can:manage-administrators')
    ->name('admin-users.showUpdate');

    Route::put('/administrators/edit', [AdminUserController::class, 'update'])
    ->middleware('can:manage-administrators')
    ->name('admin-users.update');



    Route::post('/administrators', [AdminUserController::class, 'store'])
    ->middleware('can:manage-administrators')
    ->name('admin-users.store');
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])
    ->name('audit-logs.index');



    Route::get('/problems', [ProblemController::class, 'index'])
    ->name('problems.index');
    Route::get('/problems/create', [ProblemController::class, 'create'])
        ->name('problems.create');
    Route::post('/problems', [ProblemController::class, 'store'])
        ->name('problems.store');


    Route::get('/problems/{problem}/test-cases',
        [ProblemTestCaseController::class, 'index']
    )->name('problems.test-cases.index');

    Route::post('/problems/{problem}/test-cases',
        [ProblemTestCaseController::class, 'store']
    )->name('problems.test-cases.store');

    Route::put('/problems/{problem}/test-cases/{testCase}',
        [ProblemTestCaseController::class, 'update']
    )->name('problems.test-cases.update');

    Route::delete('/problems/{problem}/test-cases/{testCase}',
        [ProblemTestCaseController::class, 'destroy']
    )->name('problems.test-cases.destroy');

});
