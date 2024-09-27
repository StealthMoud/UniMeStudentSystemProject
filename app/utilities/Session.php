<?php

namespace App\utilities;

class Session {
    public static function init(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    // Clear a session variable
    public static function clear($key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Get and clear a session variable
    public static function flash($key) {
        $value = self::get($key);
        self::clear($key);
        return $value;
    }

    public static function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }
}

