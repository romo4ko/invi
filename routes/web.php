<?php

declare(strict_types=1);

use App\Http\Controllers\InviteController;
use Illuminate\Support\Facades\Route;

Route::get('/{slug}', [InviteController::class, 'invite'])
    ->name('invite.show');

Route::post('/approve/{id}', [InviteController::class, 'approve'])
    ->name('invite.approve');


Route::get('/', function () {
    return view('welcome');
});
