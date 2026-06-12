<?php

namespace App\Support;

use App\Models\User;

class OwnerContext
{
    public static function id(?User $user = null): ?int
    {
        $user ??= auth()->user();

        if (!$user) {
            return null;
        }

        return match ($user->role) {
            'admin' => null,
            'owner' => $user->id,
            default => $user->owner_id ?: null,
        };
    }

    public static function user(?User $user = null): ?User
    {
        $user ??= auth()->user();

        if (!$user) {
            return null;
        }

        if ($user->role === 'admin') {
            return null;
        }

        if ($user->role === 'owner') {
            return $user;
        }

        return $user->owner;
    }
}
