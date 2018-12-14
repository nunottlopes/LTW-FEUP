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

    /**
     * Transform a mixed query of stories and comments. Add the ascendant stories
     * to the comments.
     */
    private static function remix(array $all, array $ascendants) {
        $count = min(count($all), count($ascendants));
        $mix = [];
        for ($i = 0; $i < $count; ++$i) {
            $save = $all[$i];
            $ascendant = $ascendants[$i];

            if ($save['type'] === 'story') {
                $mix[] = [
                    'story' => $save
                ];
            } else {
                $mix[] = [
                    'story' => $ascendant,
                    'comment' => $save
                ];
            }
        }
        return $mix;
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
    public static function getComment(int $commentid, array $more = []) {
        $query = '
            SELECT * FROM SaveUser
            WHERE entityid = ? AND entityid IN (SELECT entityid FROM Comment)
            ORDER BY savedat DESC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$commentid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getStory(int $storyid, array $more = []) {
        $query = '
            SELECT * FROM SaveUser
            WHERE entityid = ? AND entityid IN (SELECT entityid FROM Story)
            ORDER BY savedat DESC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$storyid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getEntity(int $entityid, array $more = []) {
        $query = '
            SELECT * FROM SaveUser
            WHERE entityid = ?
            ORDER BY savedat DESC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$entityid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getCommentVoted(int $commentid, int $userid) {
        $query = '
            SELECT * FROM SaveUserComment
            WHERE entityid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$commentid, $userid]);
        $comment = static::fetch($stmt);

        $query = '
            SELECT * FROM SaveUserAscendant
            WHERE commentid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$commentid, $userid]);
        $story = static::fetch($stmt);

        $merge = ['story' => $story, 'comment' => $comment];
        return $merge;
    }

    public static function getStoryVoted(int $storyid, int $userid) {
        $query = '
            SELECT * FROM SaveUserStory
            WHERE entityid = ? AND userid = ?
            ORDER BY savedat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$storyid, $userid]);
        return static::fetch($stmt);
    }

    public static function getUserComments(int $userid, array $more = []) {
        $queryArguments = static::extend([$userid], $more);

        $query = '
            SELECT * FROM SaveUserComment
            WHERE userid = ?
            ORDER BY savedat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        $comments = static::fetchAll($stmt);

        $query = '
            SELECT * FROM SaveUserAscendant
            WHERE userid = ?
            ORDER BY savedat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        $stories = static::fetchAll($stmt);

        $merge = API::group(['story', 'comment'], $stories, $comments);
        return $merge;
    }

    public static function getUserStories(int $userid, array $more = []) {
        $query = '
            SELECT * FROM SaveUserStory
            WHERE userid = ?
            ORDER BY savedat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ';

        $queryArguments = static::extend([$userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getUserAll(int $userid, array $more = []) {
        $queryArguments = static::extend([$userid], $more);

        $query = '
            SELECT * FROM SaveUserAll
            WHERE userid = ?
            ORDER BY savedat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        $saves = static::fetchAll($stmt);

        $query = '
            SELECT *
            FROM SaveUserAllAscendant
            WHERE userid = ?
            ORDER BY savedat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        $ascendants = static::fetchAll($stmt);

        $mix = static::remix($saves, $ascendants);
        return $mix;
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
            SELECT * FROM SaveAll
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
