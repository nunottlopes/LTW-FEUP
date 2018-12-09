<?php
$auth = Auth::demandLevel('free');

$channelid = $args['channelid'];
$authorid = $args['authorid'];

if (!Channel::read($channelid)) {
    HTTPResponse::notFound("Channel with id $channelid");
}

if (!User::read($authorid)) {
    HTTPResponse::notFound("User with id $authorid");
}

$stories = Story::getChannelUser($channelid, $authorid);

HTTPResponse::ok("Stories of user $authorid in channel $channelid", $stories);
?>
