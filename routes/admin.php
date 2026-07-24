<?php

declare(strict_types=1);

use Varsite\Platform\Routing\ScopedRoutes;
use Varsite\Audio\Http\Controllers\Admin\CategoryController;
use Varsite\Audio\Http\Controllers\Admin\StatsController;
use Varsite\Audio\Http\Controllers\Admin\TrackController;

/** Admin API Audio — auth:sanctum + RBAC (polityki). */
return static function (ScopedRoutes $r): void {
    $r->middleware(['auth:sanctum'])->prefix('api/v1/admin/audio')->group(function (ScopedRoutes $r): void {
        $r->post('tracks/reorder', [TrackController::class, 'reorder']);
        $r->get('tracks', [TrackController::class, 'index']);
        $r->post('tracks', [TrackController::class, 'store']);
        $r->get('tracks/{track}', [TrackController::class, 'show']);
        $r->patch('tracks/{track}', [TrackController::class, 'update']);
        $r->delete('tracks/{track}', [TrackController::class, 'destroy']);

        $r->get('stats/published', [StatsController::class, 'published']);
        $r->get('stats/recent', [StatsController::class, 'recent']);

        $r->get('categories', [CategoryController::class, 'index']);
        $r->post('categories', [CategoryController::class, 'store']);
        $r->patch('categories/{category}', [CategoryController::class, 'update']);
        $r->delete('categories/{category}', [CategoryController::class, 'destroy']);
    });
};
