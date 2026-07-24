<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Varsite\Audio\Http\Requests\ReorderTracksRequest;
use Varsite\Audio\Http\Requests\StoreTrackRequest;
use Varsite\Audio\Http\Requests\UpdateTrackRequest;
use Varsite\Audio\Http\Resources\AudioTrackResource;
use Varsite\Audio\Models\AudioTrack;
use Varsite\Audio\Support\Slug;

final class TrackController
{
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', AudioTrack::class);

        return AudioTrackResource::collection(
            AudioTrack::query()->with('category')->orderBy('order')->paginate((int) config('audio.per_page'))
        );
    }

    public function store(StoreTrackRequest $request): JsonResponse
    {
        Gate::authorize('create', AudioTrack::class);

        $track = AudioTrack::create([
            ...$request->validated(),
            'slug' => Slug::unique($request->string('title')->value(), 'audio_tracks'),
            'order' => (int) AudioTrack::max('order') + 1,
            'created_by' => $request->user()?->getAuthIdentifier(),
        ]);

        return AudioTrackResource::make($track)->response()->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function show(AudioTrack $track): AudioTrackResource
    {
        Gate::authorize('view', $track);

        return AudioTrackResource::make($track->load('category'));
    }

    public function update(UpdateTrackRequest $request, AudioTrack $track): AudioTrackResource
    {
        Gate::authorize('update', $track);

        $track->update($request->validated());

        return AudioTrackResource::make($track->load('category'));
    }

    public function destroy(AudioTrack $track): Response
    {
        Gate::authorize('delete', $track);

        $track->delete();

        return response()->noContent();
    }

    public function reorder(ReorderTracksRequest $request): Response
    {
        Gate::authorize('reorder', AudioTrack::class);

        foreach (array_values($request->array('ids')) as $position => $id) {
            AudioTrack::whereKey($id)->update(['order' => $position]);
        }

        return response()->noContent();
    }
}
