<?php

declare(strict_types=1);

namespace Varsite\Audio\Enums;

/** Status publikacji próbki. (Planowanie z datą — poza zakresem Fazy 2, JIT.) */
enum AudioSampleStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Hidden = 'hidden';
}
