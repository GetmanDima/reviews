<?php

declare(strict_types=1);

namespace App\Services\YandexMap;

use App\Contracts\Repositories\PlaceParsingLogRepositoryContract;
use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Contracts\Services\Map\MapLogManagerContract;
use App\Enums\PlaceParsingLogStatus;
use App\Enums\PlaceParsingLogType;
use App\Enums\PlaceStatus;
use App\Exceptions\BusinessException;
use App\Models\Place;
use App\Models\PlaceParsingLog;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class YandexMapLogManager implements MapLogManagerContract
{
    public function __construct(
        private readonly PlaceParsingLogRepositoryContract $placeParsingLogRepository,
        private readonly PlaceRepositoryContract $placeRepository,
    ) {}

    public function generatePlaceInfoContentFilePath(Place $place): string
    {
        return "yandex_map_pages/{$place->id}/place_info_{$place->id}_".now()->format('Y-m-d_H-i-s').'.html';
    }

    public function generateReviewsContentFilePath(Place $place, int $fromReviewNumber, int $toReviewNumber): string
    {
        return "yandex_map_pages/{$place->id}/place_reviews_{$place->id}_from_{$fromReviewNumber}_to_{$toReviewNumber}_".now()->format('Y-m-d_H-i-s').'.html';
    }

    public function savePageContentIntoFile(RemoteWebDriver $driver, string $filePath): void
    {
        $html = $driver->getPageSource();
        Storage::put($filePath, $html);
    }

    public function logException(PlaceParsingLog $log, Throwable $e): void
    {
        $privateLogger = Log::build([
            'driver' => 'single',
            'path' => $this->getPrivateLogFilePath($log->place_id),
        ]);

        $publicLogger = Log::build([
            'driver' => 'single',
            'path' => $this->getPublicLogFilePath($log->place_id),
        ]);

        if ($e instanceof BusinessException) {
            $privateLogger->error($e->getMessage(), [
                'place_parsing_log_id' => $log->id,
                'place_id' => $log->place_id,
            ]);

            $publicLogger->error($e->getMessage(), [
                'place_id' => $log->place_id,
            ]);

            return;
        }

        $privateLogger->error($e->getMessage(), [
            'place_parsing_log_id' => $log->id,
            'place_id' => $log->place_id,
            'trace' => $e->getTraceAsString(),
        ]);

        if ($log->type === PlaceParsingLogType::PARSE_PLACE) {
            $publicLogger->error('Unknown error when parsed place info');
        } else {
            $publicLogger->error('Unknown error when parsed place reviews from {$log->from_review} to {$log->to_review}');
        }

        $this->placeParsingLogRepository->updateStatus($log->id, PlaceParsingLogStatus::IN_PROCESS_WITH_ERRORS);
        $this->placeRepository->updateStatus($log->place_id, PlaceStatus::IN_PROCESS_WITH_ERRORS);
    }

    public function getPublicLogFilePath(int $placeId): string
    {
        return Storage::disk('public')->path("logs/yandex_map/place_parsing_{$placeId}.log");
    }

    private function getPrivateLogFilePath(int $placeId): string
    {
        return storage_path("logs/yandex_map/place_parsing_{$placeId}.log");
    }
}
