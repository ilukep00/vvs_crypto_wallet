<?php

namespace App\Infrastructure\Persistence;

use Illuminate\Support\Facades\Cache;

class WalletDataSource
{
    public function createWallet(string $userId): string|null
    {
        if (is_null(Cache::get($userId))) {
            return null;
        }

        // TODO

        return Cache::get($userId);
    }
}
