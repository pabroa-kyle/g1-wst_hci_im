<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/samples/{template}', [HomeController::class, 'sample'])->name('samples.show');
Route::get('/p/{slug}', [PortfolioController::class, 'showPublic'])->name('portfolios.public')
    ->where('slug', '[a-z0-9-]+');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/portfolios/create', [PortfolioController::class, 'create'])->name('portfolios.create');
    Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
    Route::post('/portfolios/sample', [PortfolioController::class, 'sample'])->name('portfolios.sample');

    Route::get('/portfolios/{portfolio}', [PortfolioController::class, 'show'])->name('portfolios.show');
    Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');

    Route::get('/portfolios/{portfolio}/edit/{step?}', [PortfolioController::class, 'edit'])->name('portfolios.edit');
    Route::put('/portfolios/{portfolio}/edit/{step}', [PortfolioController::class, 'update'])->name('portfolios.update');

    Route::get('/portfolios/{portfolio}/template', [PortfolioController::class, 'chooseTemplate'])->name('portfolios.template');
    Route::post('/portfolios/{portfolio}/generate', [PortfolioController::class, 'generate'])->name('portfolios.generate');
    Route::put('/portfolios/{portfolio}/share', [PortfolioController::class, 'share'])->name('portfolios.share');
    Route::get('/portfolios/{portfolio}/preview', [PortfolioController::class, 'preview'])->name('portfolios.preview');
    Route::get('/portfolios/{portfolio}/render', [PortfolioController::class, 'render'])->name('portfolios.render');
});
