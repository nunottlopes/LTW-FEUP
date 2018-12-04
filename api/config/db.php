<?php
class DB {
    private static $db = null;
    private static $apipath = 'api.db';

    // Get the PDO object
    public static function get() {
        if (static::$db == null) static::open();
        return static::$db;
    }

    // Open the PDO connection to the SQLite server
    public static function open() {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/' . static::$apipath;
        static::$db = new PDO('sqlite:' . $path, 
            '', '', array(PDO::ATTR_PERSISTENT => true));
        static::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

DB::open();

?>
