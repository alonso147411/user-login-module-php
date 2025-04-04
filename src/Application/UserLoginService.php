<?php

namespace UserLoginService\Application;

use PHPUnit\Util\Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;

class UserLoginService
{

    private array $loggedUsers = [];

    /**
     * @param array $loggedUsers
     */
    public function __construct(private FacebookSessionManager $facebookSessionManager)
    {
        $this->facebookSessionManager = $facebookSessionManager;
    }


    public function manualLogin(User $user): void
    {
        if (in_array($user->getUserName(), $this->loggedUsers)) {
            throw new Exception('user already logged in');
        } else {
            $this->loggedUsers[] = $user->getUserName();
        }
    }

    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }

    public function getExternalSessions(): int
    {
        $facebookSessionManager = new FacebookSessionManager();
        return $facebookSessionManager->getSessions();
    }

}