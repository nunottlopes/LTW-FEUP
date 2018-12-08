<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/story.php';
require_once __DIR__ . '/comment.php';

class Save extends APIEntity {
    private static function mergeSaves($stories, $comments) {
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
        } else if ($c === $commentTotal - 1) {
            $merged = array_merge($merged, array_slice($stories, $s));
        }

        return $merged;
    }

    /**
     * CREATE
     */
    public static function create(int $entityid, int $userid) {
        $query = '
            INSERT INTO Save(entityid, userid) VALUES (?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid]);
        return $stmt->rowCount();
    }

    /**
     * READ
     */
    public static function getUserStories(int $userid) {
        $query = '
            SELECT * FROM SaveStory
            WHERE userid = ?
            ORDER BY savedat DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return static::fetchAll($stmt);
    }

    public static function getUserComments(int $userid) {
        $query = '
            SELECT * FROM SaveComment
            WHERE userid = ?
            ORDER BY savedat DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return static::fetchAll($stmt);
    }

    public static function getUser(int $userid) {
        $stories = static::getUserStories($userid);
        $comments = static::getUserComments($userid);

        // Merge $stories and $comments descendingly by save_date
        return static::mergeSaves($stories, $comments);
    }

    public static function getStory(int $entityid) {
        $query = '
            SELECT * FROM Save
            WHERE entityid = ? AND entityid IN (SELECT entityid FROM Story)
            ORDER BY savedat DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return static::fetchAll($stmt);
    }

    public static function getComment(int $entityid) {
        $query = '
            SELECT * FROM Save
            WHERE entityid = ? AND entityid IN (SELECT entityid FROM Comment)
            ORDER BY savedat DESC
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return static::fetchAll($stmt);
    }

    public static function read(int $entityid) {
        if (Story::read($entityid)) {
            return static::getStory($entityid);
        } else {
            return static::getComment($entityid);
        }
    }

    public static function readAll() {
        $query = '
            SELECT * FROM Save
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return static::fetchAll($stmt);
    }

    /**
     * NO UPDATE
     * There's nothing to be updated.
     */
    
    /**
     * DELETE
     */
    public static function delete(int $entityid, int $userid) {
        $query = '
            DELETE FROM Save WHERE entityid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid]);
        return $stmt->rowCount();
    }

    /**
     * EXTRA
     */
    public static function deleteAllUser(int $userid) {
        $query = '
            DELETE FROM Save WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return $stmt->rowCount();
    }
}
?>