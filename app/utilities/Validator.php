<?php
// app/Utilities/Validator.php

namespace App\utilities;

class Validator {
    public static function sanitize($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public static function validateUsername($username, $role) {
        return preg_match("/^[a-zA-Z0-9._%+-]+@$role\.unime\.it$/", $username);
    }

    public static function validatePassword($password) {
        // Updated regular expression
        return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/', $password);
    }
}
