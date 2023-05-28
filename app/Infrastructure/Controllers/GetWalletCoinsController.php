<?php

namespace App\Infrastructure\Controllers;

use App\Application\GetWalletCoinsService;
use App\Infrastructure\Persistence\WalletDataSource;
use Exception;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GetWalletCoinsController extends BaseController
{
    private GetWalletCoinsService $getWalletCoinsService;
    private string $PATTERN = "/^[a-zA-Z0-9]+_[a-zA-Z0-9]+$/";
    public function __construct(GetWalletCoinsService $getWalletCoinsService)
    {
        $this->getWalletCoinsService = $getWalletCoinsService;
    }

    public function __invoke(Request $request, $wallet_id): JsonResponse
    {
        $patternCheck = preg_match($this->PATTERN, $wallet_id);
        if ($patternCheck === 0) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        try {
            $coinData = $this->getWalletCoinsService->execute($wallet_id);
        } catch (Exception $ex) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return response()->json($coinData, Response::HTTP_OK);
    }
}
