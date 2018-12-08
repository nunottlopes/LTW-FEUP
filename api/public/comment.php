<?php
require_once __DIR__ . '/../utils/http.php';
require_once API::entity('story');
require_once API::entity('comment');

$resource = 'comment';

$supported = ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE'];

$parameters = ['commentid', 'authorid', 'parentid', 'content',
                'confirm-delete', 'all'];

$method = HTTPRequest::method($supported, true);

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
    break;
}

HTTPResponse::noAction($method);
?>
