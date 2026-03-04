<?php

use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\TeacherDashbaordController;
use App\Http\Controllers\TeacherPreviousExamCategoryController;
use App\Http\Controllers\TeacherQuestionBuilderController;
use App\Http\Controllers\TeacherQuestionCategoryController;
use App\Http\Controllers\TeacherQuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('t')->group(function () {
    Route::get('/', [TeacherAuthController::class, 'teacherLogin'])->name('teacherLogin');
    Route::post('/login-request', [TeacherAuthController::class, 'teacherLoginRequest'])->name('teacherLoginRequest');


    Route::get('/dashboard', [TeacherDashbaordController::class, 'teacherDashboard'])->name('teacherDashboard');

    Route::prefix('question-categories')->group(function () {
        Route::get('/', [TeacherQuestionCategoryController::class, 'questionCategoryList'])->name('questionCategoryList');
        Route::get('/form/{id?}', [TeacherQuestionCategoryController::class, 'questionCategoryForm'])->name('questionCategoryForm');
        Route::post('/save', [TeacherQuestionCategoryController::class, 'questionCategorySave'])->name('questionCategorySave');
        Route::get('/delete/{id}', [TeacherQuestionCategoryController::class, 'questionCategoryDelete'])->name('questionCategoryDelete');
    });
    Route::prefix('previous-exam-categories')->group(function () {
        Route::get('/', [TeacherPreviousExamCategoryController::class, 'previousExamCategoryList'])->name('previousExamCategoryList');
        Route::get('/form/{id?}', [TeacherPreviousExamCategoryController::class, 'previousExamCategoryForm'])->name('previousExamCategoryForm');
        Route::post('/save', [TeacherPreviousExamCategoryController::class, 'previousExamCategorySave'])->name('previousExamCategorySave');
        Route::get('/delete/{id}', [TeacherPreviousExamCategoryController::class, 'previousExamCategoryDelete'])->name('previousExamCategoryDelete');

        Route::get('/exam-list-form/{categoryId}', [TeacherPreviousExamCategoryController::class, 'previousExamListForm'])->name('previousExamListForm');
    });

    Route::prefix('question')->group(function () {
        Route::get('/', [TeacherQuestionController::class, 'questionList'])->name('questionList');
        Route::get('/form/{id?}', [TeacherQuestionController::class, 'questionForm'])->name('questionForm');
        Route::post('/save/{id?}', [TeacherQuestionController::class, 'questionSave'])->name('questionSave');
        Route::get('/delete/{id}', [TeacherQuestionController::class, 'questionDelete'])->name('questionDelete');
        Route::get('/import-excel', [TeacherQuestionController::class, 'questionImportExcel'])->name('questionImportExcel');
        Route::post('/upload-excel', [TeacherQuestionController::class, 'questionUploadExcel'])->name('questionUploadExcel');
    });

    Route::prefix('question-builder')->group(function () {
        Route::get( '/select', [TeacherQuestionBuilderController::class, 'selectExamQuestion'])->name('selectExamQuestion');
        Route::get('/load-chapters',[TeacherQuestionBuilderController::class, 'loadChapters'])->name('loadChapters');
        Route::any( '/load-questions', [TeacherQuestionBuilderController::class, 'loadQuestions'])->name('loadQuestions');
        Route::post('/toggle-question', [TeacherQuestionBuilderController::class, 'toggleQuestion'])->name('toggleQuestion');
        Route::post('/create-exam', [TeacherQuestionBuilderController::class, 'createExam'])->name('createExam');
    });
});
