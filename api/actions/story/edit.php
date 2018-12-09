<?php
$storyid = $args['storyid'];

$story = Story::read($storyid);

if (!$story) {
    HTTPResponse::notFound("Story with id $storyid");
}

$authorid = $story['authorid'];

$auth = Auth::demandLevel('authid', $authorid);

$content = HTTPRequest::getContent();

$count = Story::update($storyid, $content);

$data = [
    'count' => $count,
    'old' => $story
];

HTTPResponse::updated("Story $storyid successfully edited", $data);
?>
