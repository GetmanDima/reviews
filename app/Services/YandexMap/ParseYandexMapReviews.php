<?php

declare(strict_types=1);

namespace App\Services\YandexMap;

use App\Contracts\Repositories\ReviewRepositoryContract;
use App\Contracts\Services\Map\ParseMapReviewsContract;
use App\DataTransferObjects\Review\CreateReviewDTO;
use App\Exceptions\Map\PublishedAtParsingException;
use App\Models\PlaceParsingLog;
use App\Models\Review;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Generator;
use Illuminate\Support\Carbon;
use Throwable;

class ParseYandexMapReviews implements ParseMapReviewsContract
{
    public function __construct(
        private readonly ReviewRepositoryContract $reviewRepository,
    ) {}

    public function handle(RemoteWebDriver $driver, PlaceParsingLog $log): Generator
    {
        $reviewElements = [];

        for ($i = $log->from_review; $i <= $log->to_review; $i++) {
            try {
                $reviewSelector = '[aria-posinset="'.$i.'"]';
                $reviewElements[] = $driver->findElement(WebDriverBy::cssSelector($reviewSelector));

                $clickExpandTextButtonScript = 'document.querySelector(\''.$reviewSelector.' .business-review-view__expand\')?.click()';
                $driver->executeScript($clickExpandTextButtonScript);
            } catch (NoSuchElementException $e) {
                break;
            }
        }

        foreach ($reviewElements as $reviewElement) {
            try {
                $review = $this->parseSingleReview($reviewElement, $log);

                yield $review;
            } catch (Throwable $e) {
                yield $e;
            }
        }
    }

    private function parseSingleReview(RemoteWebElement $reviewElement, PlaceParsingLog $log): Review
    {
        $nameElement = $reviewElement->findElement(
            WebDriverBy::cssSelector('[itemprop="author"] [itemprop="name"]')
        );
        $name = $nameElement->getText();

        $rank = null;

        try {
            $rankElement = $reviewElement->findElement(
                WebDriverBy::cssSelector('.business-review-view__author-caption')
            );
            $rank = $rankElement->getText();
        } catch (NoSuchElementException $e) {
        }

        $imageElement = $reviewElement->findElement(
            WebDriverBy::cssSelector('.user-icon-view__icon')
        );
        /**
         * @var string
         */
        $imageStyleValue = $imageElement->getAttribute('style') ?? '';
        preg_match('/background-image:\s*url\(["\']?([^"\'\)]+)["\']?\)/', $imageStyleValue, $matches);

        $image = null;

        if (!empty($matches[1])) {
            $image = $matches[1];
        }

        $rating = 0;

        try {
            $ratingElement = $reviewElement->findElement(
                WebDriverBy::cssSelector('[itemprop="reviewRating"] [itemprop="ratingValue"]')
            );

            $rating = floatval($ratingElement->getAttribute('content'));
        } catch (NoSuchElementException $e) {
        }

        $publishedAtElement = $reviewElement->findElement(
            WebDriverBy::cssSelector('[itemprop="datePublished"]')
        );
        /**
         * @var string
         */
        $publishedAtString = $publishedAtElement->getAttribute('content') ?? '';
        $publishedAtString = str_replace('T', ' ', explode('.', $publishedAtString)[0]);

        if ($publishedAtString === '') {
            throw new PublishedAtParsingException($log->id);
        }

        $publishedAt = (new Carbon($publishedAtString))->toDateTimeString();

        $textElement = $reviewElement->findElement(
            WebDriverBy::cssSelector('.spoiler-view__text-container')
        );
        $text = $textElement->getText();

        $dto = CreateReviewDTO::fromArray([
            'place_id' => $log->place_id,
            'image' => $image,
            'name' => $name,
            'rank' => $rank,
            'rating' => $rating,
            'published_at' => $publishedAt,
            'text' => $text,
        ]);

        return $this->reviewRepository->create($dto);
    }
}
