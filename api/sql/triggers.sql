DROP TRIGGER IF EXISTS InsertEntity;
DROP TRIGGER IF EXISTS MapInsertStoryEntity;
DROP TRIGGER IF EXISTS MapInsertCommentEntity;
DROP TRIGGER IF EXISTS InsertComment;
DROP TRIGGER IF EXISTS InsertTree;
DROP TRIGGER IF EXISTS UpdateStory;
DROP TRIGGER IF EXISTS UpdateComment;
DROP TRIGGER IF EXISTS MapUpdateStoryEntity;
DROP TRIGGER IF EXISTS MapUpdateCommentEntity;
DROP TRIGGER IF EXISTS DeleteStory;
DROP TRIGGER IF EXISTS DeleteComment;
DROP TRIGGER IF EXISTS MapDeleteStoryDelete;
DROP TRIGGER IF EXISTS InsertVoteBefore;
DROP TRIGGER IF EXISTS InsertVoteAfter;
DROP TRIGGER IF EXISTS UpdateVote;
DROP TRIGGER IF EXISTS DeleteVote;

/**
 * Upvote own Entity
 */
CREATE TRIGGER InsertEntity
AFTER INSERT ON Entity
FOR EACH ROW
BEGIN
    INSERT INTO Vote(entityid, userid, vote)
    VALUES (NEW.entityid, NEW.authorid, '+');
END;

/**
 * Map inserts in view StoryEntity
 */
CREATE TRIGGER MapInsertStoryEntity
INSTEAD OF INSERT ON StoryEntity
FOR EACH ROW
BEGIN
    INSERT INTO Entity(authorid) VALUES (NEW.authorid);

    INSERT INTO Story(entityid, channelid, storyTitle, storyType, content)
    VALUES (last_insert_rowid(), NEW.channelid, NEW.storyTitle, NEW.storyType, NEW.content);
END;

/**
 * Map inserts in view CommentEntity
 */
CREATE TRIGGER MapInsertCommentEntity
INSTEAD OF INSERT ON CommentEntity
FOR EACH ROW
BEGIN
    INSERT INTO Entity(authorid) VALUES (NEW.authorid);

    INSERT INTO Comment(entityid, parentid, content)
    VALUES (last_insert_rowid(), NEW.parentid, NEW.content);
END;

/**
 * Insert into Tree on Comment insert
 */
CREATE TRIGGER InsertComment
AFTER INSERT ON Comment
FOR EACH ROW
BEGIN
    INSERT INTO Tree(ascendantid, descendantid, depth)
    VALUES (NEW.parentid, NEW.entityid, 1);
END;

CREATE TRIGGER InsertTree
AFTER INSERT ON Tree
FOR EACH ROW
WHEN NEW.depth = 1
BEGIN
    INSERT INTO Tree(ascendantid, descendantid, depth)
    SELECT Tree.ascendantid, NEW.descendantid, depth + 1
    FROM Tree
    WHERE Tree.descendantid = NEW.ascendantid;
END;

/**
 * Update the timestamp on the entity table whenever the story table is updated
 */
CREATE TRIGGER UpdateStory
AFTER UPDATE ON Story
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET updatedat = strftime('%s', 'now')
    WHERE entityid = NEW.entityid;
END;

/**
 * Update the timestamp on the entity table whenever the comment table is updated
 */
CREATE TRIGGER UpdateComment
AFTER UPDATE ON Comment
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET updatedat = strftime('%s', 'now')
    WHERE entityid = NEW.entityid;
END;

/**
 * Map update on StoryEntity to Story
 */
CREATE TRIGGER MapUpdateStoryEntity
INSTEAD OF UPDATE ON StoryEntity
FOR EACH ROW
BEGIN
    UPDATE Story
    SET content = NEW.content
    WHERE entityid = NEW.entityid;
END;

/**
 * Map update on CommentEntity to Comment
 */
CREATE TRIGGER MapUpdateCommentEntity
INSTEAD OF UPDATE ON CommentEntity
FOR EACH ROW
BEGIN
    UPDATE Comment
    SET content = NEW.content
    WHERE entityid = NEW.entityid;
END;

/**
 * Delete the entity row corresponding to a deleted story
 */
CREATE TRIGGER DeleteStory
AFTER DELETE ON Story
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE entityid = OLD.entityid;
END;

/**
 * Delete the entity row corresponding to a deleted comment
 */
CREATE TRIGGER DeleteComment
AFTER DELETE ON Comment
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE entityid = OLD.entityid;
END;

/**
 * Map delete on StoryEntity to Story
 */
CREATE TRIGGER MapDeleteStoryEntity
INSTEAD OF DELETE ON StoryEntity
FOR EACH ROW
BEGIN
    DELETE FROM Story
    WHERE entityid = OLD.entityid;
END;

/**
 * Map delete on CommentEntity to Comment
 */
CREATE TRIGGER MapDeleteCommentEntity
INSTEAD OF DELETE ON CommentEntity
FOR EACH ROW
BEGIN
    DELETE FROM Comment
    WHERE entityid = OLD.entityid;
END;

/**
 * Check if a vote already existed prior to an insert, delete it in that case
 * Essentially ON CONFLICT REPLACE but calls the DeleteVote trigger.
 */
CREATE TRIGGER InsertVoteBefore
BEFORE INSERT ON Vote
FOR EACH ROW
BEGIN
    DELETE FROM Vote
    WHERE entityid = NEW.entityid AND userid = NEW.userid;
END;

/**
 * Update the vote count on the entity table after a NEW vote
 */
CREATE TRIGGER InsertVoteAfter
AFTER INSERT ON Vote
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET upvotes = CASE
            WHEN NEW.vote = '+' THEN upvotes + 1 ELSE upvotes
        END,
        downvotes = CASE
            WHEN NEW.vote = '-' THEN downvotes + 1 ELSE downvotes
        END
    WHERE entityid = NEW.entityid;
END;

/**
 * Update the vote count on the entity table on a vote update
 */
CREATE TRIGGER UpdateVote
AFTER UPDATE ON Vote
FOR EACH ROW
WHEN NEW.vote != OLD.vote
BEGIN
    UPDATE Entity
    SET upvotes = CASE
            WHEN NEW.vote = '+' AND OLD.vote = '-' THEN upvotes + 1
            WHEN NEW.vote = '-' AND OLD.vote = '+' THEN upvotes - 1
        END,
        downvotes = CASE
            WHEN NEW.vote = '+' AND OLD.vote = '-' THEN downvotes - 1
            WHEN NEW.vote = '-' AND OLD.vote = '+' THEN downvotes + 1
        END
    WHERE entityid = NEW.entityid;
END;

/**
 * Update the vote count on the entity table on a vote deletion
 */
CREATE TRIGGER DeleteVote
AFTER DELETE ON Vote
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET upvotes = CASE
            WHEN OLD.vote = '+' THEN upvotes - 1 ELSE upvotes
        END,
        downvotes = CASE
            WHEN OLD.vote = '-' THEN downvotes - 1 ELSE downvotes
        END
    WHERE entityid = OLD.entityid;
END;
