<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Mockery;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;

final class UserLoginServiceTest extends TestCase
{
    /**
     * @test
     */
    public function userAlreadyLoggedIn()
    {
        $user = new User('Ana');
        $userLoginService = new UserLoginService();

        $this->expectExceptionMessage("user already logged in");

        $userLoginService->manualLogin($user);
        $userLoginService->manualLogin($user);

    }

    /**
     * @test
     */
    public function userIsLoggedIn()
    {
        $user = new User('Ana');
        $userLoginService = new UserLoginService();
        $userLoginService->manualLogin($user);

        $this->assertEquals(['Ana'], $userLoginService->getLoggedUsers());

    }

    /**
     * @test
     */
    public function getNumberOfSessions()
    {
         $userLoginService = new UserLoginService();
         $facebookSessionManager = Mockery::mock(FacebookSessionManager::class);
         $facebookSessionManager->allows()->getSessions()->andReturn(4);

         $this->assertEquals(4,$userLoginService->getExternalSessions());

    }



}
