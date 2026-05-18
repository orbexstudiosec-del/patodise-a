<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PhotoController as AdminPhotoController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PhotoPreviewController;
use App\Http\Controllers\PrivateGalleryController;
use Illuminate\Support\Facades\Route;

// Storefront
Route::get('/', HomeController::class)->name('home');

// Galerías (eventos) — listado y detalle con fotos
Route::get('/galerias', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/galeria/{gallery:slug}', [GalleryController::class, 'show'])->name('galleries.show');

// Galerías privadas (acceso por enlace + token, opcional con contraseña)
Route::get('/g/{token}', [PrivateGalleryController::class, 'show'])->name('private-gallery.show');
Route::post('/g/{token}/unlock', [PrivateGalleryController::class, 'unlock'])->name('private-gallery.unlock');

// Foto individual
Route::get('/foto/{photo:slug}', [PhotoController::class, 'show'])->name('photos.show');

// Contacto y páginas estáticas
Route::get('/contacto', [ContactController::class, 'show'])->name('contact');
Route::post('/contacto', [ContactController::class, 'send'])->name('contact.send');
Route::view('/sobre-mi', 'about')->name('about');
Route::view('/servicios', 'services')->name('services');

// Carrito
Route::prefix('carrito')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/resumen', [CartController::class, 'summary'])->name('summary');
    Route::post('/foto/{photo:slug}', [CartController::class, 'addPhoto'])->name('add-photo');
    Route::post('/galeria/{gallery:slug}', [CartController::class, 'addGallery'])->name('add-gallery');
    Route::patch('/actualizar/{key}', [CartController::class, 'update'])->name('update');
    Route::delete('/eliminar/{key}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/vaciar', [CartController::class, 'clear'])->name('clear');
});

// Preview de foto con marca de agua (sirve la versión protegida)
Route::get('/img/preview/{photo:slug}/{size?}', PhotoPreviewController::class)
    ->where('size', 'thumb|full')
    ->name('photos.preview');

// Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('show');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/gracias/{order}', [CheckoutController::class, 'success'])->name('success');
});

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('photos', AdminPhotoController::class)->except('show');
    Route::resource('galleries', AdminGalleryController::class)->except('show');
    Route::patch('/galleries/{gallery}/regenerate-token', [AdminGalleryController::class, 'regenerateToken'])->name('galleries.regenerate-token');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    Route::get('/slider', [AdminSliderController::class, 'edit'])->name('slider.edit');
    Route::post('/slider', [AdminSliderController::class, 'update'])->name('slider.update');
});
