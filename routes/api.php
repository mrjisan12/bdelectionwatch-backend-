<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\VerifyApiToken;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/create-report',[ReportController::class, 'createReport']) ->middleware([VerifyApiToken::class, 'throttle:5,10']);
Route::get('/get-reports',[ReportController::class, 'getReports']);
