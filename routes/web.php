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
use App\Http\Controllers\CartController;

// webhook
Route::post('/webhook/stripe', [WebhookController::class, 'handleStripe'])->name('webhook.stripe');

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/categorias/{category}', [EventController::class, 'category'])
    ->name('events.category');

Route::get('/convite/{token}',[EventController::class, 'private'])
    ->name('events.private');

Route::get('/catalogo', [EventController::class, 'index'])
    ->name('catalog.index');

Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

Route::get('/carrinho', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/carrinho/{event}', [CartController::class, 'add'])
    ->name('cart.add');

Route::patch('/carrinho/{event}', [CartController::class, 'update'])
    ->name('cart.update');

Route::delete('/carrinho/{event}', [CartController::class, 'remove'])
    ->name('cart.remove');

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

Route::get('/checkout', [PaymentController::class, 'checkout'])
    ->middleware('auth')
    ->name('checkout');
Route::post('/checkout', [PaymentController::class, 'processCheckout'])
    ->middleware('auth')
    ->name('checkout.process');
Route::get('/pagamento-sucesso', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/pagamento-cancelado', [PaymentController::class, 'cancel'])->name('payment.cancel');