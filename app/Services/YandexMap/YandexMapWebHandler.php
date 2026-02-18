<?php

declare(strict_types=1);

namespace App\Services\YandexMap;

use App\Contracts\Services\Map\MapWebHandlerContract;
use App\Models\Place;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Throwable;

class YandexMapWebHandler implements MapWebHandlerContract
{
    private const SELENIUM_URL = 'http://selenium:4444/wd/hub';

    private const REVIEWS_PER_PAGE = 50;

    private int $currentPageLastReviewNumber = 0;

    private RemoteWebDriver $driver;

    public function __construct()
    {
        $options = new ChromeOptions;
        $options->addArguments([
            '--headless',
            '--disable-gpu',
            '--no-sandbox',
            '--window-size=1920,1080',
        ]);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = RemoteWebDriver::create(self::SELENIUM_URL, desired_capabilities: $capabilities);

        app()->terminating(function () {
            $this->driver->quit();
        });
    }

    public function getDriver(): RemoteWebDriver
    {
        return $this->driver;
    }

    public function fetchPage(Place $place): void
    {
        $this->driver->get($place->url);

        $this->driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('[data-chunk="reviews"]')
            )
        );

        // Sort by date if possible
        try {
            $clickSortScript = "document.querySelector('.rating-ranking-view').click()";
            $this->driver->executeScript($clickSortScript);

            $this->driver->wait(5)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(
                    WebDriverBy::cssSelector('.rating-ranking-view__popup')
                )
            );

            $clickSortByDateScript = "document.querySelector('.rating-ranking-view__popup :nth-child(2)').click()";
            $this->driver->executeScript($clickSortByDateScript);

            sleep(2);
        } catch (Throwable $e) {
            // Can continue parsing even if reviews not sorted on page
        }

        $this->currentPageLastReviewNumber = self::REVIEWS_PER_PAGE;
    }

    public function needNextReviews(): bool
    {
        try {
            $this->driver->findElement(WebDriverBy::cssSelector('[aria-posinset="'.$this->currentPageLastReviewNumber.'"]'));

            return true;
        } catch (NoSuchElementException $e) {
            return false;
        }
    }

    public function fetchNextReviews(): void
    {
        $scrollReviewsScript = "document.querySelector('.scroll__container').scrollTo({top: document.querySelector('.scroll__container').scrollHeight})";
        $this->driver->executeScript($scrollReviewsScript);

        $this->driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(
                WebDriverBy::cssSelector('[aria-posinset="'.($this->currentPageLastReviewNumber + 1).'"]')
            )
        );

        $this->currentPageLastReviewNumber += self::REVIEWS_PER_PAGE;
    }

    public function getCurrentPageFirstReviewNumber(): int
    {
        return $this->currentPageLastReviewNumber - self::REVIEWS_PER_PAGE + 1;
    }

    public function getCurrentPageLastReviewNumber(): int
    {
        return $this->currentPageLastReviewNumber;
    }
}
