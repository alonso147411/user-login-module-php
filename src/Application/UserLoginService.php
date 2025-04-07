<?php

namespace UserLoginService\Application;

use PHPUnit\Util\Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;

class UserLoginService
{

    private array $loggedUsers = [];
    private mixed $facebookSessionManager;


    public function __construct( $facebookSessionManager)
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
        return $this->facebookSessionManager->getSessions();
    }

    public function logout(User $user): string{
        if (in_array($user->getUserName(), $this->loggedUsers)) {
            unset($this->loggedUsers[array_search($user->getUserName(), $this->loggedUsers)]);
            return 'ok';
        } else {
            throw new Exception('user not found');
        }
    }

}