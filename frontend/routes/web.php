<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SettingsController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Auth routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Practice
    Route::get('/practice', [PracticeController::class, 'index'])->name('practice');
    Route::post('/practice/start', [PracticeController::class, 'start'])->name('practice.start');
    Route::post('/practice/end', [PracticeController::class, 'end'])->name('practice.end');
    
    // Tutorial
    Route::get('/tutorial', [TutorialController::class, 'index'])->name('tutorial');
    Route::get('/tutorial/{karakter}/{gerakan}', [TutorialController::class, 'show'])->name('tutorial.show');
    
    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    
    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset-progress', [SettingsController::class, 'resetProgress'])->name('settings.reset');
    Route::delete('/settings/delete-account', [SettingsController::class, 'deleteAccount'])->name('settings.delete');
    
    // Upload
    Route::post('/upload-maestro', [UploadController::class, 'upload'])->name('upload.maestro');
});

// API routes for AJAX
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/user/stats', [DashboardController::class, 'getStats']);
    Route::get('/leaderboard/data', [LeaderboardController::class, 'getData']);
    Route::get('/practice/history', [HistoryController::class, 'getHistory']);
});