<?php

declare(strict_types=1);

namespace App\Jobs\Map;

use App\Contracts\Repositories\PlaceParsingLogRepositoryContract;
use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Contracts\Services\Map\MapLogManagerContract;
use App\Contracts\Services\Map\MapWebHandlerContract;
use App\Contracts\Services\Map\ParseMapPlaceInfoContract;
use App\Contracts\Services\Map\ParseMapReviewsContract;
use App\DataTransferObjects\Place\UpdatePlaceDTO;
use App\DataTransferObjects\PlaceParsingLog\CreatePlaceParsingLogDTO;
use App\Enums\PlaceParsingLogStatus;
use App\Enums\PlaceParsingLogType;
use App\Enums\PlaceStatus;
use App\Models\Place;
use App\Models\PlaceParsingLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ParseMapPlace implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600;

    private PlaceParsingLogRepositoryContract $placeParsingLogRepository;

    private PlaceRepositoryContract $placeRepository;

    private MapWebHandlerContract $mapWebHandler;

    private MapLogManagerContract $mapLogManager;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Place $place,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->placeParsingLogRepository = app(PlaceParsingLogRepositoryContract::class);
        $this->placeRepository = app(PlaceRepositoryContract::class);
        $this->mapWebHandler = app(MapWebHandlerContract::class);
        $this->mapLogManager = app(MapLogManagerContract::class);

        $this->placeRepository->updateStatus($this->place->id, PlaceStatus::IN_PROCESS);

        $this->parsePlaceInfo();
        $this->parseReviews();

        $this->place->refresh();

        if ($this->place->status === PlaceStatus::IN_PROCESS_WITH_ERRORS) {
            $this->placeRepository->updateStatus($this->place->id, PlaceStatus::PROCESSED_WITH_ERRORS);
        } else {
            $this->placeRepository->updateStatus($this->place->id, PlaceStatus::PROCESSED);
        }
    }

    private function parsePlaceInfo(): void
    {
        $driver = $this->mapWebHandler->getDriver();
        $filePath = $this->mapLogManager->generatePlaceInfoContentFilePath($this->place);
        $log = $this->createPlaceParsingLog(PlaceParsingLogType::PARSE_PLACE, $filePath);

        try {
            $this->mapWebHandler->fetchPage($this->place);

            $this->mapLogManager->savePageContentIntoFile($driver, $filePath);

            app(ParseMapPlaceInfoContract::class)->handle($driver, $log);
        } catch (Throwable $e) {
            $this->mapLogManager->logException($log, $e);
        }

        $this->finishLogAction($log);
    }

    private function parseReviews(): void
    {
        $driver = $this->mapWebHandler->getDriver();

        for (; ;) {
            $parsedReviewsCount = 0;
            $fromReviewNumber = $this->mapWebHandler->getCurrentPageFirstReviewNumber();
            $toReviewNumber = $this->mapWebHandler->getCurrentPageLastReviewNumber();

            $filePath = $this->mapLogManager->generateReviewsContentFilePath($this->place, $fromReviewNumber, $toReviewNumber);

            $log = $this->createPlaceParsingLog(
                PlaceParsingLogType::PARSE_REVIEWS,
                $filePath,
                $fromReviewNumber,
                $toReviewNumber,
            );

            try {
                $this->mapLogManager->savePageContentIntoFile($driver, $filePath);

                $reviews = app(ParseMapReviewsContract::class)->handle($driver, $log);

                foreach ($reviews as $review) {
                    if ($review instanceof Throwable) {
                        $this->mapLogManager->logException($log, $review);
                    } else {
                        $parsedReviewsCount++;
                    }
                }

                $dto = UpdatePlaceDTO::fromArray([
                    'parsed_reviews_count' => ($this->place->parsed_reviews_count ?? 0) + $parsedReviewsCount,
                ]);

                $this->place = $this->placeRepository->update($this->place->id, $dto);
            } catch (Throwable $e) {
                $this->mapLogManager->logException($log, $e);
            }

            $this->finishLogAction($log);

            if (!$this->mapWebHandler->needNextReviews()) {
                break;
            }

            $this->mapWebHandler->fetchNextReviews();

            sleep(1);
        }
    }

    private function createPlaceParsingLog(PlaceParsingLogType $type, string $filePath, ?int $fromReviewNumber = null, ?int $toReviewNumber = null): PlaceParsingLog
    {
        $dto = new CreatePlaceParsingLogDTO(
            $this->place,
            PlaceParsingLogStatus::IN_PROCESS,
            $filePath,
            $type,
            $fromReviewNumber,
            $toReviewNumber
        );

        return $this->placeParsingLogRepository->create($dto);
    }

    private function finishLogAction(PlaceParsingLog $log): void
    {
        $log->refresh();

        if ($log->status === PlaceParsingLogStatus::IN_PROCESS_WITH_ERRORS) {
            $this->placeParsingLogRepository->updateStatus($log->id, PlaceParsingLogStatus::PROCESSED_WITH_ERRORS);
        } else {
            $this->placeParsingLogRepository->updateStatus($log->id, PlaceParsingLogStatus::PROCESSED);
        }
    }
}
