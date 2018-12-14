<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/image.php';

class Channel extends APIEntity {
    private static $channelRegex = '/^[a-zA-Z][a-zA-Z0-9_+-]{2,31}$/i';

    /**
     * VALIDATION
     */
    public static function valid(string $channelname) {
        return preg_match(static::$channelRegex, $channelname) === 1;
    }

    public static function check(string $channelname) {
        if (!static::valid($channelname)) {
            throw new Error("Invalid channelname");
        }
    }

    /**
     * CREATE
     */
    public static function create(string $channelname, int $creatorid) {
        static::check($channelname);

        $query = '
            INSERT INTO Channel(channelname, creatorid)
            VALUES (?, ?)
            ';

        $stmt = DB::get()->prepare($query);

        try {
            DB::get()->beginTransaction();
            $stmt->execute([$channelname, $creatorid]);
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
    public static function get(string $channelname) {
        $query = '
            SELECT * FROM ChannelAll WHERE channelname = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelname]);
        return static::fetch($stmt);
    }

    public static function getCreator(int $creatorid) {
        $query = '
            SELECT * FROM ChannelBanner WHERE creatorid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$creatorid]);
        return static::fetchAll($stmt);
    }

    public static function read(int $channelid) {
        $query = '
            SELECT * FROM ChannelAll WHERE channelid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid]);
        return static::fetch($stmt);
    }

    public static function readAll() {
        $query = '
            SELECT * FROM ChannelAll
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return static::fetchAll($stmt);
    }

    /**
     * UPDATE
     */
    public static function setBanner(int $channelid, int $imageid) {
        $query = '
            UPDATE Channel SET imageid = ? WHERE channelid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid, $channelid]);
        return $stmt->rowCount();
    }

    public static function clearBanner(int $channelid) {
        $query = '
            UPDATE Channel SET imageid = NULL WHERE channelid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid]);
        return $stmt->rowCount();
    }
    
    /**
     * DELETE
     */
    public static function delete(int $channelid) {
        $query = '
            DELETE FROM Channel WHERE channelid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channelid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        $query = '
            DELETE FROM Channel
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
