<?php
$action = 'get';

$auth = Auth::demandLevel('free');

$channelname = $args['channelname'];

$channel = Channel::get($channelname);

if (!$channel) {
    HTTPResponse::notFound("Channel with name $channelname");
}

HTTPResponse::ok("Channel $channelname", $channel);
?>
