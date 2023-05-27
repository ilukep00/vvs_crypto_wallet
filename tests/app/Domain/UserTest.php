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
            $user->getNewWallet();
        }

        $this->assertEquals($walletNum, $user->getNumOfWallets());
    }
}
