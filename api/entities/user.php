<?php
require_once __DIR__ . '/apientity.php';
require_once __DIR__ . '/image.php';

class User extends APIEntity {
    private static $usernameRegex = '/^[a-zA-Z][a-zA-Z0-9_+-]{2,31}$/i';
    private static $passwordRegex = '/^(?:.){6,}$/i';
    private static $hashOpt = ['cost' => 10];
    
    public static $usernameRequires = "At least 3 characters starting with a letter";
    public static $passwordRequires = "At least 6 characters";

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

    public static function validPassword($password) {
        return preg_match(static::$passwordRegex, $password) === 1;
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

    public static function checkPassword($password) {
        if (!static::validPassword($username)) {
            throw new Error('Invalid password');
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
    private static function getByUsernameHash($username) {
        $query = '
            SELECT * FROM User WHERE username = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$username]);
        return static::fetch($stmt);
    }

    private static function getByEmailHash($email) {
        $query = '
            SELECT * FROM User WHERE email = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$email]);
        return static::fetch($stmt);
    }

    private static function getHash($name) {
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
    public static function getByUsername($username) {
        $user = static::getByUsernameHash($username);
        unset($user['hash']);
        return $user;
    }

    public static function getByEmail($email) {
        $user = static::getByEmailHash($email);
        unset($user['hash']);
        return $user;
    }

    public static function get($name) {
        $user = static::getHash($name);
        unset($user['hash']);
        return $user;
    }

    public static function read($userid) {
        $query = '
            SELECT * FROM UserProfile WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return static::fetch($stmt);
    }

    public static function readAll() {
        $query = '
            SELECT * FROM UserProfile
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return static::fetchAll($stmt);
    }

    public static function self($userid) {
        return static::read($userid);
    }

    /**
     * AUTHENTICATE
     */
    public static function isAdmin($userid) {
        $query = '
            SELECT admin FROM User WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        $row = static::fetch($stmt);
        return $row ? (bool)$row['admin'] : false;
    }

    public static function authenticate($name, $password, &$error = null) {
        $user = static::getHash($name);

        if (!$user) {
            $error = "User $name does not exist";
            return false;
        }

        if (!password_verify($password, $user['hash'])) {
            $error = 'Wrong password';
            return false;
        }

        return true;
    }

    /**
     * UPDATE
     */
    public static function setPassword($userid, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT, static::$hashOpt);
        
        $query = '
            UPDATE User SET password = ? WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$hash, $userid]);
        return $stmt->rowCount();
    }

    public static function setPicture($userid, $imageid) {
        $query = '
            UPDATE User SET imageid = ? WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$imageid, $userid]);
        return $stmt->rowCount();
    }

    public static function clearPicture($userid) {
        $query = '
            UPDATE User SET imageid = NULL WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return $stmt->rowCount();
    }
    
    /**
     * DELETE
     */
    public static function delete($userid) {
        $query = '
            DELETE FROM User WHERE userid = ?
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute([$userid]);
        return $stmt->rowCount();
    }

    public static function deleteAll() {
        $query = '
            DELETE FROM User
            ';

        $stmt = DB::get()->prepare($query);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
