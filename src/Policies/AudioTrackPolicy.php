<?php

declare(strict_types=1);

namespace Varsite\Audio\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;

/** RBAC nagrań. Typ-hint kontraktu Authorizable (bez zależności od User z Core). */
final class AudioTrackPolicy
{
    public function viewAny(Authorizable $user): bool
    {
        return $user->can('audio.view');
    }

    public function view(Authorizable $user): bool
    {
        return $user->can('audio.view');
    }

    public function create(Authorizable $user): bool
    {
        return $user->can('audio.create');
    }

    public function update(Authorizable $user): bool
    {
        return $user->can('audio.update');
    }

    public function delete(Authorizable $user): bool
    {
        return $user->can('audio.delete');
    }

    public function reorder(Authorizable $user): bool
    {
        return $user->can('audio.update');
    }
}
