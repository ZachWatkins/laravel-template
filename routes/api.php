<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\DownloadController;
use Illuminate\Routing\Middleware\ValidateSignature;

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

Route::middleware('auth:sanctum')->get('/api/user/me', function (Request $request) {
    return $request->user();
});

Route::resource('locations', LocationController::class);
Route::post('/api/import', ImportController::class);
Route::get('/api/export', [ExportController::class, 'index']);
Route::get('/api/export/create', [ExportController::class, 'create']);
Route::get('/api/download', DownloadController::class)
    ->name('download')->middleware('signed');
