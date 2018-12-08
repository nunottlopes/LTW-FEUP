<?php
$action = 'create';

$auth = Auth::demandLevel('none');

$username = $args['username'];
$useremail = $args['email'];
$password = $args['password'];

if (!User::validUsername($username)) {
    HTTPResponse::badArgument('username', $username);
}

if (!User::validEmail($useremail)) {
    HTTPResponse::badArgument('email', $useremail);
}

if (User::getByUsername($username)) {
    HTTPResponse::conflict('Already existing username', 'username', $username);
}

if (User::getByEmail($useremail)) {
    HTTPResponse::conflict('Email already in use', 'email', $useremail);
}

$userid = User::create($username, $useremail, $password);

if (!$userid) {
    HTTPResponse::serverError();
}

$data = [
    'userid' => $userid,
    'username' => $username,
    'useremail' => $useremail,
    'admin' => false
];

HTTPResponse::created("Successfully created account $userid", $data);
?>
