<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('entity');

$resource = 'entity';

$methods = ['GET', 'HEAD'];

$parameters = ['entityid', 'all'];

$actions = [
    'read'     => ['GET', 'entityid'],
    'read-all' => ['GET', 'all'],
    'look'     => ['GET']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (API::gotargs('entityid')) {
        API::action('read');
    }
    if (API::gotargs('all')) {
        API::action('read-all');
    }
    break;
}

HTTPResponse::noAction();
?>
