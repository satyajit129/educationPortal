<?php

use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentDashbaordController;
use App\Http\Controllers\StudentExamController;
use App\Http\Middleware\IsStudent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/s')->group(function () {

    Route::get('/', [StudentAuthController::class, 'studentLogin'])->name('studentLogin');
    Route::post('/login-request', [StudentAuthController::class, 'studentLoginRequest'])->name('studentLoginRequest');

    Route::middleware([IsStudent::class])->group(function () {
        Route::get('/dashboard', [StudentDashbaordController::class, 'studentDashboard'])->name('studentDashboard');
        Route::get('/exam-question/{id}',[StudentExamController::class, 'studentExamQuestion'])->name('studentExamQuestion');
    });

    // ⚠️ THIS MUST BE AT THE BOTTOM
    Route::get('/{code}', [StudentExamController::class, 'studentExam'])->name('studentExam');
    Route::post('/exam-submit', [StudentExamController::class, 'studentExamSubmit'])->name('studentExamSubmit');

});
