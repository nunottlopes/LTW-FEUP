<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('tree');

$resource = 'tree';

$methods = ['GET', 'HEAD'];

$parameters = ['parentid', 'childid'];

$actions = [
    'get-tree'     => ['GET', 'parentid'],
    'get-ancestry' => ['GET', 'childid'],
    'look'         => ['GET']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (API::gotargs('parentid')) {
        API::action('get-tree');
    }
    if (API::gotargs('childid')) {
        API::action('get-ancestry');
    }
    break;
}

HTTPResponse::noAction();
?>
