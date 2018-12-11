<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/entity.php';
require_once __DIR__ . '/story.php';

class Comment extends APIEntity {
    /**
     * $more Default Constants
     */
    protected const defaultSince = 0;
    protected const defaultLimit = 50;
    protected const defaultOffset = 0;

    /**
     * AUXILIARY
     * 
     * Select the appropriate story table view based on sorting desired.
     *
     * Switch statement prevents SQL injection.
     */
    private static function sortTablename($orderby) {
        if (!is_string($orderby)) return 'CommentAll';

        switch ($orderby) {
        case 'top': return 'CommentSortTop';
        case 'bot': return 'CommentSortBot';
        case 'controversial': return 'CommentSortControversial';
        case 'average': return 'CommentSortAverage';
        case 'new': return 'CommentSortNew';
        case 'old': return 'CommentSortOld';
        case 'best': return 'CommentSortBest';
        default: return 'CommentAll';
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
    public static function getChildrenAuthor(int $parentid, int $authorid, array $more = null) {
        $sorttable = static::sortTablename($more['orderby']);
        
        $query = "
            SELECT * FROM $sorttable WHERE parentid = ? AND authorid = ?
            WHERE createdat > ? LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$parentid, $authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getChildren(int $parentid, array $more = null) {
        $sorttable = static::sortTablename($more['orderby']);
        
        $query = "
            SELECT * FROM $sorttable WHERE parentid = ?
            WHERE createdat > ? LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$parentid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function getAuthor(int $authorid, array $more = null) {
        $sorttable = static::sortTablename($more['orderby']);

        $query = "
            SELECT * FROM $sorttable WHERE authorid = ?
            WHERE createdat > ? LIMIT ? OFFSET ?
            ";

        $queryArguments = static::extend([$authorid], $more);

        $stmt = DB::get()->prepare($query);
        $stmt->execute($queryArguments);
        return static::fetchAll($stmt);
    }

    public static function read(int $id) {
        $query = '
            SELECT * FROM CommentAll WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return static::fetch($stmt);
    }

    public static function readAll(array $more = null) {
        $sorttable = static::sortTablename($more['orderby']);

        $query = "
            SELECT * FROM $sorttable
            WHERE createdat > ? LIMIT ? OFFSET ?
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
            UPDATE Comment SET content = ? WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$content, $id]);
        return $stmt->rowCount();
    }

    public static function clear(int $id) {
        $query = '
            UPDATE Comment SET content = "" WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        return $stmt->execute([$id]);
    }
    
    /**
     * DELETE
     */
    public static function delete(int $id) {
        $query = '
            DELETE FROM Comment WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
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

    public static function deleteChildren(int $parentid) {
        $query = '
            DELETE FROM Comment WHERE parentid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parentid]);
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
