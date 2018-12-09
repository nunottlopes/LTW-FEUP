<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('comment');

$resource = 'comment';

$methods = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE'];

$actions = [
    'create'            => ['POST', ['parentid', 'content']],
    'delete'            => ['DELETE', ['commentid'], [], ['confirm-delete']],
    'get-children-user' => ['GET', 'parentid', 'authorid'],
    'get-children'      => ['GET', 'parentid'],
    'get-user'          => ['GET', 'authorid'],
    'read-all'          => ['GET', 'all'],
    'read'              => ['GET', 'commentid'],
    'look'              => ['GET'],
    'update'            => ['PUT', 'commentid', 'content']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (API::gotargs('commentid')) {
        API::action('read');
    }
    if (API::gotargs('all')) {
        API::action('read-all');
    }
    if (API::gotargs('authorid', 'parentid')) {
        API::action('get-children-user');
    }
    if (API::gotargs('authorid')) {
        API::action('get-user');
    }
    if (API::gotargs('parentid')) {
        API::action('get-children');
    }
    break;
case 'POST':
    if (API::gotargs('parentid', 'content')) {
        API::action('create');
    }
    HTTPResponse::missingParameter(['parentid', 'content']);
    break;
case 'PUT':
    if (API::gotargs('commentid', 'content')) {
        API::action('update');
    }
    HTTPResponse::missingParameter(['commentid', 'content']);
    break;
case 'DELETE':
    if (API::gotargs('commentid', 'confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::missingParameter(['commentid', 'confirm-delete']);
    break;
}

HTTPResponse::noAction();
?>
