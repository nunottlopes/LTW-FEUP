<?php
require_once __DIR__ . '/session.php';

function got(string $key) {
    global $args;
    return isset($args[$key]);
}

class API {
    /**
     * Redirect to appropriate action file.
     */
    public static function action(string $act) {
        global $resource, $supported, $parameters, $method, $args, $auth, $action;
        $file = $_SERVER['DOCUMENT_ROOT'] . "/api/actions/$resource/$act.php";
        require_once $file;
    }

    /**
     * Directory of entity
     */
    public static function entity(string $entity) {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/api/entities/$entity.php";
        return $file;
    }

    /**
     * Directory of resource
     */
    public static function resource(string $resource) {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/api/public/$resource.php";
        return $file;
    }
}

require_once API::entity('user');

/**
 * Class managing user authentication and resource access authorization levels
 */
class Auth {
    private static $authRegex = '/^Basic ((?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?)$/';

    private static $authorizationParsed = false;
    private static $authorizationUser = null;

    /**
     * Check if userid is an admin.
     * Admin specs might change in the future, e.g. another column on the
     * database's user table specifying authorization level, or specific username.
     */
    private static function isAdminUser(int $userid) {
        return $userid === 0;
    }

    /**
     * Authenticate a user without creating a logged in session.
     * 
     * Returns an object holding the userid, username and email if successful.
     * Returns false otherwise.
     */
    public static function autho(string $name, string $password, &$error = null) {
        if (User::authenticate($name, $password, $error)) {
            $user = User::get($name);

            if (static::isAdminUser($user['userid'])) {
                $user['admin'] = true;
            } else {
                $user['admin'] = false;
            }

            return $user;
        }
        
        return false;
    }

