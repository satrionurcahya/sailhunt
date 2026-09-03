<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleDriveDocumentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUnitController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Admin\AdminCompetitionController;
use App\Http\Controllers\Admin\AdminScoreController;
use App\Http\Controllers\DownloadController;

// ============================================================
// LANDING PAGE
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================================
// AUTHENTICATION
// ============================================================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,10');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,10');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================
// VERIFIKASI EMAIL
// ============================================================
Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])
    ->name('verification.verify')
    ->middleware('signed');

Route::post('/email/verify/resend', [RegisterController::class, 'resendVerification'])
    ->name('verification.resend')
    ->middleware('throttle:3,10');

// ============================================================
// RESET PASSWORD (LUPA PASSWORD)
// ============================================================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email')
    ->middleware('throttle:3,10'); // 3 percobaan per 10 menit

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// ============================================================
// DASHBOARD UNIT
// ============================================================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ============================================================
// SEMUA ROUTE YANG MEMBUTUHKAN LOGIN UNIT
// ============================================================
Route::middleware(['check.unit'])->group(function () {

    // --------------------------------------------------------
    // LOMBA
    // --------------------------------------------------------
    Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
    Route::post('/competitions/batch', [CompetitionController::class, 'storeBatch'])->name('competitions.storeBatch');

    // --------------------------------------------------------
    // PROFIL
    // --------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/daftar-ulang', [ProfileController::class, 'uploadDaftarUlang'])->name('profile.daftar-ulang.upload');
    Route::post('/profile/lomba/{registration}', [ProfileController::class, 'uploadLomba'])->name('profile.lomba.upload');

    // --------------------------------------------------------
    // PAYMENT
    // --------------------------------------------------------
    Route::post('/payment/batch', [PaymentController::class, 'storeBatch'])->name('payment.storeBatch');

    // --------------------------------------------------------
    // KARTU PESERTA
    // --------------------------------------------------------
    Route::get('/card/download/{registration}', [ProfileController::class, 'downloadCard'])->name('card.download');

    // --------------------------------------------------------
    // STATUS
    // --------------------------------------------------------
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');

    // --------------------------------------------------------
    // DOKUMEN GOOGLE DRIVE - USER
    // --------------------------------------------------------
    Route::get('/dokumen/{upload}/view', [GoogleDriveDocumentController::class, 'userView'])->name('documents.view');
});

// ============================================================
// ROUTE ADMIN
// ============================================================
Route::middleware(['check.unit', 'check.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ----------------------------------------------------
        // DASHBOARD ADMIN
        // ----------------------------------------------------
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // ----------------------------------------------------
        // UNIT
        // ----------------------------------------------------
        Route::get('/units', [AdminUnitController::class, 'index'])->name('units.index');
        Route::get('/units/{unit}', [AdminUnitController::class, 'show'])->name('units.show');
        Route::post('/units/{unit}/verify', [AdminUnitController::class, 'verify'])->name('units.verify');

        // ----------------------------------------------------
        // PEMBAYARAN
        // ----------------------------------------------------
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{upload}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');

        // ----------------------------------------------------
        // DAFTAR ULANG
        // ----------------------------------------------------
        Route::get('/daftar-ulang', [AdminVerificationController::class, 'daftarUlang'])->name('daftar-ulang.index');
        Route::post('/daftar-ulang/{upload}/verify', [AdminVerificationController::class, 'verifyDaftarUlang'])->name('daftar-ulang.verify');

        // ----------------------------------------------------
        // DOKUMEN GOOGLE DRIVE - ADMIN
        // ----------------------------------------------------
        Route::get('/dokumen/{upload}/view', [GoogleDriveDocumentController::class, 'adminView'])->name('documents.view');

        // ----------------------------------------------------
        // COMPETITIONS
        // ----------------------------------------------------
        Route::get('/competitions', [AdminCompetitionController::class, 'index'])->name('competitions.index');
        Route::get('/competitions/{competition}', [AdminCompetitionController::class, 'show'])->name('competitions.show');

        // ----------------------------------------------------
        // SCORES
        // ----------------------------------------------------
        Route::get('/scores', [AdminScoreController::class, 'selectCompetition'])->name('scores.select');
        Route::get('/scores/{competition}', [AdminScoreController::class, 'input'])->name('scores.input');
        Route::post('/scores/{competition}', [AdminScoreController::class, 'store'])->name('scores.store');

        // ----------------------------------------------------
        // SCORES - RANKING
        // ----------------------------------------------------
        Route::get('/scores/ranking', [AdminScoreController::class, 'ranking'])->name('scores.ranking');
    });

// ============================================================
// DOWNLOAD DOKUMEN / INFORMASI PUBLIK
// ============================================================
Route::get('/download', [DownloadController::class, 'index'])->name('download.index');
Route::get('/download/{slug}', [DownloadController::class, 'show'])->name('download.show');