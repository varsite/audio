<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Varsite\Audio\Http\Requests\StoreCategoryRequest;
use Varsite\Audio\Http\Requests\UpdateCategoryRequest;
use Varsite\Audio\Http\Resources\AudioCategoryResource;
use Varsite\Audio\Models\AudioCategory;
use Varsite\Audio\Support\Slug;

final class CategoryController
{
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', AudioCategory::class);

        return AudioCategoryResource::collection(AudioCategory::query()->orderBy('order')->get());
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        Gate::authorize('create', AudioCategory::class);

        $category = AudioCategory::create([
            ...$request->validated(),
            'slug' => Slug::unique($request->string('name')->value(), 'audio_categories'),
        ]);

        return AudioCategoryResource::make($category)->response()->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function update(UpdateCategoryRequest $request, AudioCategory $category): AudioCategoryResource
    {
        Gate::authorize('update', $category);

        $category->update($request->validated());

        return AudioCategoryResource::make($category);
    }

    public function destroy(AudioCategory $category): Response
    {
        Gate::authorize('delete', $category);

        $category->delete();

        return response()->noContent();
    }
}
