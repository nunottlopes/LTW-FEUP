<?php
require_once __DIR__ . '/../config/db.php';

class Channel {
    private static $channelRegex = '/^[a-zA-Z][a-zA-Z0-9_+-]{2,31}$/i';

    /**
     * VALIDATION
     */
    public static function valid(string $name) {
        return preg_match(static::$channelRegex, $name) === 1;
    }

    public static function check(string $name) {
        if (!static::valid($name)) {
            throw new Error($error);
        }
    }

    /**
     * CREATE
     */
    public static function create(string $name, int $creator, &$error = null) {
        static::check($name);

        $query = '
            INSERT INTO channel(name, creator_id) VALUES (?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        try {
            $stmt->execute([$name, $creator]);
            return true;
        } catch (PDOException $exception) {
            $error = $exception;
            return false;
        }
    }

    /**
     * READ
     */
    public static function get(string $name) {
        $query = '
            SELECT * FROM channel WHERE name = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public static function read(int $id) {
        $query = '
            SELECT * FROM channel WHERE channel_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function readAll() {
        $query = '
            SELECT * FROM channel
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * NO UPDATE FOR NOW
     */
    
    /**
     * NO DELETE FOR NOW
     */
}
?>