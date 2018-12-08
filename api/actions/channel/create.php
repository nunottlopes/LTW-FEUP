<?php
$action = 'create';

if (got('userid')) { // admin impersonation
    $auth = Auth::demandLevel('authid', $args['userid']);
    $creatorid = $args['userid'];
} else {
    $auth = Auth::demandLevel('auth');
    $creatorid = $auth['userid'];
}

$channelname = $args['channelname'];

if (!Channel::valid($channelname)) {
    HTTPResponse::badArgument('channelname', $channelname);
}

if (Channel::get($channelname)) {
    HTTPResponse::conflict("Already existing channel", 'channelname', $channelname);
}

$channelid = Channel::create($channelname, $creatorid);

if (!$channelid) {
    HTTPResponse::serverError();
}

$data = [
    'channelid' => $channelid,
    'channelname' => $channelname,
    'creatorid' => $creatorid
];

HTTPResponse::created("Created channel $channelid", $data);
?>
