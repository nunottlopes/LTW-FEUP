<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('user');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'user';

$methods = ['GET', 'PUT'];

$actions = [
    'login'  => ['PUT', ['username', 'login'], ['password']],
    'logout' => ['GET', ['logout']]
];

/**
 * 1.2. LOAD request description variables
 */
$auth = Auth::demandLevel('free');

$method = HTTPRequest::requireMethod($methods);

$args = HTTPRequest::query($method, $actions, $action);

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

    if ($auth === true) {
        HTTPResponse::accepted("Not logged in");
    } else {
        HTTPResponse::accepted("Successfully logged out $authname");
    }
}
?>
