<?php

declare(strict_types=1);

namespace Varsite\Audio\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Varsite\Audio\Models\AudioCategory;

/** @extends Factory<AudioCategory> */
final class AudioCategoryFactory extends Factory
{
    protected $model = AudioCategory::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug()->value(),
            'order' => 0,
        ];
    }
}
