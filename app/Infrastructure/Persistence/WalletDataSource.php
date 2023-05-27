<?php

namespace App\Infrastructure\Persistence;

use Illuminate\Support\Facades\Cache;
use App\Domain\Wallet;

class WalletDataSource
{
    private UserDataSource $userDataSource;

    public function __construct()
    {
        $this->userDataSource = new UserDataSource();
    }

    public function searchWallet(string $walletId): Wallet|null
    {
        return Cache::get("wallet_" . $walletId);
    }

    public function saveWallet(Wallet $wallet): void
    {
        Cache::forever("wallet_" . $wallet->getId(), $wallet);
    }
}
