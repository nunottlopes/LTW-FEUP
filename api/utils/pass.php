<?php
$hashOpt = ['cost' => 10];

$passes = [
    '123456',
    'qwerty',
    'password',
    '12121212',
    'bruno',
    'amadeu',
    'nuno'
];

$hashes = [];

foreach ($passes as $pass) {
    $hash = password_hash($pass, PASSWORD_DEFAULT, $hashOpt);
    array_push($hashes, ['pass' => $pass, 'hash' => $hash]);
}

echo json_encode($hashes, JSON_PRETTY_PRINT);

// This is an old file used to generate password hashes for db/sql/populate.sql
?>
