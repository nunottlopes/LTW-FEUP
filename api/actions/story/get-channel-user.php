<?php
require_once API::entity('channel');

$action = 'get-channel-user';

$auth = Auth::demandLevel('free');

$channelid = (int)$args['channelid'];
$authorid = (int)$args['authorid'];

if (!Channel::read($channelid)) {
    HTTPResponse::adjacentNotFound("Channel with id $channelid");
}

if (!User::read($authorid)) {
    HTTPResponse::adjacentNotFound("User with id $authorid");
}

$stories = Story::getChannelUser($channelid, $authorid);

HTTPResponse::ok("Stories of user $authorid in channel $channelid", $stories);
?>
