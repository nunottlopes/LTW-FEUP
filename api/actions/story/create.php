<?php
require_once API::entity('channel');

$action = 'create';

$user = Auth::demandLevel('user');

$channelid = (int)$args['channelid'];
$title = $args['storyTitle'];
$type = $args['storyType'];
$content = $args['content'];
$authorid = $user['userid'];

if (!Channel::read($channelid)) {
    HTTPResponse::adjacentNotFound("Channel with id $channelid");
}

$storyid = Story::create($channelid, $authorid, $title, $type, $content);

if ($storyid) {
    $data = [
        'storyid' => $storyid,
        'entityid' => $storyid
    ];
    HTTPResponse::created("Created story $storyid", $data);
}

HTTPResponse::badRequest("Invalid story title, type or content");
?>
