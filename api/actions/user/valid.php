<?php
$action = 'valid';

$auth = Auth::demandLevel('free');

$username = $args['username'];
$useremail = $args['email'];

$valid = User::valid($username, $useremail, $error);

$valid_string = $valid ? "valid" : "invalid";

$data = [
    'username' => $username,
    'email' => $useremail,
    'error' => $error,
    'valid' => $valid,
    'text' => $valid_string
];

HTTPResponse::ok("Username $username and email $useremail are $valid_string", $data);
?>
