<?php
$auth = Auth::demandLevel('free');

$channelid = $args['channelid'];

$channel = Channel::read($channelid);

if (!$channel) {
    HTTPResponse::notFound("Channel with id $channelid");
}

HTTPResponse::ok("Channel $channelid", $channel);
?>
