<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('vote');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'vote';

$methods = ['GET', 'PUT', 'DELETE'];

$actions = [
    'put'           => ['PUT', ['entityid', 'userid']],
    
    'get-id'        => ['GET', ['entityid', 'userid']],
    'get-entity'    => ['GET', ['entityid']],
    'get-user'      => ['GET', ['userid']],
    'get-all'       => ['GET', ['all']],
    
    'delete-id'     => ['DELETE', ['entityid', 'userid']],
    'delete-entity' => ['DELETE', ['entityid']],
    'delete-user'   => ['DELETE', ['userid']],
    'delete-all'    => ['DELETE', ['all']],
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::requireMethod($methods);

$args = HTTPRequest::query($method, $actions, $action);

$auth = Auth::demandLevel('free');

/**
 * 2. GET: Check query parameter identifying resources
 * VOTE: entityid, userid, all
 */
// entityid
if (API::gotargs('entityid')) {
    $entityid = $args['entityid'];

    $entity = Entity::read($entityid);

    if (!$entity) {
        HTTPResponse::notFound("Entity with id $entityid");
    }
}
// userid
if (API::gotargs('userid')) {
    $userid = $args['userid'];

    $user = User::read($userid);

    if (!$user) {
        HTTPResponse::notFound("User with id $userid");
    }
}

/**
 * 3. ANSWER: HTTPResponse
 */
// PUT
if ($action === 'put') {
    $auth = Auth::demandLevel('authid', $userid);

    $vote = HTTPRequest::body(['vote']);

    if ($vote === 'upvote') $vote = '+';
    if ($vote === 'downvote') $vote = '-';

    if ($vote === '+') {
        Vote::upvote($entityid, $userid);
    } else if if ($vote === '-') {
        Vote::downvote($entityid, $userid);
    } else {
        HTTPResponse::badRequest('Invalid vote', ['vote' => $vote]);
    }

    $voted = $vote === '+' ? 'Upvoted' : 'Downvoted';

    $data = [
        'count' => $count,
        'entityid' => $entityid,
        'userid' => $userid
    ];

    HTTPResponse::created("$voted entity $entityid", $data);
}

// GET
if ($action === 'get-id') {
    $auth = Auth::demandLevel('authid', $userid);

    $vote = Vote::get($entityid, $userid);

    $data = [
        'vote' => $vote['kind'],
        'entityid' => $entityid,
        'entity' => $entity,
        'userid' => $userid,
        'user' => $user
    ];

    HTTPResponse::ok("Vote of user $userid on entity $entityid", $data);
}

if ($action === 'get-entity') {
    $auth = Auth::demandLevel('admin');

    $votes = Vote::getEntity($entityid);

    $data = [
        'votes' => $votes,
        'entityid' => $entityid,
        'entity' => $entity
    ];

    HTTPResponse::ok("Votes on entity $entityid", $data);
}

if ($action === 'get-user') {
    $auth = Auth::demandLevel('authid', $userid);

    $votes = Vote::getUser($userid);

    $data = [
        'votes' => $votes,
        'userid' => $userid,
        'user' => $user
    ];

    HTTPResponse::ok("Votes of user $userid", $data);
}

if ($action === 'get-all') {
    $auth = Auth::demandLevel('admin');

    $votes = Vote::readAll();

    $data = ['votes' => $votes];

    HTTPResponse::ok("All votes", $data);
}

// DELETE
if ($action === 'delete-id') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = Vote::delete($entityid, $userid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted vote of user $userid on entity $entityid", $data);
}

if ($action === 'delete-entity') {
    $auth = Auth::demandLevel('admin');

    $count = Vote::deleteEntity($entityid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted votes on entity $entityid", $data);
}

if ($action === 'delete-user') {
    $auth = Auth::demandLevel('authid', $userid);

    $count = Vote::deleteUser($userid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted votes of user $userid", $data);
}

if ($action === 'delete-all') {
    $auth = Auth::demandLevel('admin');

    $count = Vote::deleteAll();

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted all votes", $data);
}
?>
