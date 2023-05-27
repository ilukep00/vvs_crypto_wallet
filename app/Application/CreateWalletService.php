<?php

namespace App\Application;

use App\Domain\Wallet;
use App\Infrastructure\Persistence\UserDataSource;
use App\Infrastructure\Persistence\WalletDataSource;

class CreateWalletService
{
    private WalletDataSource $walletDataSource;
    private UserDataSource $userDataSource;

    public function __construct(WalletDataSource $walletDataSource, UserDataSource $userDataSource)
    {
        $this->walletDataSource = $walletDataSource;
        $this->userDataSource = $userDataSource;
    }

    public function execute(string $userId): string|null
    {
        $user = $this->userDataSource->search($userId);
        if (is_null($user)) {
            return null;
        }

        $newWalletId = sprintf('%d_%d', $userId, $user->getNewWallet());
        $newWallet = new Wallet($newWalletId);

        $this->walletDataSource->saveWallet($newWallet);
        $this->userDataSource->save($user);

        return $newWalletId;
    }
}
