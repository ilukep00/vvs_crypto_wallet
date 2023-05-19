<?php

namespace Tests\Application;

use App\Application\BuyCoinService;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;

class BuyCoinServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;

    private CoinDataSource $coinDataSource;

    private BuyCoinService $buyCoinService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->buyCoinService = new BuyCoinService($this->coinDataSource, $this->walletDataSource);
    }

    /**
     * @test
     */
    public function exceptionCoinNotFound()
    {

        $this->coinDataSource->expects("searchCoin")
            ->with("1", 1)
            ->andReturnNull();

        $wallet_id = "1";
        $coin_id = "1";
        $amount_sd = 1;

        $this->expectExceptionMessage("Moneda no encontrada");

        $this->buyCoinService->execute($coin_id, $wallet_id, $amount_sd);
    }

    /**
     * @test
     */
    public function exceptionWalletNotFound()
    {
        $this->coinDataSource->expects("searchCoin")
            ->with("1", 1)
            ->andReturn(new Coin());

        $this->walletDataSource->expects("searchWallet")
            ->with("1")
            ->andReturnNull();

        $wallet_id = "1";
        $coin_id = "1";
        $amount_sd = 1;

        $this->expectExceptionMessage("Cartera no encontrada");

        $this->buyCoinService->execute($coin_id, $wallet_id, $amount_sd);
    }

    /**
     * @test
     */
    public function walletSaveCorrectly()
    {
        $this->coinDataSource->expects("searchCoin")
            ->with("1", 1)
            ->andReturn(new Coin());

        $this->walletDataSource->expects("searchWallet")
            ->with("1")
            ->andReturn(new Wallet("1"));
        $this->walletDataSource->expects("saveWallet");

        $wallet_id = "1";
        $coin_id = "1";
        $amount_sd = 1;

        $this->buyCoinService->execute($coin_id, $wallet_id, $amount_sd);
    }
}
