<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GetWalletCoinsController extends BaseController
{
    private WalletDataSource $walletDataSource;
    private string $PATTERN = "/^[a-zA-Z0-9]+$/";
    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }

    public function __invoke(Request $request, $wallet_id): JsonResponse
    {
        $patternCheck = preg_match($this->PATTERN, $wallet_id);
        if ($patternCheck === 0) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        $wallet = $this->walletDataSource->searchWallet($wallet_id);

        if (is_null($wallet)) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $coinsInWallet  = $wallet->getCoinList();
        $coinData = array();
        foreach ($coinsInWallet as $coin) {
            $coinData[] = array(
                'coin_id' => $coin->id,
                'name' => $coin->name,
                'symbol' => $coin->symbol,
                'amount' => $coin->ammount,
                'value_usd' => $coin->invertedMoney
            );
        }
        return response()->json($coinData, Response::HTTP_OK);
    }
}
