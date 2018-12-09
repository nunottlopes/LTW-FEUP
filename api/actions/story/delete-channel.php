<?php
$auth = Auth::demandLevel('admin');

$channelid = $args['channelid'];

if (!Channel::read($channelid)) {
    HTTPResponse::notFound("Channel with id $channelid");
}

$count = Story::deleteChannel($channelid);

$data = [
    'count' => $count
];

HTTPResponse::deleted("Deleted all stories in channel $channelid", $data);
?>
