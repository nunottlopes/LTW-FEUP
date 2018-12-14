<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/entity.php';
require_once __DIR__ . '/story.php';

class Comment extends APIEntity {
    /**
     * $more Default Constants
     */
    protected static $defaultSince = 0;
    protected static $defaultLimit = 50;
    protected static $defaultOffset = 0;
    protected static $defaultSort = 'top';

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
     * AUXILIARY
     *
     * Select the appropriate sort function based on order desired.
     *
     * IMPORTANT: Switch statement prevents any SQL injection.
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
    public static function create(int $parentid, int $authorid, string $content) {
        $query = '
            INSERT INTO Comment(parentid, authorid, content)
            VALUES (?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);

        try {
            DB::get()->beginTransaction();
            $stmt->execute([$parentid, $authorid, $content]);
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
    public static function read(int $id) {
        $query = '
            SELECT * FROM CommentAll WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return static::fetch($stmt);
    }

    public static function getChildren(int $parentid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentAuthor WHERE parentid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$parentid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthor(int $authorid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentExtra WHERE authorid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getChildrenAuthor(int $parentid, int $authorid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentEntity WHERE parentid = ? AND authorid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$parentid, $authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readAll(array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentAll
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
    public static function readVoted(int $id, int $userid) {
        $query = '
            SELECT * FROM CommentVotingAll WHERE entityid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id, $userid]);
        return static::fetch($stmt);
    }

    public static function getChildrenVoted(int $parentid, int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentVotingAuthor
            WHERE parentid = ? AND userid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$parentid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthorVoted(int $authorid, int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentVotingExtra
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

    public static function getChildrenAuthorVoted(int $parentid, int $authorid,
            int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentVotingEntity
            WHERE parentid = ? AND authorid = ? AND userid = ?
            AND createdat >= ?
            ORDER BY rating DESC, createdat DESC, entityid ASC
            LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$parentid, $authorid, $userid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function readAllVoted(int $userid, array $more = []) {
        $sort = static::sort($more);

        $query = "
            SELECT *, $sort AS rating
            FROM CommentVotingAll
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
    public static function update(int $entityid, string $content) {
        $query = '
            UPDATE Comment SET content = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $entityid]);
        return $stmt->rowCount();
    }

    public static function clear(int $entityid) {
        $query = '
            UPDATE Comment SET content = "" WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        return $stmt->execute([$entityid]);
    }

    public static function free(int $entityid) {
        $query = '
            UPDATE Comment SET content = "", userid = NULL WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->execute([$entityid]);
    }

    /**
     * DELETE
     */
    public static function delete(int $entityid) {
        $query = '
            DELETE FROM Comment WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    public static function deleteChildren(int $parentid) {
        $query = '
            DELETE FROM Comment WHERE parentid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parentid]);
        return $stmt->rowCount();
    }

    public static function deleteAuthor(int $authorid) {
        $query = '
            DELETE FROM Comment WHERE authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$authorid]);
        return $stmt->rowCount();
    }

    public static function deleteChildrenAuthor(int $parentid, int $authorid) {
        $query = '
            DELETE FROM Comment WHERE parentid = ? AND authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parentid, $authorid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        $query = '
            DELETE FROM Comment
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
