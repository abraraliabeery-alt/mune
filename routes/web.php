<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('menu');
});

Route::get('/lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'ar'], true)) {
        abort(404);
    }

    $fallback = route('menu');

    return redirect()->to(url()->previous($fallback))
        ->withCookie(cookie('locale', $locale, 60 * 24 * 30));
})->name('lang.switch');

Route::get('/theme/{mode}', function (string $mode) {
    if (! in_array($mode, ['light', 'dark'], true)) {
        abort(404);
    }

    $fallback = route('menu');

    return redirect()->to(url()->previous($fallback))
        ->withCookie(cookie('theme', $mode, 60 * 24 * 30));
})->name('theme.switch');

Route::get('/menu', function () {
    $categories = ['Hot', 'Iced', 'Desserts'];

    $productsQuery = \App\Models\Product::query()->where('is_available', true)->orderBy('name');

    if (request()->filled('category')) {
        $productsQuery->where('category', request()->string('category')->toString());
    }

    return view('menu', [
        'categories' => $categories,
        'activeCategory' => request()->string('category')->toString(),
        'products' => $productsQuery->get(),
    ]);
})->name('menu');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/table', [CartController::class, 'setTableNumber'])->name('cart.table');

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{publicCode}', [OrderController::class, 'show'])->name('orders.show');

Route::resource('products', ProductController::class)->only(['index', 'show']);

Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('orders.status');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
