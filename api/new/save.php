<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('story');

$resource = 'story';

$methods = ['GET', 'HEAD', 'PUT' 'DELETE'];

$actions = [
    'create'            => ['PUT', ['entityid']],
    'delete-id'         => ['DELETE', ['entityid', 'userid']],
    'delete-user'       => ['DELETE', ['userid']],
    'delete-entity'     => ['DELETE', ['entityid']],
    'get-entity'        => ['GET', ['entityid']],
    'get-comment'       => ['GET', ['commentid']],
    'get-story'         => ['GET', ['storyid']],
    'get-user-all'      => ['GET', ['userid', 'all']],
    'get-user-comments' => ['GET', ['userid', 'comments']],
    'get-user-stories'  => ['GET', ['userid', 'stories']],
    'get-all'           => ['GET', ['all']]
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parseQuery();

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::look();
    }
    if (API::gotargs('entityid')) {
        API::action('get-entity');
    }
    if (API::gotargs('storyid')) {
        API::action('get-story');
    }
    if (API::gotargs('commentid')) {
        API::action('get-comment');
    }
    if (API::gotargs('userid', 'all')) {
        API::action('get-user-all');
    }
    if (API::gotargs('userid', 'stories')) {
        API::action('get-user-stories');
    }
    if (API::gotargs('userid', 'comments')) {
        API::action('get-user-comments');
    }
    if (API::gotargs('all')) {
        API::action('get-all');
    }
    break;
case 'PUT':
    if (API::gotargs('entityid')) {
        API::action('create');
    }
    break;
case 'DELETE':
    if (API::gotargs('entityid', 'userid')) {
        API::action('delete-id');
    }
    if (API::gotargs('userid')) {
        API::action('delete-user');
    }
    if (API::gotargs('entityid')) {
        API::action('delete-entity');
    }
    break;
}

HTTPResponse::noAction();
?>
