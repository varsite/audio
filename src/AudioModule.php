<?php

declare(strict_types=1);

namespace Varsite\Audio;

use Varsite\Platform\Contracts\PlatformModule;

/** Deklaracja modułu Audio (próbki lektorskie). Autonomiczny; zależy tylko od Core. */
final class AudioModule implements PlatformModule
{
    public function key(): string
    {
        return 'audio';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    /** @return array<int, string> */
    public function permissions(): array
    {
        return [
            'audio.view',
            'audio.create',
            'audio.update',
            'audio.delete',
            'audio.category.manage',
        ];
    }
}
