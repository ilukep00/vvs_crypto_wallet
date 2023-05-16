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

    public function createWallet(string $userId): string|null
    {
        if (is_null(Cache::get("user_" . $userId))) {
            return null;
        }

        $user = Cache::get("user_" . $userId);
        $newWallet = $user->newWallet();
        $this->userDataSource->save($user);

        if ($newWallet == null) {
            return null;
        }

        Cache::forever("wallet_" . $newWallet->getId(), $newWallet);

        return $newWallet->getId();
    }

    public function searchWallet(string $wallet_id): Wallet|null
    {
        return Cache::get("wallet_" . $wallet_id);
    }
}
