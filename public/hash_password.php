<?php
$password = '.A12345678a.';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword;
