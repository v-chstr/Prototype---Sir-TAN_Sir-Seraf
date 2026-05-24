<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check() ? redirect()->route('home') : redirect()->route('login');
});

// Authenticated landing pages
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/standards', [HomeController::class, 'standards'])->name('standards');
    Route::get('/offices', [HomeController::class, 'offices'])->name('offices');
});

// Public contact page
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,10');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Evaluation Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/evaluation/{id}', [EvaluationController::class, 'show'])->name('evaluation.show');
    Route::post('/evaluation/{id}', [EvaluationController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('evaluation.store');
    Route::get('/thank-you', [EvaluationController::class, 'thankYou'])->name('evaluation.thank-you');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class, 'no-cache'])
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
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::patch('/categories/{id}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Messages
        Route::get('/messages', [DashboardController::class, 'messages'])->name('messages');
        Route::get('/messages/{id}', [DashboardController::class, 'showMessage'])->name('messages.show');
        Route::post('/messages/{id}/reply', [DashboardController::class, 'replyMessage'])->name('messages.reply');
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/summary', [ReportController::class, 'summaryReport'])->name('summary');
        Route::get('/reports/export-summary', [ReportController::class, 'exportSummary'])->name('reports.export-summary');
        Route::get('/reports/export-summary-pdf', [ReportController::class, 'exportSummaryPdf'])->name('reports.export-summary-pdf');
        
        // Chart Data API
        Route::get('/api/chart-data', [DashboardController::class, 'chartData'])->name('api.chart-data');

        // Settings - Academic Period
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings/open-initial', [SettingsController::class, 'openInitial'])->name('settings.open-initial');
        Route::post('/settings/transition', [SettingsController::class, 'transition'])->name('settings.transition');
        Route::post('/settings/close', [SettingsController::class, 'close'])->name('settings.close');
    });

