<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class BuyCoinController extends BaseController
{
    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if (!isset($jsonData['coin_id']) || !isset($jsonData['wallet_id']) || !isset($jsonData['amount_usd'])) {
            return response()->json([], 400);
        }
        if (
            gettype($jsonData['coin_id']) != 'string' ||
            gettype($jsonData['wallet_id']) != 'string' ||
            gettype($jsonData['amount_usd']) != 'integer'
        ) {
            return response()->json([], 400);
        }
    }
}
