<?php

declare(strict_types=1);

namespace Varsite\Audio\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Varsite\Audio\Enums\AudioTrackStatus;
use Varsite\Audio\Models\AudioCategory;
use Varsite\Audio\Models\AudioTrack;

/** @extends Factory<AudioTrack> */
final class AudioTrackFactory extends Factory
{
    protected $model = AudioTrack::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => rtrim($title, '.'),
            'slug' => str($title)->slug()->value(),
            'category_id' => AudioCategory::factory(),
            'description' => $this->faker->optional()->paragraph(),
            'media_id' => $this->faker->numberBetween(1, 1000),
            'order' => 0,
            'status' => AudioTrackStatus::Published,
            'published_at' => now(),
            'created_by' => null,
            'meta' => [],
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => AudioTrackStatus::Draft, 'published_at' => null]);
    }

    public function hidden(): static
    {
        return $this->state(['status' => AudioTrackStatus::Hidden]);
    }
}
