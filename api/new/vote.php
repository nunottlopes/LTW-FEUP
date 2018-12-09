<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('vote');

$resource = 'vote';

$methods = ['GET', 'HEAD', 'PUT', 'DELETE'];

$actions = [
    'put'             => ['PUT', ['entityid']],
    'get--id'         => ['GET', ['entityid', 'userid']],
    'get-entity'      => ['GET', ['entityid']],
    'get-user'        => ['GET', ['userid']],
    'get-all'         => ['GET', ['all']],
    'delete'          => ['DELETE', ['entityid']]
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parseQuery();

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (API::gotargs('entityid', 'userid')) {
        API::action('get-id');
    }
    if (API::gotargs('entityid')) {
        API::action('get-entity');
    }
    if (API::gotargs('userid')) {
        API::action('get-user');
    }
    if (API::gotargs('all')) {
        API::action('get-all');
    }
    break;
case 'PUT':
    if (API::gotargs('entityid')) {
        API::action('put');
    }
    break;
case 'DELETE':
    if (API::gotargs('entityid')) {
        API::action('delete');
    }
    break;
}

HTTPResponse::noAction();
?>
