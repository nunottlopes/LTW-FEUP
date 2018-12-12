<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('tree');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'tree';

$methods = ['GET'];

$actions = [
    'get-tree'     => ['GET', ['ascendantid'], [], ['order', 'maxdepth', 'limit', 'offset']],
    'get-ancestry' => ['GET', ['descendantid']]
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

$auth = Auth::demandLevel('free');

/**
 * 2. GET: Check query parameter identifying resources
 * TREE: ascendantid, descendantid
 */
// ascendantid
if (API::gotargs('ascendantid')) {
    $ascendantid = $args['ascendantid'];

    $parent = Entity::read($ascendantid);

    if (!$parent) {
        HTTPResponse::notFound("Ascendant Entity with id $ascendantid");
    }
}

// descendantid
if (API::gotargs('descendantid')) {
    $descendantid = $args['descendantid'];

    $child = Comment::read($descendantid);

    if (!$child) {
        HTTPResponse::notFound("Descendant comment with id $descendantid");
    }
}

/**
 * 3. ANSWER: HTTPResponse
 */
// GET
if ($action === 'get-tree') {
    $tree = Tree::getTree($ascendantid, $args);

    HTTPResponse::ok("Comment tree on $ascendantid", $tree);
}

if ($action === 'get-ancestry') {
    $ancestry = Tree::getAncestry($descendantid);

    HTTPResponse::ok("Ancestry of $descendantid", $ancestry);
}
?>
