<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('vote');

$resource = 'vote';

$methods = ['GET', 'HEAD', 'PUT', 'DELETE'];

$parameters = ['entityid', 'userid', 'vote', 'upvote', 'downvote', 'all'];

$actions = [
    'upvote'          => ['PUT', 'entityid', 'upvote'],
    'downvote'        => ['PUT', 'entityid', 'downvote'],
    'get'             => ['GET', 'entityid', 'userid'],
    'get-entity'      => ['GET', 'entityid'],
    'get-user'        => ['GET', 'userid'],
    'read-all'        => ['GET', 'all'],
    'look'            => ['GET'],
    'delete'          => ['DELETE', 'entityid']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (got('userid') && got('entityid')) {
        API::action('get');
    }
    if (got('userid')) {
        API::action('get-user');
    }
    if (got('entityid')) {
        API::action('get-entity');
    }
    if (got('all')) {
        API::action('read-all');
    }
    break;
case 'PUT':
    if (got('entityid') && got('upvote')) {
        API::action('upvote');
    }
    if (got('entityid') && got('downvote')) {
        API::action('downvote');
    }
    break;
case 'DELETE':
    if (got('entityid')) {
        API::action('delete');
    }
    break;
}

HTTPResponse::noAction();
?>
