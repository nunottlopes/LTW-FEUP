<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('user');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'login';

$methods = ['GET', 'PUT'];

$actions = [
    'login'  => ['PUT', ['username', 'login'], ['password']],
    'logout' => ['GET', ['logout']],
    'auth'   => ['GET', ['auth']]
];

/**
 * 1.2. LOAD request description variables
 */
$auth = Auth::demandLevel('free');

$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * USER: userid, username, email, all, self, valid, admin
 */
// username
if (API::gotargs('username')) {
    $username = $args['username'];
}

/**
 * 3. ANSWER: HTTPResponse
 */
// PUT
if ($action === 'login') {
    $password = HTTPRequest::body('password');

    $auth = Auth::login($username, $password, $error);

    if (!$auth) {
        HTTPResponse::wrongCredentials($error);
    }

    $user = User::get($username);

    $self = User::self($user['userid']);

    HTTPResponse::accepted("Successfully logged in user $username", $self);
}

// GET
if ($action === 'logout') {
    Auth::logout();

    if ($auth == null) {
        HTTPResponse::accepted("Not logged in");
    } else {
        $authname = $auth['username'];
        HTTPResponse::accepted("Successfully logged out $authname");
    }
}

if ($action === 'auth') {
    HTTPResponse::ok("Login query", $auth);
}
?>
