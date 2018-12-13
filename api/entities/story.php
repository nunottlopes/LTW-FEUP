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
    protected static $defaultSortTable = 'StorySortBest';

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
    private static function sortTablename($more) {
        if (!isset($more['order'])) return static::$defaultSortTable;

        $order = $more['order'];
        if (!is_string($order)) return static::$defaultSortTable;

        switch ($order) {
        case 'top': return 'StorySortTop';
        case 'bot': return 'StorySortBot';
        case 'new': return 'StorySortNew';
        case 'old': return 'StorySortOld';
        case 'best': return 'StorySortBest';
        case 'controversial': return 'StorySortControversial';
        case 'average': return 'StorySortAverage';
        case 'hot': return 'StorySortHot';
        default: return static::$defaultSortTable;
        }
    }

    /**
     * CREATE
     */
    public static function create(string $channelid, int $authorid, string $title,
            string $type, string $content) {
        $query = '
            INSERT INTO Story(channelid, authorid, storyTitle, storyType, content)
            VALUES (?, ?, ?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);

        try {
            DB::get()->beginTransaction();
            $stmt->execute([$channelid, $authorid, $title, $type, $content]);
            $row = DB::get()->query('SELECT max(entityid) id FROM Entity')->fetch();
            DB::get()->commit();
            return (int)$row['id'];
        } catch (PDOException $e) {
            DB::get()->rollback();
            return false;
        }
    }

    /**
     * READ
     */
    public static function getChannelAuthor(int $channelid, int $authorid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT * FROM $sorttable
            WHERE channelid = ? AND authorid = ?
            AND createdat >= ?
            ORDER BY rating DESC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getChannel(int $channelid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT * FROM $sorttable
            WHERE channelid = ?
            AND createdat >= ?
            ORDER BY rating DESC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthor(int $authorid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT * FROM $sorttable
            WHERE authorid = ?
            AND createdat >= ?
            ORDER BY rating DESC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function read(int $entityid) {
        $query = '
            SELECT * FROM StoryAll
            WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return static::fetch($stmt);
    }

    public static function readAll(array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT * FROM $sorttable
            WHERE createdat >= ?
            ORDER BY rating DESC
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
    public static function getChannelAuthorVoted(int $channelid, int $authorid,
            int $userid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT ST.*, coalesce(V.vote, '') vote
            FROM $sorttable ST NATURAL JOIN UserVote V
            WHERE channelid = ? AND authorid = ? AND V.userid = ?
            AND createdat >= ?
            ORDER BY rating DESC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $authorid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getChannelVoted(int $channelid, int $userid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT ST.*, coalesce(V.vote, '') vote
            FROM $sorttable ST NATURAL JOIN UserVote V
            WHERE channelid = ? AND V.userid = ?
            AND createdat >= ?
            ORDER BY rating DESC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthorVoted(int $authorid, int $userid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT ST.*, coalesce(V.vote, '') vote
            FROM $sorttable ST NATURAL JOIN UserVote V
            WHERE authorid = ? AND V.userid = ?
            AND createdat >= ?
            ORDER BY rating DESC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readVoted(int $entityid, int $userid) {
        $query = '
            SELECT SA.*, coalesce(V.vote, "") vote
            FROM StoryAll SA NATURAL JOIN UserVote V
            WHERE entityid = ? AND V.userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid]);
        return static::fetch($stmt);
    }

    public static function readAllVoted(int $userid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT ST.*, coalesce(V.vote, '') vote
            FROM $sorttable ST NATURAL JOIN UserVote V
            WHERE V.userid = ?
            AND createdat >= ?
            ORDER BY rating DESC
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
    public static function update(int $entityid, string $content) {
        $query = '
            UPDATE Story SET content = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $entityid]);
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
