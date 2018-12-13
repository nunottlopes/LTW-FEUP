<?php
require_once __DIR__ . '/apientity.php';

class Image extends APIEntity {
    /**
     * CREATE
     */
    public static function create(string $filename) {
        $query = '
            INSERT INTO Image(filename) VALUES (?)
            ';

        $stmt = DB::get()->prepare($query);

        try {
            DB::get()->beginTransaction();
            $stmt->execute([$filename]);
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
    public static function read(int $imageid) {
        $query = '
            SELECT * FROM Image WHERE imageid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid]);
        return static::fetch($stmt);
    }

    public static function readAll() {
        $query = '
            SELECT * FROM Image
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
    public static function delete(int $imageid) {
        $query = '
            DELETE FROM Image WHERE imageid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        $query = '
            DELETE FROM Image
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
