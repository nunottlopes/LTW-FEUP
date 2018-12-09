<?php
$auth = Auth::demandLevel('free');

$channelname = $args['channelname'];

$channel = Channel::get($channelname);

if (!$channel) {
    HTTPResponse::notFound("Channel $channelname");
}

HTTPResponse::ok("Channel $channelname", $channel);
?>
