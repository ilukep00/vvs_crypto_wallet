<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;

class WalletDataSource
{
    public function searchWallet(string $walletId): Wallet|null
    {
        return new Wallet();
    }
    public function createWallet(string $userId): string|null
    {
        if (is_null(Cache::get($userId))) {
            return null;
        }

        // TODO

        return Cache::get($userId);
    }

    public function saveWallet(Wallet $wallet)
    {
    }
}
