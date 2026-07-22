<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Varsite\Audio\Models\AudioCategory;

/** @mixin AudioCategory */
final class AudioCategoryResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'order' => $this->order,
        ];
    }
}
