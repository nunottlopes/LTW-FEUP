<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('story');

$resource = 'story';

$methods = ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE'];

$actions = [
    'create'                => ['POST', ['channelid', 'authorid'], ['storyTitle', 'storyType', 'content']],
    'delete-id'             => ['DELETE', ['storyid']],
    'delete-channel-author' => ['DELETE', ['channelid', 'authorid']],
    'delete-channel'        => ['DELETE', ['channelid']],
    'delete-author'         => ['DELETE', ['authorid']],
    'edit'                  => ['PATCH', ['storyid'], ['content']],
    'get-id'                => ['GET', ['storyid']],
    'get-channel-author'    => ['GET', ['channelid', 'authorid']],
    'get-channel'           => ['GET', ['channelid']],
    'get-author'            => ['GET', ['authorid']],
    'get-all'               => ['GET', ['all']]
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parseQuery();

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::look();
    }
    if (API::gotargs('storyid')) {
        API::action('get-id');
    }
    if (API::gotargs('channel', 'authorid')) {
        API::action('get-channel-user');
    }
    if (API::gotargs('channelid')) {
        API::action('get-channel');
    }
    if (API::gotargs('authorid')) {
        API::action('get-user');
    }
    if (API::gotargs('all')) {
        API::action('get-all');
    }
    break;
case 'POST':
    if (API::gotargs('channelid', 'authorid')) {
        API::action('create');
    }
    break;
case 'PATCH':
    if (API::gotargs('storyid')) {
        API::action('edit');
    }
    break;
case 'DELETE':
    if (API::gotargs('storyid')) {
        API::action('delete-id');
    }
    if (API::gotargs('channelid', 'authorid')) {
        API::action('delete-channel-author');
    }
    if (API::gotargs('channelid')) {
        API::action('delete-channel');
    }
    if (API::gotargs('authorid')) {
        API::action('delete-author');
    }
    break;
}

HTTPResponse::noAction();
?>
