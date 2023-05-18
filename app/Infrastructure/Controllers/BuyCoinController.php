<?php

namespace App\Infrastructure\Controllers;

use App\Application\BuyCoinService;
use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class BuyCoinController extends BaseController
{
    private BuyCoinService $buyCoinService;

    /**
     * @param BuyCoinService $buyCoinService
     */
    public function __construct(BuyCoinService $buyCoinService)
    {
        $this->buyCoinService = $buyCoinService;
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
        try {
            $this->buyCoinService->execute($coin_id, $wallet_id, $amount_usd);
        } catch (Exception $ex) {
            return response()->json([$ex->getMessage()], 404);
        }
        return response()->json(["Compra realizada"], 200);
    }
}
