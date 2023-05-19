<?php

namespace Tests\app\Infrastructure\Controllers;

use Illuminate\Http\Response;
use Tests\TestCase;
use Mockery;

class GetWalletBalanceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function returnsErrorOnBadRequest()
    {
        $response1 = $this->get('/api/wallet/1/balance');
        $response2 = $this->get('/api/wallet/1_a/balance');

        $response1->assertStatus(400);
        $response1->assertExactJson([]);
        $response2->assertStatus(400);
        $response2->assertExactJson([]);
    }
}
