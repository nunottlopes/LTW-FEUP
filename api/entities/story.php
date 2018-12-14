<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/entity.php';
require_once __DIR__ . '/channel.php';

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
    public static function create(string $channelid, int $authorid, string $title,
            string $type, string $content, int $imageid = NULL) {
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

    public static function createText(string $channelid, int $authorid, string $title,
            string $content) {
        return static::create($channelid, $authorid, $title, 'text', $content, NULL);
    }

    public static function createTitle(string $channelid, int $authorid, string $title) {
        return static::create($channelid, $authorid, $title, 'title', '', NULL);
    }

    public static function createImage(string $channelid, int $authorid, string $title,
            string $content, int $imageid) {
        return static::create($channelid, $authorid, $title, 'image', $content, $imageid);
    }

    /**
     * READ
     */
    public static function read(int $id) {
        $query = '
            SELECT * FROM StoryAll WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return static::fetch($stmt);
    }

    public static function getChannel(int $channelid, array $more = []) {
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

    public static function getAuthor(int $authorid, array $more = []) {
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

    public static function getChannelAuthor(int $channelid, int $authorid,
            array $more = []) {
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

    public static function readAll(array $more = []) {
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
    public static function readVoted(int $entityid, int $userid) {
        $query = '
            SELECT R.*, V.vote
            FROM StoryAll R NATURAL JOIN UserVote V
            WHERE entityid = ? AND V.userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid]);
        return static::fetch($stmt);
    }

    public static function getChannelVoted(int $channelid, int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT R.*, V.vote, $sort AS rating
            FROM StoryImageAuthor R NATURAL JOIN UserVote V
            WHERE R.channelid = ? AND V.userid = ?
            AND R.createdat >= ?
            ORDER BY rating DESC, R.createdat DESC, R.entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthorVoted(int $authorid, int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT R.*, V.vote, $sort AS rating
            FROM StoryImageChannel R NATURAL JOIN UserVote V
            WHERE R.authorid = ? AND V.userid = ?
            AND R.createdat >= ?
            ORDER BY rating DESC, R.createdat DESC, R.entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getChannelAuthorVoted(int $channelid, int $authorid,
            int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT R.*, V.vote, $sort AS rating
            FROM StoryImage R NATURAL JOIN UserVote V
            WHERE R.channelid = ? AND R.authorid = ? AND V.userid = ?
            AND R.createdat >= ?
            ORDER BY rating DESC, R.createdat DESC, R.entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $authorid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readAllVoted(int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT R.*, V.vote, $sort AS rating
            FROM StoryAll R NATURAL JOIN UserVote V
            WHERE V.userid = ?
            AND R.createdat >= ?
            ORDER BY rating DESC, R.createdat DESC, R.entityid ASC
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
    public static function update(int $entityid, string $content, int $imageid) {
        $query = '
            UPDATE Story SET content = ?, imageid = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $imageid, $entityid]);
        return $stmt->rowCount();
    }
    public static function updateContent(int $entityid, string $content) {
        $query = '
            UPDATE Story SET content = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $entityid]);
        return $stmt->rowCount();
    }

    public static function setImage(int $entityid, int $imageid) {
        $query = '
            UPDATE Story SET imageid = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid, $entityid]);
        return $stmt->rowCount();
    }

    public static function clearContent(int $entityid) {
        $query = '
            UPDATE Story SET content = "" WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    public static function clearImage(int $entityid) {
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
    public static function delete(int $entityid) {
        $query = '
            DELETE FROM Story WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    public static function deleteAuthor(int $authorid) {
        $query = '
            DELETE FROM Story WHERE authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$authorid]);
        return $stmt->rowCount();
    }

    public static function deleteChannel(int $channelid) {
        $query = '
            DELETE FROM Story WHERE channelid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid]);
        return $stmt->rowCount();
    }

    public static function deleteChannelAuthor(int $channelid, int $authorid) {
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
