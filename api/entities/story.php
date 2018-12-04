<?php
require_once __DIR__ '/../config/db.php';

class Story {
    /**
     * CREATE
     */
    public static function create(string $channel, int $user, string $title,
            string $type, string $content) {
        $query = '
            INSERT INTO story(channel_id, user_id, title, type, content)
            VALUES (?, ?, ?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channel, $user, $title, $type, $content]);
        return true;
    }

    /**
     * READ
     */
    public static function read(int $id) {
        $query = '
            SELECT * FROM story WHERE entity_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function readChannel(int $channel) {
        $query = '
            SELECT * FROM story WHERE channel_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$channel]);
        return $stmt->fetchAll();
    }

    public static function readUser(int $user) {
        $query = '
            SELECT * FROM story WHERE user_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$user]);
        return $stmt->fetchAll();
    }

    /**
     * UPDATE
     */
    public static function update(int $id, string $content) {
        $query = '
            UPDATE story WHERE entity_id = ? SET content = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id, $content]);
        return true;
    }
    
    /**
     * DELETE
     */
    public static function delete(int $id) {
        $query = '
            DELETE FROM story WHERE entity_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return true;
    }
}
?>