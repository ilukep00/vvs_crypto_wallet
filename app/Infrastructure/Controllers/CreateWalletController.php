<?php

namespace App\Infrastructure\Controllers;

use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class CreateWalletController extends BaseController
{
    private WalletDataSource $walletDataSource;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if (!isset($jsonData['user_id'])) {
            return response()->json([], 400);
        }
        $wallets = $this->walletDataSource->getWallet($jsonData['user_id']);
        if (is_null($wallets)) {
            return response()->json([], 404);
        }

        return response()->json($wallets, 200);
    }
}
