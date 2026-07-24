<?php

declare(strict_types=1);

namespace Varsite\Audio\Enums;

/** Status publikacji nagrania. (Planowanie z datą — poza zakresem Fazy 2, JIT.) */
enum AudioTrackStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Hidden = 'hidden';
}
