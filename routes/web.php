<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/standards', [HomeController::class, 'standards'])->name('standards');
Route::get('/offices', [HomeController::class, 'offices'])->name('offices');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Evaluation Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/evaluation/{id}', [EvaluationController::class, 'show'])->name('evaluation.show');
    Route::post('/evaluation/{id}', [EvaluationController::class, 'store'])->name('evaluation.store');
    Route::get('/thank-you', [EvaluationController::class, 'thankYou'])->name('evaluation.thank-you');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        // Evaluations Management
        Route::get('/evaluations', [DashboardController::class, 'evaluations'])->name('evaluations');
        Route::get('/evaluations/{id}', [DashboardController::class, 'showEvaluation'])->name('evaluations.show');
        
        // Users Management
        Route::get('/users', [DashboardController::class, 'users'])->name('users');
        
        // Categories
        Route::get('/categories', [DashboardController::class, 'categories'])->name('categories');
        
        // Messages
        Route::get('/messages', [DashboardController::class, 'messages'])->name('messages');
        Route::get('/messages/{id}', [DashboardController::class, 'showMessage'])->name('messages.show');
        Route::post('/messages/{id}/reply', [DashboardController::class, 'replyMessage'])->name('messages.reply');
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/summary', [ReportController::class, 'summaryReport'])->name('summary');
        Route::get('/reports/export-summary', [ReportController::class, 'exportSummary'])->name('reports.export-summary');
        
        // Chart Data API
        Route::get('/api/chart-data', [DashboardController::class, 'chartData'])->name('api.chart-data');
    });

