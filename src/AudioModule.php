<?php

declare(strict_types=1);

namespace Varsite\Audio;

use Varsite\Platform\Contracts\ModuleManifest;
use Varsite\Platform\Contracts\PlatformModule;

final class AudioModule implements PlatformModule
{
    public function manifest(): ModuleManifest
    {
        return new ModuleManifest(
            key: 'audio',
            name: 'Audio',
            version: '1.0.0',
            description: 'Nagrania audio z publicznym API dla frontendu klienta.',
            author: 'Varsite',
            section: 'content',
            icon: 'audio-lines',
            order: 20,
            permissions: [
                'audio.view',
                'audio.create',
                'audio.update',
                'audio.delete',
                'audio.category.manage',
            ],
            requiresGeneration: '^0.6',
        );
    }
}
