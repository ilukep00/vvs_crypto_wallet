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
        if (is_null(Cache::get("u_" . $userId))) {
            return null;
        }

        $user = Cache::get("u_" . $userId);
        $newWallet = $user->newWallet();
        $this->userDataSource->save($user);

        if ($newWallet == null) {
            return null;
        }

        Cache::forever("w_" . $newWallet->getId(), $newWallet);

        return $newWallet->getId();
    }
}
