<?php

namespace App\Infrastructure\Persistence;

use Illuminate\Support\Facades\Cache;
use App\Domain\Wallet;

class WalletDataSource
{
    public function createWallet(string $userId): string|null
    {
        if (is_null(Cache::get($userId))) {
            return null;
        }

        $user = Cache::get($userId);

        $newWallet = $user->newWallet();

        if ($newWallet == null) {
            return null;
        }

        Cache::forever("w_" . $newWallet->getId(), $newWallet);

        return $newWallet->getId();
    }
}
