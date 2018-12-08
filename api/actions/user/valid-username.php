<?php
$action = 'valid-username';

$auth = Auth::demandLevel('free');

$username = $args['username'];

$valid = User::validUsername($username);

$valid_string = $valid ? "valid" : "invalid";

$data = [
    'username' => $username,
    'valid' => $valid,
    'text' => $valid_string
];

HTTPResponse::ok("Username $username is $valid_string", $data);
?>
