/**
 * 9 Users [1-9] Admins 1 and 9
 * 4 Channels [1-4]
 * 27 Stories [1-27] all four channels
 * 73 Comments [28-100] 6 levels
 * 11 Saves [Stories 1-4]
 */
INSERT INTO Image(imageid, imagefile, width, height, filesize, format) VALUES
    (1,  'img1.jpeg', 1920, 1080, 449815, 'jpeg'),
    (2,  'img2.jpeg', 1920, 1080,1284828, 'jpeg'),
    (3,  'img3.jpeg', 1920, 1080, 103230, 'jpeg'),
    (4,  'img4.jpeg', 2048, 1543, 679971, 'jpeg'),
    (5,  'img5.jpeg', 1920, 1080, 305224, 'jpeg'),
    (6,  'img6.jpeg', 1920, 1080, 227905, 'jpeg'),
    (7,  'img7.jpeg', 1920, 1080, 675886, 'jpeg'),
    (8,  'img8.jpeg', 1920, 1080, 265404, 'jpeg'),
    (9,  'img9.jpeg', 1920, 1200,2059155, 'jpeg'),
    (10,'img10.jpeg', 1920, 1080, 721004, 'jpeg');

/**
 * USER
 * ID USERNAMES  PASSWORDS
 * 1  admin      admin       ADMIN
 * 2  Emanuel    123456
 * 3  David      qwerty
 * 4  Tiago      password
 * 5  Sofia      12121212
 * 6  Bruno      bruno
 * 7  Amadeu     amadeu
 * 8  Nuno       nuno
 * 9  Jaime                  ADMIN
 */
INSERT INTO User(userid, username, email, hash, admin, imageid) VALUES
    (1, 'admin', 'admin@feupnews.com', '$2y$10$p2It7atX5xmjgOCj1ueLLOO9ImNkg5jC/O84yu9yU/578RekCoY62', 1, 1),
    (2, 'Emanuel', 'emanuel@gmail.com', '$2y$10$xCpKMa8XygdBr3VOxsIOhOyl9HLzw8WgmxCdAs4rhEsjcQsMW87hO', 0, 2),
    (3, 'David', 'david.andrade@gmail.com', '$2y$10$xesrOHbPqklXV1I7FNfERuA37Indy1PJBIrxqqZ7tY7/qIJOxb5Ge', 0, NULL),
    (4, 'Tiago', 'tiago@live.com.pt', '$2y$10$raS40nxOFgUViuNF61HRMOn6bDrJIobJM7TXsWyp0RXNuTtdEOTS.', 0, 3),
    (5, 'Sofia', 'sofia@hotmail.com', '$2y$10$ysl//9wSz70ld77ke0l2lOUd9H3lxzOH.ogY6gezjGl9y1xCK4pxO', 0, NULL),
    (6, 'Bruno', 'bruno@gmail.com', '$2y$10$iDV2CXM5NbVVGphWIqQ73.Shl.xBrtO.QS2laFNPy7ojSMBKFfeUa', 0, NULL),
    (7, 'Amadeu', 'amadeu@gmail.com', '$2y$10$CAEOQq547goKiJN3KzwSjus1kbdcchrGtSSW2g5v.zkll88Xbquse', 0, NULL),
    (8, 'Nuno', 'nuno.lopes@gmail.com', '$2y$10$gPB0nQ76r4AzqKupnVGf3edvlgHBqB2EkAQQ5SrQ23yDl9D5CnaA6', 0, 4),
    (9, 'Jaime', 'jaime.lopes@hotmail.com', '$2y$10$kPrXjU1oOA7TTD2fxjTiRecK1H/BlV.6vBm4RuB.bD7GGwCSaMv5S', 1, NULL);

/**
 * CHANNEL
 * ID NAME            CREATOR
 * 1  showerthoughts  Amadeu
 * 2  philosophy      Sofia
 * 3  jokes           Nuno
 * 4  askscience      Emanuel
 */
