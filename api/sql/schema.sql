DROP TABLE IF EXISTS 'Entity';
DROP TABLE IF EXISTS 'User';
DROP TABLE IF EXISTS 'Save';
DROP TABLE IF EXISTS 'Channel';
DROP TABLE IF EXISTS 'Comment';
DROP TABLE IF EXISTS 'Story';
DROP TABLE IF EXISTS 'Vote';
DROP TABLE IF EXISTS 'Subscribe';

CREATE TABLE User (
    'userid'        INTEGER NOT NULL PRIMARY KEY,
    'username'      TEXT NOT NULL UNIQUE,
    'email'         TEXT NOT NULL UNIQUE,
  --  'createdat'    INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
  --  'updatedat'    INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    'hash'          TEXT NOT NULL
);

CREATE TABLE Entity (
    'entityid'      INTEGER NOT NULL PRIMARY KEY,
    'createdat'     INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    'updatedat'     INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    'upvotes'       INTEGER NOT NULL DEFAULT 0,
    'downvotes'     INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE Channel (
    'channelid'     INTEGER NOT NULL PRIMARY KEY,
    'channelname'   TEXT NOT NULL UNIQUE,
    'creatorid'     INTEGER,
    FOREIGN KEY('creatorid') REFERENCES User('userid') ON DELETE SET NULL
);

CREATE TABLE Story (
    'entityid'      INTEGER NOT NULL,
    'channelid'     INTEGER,
    'authorid'      INTEGER,
    'storyTitle'    TEXT NOT NULL,
    'storyType'     TEXT NOT NULL,
    'content'       TEXT NOT NULL,
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('channelid') REFERENCES Channel('channelid') ON DELETE SET NULL,
    FOREIGN KEY('authorid') REFERENCES User('userid') ON DELETE SET NULL,
    PRIMARY KEY('entityid')
);

CREATE TABLE Comment (
    'entityid'      INTEGER NOT NULL,
    'parentid'      INTEGER NOT NULL,
    'authorid'      INTEGER,
    'content'       TEXT NOT NULL,
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('parentid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('authorid') REFERENCES User('userid') ON DELETE SET NULL,
    PRIMARY KEY('entityid')
);

CREATE TABLE Save (
    'entityid'      INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    'savedat'       INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('entityid','userid')
);

CREATE TABLE Vote (
    'entityid'      INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    'vote'          CHAR NOT NULL DEFAULT '+',
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('entityid','userid')
);

CREATE TABLE Subscribe (
    'channelid'     INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    FOREIGN KEY('channelid') REFERENCES Channel('channelid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('channelid','userid')
);
