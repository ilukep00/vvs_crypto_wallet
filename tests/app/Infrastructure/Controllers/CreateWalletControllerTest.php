<?php

namespace Tests\app\Infrastructure\Controllers;

use App\Application\CreateWalletService;
use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;

class CreateWalletControllerTest extends TestCase
{
    private CreateWalletService $createWalletService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createWalletService = Mockery::mock(CreateWalletService::class);
        $this->app->bind(CreateWalletService::class, function () {
            return $this->createWalletService;
        });
    }

    /**
     * @test
     */
    public function returnsErrorOnInvalidRequest()
    {
        $response = $this->postJson('/api/wallet/open', ['userid' => '']);

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function returnsErrorOnUserNotFound()
    {
        $idUsuario = 'id_usuario_invalido';
        $this->createWalletService
            ->expects('execute')
            ->with($idUsuario)
            ->andReturn(null);

        $response = $this->postJson('/api/wallet/open', ['user_id' => $idUsuario]);

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function returnsWalletIdOnCreate()
    {
        $idUsuario = 'valid_user_id';
        $this->createWalletService
            ->expects('execute')
            ->with($idUsuario)
            ->andReturn('1_1');

        $response = $this->postJson('/api/wallet/open', ['user_id' => $idUsuario]);

        $response->assertStatus(200);
        $response->assertExactJson(['wallet_id' => '1_1']);
    }
}
