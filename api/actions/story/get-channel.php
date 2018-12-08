<?php
require_once API::entity('channel');

$action = 'get-channel';

$auth = Auth::demandLevel('free');

$channelid = (int)$args['channelid'];

if (!Channel::read($channelid)) {
    HTTPResponse::adjacentNotFound("Channel with id $channelid");
}

$stories = Story::getChannel($channelid);

HTTPResponse::ok("Stories in channel $channelid", $stories);
?>
