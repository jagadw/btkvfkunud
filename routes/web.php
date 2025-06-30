<?php

use App\Livewire\SemuaTindakan;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\MenuManagement;
use App\Livewire\RolesPermissions;
use App\Livewire\User;
use App\Livewire\Auth\Login;
use App\Livewire\CreateFotoTindakan;
use App\Livewire\FotoTindakan;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');
    Route::get('/login', Login::class)->name('login');
    Route::get('/logout', [Login::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/user', User::class)->name('user');
        Route::get('/menu', MenuManagement::class)->name('menu');
        Route::get('/roles', RolesPermissions::class)->name('role');
        Route::get('/mahasiswa', \App\Livewire\Mahasiswa::class)->name('mahasiswa');
        Route::get('/dpjp', \App\Livewire\Dpjp::class)->name('dpjp');
        Route::get('/verifikasi-tindakan', \App\Livewire\VerifikasiTindakan::class)->name('verifikasi-tindakan');
        Route::get('/sudah-verifikasi', \App\Livewire\DataVerifikasi::class)->name('sudah-verifikasi');
        Route::get('/pasien', \App\Livewire\Pasien::class)->name('pasien');

        Route::get('/tindakan', \App\Livewire\Tindakan::class)->name('tindakan');
        Route::get('/semua-tindakan', SemuaTindakan::class)->name('semua-tindakan');
        Route::get('/tindakan/add', \App\Livewire\CreateTindakan::class)->name('create-tindakan');
        Route::get('/tindakan/edit/{id}', \App\Livewire\CreateTindakan::class)->name('edit-tindakan');
        Route::get('/tindakan/{id}/foto-tindakan', action: CreateFotoTindakan::class)->name('create-fototindakan');

        Route::get('/logbook', \App\Livewire\LogBook::class)->name('logbook');
        Route::get('/logbook/add', \App\Livewire\CreateLogBook::class)->name(name: 'add-logbook');
        Route::get('/logbook/edit/{id}', \App\Livewire\CreateLogBook::class)->name(name: 'edit-logbook');

        Route::get('/fototindakan',FotoTindakan::class)->name('fototindakan');
        Route::get('/conference', \App\Livewire\Conference::class)->name('conference');
        Route::get('/test', MenuManagement::class)->name('test');
    });

require __DIR__.'/auth.php';
