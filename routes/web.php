<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\ProfileEntryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Ana site — locale otomatik algılanır (ayarlar: varsayılan dil, /en/... İngilizce)
Route::middleware(\App\Http\Middleware\SetLocale::class)->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/haberler', [NewsController::class, 'index'])->name('news.index');
    Route::get('/haberler/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/projeler', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projeler/{slug}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/profil', [AboutController::class, 'profile'])->name('about.profile');
    Route::get('/profil/studyo', [AboutController::class, 'studio'])->name('about.studio');
    Route::get('/profil/ekip', [AboutController::class, 'team'])->name('about.team');
    Route::get('/profil/oduller', [AboutController::class, 'awards'])->name('about.awards');
    Route::get('/profil/yayinlar', [AboutController::class, 'publications'])->name('about.publications');
    Route::get('/ara', [SearchController::class, 'index'])->name('search');
    Route::get('/iletisim', [ContactController::class, 'index'])->name('contact');

    Route::prefix('en')->name('en.')->group(function () {
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/profile', [AboutController::class, 'profile'])->name('about.profile');
        Route::get('/profile/studio', [AboutController::class, 'studio'])->name('about.studio');
        Route::get('/profile/team', [AboutController::class, 'team'])->name('about.team');
        Route::get('/profile/awards', [AboutController::class, 'awards'])->name('about.awards');
        Route::get('/profile/publications', [AboutController::class, 'publications'])->name('about.publications');
        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
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

    Route::get('awards', [ProfileEntryController::class, 'index'])->name('awards.index');
    Route::get('awards/create', [ProfileEntryController::class, 'create'])->name('awards.create');
    Route::post('awards', [ProfileEntryController::class, 'store'])->name('awards.store');
    Route::get('awards/{entry}/edit', [ProfileEntryController::class, 'edit'])->name('awards.edit');
    Route::put('awards/{entry}', [ProfileEntryController::class, 'update'])->name('awards.update');
    Route::delete('awards/{entry}', [ProfileEntryController::class, 'destroy'])->name('awards.destroy');

    Route::get('publications', [ProfileEntryController::class, 'index'])->name('publications.index');
    Route::get('publications/create', [ProfileEntryController::class, 'create'])->name('publications.create');
    Route::post('publications', [ProfileEntryController::class, 'store'])->name('publications.store');
    Route::get('publications/{entry}/edit', [ProfileEntryController::class, 'edit'])->name('publications.edit');
    Route::put('publications/{entry}', [ProfileEntryController::class, 'update'])->name('publications.update');
    Route::delete('publications/{entry}', [ProfileEntryController::class, 'destroy'])->name('publications.destroy');
    Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('about', [AdminAboutController::class, 'index'])->name('about.index');
    Route::post('about', [AdminAboutController::class, 'update'])->name('about.update');

    Route::get('homepage', [HomepageController::class, 'index'])->name('homepage.index');
    Route::post('homepage', [HomepageController::class, 'update'])->name('homepage.update');
    Route::post('homepage/slides', [HomepageController::class, 'storeSlide'])->name('homepage.slides.store');
    Route::delete('homepage/slides/{slide}', [HomepageController::class, 'destroySlide'])->name('homepage.slides.destroy');

    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::post('media/folder', [MediaController::class, 'createFolder'])->name('media.folder.create');
    Route::get('media/list', [MediaController::class, 'list'])->name('media.list');
    Route::get('media/folders', [MediaController::class, 'folders'])->name('media.folders');
    Route::post('media/move', [MediaController::class, 'move'])->name('media.move');
    Route::post('media/reorder', [MediaController::class, 'reorder'])->name('media.reorder');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
});

require __DIR__.'/auth.php';
