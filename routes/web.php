<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BuffetRequestController;
use App\Http\Controllers\WorkController;
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

Route::get('/works', [WorkController::class, 'index'])->name('works.index');
Route::get('/works/{slug}', [WorkController::class, 'show'])->name('works.show');

Route::get('/buffet', [BuffetRequestController::class, 'create'])->name('buffet.create');
Route::post('/buffet', [BuffetRequestController::class, 'store'])->name('buffet.store');
Route::get('/buffet/{publicCode}', [BuffetRequestController::class, 'show'])->name('buffet.show');

Route::resource('products', ProductController::class)->only(['index', 'show']);

Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('orders.status');

        Route::get('/buffet', [\App\Http\Controllers\Admin\BuffetRequestAdminController::class, 'index'])->name('buffet.index');
        Route::get('/buffet/{buffet}', [\App\Http\Controllers\Admin\BuffetRequestAdminController::class, 'show'])->name('buffet.show');
        Route::patch('/buffet/{buffet}/status', [\App\Http\Controllers\Admin\BuffetRequestAdminController::class, 'updateStatus'])->name('buffet.status');
        Route::patch('/buffet/{buffet}/quote', [\App\Http\Controllers\Admin\BuffetRequestAdminController::class, 'saveQuote'])->name('buffet.quote');

        Route::get('/loyalty', [\App\Http\Controllers\Admin\LoyaltyAdminController::class, 'index'])->name('loyalty.index');

        Route::get('/works', [\App\Http\Controllers\Admin\WorkAdminController::class, 'index'])->name('works.index');
        Route::get('/works/create', [\App\Http\Controllers\Admin\WorkAdminController::class, 'create'])->name('works.create');
        Route::post('/works', [\App\Http\Controllers\Admin\WorkAdminController::class, 'store'])->name('works.store');
        Route::get('/works/{work}/edit', [\App\Http\Controllers\Admin\WorkAdminController::class, 'edit'])->name('works.edit');
        Route::patch('/works/{work}', [\App\Http\Controllers\Admin\WorkAdminController::class, 'update'])->name('works.update');
        Route::delete('/works/{work}', [\App\Http\Controllers\Admin\WorkAdminController::class, 'destroy'])->name('works.destroy');
        Route::delete('/works/media/{media}', [\App\Http\Controllers\Admin\WorkAdminController::class, 'destroyMedia'])->name('works.media.destroy');

        Route::middleware(['role:admin'])->group(function () {
            Route::get('/users', [\App\Http\Controllers\Admin\StaffAdminController::class, 'index'])->name('staff.index');
            Route::get('/staff/create', [\App\Http\Controllers\Admin\StaffAdminController::class, 'create'])->name('staff.create');
            Route::post('/staff', [\App\Http\Controllers\Admin\StaffAdminController::class, 'store'])->name('staff.store');
            Route::patch('/users/{user}/role', [\App\Http\Controllers\Admin\StaffAdminController::class, 'updateRole'])->name('users.role');
        });
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
