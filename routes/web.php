<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporBanjirController;
use App\Http\Controllers\SmartEduController;
use App\Http\Controllers\PendaftaranSiswaController;

Route::get('/laporbanjir', [LaporBanjirController::class, 'form'])->name('laporbanjir.form');
Route::post('/laporbanjir/kirim', [LaporBanjirController::class, 'kirim'])->name('laporbanjir.kirim');
Route::get('/laporbanjir/daftar', [LaporBanjirController::class, 'daftar'])->name('laporbanjir.daftar');

// Halaman utama / landing
Route::get('/', [SmartEduController::class, 'landing'])->name('home');

// Auth
Route::get('/login', [SmartEduController::class, 'loginForm'])->name('login');
Route::post('/login', [SmartEduController::class, 'login'])->name('login.submit');
Route::post('/logout', [SmartEduController::class, 'logout'])->name('logout');

// Dashboard & fitur per role
Route::middleware('smartedu.auth:staff-tu')->group(function () {
    Route::get('/staff-tu/dashboard', [SmartEduController::class, 'staffDashboard'])->name('staff-tu.dashboard');

    // Staff TU — Manajemen Pengguna
    Route::get('/staff-tu/pengguna', [SmartEduController::class, 'pengguna'])->name('staff-tu.pengguna');
    Route::get('/staff-tu/pengguna/tambah', [SmartEduController::class, 'createPengguna'])->name('staff-tu.pengguna.create');
    Route::post('/staff-tu/pengguna/tambah', [SmartEduController::class, 'storePengguna'])->name('staff-tu.pengguna.store');
    Route::get('/staff-tu/pengguna/{id}/edit', [SmartEduController::class, 'editPengguna'])->name('staff-tu.pengguna.edit');
    Route::put('/staff-tu/pengguna/{id}', [SmartEduController::class, 'updatePengguna'])->name('staff-tu.pengguna.update');
    Route::delete('/staff-tu/pengguna/{id}', [SmartEduController::class, 'destroyPengguna'])->name('staff-tu.pengguna.destroy');

    // Staff TU — Manajemen Kelas
    Route::get('/staff-tu/kelas', [SmartEduController::class, 'kelas'])->name('staff-tu.kelas');

    // Staff TU — Manajemen Mata Pelajaran
    Route::get('/staff-tu/mapel', [SmartEduController::class, 'mapel'])->name('staff-tu.mapel');
    Route::post('/staff-tu/mapel/upload', [SmartEduController::class, 'uploadMapelFile'])->name('staff-tu.mapel.upload');
});

Route::middleware('smartedu.auth:guru')->group(function () {
    Route::get('/guru/dashboard', [SmartEduController::class, 'guruDashboard'])->name('guru.dashboard');
});

Route::middleware('smartedu.auth:siswa')->group(function () {
    Route::get('/siswa/dashboard', [SmartEduController::class, 'siswaDashboard'])->name('siswa.dashboard');

    // Siswa — Materi
    Route::get('/siswa/materi', [SmartEduController::class, 'materi'])->name('siswa.materi');
    Route::get('/siswa/materi/{kodeMateri}', [SmartEduController::class, 'detailMateri'])->name('siswa.materi.detail');

    // Siswa — Latihan
    Route::get('/siswa/latihan', [SmartEduController::class, 'latihan'])->name('siswa.latihan');
    Route::get('/siswa/latihan/{kodeMateri}', [SmartEduController::class, 'detailLatihan'])->name('siswa.latihan.detail');
});

Route::middleware('smartedu.auth:orang-tua')->group(function () {
    Route::get('/orang-tua/dashboard', [SmartEduController::class, 'orangTuaDashboard'])->name('orang-tua.dashboard');
});

Route::get('/pendaftaran', [PendaftaranSiswaController::class, 'form'])->name('pendaftaran.form');
Route::post('/pendaftaran/proses', [PendaftaranSiswaController::class, 'proses'])->name('pendaftaran.proses');
