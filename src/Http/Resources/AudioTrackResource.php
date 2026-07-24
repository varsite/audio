<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Varsite\Audio\Models\AudioTrack;
use Varsite\Platform\Contracts\MediaLibrary;

/** @mixin AudioTrack */
final class AudioTrackResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        // Dane pliku WYŁĄCZNIE przez kontrakt Core (nie model Media). §0.8
        $media = app(MediaLibrary::class)->find($this->media_id);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status->value,
            'order' => $this->order,
            'category' => $this->category
                ? ['slug' => $this->category->slug, 'name' => $this->category->name]
                : null,
            'media' => $media === null ? null : [
                'id' => $media->id,
                'url' => $media->url,
                'mimeType' => $media->mimeType,
                'duration' => $media->duration,
            ],
            'published_at' => $this->published_at?->toIso8601String(),
        ];
    }
}
