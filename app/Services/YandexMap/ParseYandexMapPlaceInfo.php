<?php

declare(strict_types=1);

namespace App\Services\YandexMap;

use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Contracts\Services\Map\ParseMapPlaceInfoContract;
use App\DataTransferObjects\Place\UpdatePlaceDTO;
use App\Exceptions\Map\NameParsingException;
use App\Exceptions\Map\RatingParsingException;
use App\Exceptions\Map\ReviewsCountParsingException;
use App\Models\Place;
use App\Models\PlaceParsingLog;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class ParseYandexMapPlaceInfo implements ParseMapPlaceInfoContract
{
    public function __construct(
        private readonly PlaceRepositoryContract $placeRepository,
    ) {}

    public function handle(RemoteWebDriver $driver, PlaceParsingLog $log): Place
    {
        $ratingElement = $driver->findElement(
            WebDriverBy::cssSelector('.business-summary-rating-badge-view__rating')
        );

        $ratingInnerElements = $ratingElement->findElements(
            WebDriverBy::cssSelector('.business-summary-rating-badge-view__rating-text')
        );

        $wholePart = '';
        $fractionalPart = '';

        if (isset($ratingInnerElements[0]) && isset($ratingInnerElements[2])) {
            $wholePart = $ratingInnerElements[0]->getText();
            $fractionalPart = $ratingInnerElements[2]->getText();
        }

        if ($wholePart === '' || $fractionalPart === '') {
            throw new RatingParsingException($log->id);
        }

        $rating = floatval($wholePart.'.'.$fractionalPart);

        $nameElement = $driver->findElement(
            WebDriverBy::cssSelector('.orgpage-header-view__header')
        );
        $name = $nameElement->getText();

        if (!$name) {
            throw new NameParsingException($log->id);
        }

        $reviewsCountElement = $driver->findElement(
            WebDriverBy::cssSelector('.card-section-header__title')
        );
        $reviewsCountString = explode(' ', $reviewsCountElement->getText())[0];

        if ($reviewsCountString === '') {
            throw new ReviewsCountParsingException($log->id);
        }

        $reviewsCount = intval($reviewsCountString);

        $dto = new UpdatePlaceDTO(
            $name,
            $rating,
            $reviewsCount
        );

        $place = $this->placeRepository->update($log->place_id, $dto);

        return $place;
    }
}
