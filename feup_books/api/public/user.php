<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('user');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'user';

$methods = ['GET', 'POST', 'DELETE'];

$actions = [
    'create'          => ['POST', [], ['username', 'email', 'password']],

    'get-id'          => ['GET', ['userid']],
    'get-username'    => ['GET', ['username']],
    'get-email'       => ['GET', ['email']],
    'get-all'         => ['GET', ['all']],
    'get-self'        => ['GET', ['self']],
    'valid-username'  => ['GET', ['valid-username']],
    'valid-email'     => ['GET', ['valid-email']],

    'delete-id'       => ['DELETE', ['userid']],
    'delete-username' => ['DELETE', ['username']],
    'delete-email'    => ['DELETE', ['email']]
    // no delete-all for users
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * USER: userid, username, email, all, self, valid, admin
 */
// userid
if (API::gotargs('userid')) {
    $userid = $args['userid'];

    $user = User::read($userid);

    if (!$user) {
        HTTPResponse::notFound("User with id $userid");
    }

    $username = $user['username'];
    $useremail = $user['email'];
}
// username
if (API::gotargs('username')) {
    $username = $args['username'];

    $user = User::getByUsername($username);

    if (!$user) {
        HTTPResponse::notFound("User $username");
    }

    $userid = $user['userid'];
    $useremail = $user['email'];
}
// email
if (API::gotargs('email')) {
    $useremail = $args['email'];

    $user = User::getByEmail($useremail);

    if (!$user) {
        HTTPResponse::notFound("User $useremail");
    }

    $userid = $user['userid'];
    $username = $user['username'];
}
// self
if (API::gotargs('self')) {
    $auth = Auth::demandLevel('auth');

    $userid = $auth['userid'];

    $user = User::self($userid);
}
// valid-username
if (API::gotargs('valid-username')) {
    $username = $args['valid-username'];

    $valid = User::validUsername($username);

    $validString = $valid ? "valid" : "invalid";
}
// valid-email
if (API::gotargs('valid-email')) {
    $useremail = $args['valid-email'];

    $valid = User::validEmail($useremail);

    $validString = $valid ? "valid" : "invalid";
}

/**
 * 3. ANSWER: HTTPResponse
 */
// PUT
if ($action === 'create') {
    $body = HTTPRequest::body('username', 'email', 'password');

    $username = $body['username'];
    $useremail = $body['email'];
    $password = $body['password'];

    if (!User::validUsername($username)) {
        HTTPResponse::invalid('username', $username, User::$usernameRequires);
    }

    if (!User::validEmail($useremail)) {
        HTTPResponse::invalid('email', $useremail, 'Valid email');
    }

    if (!User::validPassword($password)) {
        HTTPResponse::invalid('password', null, User::$passwordRequires);
    }

    if (User::getByUsername($username)) {
        HTTPResponse::conflict('username', $username, "Already existing username");
    }

    if (User::getByEmail($useremail)) {
        HTTPResponse::conflict('email', $useremail, "Email already in use");
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

    HTTPResponse::created("Successfully created user account $userid", $data);
}

// GET
if ($action === 'get-id') {
    HTTPResponse::ok("User $userid", $user);
}

if ($action === 'get-username') {
    HTTPResponse::ok("User $username", $user);
}

if ($action === 'get-email') {
    HTTPResponse::ok("User $useremail", $user);
}

if ($action === 'get-all') {
    $users = User::readAll();

    HTTPResponse::ok("All users", $users);
}

if ($action === 'get-self') {
    HTTPResponse::ok("User $userid (self)", $user);
}

if ($action === 'valid-username') {
    $data = [
        'username' => $username,
        'valid' => $valid,
        'text' => $validString
    ];

    HTTPResponse::ok("Username $username is $validString", $data);
}

if ($action === 'valid-email') {
    $data = [
        'email' => $useremail,
        'valid' => $valid,
        'text' => $validString
    ];

    HTTPResponse::ok("User email $useremail is $validString", $data);
}

if ($action === 'admin') {
    HTTPResponse::accepted("Authenticated as admin");
}

// DELETE
if ($action === 'delete-id') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = User::delete($userid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted user $userid", $data);
}

if ($action === 'delete-username') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = User::delete($userid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted user $username", $data);
}

if ($action === 'delete-email') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = User::delete($userid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted user $useremail", $data);
}
?>
