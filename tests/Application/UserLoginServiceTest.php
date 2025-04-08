<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Mockery;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\SessionManager;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;

final class UserLoginServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }
    /**
     * @test
     */
    public function userAlreadyLoggedIn()
    {
        $user = new User('Ana');

        $userLoginService = new UserLoginService(Mockery::mock(FacebookSessionManager::class));

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

        $userLoginService = new UserLoginService(Mockery::mock(FacebookSessionManager::class));
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

    public function userIsLoggedOut()
    {
        $user = new User('Ana');
        $sessionManager = Mockery::spy(FacebookSessionManager::class);
        $userLoginService = new UserLoginService($sessionManager);
        $userLoginService->manualLogin($user);

        $this->assertEquals('ok', $userLoginService->logout($user));

        $sessionManager->shouldHaveReceived('logout')->once();

    }

    /**
     * @test
     */
    public function userWantsToLogging()
    {
        $user = new User('Ana');
        $sessionManager = Mockery::spy(FacebookSessionManager::class);

        $sessionManager->shouldReceive('login')
            ->with($user->getUserName(), '1234')
            ->andReturn(true);

        $userLoginService = new UserLoginService($sessionManager);
        $response = $userLoginService->login($user->getUserName(), '1234');
        $this->assertEquals('Login correcto', $response);

        $sessionManager->shouldNotHaveReceived('logout');
    }


}
