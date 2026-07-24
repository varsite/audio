<?php

declare(strict_types=1);

namespace Varsite\Audio\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Varsite\Audio\Database\Factories\AudioCategoryFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $order
 */
final class AudioCategory extends Model
{
    /** @use HasFactory<AudioCategoryFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['order' => 'integer'];
    }

    /** @return HasMany<AudioTrack, $this> */
    public function tracks(): HasMany
    {
        return $this->hasMany(AudioTrack::class, 'category_id');
    }

    protected static function newFactory(): AudioCategoryFactory
    {
        return AudioCategoryFactory::new();
    }
}
