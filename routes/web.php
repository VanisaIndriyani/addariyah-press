<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EcatalogController as AdminEcatalogController;
use App\Http\Controllers\Admin\ManuscriptController as AdminManuscriptController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EcatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManuscriptPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/buku/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/terbitan', [PublicationController::class, 'index'])->name('publications');
Route::get('/berita', [NewsController::class, 'index'])->name('news');
Route::get('/berita/{post:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/naskah', [ManuscriptPageController::class, 'index'])->name('manuscripts');
Route::get('/naskah/pedoman', [ManuscriptPageController::class, 'guide'])->name('manuscripts.guide');
Route::get('/naskah/template', [ManuscriptPageController::class, 'templates'])->name('manuscripts.templates');
Route::get('/naskah/kirim', [ManuscriptPageController::class, 'create'])->name('manuscripts.create');
Route::post('/naskah/kirim', [ManuscriptPageController::class, 'store'])->middleware('throttle:8,1')->name('manuscripts.store');
Route::get('/e-catalog', [EcatalogController::class, 'index'])->name('ecatalog');
Route::get('/tentang', [PageController::class, 'about'])->name('about');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart');
Route::post('/keranjang/{book:slug}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{book:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{book:slug}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:8,1')->name('checkout.store');
Route::get('/pembayaran/{code}', [CheckoutController::class, 'show'])
    ->where('code', 'AP-[A-Za-z0-9]+')
    ->name('checkout.pay');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/buku/{book}/ebook', [AdminBookController::class, 'ebook'])->name('buku.ebook');
        Route::resource('buku', AdminBookController::class)->except(['show'])->parameters(['buku' => 'book']);

        Route::resource('berita', AdminPostController::class)->except(['show'])->parameters(['berita' => 'post']);

        Route::get('/naskah', [ResourceController::class, 'index'])->name('resources.index');
        Route::put('/naskah/pedoman', [ResourceController::class, 'updateGuide'])->name('resources.guide');
        Route::post('/naskah/berkas', [ResourceController::class, 'store'])->name('resources.store');
        Route::put('/naskah/berkas/{download}', [ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/naskah/berkas/{download}', [ResourceController::class, 'destroy'])->name('resources.destroy');

        Route::get('/naskah-masuk', [AdminManuscriptController::class, 'index'])->name('manuscripts.index');
        Route::get('/naskah-masuk/{manuscript}', [AdminManuscriptController::class, 'show'])->name('manuscripts.show');
        Route::get('/naskah-masuk/{manuscript}/berkas/{kind}', [AdminManuscriptController::class, 'file'])
            ->whereIn('kind', ['pernyataan', 'keaslian', 'cover', 'dummy', 'buku'])
            ->name('manuscripts.file');
        Route::delete('/naskah-masuk/{manuscript}', [AdminManuscriptController::class, 'destroy'])->name('manuscripts.destroy');

        Route::get('/e-catalog', [AdminEcatalogController::class, 'index'])->name('ecatalog.index');
        Route::post('/e-catalog', [AdminEcatalogController::class, 'store'])->name('ecatalog.store');
        Route::put('/e-catalog/{ecatalog}', [AdminEcatalogController::class, 'update'])->name('ecatalog.update');
        Route::delete('/e-catalog/{ecatalog}', [AdminEcatalogController::class, 'destroy'])->name('ecatalog.destroy');

        Route::get('/slide', [SlideController::class, 'index'])->name('slides.index');
        Route::post('/slide', [SlideController::class, 'store'])->name('slides.store');
        Route::put('/slide/{slide}', [SlideController::class, 'update'])->name('slides.update');
        Route::delete('/slide/{slide}', [SlideController::class, 'destroy'])->name('slides.destroy');

        Route::get('/pengaturan', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
    });
});
