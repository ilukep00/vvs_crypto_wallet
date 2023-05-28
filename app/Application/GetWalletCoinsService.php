<?php

namespace App\Application;

use App\Infrastructure\Persistence\WalletDataSource;
use Exception;
use Illuminate\Http\Response;

class GetWalletCoinsService
{
    private WalletDataSource $walletDataSource;
    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }

    public function execute($wallet_id): array
    {
        $wallet = $this->walletDataSource->searchWallet($wallet_id);

        if (is_null($wallet)) {
            throw new Exception("Cartera no encontrada");
        }

        $coinsInWallet  = $wallet->getCoinList();
        $coinData = array();
        foreach ($coinsInWallet as $coin) {
            $coinData[] = array(
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->ammount,
                'value_usd' => $coin->invertedMoney
            );
        }

        return $coinData;
    }
}
