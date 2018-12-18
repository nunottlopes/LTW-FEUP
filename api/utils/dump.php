<?php

    private static function interweave($stories, $comments, array $more) {
        // Null checks
        if (!$stories) return $comments;
        if (!$comments) return $stories;

        $storyTotal = count($stories);
        $commentTotal = count($comments);
        // ^ both positive

        $merged = [];

        $s = 0; $c = 0;
        while ($s < $storyTotal && $c < $commentTotal) {
            $story = $stories[$s];
            $comment = $comments[$c];

            $storyTime = $story['savedat'];
            $commentTime = $comment['savedat'];

            // Most recent first
            if ($storyTime > $commentTime) {
                ++$s;
                array_push($merged, $story);
            } else {
                ++$c;
                array_push($merged, $comment);
            }
        }

        if ($s === $storyTotal) {
            $merged = array_merge($merged, array_slice($comments, $c));
        } else if ($c === $commentTotal) {
            $merged = array_merge($merged, array_slice($stories, $s));
        }

        $limit = static::limit($more);
        $offset = static::offset($more);

        return array_slice($merged, $offset, $limit);
    }

?>