    /**
     * Authenticate a user and create a logged in session if successful.
     * 
     * Returns an object holding the userid, username and email if successful.
     * Returns false otherwise.
     * 
     * Failed authentication does not change state nor call an HTTPResponse method.
     *
     * It is assumed that a session has already been started.
     */
    public static function login(string $name, string $password, &$error = null) {
        if (User::authenticate($name, $password, $error)) {
            $user = User::get($name);

            $_SESSION['userid'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['useremail'] = $user['email'];
            $_SESSION['login_timestamp'] = time();

            if (static::isAdminUser($user['userid'])) {
                $_SESSION['admin'] = true;
                $user['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
                $user['admin'] = false;
            }

            return $user;
        }

        return false;
    }

    /**
     * Attempt to authenticate a user using the HTTP header 'Authorization'.
     *
     * If the header is not present the authentication fails.
     * If the header is present but the credentials are incorrect it fails too.
     *
     * Returns an array representing the authenticated user if successful, or false.
     */
    public static function authorization() {
        // Cache
        if (static::$authorizationParsed) return static::$authorizationUser;

        static::$authorizationParsed = true;

        $header = HTTPRequest::header('Authorization');

        if ($header !== null) {
            // Regex parse
            if (!preg_match(static::$authRegex, $header, $matches)) {
                HTTPResponse::badHeader('Authorization', $header);
            }

            // Base 64 decode
            $string = base64_decode($matches[1], true);

            if (!$string) {
                HTTPResponse::badHeader('Authorization', $header);
            }

            // Username:Password split, might replace with regex parse in the future
            $split = explode(':', $string, 2);

            if (count($split) < 2) {
                HTTPResponse::badHeader('Authorization', $string);
            }

            $username = $split[0];
            $password = $split[1];

            $user = static::autho($username, $password, $error);

            //if (!$user) HTTPResponse::unauthorized();

            static::$authorizationUser = $user;
            return $user;
        }
        
        return false;
    }

    /**
     * Attempt to authenticate a user using the current session.
     * 
     * Returns an array representing the authenticated user if successful, or false.
     */
    public static function session() {
        $auth = isset($_SESSION) && isset($_SESSION['userid']);

        if (!$auth) return false;

        $userid = $_SESSION['userid'];

        return [
            'userid' => $userid,
            'username' => $_SESSION['username'],
            'email' => $_SESSION['useremail'],
            'admin' => static::isAdminUser($userid)
        ];
    }

    /**
     * Practical authentication function. Authenticate the user using a mix of
     * session and basic authorization.
     *
     * If the session is not authenticated, authorization() is called.
     *
     * It is assumed that a session has already been started.
     */
    public static function authenticate() {
        $auth = static::session();
        if ($auth) return $auth;

        $auth = static::authorization();
        if ($auth) return $auth;

        return false;
    }

    /**
     * Checks whether the sent authorization header, if existent, provides
     * authorization to access a resource with a certain authorization level.
     *
     * Levels
     *   - open,
     *     free         Anyone can access the resource.
     *   - auth,
     *     user         Accessible if the user is authorized.
     *   - authid,
     *     userid       Accessible if the user is authorized as particular user.
     *   - privileged,
     *     admin        Authorized as admin.
     *   - none         Not authorized.
     *
     * Returns
     *     true       if authentication is not achieved and not required.
     *     false      if authentication is not achieved and is required.
     *     user array if authentication is achieved and required.
     */
    public static function authLevel(string $level, int $userid = null) {
        $auth = static::authorization();

        if (!$auth) return $level === 'open' || $level === 'free' || $level === 'none';

        $authid = $auth['userid'];
        $admin = $auth['admin'];

        switch ($level) {
        case 'open':
        case 'free':
        case 'auth':
        case 'user':
            $allow = true;
            break;
        case 'authid':
        case 'userid':
            $allow = $authid === $userid;
            break;
        case 'privileged':
        case 'admin':
            $allow = $admin;
            break;
        case 'none':
        default:
            $allow = false;
            break;
        }

        return $allow ? $auth : false;
    }

    /**
     * Checks whether the current session has authorization to access a resource with
     * a certain authorization level.
     *
     * Levels
     *   - open,
     *     free         Anyone can access the resource.
     *   - auth,
     *     user,
     *     login,
     *     logged,
     *     loggedin     Accessible if the user is logged in.
     *   - authid,
     *     userid,
     *     loginid,
     *     loggedid,
     *     loggedinid   Accessible if the user is logged in as a particular user.
     *   - privileged,
     *     admin        Logged in as admin.
     *   - none         No session.
     *
     * Returns
     *     true       if authentication is not achieved and not required.
     *     false      if authentication is not achieved and is required.
     *     user array if authentication is achieved and required.
     */
    public static function sessionLevel(string $level, int $userid = null) {
        $auth = static::session();

        if (!$auth) return $level === 'open' || $level === 'free' || $level === 'none';

        $authid = $auth['userid'];
        $admin = $auth['admin'];

        switch ($level) {
        case 'open':
        case 'free':
        case 'auth':
        case 'login':
        case 'logged':
        case 'loggedin':
            $allow = true;
            break;
        case 'authid':
        case 'loginid':
        case 'user':
            $allow = $authid === $userid;
            break;
        case 'privileged':
        case 'admin':
            $allow = $admin;
            break;
        case 'none':
        default:
            $allow = false;
            break;
        }

        return $allow ? $auth : false;
    }

    /**
     * Checks whether the client has authorization to access a resource with
     * a certain authorization level.
     *
     * Levels
     *   - open,
     *     free         Anyone can access the resource.
     *   - auth,
     *     user         Accessible if the user is authenticated.
     *   - login,
     *     logged,
     *     loggedin     Accessible if the user is logged in.
     *   - authid,
     *     userid       Accessible if the user is authenticated as a particular user.
     *   - loginid,
     *     loggedid,
     *     loggedinid   Accessible if the user is logged in as a particular user.
     *   - privileged,
     *     admin        Authenticated as admin.
     *
     * Returns
     *     true       if authentication is not achieved and not required.
     *     false      if authentication is not achieved and is required.
     *     user array if authentication is achieved and required.
     */
    public static function level(string $level, int $userid = null) {
        $auth = static::sessionLevel($level, $userid);
        if ($auth) return $auth;

        $auth = static::authLevel($level, $userid);
        if ($auth) return $auth;

        return false;
    }

    /**
     * Force authLevel to succeed.
     *
     * If it does not, an appropriate response is sent to the user.
     *
     * Response:
     *   - open,
     *     free         Succeeds.
     *   - auth,
     *     user         401 Unauthorized.
     *   - authid,
     *     userid       401 Unauthorized, user identified.
     *   - privileged,
     *     admin        403 Forbidden.
     */
    public static function demandAuthLevel(string $level, int $userid = null) {
        $auth = static::authLevel($level, $userid);

        if ($auth) return $auth;

        switch ($level) {
        case 'auth':
        case 'user':
        case 'none':
            HTTPResponse::unauthorized();
        case 'authid':
        case 'userid':
            HTTPResponse::unauthorized($userid);
        case 'privileged':
        case 'admin':
            HTTPResponse::forbidden();
        }
    }

    /**
     * Force sessionLevel to succeed.
     *
     * If it does not, an appropriate response is sent to the user.
     *
     * Response:
     *   - open,
     *     free         Succeeds.
     *   - auth,
     *     user,
     *     login,
     *     logged,
     *     loggedin     401 Unauthorized.
     *   - authid,
     *     userid,
     *     loginid,
     *     loggedid,
     *     loggedinid   401 Unauthorized, user identified.
     *   - privileged,
     *     admin        403 Forbidden.
     */
    public static function demandSessionLevel(string $level, int $userid = null) {
        $auth = static::sessionLevel($level, $userid);

        if ($auth) return $auth;

        switch ($level) {
        case 'auth':
        case 'user':
        case 'login':
        case 'logged':
        case 'loggedin':
        case 'none':
            HTTPResponse::unauthorized();
        case 'authid':
        case 'userid':
        case 'loginid':
        case 'loggedid':
        case 'loggedinid':
            HTTPResponse::unauthorized($userid);
        case 'privileged':
        case 'admin':
            HTTPResponse::forbidden();
        }
    }

    /**
     * Force level to succeed.
     *
     * If it does not, an appropriate response is sent to the user.
     *
     * Response:
     *   - open,
     *     free         Succeeds.
     *   - auth,
     *     user,
     *     login,
     *     logged,
     *     loggedin     401 Unauthorized.
     *   - authid,
     *     userid,
     *     loginid,
     *     loggedid,
     *     loggedinid   401 Unauthorized, user identified.
     *   - privileged,
     *     admin        403 Forbidden.
     */
    public static function demandLevel(string $level, int $userid = null) {
        $auth = static::level($level, $userid);

        if ($auth) return $auth;

        switch ($level) {
        case 'auth':
        case 'user':
        case 'login':
        case 'logged':
        case 'loggedin':
        case 'none':
            HTTPResponse::unauthorized();
        case 'authid':
        case 'userid':
        case 'loginid':
        case 'loggedid':
        case 'loggedinid':
            HTTPResponse::unauthorized($userid);
        case 'privileged':
        case 'admin':
            HTTPResponse::forbidden();
        }
    }

    /**
     * Logout and start a new session.
     * Idempotent, does not fail if there is no login.
     */
    public static function logout() {
        session_destroy();
        session_start();

        if (isset($_SESSION['userid'])) unset($_SESSION['userid']);
        if (isset($_SESSION['username'])) unset($_SESSION['username']);
        if (isset($_SESSION['useremail'])) unset($_SESSION['useremail']);
        if (isset($_SESSION['login_timestamp'])) unset($_SESSION['login_timestamp']);
        if (isset($_SESSION['authkey'])) unset($_SESSION['authkey']);

        return true;
    }
}

/**
 * Utilities for parsing the client's HTTP request.
 */
class HTTPRequest {
    /**
     * Assume GET or HEAD and parse the query's key value pairs into a JSON.
     *
     * If $force is set all parameters searched must be present.
     */
    public static function parseQuery(array $parameters, bool $force = false) {
        $data = [];

        foreach ($parameters as $param) {
            if (!isset($_GET[$param])) {
                if ($force) HTTPResponse::missingParameter($param, $parameters);
            } else {
                $data[$param] = $_GET[$param];
            }
        }

        return $data;
    }

    /**
     * Assume POST, PUT, ... and parse the request's body into a JSON.
     *
     * If $force is set all parameters searched must be present.
     */
    public static function parseBodyJSON(array $parameters, bool $force = false) {
        $data = [];

        $json = static::bodyJSON();

        foreach ($parameters as $param) {
            if (!isset($json[$param])) {
                if ($force) HTTPResponse::missingParameter($param, $parameters);
            } else {
                $data[$param] = $json[$param];
            }
        }

        return $data;
    }

    /**
     * Get request parameters according to request method.
     */
    public static function parse(array $parameters, bool $force = false) {
        $method = static::method();

        if ($method === 'GET' || $method === 'HEAD') {
            return static::parseQuery($parameters, $force);
        } else {
            return static::parseBodyJSON($parameters, $force);
        }
    }

    /**
     * Get the request method.
     */
    public static function method(array $supported = null, bool $force = false) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($supported === null) return $method;

        if (in_array($method, $supported, true)) {
            return $method;
        } else if ($force) {
            HTTPResponse::badMethod($supported);
        } else {
            return false;
        }
    }

    /**
     * Get the request's query string.
     */
    public static function queryString() {
        return $_SERVER['QUERY_STRING'];
    }

    /**
     * Get the request's body string.
     */
    public static function bodyString() {
        return file_get_contents('php://input');
    }

    /**
     * Assume application/json and return the request's body as JSON.
     * If the body has invalid JSON a 400 HTTP response is sent to the client.
     */
    public static function bodyJSON() {
        $body = static::bodyString();
        $json = json_decode($body, true);

        if ($json === null) {
            HTTPResponse::malformedJSON($body, $parameters);
        }

        return $json;
    }

    /**
     * Get the request's headers.
     */
    public static function headers() {
        return apache_request_headers();
    }

    /**
     * Get a specific header field.
     */
    public static function header(string $header) {
        $headers = static::headers();

        if (isset($headers[$header])) {
            return $headers[$header];
        } else {
            return null; // maybe false?
        }
    }
}

/**
 * API's HTTP response abstractions.
 *
 * Calling any public method will terminate the script and answer the client.
 *
 * Every method except json() and plain() assume -nothing- has been echoed yet,
 * effectively disregarding all echoes and emitting headers freely.
 */
class HTTPResponse {
    private static $authenticationRealm = 'FEUP News';

