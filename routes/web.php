<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\IsAdmin;
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

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/after-login', [HomeController::class, 'after_login']);

Route::get('/bkash/callback', [BkashController::class, 'bkash_callback'])->name('bkash.callback');

Route::get('/ticket/{id}', [HomeController::class, 'ticket'])->name('ticket');
Route::get('/ticket/{id}/buy', [HomeController::class, 'ticket_buy'])
    ->name('ticket.buy');

Route::get('/movie/{id}', [HomeController::class, 'movie'])->name('movie');
Route::get('/movie/{id}/buy', [HomeController::class, 'movie_buy'])
    ->name('movie.buy');

Route::redirect('home', '/');
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_action'])->name('login.action');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_store'])->name('register.store');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware([IsAdmin::class])
    ->group(function () {
    
        Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class);
        Route::resource('movies', \App\Http\Controllers\Admin\MovieController::class);
        Route::resource('hall-packages', \App\Http\Controllers\Admin\HallPackageController::class);

    });
