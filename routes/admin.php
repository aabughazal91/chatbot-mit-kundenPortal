<?php

use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\PriceModuleController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Inquiry routes
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::post('/inquiries/{inquiry}/link-user', [InquiryController::class, 'linkUser'])->name('inquiries.linkUser');
    Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.updateStatus');
    Route::patch('/inquiries/{inquiry}/clickup', [InquiryController::class, 'updateClickUp'])->name('inquiries.updateClickUp');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Price Module routes
    Route::get('/price-modules', [PriceModuleController::class, 'index'])->name('price-modules.index');
    Route::get('/price-modules/create', [PriceModuleController::class, 'create'])->name('price-modules.create');
    Route::post('/price-modules', [PriceModuleController::class, 'store'])->name('price-modules.store');
    Route::get('/price-modules/{priceModule}/edit', [PriceModuleController::class, 'edit'])->name('price-modules.edit');
    Route::put('/price-modules/{priceModule}', [PriceModuleController::class, 'update'])->name('price-modules.update');
    Route::delete('/price-modules/{priceModule}', [PriceModuleController::class, 'destroy'])->name('price-modules.destroy');
});
