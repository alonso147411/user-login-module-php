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
        $facebookSessionManager = Mockery::mock(FacebookSessionManager::class);
        $userLoginService = new UserLoginService($facebookSessionManager );

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
        $facebookSessionManager = Mockery::mock(FacebookSessionManager::class);

        $userLoginService = new UserLoginService($facebookSessionManager);
        $userLoginService->manualLogin($user);

        $this->assertEquals(['Ana'], $userLoginService->getLoggedUsers());

    }

    /**
     * @test
     */

    public function getNumberOfSessions()
    {
        $facebookSessionManager = Mockery::mock(FacebookSessionManager::class);
        $facebookSessionManager->shouldReceive('getSessions')->andReturn(4);
        $userLoginService = new UserLoginService($facebookSessionManager);

        $this->assertEquals(4, $userLoginService->getExternalSessions());
    }
    /**
     * @test
     */




}
