<?php

declare(strict_types=1);

namespace Varsite\Audio\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Varsite\Audio\Enums\AudioTrackStatus;
use Varsite\Audio\Models\AudioTrack;

/**
 * Dane widgetów modułu Audio. Kontrakt odpowiedzi zależy od wariantu widgetu
 * (stat / list) i jest częścią deklaracji modułu — Core go nie interpretuje.
 */
final class StatsController
{
    /** Wariant "stat": liczba opublikowanych nagrań wraz ze zmianą 30-dniową. */
    public function published(): JsonResponse
    {
        $published = AudioTrack::query()->where('status', AudioTrackStatus::Published->value)->count();
        $lastMonth = AudioTrack::query()
            ->where('status', AudioTrackStatus::Published->value)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return response()->json(['data' => [
            'value' => $published,
            'delta' => $lastMonth,
            'hint' => $lastMonth > 0 ? sprintf('+%d w ostatnich 30 dniach', $lastMonth) : 'Bez zmian w ostatnich 30 dniach',
        ]]);
    }

    /** Wariant "list": ostatnio dodane nagrania. */
    public function recent(): JsonResponse
    {
        $items = AudioTrack::query()
            ->with('category')
            ->latest('id')
            ->limit(5)
            ->get()
            ->map(static fn (AudioTrack $track): array => [
                'title' => $track->title,
                'meta' => $track->category?->name ?? 'Bez kategorii',
                'href' => '/audio/tracks',
            ]);

        return response()->json(['data' => $items]);
    }
}
