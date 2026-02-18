<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PlaceParsingLogStatus;
use App\Enums\PlaceParsingLogType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $place_id
 * @property PlaceParsingLogStatus $status
 * @property string $file_path
 * @property PlaceParsingLogType $type
 * @property int|null $from_review
 * @property int|null $to_review
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Place $place
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereFromReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog wherePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereToReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlaceParsingLog withoutTrashed()
 *
 * @mixin \Eloquent
 */
class PlaceParsingLog extends Model
{
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'place_id',
        'status',
        'file_path',
        'type',
        'from_review',
        'to_review',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PlaceParsingLogStatus::class,
            'type' => PlaceParsingLogType::class,
        ];
    }

    /**
     * @return BelongsTo<Place, $this>
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
