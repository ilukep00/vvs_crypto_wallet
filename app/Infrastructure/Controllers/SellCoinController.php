<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellCoinController
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;

    public function __construct(WalletDataSource $walletDataSource, CoinDataSource $coinDataSource)
    {
        $this->walletDataSource = $walletDataSource;
        $this->coinDataSource = $coinDataSource;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if (!isset($jsonData['coin_id']) || !isset($jsonData['wallet_id']) || !isset($jsonData['amount_usd'])) {
            return response()->json([], 400);
        }

        $wallet = $this->walletDataSource->searchWallet($jsonData['wallet_id']);
        if (is_null($wallet)) {
            return response()->json([], 404);
        }

        $coin = $wallet->getCoinById($jsonData['coin_id']);
        if (is_null($coin)) {
            return response()->json([], 404);
        }

        if ($coin->getAmmount() < $jsonData['amount_usd']) {
            return response()->json([], 404);
        }

        return response()->json(['venta realizada'], 200);
    }
}