    /**
     * Output response body as JSON and exit.
     */
    private static function json(array $json) {
        header("Content-Type: application/json");

        if (HTTPRequest::method() !== 'HEAD') {
            echo json_encode($json, JSON_PRETTY_PRINT);
        }

        exit(0);
    }

    /**
     * Output response body as plain text JSON and exit.
     */
    private static function plain(array $json) {
        header("Content-Type: text/plain");

        if (HTTPRequest::method() !== 'HEAD') {
            echo json_encode($json, JSON_PRETTY_PRINT);
        }

        exit(0);
    }

    /**
     * Append several variables to a JSON, output response body and exit.
     * Redirects to json() for output. plain() not used as of now.
     */
    private static function output(string $code, string $message, array $data = null) {
        global $resource, $supported, $parameters, $method, $args, $auth, $action;

        $json = [
            'message' => $message,          // User readable [success] message
            'code' => $code,                // Machine readable [success] message
            'args' => $args,                // Client provided arguments
            'auth' => $auth,                // Request performed as this user
            'resource' => $resource,        // Resource accessed
            'action' => $action,            // Action performed on this resource
            'supported' => $supported,      // Methods supported on this resource
            'parameters' => $parameters,    // Parameters supported on this resource
            'method' => $method,            // HTTP request method
            'data' => $data
        ];

        static::json($json);
    }

