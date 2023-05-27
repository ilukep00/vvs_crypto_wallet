<?php

namespace App\Application;

use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Exception;

class WalletBalanceService
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

    public function execute(string $walletId)
    {
        $wallet = $this->walletDataSource->searchWallet($walletId);
        if (is_null($wallet)) {
            throw new Exception("Cartera no encontrada");
        }
        $balance = 0;
        $coinList = $wallet->getCoinList();

        foreach ($coinList as $coin) {
            $actualCoinValue = $this->coinDataSource->getCoinPrize($coin->getId());

            $balance = $balance + (($coin->ammount * $actualCoinValue) - $coin->invertedMoney);
        }
        return $balance;
    }
}
