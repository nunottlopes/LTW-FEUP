<?php
$auth = Auth::demandLevel('free');

$useremail = $args['email'];

$valid = User::validEmail($useremail);

$valid_string = $valid ? "valid" : "invalid";

$data = [
    'email' => $useremail,
    'valid' => $valid,
    'text' => $valid_string
];

HTTPResponse::ok("User email $useremail is $valid_string", $data);
?>
