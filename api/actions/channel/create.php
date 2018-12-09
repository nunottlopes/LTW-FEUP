<?php
$creatorid = $auth['creatorid'];

if (!User::read($creatorid)) {
    HTTPRequest::notFound("User with id $creatorid");
}

$auth = Auth::demandLevel('authid', $creatorid);

$channelname = $args['channelname'];

if (!Channel::valid($channelname)) {
    HTTPResponse::badArgument('channelname', $channelname);
}

if (Channel::get($channelname)) {
    HTTPResponse::conflict("Already existing channel", 'channelname', $channelname);
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
?>