    /**
     * Like output() but intended for error messages.
     */
    private static function error(string $code, string $error, array $data = null) {
        global $resource, $supported, $parameters, $method, $args, $auth, $action;

        $json = [
            'message' => $error,            // User readable [error] message
            'error' => $error,              // User readable [error] message
            'code' => $code,                // Machine readable [error] message
            'args' => $args,                // Client provided arguments, possibly null
            'auth' => $auth,                // Request performed as this user
            'resource' => $resource,        // Resource accessed
            'action' => $action,            // Action performed on this resource
            'supported' => $supported,      // Methods supported on this resource
            'parameters' => $parameters,    // Parameters supported on this resource
            'method' => $method,            // HTTP request method
            'data' => $data
        ];

        static::json($json);
    }

    /**
     * HTTP Server Response Methods
     *
     * Most methods accept argument $message, holding a human readable description
     * of the result, and $data, holding the specifically requested resource
     * or other willingly given data.
     */
    
    /**
     * 200 OK
     * No arguments provided, querying resource.
     */
    public static function look(string $message, array $data) {
        http_response_code(200);

        static::output('Query resource', $message, $data);
    }

    /**
     * 200 OK
     */
    public static function ok(string $message, array $data) {
        http_response_code(200);

        static::output('OK', $message, $data);
    }