INSERT INTO Channel(channelid, channelname, creatorid, imageid) VALUES
    (1, 'showerthoughts', 6, 5),
    (2, 'philosophy', 4, 6),
    (3, 'jokes', 7, 7),
    (4, 'askscience', 1, 8);

/**
 * STORY
 */
INSERT INTO Story(channelid, authorid, storyTitle, storyType, content, imageid) VALUES
    (1, 6,  'Story#1','title', '', NULL), -- 6 levels with votes
    (1, 3,  'Story#2','title', '', NULL),
    (1, 2,  'Story#3', 'text', 'Content #3', NULL),

    (2, 1,  'Story#4', 'text', 'Content #4', NULL),
    (2, 6,  'Story#5','image', 'Content #5', 9), -- 3 levels with votes
    (2, 1,  'Story#6', 'text', 'Content #6', NULL),
    (2, 9,  'Story#7','image', 'Content #7', 10), -- 2 levels
    (2, 1,  'Story#8', 'text', 'Content #8', NULL),

    (3, 2,  'Story#9', 'text', 'Content #9', NULL),
    (3, 8, 'Story#10', 'text', 'Content #10', NULL),
    (3, 3, 'Story#11', 'text', 'Content #11', NULL),
    (3, 7, 'Story#12', 'text', 'Content #12', NULL),
    (3, 7, 'Story#13', 'text', 'Content #13', NULL),
    (3, 2, 'Story#14', 'text', 'Content #14', NULL),
    (3, 2, 'Story#15', 'text', 'Content #15', NULL),
   
    (4, 1, 'Story#16', 'text', 'Content #16', NULL),
    (4, 2, 'Story#17', 'text', 'Content #17', NULL),
    (4, 3, 'Story#18', 'text', 'Content #18', NULL),
    (4, 6, 'Story#19', 'text', 'Content #19', NULL),
    (4, 7, 'Story#20', 'text', 'Content #20', NULL),
    (4, 9, 'Story#21', 'text', 'Content #21', NULL),
    (4, 2, 'Story#22', 'text', 'Content #22', NULL),
    (4, 3, 'Story#23', 'text', 'Content #23', NULL),
    (4, 1, 'Story#24', 'text', 'Content #24', NULL),
    (4, 9, 'Story#25', 'text', 'Content #25', NULL),
    (4, 8, 'Story#26', 'text', 'Content #26', NULL),
    (4, 9, 'Story#27', 'text', 'Content #27', NULL);

/**
 * COMMENT
 */
-- Level 1
INSERT INTO Comment(parentid, authorid, content) VALUES
    -- Story 1
    (1, 4, 'Comment#28-#1-1'), --
    (1, 5, 'Comment#29-#1-1'), --
    (1, 3, 'Comment#30-#1-1'), --
    -- Story 5
    (5, 1, 'Comment#31-#5-1'), --
    (5, 4, 'Comment#32-#5-1'),
    (5, 7, 'Comment#33-#5-1'), --
    (5, 3, 'Comment#34-#5-1'), --
    -- Story 7
    (7, 1, 'Comment#35-#7-1'), --
    (7, 1, 'Comment#36-#7-1'), --
    (7, 3, 'Comment#37-#7-1'), --
    (7, 4, 'Comment#38-#7-1'),
    (7, 4, 'Comment#39-#7-1'),
    (7, 1, 'Comment#40-#7-1');

