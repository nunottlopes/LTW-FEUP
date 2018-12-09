<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('entity');

$resource = 'entity';

$methods = ['GET', 'HEAD'];

$actions = [
    'get'     => ['GET', ['entityid']],
    'get-all' => ['GET', ['all']]
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parseQuery();

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::look();
    }
    if (API::gotargs('entityid')) {
        API::action('get');
    }
    if (API::gotargs('all')) {
        API::action('get-all');
    }
    break;
}

HTTPResponse::noAction();
?>
