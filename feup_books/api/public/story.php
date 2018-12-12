<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/api.php';
require_once API::entity('story');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'story';

$methods = ['GET', 'POST', 'PATCH', 'DELETE'];

$actions = [
    'create'                => ['POST', ['channelid', 'authorid'], ['storyTitle', 'storyType', 'content']],

    'edit'                  => ['PATCH', ['storyid'], ['content']],

    'get-id'                => ['GET', ['storyid']],
    'get-channel-author'    => ['GET', ['channelid', 'authorid'], [], ['order', 'since', 'limit', 'offset']],
    'get-channel'           => ['GET', ['channelid'], [], ['order', 'since', 'limit', 'offset']],
    'get-author'            => ['GET', ['authorid'], [], ['order', 'since', 'limit', 'offset']],
    'get-all'               => ['GET', ['all'], [], ['order', 'since', 'limit', 'offset']],

    'delete-id'             => ['DELETE', ['storyid']],
    'delete-channel-author' => ['DELETE', ['channelid', 'authorid']],
    'delete-channel'        => ['DELETE', ['channelid']],
    'delete-author'         => ['DELETE', ['authorid']],
    'delete-all'            => ['DELETE', ['all']],
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
 * STORY: storyid, channelid, authorid, all
 */
// storyid
if (API::gotargs('storyid')) {
    $storyid = $args['storyid'];

    $story = Story::read($storyid);

    if (!$story) {
        HTTPResponse::notFound("Story with id $storyid");
    }

    $authorid = $story['authorid'];
}
// channelid
if (API::gotargs('channelid')) {
    $channelid = $args['channelid'];

    $channel = Channel::read($channelid);

    if (!$channel) {
        HTTPResponse::notFound("Channel with id $channelid");
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

/**
 * 3. ANSWER: HTTPResponse
 */
// POST
if ($action === 'create') {
    $auth = Auth::demandLevel('authid', $authorid);

    $body = HTTPRequest::body('content', 'storyTitle', 'storyType');
    $title = $body['storyTitle'];
    $type = $body['storyType'];
    $content = $body['content'];

    $storyid = Story::create($channelid, $authorid, $title, $type, $content);

    if (!$storyid) {
        HTTPResponse::serverError();
    }

    $story = Story::read($storyid);

    $data = [
        'storyid' => $storyid,
        'story' => $story
    ];

    HTTPResponse::created("Created story $storyid in channel $channelid", $data);
}

// PATCH
if ($action === 'edit') {
    $auth = Auth::demandLevel('authid', $authorid);

    $content = HTTPRequest::body('content');

    $count = Story::update($storyid, $content);

    $story = Story::read($storyid);

    $data = [
        'count' => $count,
        'story' => $story
    ];

    HTTPResponse::updated("Story $storyid successfully updated", $data);
}

// GET
if ($action === 'get-id') {
    HTTPResponse::ok("Story $storyid", $story);
}

if ($action === 'get-channel-author') {
    $stories = Story::getChannelUser($channelid, $authorid, $args);

    HTTPResponse::ok("Stories of user $authorid in channel $channelid", $stories);
}

if ($action === 'get-channel') {
    $stories = Story::getChannel($channelid, $args);

    HTTPResponse::ok("Stories in channel $channelid", $stories);
}

if ($action === 'get-author') {
    $stories = Story::getUser($authorid, $args);

    HTTPResponse::ok("Stories of user $authorid", $stories);
}

if ($action === 'get-all') {
    $stories = Story::readAll($args);

    HTTPResponse::ok("All stories", $stories);
}

// DELETE
if ($action === 'delete-id') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Story::delete($storyid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted story $storyid", $data);
}

if ($action === 'delete-channel-author') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Story::deleteChannelUser($channelid, $authorid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted stories of user $userid in channel $channelid", $data);
}

if ($action === 'delete-channel') {
    $auth = Auth::demandLevel('admin');

    $count = Story::deleteChannel($channelid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted stories in channel $channelid", $data);
}

if ($action === 'delete-author') {
    $auth = Auth::demandLevel('authid', $authorid);

    $count = Story::deleteUser($authorid);

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted stories of user $userid", $data);
}

if ($action === 'delete-all') {
    $auth = Auth::demandLevel('admin');

    $count = Story::deleteAll();

    $data = ['count' => $count];

    HTTPResponse::deleted("Deleted all stories", $data);
}
?>
