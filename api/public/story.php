<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('story');

$resource = 'story';

$methods = ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE'];

$parameters = ['storyid', 'userid', 'authorid', 'channelid', 'storyTitle',
                'storyType', 'content', 'confirm-delete', 'all'];

$actions = [
    'create'           => ['POST', 'channelid', 'storyTitle', 'storyType', 'content'],
    'delete'           => ['DELETE', 'storyid', 'confirm-delete'],
    'get-channel-user' => ['GET', 'authorid', 'channelid'],
    'get-channel'      => ['GET', 'channelid'],
    'get-user'         => ['GET', 'authorid'],
    'read-all'         => ['GET', 'all'],
    'read'             => ['GET', 'storyid'],
    'look'             => ['GET'],
    'update'           => ['PATCH', 'storyid', 'content'],
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (got('storyid')) {
        API::action('read');
    }
    if (got('all')) {
        API::action('read-all');
    }
    if (got('authorid', 'channelid')) {
        API::action('get-channel-user');
    }
    if (got('authorid')) {
        API::action('get-user');
    }
    if (got('channelid')) {
        API::action('get-channel');
    }
    break;
case 'POST':
    if (got('channelid', 'storyTitle') &&
        got('storyType', 'content')) {
        API::action('create');
    }
    break;
case 'PATCH':
    if (got('storyid', 'content')) {
        API::action('update');
    }
    break;
case 'DELETE':
    if (got('storyid', 'confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::noConfirmDelete();
    break;
}

HTTPResponse::noAction();
?>
