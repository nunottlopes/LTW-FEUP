<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/entity.php';
require_once __DIR__ . '/story.php';

class Comment extends APIEntity {    
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
    public static function getUser(int $authorid) {
        $query = '
            SELECT * FROM CommentAll WHERE authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$authorid]);
        return static::fetchAll($stmt);
    }

    public static function getChildren(int $parentid) {
        $query = '
            SELECT * FROM CommentAll WHERE parentid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parentid]);
        return static::fetchAll($stmt);
    }

    public static function getChildrenUser(int $parentid, int $authorid) {
        $query = '
            SELECT * FROM CommentAll WHERE parentid = ? AND authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parentid, $authorid]);
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

    public static function readAll() {
        $query = '
            SELECT * FROM CommentAll
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
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

    public static function deleteUser(int $authorid) {
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

    public static function deleteChildrenUser(int $parentid, int $authorid) {
        $query = '
            DELETE FROM Comment WHERE parentid = ? AND authorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parentid, $authorid]);
        return $stmt->rowCount();
    }
}
?>
