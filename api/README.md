# API

A quick rundown of what is implemented in the API, plus a quick reference to specific parts of the code where you may find what you are looking for.

# Quick Lookup

    Testing the API
        go to page feup_books/api/public/test.php and use the javascript object "api"
        see also:
        api/test.js
        feup_books/javascript/api-ajax.js

    Database schema:
        folder api/sql/

    REST API entities:
        folder api/entities/

    REST API resources:
        folder feup_books/api/public/

    User Authentication:
        class   Auth  in  api/api.php
        entity  User  in  api/entities/user.php

        Checking credentials:
            User::authenticate()
        Checking permission:
            Auth::demandLevel(), Auth::level()
        Authenticating:
            Auth::login(), Auth::logout(), Auth::authenticate()

    CSRF prevention:
        api/session.php
        CSRF token is the same for all pages and expires after a
        set amount of seconds.

    XSS

## Database: api/sql/

#### Files

    sql/schema.sql
        database schema tables and indexes
    
    sql/views.sql
        abstraction views, to be used by the PHP application to communicate
        with sqlite as simply as possible and create simple to understand queries.
    
    sql/triggers.sql
        triggers, namely: propagation of inserts in Story/Comment to Entity/Tree
        and update of vote counts in Entity after an insert in Vote.

    sql/basic-populate.sql
        populate the schema with a minimally rich set of data to test the API.

    IMPORTANT:
    sql/init.sql
        read this to setup an empty database.

    sql/reset.sql
        read this to reset a database and populate with sql/basic-populate.sql

#### Tables
    
    Image
        store image metadata and filename
    Entity
        not to be confused with the API entities in api/entities/
        parent table of Story and Comment. 
        stores the count of upvotes/downvotes for efficiency, plus
        creation and update times.
        entities can be *voted* and *saved* by users.
            note: Entity does not contain the authorid (or the content) entries of Story
        and Comment because of implementation issues regarding PDO and inserting into a
        view in SQLite.
    User
        the user table.
        includes authentication information (password hashes and admin flag) and picture.
    Channel
        each channel has a creator and a banner.
    Story
        each story may be of one of three types: text, title, image
            'text' stories contain only text.
            'title' stories contain only a title.
            'image' stories contain an image and optionally text.
        all stories belong to a fixed channel and have an immutable title and type.
        the story's content and image is, however, editable by its author.
        a story survives if its author is deleted, but not if the channel is deleted.
    Comment
        comments are multilevel, so each comment has a parent Entity which may be
        a Story or another Comment.
        a comment's content is editable by its author.
        a comment survives if its author is deleted, but not if its parent is deleted.
    Tree
        a closure table containing hierarchical relationship between Entities.
        consider a comment tree with root at an entity A (story or comment)
        for every entity (comment) B present in the comment tree of A we have
        an entry (ascendantid, descendantid) in this table, with depth being
        the level difference between A and B.
    Save
        each User save of an Entity is stored here, timestamped.
    Vote
        each User vote of an Entity is stored here, + for upvote and - for downvote.
        no reason to create two different tables for up and down votes.

#### Views (Quick overview)

There are a lot of them, yes. Almost all of them are used, at least as buildups for later views.

The Image and User views mainly filter and rename the columns in their respective tables, to be used to simplify later views. In particular, no User view contains the password hash.

The **Voting** view is important: it pairs each *userid* with an *entityid* and holds two extra columns *vote* and *save*, with not null values if the User *userid* has voted on or saved Entity *entityid*, respectively. This view is multiple types later to attach voting and save information to complicated and rich views holding a lot of information.

The Channel views attach information about the channer's banner and creator to a Channel query.

The Story views attach information about the story's image, author and channel to a Story query, including the number of child comments in its comment tree.

The Comment views attach information about the comment's tree position and author to a Comment query, including child comments in its comment tree and parent story.

The AnyEntity views, verbose as they are, combine the Story views and the Comment views, producing a single rich table for a query which does not distinguish between story and comment.

The Tree view CommentAncestryTree is used to trace back a *descendant* comment to its parent comments, and CommentTree is used to find all arbitrarily deep nested child comments of an *ascendant* comment.

The **StoryVoting\***, **CommentVoting\***, **AnyEntity\*** and **\*VotingTree** views extend the Comment, Story, AnyEntity and Tree views with *vote* and *save* information for each user, using view **Voting**.

The **Save** views are used to query save lists.

## Entities: api/db.php, api/entities/

These are the REST API's database abstraction classes. All with base class **APIEntity**.

The class **APIEntity** merely provides auxiliary functions. The fetch() and fetchAll() functions wrap the $stmt->fetch() and $stmt->fetchAll() respectively with API::nonull and API::cast, which remove null entries in the rows and cast them to their appropriate types, respectively.

Stuff worth noting ahead.

### SQL Injection

**All statements at prepared** except for the two *immediate* queries in *Story::create* and *Comment::create*. Emulation of prepared statements is disabled in *DB::open()*. Please read the next item as well.

### Sorting in Story, Comment and Tree

The 8 sorting methods are implemented directly in SQL through the ORDER BY of a *rating* column. The *rating* column has a different spec (function on upvotes, downvotes and time) for each method; the spec is chosen in method *\*::sort()* **safely through a switch case**, and appended to the query in php.

The sorting methods *best*, *hot* and *controversial* à la Reddit are implemented directly for SQL in *db.php*.

### Image Entity