-- Level 2
INSERT INTO Comment(parentid, authorid, content) VALUES
    -- Story 1
    (28, 5, 'Comment#41-#1-2'), --
    (28, 7, 'Comment#42-#1-2'), --
    (28, 8, 'Comment#43-#1-2'), --
    (28, 5, 'Comment#44-#1-2'),
    (29, 2, 'Comment#45-#1-2'),
    (29, 4, 'Comment#46-#1-2'), --
    (29, 3, 'Comment#47-#1-2'),
    (30, 2, 'Comment#48-#1-2'),
    (30, 1, 'Comment#49-#1-2'),
    (30, 6, 'Comment#50-#1-2'), --
    (30, 5, 'Comment#51-#1-2'), --
    -- Story 2
    (31, 7, 'Comment#52-#5-2'),
    (31, 4, 'Comment#53-#5-2'),
    (31, 5, 'Comment#54-#5-2'), --
    (33, 7, 'Comment#55-#5-2'), --
    (33, 3, 'Comment#56-#5-2'),
    (33, 2, 'Comment#57-#5-2'),
    (34, 1, 'Comment#58-#5-2'), --
    (34, 2, 'Comment#59-#5-2'), --
    -- Story 3
    (35, 2, 'Comment#60-#7-2'),
    (35, 1, 'Comment#61-#7-2'),
    (36, 6, 'Comment#62-#7-2'),
    (36, 7, 'Comment#63-#7-2'),
    (38, 3, 'Comment#64-#7-2'),
    (38, 2, 'Comment#65-#7-2');

-- Level 3
INSERT INTO Comment(parentid, authorid, content) VALUES
    -- Story 1
    (41, 2, 'Comment#66-#1-3'), --
    (41, 7, 'Comment#67-#1-3'), --
    (42, 8, 'Comment#68-#1-3'),
    (43, 9, 'Comment#69-#1-3'),
    (43, 2, 'Comment#70-#1-3'), --
    (43, 4, 'Comment#71-#1-3'),
    (46, 3, 'Comment#72-#1-3'),
    (50, 2, 'Comment#73-#1-3'), --
    (51, 1, 'Comment#74-#1-3'),
    -- Story 2
    (54, 4, 'Comment#75-#5-3'),
    (55, 1, 'Comment#76-#5-3'),
    (55, 4, 'Comment#77-#5-3'),
    (58, 3, 'Comment#78-#5-3'),
    (59, 6, 'Comment#79-#5-3'),
    (59, 5, 'Comment#80-#5-3');

-- Level 4
INSERT INTO Comment(parentid, authorid, content) VALUES
    -- Story 1
    (66, 3, 'Comment#81-#1-4'), --
    (66, 4, 'Comment#82-#1-4'), --
    (66, 5, 'Comment#83-#1-4'),
    (67, 9, 'Comment#84-#1-4'),
    (67, 8, 'Comment#85-#1-4'),
    (70, 4, 'Comment#86-#1-4'),
    (73, 1, 'Comment#87-#1-4'), --
    (73, 4, 'Comment#88-#1-4'), --
    (73, 6, 'Comment#89-#1-4'); --

-- Level 5
INSERT INTO Comment(parentid, authorid, content) VALUES
    -- Story 1
    (81, 8, 'Comment#90-#1-5'),
    (81, 5, 'Comment#91-#1-5'), --
    (82, 5, 'Comment#92-#1-5'),
    (82, 3, 'Comment#93-#1-5'),
    (82, 3, 'Comment#94-#1-5'),
    (87, 2, 'Comment#95-#1-5'),
    (87, 3, 'Comment#96-#1-5'),
    (88, 5, 'Comment#97-#1-5'),
    (89, 7, 'Comment#98-#1-5');

-- Level 6
INSERT INTO Comment(parentid, authorid, content) VALUES
    -- Story 1
    (91, 6, 'Comment#99-#1-6'),
    (91, 3, 'Comment#100-#1-6');

/**
 * SAVE
 */
INSERT INTO Save(entityid, userid) VALUES
    (1, 3), (1, 6), (1, 7),
    (3, 2), (3, 7),
    (4, 1), (4, 2),
    (5, 2), (5, 7), (5, 9),
    (7, 1), (7, 2), (7, 3),
    (14, 1), (15, 2), (15, 7), (17, 5), (21, 2),

    (66, 1), (66, 2), (66, 3), (66, 6), (66, 7),
    (67, 3), (67, 7), (67, 8),
    (81, 2), (81, 4), (81, 9),
    (82, 2),
    (83, 4), (83, 5), (83, 7),
    (84, 1), (84, 2), (84, 8),
    (85, 1), (85, 3), (85, 6);

