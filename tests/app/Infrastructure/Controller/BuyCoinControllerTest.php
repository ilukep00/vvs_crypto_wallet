<?php

namespace Tests\app\Infrastructure\Controller;

use Tests\TestCase;

class BuyCoinControllerTest extends TestCase
{
    /**
     * @test
     */
    public function buyCoinBodyParameterRequestError()
    {
        $response = $this->postJson(
            '/api/coin/buy',
            ['id_coin' => 'sttring',
             'id_wallet' => 'sfdfdf',
            'amount_usd' => 1]
        );

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }
}
