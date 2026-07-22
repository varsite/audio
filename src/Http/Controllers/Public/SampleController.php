<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Controllers\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Varsite\Audio\Http\Resources\AudioSampleResource;
use Varsite\Audio\Models\AudioSample;

final class SampleController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AudioSample::query()->published()->with('category')->orderBy('order');

        if ($slug = $request->string('category')->toString()) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $slug));
        }

        return AudioSampleResource::collection($query->paginate((int) config('audio.per_page')));
    }

    public function show(string $slug): AudioSampleResource
    {
        $sample = AudioSample::query()->published()->with('category')->where('slug', $slug)->firstOrFail();

        return AudioSampleResource::make($sample);
    }
}
