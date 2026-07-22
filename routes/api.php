<?php

declare(strict_types=1);

use Varsite\Platform\Routing\ScopedRoutes;
use Varsite\Audio\Http\Controllers\Public\CategoryController;
use Varsite\Audio\Http\Controllers\Public\SampleController;

/** Publiczne API Audio — tylko dane opublikowane (cache/ETag). Bez auth. */
return static function (ScopedRoutes $r): void {
    $r->prefix('api/v1/audio')->group(function (ScopedRoutes $r): void {
        $r->get('samples', [SampleController::class, 'index']);
        $r->get('samples/{slug}', [SampleController::class, 'show']);
        $r->get('categories', [CategoryController::class, 'index']);
    });
};
