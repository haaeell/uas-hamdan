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

        return $user->role === 'admin'
            ? $user->id
            : ($user->owner_id ?: null);
    }

    public static function user(?User $user = null): ?User
    {
        $user ??= auth()->user();

        if (!$user) {
            return null;
        }

        if ($user->role === 'admin') {
            return $user;
        }

        return $user->owner;
    }
}
