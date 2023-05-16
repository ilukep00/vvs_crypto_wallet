<?php

namespace Tests\app\Infrastructure\Controllers;

use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;

class CreateWalletControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->app->bind(WalletDataSource::class, function () {
            return $this->walletDataSource;
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
        $this->walletDataSource
            ->expects('createWallet')
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
        $this->walletDataSource
            ->expects('createWallet')
            ->with($idUsuario)
            ->andReturn('user123');

        $response = $this->postJson('/api/wallet/open', ['user_id' => $idUsuario]);

        $response->assertStatus(200);
        $response->assertExactJson(['wallet_id' => 'user123']);
    }
}
