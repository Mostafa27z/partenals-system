<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\LineController;
use App\Http\Controllers\PlanController;
// use App\Http\Controllers\UserController;
use App\Http\Controllers\LineController;

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
    // Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('permissions.index');
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
// use App\Http\Controllers\PlanController;

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('plans', PlanController::class);
    Route::get('plans-export', [PlanController::class, 'export'])->name('plans.export');
});
use App\Http\Controllers\InvoiceController;
Route::prefix('admin/lines')->middleware('auth')->group(function () {
    Route::get('import', [LineController::class, 'importForm'])->name('lines.import.form');
    Route::post('import', [LineController::class, 'importProcess'])->name('lines.import.process');
});
Route::middleware(['auth'])->group(function () {
// routes/web.php


    Route::get('lines/{line}/pay', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('lines/{line}/pay', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('/lines/{line}', [LineController::class, 'show'])->name('lines.show');

    Route::get('/customers/{customer}/invoices', [InvoiceController::class, 'customerInvoices'])->name('customers.invoices');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

});


Route::middleware(['auth'])->prefix('admin')->group(function () {

    /** ✅ الخطوط المرتبطة بعميل محدد */
    Route::prefix('customers/{customer}/lines')->name('customers.lines.')->group(function () {
        Route::get('/', [LineController::class, 'index'])->name('index');
        Route::get('create', [LineController::class, 'create'])->name('create');
        Route::post('/', [LineController::class, 'store'])->name('store');
        Route::get('{line}/edit', [LineController::class, 'edit'])->name('edit');
        Route::put('{line}', [LineController::class, 'update'])->name('update');
        Route::delete('{line}', [LineController::class, 'destroy'])->name('destroy');
    });

    /** ✅ الخطوط العامة (بدون ربط بعميل) */
   Route::prefix('lines')->name('lines.')->middleware('auth')->group(function () {
    // ثابتة
    Route::get('all', [LineController::class, 'all'])->name('all');
    Route::get('create', [LineController::class, 'createStandalone'])->name('create');
    Route::post('/', [LineController::class, 'storeStandalone'])->name('store');
    Route::get('export', [LineController::class, 'export'])->name('export');

    // ديناميكية
    Route::get('{line}/edit', [LineController::class, 'editStandalone'])->name('edit');
    Route::put('{line}', [LineController::class, 'updateStandalone'])->name('update'); // ✅ هنا
    Route::delete('{line}', [LineController::class, 'destroyStandalone'])->name('destroy');
    Route::get('{line}/invoices', [InvoiceController::class, 'lineInvoices'])->name('invoices');
});
// search
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/ajax/customers/search', [CustomerController::class, 'searchByNationalId'])->name('ajax.customers.search');
});

// web.php
Route::get('/ajax/customers/search', [CustomerController::class, 'searchByNationalId'])->name('ajax.customers.search');


});



require __DIR__.'/auth.php';
