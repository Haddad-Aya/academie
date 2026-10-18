<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Coach;
use App\Http\Controllers\Guardian;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Webhook\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

// Site vitrine : les groupes, entraîneurs et photos sont chargés depuis la base.
Route::get('/', [PublicSiteController::class, 'index'])->name('home');

// Webhook Konnect : GET payment_ref, statut vérifié ensuite côté serveur auprès de son API.
Route::get('/webhooks/konnect', PaymentWebhookController::class)
    ->middleware('throttle:120,1')
    ->name('webhooks.konnect');

// Ancienne route générique conservée pour les autres passerelles éventuelles.
Route::post('/webhooks/payments', PaymentWebhookController::class)
    ->middleware('throttle:120,1')
    ->name('webhooks.payments');

Route::middleware('auth')->group(function () {
    // Redirige chaque rôle vers son espace après connexion (remplace la route /dashboard de Breeze).
    Route::get('/dashboard', HomeController::class)->name('dashboard');

    // Profil (fourni par Breeze).
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ---------------- Parents ---------------- */
    Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function () {
        Route::get('/', [Guardian\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/children/create', [Guardian\ChildController::class, 'create'])->name('children.create');
        Route::post('/children', [Guardian\ChildController::class, 'store'])->name('children.store');
        Route::get('/children/{child}/edit', [Guardian\ChildController::class, 'edit'])->name('children.edit');
        Route::put('/children/{child}', [Guardian\ChildController::class, 'update'])->name('children.update');

        Route::get('/children/{child}/register', [Guardian\RegistrationController::class, 'create'])->name('registrations.create');
        Route::post('/children/{child}/register', [Guardian\RegistrationController::class, 'store'])->name('registrations.store');

        Route::get('/payments/{payment}/checkout', [Guardian\PaymentController::class, 'checkout'])->name('payments.checkout');
        Route::get('/payments/{payment}/pending', [Guardian\PaymentController::class, 'pending'])->name('payments.pending');
        Route::get('/payments/{payment}/return', [Guardian\PaymentController::class, 'returned'])->name('payments.return');
    });

    /* ---------------- Entraîneurs (et administrateur) ---------------- */
    Route::middleware('role:coach,admin')->prefix('coach')->name('coach.')->group(function () {
        Route::get('/', [Coach\AttendanceController::class, 'groups'])->name('groups');
        Route::post('/groups/{group}/sessions', [Coach\AttendanceController::class, 'storeSession'])->name('sessions.store');
        Route::get('/sessions/{session}', [Coach\AttendanceController::class, 'show'])->name('attendance.show');
        Route::post('/sessions/{session}', [Coach\AttendanceController::class, 'store'])->name('attendance.store');
    });

    /* ---------------- Administrateur ---------------- */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/registrations', [Admin\RegistrationController::class, 'index'])->name('registrations.index');
        Route::post('/registrations/{registration}/approve', [Admin\RegistrationController::class, 'approve'])->name('registrations.approve');
        Route::post('/registrations/{registration}/reject', [Admin\RegistrationController::class, 'reject'])->name('registrations.reject');
        Route::get('/registrations/export', [Admin\ExportController::class, 'registrations'])->name('registrations.export');

        Route::get('/groups', [Admin\TrainingGroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/create', [Admin\TrainingGroupController::class, 'create'])->name('groups.create');
        Route::post('/groups', [Admin\TrainingGroupController::class, 'store'])->name('groups.store');
        Route::get('/groups/{group}/edit', [Admin\TrainingGroupController::class, 'edit'])->name('groups.edit');
        Route::put('/groups/{group}', [Admin\TrainingGroupController::class, 'update'])->name('groups.update');

        Route::get('/coaches', [Admin\CoachController::class, 'index'])->name('coaches.index');
        Route::post('/coaches', [Admin\CoachController::class, 'store'])->name('coaches.store');
        Route::post('/coaches/{user}/toggle', [Admin\CoachController::class, 'toggle'])->name('coaches.toggle');

        Route::get('/gallery', [Admin\GalleryController::class, 'index'])->name('gallery.index');
        Route::post('/gallery', [Admin\GalleryController::class, 'store'])->name('gallery.store');
        Route::delete('/gallery/{item}', [Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::get('/payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/paid', [Admin\PaymentController::class, 'markPaid'])->name('payments.paid');
    });
});

require __DIR__.'/auth.php';
