<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user/stats', [ApiController::class, 'getUserStats']);
    Route::get('/user/weekly-progress', [ApiController::class, 'getWeeklyProgress']);
    Route::get('/user/character-mastery', [ApiController::class, 'getCharacterMastery']);
    
    // Practice
    Route::get('/practice/history', [ApiController::class, 'getPracticeHistory']);
    Route::post('/practice/save', [ApiController::class, 'savePracticeSession']);
    
    // Maestro
    Route::get('/maestro/references', [ApiController::class, 'getMaestroReferences']);
    
    // Leaderboard
    Route::get('/leaderboard', [ApiController::class, 'getLeaderboard']);
});

// Public routes
Route::get('/ping', function () {
    return response()->json(['status' => 'ok', 'message' => 'CITRA API is running']);
});
