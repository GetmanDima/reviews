<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\Repositories\PlaceRepositoryContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\DataTransferObjects\Place\CreatePlaceDTO;
use App\Jobs\Map\ParseMapPlace;
use App\Models\Place;
use App\ValueObjects\Place\PlaceUrl;
use Illuminate\Console\Command;

class RunPlaceParsing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'place:parse {user} {placeUrl}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run map place parsing by url';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Start parsing');

        try {
            $this->info('Creating place');

            $place = $this->createPlace();

            $this->info('Run parsing job');

            ParseMapPlace::dispatch($place);

            $this->info('Parsing job is running');
        } catch (\Exception $e) {
            $this->error("An error occurred while parsing: {$e->getMessage()}");
        }
    }

    private function createPlace(): Place
    {
        $userId = intval($this->argument('user'));
        $placeUrlString = $this->argument('placeUrl');

        $user = app(UserRepositoryContract::class)->findOrFail($userId);
        $placeUrl = new PlaceUrl($placeUrlString);

        $dto = CreatePlaceDTO::fromArray([
            'user_id' => $user->id,
            'url' => $placeUrl->value,
        ]);

        return app(PlaceRepositoryContract::class)->create($dto);
    }
}
