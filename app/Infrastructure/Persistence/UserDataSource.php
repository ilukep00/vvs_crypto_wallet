<?php

namespace App\Infrastructure\Persistence;

use App\Domain\User;
use Illuminate\Support\Facades\Cache;

class UserDataSource
{
    public function save(User $user): void
    {
        Cache::forever("user_" . $user->getId(), $user);
    }
}