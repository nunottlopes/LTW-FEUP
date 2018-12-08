<?php
require_once __DIR__ . '/../utils/http.php';
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
    if (got('self')) {
        API::action('self');
    }
    if (got('userid')) {
        API::action('read');
    }
    if (got('all')) {
        API::action('read-all');
    }
    if (got('valid') && got('username') && got('email')) {
        API::action('valid');
    }
    if (got('valid') && got('username')) {
        API::action('valid-username');
    }
    if (got('valid') && got('email')) {
        API::action('valid-email');
    }
    if (got('username')) {
        API::action('get-by-username');
    }
    if (got('email')) {
        API::action('get-by-email');
    }
    if (got('admin')) {
        API::action('admin');
    }
    break;
case 'POST':
    if (got('username') && got('email') && got('password') && got('confirm')) {
        API::action('create');
    }
    HTTPResponse::noConfirm("Account creation");
    break;
case 'DELETE':
    if (got('confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::noConfirmDelete();
    break;
}

HTTPResponse::noAction();
?>
