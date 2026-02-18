<?php

declare(strict_types=1);

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\Place\CreatePlacePageController;
use App\Http\Controllers\Web\Place\ShowPlacePageController;
use App\Http\Controllers\Web\Profile\EmailPageController;
use App\Http\Controllers\Web\Profile\PersonalDataPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->middleware(['auth'])->name('home');

Route::middleware(['auth'])
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::get('personal-data', PersonalDataPageController::class)->name('personal_data.page');
        Route::get('email', EmailPageController::class)->name('email.page');
    });

Route::middleware(['auth'])
    ->prefix('places')
    ->name('places.')
    ->group(function () {
        Route::get('create', CreatePlacePageController::class)->name('create.page');
        Route::get('{place}', ShowPlacePageController::class)
            ->middleware('can:view,place')
            ->name('show.page');
    });

require base_path('routes/web/auth.php');
