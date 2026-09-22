<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DemographicController;
use App\Http\Controllers\Admin\MethodologyController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ResponseController;
use App\Http\Controllers\Admin\SentimentDashboardController;
use App\Http\Controllers\Survey\SurveyController;
use Illuminate\Support\Facades\Route;

// 1. Modul Kuesioner Responden Anonim
Route::get('/', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey', [SurveyController::class, 'index']);
Route::post('/survey/submit', [SurveyController::class, 'submit'])
    ->middleware('throttle:6,1')
    ->name('survey.submit');
Route::get('/survey/finish', [SurveyController::class, 'finish'])->name('survey.finish');

// 2. Modul Autentikasi Admin
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.submit');

// Redirect /admin/login ke /login jika diakses
Route::redirect('/admin/login', '/login');

// 3. Panel Admin (Protected)
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard Analitik & Export Excel
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

        // Dashboard Analisis Sentimen (Clean Architecture)
        Route::get('/sentiment', [SentimentDashboardController::class, 'index'])->name('sentiment.index');
        Route::get('/sentiment/export', [SentimentDashboardController::class, 'export'])->name('sentiment.export');

        // Panduan Metodologi & Rumus Perhitungan
        Route::get('/methodology', [MethodologyController::class, 'index'])->name('methodology.index');

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

        // Data Respon Survei Responden
        Route::get('/responses', [ResponseController::class, 'index'])->name('responses.index');
        Route::get('/responses/{response}', [ResponseController::class, 'show'])->name('responses.show');
        Route::delete('/responses/{response}', [ResponseController::class, 'destroy'])->name('responses.destroy');

        // CRUD Master Demografi (Unit Kerja & Profesi)
        Route::resource('demographics', DemographicController::class)->except(['create', 'edit', 'show']);
        Route::patch('/demographics/{demographic}/toggle', [DemographicController::class, 'toggle'])->name('demographics.toggle');

        // Pengaturan Akun & Kata Sandi Admin
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
