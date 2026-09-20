<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Survey\SurveyController;
use Illuminate\Support\Facades\Route;

// 1. Modul Kuesioner Responden Anonim
Route::get('/', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey', [SurveyController::class, 'index']);
Route::post('/survey/submit', [SurveyController::class, 'submit'])->name('survey.submit');
Route::get('/survey/finish', [SurveyController::class, 'finish'])->name('survey.finish');

// 2. Modul Autentikasi & Panel Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard Analitik & Export CSV
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

        // CRUD Periode Survei
        Route::resource('periods', PeriodController::class)->except(['create', 'edit', 'show']);
        Route::post('/periods/{period}/activate', [PeriodController::class, 'activate'])->name('periods.activate');

        // CRUD Master Kategori
        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

        // CRUD Master Pertanyaan (Full Control)
        Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
        Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::patch('/questions/{question}/toggle', [QuestionController::class, 'toggle'])->name('questions.toggle');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    });
});
