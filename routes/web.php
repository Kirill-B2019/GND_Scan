<?php

use App\Http\Controllers\Explorer\AddressController;
use App\Http\Controllers\Explorer\BlockController;
use App\Http\Controllers\Explorer\ContractController;
use App\Http\Controllers\Explorer\DashboardController;
use App\Http\Controllers\Explorer\SearchController;
use App\Http\Controllers\Explorer\StatsController;
use App\Http\Controllers\Explorer\TokenController;
use App\Http\Controllers\Explorer\TransactionController;
use App\Http\Controllers\Explorer\ValidatorsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

// Отдача Vite-сборки (CSS/JS) через Laravel, если веб-сервер не отдаёт public/build
Route::get('/build/assets/{filename}', function (string $filename): Response {
    if (! preg_match('/^[a-zA-Z0-9_.-]+\\.(css|js)$/', $filename)) {
        abort(404);
    }
    $path = public_path('build/assets/' . $filename);
    if (! is_file($path)) {
        abort(404);
    }
    $mime = str_ends_with($filename, '.css') ? 'text/css' : 'application/javascript';
    return response()->file($path, ['Content-Type' => $mime]);
})->where('filename', '[a-zA-Z0-9_.-]+\\.(css|js)');

// Публичный блокчейн-сканер (GND Explorer)
Route::get('/', [DashboardController::class, 'index'])->name('explorer.dashboard');
Route::get('/explorer/data', [DashboardController::class, 'data'])->name('explorer.dashboard.data');
Route::get('/search', [SearchController::class, 'search'])->name('explorer.search');
Route::get('/explorer/search/suggest', [SearchController::class, 'suggest'])->name('explorer.search.suggest');
Route::get('/block/{number}', [BlockController::class, 'show'])->name('explorer.block.show');
Route::get('/transactions', [TransactionController::class, 'index'])->name('explorer.transactions');
Route::get('/tx/{hash}', [TransactionController::class, 'show'])->name('explorer.transaction.show');
Route::get('/address/{address}', [AddressController::class, 'show'])->where('address', '.*')->name('explorer.address.show');
Route::get('/contract/{address}', [ContractController::class, 'show'])->where('address', '.*')->name('explorer.contract.show');
Route::get('/token/{address}', [TokenController::class, 'show'])->where('address', '.*')->name('explorer.token.show');
Route::get('/validators', [ValidatorsController::class, 'index'])->name('explorer.validators');
Route::get('/stats', [StatsController::class, 'index'])->name('explorer.stats');

// Breeze: личный кабинет (опционально)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
