<?php

namespace Tests\app\Domain;

use App\Domain\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function canCreateMultipleWallets()
    {
        $user = new User(1);

        $walletNum = 3;
        for ($i = 0; $i < $walletNum; $i++) {
            $user->newWallet();
        }

        $this->assertEquals($walletNum, $user->getNumOfWallets());
    }

    /**
     * @test
     */
    public function returnsNullOnCreateWalletIfOver999()
    {
        $user = new User(1);

        for ($i = 0; $i < 999; $i++) {
            $user->newWallet();
        }
        $wallet = $user->newWallet();

        $this->assertNull($wallet);
    }
}
