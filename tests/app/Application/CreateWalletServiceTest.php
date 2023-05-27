<?php

namespace Tests\Application;

use App\Application\CreateWalletService;
use App\Domain\User;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\UserDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;

class CreateWalletServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private UserDataSource $userDataSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->userDataSource = Mockery::mock(UserDataSource::class);
    }

    /**
     * @test
     */
    public function returnsNullOnNoUserFound()
    {
        $this->userDataSource->expects('search')
            ->with('1')
            ->andReturn(null);
        $createWalletService = new CreateWalletService($this->walletDataSource, $this->userDataSource);

        $response = $createWalletService->execute('1');

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function returnsNewWalletIdWhenCreate()
    {
        $user = new User('1');
        $this->userDataSource->expects('search')
            ->with('1')
            ->andReturn($user);
        $this->userDataSource->expects('save');
        $this->walletDataSource->expects('saveWallet');
        $createWalletService = new CreateWalletService($this->walletDataSource, $this->userDataSource);

        $response = $createWalletService->execute('1');

        $this->assertEquals('1_1', $response);
    }
}
