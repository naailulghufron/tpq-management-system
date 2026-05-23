<?php

use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/profil-tpq', [PublicSiteController::class, 'profile'])->name('profile');
Route::get('/program-pendidikan', [PublicSiteController::class, 'programs'])->name('programs');
Route::get('/blog', [PublicSiteController::class, 'blog'])->name('blog');
Route::get('/galeri', [PublicSiteController::class, 'gallery'])->name('gallery');
Route::get('/pengumuman', [PublicSiteController::class, 'announcements'])->name('announcements');
Route::get('/kontak', [PublicSiteController::class, 'contact'])->name('contact');
Route::get('/pendaftaran-santri', [PublicSiteController::class, 'registration'])->name('registration');
