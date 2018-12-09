<?php
require_once __DIR__ . '/../api.php';
require_once API::entity('save');

$resource = 'save';

$methods = ['GET', 'HEAD', 'PUT', 'DELETE'];

$parameters = ['entityid', 'userid', 'commentid', 'storyid', 'comments', 'entities', 'stories', 'all'];

$actions = [
    'create'            => ['PUT', 'entityid'],
    'delete'            => ['DELETE', 'entityid'],
    'get-comment'       => ['GET', 'commentid'],
    'get-entity'        => ['GET', 'entityid'],
    'get-story'         => ['GET', 'storyid'],
    'get-user-comments' => ['GET', 'comments'],
    'get-user-entities' => ['GET', 'entities'],
    'get-user-stories'  => ['GET', 'stories'],
    'read-all'          => ['GET', 'all'],
    'look'              => ['GET']
];

$method = HTTPRequest::method($methods, true);

$args = HTTPRequest::parse($parameters);

switch ($method) {
case 'GET':
case 'HEAD':
    if ($args === []) {
        API::action('look');
    }
    if (API::gotargs('all')) {
        API::action('read-all');
    }
    if (API::gotargs('stories')) {
        API::action('get-user-stories');
    }
    if (API::gotargs('comments')) {
        API::action('get-user-comments');
    }
    if (API::gotargs('entities')) {
        API::action('get-user-entities');
    }
    if (API::gotargs('storyid')) {
        API::action('get-story');
    }
    if (API::gotargs('commentid')) {
        API::action('get-comment');
    }
    if (API::gotargs('entityid')) {
        API::action('get-entity');
    }
    break;
case 'PUT':
    if (API::gotargs('entityid')) {
        API::action('create');
    }
    HTTPResponse::missingParameters(['entityid']);
    break;
case 'DELETE':
    if (API::gotargs('entityid')) {
        API::action('delete');
    }
    HTTPResponse::missingParameters(['entityid']);
    break;
}

HTTPResponse::noAction();
?>
