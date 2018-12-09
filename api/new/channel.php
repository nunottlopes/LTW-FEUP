<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('channel');

$resource = 'channel';

$methods = ['GET', 'HEAD', 'PUT', 'DELETE'];

$actions = [
    'create'      => ['PUT', ['channelname']],
    'delete-id'   => ['DELETE', ['channelid']],
    'delete-name' => ['DELETE', ['channelname']],
    'get-id'      => ['GET', ['channelid']],
    'get-name'    => ['GET', ['channelname']],
    'get-all'     => ['GET', ['all']],
    'valid'       => ['GET', ['valid', 'channelname']]
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parseQuery();

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::look();
    }
    if (API::gotargs('valid', 'channelname')) {
        API::action('valid');
    }
    if (API::gotargs('channelid')) {
        API::action('get-id');
    }
    if (API::gotargs('channelname')) {
        API::action('get-name');
    }
    if (API::gotargs('all')) {
        API::action('get-all');
    }
    break;
case 'POST':
    if (API::gotargs('channelname')) {
        API::action('create');
    }
    break;
case 'DELETE':
    if (API::gotargs('channelid')) {
        API::action('delete-id');
    }
    if (API::gotargs('channelname')) {
        API::action('delete-name');
    }
    break;
}

HTTPResponse::noAction();
?>
