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
    if (API::gotargs('storyid')) {
        API::action('read');
    }
    if (API::gotargs('all')) {
        API::action('read-all');
    }
    if (API::gotargs('authorid', 'channelid')) {
        API::action('get-channel-user');
    }
    if (API::gotargs('authorid')) {
        API::action('get-user');
    }
    if (API::gotargs('channelid')) {
        API::action('get-channel');
    }
    break;
case 'POST':
    if (API::gotargs('channelid', 'storyTitle', 'storyType', 'content')) {
        API::action('create');
    }
    HTTPResponse::missingParameters(['channelid', 'storyTitle', 'storyType', 'content']);
    break;
case 'PATCH':
    if (API::gotargs('storyid', 'content')) {
        API::action('update');
    }
    HTTPResponse::missingParameters(['storyid', 'content']);
    break;
case 'DELETE':
    if (API::gotargs('storyid', 'confirm-delete')) {
        API::action('delete');
    }
    HTTPResponse::missingParameters(['storyid', 'confirm-delete']);
    break;
}

HTTPResponse::noAction();
?>
