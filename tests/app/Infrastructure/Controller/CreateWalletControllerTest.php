<?php

namespace Tests\app\Infrastructure\Controller;

use App\Infrastructure\Persistence\WalletDataSource;
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

    /**
     * @test
     */
    public function returnsErrorOnUserNotFound()
    {
        $walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->app->bind(WalletDataSource::class, function () use ($walletDataSource) {
            return $walletDataSource;
        });

        $idUsuario = 'id_usuario_invalido';
        $walletDataSource
            ->expects('getWallet')
            ->with($idUsuario)
            ->andReturn(null);

        $response = $this->postJson('/api/wallet/open', ['user_id' => $idUsuario]);

        $response->assertNotFound();
        $response->assertExactJson([]);
    }
}
