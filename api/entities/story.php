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

    /**
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
    protected static function extend(array $args, array $more) {
        $since = static::since($more);
        $limit = static::limit($more);
        $offset = static::offset($more);

        $args[] = $since;
        $args[] = $limit;
        $args[] = $offset;

        return $args;
    }

    /**
     * AUXILIARY
     * 
     * Select the appropriate story table view based on sorting desired.
     *
     * Switch statement prevents SQL injection.
     */
    private static function sortTablename($more) {
        if (!isset($more['order'])) return 'StoryAll';

        $order = $more['order'];
        if (!is_string($order)) return 'StoryAll';

        switch ($order) {
        case 'top': return 'StorySortTop';
        case 'bot': return 'StorySortBot';
        case 'average': return 'StorySortAverage';
        case 'new': return 'StorySortNew';
        case 'old': return 'StorySortOld';
        case 'hot': return 'StorySortHot';
        default: return 'StoryAll';
        }
    }

    /**
     * CREATE
     */
    public static function create(string $channelid, int $authorid, string $title,
            string $type, string $content) {
        $query = '
            INSERT INTO StoryEntity(channelid, authorid, storyTitle, storyType, content)
            VALUES (?, ?, ?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);

        try {
            DB::get()->beginTransaction();
            $stmt->execute([$channelid, $authorid, $title, $type, $content]);
            $id = (int)DB::get()->lastInsertId();
            DB::get()->commit();
            return $id;
        } catch (PDOException $e) {
            DB::get()->rollback();
            return false;
        }
    }

    /**
     * READ
     */
    public static function getChannelUser(int $channelid, int $authorid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT * FROM $sorttable
            WHERE channelid = ? AND authorid = ?
            AND createdat >= ? LIMIT ? OFFSET ?
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
            AND createdat >= ? LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$channelid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getUser(int $authorid, array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT * FROM $sorttable
            WHERE authorid = ?
            AND createdat >= ? LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function read(int $id) {
        $query = '
            SELECT * FROM StoryAll
            WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return static::fetch($stmt);
    }

    public static function readAll(array $more = []) {
        $sorttable = static::sortTablename($more);

        $query = "
            SELECT * FROM $sorttable
            WHERE createdat >= ? LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    /**
     * UPDATE
     */
    public static function update(int $id, string $content) {
        $query = '
            UPDATE StoryEntity SET content = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $id]);
        return $stmt->rowCount();
    }
    
    /**
     * DELETE
     */
    public static function delete(int $id) {
        $query = '
            DELETE FROM Story WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>