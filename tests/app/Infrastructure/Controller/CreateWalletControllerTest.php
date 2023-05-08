<?php

namespace Tests\app\Infrastructure\Controller;

use Tests\TestCase;
use Mockery;

class CreateWalletControllerTest extends TestCase
{
    /**
     * @test
     */
    public function returnsErrorOnInvalidRequest()
    {
        $response = $this->postJson('/api/wallet/open', ['userid' => '']);

        $response->assertBadRequest();
        $response->assertExactJson([]);
    }
}
