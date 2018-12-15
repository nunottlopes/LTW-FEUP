<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('comment');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'comment';

$methods = ['GET', 'POST', 'PUT', 'DELETE'];

$actions = [
    'create'                  => ['POST', ['parentid', 'authorid'], ['content']],

    'clear'                   => ['PUT', ['commentid', 'clear']],
    'free'                    => ['PUT', ['commentid', 'free']],
    'edit'                    => ['PUT', ['commentid'], ['content']],

    'get-id-voted'            => ['GET', ['voterid', 'commentid']],
    'get-parent-author-voted' => ['GET', ['voterid', 'parentid', 'authorid'], [], ['order', 'since', 'limit', 'offset']],
    'get-parent-voted'        => ['GET', ['voterid', 'parentid'], [], ['order', 'since', 'limit', 'offset']],
    'get-author-voted'        => ['GET', ['voterid', 'authorid'], [], ['order', 'since', 'limit', 'offset']],
    'get-all-voted'           => ['GET', ['voterid', 'all'], [], ['order', 'since', 'limit', 'offset']],

    'get-id'                  => ['GET', ['commentid']],
    'get-parent-author'       => ['GET', ['parentid', 'authorid'], [], ['order', 'since', 'limit', 'offset']],
    'get-parent'              => ['GET', ['parentid'], [], ['order', 'since', 'limit', 'offset']],
    'get-author'              => ['GET', ['authorid'], [], ['order', 'since', 'limit', 'offset']],
    'get-all'                 => ['GET', ['all'], [], ['order', 'since', 'limit', 'offset']],

    'delete-id'               => ['DELETE', ['commentid']],
    'delete-parent-author'    => ['DELETE', ['parentid', 'authorid']],
    'delete-parent'           => ['DELETE', ['parentid']],
    'delete-author'           => ['DELETE', ['authorid']],
    'delete-all'              => ['DELETE', ['all']]
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * COMMENT: commentid, parentid, authorid, voterid, all
 */
// commentid
if (API::gotargs('commentid')) {
    $commentid = $args['commentid'];

    $comment = Comment::read($commentid);

    if (!$comment) {
        HTTPResponse::notFound("Comment with id $commentid");
    }

    $authorid = $comment['authorid'];
    $parentid = $comment['parentid'];
}
// parentid
if (API::gotargs('parentid')) {
    $parentid = $args['parentid'];

    $parent = Entity::read($parentid);

    if (!$parent) {
        HTTPResponse::notFound("Parent Entity with id $parentid");
    }
}
// authorid
if (API::gotargs('authorid')) {
    $authorid = $args['authorid'];

    $author = User::read($authorid);

    if (!$author) {
        HTTPResponse::notFound("User with id $authorid");
    }

    $authorname = $author['username'];
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
// POST
if ($action === 'create') {
    $auth = Auth::demandLevel('authid', $authorid);

    $content = htmlspecialchars(HTTPRequest::body('content'));

    $commentid = Comment::create($parentid, $authorid, $content);

    if (!$commentid) {
        HTTPResponse::serverError();
    }

    $comment = Comment::read($commentid);

    $data = [
        'commentid' => $commentid,
        'comment' => $comment
    ];

    HTTPResponse::created("Created comment $commentid, child of $parentid", $data);
}

// PUT
if ($action === 'edit') {
    $auth = Auth::demandLevel('authid', $authorid);

    $content = htmlspecialchars(HTTPRequest::body('content'));

    $count = Comment::update($commentid, $content);

    $comment = Comment::read($commentid);

    $data = [
        'count' => $count,
        'comment' => $comment
    ];

    HTTPResponse::updated("Comment $commentid successfully edited", $data);
}

if ($action === 'clear') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Comment::clear($commentid);

    $comment = Comment::read($commentid);

    $data = [
        'count' => $count,
        'comment' => $comment
    ];

    HTTPResponse::updated("Comment $commentid successfully cleared", $data);
}

if ($action === 'free') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Comment::free($commentid);

    $comment = Comment::read($commentid);

    $data = [
        'count' => $count,
        'comment' => $comment
    ];

    HTTPResponse::updated("Comment $commentid successfully freed", $data);
}

//GET
if ($action === 'get-id-voted') {
    $comment = Comment::readVoted($commentid, $userid);

    HTTPResponse::ok("Comment with id $commentid (voted by $userid)", $comment);
}

if ($action === 'get-parent-author-voted') {
    $comments = Comment::getChildrenAuthorVoted($parentid, $authorid, $userid, $args);

    HTTPResponse::ok("Comments of user $authorid children of $parentid (voted by $userid)", $comments);
}

if ($action === 'get-parent-voted') {
    $comments = Comment::getChildrenVoted($parentid, $userid, $args);

    HTTPResponse::ok("Comments children of $parentid (voted by $userid)", $comments);
}

if ($action === 'get-author-voted') {
    $comments = Comment::getAuthorVoted($authorid, $userid, $args);

    HTTPResponse::ok("Comments of user $authorid (voted by $userid)", $comments);
}

if ($action === 'get-all-voted') {
    $comments = Comment::readAllVoted($userid, $args);

    HTTPResponse::ok("All comments (voted by $userid)", $comments);
}

if ($action === 'get-id') {
    HTTPResponse::ok("Comment with id $commentid", $comment);
}

if ($action === 'get-parent-author') {
    $comments = Comment::getChildrenAuthor($parentid, $authorid, $args);

    HTTPResponse::ok("Comments of user $authorid children of $parentid", $comments);
}

if ($action === 'get-parent') {
    $comments = Comment::getChildren($parentid, $args);

    HTTPResponse::ok("Comments children of $parentid", $comments);
}

if ($action === 'get-author') {
    $comments = Comment::getAuthor($authorid, $args);

    HTTPResponse::ok("Comments of user $authorid", $comments);
}

if ($action === 'get-all') {
    $comments = Comment::readAll($args);

    HTTPResponse::ok("All comments", $comments);
}

// DELETE
if ($action === 'delete-id') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Comment::delete($commentid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted comment $commentid", $data);
}

if ($action === 'delete-parent-author') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Comment::deleteChildrenAuthor($parentid, $authorid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted comments of user $authorid children of $parentid", $data);
}

if ($action === 'delete-parent') {
    $auth = Auth::demandLevel('admin');

    $count = Comment::deleteChildren($parentid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted comments children of $parentid", $data);
}

if ($action === 'delete-author') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Comment::deleteAuthor($authorid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted comments of user $authorid", $data);
}

if ($action === 'delete-all') {
    $auth = Auth::demandLevel('admin');

    $count = Comment::deleteAll();

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted all comments", $data);
}
?>
