<?php
$hashOpt = ['cost' => 10];

$passes = [
    '123456',
    'qwerty',
    'password',
    '12121212',
    'bruno',
    'amadeu',
    'nuno',
    'admin'
];

$hashes = [];

foreach ($passes as $pass) {
    $hash = password_hash($pass, PASSWORD_DEFAULT, $hashOpt);
    array_push($hashes, ['pass' => $pass, 'hash' => $hash]);
}

echo '<pre>';
echo json_encode($hashes, JSON_PRETTY_PRINT);
echo '</pre>'

// This is an old file used to generate password hashes for sql/populate.sql
?>
