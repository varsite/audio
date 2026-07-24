<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Controllers\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Varsite\Audio\Http\Resources\AudioTrackResource;
use Varsite\Audio\Models\AudioTrack;

final class TrackController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AudioTrack::query()->published()->with('category')->orderBy('order');

        if ($slug = $request->string('category')->toString()) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $slug));
        }

        return AudioTrackResource::collection($query->paginate((int) config('audio.per_page')));
    }

    public function show(string $slug): AudioTrackResource
    {
        $track = AudioTrack::query()->published()->with('category')->where('slug', $slug)->firstOrFail();

        return AudioTrackResource::make($track);
    }
}
