<?php
$password = 'M10135360m.';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword;
