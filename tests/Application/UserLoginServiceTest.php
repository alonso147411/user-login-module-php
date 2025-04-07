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
  public function userLogout()
  {
      $user = new User('Ana');
      $facebookSessionManager = Mockery::spy(FacebookSessionManager::class);

      $userLoginService = new UserLoginService($facebookSessionManager);
      
      $userLoginService->manualLogin($user);
      $result = $userLoginService->logout($user);

      $facebookSessionManager->shouldHaveReceived('logout')
                              ->with($user)
                              ->once();

      $this->assertEquals('ok', $result);
      $this->assertEquals([], $userLoginService->getLoggedUsers());

  }
    /**
     * @test
     */
    public function userLogoutNotFound()
    {
        $user = new User('Ana');
        $facebookSessionManager = Mockery::spy(FacebookSessionManager::class);

        $userLoginService = new UserLoginService($facebookSessionManager);
        $result = $userLoginService->logout($user);

        $this->assertEquals('user not found', $result);
    }

    /**
     * @test
     */
    public function loginCorrecto()
    {
        $facebookSessionManager = Mockery::mock(FacebookSessionManager::class);
        $facebookSessionManager->shouldReceive('login')->andReturn(true);
        $userLoginService = new UserLoginService($facebookSessionManager);

        $this->assertEquals('Login correcto', $userLoginService->login('Ana', '1234'));
    }

    /**
     * @test
     */
    public function userIsLoginIn()
    {
        $facebookSessionManager = Mockery::mock(FacebookSessionManager::class);
        $facebookSessionManager->shouldReceive('login')->andReturn('Login correcto');
        $userLoginService = new UserLoginService($facebookSessionManager);

        $this->assertEquals('Login correcto', $userLoginService->login('Ana', '1234'));

    }




}
