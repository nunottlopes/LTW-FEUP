<?php
require_once __DIR__ . '/../utils/http.php';
require_once API::entity('save');

$resource = 'save';

$methods = ['GET', 'HEAD', 'PUT', 'DELETE'];

$parameters = ['entityid', 'commentid', 'storyid', 'comments', 'entities', 'stories'];

$actions = [
    'create'            => ['PUT', 'entityid'],
    'delete'            => ['DELETE', 'entityid'],
    'get-comment'       => ['GET', 'commentid'],
    'get-entity'        => ['GET', 'entityid'],
    'get-story'         => ['GET', 'storyid'],
    'get-user-comments' => ['GET', 'comments'],
    'get-user-entities' => ['GET', 'entities'],
    'get-user-stories'  => ['GET', 'stories'],
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
    if (got('stories')) {
        API::action('get-user-stories');
    }
    if (got('comments')) {
        API::action('get-user-comments');
    }
    if (got('entities')) {
        API::action('get-user-entities');
    }
    if (got('storyid')) {
        API::action('get-story');
    }
    if (got('commentid')) {
        API::action('get-comment');
    }
    if (got('entityid')) {
        API::action('get-entity');
    }
    break;
case 'PUT':
    if (got('entityid')) {
        API::action('create');
    }
    break;
case 'DELETE':
    if (got('entityid')) {
        API::action('delete');
    }
    break;
}

HTTPResponse::noAction();
?>
