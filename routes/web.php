<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PrivilageController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StatusBookingController;
use App\Http\Controllers\Backend\LantaiController;
use App\Http\Controllers\Backend\MejaController;

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [Backend::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [Backend::class, 'profile'])->name('profile.edit');
});

Route::get('/', [Backend::class, 'signin'])->name('signin');
Route::get('/get-role-name', [PrivilageController::class, 'getRoleName'])->name('get.role.name');

Route::get('/company-profile', [Backend::class, 'editCompany'])->name('companyProfile');
Route::put('/company-profile/update', [Backend::class, 'updateCompany'])->name('companyProfile.update');

Route::get('/meja/check-nama-meja', [MejaController::class, 'checkNamaMeja'])->name('meja.checkNamaMeja');

Route::middleware(['auth'])->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('user', UserController::class); //user
    Route::get('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::resource('privilage', PrivilageController::class); //privilage
    Route::resource('role', RoleController::class); //role
    Route::resource('statusbooking', StatusBookingController::class); //statusbooking
    Route::resource('lantai', LantaiController::class); //lantai
    Route::resource('meja', MejaController::class); //meja
});

require __DIR__.'/auth.php';
