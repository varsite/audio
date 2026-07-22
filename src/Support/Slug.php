<?php

declare(strict_types=1);

namespace Varsite\Audio\Support;

use Illuminate\Support\Facades\DB;

/** Generuje unikalny slug (sprawdza wszystkie wiersze, także miękko usunięte). */
final class Slug
{
    public static function unique(string $value, string $table): string
    {
        $base = str($value)->slug()->value() ?: 'item';
        $slug = $base;
        $i = 1;

        while (DB::table($table)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
