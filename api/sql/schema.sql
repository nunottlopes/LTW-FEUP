DROP TABLE IF EXISTS 'Entity';
DROP TABLE IF EXISTS 'User';
DROP TABLE IF EXISTS 'Save';
DROP TABLE IF EXISTS 'Channel';
DROP TABLE IF EXISTS 'Comment';
DROP TABLE IF EXISTS 'Tree';
DROP TABLE IF EXISTS 'Story';
DROP TABLE IF EXISTS 'Vote';
DROP TABLE IF EXISTS 'Subscribe';

CREATE TABLE User (
    'userid'        INTEGER NOT NULL PRIMARY KEY,
    'username'      TEXT NOT NULL UNIQUE,
    'email'         TEXT NOT NULL UNIQUE,
  --  'createdat'    INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
  --  'updatedat'    INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    'hash'          TEXT NOT NULL,
    'admin'         INTEGER NOT NULL DEFAULT 1,
    CONSTRAINT BooleanAdmin CHECK (admin IN (0,1))
);

CREATE TABLE Entity (
    'entityid'      INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    'createdat'     INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    'updatedat'     INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    'upvotes'       INTEGER NOT NULL DEFAULT 0,
    'downvotes'     INTEGER NOT NULL DEFAULT 0,
    CONSTRAINT PositiveUpvotes CHECK (upvotes >= 0),
    CONSTRAINT PositiveDownvotes CHECK (downvotes >= 0),
    CONSTRAINT UpdateTime CHECK (updatedat >= createdat)
);

CREATE TABLE Channel (
    'channelid'     INTEGER NOT NULL PRIMARY KEY,
    'channelname'   TEXT NOT NULL UNIQUE,
    'creatorid'     INTEGER,
    FOREIGN KEY('creatorid') REFERENCES User('userid') ON DELETE SET NULL
);

CREATE TABLE Story (
    'entityid'      INTEGER NOT NULL PRIMARY KEY,
    'authorid'      INTEGER,
    'channelid'     INTEGER NOT NULL,
    'storyTitle'    TEXT NOT NULL,
    'storyType'     TEXT NOT NULL,
    'content'       TEXT NOT NULL,
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('authorid') REFERENCES User('userid') ON DELETE SET NULL,
    FOREIGN KEY('channelid') REFERENCES Channel('channelid') ON DELETE CASCADE
);

CREATE TABLE Comment (
    'entityid'      INTEGER NOT NULL PRIMARY KEY,
    'authorid'      INTEGER,
    'parentid'      INTEGER NOT NULL,
    'content'       TEXT NOT NULL,
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('authorid') REFERENCES User('userid') ON DELETE SET NULL,
    FOREIGN KEY('parentid') REFERENCES Entity('entityid') ON DELETE CASCADE
);

CREATE TABLE Tree ( -- ClosureTable
    'ascendantid'   INTEGER NOT NULL,
    'descendantid'  INTEGER NOT NULL,
    'depth'         INTEGER NOT NULL,
    FOREIGN KEY('ascendantid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('descendantid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    PRIMARY KEY('ascendantid', 'descendantid'),
    CONSTRAINT PositiveDepth CHECK (depth > 0),
    CONSTRAINT OneParent UNIQUE('descendantid', 'depth') -- Should create an implicit index
);

CREATE TABLE Save (
    'entityid'      INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    'savedat'       INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('entityid','userid') ON CONFLICT IGNORE
);

CREATE TABLE Vote (
    'entityid'      INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    'vote'          CHAR NOT NULL DEFAULT '+',
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('entityid','userid'),
    CONSTRAINT UpDown CHECK (vote IN ('+','-'))
);

CREATE TABLE Subscribe (
    'channelid'     INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    FOREIGN KEY('channelid') REFERENCES Channel('channelid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('channelid','userid')
);
