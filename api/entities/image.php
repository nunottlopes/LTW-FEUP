<?php
require_once __DIR__ . '/apientity.php';

const THUMBNAIL_SIDE = 256;
const SMALL_MAXWIDTH = 512;
const MEDIUM_MAXWIDTH = 1024;
const IMAGE_DELETE_UPLOAD = false;

class Image extends APIEntity {
    /**
     * CONVENTION
     */
    public static function filename(string $imageid, string $extension) {
        return "img$imageid.$extension";
    }

    public static function files(string $imagefile) {
        $UPLOAD_DIR = $_SERVER['DOCUMENT_ROOT'] . '/feup_books/images/upload';

        return [
            'original'  => "$UPLOAD_DIR/original/$imagefile",
            'medium'    => "$UPLOAD_DIR/medium/$imagefile",
            'small'     => "$UPLOAD_DIR/small/$imagefile",
            'thumbnail' => "$UPLOAD_DIR/thumbnail/$imagefile"
        ];
    }

    public static function glob() {
        $UPLOAD_DIR = $_SERVER['DOCUMENT_ROOT'] . '/feup_books/images/upload';

        return [
            'original'  => "$UPLOAD_DIR/original/*",
            'medium'    => "$UPLOAD_DIR/medium/*",
            'small'     => "$UPLOAD_DIR/small/*",
            'thumbnail' => "$UPLOAD_DIR/thumbnail/*"
        ];
    }

    /**
     * CREATE
     */
    public static function create() {
        $query = '
            INSERT INTO Image DEFAULT VALUES
            ';

        $stmt = DB::get()->prepare($query);

        try {
            DB::get()->beginTransaction();
            $stmt->execute();
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
     * UPDATE
     */
    public static function setInfo(int $imageid, string $imagefile, array $info) {
        $width = $info['original']['width'];
        $height = $info['original']['height'];
        $filesize = $info['filesize'];
        $format = $info['format'];

        $query = '
            UPDATE Image
            SET imagefile = ?,
                width = ?,
                height = ?,
                filesize = ?,
                format = ?
            WHERE imageid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imagefile, $width, $height, $filesize, $format, $imageid]);
        return $stmt->rowCount();
    }

    public static function eraseInfo(int $imageid) {
        $query = '
            UPDATE Image
            SET imagefile = NULL,
                width = NULL,
                height = NULL,
                filesize = NULL,
                format = NULL
            WHERE imageid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid]);
        return $stmt->rowCount();
    }
    
    /**
     * DELETE
     */
    public static function unlink(string $imagefile) {
        $files = static::files($imagefile);

        $count = 0;

        foreach ($files as $file) {
            if (file_exists($file)) {
                ++$count;
                unlink($file);
            }
        }

        return $count;
    }

    public static function unlinkAll() {
        $files = static::glob();

        $count = 0;

        foreach ($files as $file) {
            if (file_exists($file) && is_file($file)) {
                ++$count;
                unlink($file);
            }
        }
    }

    public static function delete(int $imageid) {
        $image = static::read($imageid);

        if (!$image) return 0;

        if (IMAGE_DELETE_UPLOAD && isset($image['imagefile'])) {
            static::unlink($image['imagefile']);
        }

        $query = '
            DELETE FROM Image WHERE imageid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        if (IMAGE_DELETE_UPLOAD) static::unlinkAll();

        $query = '
            DELETE FROM Image
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
