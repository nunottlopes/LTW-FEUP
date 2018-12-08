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
    if (API::gotargs('userid', 'entityid')) {
        API::action('get');
    }
    if (API::gotargs('userid')) {
        API::action('get-user');
    }
    if (API::gotargs('entityid')) {
        API::action('get-entity');
    }
    if (API::gotargs('all')) {
        API::action('read-all');
    }
    break;
case 'PUT':
    if (API::gotargs('entityid', 'upvote')) {
        API::action('upvote');
    }
    if (API::gotargs('entityid', 'downvote')) {
        API::action('downvote');
    }
    HTTPResponse::missingParameters(['entityid', '*vote']);
    break;
case 'DELETE':
    if (API::gotargs('entityid')) {
        API::action('delete');
    }
    HTTPResponse::missingParameters(['entityid']);
    break;
}

HTTPResponse::noAction();
?>