/**
 * VOTE
 */
INSERT INTO Vote(entityid, userid, vote) VALUES
    (41, 5, '+'), (56, 3, '+'), (76, 9, '+'), (73, 5, '+'),
    (62, 3, '+'), (75, 4, '+'), (43, 8, '+'), (34, 3, '+'),
    (73, 2, '+'), (34, 5, '+'), (34, 4, '+'), (83, 2, '+'),
    (36, 7, '+'), (35, 9, '+'), (27, 2, '+'), (81, 5, '+'),
    (64, 8, '+'), (78, 2, '+'), (28, 3, '+'), (61, 1, '+'),
    (65, 4, '+'), (79, 1, '+'), (23, 1, '+'), (54, 5, '+'),
    (98, 3, '+'), (80, 3, '+'), ( 5, 6, '+'), (94, 2, '+'),
    (67, 2, '+'), (81, 6, '+'), ( 7, 7, '+'), (99, 5, '+'),
    (32, 1, '+'), (85, 7, '+'), (10, 4, '+'), (32, 5, '+'),
    (14, 6, '+'), (45, 8, '+'), (14, 3, '+'), (45, 3, '+'),
    (94, 9, '+'), (43, 9, '+'), ( 7, 2, '+'), (46, 4, '+'),
    (52, 8, '+'), (42, 4, '+'), (18, 1, '+'), (21, 6, '+'),
    (41, 5, '+'), (49, 5, '+'), (43, 5, '+'), (75, 5, '+'),

    (53, 2, '+'), (77, 5, '+'), (30, 4, '+'), ( 8, 2, '+'),
    (38, 7, '+'), (66, 9, '+'), (40, 2, '+'), ( 7, 5, '+'),
    (66, 8, '+'), (88, 2, '+'), (42, 3, '+'), ( 9, 1, '+'),
    (55, 4, '+'), (44, 1, '+'), (36, 1, '+'), ( 1, 5, '+'),
    (99, 3, '+'), (62, 3, '+'), (37, 6, '+'), ( 3, 2, '+'),
    (61, 2, '+'), (77, 6, '+'), (89, 7, '+'), ( 7, 5, '+'),
    (34, 1, '+'), (82, 7, '+'), (25, 4, '+'), ( 6, 5, '+'),
    (19, 6, '+'), (64, 8, '+'), (35, 3, '+'), ( 1, 3, '+'),
    (92, 9, '+'), (35, 9, '+'), (15, 2, '+'), ( 8, 4, '+'),
    (57, 8, '+'), (85, 4, '+'), (18, 1, '+'), ( 3, 6, '+'),
    (42, 5, '+'), (90, 5, '+'), ( 4, 5, '+'), ( 2, 5, '+'),

    (37, 1, '-'), (76, 7, '-'), (67, 7, '-'), ( 6, 2, '-'),
    (38, 2, '-'), (43, 4, '-'), (45, 4, '-'), ( 2, 4, '-'),
    (45, 4, '-'), (15, 5, '-'), (63, 5, '-'), ( 3, 6, '-'),
    (15, 1, '-'), (14, 4, '-'), (18, 2, '-'), ( 3, 1, '-'),
    (87, 6, '-'), (32, 9, '-'), (17, 1, '-'), ( 2, 2, '-'),
    (65, 5, '-'), (74, 8, '-'), (25, 8, '-'), ( 4, 9, '-'),
    (40, 1, '-'), (56, 7, '-'), (16, 6, '-'), ( 4, 6, '-'),
    (56, 8, '-'), (59, 1, '-'), (34, 9, '-'), ( 3, 3, '-'),
    (89, 9, '-'), (12, 2, '-'), (80, 4, '-'), ( 4, 7, '-'),
    (57, 1, '-'), (63, 3, '-'), (76, 3, '-'), ( 2, 5, '-');
