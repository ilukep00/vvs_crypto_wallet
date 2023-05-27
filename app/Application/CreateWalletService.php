<?php

namespace App\Application;

use App\Infrastructure\Persistence\WalletDataSource;

class CreateWalletService
{
    private WalletDataSource $walletDataSource;

    public function construct($walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }

    public function execute(string $userId): string|null
    {
        $walletDataSource = new WalletDataSource();
        return $this->walletDataSource->createWallet($userId);
    }
}
