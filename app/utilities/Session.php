<?php
// app/core/utilities/Session.php

namespace App\utilities;

class Session {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    // Clear a session variable
    public static function clear($key) {
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

    public static function destroy() {
        session_destroy();
        $_SESSION = [];
    }
}

