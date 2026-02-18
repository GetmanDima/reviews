<?php

declare(strict_types=1);

use App\Http\Controllers\Web\Auth\LoginPageController;
use App\Http\Controllers\Web\Auth\RegisterPageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])
    ->group(function () {
        Route::get('register', RegisterPageController::class)->name('register.page');
        Route::get('login', LoginPageController::class)->name('login.page');
    });
