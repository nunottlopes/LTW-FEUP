<?php
if (API::gotargs('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $authorid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $authorid = $auth['userid'];
}

$channelid = $args['channelid'];
$title = $args['storyTitle'];
$type = $args['storyType'];
$content = $args['content'];

if (!Channel::read($channelid)) {
    HTTPResponse::adjacentNotFound("Channel with id $channelid");
}

$storyid = Story::create($channelid, $authorid, $title, $type, $content);

$data = [
    'storyid' => $storyid,
    'entityid' => $storyid,
    'authorid' => $authorid,
    'channelid' => $channelid,
    'title' => $title,
    'type' => $type,
    'content' => $content
];

HTTPResponse::created("Created story $storyid", $data);
?>
