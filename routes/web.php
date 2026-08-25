<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminQuoteRequestController;
use App\Http\Controllers\Admin\PrivateQuoteRequestFileController;
use App\Http\Middleware\EnsureAdminAccess;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminProjectMediaController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminServiceContentController;
use App\Http\Controllers\Admin\AdminServiceMediaController;
use App\Http\Controllers\PublicServiceController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\PublicQuoteController;
use App\Http\Controllers\PublicHomeController;


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


        Route::post(
            '/services/{service}/solutions',
            [AdminServiceContentController::class, 'storeSolution']
        )->name('services.solutions.store');

        Route::put(
            '/services/{service}/solutions/{serviceSolution}',
            [AdminServiceContentController::class, 'updateSolution']
        )->name('services.solutions.update');

        Route::delete(
            '/services/{service}/solutions/{serviceSolution}',
            [AdminServiceContentController::class, 'destroySolution']
        )->name('services.solutions.destroy');

        Route::post(
            '/services/{service}/benefits',
            [AdminServiceContentController::class, 'storeBenefit']
        )->name('services.benefits.store');

        Route::put(
            '/services/{service}/benefits/{serviceBenefit}',
            [AdminServiceContentController::class, 'updateBenefit']
        )->name('services.benefits.update');

        Route::delete(
            '/services/{service}/benefits/{serviceBenefit}',
            [AdminServiceContentController::class, 'destroyBenefit']
        )->name('services.benefits.destroy');

        Route::post(
            '/services/{service}/faqs',
            [AdminServiceContentController::class, 'storeFaq']
        )->name('services.faqs.store');

        Route::put(
            '/services/{service}/faqs/{serviceFaq}',
            [AdminServiceContentController::class, 'updateFaq']
        )->name('services.faqs.update');

        Route::delete(
            '/services/{service}/faqs/{serviceFaq}',
            [AdminServiceContentController::class, 'destroyFaq']
        )->name('services.faqs.destroy');

        Route::post(
            '/services/{service}/media',
            [AdminServiceMediaController::class, 'store']
        )->name('services.media.store');

        Route::patch(
            '/services/{service}/media/{serviceMedia}/feature',
            [AdminServiceMediaController::class, 'feature']
        )->name('services.media.feature');

        Route::delete(
            '/services/{service}/media/{serviceMedia}',
            [AdminServiceMediaController::class, 'destroy']
        )->name('services.media.destroy');

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

        Route::get(
            '/services',
            [AdminServiceController::class, 'index']
        )->name('services.index');

        Route::get(
            '/services/create',
            [AdminServiceController::class, 'create']
        )->name('services.create');

        Route::post(
            '/services',
            [AdminServiceController::class, 'store']
        )->name('services.store');

        Route::get(
            '/services/{service}/edit',
            [AdminServiceController::class, 'edit']
        )->name('services.edit');

        Route::put(
            '/services/{service}',
            [AdminServiceController::class, 'update']
        )->name('services.update');

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
Route::get(
    '/servicios',
    [PublicServiceController::class, 'index']
)->name('public.services.index');

Route::get(
    '/servicios/{service:slug}',
    [PublicServiceController::class, 'show']
)->name('public.services.show');

Route::get(
    '/proyectos',
    [PublicProjectController::class, 'index']
)->name('public.projects.index');

Route::get(
    '/proyectos/{project:slug}',
    [PublicProjectController::class, 'show']
)->name('public.projects.show');

Route::get(
    '/cotizar',
    [PublicQuoteController::class, 'create']
)->name('public.quote.create');

Route::post(
    '/cotizar',
    [PublicQuoteController::class, 'store']
)
    ->middleware('throttle:10,1')
    ->name('public.quote.store');

Route::get(
    '/cotizar/gracias/{quoteRequest:folio}',
    [PublicQuoteController::class, 'thanks']
)->name('public.quote.thanks');

Route::get(
    '/',
    [PublicHomeController::class, 'index']
)->name('public.home');