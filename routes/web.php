<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/admin/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // ✅ Route to permissions view
    Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('permissions.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


use App\Http\Controllers\CompanyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/company', [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('/admin/company', [CompanyController::class, 'store'])->name('company.store');
    Route::put('/admin/company/update/{id}', [CompanyController::class, 'update'])->name('company.update');

});
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::get('export-customers', [\App\Http\Controllers\CustomerController::class, 'export'])->name('customers.export');
});
use App\Http\Controllers\PlanController;

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('plans', PlanController::class);
    Route::get('plans-export', [PlanController::class, 'export'])->name('plans.export');
});

require __DIR__.'/auth.php';
