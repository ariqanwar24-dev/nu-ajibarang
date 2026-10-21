<?php

namespace App\Policies;

use App\Models\Pengurus;
use App\Models\User;

class PengurusPolicy
{
    public function viewAny(User $actor): bool
    {
        return $this->canManage($actor);
    }

    public function create(User $actor): bool
    {
        return $this->canManage($actor);
    }

    public function update(User $actor, Pengurus $pengurus): bool
    {
        return $this->canManage($actor);
    }

    public function delete(User $actor, Pengurus $pengurus): bool
    {
        return $this->canManage($actor);
    }

    private function canManage(User $actor): bool
    {
        return $actor->isActive() && $actor->isAdmin();
    }
}
