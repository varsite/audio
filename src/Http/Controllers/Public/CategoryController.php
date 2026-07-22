<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Controllers\Public;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Varsite\Audio\Http\Resources\AudioCategoryResource;
use Varsite\Audio\Models\AudioCategory;

final class CategoryController
{
    public function index(): AnonymousResourceCollection
    {
        return AudioCategoryResource::collection(
            AudioCategory::query()->orderBy('order')->get()
        );
    }
}
