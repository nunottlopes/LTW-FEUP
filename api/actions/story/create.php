<?php
$authorid = $auth['authorid'];

if (!User::read($authorid)) {
    HTTPRequest::notFound("User with id $authorid");
}

$auth = Auth::demandLevel('authid', $authorid);

$channelid = $args['channelid'];

if (!Channel::read($channelid)) {
    HTTPResponse::notFound("Channel with id $channelid");
}

$body = HTTPRequest::parseBody(['storyTitle', 'storyType', 'content']);
$title = $body['storyTitle'];
$type = $body['storyType'];
$content = $body['content'];

$storyid = Story::create($channelid, $authorid, $title, $type, $content);

if (!$storyid) {
    HTTPResponse::serverError();
}

$story = Story::read($storyid);

$data = [
    'storyid' => $storyid,
    'story' => $story
];

HTTPResponse::created("Created story $storyid in channel $channelid", $data);
?>
