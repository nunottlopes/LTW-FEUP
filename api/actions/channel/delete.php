<?php
$action = 'delete';

$auth = Auth::demandLevel('admin');

$channelid = (int)$args['channelid'];

$channel = Channel::read($channelid);

if ($channel) {
    Channel::delete($channelid);

    HTTPResponse::deleted("Deleted channel $channelid");
}

HTTPResponse::notFound("Channel with id $channelid");
?>
