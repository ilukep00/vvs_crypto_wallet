<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Persistence\CoinDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class BuyCoinController extends BaseController
{
    private CoinDataSource $coinDataSource;

    /**
     * @param CoinDataSource $coinDataSource
     */
    public function __construct(CoinDataSource $coinDataSource)
    {
        $this->coinDataSource = $coinDataSource;
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

        if (is_null($this->coinDataSource->getCoinById($coin_id))) {
            return response()->json(["Moneda no encontrada"], 404);
        }
    }
}
