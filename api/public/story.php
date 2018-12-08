<?php
require_once __DIR__ . '/../utils/http.php';
require_once API::entity('story');

$resource = 'story';

$supported = ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE'];

$parameters = ['storyid', 'authorid', 'channelid', 'storyTitle', 'storyType',
                'content', 'confirm-delete', 'all'];

$method = HTTPRequest::method($supported, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if (got('storyid')) {
        API::action('read');
    }
    if (got('authorid') && got('channelid')) {
        API::action('get-channel-user');
    }
    if (got('authorid')) {
        API::action('get-user');
    }
    if (got('channelid')) {
        API::action('get-channel');
    }
    if (got('all')) {
        API::action('read-all');
    }
    if ($args === []) {
        API::action('look');
    }
    break;
case 'POST':
    if (got('channelid') && got('storyTitle') &&
        got('storyType') && got('content')) {
        API::action('create');
    }
    break;
case 'PATCH':
    if (got('storyid') && got('content')) {
        API::action('update');
    }
    break;
case 'DELETE':
    if (got('storyid') && got('confirm-delete')) {
        API::action('delete');
    }
    break;
}

HTTPResponse::noAction($method);
?>
