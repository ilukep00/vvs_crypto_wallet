<?php

namespace App\Infrastructure\Controllers;

use App\Application\WalletBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Exception;

class GetWalletBalanceController extends BaseController
{
    private WalletBalanceService $walletBalanceService;
    public function __construct(WalletBalanceService $walletBalanceService)
    {
        $this->walletBalanceService = $walletBalanceService;
    }

    public function __invoke(Request $request, string $walletId): JsonResponse
    {
        $idParts = explode("_", $walletId);
        if (count($idParts) != 2 or (!is_numeric($idParts[0]) or !is_numeric($idParts[1]))) {
            return response()->json([], 400);
        }
        try {
            $balance = $this->walletBalanceService->execute($walletId);
        } catch (Exception $exception) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        return response()->json(["balance_usd" => $balance], 200);

        $wallet = $this->walletDataSource->searchWallet($walletId);
    }
}
