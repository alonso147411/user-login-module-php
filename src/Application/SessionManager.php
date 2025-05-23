<?php

namespace UserLoginService\Application;

interface SessionManager
{
    public function getSessions(): int;

    public function login(string $userName, string $password): bool;

    public function logout(string $getUserName): void;
}