<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Language\ChangeLanguageController;
use App\Http\Controllers\Api\Place\DestroyPlaceController;
use App\Http\Controllers\Api\Place\IndexPlaceController;
use App\Http\Controllers\Api\Place\ShowPlaceController;
use App\Http\Controllers\Api\Place\StorePlaceController;
use App\Http\Controllers\Api\Profile\ShowProfileController;
use App\Http\Controllers\Api\Profile\UpdateEmailController;
use App\Http\Controllers\Api\Profile\UpdatePersonalDataController;
use App\Http\Controllers\Api\Review\IndexReviewsController;
use Illuminate\Support\Facades\Route;

Route::post('language/change', ChangeLanguageController::class)
    ->name('language.update');

Route::middleware(['auth'])
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::get('', ShowProfileController::class)->name('show');
        Route::put('personal-data', UpdatePersonalDataController::class)->name('personal_data.update');
        Route::put('email', UpdateEmailController::class)->name('email.update');
    });

Route::middleware(['auth'])
    ->prefix('places')
    ->name('places.')
    ->group(function () {
        Route::get('', IndexPlaceController::class)->name('index');
        Route::post('', StorePlaceController::class)
            ->middleware('throttle:places.store')
            ->name('store');
        Route::get('{place}', ShowPlaceController::class)
            ->middleware('can:view,place')
            ->name('show');
        Route::delete('{place}', DestroyPlaceController::class)
            ->middleware('can:delete,place')
            ->name('destroy');
    });

Route::middleware(['auth'])
    ->prefix('places/{place}/reviews')
    ->middleware('can:view,place')
    ->name('reviews.')
    ->group(function () {
        Route::get('', IndexReviewsController::class)->name('index');
    });
