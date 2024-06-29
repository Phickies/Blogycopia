<?php

declare(strict_types=1);

namespace Core;


class SessionHandler
{

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }


    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }


    public function isAuthenticated(): bool
    {
        return isset($_SESSION['username']);
    }


    public function destroy()
    {
        session_destroy();
    }
}
