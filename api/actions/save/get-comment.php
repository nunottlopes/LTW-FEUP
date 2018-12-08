<?php
$auth = Auth::demandLevel('admin');

$commentid = $args['commentid'];

if (!Comment::read($commentid)) {
    HTTPResponse::notFound("Comment with id $commentid");
}

$saves = Save::getComment($commentid);

HTTPResponse::ok("All saves of comment $commentid", $saves);
?>
