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
    if (API::gotargs('channelid')) {
        API::action('read');
    }
    if (API::gotargs('all')) {
        API::action('read-all');
    }
    if (API::gotargs('valid', 'channelname')) {
        API::action('valid');
    }
    if (API::gotargs('channelname')) {
        API::action('get');
    }
    break;
case 'POST':
    if (API::gotargs('channelname', 'confirm')) {
        API::action('create');
    }
    HTTPResponse::missingParameters(['channelname', 'confirm']);
    break;
case 'DELETE':
    if (API::gotargs('channelid', 'confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::missingParameters(['channelid', 'confirm-delete']);
    break;
}

HTTPResponse::noAction();
?>
