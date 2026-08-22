<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\RegistrationController;
use App\Http\Controllers\CheckinController;
use App\Http\Middleware\EnsureUserCanValidateCheckin;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\WebhookController;

// webhook
Route::post('/webhook/stripe', [WebhookController::class, 'handleStripe'])->name('webhook.stripe');

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/categorias/{category}', [EventController::class, 'category'])
    ->name('events.category');

Route::get('/convite/{token}',[EventController::class, 'private'])
    ->name('events.private');

Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

Route::get('/checkin/{token}', [CheckinController::class, 'check'])
    ->middleware(['auth','checkin.validator'])
    ->name('checkin');

Route::get('/checkin-scanner', [CheckinController::class, 'scanner'])
    ->middleware(['auth', 'checkin.validator'])
    ->name('checkin.scanner');

Route::get('/dashboard', function () {return view('dashboard');})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/events/{event}/register',[EventController::class, 'register'])
        ->name('events.register');

    Route::get('/minhas-inscricoes', [RegistrationController::class, 'my'])
        ->name('registrations.my');

    Route::get('/minhas-inscricoes/{registration}', [RegistrationController::class, 'show'])
        ->name('registrations.show');
});

require __DIR__.'/auth.php';

Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/pagamento-sucesso', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/pagamento-cancelado', [PaymentController::class, 'cancel'])->name('payment.cancel');