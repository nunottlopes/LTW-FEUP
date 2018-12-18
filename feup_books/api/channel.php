<?php
require_once __DIR__ . '/../../api/api.php';
require_once API::entity('channel');

/**
 * 1.1. LOAD resource description variables
 */
$resource = 'channel';

$methods = ['GET', 'PUT', 'PATCH', 'DELETE'];

$actions = [
    'create'      => ['PUT', ['creatorid'], ['channelname']],

    'set-banner'  => ['PATCH', ['channelid'], ['imageid']],

    'get-id'      => ['GET', ['channelid']],
    'get-name'    => ['GET', ['channelname']],
    'get-creator' => ['GET', ['creatorid']],
    'get-all'     => ['GET', ['all']],
    'get-valid'   => ['GET', ['valid']],

    'delete-id'   => ['DELETE', ['channelid']],
    'delete-name' => ['DELETE', ['channelname']],
    'delete-all'  => ['DELETE', ['all']]
];

/**
 * 1.2. LOAD request description variables
 */
$method = HTTPRequest::method($methods);

$action = HTTPRequest::action($resource, $actions);

$args = API::cast($_GET);

/**
 * 2. GET: Check query parameter identifying resources
 * CHANNEL: creatorid, channelid, channelname, valid, all
 */
// creatorid
if (API::gotargs('creatorid')) {
    $creatorid = $args['creatorid'];

    $creator = User::read($creatorid);

    if (!$creator) {
        HTTPResponse::notFound("User with id $creatorid");
    }

    $creatorname = $creator['username'];
}
// channelid
if (API::gotargs('channelid')) {
    $channelid = $args['channelid'];

    $channel = Channel::read($channelid);

    if (!$channel) {
        HTTPResponse::notFound("Channel with id $channelid");
    }

    $channelname = $channel['channelname'];
}
// channelname
if (API::gotargs('channelname')) {
    $channelname = $args['channelname'];

    $channel = Channel::get($channelname);

    if (!$channel) {
        HTTPResponse::notFound("Channel $channelname");
    }

    $channelid = $channel['channelid'];
}
// valid
if (API::gotargs('valid')) {
    $channelname = $args['valid'];

    $valid = Channel::valid($channelname);

    $validString = $valid ? "valid" : "invalid";
}

/**
 * 3. ANSWER: HTTPResponse
 */
// PUT
if ($action === 'create') {
    $auth = Auth::demandLevel('authid', $creatorid);

    $channelname = HTTPRequest::body('channelname');

    if (!Channel::valid($channelname)) {
        HTTPResponse::invalid('channelname', $channelname, "Valid channelname $channelname");
    }

    if (Channel::get($channelname)) {
        HTTPResponse::conflict('channelname', $channelname, "Already existing channelname");
    }

    $channelid = Channel::create($channelname, $creatorid);

    if (!$channelid) {
        HTTPResponse::serverError();
    }

    $channel = Channel::read($channelid);

    $data = [
        'channelid' => $channelid,
        'channel' => $channel
    ];

    HTTPResponse::created("Created channel $channelid", $data);
}

if ($action === 'set-banner') {
    $creatorid = $channel['creatorid'];

    $auth = Auth::demandLevel('authid', $creatorid);

    $imageid = HTTPRequest::body('imageid');

    if (!Image::read($imageid)) {
        HTTPResponse::notFound("Image with id $imageid");
    }

    $count = Channel::setBanner($channelid, $imageid);

    $channel = Channel::read($channelid);

    $data = [
        'count' => $count,
        'channel' => $channel
    ];

    HTTPResponse::updated("Set banner $imageid to channel $channelid", $data);
}

// ***** GET
if ($action === 'get-id') {
    HTTPResponse::ok("Channel with id $channelid", $channel);
}

if ($action === 'get-name') {
    HTTPResponse::ok("Channel $channelname", $channel);
}

if ($action === 'get-creator') {
    $channels = Channel::getCreator($creatorid);

    HTTPResponse::ok("Channels with creator $creatorid", $channels);
}

if ($action === 'get-all') {
    $channels = Channel::readAll();

    HTTPResponse::ok("All channels", $channels);
}

if ($action === 'valid') {
    $data = [
        'channelname' => $channelname,
        'valid' => $valid,
        'text' => $validString
    ];

    HTTPResponse::ok("Channel name $channelname is $validString", $data);
}

// ***** DELETE
if ($action === 'delete-id') {
    Auth::demandLevel('admin');

    $count = Channel::delete($channelid);

    $data = [
        'count' => $count
    ];

    HTTPResponse::deleted("Deleted channel $channelid", $data);
}

if ($action === 'delete-name') {
    Auth::demandLevel('admin');

    $count = Channel::delete($channelid);

    $data = [
        'count' => $count
    ];

    HTTPResponse::deleted("Deleted channel $channelname", $data);
}

if ($action === 'delete-all') {
    Auth::demandLevel('admin');

    $count = Channel::deleteAll();

    $data = [
        'count' => $count
    ];

    HTTPResponse::deleted("Deleted all channels", $data);
}
?>
