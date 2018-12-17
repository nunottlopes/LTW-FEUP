<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/entity.php';
require_once __DIR__ . '/channel.php';
require_once __DIR__ . '/image.php';

class Story extends APIEntity {
    /**
     * $more Default Constants
     */
    protected static $defaultSince = 0;
    protected static $defaultLimit = 25;
    protected static $defaultOffset = 0;
    protected static $defaultSort = 'top';

    /**
     * AUXILIARY
     *
     * Extend a normal query's arguments $args with since, limit and offset.
     * The query string ends with:
     *
     *      [AND|WHERE] createdat >= ? LIMIT ? OFFSET ?
     *                               ^       ^        ^- $offset
     *                               |       +--- $limit
     *                               +--- $since
     *
     * So we push to $args array values $since, $limit and $offset IN THIS ORDER.
     */
    private static function extend(array $args, array $more) {
        $since = static::since($more);
        $limit = static::limit($more);
        $offset = static::offset($more);

        $args[] = $since;
        $args[] = $limit;
        $args[] = $offset;

        return $args;
    }

    /**
     * Select the appropriate story table view based on sorting desired.
     *
     * Switch statement prevents SQL injection.
     */
    private static function sort($more) {
        $order = static::order($more);

        switch ($order) {
        case 'top':
            return 'score';
        case 'bot':
            return '-score';
        case 'new':
            return 'createdat';
        case 'old':
            return '-createdat';
        case 'best':
            return 'WILSONLOWERBOUND(upvotes, downvotes)';
        case 'controversial':
            return 'REDDITCONTROVERSIAL(upvotes, downvotes)';
        case 'hot':
            return 'REDDITHOT(upvotes, downvotes, createdat)';
        case 'average':
            return 'CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float)';
        default:
            return 'score'; // top
        }
    }

    /**
     * CREATE
     */
    public static function create($channelid, $authorid, $title, $type, $content,
            $imageid = NULL) {
        $query = '
            INSERT INTO Story(channelid, authorid, storyTitle, storyType, content, imageid)
            VALUES (?, ?, ?, ?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);

        try {
            DB::get()->beginTransaction();
            $stmt->execute([$channelid, $authorid, $title, $type, $content, $imageid]);
            $row = DB::get()->query('SELECT max(entityid) id FROM Entity')->fetch();
            DB::get()->commit();
            return (int)$row['id'];
        } catch (PDOException $e) {
            DB::get()->rollback();
            return false;
        }
    }

    public static function createText($channelid, $authorid, $title, $content) {
        return static::create($channelid, $authorid, $title, 'text', $content, NULL);
    }

    public static function createTitle($channelid, $authorid, $title) {
        return static::create($channelid, $authorid, $title, 'title', '', NULL);
    }

    public static function createImage($channelid, $authorid, $title, $content, $imageid) {
        return static::create($channelid, $authorid, $title, 'image', $content, $imageid);
    }

    /**
     * READ
     */
    public static function read($id) {
        $query = '
            SELECT * FROM StoryAll WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return static::fetch($stmt);
    }

    public static function getChannel($channelid, array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryImageAuthor WHERE channelid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthor($authorid, array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryImageChannel WHERE authorid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getChannelAuthor($channelid, $authorid, array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryImage WHERE channelid = ? AND authorid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readAll(array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryAll
            WHERE createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    /**
     * VOTED READ
     */
    public static function readVoted($entityid, $userid) {
        $query = '
            SELECT * FROM StoryVotingAll WHERE entityid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid]);
        return static::fetch($stmt);
    }

    public static function getChannelVoted($channelid, $userid, array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryVotingImageAuthor
            WHERE channelid = ? AND userid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthorVoted($authorid, $userid, array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryVotingImageChannel
            WHERE authorid = ? AND userid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getChannelAuthorVoted($channelid, $authorid, $userid,
            array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryVotingImage
            WHERE channelid = ? AND authorid = ? AND userid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $authorid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readAllVoted($userid, array $more = null) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM StoryVotingAll
            WHERE userid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    /**
     * UPDATE
     */
    public static function update($entityid, $content, $imageid) {
        $query = '
            UPDATE Story SET content = ?, imageid = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $imageid, $entityid]);
        return $stmt->rowCount();
    }
    public static function updateContent($entityid, $content) {
        $query = '
            UPDATE Story SET content = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $entityid]);
        return $stmt->rowCount();
    }

    public static function setImage($entityid, $imageid) {
        $query = '
            UPDATE Story SET imageid = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid, $entityid]);
        return $stmt->rowCount();
    }

    public static function clearContent($entityid) {
        $query = '
            UPDATE Story SET content = "" WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    public static function clearImage($entityid) {
        $query = '
            UPDATE Story SET imageid = NULL WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    /**
     * DELETE
     */
    public static function delete($entityid) {
        $query = '
            DELETE FROM Story WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    public static function deleteAuthor($authorid) {
        $query = '
            DELETE FROM Story WHERE authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$authorid]);
        return $stmt->rowCount();
    }

    public static function deleteChannel($channelid) {
        $query = '
            DELETE FROM Story WHERE channelid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid]);
        return $stmt->rowCount();
    }

    public static function deleteChannelAuthor($channelid, $authorid) {
        $query = '
            DELETE FROM Story WHERE channelid = ? AND authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid, $authorid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        $query = '
            DELETE FROM Story
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
