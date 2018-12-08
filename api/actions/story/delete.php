<?php
$action = 'delete';

$storyid = (int)$args['storyid'];

$story = Story::read($storyid);

if ($story) {
    $authorid = (int)$story['authorid'];

    $user = Auth::demandLevel('authid', $authorid);

    Story::delete($storyid);

    HTTPResponse::deleted("Deleted story $storyid");
}

HTTPResponse::notFound("Story with id $storyid");
?>
