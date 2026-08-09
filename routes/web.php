<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PrivateQuoteRequestFileController;
use App\Http\Middleware\EnsureAdminAccess;

Route::get(
    '/admin/login',
    [AdminAuthController::class, 'show']
)->name('login');

Route::post(
    '/admin/login',
    [AdminAuthController::class, 'store']
)
    ->middleware('throttle:5,1')
    ->name('admin.login.store');

Route::middleware([
    'auth',
    EnsureAdminAccess::class,
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get(
            '/',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');

        Route::post(
            '/logout',
            [AdminAuthController::class, 'destroy']
        )->name('logout');

        Route::get(
            '/quote-files/{quoteRequestFile}/view',
            [
                PrivateQuoteRequestFileController::class,
                'show',
            ]
        )->name('quoteFiles.view');

        Route::get(
            '/quote-files/{quoteRequestFile}/download',
            [
                PrivateQuoteRequestFileController::class,
                'download',
            ]
        )->name('quoteFiles.download');
    });
