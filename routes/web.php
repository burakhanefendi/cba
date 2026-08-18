<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\HomepageController;
use Illuminate\Support\Facades\Route;

// Ana site — locale otomatik algılanır (tr varsayılan, /en/... İngilizce)
Route::middleware(\App\Http\Middleware\SetLocale::class)->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/haberler', [NewsController::class, 'index'])->name('news.index');
    Route::get('/haberler/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/projeler', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projeler/{slug}', [ProjectController::class, 'show'])->name('projects.show');

    Route::prefix('en')->name('en.')->group(function () {
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
    });
});

// Breeze profil route'ları
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin paneli
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('projects', AdminProjectController::class);
    Route::resource('news', AdminNewsController::class);
    Route::resource('team', TeamMemberController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('homepage', [HomepageController::class, 'index'])->name('homepage.index');
    Route::post('homepage', [HomepageController::class, 'update'])->name('homepage.update');
    Route::post('homepage/slides', [HomepageController::class, 'storeSlide'])->name('homepage.slides.store');
    Route::delete('homepage/slides/{slide}', [HomepageController::class, 'destroySlide'])->name('homepage.slides.destroy');

    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::get('media/list', [MediaController::class, 'list'])->name('media.list');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
});

require __DIR__.'/auth.php';
