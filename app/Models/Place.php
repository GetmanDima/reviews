<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PlaceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property PlaceStatus $status
 * @property string $map_id
 * @property string $url
 * @property string|null $name
 * @property float|null $rating
 * @property-read int|null $reviews_count
 * @property int|null $parsed_reviews_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\PlaceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereParsedReviewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereReviewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Place withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Place extends Model
{
    /** @use HasFactory<\Database\Factories\PlaceFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'map_id',
        'url',
        'name',
        'rating',
        'reviews_count',
        'parsed_reviews_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PlaceStatus::class,
            'rating' => 'float',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'place_id');
    }
}
