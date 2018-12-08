<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('story');
require_once API::entity('comment');

$resource = 'comment';

$methods = ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE'];

$parameters = ['parentid', 'commentid', 'content', 'userid', 'authorid',
                'all', 'confirm-delete'];

$actions = [
    'create'            => ['POST', 'parentid', 'content'],
    'delete'            => ['DELETE', 'commentid', 'confirm-delete'],
    'get-children-user' => ['GET', 'parentid', 'authorid'],
    'get-children'      => ['GET', 'parentid'],
    'get-user'          => ['GET', 'authorid'],
    'read-all'          => ['GET', 'all'],
    'read'              => ['GET', 'commentid'],
    'look'              => ['GET'],
    'update'            => ['PATCH', 'commentid', 'content']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (got('commentid')) {
        API::action('read');
    }
    if (got('all')) {
        API::action('read-all');
    }
    if (got('authorid') && got('parentid')) {
        API::action('get-children-user');
    }
    if (got('authorid')) {
        API::action('get-user');
    }
    if (got('parentid')) {
        API::action('get-children');
    }
    break;
case 'POST':
    if (got('parentid') && got('content')) {
        API::action('create');
    }
    break;
case 'PATCH':
    if (got('commentid') && got('content')) {
        API::action('update');
    }
    break;
case 'DELETE':
    if (got('commentid') && got('confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::noConfirmDelete();
    break;
}

HTTPResponse::noAction();
?>
