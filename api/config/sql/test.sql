/**
DROP TABLE IF EXISTS 'foo';
DROP TABLE IF EXISTS 'bar';

CREATE TABLE foo (
    'id'            INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    'type'          VARCHAR(60) NOT NULL UNIQUE,
    'time'          TIMESTAMP
);

CREATE TABLE bar (
    'id'            INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    'description'   VARCHAR(40) NOT NULL,
    'foo_id'        BIGINT NOT NULL,
    FOREIGN KEY('foo_id') REFERENCES foo('id') ON DELETE CASCADE
);

DROP TRIGGER IF EXISTS 'ok';

CREATE TRIGGER ok
AFTER INSERT ON foo
FOR EACH ROW
WHEN New.time IS NULL
BEGIN
    UPDATE foo SET time = CURRENT_TIMESTAMP WHERE rowid = NEW.rowid;
END;

INSERT INTO foo(type) VALUES
    ('red'),
    ('green'),
    ('blue');

INSERT INTO bar(description, foo_id) VALUES
    ('a','1'),
    ('b','1'),
    ('c','2'),
    ('d','3'),
    ('e','2'),
    ('f','3');
 */

PRAGMA foreign_keys = OFF;

BEGIN TRANSACTION;

DROP TABLE IF EXISTS 'dad';
DROP TABLE IF EXISTS 'mom';
DROP TABLE IF EXISTS 'child';

CREATE TABLE dad (
    'id'        INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT
);

CREATE TABLE mom (
    'id'        INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT
);

CREATE TABLE child (
    'dad_id'        INTEGER,
    'mom_id'        INTEGER,
    'content'       TEXT NOT NULL,
    FOREIGN KEY('dad_id') REFERENCES dad('id'),
    FOREIGN KEY('mom_id') REFERENCES mom('id'),
    PRIMARY KEY('dad_id','mom_id')
);

CREATE TRIGGER new_dad
AFTER INSERT ON child
FOR EACH ROW
WHEN NEW.dad_id IS NULL
BEGIN
    INSERT INTO dad(id) VALUES (NULL);
    UPDATE child
    SET dad_id = (SELECT max(id) FROM dad)
    WHERE rowid = NEW.rowid;
END;

CREATE TRIGGER new_mom
AFTER INSERT ON child
FOR EACH ROW
WHEN NEW.mom_id IS NULL
BEGIN
    INSERT INTO mom(id) VALUES (NULL);
    UPDATE child
    SET mom_id = (SELECT max(id) FROM mom)
    WHERE rowid = NEW.rowid;
END;

COMMIT;

PRAGMA foreign_keys = ON;

INSERT INTO dad DEFAULT VALUES;
INSERT INTO dad DEFAULT VALUES;
INSERT INTO dad DEFAULT VALUES;
INSERT INTO dad DEFAULT VALUES;

INSERT INTO mom DEFAULT VALUES;
INSERT INTO mom DEFAULT VALUES;
