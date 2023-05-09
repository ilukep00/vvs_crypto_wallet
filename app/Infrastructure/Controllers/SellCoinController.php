<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellCoinController
{
    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if (!isset($jsonData['coin_id']) || !isset($jsonData['wallet_id']) || !isset($jsonData['amount_usd'])) {
            return response()->json([], 400);
        }

        return response()->json([]);
    }
}
