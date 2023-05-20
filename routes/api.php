<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::any('/github/webhook/{github_key}', function ($github_key) {
    if($github_key && $github_key === env('GITHUB_WEBHOOK_KEY'))
    {
        dump(shell_exec('git pull'));
        dump(shell_exec('composer update'));
        dump(shell_exec('php artisan migrate --force'));
        return 1;
    }
    return abort(404);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
