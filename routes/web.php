<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chatbot', [ChatBotController::class, 'show'])->name('chatbot.show');
Route::post('/chatbot/message', [ChatBotController::class, 'message'])->name('chatbot.message');
Route::get('/chatbot/pdf/{quote}', [ChatBotController::class, 'downloadPdf'])->name('chatbot.pdf');
Route::get('/chatbot/pdf/{quote}/embedded', [ChatBotController::class, 'embeddedPdf'])->name('chatbot.pdf.embedded');

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin dashboard route
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');

// Customer dashboard route
Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'customer'])
    ->name('customer.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/customer.php';
