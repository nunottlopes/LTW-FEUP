<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
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
    'get-ancestry'       => ['GET', ['descendantid']],
    'get-storyof-voted'  => ['GET', ['voterid', 'commentid', 'storyof']],
    'get-storyof'        => ['GET', ['commentid', 'storyof']]
];

/**
 * 1.2. LOAD request description variables
 */
$auth = Auth::demandLevel('free');

$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * TREE: ascendantid, descendantid, voterid, storyof
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
// commentid
if (API::gotargs('commentid')) {
    $commentid = $args['commentid'];

    $comment = Comment::read($commentid);

    if (!$comment) {
        HTTPResponse::notFound("Comment with id $commentid");
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

if ($action === 'get-storyof-voted') {
    $story = Tree::getStoryOfVoted($commentid, $userid);

    HTTPResponse::ok("Story of comment $commentid (voted by $userid)", $story);
}

if ($action === 'get-storyof') {
    $story = Tree::getStoryOf($commentid);

    HTTPResponse::ok("Story of comment $commentid", $story);
}
?>
