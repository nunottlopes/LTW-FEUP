<?php
require_once __DIR__ . '/../utils/http.php';
require_once API::entity('user');

$resource = 'user';

$supported = ['GET', 'HEAD', 'POST', 'DELETE'];

$parameters = ['self', 'userid', 'username', 'email', 'password', 'confirm',
                'confirm-delete', 'all'];

$method = HTTPRequest::method($supported, true);

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
    if (got('username')) {
        API::action('get-by-username');
    }
    if (got('email')) {
        API::action('get-by-email');
    }
    if (got('all')) {
        API::action('read-all');
    }
    break;
case 'POST':
    if (got('username') && got('email') && got('password') && got('confirm')) {
        API::action('create-self');
    }
    break;
case 'DELETE':
    if (got('userid') && got('confirm-delete')) {
        API::action('delete');
    }
    if (got('confirm-delete')) {
        API::action('delete-self');
    }
    break;
}

HTTPResponse::noAction($method);
?>
