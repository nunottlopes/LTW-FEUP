DROP TABLE IF EXISTS 'entity';
DROP TABLE IF EXISTS 'user';
DROP TABLE IF EXISTS 'save';
DROP TABLE IF EXISTS 'channel';
DROP TABLE IF EXISTS 'comment';
DROP TABLE IF EXISTS 'story';
DROP TABLE IF EXISTS 'vote';
DROP TABLE IF EXISTS 'subscribe';

CREATE TABLE entity (
    'entity_id'     INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    'created_at'    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    'updated_at'    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    'upvotes'       INTEGER NOT NULL DEFAULT 0,
    'downvotes'     INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE user (
    'user_id'       INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    'username'      TEXT NOT NULL UNIQUE,
    'email'         TEXT NOT NULL UNIQUE,
    'hash'          TEXT NOT NULL,
    'created_at'    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    'updated_at'    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE channel (
    'channel_id'    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    'name'          TEXT NOT NULL UNIQUE,
    'creator_id'    INTEGER,
    FOREIGN KEY('creator_id') REFERENCES user('user_id') ON DELETE SET NULL
);

CREATE TABLE story (
    'entity_id'     INTEGER NOT NULL,
    'channel_id'    INTEGER NOT NULL,
    'user_id'       INTEGER,
    'title'         TEXT NOT NULL,
    'type'          TEXT NOT NULL,
    'content'       INTEGER NOT NULL,
    FOREIGN KEY('entity_id') REFERENCES entity('entity_id') ON DELETE CASCADE,
    FOREIGN KEY('channel_id') REFERENCES channel('channel_id') ON DELETE CASCADE,
    FOREIGN KEY('user_id') REFERENCES user('user_id') ON DELETE SET NULL,
    PRIMARY KEY('entity_id')
);

CREATE TABLE comment (
    'entity_id'     INTEGER NOT NULL,
    'parent_id'     INTEGER NOT NULL,
    'user_id'       INTEGER,
    'content'       TEXT NOT NULL,
    FOREIGN KEY('entity_id') REFERENCES entity('entity_id') ON DELETE CASCADE,
    FOREIGN KEY('parent_id') REFERENCES entity('entity_id') ON DELETE CASCADE,
    FOREIGN KEY('user_id') REFERENCES user('user_id') ON DELETE SET NULL,
    PRIMARY KEY('entity_id')
);

CREATE TABLE save (
    'entity_id'     INTEGER NOT NULL,
    'user_id'       INTEGER NOT NULL,
    'created_at'    INTEGER NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY('entity_id') REFERENCES entity('entity_id') ON DELETE CASCADE,
    FOREIGN KEY('user_id') REFERENCES user('user_id') ON DELETE CASCADE,
    PRIMARY KEY('entity_id','user_id')
);

CREATE TABLE vote (
    'entity_id'     INTEGER NOT NULL,
    'user_id'       INTEGER NOT NULL,
    'kind'          CHAR NOT NULL DEFAULT '+',
    FOREIGN KEY('entity_id') REFERENCES entity('entity_id') ON DELETE CASCADE,
    FOREIGN KEY('user_id') REFERENCES user('user_id') ON DELETE CASCADE,
    PRIMARY KEY('entity_id','user_id')
);

CREATE TABLE subscribe (
    'channel_id'    INTEGER NOT NULL,
    'user_id'       INTEGER NOT NULL,
    'created_at'    INTEGER NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY('channel_id') REFERENCES channel('channel_id') ON DELETE CASCADE,
    FOREIGN KEY('user_id') REFERENCES user('user_id') ON DELETE CASCADE,
    PRIMARY KEY('channel_id','user_id')
);
