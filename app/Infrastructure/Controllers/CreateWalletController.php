<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class CreateWalletController extends BaseController
{
    public function __invoke(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        if( !isset($jsonData['user_id']) ) {
            return response()->json([], 400);
        }

        return response()->json([], 200);
    }
}
