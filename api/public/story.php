<?php
require_once __DIR__ . '/../utils/http.php';
require_once API::entity('story');

$resource = 'story';

$supported = ['GET', 'HEAD', 'POST', 'PATCH', 'DELETE'];

$parameters = ['storyid', 'authorid', 'channelid', 'storyTitle', 'storyType', 'content', 'confirm'];

$method = HTTPRequest::method($supported, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if (isset($args['storyid'])) {
        API::action('read');
    }
    if (isset($args['authorid']) && isset($args['channelid'])) {
        API::action('get-channel-user');
    }
    if (isset($args['authorid'])) {
        API::action('get-user');
    }
    if (isset($args['channelid'])) {
        API::action('get-channel');
    }
    if (isset($args['confirm'])) {
        API::action('read-all');
    }
    if ($args === []) {
        API::action('look');
    }
    break;
case 'POST':
    if (isset($args['channelid']) && isset($args['storyTitle']) &&
        isset($args['storyType']) && isset($args['content'])) {
        API::action('create');
    }
    break;
case 'PATCH':
    if (isset($args['storyid']) && isset($args['content'])) {
        API::action('update');
    }
    break;
case 'DELETE':
    if (isset($args['storyid'])) {
        API::action('delete');
    }
    break;
}

HTTPResponse::noAction($method);
?>
