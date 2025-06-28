<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/{slug}', function () {
    return view('invite');
})->name('invite.show');

Route::get('/', function () {
    return view('welcome');
});
