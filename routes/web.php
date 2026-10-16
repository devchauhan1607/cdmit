<?php

use App\Http\Controllers\Admin\CaseStudyController as AdminCaseStudyController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services.index');
    Route::get('/services/{slug}', 'service')->name('services.show');
    Route::get('/portfolio', 'portfolio')->name('portfolio.index');
    Route::get('/portfolio/{slug}', 'caseStudy')->name('case-studies.show');
    Route::get('/technologies', 'technologies')->name('technologies');
    Route::get('/blog', 'blog')->name('blog.index');
    Route::get('/blog/{slug}', 'post')->name('blog.show');
    Route::get('/contact', 'contact')->name('contact');
});

Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:inquiries')->name('contact.store');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'super-admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('services', AdminServiceController::class);
    Route::resource('case-studies', AdminCaseStudyController::class);
    Route::resource('posts', AdminPostController::class);

    Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
    Route::patch('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
    Route::post('leads/{lead}/retry-notification', [LeadController::class, 'retryNotification'])->name('leads.retry-notification');

    Route::get('catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::post('catalog/categories', [CatalogController::class, 'storeCategory'])->name('categories.store');
    Route::delete('catalog/categories/{category}', [CatalogController::class, 'destroyCategory'])->name('categories.destroy');
    Route::post('catalog/technologies', [CatalogController::class, 'storeTechnology'])->name('technologies.store');
    Route::delete('catalog/technologies/{technology}', [CatalogController::class, 'destroyTechnology'])->name('technologies.destroy');

    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('settings/seo/{pageKey}', [SettingsController::class, 'updateSeo'])->name('settings.seo.update');
});
