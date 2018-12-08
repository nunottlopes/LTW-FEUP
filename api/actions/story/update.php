<?php
$action = 'update';

$storyid = (int)$args['storyid'];
$content = $args['content'];

$story = Story::read($storyid);

if ($story) {
    $authorid = $story['authorid'];

    $user = Auth::demandLevel('authid', $authorid);

    $result = Story::update($storyid, $content);

    if ($result) {
        HTTPResponse::updated("Story successfully updated");
    }
    
    HTTPResponse::badRequest("Invalid story new content");
}

HTTPResponse::notFound("Story with id $storyid");
?>
