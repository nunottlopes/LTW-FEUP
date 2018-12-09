<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('comment');

$resource = 'comment';

$methods = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE'];

$actions = [
    'create'               => ['POST', ['parentid', 'authorid', 'content']],
    'delete-id'            => ['DELETE', ['commentid']],
    'delete-parent-author' => ['DELETE', ['parentid', 'authorid']],
    'delete-parent'        => ['DELETE', ['parentid']],
    'delete-author'        => ['DELETE', ['authorid']],
    'edit'                 => ['PUT', ['commentid'], [], ['content']],
    'get-id'               => ['GET', ['commentid']],
    'get-parent-author'    => ['GET', ['parentid', 'authorid']],
    'get-parent'           => ['GET', ['parentid']],
    'get-author'           => ['GET', ['authorid']],
    'get-all'              => ['GET', ['all']]
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parseQuery();

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::look();
    }
    if (API::gotargs('commentid')) {
        API::action('get-id');
    }
    if (API::gotargs('parentid', 'authorid')) {
        API::action('get-parent-author');
    }
    if (API::gotargs('parentid')) {
        API::action('get-parent');
    }
    if (API::gotargs('authorid')) {
        API::action('get-author');
    }
    if (API::gotargs('all')) {
        API::action('get-all');
    }
    break;
case 'POST':
    if (API::gotargs('parentid')) {
        API::action('create');
    }
    break;
case 'PUT':
    if (API::gotargs('commentid')) {
        API::action('edit');
    }
    break;
case 'DELETE':
    if (API::gotargs('commentid')) {
        API::action('delete-id');
    }
    if (API::gotargs('parentid', 'authorid')) {
        API::action('delete-parent-author');
    }
    if (API::gotargs('parentid')) {
        API::action('delete-parent');
    }
    if (API::gotargs('authorid')) {
        API::action('delete-author');
    }
    break;
}

HTTPResponse::noAction();
?>