    /**
     * 200 OK
     */
    public static function updated(string $message, array $data = null) {
        http_response_code(200);

        static::output('Updated', $message, $data);
    }

    /**
     * 200 OK
     */
    public static function deleted(string $message, array $data = null) {
        http_response_code(200);

        static::output('Deleted', $message, $data);
    }

    /**
     * 201 Created
     */
    public static function created(string $message, array $data) {
        http_response_code(201);

        static::output('Created', $message, $data);
    }

    /**
     * 202 Accepted
     */
    public static function accepted(string $message, array $data = null) {
        http_response_code(202);

        static::output('Accepted', $message, $data);
    }

    /**
     * 204 No Content
     * No response body.
     */
    public static function okNoContent() {
        http_response_code(204);

        exit(0);
    }

    /**
     * 205 Reset Content
     * No response body.
     */
    public static function okResetContent() {
        http_response_code(205);

        exit(0);
    }

    /**
     * 400 Bad Request
     * The request performed on the specified entity was successfully deduced to an
     * action based on the arguments provided, but that action requires another(s)
     * argument which was not provided.
     */
    public static function missingParameter(string $param, array $parameters) {
        http_response_code(400);

        $error = "Required parameter \"$param\" is missing";

        $data = [
            'missing' => $param,
            'key' => $param,
            'keys' => $parameters
        ];

        static::output('Missing Parameter', $error, $data);
    }

    /**
     * 400 Bad Request
     * The request performed on the specified entity was successfully deduced to an
     * action based on the arguments provided, but a particular argument had an
     * incorrect value (probably of the wrong type).
     */
    public static function badArgument(string $param, string $value) {
        http_response_code(400);

        $error = "Parameter \"$param\" has an unexpected value";

        $data = [
            'param' => $param,
            'key' => $param,
            'value' => $value
        ];

        static::error('Bad Argument', $error, $data);
    }

    /**
     * 400 Bad Request
     * The request body was treated as a JSON and the parsing was unsuccessful.
     */
    public static function malformedJSON() {
        http_response_code(400);

        $error = "Request body contains marformed JSON";

        $data = [
            'body' => HTTPRequest::bodyString()
        ];

        static::error('Malformed JSON', $error, $data);
    }

    /**
     * 400 Bad Request
     * A request header sent has an invalid/unexpected value.
     */
    public static function badHeader(string $header, string $value) {
        http_response_code(400);

        $error = "Header $header has an unexpected value: $value";

        $data = [
            'header' => $header,
            'value' => $value
        ];

        static::error('Bad Header', $error, $data);
    }

