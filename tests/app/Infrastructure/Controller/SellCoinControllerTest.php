<?php

namespace Tests\app\Infrastructure\Controller;

use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    /**
     * @test
     */
    public function returnsErrorOnInvalidRequest()
    {
        $response = $this->postJson('/api/coin/sell', [
            'coinid' => '',
            'wallet_id' => '',
            'amount_usd' => '']);

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }
}
