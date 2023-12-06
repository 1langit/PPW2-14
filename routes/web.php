<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BukuController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function() {return view('dashboard');})->name('dashboard');
    Route::get('/buku', [BukuController::class, 'index'])->name('buku');
    Route::get('/favorit', [BukuController::class, 'showfavourite'])->name('favorit');
    Route::post('/buku/addfavourite', [BukuController::class, 'addfavourite'])->name('buku.addfavourite');
    Route::post('/buku/addrating', [BukuController::class, 'addrating'])->name('buku.addrating');
});

require __DIR__.'/auth.php';

Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');
Route::get('/detail-buku/{id}', [BukuController::class, 'galbuku'])->name('galeri.buku');

Route::middleware('admin')->group(function () {
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku/store', [BukuController::class, 'store'])->name('buku.store');
    Route::post('/buku/delete/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
    Route::post('/buku/edit/{id}', [BukuController::class, 'edit'])->name('buku.edit');
    Route::post('/buku/update/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::post('/gallery/delete/{id}', [BukuController::class, 'deletegallery'])->name('buku.deletegallery');
});