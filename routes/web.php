<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');

Route::group([
    'as' => 'admin.',
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin']
], function () {
    Route::get('', [AdminController::class, 'index'])->name('index');

    Route::resource('category', CategoryController::class);

    Route::resource('brand', BrandController::class);

    Route::resource('product', ProductController::class);
    Route::get('product/category/{category}', [ProductController::class, 'category'])
        ->name('product.category');

    Route::resource('order', OrderController::class, [
        'except' => [
            'create',
            'store',
            'destroy'
        ]
    ]);

    Route::resource('user', \App\Http\Controllers\Admin\UserController::class, [
        'except' => [
            'create',
            'store',
            'show',
            'destroy'
        ]
    ]);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::name('user.')->prefix('user')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('index');
    });
});

Route::middleware("guest")->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/login/process', [AuthController::class, 'login'])->name('auth.loginProcess');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('auth.register');
    Route::post('/register/process', [RegisterController::class, 'register'])->name('auth.registerProcess');

    Route::get('/forgot/password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name(
        'auth.forgotPassword'
    );
    Route::post('/forgot/password/process', [ForgotPasswordController::class, 'forgotPassword'])->name(
        'auth.forgotPasswordProcess'
    );
    Route::get('/complete/forgot/password', [ForgotPasswordController::class, 'showCompleteForgotPassword'])->name(
        'auth.completeForgotPassword'
    );
});

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/brand', [App\Http\Controllers\BrandController::class, 'index'])->name('brand.index');
Route::get('/catalog/category/{slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/brand/{slug}', [CatalogController::class, 'brand'])->name('catalog.brand');
Route::get('/catalog/product/{slug}', [CatalogController::class, 'product'])->name('catalog.product');

Route::get('/basket', [BasketController::class, 'index'])->name('basket.index');
Route::get('/basket/checkout', [BasketController::class, 'checkout'])->name('basket.checkout');

Route::post('/basket/add/{id}', [BasketController::class, 'add'])
    ->where('id', '[0-9]+')
    ->name('basket.add');

Route::post('/basket/plus/{id}', [BasketController::class, 'plus'])
    ->where('id', '[0-9]+')
    ->name('basket.plus');
Route::post('/basket/minus/{id}', [BasketController::class, 'minus'])
    ->where('id', '[0-9]+')
    ->name('basket.minus');

Route::post('/basket/remove/{id}', [BasketController::class, 'remove'])
    ->where('id', '[0-9]+')
    ->name('basket.remove');

Route::post('/basket/clear', [BasketController::class, 'clear'])->name('basket.clear');

Route::post('/basket/saveOrder', [BasketController::class, 'saveOrder'])->name('basket.saveOrder');

Route::get('/basket/success', [BasketController::class, 'success'])->name('basket.success');
