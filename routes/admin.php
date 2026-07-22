<?php

declare(strict_types=1);

use Varsite\Platform\Routing\ScopedRoutes;
use Varsite\Audio\Http\Controllers\Admin\CategoryController;
use Varsite\Audio\Http\Controllers\Admin\SampleController;

/** Admin API Audio — auth:sanctum + RBAC (polityki). */
return static function (ScopedRoutes $r): void {
    $r->middleware(['auth:sanctum'])->prefix('api/v1/admin/audio')->group(function (ScopedRoutes $r): void {
        $r->post('samples/reorder', [SampleController::class, 'reorder']);
        $r->get('samples', [SampleController::class, 'index']);
        $r->post('samples', [SampleController::class, 'store']);
        $r->get('samples/{sample}', [SampleController::class, 'show']);
        $r->patch('samples/{sample}', [SampleController::class, 'update']);
        $r->delete('samples/{sample}', [SampleController::class, 'destroy']);

        $r->get('categories', [CategoryController::class, 'index']);
        $r->post('categories', [CategoryController::class, 'store']);
        $r->patch('categories/{category}', [CategoryController::class, 'update']);
        $r->delete('categories/{category}', [CategoryController::class, 'destroy']);
    });
};
