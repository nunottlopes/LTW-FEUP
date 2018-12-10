<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('tree');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'tree';

$methods = ['GET'];

$actions = [
    'get-tree'     => ['GET', ['parentid']],
    'get-ancestry' => ['GET', ['childid']]
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::requireMethod($methods);

$args = HTTPRequest::query($method, $actions, $action);

$auth = Auth::demandLevel('free');

/**
 * 2. GET: Check query parameter identifying resources
 * TREE: parentid, childid
 */
// parentid
if (API::gotargs('parentid')) {
    $parentid = $args['parentid'];

    $parent = Entity::read($parentid);

    if (!$parent) {
        HTTPResponse::notFound("Parent Entity with id $parentid");
    }
}

// childid
if (API::gotargs('childid')) {
    $childid = $args['childid'];

    $child = Comment::read($childid);

    if (!$child) {
        HTTPResponse::notFound("Child comment with id $childid");
    }
}

/**
 * 3. ANSWER: HTTPResponse
 */
// GET
if ($action === 'get-tree') {
    $tree = Tree::getTree($parentid);

    HTTPResponse::ok("Comment tree on $parentid", $tree);
}

if ($action === 'get-ancestry') {
    $ancestry = Tree::getAncestry($childid);

    HTTPResponse::ok("Ancestry of $childid", $ancestry);
}
?>
