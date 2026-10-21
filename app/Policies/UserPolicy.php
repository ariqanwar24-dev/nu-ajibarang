<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->isActive() && $actor->isAdmin();
    }

    public function approve(User $actor, User $target): bool
    {
        return $this->canReview($actor, $target);
    }

    public function reject(User $actor, User $target): bool
    {
        return $this->canReview($actor, $target);
    }

    public function updateRole(User $actor, User $target): bool
    {
        return $actor->isActive()
            && $actor->isSuperAdmin()
            && ! $actor->is($target);
    }

    private function canReview(User $actor, User $target): bool
    {
        if (! $actor->isActive() || $actor->is($target)) {
            return false;
        }

        if (! $target->isPending()) {
            return false;
        }

        if ($actor->isSuperAdmin()) {
            return true;
        }

        return $actor->isAdmin() && $target->role === User::ROLE_USER;
    }
}
