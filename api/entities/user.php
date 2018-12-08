<?php
require_once __DIR__ . '/apientity.php';

class User extends APIEntity {
    private static $usernameRegex = '/^[a-zA-Z][a-zA-Z0-9_+-]{2,31}$/i';
    private static $hashOpt = ['cost' => 10];

    /**
     * VALIDATION
     *
     * Não sei que funções abaixo vão ser úteis. Escolham as mais apropriadas,
     * e apaguem ou ignorem as outras que forem desnecessárias.
     *
     * valid -> true or false
     * check -> void or throw
     */
    public static function validUsername(string $username) {
        return preg_match(static::$usernameRegex, $username) === 1;
    }

    public static function validEmail(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function valid(string $username, string $email, &$error = null) {
        if (!static::validUsername($username)) {
            $error = 'Invalid username';
            return false;
        }

        if (!static::validEmail($email)) {
            $error = 'Invalid email';
            return false;
        }

        return true;
    }

    public static function checkUsername(string $username) {
        if (!static::validUsername($username)) {
            throw new Error('Invalid username');
        }
    }

    public static function checkEmail(string $email) {
        if (!static::validEmail($email)) {
            throw new Error('Invalid email');
        }
    }

    public static function check(string $username, string $email) {
        if (!static::valid($username, $email, $error)) {
            throw new Error($error);
        }
    }

    /**
     * CREATE
     */
    public static function create(string $username, string $email, string $password) {
        static::check($username, $email);

        $hash = password_hash($password, PASSWORD_DEFAULT, static::$hashOpt);

        $query = '
            INSERT INTO User(username, email, hash)
            VALUES (?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        
        try {
            DB::get()->beginTransaction();
            $stmt->execute([$username, $email, $hash]);
            $id = (int)DB::get()->lastInsertId();
            DB::get()->commit();
            return $id;
        } catch (PDOException $e) {
            DB::get()->rollback();
            return false;
        }
    }

    /**
     * READ WITH HASH
     */
    private static function getByUsernameHash(string $username) {
        $query = '
            SELECT * FROM User WHERE username = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$username]);
        return static::fetch($stmt);
    }

    private static function getByEmailHash(string $email) {
        $query = '
            SELECT * FROM User WHERE email = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$email]);
        return static::fetch($stmt);
    }

    private static function getHash(string $name) {
        if (static::validUsername($name)) {
            return static::getByUsernameHash($name);
        }

        if (static::validEmail($name)) {
            return static::getByEmailHash($name);
        }
        
        return false;
    }

    /**
     * READ
     */
    public static function getByUsername(string $username) {
        $user = static::getByUsernameHash($username);
        unset($user['hash']);
        return $user;
    }

    public static function getByEmail(string $email) {
        $user = static::getByEmailHash($email);
        unset($user['hash']);
        return $user;
    }

    public static function get(string $name) {
        $user = static::getHash($name);
        unset($user['hash']);
        return $user;
    }

    public static function read(int $userid) {
        $query = '
            SELECT * FROM UserNohash WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return static::fetch($stmt);
    }

    public static function readAll() {
        $query = '
            SELECT * FROM UserNohash
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return static::fetchAll($stmt);
    }

    public static function self(int $userid) {
        return static::read($userid);
    }

    /**
     * AUTHENTICATE
     */
    public static function authenticate(string $name, string $password, &$error = null) {
        if (!static::validUsername($name)) {
            $error = 'Invalid username';
            return false;
        }

        $user = static::getHash($name);

        if (!$user) {
            $error = 'Invalid username'; // no it doesn't
            return false;
        }

        if (!password_verify($password, $user['hash'])) {
            $error = 'Wrong password';
            return false;
        }

        return true;
    }

    /**
     * NO UPDATE FOR NOW
     */
    
    /**
     * DELETE
     */
    public static function delete($userid) {
        $query = '
            DELETE FROM User WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return DB::get()->rowCount();
    }
}
?>
