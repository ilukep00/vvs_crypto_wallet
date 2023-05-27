<?php

namespace App\Infrastructure\Controllers;

use App\Application\SellCoinService;
use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class SellCoinController
{
    private SellCoinService $sellCoinService;

    public function __construct(SellCoinService $sellCoinService)
    {
        $this->sellCoinService = $sellCoinService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if (!isset($jsonData['coin_id']) || !isset($jsonData['wallet_id']) || !isset($jsonData['amount_usd'])) {
            return response()->json([], 400);
        }
        try {
            $this->sellCoinService->execute($jsonData['coin_id'], $jsonData['wallet_id'], $jsonData['amount_usd']);
        } catch (Exception $ex) {
            $statusCode = 400;
            if ($ex->getMessage() == "Cartera no encontrada") {
                $statusCode = 404;
            } elseif ($ex->getMessage() == "Moneda no encontrada") {
                $statusCode = 404;
            }
            return response()->json([], $statusCode);
        }
        return response()->json(['venta realizada'], 200);
    }
}
