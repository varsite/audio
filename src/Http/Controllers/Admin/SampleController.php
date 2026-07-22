<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Varsite\Audio\Http\Requests\ReorderSamplesRequest;
use Varsite\Audio\Http\Requests\StoreSampleRequest;
use Varsite\Audio\Http\Requests\UpdateSampleRequest;
use Varsite\Audio\Http\Resources\AudioSampleResource;
use Varsite\Audio\Models\AudioSample;
use Varsite\Audio\Support\Slug;

final class SampleController
{
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', AudioSample::class);

        return AudioSampleResource::collection(
            AudioSample::query()->with('category')->orderBy('order')->paginate((int) config('audio.per_page'))
        );
    }

    public function store(StoreSampleRequest $request): JsonResponse
    {
        Gate::authorize('create', AudioSample::class);

        $sample = AudioSample::create([
            ...$request->validated(),
            'slug' => Slug::unique($request->string('title')->value(), 'audio_samples'),
            'order' => (int) AudioSample::max('order') + 1,
            'created_by' => $request->user()?->getAuthIdentifier(),
        ]);

        return AudioSampleResource::make($sample)->response()->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function show(AudioSample $sample): AudioSampleResource
    {
        Gate::authorize('view', $sample);

        return AudioSampleResource::make($sample->load('category'));
    }

    public function update(UpdateSampleRequest $request, AudioSample $sample): AudioSampleResource
    {
        Gate::authorize('update', $sample);

        $sample->update($request->validated());

        return AudioSampleResource::make($sample->load('category'));
    }

    public function destroy(AudioSample $sample): Response
    {
        Gate::authorize('delete', $sample);

        $sample->delete();

        return response()->noContent();
    }

    public function reorder(ReorderSamplesRequest $request): Response
    {
        Gate::authorize('reorder', AudioSample::class);

        foreach (array_values($request->array('ids')) as $position => $id) {
            AudioSample::whereKey($id)->update(['order' => $position]);
        }

        return response()->noContent();
    }
}
