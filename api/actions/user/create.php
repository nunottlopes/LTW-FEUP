<?php
$auth = Auth::demandLevel('none');

$body = HTTPRequest::parseBody(['username', 'email', 'password']);
$username = $body['username'];
$useremail = $body['useremail'];
$password = $body['password'];

if (!User::validUsername($username)) {
    HTTPResponse::invalid('username', User::$usernameRequires);
}

if (!User::validEmail($useremail)) {
    HTTPResponse::invalid('email', 'Valid email');
}

if (!User::validPassword($password)) {
    HTTPResponse::invalid('password', User::$passwordRequires);
}

if (User::getByUsername($username)) {
    HTTPResponse::conflict("Already existing username", 'username', $username);
}

if (User::getByEmail($useremail)) {
    HTTPResponse::conflict("Email already in use", 'email', $useremail);
}

$userid = User::create($username, $useremail, $password);

if (!$userid) {
    HTTPResponse::serverError();
}

$user = User::self($userid);

$data = [
    'userid' => $userid,
    'user' => $user
];

HTTPResponse::created("Successfully created account $userid", $data);
?>
