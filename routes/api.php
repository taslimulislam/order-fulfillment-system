<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐19

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\OrderReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('login', [AuthController::class, 'login'])->name('login');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('orders')->group(function () {
            Route::post('/', [OrderController::class, 'store']);
            Route::get('/report', [OrderReportController::class, 'roleBasedOrderReport']);
        });
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