The Image Entity does not directly handle the upload of images to the server in folder **feup_books/images/upload/**. Instead it merely handles keeping references to these images and their metadata -- setting, editing, and deleting said metadata.

Whenever an image is deleted through *Image::delete()*, the image in **feup_books/images/upload/** will be deleted if the flag IMAGE_DELETE_UPLOAD is set at the top of *image.php*.

### CommentTree Nested Tree Array

The SQL query on view **CommentTree**, used to extract the comment tree on a story (or another comment), returns (obviously) a flat table, ordered first by depth and then by the sorting method desired. The array is converted in PHP into a nested tree array in *Tree::fixTree*, recursively.

### Mixed Save List

Here we refer to SQL views **Save\*** and functions *Save::get\** which have complex SQL queries.

The User's save list in the website is mixed in comments and stories, **and** the saved comments come paired with their parent stories.

To query an ordered list of mixed saves we use queries built on view **AnyEntityAll** and use the *type* column to identify whether the save row refers to a comment or a story (function *Save::userAll()*).

All the comments in this mix are then paired with their story with one query on view **Save\*AllAscendant**: for the same query parameters, it will return the null rows for story saves in the previous query, and parent story for comment save rows in the previous query.

### User

Account creation is in function *User::create()*.
Usernames, emails and passwords are validated for account creation in *User::valid\*()*.
The end of the authentication pipeline is *User::authenticate()*, more on this later.
All public queries performed by this API entity **do not** include the password hash.

## API Auxiliary class: api/api.php class API

Merely an utility class for every other class.

Values queried from the database by the **API entities** and input to **API resources** are all string typed, so this class handles all casts in functions *API::single()* and *API::cast()*.

With time, more auxiliary functions were added.
*API::got()* is used to check if an array of key-value pairs contains the keys specified in its argument -- used later in **API resources** to make action deduction more declarative.
*API::nonull()* removes entries with null values from an array -- used to remove empty column from database fetches.
*API::rekey()* is used to rename keys in an array. Used to allow and map multiple names for the same query parameter.
...

## Authentication: api/api.php class Auth

The API supports two authentication schemes: **sessions** and **basic authorization**. For the website only **sessions** is used. To toggle authentication scheme, change AUTH_MODE at the top of *api.php*.

The authentication pipeline for **API resources** is

    demandLevel() --> level() --> authenticate() --> session()
                                              OR --> authorization() --> autho()

There are four permission levels:

    free
        Anyone can access this resource.
    auth
        Anyone authenticated (logged in or valid Authorization header)
        can access this resource.
    authid  $userid
        Must be authenticated as $userid
    admin
        Must be authenticated as an admin user.

The authentication pipeline is entered with:

    $auth = Auth::demandLevel(PERMISSION_LEVEL[, $userid]);

For the **API resources** this handles the entire authentication stack.
If the permission level is not satisfied, this function will terminate the script. Otherwise it will return to $auth an identification of the logged in user.

*Auth::login()* and *Auth::logout()* are used to login and logout a user respectively (only meaningful for the API if in session mode, but useful for the rest of the website regardless).

## HTTP Request: api/api.php class HTTPRequest

Method *HTTPRequest::body()* fetches data for the Request body. Content types supported:

    application/json
    application/x-www-form-urlencoded ($_POST)
    multipart/form-data ($_POST)
    text/plain (single parameter only)

Method *HTTPRequest::action()* deduces which action a resource should follow based on the descriptor **$actions**.

## HTTP Response: api/api.php class HTTPResponse

All API responses have Content-Type **application/json**.

#### Response codes

    200 OK
        ::ok()
        ::updated()
        ::deleted()
    201 Created
        ::created()
    202 Accepted
        ::accepted()
    300 Multiple Choices
        ::look()
    400 Bad Request
        ::missingQueryParameters()
        ::missingBodyParameters()
        ::invalid()
        ::conflict()
        ::malformedJSON()
        ::badHeader()
        ::noAction()
        ::badRequest()
        ::wrongCredentials()
    401 Unauthorized
        ::unauthorized()
    403 Forbidden
        ::forbidden()
    405 Method Not Allowed
        ::badMethod()
    415 Unsupported Media Type
        ::badContentType()
    500 Server Error
        ::serverError()

#### Output

Check *HTTPResponse::success()* and *HTTPResponse::error()*.

## API Resources: feup_books/api/public/

The resource pipeline is:

    1. Identify resource
        name
        methods supported list
        actions description list
    2. Identify request and client
        authenticate
        validate method
        identify action desired
        parse get parameters
    3. Validate get parameters (action agnostic)
        for each supported query argument that identifies a resource
        (some are just flags), fetch the resource, ensuring it exists.
        perform other actions and expose meaningful globals for the rest
        of the script.
    4. Perform the desired action
        uses the variables exposes in the previous step and concludes the request.
        will:
            require authenticated/admin permission if necessary
            parse the request's body parameters on POST, PUT, PATCH
            validate arguments provided (in query and in body)
            answer the client.

### Action Description

The variable $actions is an array like the following:

    $action = [METHOD, QUERY_GOT_KEYS{, BODY_USED{, OPTIONAL_QUERY_KEYS}}]

The **METHOD** string specifies which HTTP method matches this action.
The **QUERY_GOT_KEYS** array specifies which parameters are expected in the query string (*$_GET*).
The **BODY_USED** array specifies which parameters *might* be inspected in the request body. Informative only.
The **OPTIONAL_QUERY_KEYS** array specifies which extra parameters from the query string will be used to manipulate the result set. Only for **GET** actions.

A **GET** without any arguments is a *look* (resource query) and returns **300** (see HTTPResponse::look()).
