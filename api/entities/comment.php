<?php
require_once __DIR__ . '/../config/db.php';

class Comment {
    /**
     * CREATE
     */
    public static function create(int $parent, int $user, string $content) {
        $query = '
            INSERT INTO comment(parent_id, user_id, content)
            VALUES (?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$parent, $user, $content]);
        return true;
    }

    /**
     * READ
     */
    public static function read(int $id) {
        $query = '
            SELECT * FROM comment WHERE entity_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * UPDATE
     */
    public static function update(int $id, string $content) {
        $query = '
            UPDATE comment WHERE entity_id = ? SET content = ?
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
            DELETE FROM comment WHERE entity_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return true;
    }
}
?>