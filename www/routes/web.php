<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SpyController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'index']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/user/profile/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');

    Route::get('/profiles', [ProfileController::class, 'all'])->name('profiles');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans');
    Route::get('/plans/subscribe/{userId}', [PlanController::class, 'show'])->name('plans-show');

    Route::get('/card', [CardController::class, 'index'])->name('card');

    Route::get('/invitation', [InvitationController::class, 'index'])->name('invitation.index')->middleware(['invitedhash']);

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
});

Route::get('/i/{linkId}', [InvitationController::class, 'redirect'])->name('invitation.redirect');

Route::get('/profiles/{slug}', [ProfileController::class, 'index'])->name('profiles-slug');
Route::get('/posts/{postId}', [PostController::class, 'index'])->name('post');

Route::get('/register/{slug}', [RegisteredUserController::class, 'create'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('register-post');

Route::get('/media/{id}', [MediaController::class, 'index'])->name('media');

if (config('app.env') != 'production') {
    Route::get('/email', [IndexController::class, 'email']);
    Route::get('/post-created-event', [IndexController::class, 'createPostEvent']);
    Route::get('/dev/auth/{userId}', [SpyController::class, 'auth']);
}
