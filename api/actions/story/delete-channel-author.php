<?php
$authorid = $auth['authorid'];

if (!User::read($authorid)) {
    HTTPRequest::notFound("User with id $authorid");
}

$auth = Auth::demandLevel('authid', $authorid);

$channelid = $args['channelid'];

if (!Channel::read($channelid)) {
    HTTPResponse::notFound("Channel with id $channelid");
}

$count = Story::deleteChannelUser($channelid, $authorid);

$data = [
    'count' => $count
];

HTTPResponse::deleted("Deleted all stories by $authorid in channel $channelid", $data);
?>
