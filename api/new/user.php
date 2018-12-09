<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('user');

$resource = 'user';

$methods = ['GET', 'HEAD', 'PUT', 'DELETE'];

$actions = [
    'create'           => ['PUT', [], ['username', 'email', 'password']],
    'delete-id'        => ['DELETE', ['userid']],
    'delete-name'      => ['DELETE', ['username']],
    'delete-email'     => ['DELETE', ['email']],
    'get-id'           => ['GET', 'userid'],
    'get-by-username'  => ['GET', 'username'],
    'get-by-email'     => ['GET', 'email'],
    'get-all'          => ['GET', 'all'],
    'self'             => ['GET', 'self'],
    'valid'            => ['GET', 'valid', 'username', 'email'],
    'valid-username'   => ['GET', 'valid', 'username'],
    'valid-email'      => ['GET', 'valid', 'email'],
    'admin'            => ['GET', 'admin']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parseQuery();

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (API::gotargs('userid')) {
        API::action('get-id');
    }
    if (API::gotargs('username')) {
        API::action('get-by-username');
    }
    if (API::gotargs('email')) {
        API::action('get-by-email');
    }
    if (API::gotargs('all')) {
        API::action('get-all');
    }
    if (API::gotargs('self')) {
        API::action('self');
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
    if (API::gotargs('admin')) {
        API::action('admin');
    }
    break;
case 'PUT':
    API::action('create');
    break;
case 'DELETE':
    if (API::gotargs('userid')) {
        API::action('delete-id');
    }
    if (API::gotargs('username')) {
        API::action('delete-name');
    }
    if (API::gotargs('email')) {
        API::action('delete-email');
    }
    break;
}

HTTPResponse::noAction();
?>
