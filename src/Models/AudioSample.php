<?php

declare(strict_types=1);

namespace Varsite\Audio\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Varsite\Audio\Database\Factories\AudioSampleFactory;
use Varsite\Audio\Enums\AudioSampleStatus;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int|null $category_id
 * @property string|null $description
 * @property int $media_id
 * @property int $order
 * @property AudioSampleStatus $status
 * @property Carbon|null $published_at
 * @property array<string, mixed> $meta
 */
final class AudioSample extends Model
{
    /** @use HasFactory<AudioSampleFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ['id'];

    /** @var array<string, mixed> */
    protected $attributes = ['status' => 'draft'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'media_id' => 'integer',
            'order' => 'integer',
            'status' => AudioSampleStatus::class,
            'published_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<AudioCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AudioCategory::class, 'category_id');
    }

    /** @param Builder<AudioSample> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', AudioSampleStatus::Published);
    }

    protected static function newFactory(): AudioSampleFactory
    {
        return AudioSampleFactory::new();
    }
}
