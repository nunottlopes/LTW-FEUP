<?php
require_once '../config/db.php';

class User {
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
    public static function validUsername($username) {
        return preg_match(static::$usernameRegex, $username) === 1;
    }

    public static function validEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function valid($username, $email, &$error = null) {
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

    public static function checkUsername($username) {
        if (!static::validUsername($username)) {
            throw new Error('Invalid username');
        }
    }

    public static function checkEmail($email) {
        if (!static::validEmail($email)) {
            throw new Error('Invalid email');
        }
    }

    public static function check($username, $email) {
        if (!static::valid($username, $email, $error)) {
            throw new Error($error);
        }
    }

    /**
     * CREATE
     */
    public static function create($username, $email, $password) {
        static::check($username, $email);

        $hash = password_hash($password, PASSWORD_DEFAULT, static::$hashOpt);

        $query = '
            INSERT INTO user(username, email, hash) VALUES (?, ?, ?)
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$username, $email, $hash]);
        return true;
    }

    /**
     * READ
     */
    public static function getByUsername($username) {
        $query = '
            SELECT * FROM user WHERE username = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public static function getByEmail($email) {
        $query = '
            SELECT * FROM user WHERE email = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function get($name) {
        if (static::validUsername($name)) {
            return static::getByUsername($name);
        }

        if (static::validEmail($name)) {
            return static::getByEmail($name);
        }
        
        return false;
    }

    public static function read($id) {
        $query = '
            SELECT * FROM user WHERE user_id = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function readAll() {
        $query = '
            SELECT * FROM user
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * AUTHENTICATE
     */
    public static function authenticate($name, $password, &$error = null) {
        $user = static::get($name);

        if (!$user) {
            $error = 'User not found';
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
     * NO DELETE FOR NOW
     */
}
?>
