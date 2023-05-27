<?php

namespace App\Infrastructure\Controllers;

use App\Application\CreateWalletService;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use App\Domain\User;
use Illuminate\Support\Facades\Cache;

class CreateWalletController extends BaseController
{
    private CreateWalletService $createWalletService;

    public function __construct(CreateWalletService $createWalletService)
    {
        $this->createWalletService = $createWalletService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if (!isset($jsonData['user_id'])) {
            return response()->json([], 400);
        }
        $walletId = $this->createWalletService->execute($jsonData['user_id']);
        if (is_null($walletId)) {
            return response()->json([], 404);
        }

        return response()->json(['wallet_id' => $walletId], 200);
    }
}
