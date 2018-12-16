<?php
require_once __DIR__ . '/../../../api/api.php';
require_once API::entity('tree');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'tree';

$methods = ['GET'];

$actions = [
    'get-tree-voted'     => ['GET', ['voterid', 'ascendantid'], [], ['order', 'maxdepth', 'since', 'limit', 'offset']],
    'get-tree'           => ['GET', ['ascendantid'], [], ['order', 'maxdepth', 'since', 'limit', 'offset']],
    'get-ancestry-voted' => ['GET', ['voterid', 'descendantid']],
    'get-ancestry'       => ['GET', ['descendantid']]
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * TREE: ascendantid, descendantid, voterid
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
// voterid
if (API::gotargs('voterid')) {
    $userid = $args['voterid'];

    $user = User::read($userid);

    if (!$user) {
        HTTPResponse::notFound("User with id $userid");
    }

    $auth = Auth::demandLevel('authid', $userid);
}

/**
 * 3. ANSWER: HTTPResponse
 */
// GET
if ($action === 'get-tree-voted') {
    $tree = Tree::getTreeVoted($ascendantid, $userid, $args);

    HTTPResponse::ok("Comment tree on $ascendantid (voted by $userid)", $tree);
}

if ($action === 'get-tree') {
    $tree = Tree::getTree($ascendantid, $args);

    HTTPResponse::ok("Comment tree on $ascendantid", $tree);
}

if ($action === 'get-ancestry-voted') {
    $ancestry = Tree::getAncestryVoted($descendantid, $userid);

    HTTPResponse::ok("Ancestry of $descendantid (voted by $userid)", $ancestry);
}

if ($action === 'get-ancestry') {
    $ancestry = Tree::getAncestry($descendantid);

    HTTPResponse::ok("Ancestry of $descendantid", $ancestry);
}
?>
