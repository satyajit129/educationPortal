<?php

use App\Http\Controllers\StudentExamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/s')->group(function(){
    Route::get('/{code}',[StudentExamController::class,'studentExam'])->name('studentExam');
    Route::post('/exam-submit',[StudentExamController::class,'studentExamSubmit'])->name('studentExamSubmit');
    // Route::get('/view-exam-result');
});
