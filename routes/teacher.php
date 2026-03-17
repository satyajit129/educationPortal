<?php

use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\TeacherDashbaordController;
use App\Http\Controllers\TeacherPreviousExamCategoryController;
use App\Http\Controllers\TeacherQuestionBuilderController;
use App\Http\Controllers\TeacherQuestionCategoryController;
use App\Http\Controllers\TeacherQuestionController;
use App\Http\Controllers\YearController;
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
        Route::get('/exam-list-form/{categoryId}/{examId?}', [TeacherPreviousExamCategoryController::class, 'previousExamListForm'])->name('previousExamListForm');
        Route::post('/exam-list-save', [TeacherPreviousExamCategoryController::class, 'previousExamListSave'])->name('previousExamListSave');
        Route::get('/exam-delete/{examId}', [TeacherPreviousExamCategoryController::class, 'previousExamListDelete'])->name('previousExamListDelete');
        Route::get('/add-question-form/{categoryId}/{examId}', [TeacherPreviousExamCategoryController::class, 'previousExamAddQuestionForm'])->name('previousExamAddQuestionForm');
        Route::post('/save-exam-questions/{examId}', [TeacherPreviousExamCategoryController::class, 'savePreviousExamQuestions'])->name('savePreviousExamQuestions');
        Route::get('/view-questions/{examId}', [TeacherPreviousExamCategoryController::class, 'viewPreviousExamQuestions'])->name('viewPreviousExamQuestions');
        Route::get('/exam-list-ajax', [TeacherPreviousExamCategoryController::class, 'loadPreviousExams'])->name('loadPreviousExams');
    });

    Route::prefix('year')->group(function () {
        Route::get('/', [YearController::class, 'yearList'])->name('yearList');
        Route::get('/form/{id?}', [YearController::class, 'yearForm'])->name('yearForm');
        Route::post('/save', [YearController::class, 'yearSave'])->name('yearSave');
        Route::get('/delete/{id}', [YearController::class, 'yearDelete'])->name('yearDelete');
        Route::get('/add-question-form/{id?}', [YearController::class, 'yearAddQuestionForm'])->name('yearAddQuestionForm');
        Route::post('/save-year-questions/{id}', [YearController::class, 'saveYearQuestions'])->name('saveYearQuestions');
        Route::get('/view-questions/{id}', [YearController::class, 'viewYearQuestions'])->name('viewYearQuestions');
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
        Route::get('/',[TeacherQuestionBuilderController::class, 'questionBuilderIndex'])->name('questionBuilderIndex');
        Route::get('/exam-form/{id?}', [TeacherQuestionBuilderController::class, 'questionBuilderExamForm'])->name('questionBuilderExamForm');
        Route::post('/exam-save/{id?}', [TeacherQuestionBuilderController::class, 'questionBuilderExamSave'])->name('questionBuilderExamSave');
        
        Route::any('/select-questions/{id}', [TeacherQuestionBuilderController::class, 'selectExamQuestion'])->name('selectExamQuestion');
        Route::get('/load-chapters',[TeacherQuestionBuilderController::class, 'loadChapters'])->name('loadChapters');

        
        Route::post('/toggle-question', [TeacherQuestionBuilderController::class, 'toggleQuestion'])->name('toggleQuestion');
        Route::post('/save-questions/{id}', [TeacherQuestionBuilderController::class, 'questionBuilderQuestionSave'])->name('questionBuilderQuestionSave');
        
    });
});
