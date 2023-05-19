<?php

namespace App\Application;

use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Exception;

class BuyCoinService
{
    private CoinDataSource $coinDataSource;

    private WalletDataSource $walletDataSource;

    /**
     * @param CoinDataSource $coinDataSource
     * @param WalletDataSource $walletDataSource
     */
    public function __construct(CoinDataSource $coinDataSource, WalletDataSource $walletDataSource)
    {
        $this->coinDataSource = $coinDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    public function execute(string $coinId, string $walletId, float $amountUSD)
    {
        $coin = $this->coinDataSource->searchCoin($coinId, $amountUSD);
        if (is_null($coin)) {
            throw new Exception("Moneda no encontrada");
        }

        $wallet = $this->walletDataSource->searchWallet($walletId);
        if (is_null($wallet)) {
            throw new Exception("Cartera no encontrada");
        }

        $wallet->buy($coin);
        $this->walletDataSource->saveWallet($wallet);
    }
}
