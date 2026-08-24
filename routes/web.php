<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminQuoteRequestController;
use App\Http\Controllers\Admin\PrivateQuoteRequestFileController;
use App\Http\Middleware\EnsureAdminAccess;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminProjectMediaController;

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

        Route::get(
            '/quotes',
            [AdminQuoteRequestController::class, 'index']
        )->name('quotes.index');

        Route::get(
            '/quotes/{quoteRequest}',
            [AdminQuoteRequestController::class, 'show']
        )->name('quotes.show');

        Route::patch(
            '/quotes/{quoteRequest}/status',
            [AdminQuoteRequestController::class, 'updateStatus']
        )->name('quotes.status.update');

        Route::post(
            '/quotes/{quoteRequest}/notes',
            [AdminQuoteRequestController::class, 'storeNote']
        )->name('quotes.notes.store');

        Route::get(
            '/projects',
            [AdminProjectController::class, 'index']
        )->name('projects.index');

        Route::get(
            '/projects/create',
            [AdminProjectController::class, 'create']
        )->name('projects.create');

        Route::post(
            '/projects',
            [AdminProjectController::class, 'store']
        )->name('projects.store');

        Route::get(
            '/projects/{project}/edit',
            [AdminProjectController::class, 'edit']
        )->name('projects.edit');

        Route::put(
            '/projects/{project}',
            [AdminProjectController::class, 'update']
        )->name('projects.update');

        Route::post(
            '/projects/{project}/media',
            [AdminProjectMediaController::class, 'store']
        )->name('projects.media.store');

        Route::put(
            '/projects/{project}/media/{projectMedia}',
            [AdminProjectMediaController::class, 'update']
        )->name('projects.media.update');

        Route::patch(
            '/projects/{project}/media/{projectMedia}/feature',
            [AdminProjectMediaController::class, 'feature']
        )->name('projects.media.feature');

        Route::delete(
            '/projects/{project}/media/{projectMedia}',
            [AdminProjectMediaController::class, 'destroy']
        )->name('projects.media.destroy');

        Route::post(
            '/projects/{project}/comparisons',
            [AdminProjectMediaController::class, 'storeComparison']
        )->name('projects.comparisons.store');

        Route::delete(
            '/projects/{project}/comparisons/{projectComparison}',
            [AdminProjectMediaController::class, 'destroyComparison']
        )->name('projects.comparisons.destroy');

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
