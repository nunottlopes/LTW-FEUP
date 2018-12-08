<?php
$action = 'delete';

$auth = Auth::demandLevel('admin');

$channelid = $args['channelid'];

$channel = Channel::read($channelid);

if (!$channel) {
    HTTPResponse::notFound("Channel with id $channelid");
}

$count = Channel::delete($channelid);

$data = [
    'count' => $count,
    'channel' => $channel
];

HTTPResponse::deleted("Deleted channel $channelid", $data);
?>
