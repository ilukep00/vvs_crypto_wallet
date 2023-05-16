<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class GetWalletBalanceController extends BaseController
{
    public function __invoke(Request $request, string $walletId): JsonResponse
    {
        $idParts = explode("_", $walletId);
        if (count($idParts) != 2 or (!is_numeric($idParts[0]) or !is_numeric($idParts[1]))) {
            return response()->json([], 400);
        }

        return response()->json([], 200);
    }
}
