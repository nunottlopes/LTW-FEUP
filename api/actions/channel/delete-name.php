<?php
$auth = Auth::demandLevel('admin');

$channelname = $args['channelname'];

$channel = Channel::get($channelname);

if (!$channel) {
    HTTPResponse::notFound("Channel $channelname");
}

$channelid = $channel['channelid'];

$count = Channel::delete($channelid);

$data = [
    'count' => $count,
    'old' => $channel
];

HTTPResponse::deleted("Deleted channel $channelid", $data);
?>
