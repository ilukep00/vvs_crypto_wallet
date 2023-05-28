<?php

namespace Tests\app\Application;

use App\Application\GetWalletCoinsService;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletDataSource;
use Mockery;
use Tests\TestCase;

class GetWalletCoinsServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private GetWalletCoinsService $getWalletCoinsService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->getWalletCoinsService = new GetWalletCoinsService($this->walletDataSource);
    }
    /**
     * @test
     */
    public function exceptionWalletNotFound()
    {
        $this->walletDataSource->expects("searchWallet")
            ->with("1")
            ->andReturnNull();

        $wallet_id = "1";

        $this->expectExceptionMessage("Cartera no encontrada");

        $this->getWalletCoinsService->execute($wallet_id);
    }
    /**
     * @test
     */
    public function coinListRead()
    {
        $wallet = Mockery::mock(Wallet::class);
        $wallet->expects("getCoinList");

        $this->walletDataSource->expects("searchWallet")
            ->with("1")
            ->andReturn($wallet);

        $wallet_id = "1";
        $this->getWalletCoinsService->execute($wallet_id);
    }
}