    /**
     * 400 Bad Request
     * The requested resource supports the used method, but not for the supplied
     * combination of arguments. More should be provided to deduce the specific action.
     * Header Allow present.
     * Might change into a 405 in the future.
     */
    public static function noAction(string $method) {
        http_response_code(400);

        global $supported;

        $allowed = implode(', ', $supported);
        header("Allow: $allowed");

        $error = "Action could not be deduced from the provided arguments";

        $data = [
            'supported' => $supported,
            'method' => $method
        ];

        static::error('No Action', $error, $data);
    }

    /**
     * 400 Bad Request
     * General 400 error with a generic message and data JSON.
     */
    public static function badRequest(string $error, array $data = null) {
        http_response_code(400);

        static::error('Bad Request', $error, $data);
    }

    /**
     * 401 Unauthorized
     * The resource requested is not accessible to the client, or an
     * authentication attempt failed. Required header WWW-Authenticate is present.
     */
    public static function unauthorized(int $userid = null) {
        http_response_code(401);
        header("WWW-Authenticate: Basic realm=\"FEUP News\"");

        if ($userid === null) {
            $error = "Unauthorized request: requires login";

            $data = [];
        } else {
            $error = "Unauthorized request: requires login as $userid";

            $data = [
                'userid' => $userid
            ];
        }

        static::error('Unauthorized', $error, $data);
    }

    /**
     * 403 Forbidden
     * The resource requires privileged (admin) access, so the user cannot access it.
     */
    public static function forbidden() {
        http_response_code(403);

        $error = "Forbidden request: requires privileged access";

        static::error('Forbidden', $error);
    }

    /**
     * 403 Forbidden
     * The request tried to create a resource that conflicted with an already existing
     * resource.
     */
    public static function conflict(string $error, string $entity, string $culprit) {
        http_response_code(403);

        $error = "Conflict: $error";

        $data = [
            'culprit' => $culprit,
            'entity' => $entity
        ];

        static::error('Conflict', $error, $data);
    }

    /**
     * 404 Not Found
     * The resource requested by the client does not exist.
     */
    public static function notFound(string $resource) {
        http_response_code(404);

        $error = "Requested resource does not exist: $resource";

        $data = [
            'resource' => $resource
        ];

        static::error('Not Found', $error, $data);
    }

    /**
     * 404 Not Found
     * The requested action on the resource seems valid, but said action requires
     * the existence of another resource as specified by the client's arguments
     * and that resource does not exist.
     */
    public static function adjacentNotFound(string $adjacent) {
        http_response_code(404);

        $error = "Implied adjacent entity does not exist: $adjacent";

        $data = [
            'resource' => $adjacent,
            'adjacent' => $adjacent
        ];

        static::error('Adjacent Not Found', $error, $data);
    }

    /**
     * 405 Method Not Allowed
     * The requested resource does not support the used method for any combination
     * of arguments.
     * Required header Allow present.
     */
    public static function badMethod(string $method) {
        http_response_code(405);

        global $supported;

        $allowed = implode(', ', $supported);
        header("Allow: $allowed");

        $error = "HTTP request method $method not supported for this resource";

        $data = [
            'allowed' => $supported,
            'supported' => $supported,
            'method' => $method
        ];

        static::error('Bad Method', $error, $data);
    }

    /**
     * 500 Internal Server Error
     */
    public static function serverError(array $data = []) {
        http_response_code(500);
        header("Retry-After: 3");

        $error = "Internal Server Error processing the request";

        static::error('Internal Server Error', $error, $data);
    }

    /**
     * 503 Service Unavailable
     * Database schema change (debugging only, presumably...)
     */
    public static function schemaChanged(array $data = []) {
        http_response_code(503);
        header("Retry-After: 1");

        $error = "Database schema changed, try again immediately.";

        static::error('Schema changed', $error, $data);
    }

    /**
     * 503 Service Unavailable
     */
    public static function unavailable(string $message, int $time, array $data = []) {
        http_response_code(503);
        header("Retry-After: $time");

        $error = "Service unavailable: $message";

        static::error('Service Unavailable', $error, $data);
    }
}
?>
