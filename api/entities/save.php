<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/story.php';
require_once __DIR__ . '/comment.php';
require_once __DIR__ . '/entity.php';

class Save extends APIEntity {
    /**
     * $more Default Constants
     */
    protected static $defaultLimit = 25;
    protected static $defaultOffset = 0;

    /**
     * AUXILIARY
     *
     * Extend a normal query's arguments $args with since, limit and offset.
     * The query string ends with:
     *
     *      ... LIMIT ? OFFSET ?
     *                ^        ^- $offset
     *                +--- $limit
     *
     * So we push to $args array values $limit and $offset IN THIS ORDER.
     */
    private static function extend(array $args, array $more) {
        $limit = static::limit($more);
        $offset = static::offset($more);

        $args[] = $limit;
        $args[] = $offset;

        return $args;
    }

    private static function mergeSaves($stories, $comments, array $more) {
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
    public static function getComment(int $entityid, array $more = []) {
        $query = '
            SELECT * FROM SaveUser
            WHERE entityid = ? AND entityid IN (SELECT entityid FROM Comment)
            ORDER BY savedat DESC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$entityid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getStory(int $entityid, array $more = []) {
        $query = '
            SELECT * FROM SaveUser
            WHERE entityid = ? AND entityid IN (SELECT entityid FROM Story)
            ORDER BY savedat DESC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$entityid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getEntity(int $entityid, array $more = []) {
        if (Story::read($entityid)) {
            return static::getStory($entityid, $more);
        } else {
            return static::getComment($entityid, $more);
        }
    }

    public static function getUserComments(int $userid, array $more = []) {
        $query = '
            SELECT * FROM SaveUserComment
            WHERE userid = ?
            ORDER BY savedat DESC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getUserStories(int $userid, array $more = []) {
        $query = '
            SELECT * FROM SaveUserStory
            WHERE userid = ?
            ORDER BY savedat DESC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getUserAll(int $userid, array $more = []) {
        $stories = static::getUserStories($userid);
        $comments = static::getUserComments($userid);

        // Merge $stories and $comments descendingly by save_date
        return static::mergeSaves($stories, $comments, $more);
    }

    public static function readAllComments(array $more = []) {
        $query = '
            SELECT * FROM SaveComment
            ORDER BY savedat
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readAllStories(array $more = []) {
        $query = '
            SELECT * FROM SaveStory
            ORDER BY savedat
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readAll(array $more = []) {
        $query = '
            SELECT * FROM Save
            ORDER BY savedat
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
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

    public static function deleteUser(int $userid) {
        $query = '
            DELETE FROM Save WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return $stmt->rowCount();
    }

    public static function deleteEntity(int $entityid) {
        $query = '
            DELETE FROM Save WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        $query = '
            DELETE FROM Save
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
