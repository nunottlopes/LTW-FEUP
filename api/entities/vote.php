<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/entity.php';

class Vote extends APIEntity {
    /**
     * CREATE
     */
    protected static function create(int $entityid, int $userid, string $vote) {
        $query = '
            INSERT INTO Vote(entityid, userid, vote)
            VALUES (?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid, $vote]);
        return $stmt->rowCount();
    }

    public static function upvote(int $entityid, int $userid) {
        return static::create($entityid, $userid, '+');
    }

    public static function downvote(int $entityid, int $userid) {
        return static::create($entityid, $userid, '-');
    }

    /**
     * READ
     */
    public static function getEntity(int $entityid) {
        $query = '
            SELECT * FROM Vote WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return static::fetchAll($stmt);
    }

    public static function getUser(int $userid) {
        $query = '
            SELECT * FROM Vote WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return static::fetchAll($stmt);
    }

    public static function get(int $entityid, int $userid) {
        $query = '
            SELECT * FROM Vote WHERE entityid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid]);
        return static::fetch($stmt);
    }

    public static function readAll() {
        $query = '
            SELECT * FROM Vote
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
            DELETE FROM Vote WHERE entityid = ? AND userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid, $userid]);
        return $stmt->rowCount();
    }

    public static function deleteEntity(int $entityid) {
        $query = '
            DELETE FROM Vote WHERE entityid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$entityid]);
        return $stmt->rowCount();
    }

    public static function deleteUser(int $userid) {
        $query = '
            DELETE FROM Vote WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        $query = '
            DELETE FROM Vote
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
