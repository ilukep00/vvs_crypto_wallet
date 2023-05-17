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

        $coin = $this->coinDataSource->searchCoin($coin_id, $amount_usd); //BUSCA LA MONEDA LLAMANDO A LA API
        if (is_null($coin)) {
            return response()->json(["Moneda no encontrada"], 404);
        }
        $wallet = $this->walletDataSource->searchWallet($wallet_id); //BUSCA EL WALLET EN CACHE
        if (is_null($wallet)) {
            return response()->json(["Cartera no encontrada"], 404);
        }
        $wallet->buy($coin);
        $this->walletDataSource->saveWallet($wallet);
        //LA VUELVO EN GUARDAR
        return response()->json(["Compra realizada"], 200);
    }
}
