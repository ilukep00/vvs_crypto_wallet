<?php

namespace Tests\app\Infrastructure\Controllers;

use App\Application\BuyCoinService;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\ApiManager;
use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;
use Exception;

class BuyCoinControllerTest extends TestCase
{
    private CoinDataSource $coinDataSource;
    private WalletDataSource $walletDataSource;
    private BuyCoinService $buyCoinService;
    private ApiManager $apiManager;
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->buyCoinService = new BuyCoinService($this->coinDataSource, $this->walletDataSource);

        $this->app->bind(BuyCoinService::class, function () {
            return $this->buyCoinService;
        });
    }

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

    /**
     * @test
     */
    public function buyCoinBodyParameterRequestTypeInvalid()
    {
        $response = $this->postJson(
            '/api/coin/buy',
            ['coin_id' => 2,
                'wallet_id' => 'sfdfdf',
                'amount_usd' => 1]
        );

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function buyCoinNotFoundError()
    {
        $this->coinDataSource
            ->expects("searchCoin")
            ->with('c_000001', 1)
            ->andReturnNull();

        $response = $this->postJson(
            '/api/coin/buy',
            ['coin_id' => "c_000001",
                'wallet_id' => 'w_000001',
                'amount_usd' => 1]
        );

        $response->assertStatus(404);
        $response->assertExactJson(["Moneda no encontrada"]);
    }

    /**
     * @test
     */
    public function buyWalletNotFoundError()
    {
        $this->coinDataSource
            ->expects("searchCoin")
            ->with('c_000001', 1)
            ->andReturn(new Coin());

        $this->walletDataSource
            ->expects("searchWallet")
            ->with('w_000001')
            ->andReturnNull();

        $response = $this->postJson(
            '/api/coin/buy',
            ['coin_id' => "c_000001",
                'wallet_id' => 'w_000001',
                'amount_usd' => 1]
        );

        $response->assertStatus(404);
        $response->assertExactJson(["Cartera no encontrada"]);
    }

    /**
     * @test
     */
    public function buyCoinSuccess()
    {
        $this->coinDataSource
            ->expects("searchCoin")
            ->with('c_000001', 1)
            ->andReturn(new Coin());

        $this->walletDataSource
            ->expects("searchWallet")
            ->with('w_000001')
            ->andReturn(new Wallet('w_000001'));
        $this->walletDataSource
            ->expects("saveWallet")
            ->once();

        $response = $this->postJson(
            '/api/coin/buy',
            ['coin_id' => "c_000001",
                'wallet_id' => 'w_000001',
                'amount_usd' => 1]
        );

        $response->assertStatus(200);
        $response->assertExactJson(["Compra realizada"]);
    }
}
