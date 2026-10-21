<?php

namespace App\Policies;

use App\Models\Berita;
use App\Models\User;

class BeritaPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->isActive() && $actor->isAdmin();
    }

    public function create(User $actor): bool
    {
        return $actor->isActive() && $actor->isAdmin();
    }

    public function update(User $actor, Berita $berita): bool
    {
        return $this->canManage($actor, $berita);
    }

    public function delete(User $actor, Berita $berita): bool
    {
        return $this->canManage($actor, $berita);
    }

    public function preview(User $actor, Berita $berita): bool
    {
        return $this->canManage($actor, $berita);
    }

    public function review(User $actor, Berita $berita): bool
    {
        return $this->publish($actor, $berita);
    }

    /**
     * Hanya super admin aktif yang boleh menerbitkan berita yang sudah diajukan.
     */
    public function publish(User $actor, Berita $berita): bool
    {
        return $actor->isActive()
            && $actor->isSuperAdmin()
            && $berita->isPending();
    }

    private function canManage(User $actor, Berita $berita): bool
    {
        if (! $actor->isActive() || ! $actor->isAdmin()) {
            return false;
        }

        if ($actor->isSuperAdmin()) {
            return true;
        }

        return $berita->user_id !== null
            && (int) $berita->user_id === (int) $actor->id;
    }
}
