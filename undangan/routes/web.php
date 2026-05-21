<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvitationController;

Route::get('/', [InvitationController::class, 'index'])->name('home');
Route::get('/undangan', [InvitationController::class, 'undangan'])->name('undangan');
Route::post('/rsvp', [InvitationController::class, 'submitRsvp'])->name('rsvp');

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([\App\Http\Middleware\CheckAdminAuth::class])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/api/stats', [DashboardController::class, 'stats']);
    Route::get('/api/list', [DashboardController::class, 'listGuests']);
    Route::post('/api/delete', [DashboardController::class, 'deleteGuest']);
    Route::post('/api/add_guest', [DashboardController::class, 'addGuest']);
    Route::post('/api/edit_guest', [DashboardController::class, 'editGuest']);
    Route::get('/api/get_settings', [DashboardController::class, 'getSettings']);
    Route::post('/api/save_settings', [DashboardController::class, 'saveSettings']);
    Route::post('/api/upload_photo', [DashboardController::class, 'uploadPhoto']);
    Route::get('/api/get_gallery', [DashboardController::class, 'getGallery']);
    Route::post('/api/upload_gallery', [DashboardController::class, 'uploadGallery']);
    Route::post('/api/delete_gallery', [DashboardController::class, 'deleteGallery']);
});
