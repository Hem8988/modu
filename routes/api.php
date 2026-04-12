<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormSubmissionController;
use App\Http\Controllers\Api\LeadController;

Route::middleware('api')->prefix('api')->group(function () {
    // Form submission routes
    Route::post('/form-submissions', [FormSubmissionController::class, 'store']);
    Route::get('/form-submissions', [FormSubmissionController::class, 'index']);
    Route::get('/form-submissions/{id}', [FormSubmissionController::class, 'show']);

    // Lead routes
    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/leads/{id}', [LeadController::class, 'show']);
    Route::put('/leads/{id}', [LeadController::class, 'update']);
});
