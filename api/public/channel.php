<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('channel');

$resource = 'channel';

$methods = ['GET', 'HEAD', 'POST', 'DELETE'];

$parameters = ['channelid', 'channelname', 'valid', 'confirm-delete', 'all'];

$actions = [
    'create'   => ['POST', 'channelname', 'confirm'],
    'delete'   => ['DELETE', 'channelid', 'confirm-delete'],
    'get'      => ['GET', 'channelname'],
    'read-all' => ['GET', 'all'],
    'read'     => ['GET', 'channelid'],
    'look'     => ['GET'],
    'valid'    => ['GET', 'channelname', 'valid']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (got('channelid')) {
        API::action('read');
    }
    if (got('all')) {
        API::action('read-all');
    }
    if (got('valid', 'channelname')) {
        API::action('valid');
    }
    if (got('channelname')) {
        API::action('get');
    }
    break;
case 'POST':
    if (got('channelname', 'confirm')) {
        API::action('create');
    }
    HTTPResponse::noConfirm("Channel creation");
    break;
case 'DELETE':
    if (got('channelid', 'confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::noConfirmDelete();
    break;
}

HTTPResponse::noAction();
?>
