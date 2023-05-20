<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\ApiManager;
use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetWalletBalanceController extends BaseController
{
    private CoinDataSource $coinDataSource;
    public function __construct(WalletDataSource $walletDataSource, CoinDataSource $coinDataSource)
    {
        $this->walletDataSource = $walletDataSource;
        $this->coinDataSource = $coinDataSource;
    }

    public function __invoke(Request $request, string $walletId): JsonResponse
    {
        $idParts = explode("_", $walletId);
        if (count($idParts) != 2 or (!is_numeric($idParts[0]) or !is_numeric($idParts[1]))) {
            return response()->json([], 400);
        }

        $wallet = $this->walletDataSource->searchWallet($walletId);

        if (is_null($wallet)) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $balance = 0;
        $coinList = $wallet->getCoinList();

        foreach ($coinList as $coin) {
            $actualCoinValue = $this->coinDataSource->getCoinPrize($coin->getId());

            $balance = $balance + (($coin->ammount * $actualCoinValue) - $coin->invertedMoney);
        }

        return response()->json(["balance_usd" => $balance], 200);
    }
}
