<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsNotAdmin;
use App\Http\Middleware\PhoneVerified;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/after-login', [HomeController::class, 'after_login'])
    ->middleware([PhoneVerified::class]);

Route::get('/verify/phone', [HomeController::class, 'verify_phone'])
    ->name('verify.phone')
    ->middleware(['auth']);
Route::post('/verify/phone', [HomeController::class, 'verify_phone'])
    ->middleware(['auth']);

Route::get('reset/password/{code}', [HomeController::class, 'reset_pass'])->name('reset.password');
Route::post('reset/password/{code}/submit', [HomeController::class, 'reset_pass_submit'])->name('reset.password.submit');
Route::get('/forgot/password', [HomeController::class, 'forgot_pass'])->name('forgot.password');
Route::get('/verify-tickets', [HomeController::class, 'verify_tickets'])->name('verify-tickets');
Route::post('/verify-tickets', [HomeController::class, 'verify_tickets_submit']);
Route::get('/verify-tickets/{code}', [HomeController::class, 'verify_tickets_list'])        
    ->name('verify-tickets.list');
Route::get('/my-tickets', [HomeController::class, 'my_tickets'])
    ->name('my-tickets')
    ->middleware(['auth', PhoneVerified::class]);

Route::get('/bkash/callback', [BkashController::class, 'bkash_callback'])
    ->name('bkash.callback');

Route::get('/ticket/{id}', [HomeController::class, 'ticket'])->name('ticket');
Route::get('/ticket/{id}/download', [HomeController::class, 'ticket_download'])
    ->name('ticket.download');
Route::get('/ticket/{id}/buy', [HomeController::class, 'ticket_buy'])
    ->name('ticket.buy')
    ->middleware([IsNotAdmin::class, PhoneVerified::class]);

Route::get('/movie/{id}', [HomeController::class, 'movie'])->name('movie');
Route::get('/movie/{id}/buy', [HomeController::class, 'movie_buy'])
    ->name('movie.buy')
    ->middleware([IsNotAdmin::class, PhoneVerified::class]);
Route::get('/movie/{id}/download', [HomeController::class, 'movie_download'])
    ->name('movie.download');

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
    
        Route::get('reports/update-status', [\App\Http\Controllers\Admin\HomeController::class, 'reports_update_status'])
            ->name('reports.update-status');
        Route::get('reports/{category?}', [\App\Http\Controllers\Admin\HomeController::class, 'reports'])->name('reports');
        Route::delete('reports/movie/{id}', [\App\Http\Controllers\Admin\HomeController::class, 'reports_movie_delete'])->name('reports.delete.movie');
        Route::delete('reports/ticket/{id}', [\App\Http\Controllers\Admin\HomeController::class, 'reports_ticket_delete'])->name('reports.delete.ticket');
        Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class);
        Route::resource('movies', \App\Http\Controllers\Admin\MovieController::class);
        Route::resource('hall-packages', \App\Http\Controllers\Admin\HallPackageController::class);

    });
Route::get('get_movie_empty_seats/{id}/{time_slot}/{hall}', [HomeController::class, 'get_movie_empty_seats']);