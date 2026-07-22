<?php

declare(strict_types=1);

namespace Varsite\Audio\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;

final class AudioCategoryPolicy
{
    public function viewAny(Authorizable $user): bool
    {
        return $user->can('audio.view');
    }

    public function create(Authorizable $user): bool
    {
        return $user->can('audio.category.manage');
    }

    public function update(Authorizable $user): bool
    {
        return $user->can('audio.category.manage');
    }

    public function delete(Authorizable $user): bool
    {
        return $user->can('audio.category.manage');
    }
}
