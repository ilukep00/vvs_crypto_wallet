<?php

namespace App\Application;

use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Exception;

class SellCoinService
{
    private WalletDataSource $walletDataSource;

    /**
     * @param WalletDataSource $walletDataSource
     */
    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }

    public function execute(string $coinId, string $walletId, float $amountUSD)
    {
        $wallet = $this->walletDataSource->searchWallet($walletId);
        if (is_null($wallet)) {
            throw new Exception("Cartera no encontrada");
        }
        $coin = $wallet->getCoinById($coinId);
        if (is_null($coin)) {
            throw new Exception("Moneda no encontrada");
        }
        if ($coin->getAmmount() < $amountUSD) {
            throw new Exception("Cantidad incorrecta");
        }

        $coin->setAmmount($coin->getAmmount() - $amountUSD);

        if ($coin->getAmmount() == 0) {
            $wallet->deleteCoinById($coin->getId());
        }

        $this->walletDataSource->saveWallet($wallet);
    }
}
