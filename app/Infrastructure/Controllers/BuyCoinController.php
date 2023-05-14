<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class BuyCoinController extends BaseController
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


    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if (!isset($jsonData['coin_id']) || !isset($jsonData['wallet_id']) || !isset($jsonData['amount_usd'])) {
            return response()->json([], 400);
        }
        $coin_id = $jsonData['coin_id'];
        $wallet_id = $jsonData['wallet_id'];
        $amount_usd = $jsonData['amount_usd'];
        if (gettype($coin_id) != 'string' || gettype($wallet_id) != 'string' || gettype($amount_usd) != 'integer') {
            return response()->json([], 400);
        }

        if (is_null($this->coinDataSource->searchCoin($coin_id))) {
            return response()->json(["Moneda no encontrada"], 404);
        }

        if (is_null($this->walletDataSource->searchWallet($wallet_id))) {
            return response()->json(["Cartera no encontrada"], 404);
        }
    }
}
