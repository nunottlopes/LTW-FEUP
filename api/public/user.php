<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('user');

$resource = 'user';

$methods = ['GET', 'HEAD', 'POST', 'DELETE'];

$parameters = ['self', 'userid', 'username', 'email', 'password', 'confirm',
                'confirm-delete', 'all', 'valid', 'admin'];

$actions = [
    'create'           => ['POST', 'username', 'email', 'password', 'confirm'],
    'delete'           => ['DELETE', 'confirm-delete', 'userid'],
    'get-by-email'     => ['GET', 'email'],
    'get-by-username'  => ['GET', 'username'],
    'read-all'         => ['GET', 'all'],
    'read'             => ['GET', 'userid'],
    'self'             => ['GET', 'self'],
    'valid-username'   => ['GET', 'valid', 'username'],
    'valid-email'      => ['GET', 'valid', 'email'],
    'valid'            => ['GET', 'valid', 'username', 'email'],
    'look'             => ['GET'],
    'admin'            => ['GET', 'admin']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (API::gotargs('self')) {
        API::action('self');
    }
    if (API::gotargs('userid')) {
        API::action('read');
    }
    if (API::gotargs('all')) {
        API::action('read-all');
    }
    if (API::gotargs('valid', 'username', 'email')) {
        API::action('valid');
    }
    if (API::gotargs('valid', 'username')) {
        API::action('valid-username');
    }
    if (API::gotargs('valid', 'email')) {
        API::action('valid-email');
    }
    if (API::gotargs('username')) {
        API::action('get-by-username');
    }
    if (API::gotargs('email')) {
        API::action('get-by-email');
    }
    if (API::gotargs('admin')) {
        API::action('admin');
    }
    break;
case 'POST':
    if (API::gotargs('username', 'email', 'password', 'confirm')) {
        API::action('create');
    }
    HTTPResponse::missingParameters(['username', 'email', 'password', 'confirm']);
    break;
case 'DELETE':
    if (API::gotargs('confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::missingParameters(['confirm-delete']);
    break;
}

HTTPResponse::noAction();
?>
