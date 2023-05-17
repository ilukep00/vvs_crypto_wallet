<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;

class WalletDataSource
{
    public function searchWallet(string $walletId): Wallet|null
    {
        return Cache::get("wallet_" . $walletId);
    }

    public function saveWallet(Wallet $wallet)
    {
        Cache::forever("wallet_" . $wallet->getId(), $wallet);
    }
}
