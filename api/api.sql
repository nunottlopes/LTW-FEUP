PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE Image (
    'imageid'       INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    'imagefile'     TEXT DEFAULT NULL UNIQUE,
    'width'         INTEGER DEFAULT NULL,
    'height'        INTEGER DEFAULT NULL,
    'filesize'      INTEGER DEFAULT NULL,
    'format'        TEXT DEFAULT NULL,
    CONSTRAINT GoodWidth CHECK
    ((imagefile IS NULL AND width IS NULL) OR (imagefile IS NOT NULL AND width > 0)),
    CONSTRAINT GoodHeight CHECK
    ((imagefile IS NULL AND height IS NULL) OR (imagefile IS NOT NULL AND height > 0)),
    CONSTRAINT GoodFilesize CHECK
    ((imagefile IS NULL AND filesize IS NULL) OR (imagefile IS NOT NULL AND filesize > 0)),
    CONSTRAINT GoodFormat CHECK
    ((imagefile IS NULL AND format IS NULL) OR (imagefile IS NOT NULL AND format IS NOT NULL)),
    CONSTRAINT SupportedImages CHECK (format IN ('gif','jpeg','png'))
);
INSERT INTO Image VALUES(1,'img1.jpeg',1920,1080,449815,'jpeg');
INSERT INTO Image VALUES(2,'img2.jpeg',1920,1080,1284828,'jpeg');
INSERT INTO Image VALUES(3,'img3.jpeg',1920,1080,103230,'jpeg');
INSERT INTO Image VALUES(4,'img4.jpeg',2048,1543,679971,'jpeg');
INSERT INTO Image VALUES(5,'img5.jpeg',1920,1080,305224,'jpeg');
INSERT INTO Image VALUES(6,'img6.jpeg',1920,1080,227905,'jpeg');
INSERT INTO Image VALUES(7,'img7.jpeg',1920,1080,675886,'jpeg');
INSERT INTO Image VALUES(8,'img8.jpeg',1920,1080,265404,'jpeg');
INSERT INTO Image VALUES(9,'img9.jpeg',1920,1200,2059155,'jpeg');
INSERT INTO Image VALUES(10,'img10.jpeg',1920,1080,721004,'jpeg');
INSERT INTO Image VALUES(11,'img11.jpeg',5184,3456,3885756,'jpeg');
INSERT INTO Image VALUES(12,'img12.jpeg',720,1280,88967,'jpeg');
INSERT INTO Image VALUES(13,'img13.png',367,420,148501,'png');
INSERT INTO Image VALUES(14,'img14.jpeg',768,1262,52478,'jpeg');
INSERT INTO Image VALUES(15,'img15.jpeg',1079,1084,210612,'jpeg');
INSERT INTO Image VALUES(16,'img16.jpeg',1280,720,33567,'jpeg');
INSERT INTO Image VALUES(17,'img17.jpeg',1080,1388,62129,'jpeg');
INSERT INTO Image VALUES(18,'img18.jpeg',488,521,24129,'jpeg');
INSERT INTO Image VALUES(19,'img19.jpeg',700,467,36957,'jpeg');
INSERT INTO Image VALUES(20,'img20.png',560,390,314559,'png');
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
INSERT INTO Entity VALUES(1,1545094506,1545094506,5,0);
INSERT INTO Entity VALUES(2,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(3,1545094506,1545094506,2,1);
INSERT INTO Entity VALUES(4,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(5,1545094506,1545094506,7,0);
INSERT INTO Entity VALUES(6,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(7,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(8,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(9,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(10,1545094506,1545094506,4,0);
INSERT INTO Entity VALUES(11,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(12,1545094506,1545094506,3,1);
INSERT INTO Entity VALUES(13,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(14,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(15,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(16,1545094506,1545094506,3,0);
INSERT INTO Entity VALUES(17,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(18,1545094506,1545094506,3,0);
INSERT INTO Entity VALUES(19,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(20,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(21,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(22,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(23,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(24,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(25,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(26,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(27,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(28,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(29,1545094506,1545094506,6,1);
INSERT INTO Entity VALUES(30,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(31,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(32,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(33,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(34,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(35,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(36,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(37,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(38,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(39,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(40,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(41,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(42,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(43,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(44,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(45,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(46,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(47,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(48,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(49,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(50,1545094506,1545094506,2,1);
INSERT INTO Entity VALUES(51,1545094506,1545094506,3,1);
INSERT INTO Entity VALUES(52,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(53,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(54,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(55,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(56,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(57,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(58,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(59,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(60,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(61,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(62,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(63,1545094506,1545094506,1,1);
INSERT INTO Entity VALUES(64,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(65,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(66,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(67,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(68,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(69,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(70,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(71,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(72,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(73,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(74,1545094506,1545094506,1,0);
INSERT INTO Entity VALUES(75,1545094506,1545094506,2,0);
INSERT INTO Entity VALUES(76,1545095564,1545095564,1,0);
INSERT INTO Entity VALUES(77,1545095641,1545095641,1,0);
INSERT INTO Entity VALUES(78,1545096205,1545096205,2,0);
INSERT INTO Entity VALUES(79,1545096325,1545096325,3,0);
INSERT INTO Entity VALUES(80,1545096341,1545096341,1,0);
INSERT INTO Entity VALUES(81,1545096347,1545096347,1,0);
INSERT INTO Entity VALUES(82,1545096369,1545096369,1,0);
INSERT INTO Entity VALUES(83,1545096428,1545096428,1,0);
INSERT INTO Entity VALUES(84,1545096517,1545096517,1,0);
INSERT INTO Entity VALUES(85,1545096543,1545096543,1,1);
INSERT INTO Entity VALUES(86,1545096568,1545096568,1,0);
INSERT INTO Entity VALUES(87,1545096591,1545096591,1,0);
INSERT INTO Entity VALUES(88,1545096622,1545096622,1,0);
INSERT INTO Entity VALUES(89,1545097311,1545097311,1,0);
INSERT INTO Entity VALUES(90,1545097351,1545097351,1,0);
INSERT INTO Entity VALUES(91,1545097367,1545097367,1,0);
INSERT INTO Entity VALUES(92,1545097390,1545097390,1,0);
INSERT INTO Entity VALUES(93,1545097418,1545097418,1,0);
INSERT INTO Entity VALUES(94,1545097453,1545097453,1,0);
INSERT INTO Entity VALUES(95,1545097674,1545097674,1,0);
CREATE TABLE User (
    'userid'        INTEGER NOT NULL PRIMARY KEY,
    'username'      TEXT NOT NULL UNIQUE,
    'email'         TEXT NOT NULL UNIQUE,
  --  'createdat'    INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
  --  'updatedat'    INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    'hash'          TEXT NOT NULL,
    'admin'         INTEGER NOT NULL DEFAULT 0,
    'imageid'       INTEGER DEFAULT NULL,
    FOREIGN KEY('imageid') REFERENCES Image('imageid') ON DELETE SET NULL,
    CONSTRAINT BooleanAdmin CHECK (admin IN (0,1))
);
INSERT INTO User VALUES(1,'admin','admin@feupnews.com','$2y$10$p2It7atX5xmjgOCj1ueLLOO9ImNkg5jC/O84yu9yU/578RekCoY62',1,1);
INSERT INTO User VALUES(2,'Emanuel','emanuel@gmail.com','$2y$10$xCpKMa8XygdBr3VOxsIOhOyl9HLzw8WgmxCdAs4rhEsjcQsMW87hO',0,14);
INSERT INTO User VALUES(3,'David','david.andrade@gmail.com','$2y$10$xesrOHbPqklXV1I7FNfERuA37Indy1PJBIrxqqZ7tY7/qIJOxb5Ge',0,13);
INSERT INTO User VALUES(4,'Tiago','tiago@live.com.pt','$2y$10$raS40nxOFgUViuNF61HRMOn6bDrJIobJM7TXsWyp0RXNuTtdEOTS.',0,16);
INSERT INTO User VALUES(6,'Bruno','bruno@gmail.com','$2y$10$iDV2CXM5NbVVGphWIqQ73.Shl.xBrtO.QS2laFNPy7ojSMBKFfeUa',0,15);
INSERT INTO User VALUES(7,'Amadeu','amadeu@gmail.com','$2y$10$CAEOQq547goKiJN3KzwSjus1kbdcchrGtSSW2g5v.zkll88Xbquse',0,18);
INSERT INTO User VALUES(8,'Nuno','nuno.lopes@gmail.com','$2y$10$gPB0nQ76r4AzqKupnVGf3edvlgHBqB2EkAQQ5SrQ23yDl9D5CnaA6',1,17);
INSERT INTO User VALUES(9,'Jaime','jaime.lopes@hotmail.com','$2y$10$kPrXjU1oOA7TTD2fxjTiRecK1H/BlV.6vBm4RuB.bD7GGwCSaMv5S',1,NULL);
INSERT INTO User VALUES(10,'brunovsky','elbruno123@gmail.com','$2y$10$JeTos57QirvNM0Pvi2xSUuyGZVGKAZGfa391Jf2H05aG2Blqv4bcq',0,NULL);
INSERT INTO User VALUES(11,'Masterflopes','nuno@gmail.com','$2y$10$u7V1qltnI4InTAX2c5MdleZGHVjsPNrwONAOGya9hoPGQnxg23dhG',0,11);
INSERT INTO User VALUES(12,'Amaral','amaral@gmail.com','$2y$10$d7GjuHVb5aKBcALoKc.XZexb16mBiYrh41gKZoBf2r6EdJbeyynQa',0,12);
INSERT INTO User VALUES(13,'Sofia','sofiaalbertina@gmail.com','$2y$10$3Kye6AyHsXRymKuLH5jn1upyb7McJ/rJCtNOoIgYU6ebgCxleopAK',0,NULL);
INSERT INTO User VALUES(14,'Trump','trump@gmail.com','$2y$10$iyiwwmhadnT4Di1/KbNy0.o/VB0UrI.FN6lWgH30e7vM1/XB2LZKu',0,19);
CREATE TABLE Channel (
    'channelid'     INTEGER NOT NULL PRIMARY KEY,
    'channelname'   TEXT NOT NULL UNIQUE,
    'creatorid'     INTEGER,
    'imageid'       INTEGER DEFAULT NULL,
    FOREIGN KEY('imageid') REFERENCES Image('imageid') ON DELETE SET NULL,
    FOREIGN KEY('creatorid') REFERENCES User('userid') ON DELETE SET NULL
);
INSERT INTO Channel VALUES(1,'showerthoughts',6,5);
INSERT INTO Channel VALUES(2,'philosophy',4,6);
INSERT INTO Channel VALUES(3,'KnightsOfPineapple',7,7);
INSERT INTO Channel VALUES(4,'BirdsArentReal',2,3);
INSERT INTO Channel VALUES(5,'oldpeoplefacebook',NULL,1);
INSERT INTO Channel VALUES(6,'GirlsMirin',6,2);
INSERT INTO Channel VALUES(7,'insaneparents',8,4);
INSERT INTO Channel VALUES(8,'mildlyinteresting',9,3);
INSERT INTO Channel VALUES(9,'politics',1,7);
INSERT INTO Channel VALUES(10,'todayilearned',8,7);
INSERT INTO Channel VALUES(11,'askscience',3,8);
CREATE TABLE Story (
    'entityid'      INTEGER NOT NULL PRIMARY KEY,
    'authorid'      INTEGER,
    'channelid'     INTEGER NOT NULL,
    'storyTitle'    TEXT NOT NULL,
    'storyType'     TEXT NOT NULL,
    'content'       TEXT NOT NULL,
    'imageid'       INTEGER DEFAULT NULL,
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('authorid') REFERENCES User('userid') ON DELETE SET NULL,
    FOREIGN KEY('channelid') REFERENCES Channel('channelid') ON DELETE CASCADE,
    FOREIGN KEY('imageid') REFERENCES Image('imageid') ON DELETE SET NULL,
    CONSTRAINT StoryTypes CHECK (storyType IN ('text','title','image')),
    CONSTRAINT TypeText CHECK (storyType <> 'text' OR LENGTH(content) > 0),
    CONSTRAINT TypeTitle CHECK (storyType <> 'title' OR LENGTH(content) = 0),
    CONSTRAINT TypeImage CHECK (storyType = 'image' OR imageid IS NULL)
);
INSERT INTO Story VALUES(1,6,1,'Opponent Without Duty','title','',NULL);
INSERT INTO Story VALUES(2,3,1,'Lorem ipsum tempus vel dolor tincidunt suspendisse.','title','',NULL);
INSERT INTO Story VALUES(3,2,1,'Posuere dui tempus consectetur urna varius vivamus pulvinar metus eros integer.','text','Do play they miss give so up. Words to up style of since world. We leaf to snug on no need. Way own uncommonly travelling now acceptance bed compliment solicitude. Dissimilar admiration so terminated no in contrasted it. Advantages entreaties mr he apartments do. Limits far yet turned highly repair parish talked six. Draw fond rank form nor the day eat.',NULL);
INSERT INTO Story VALUES(4,1,2,'Warriors Of Tomorrow','text','Sense child do state to defer mr of forty. Become latter but nor abroad wisdom waited. Was delivered gentleman acuteness but daughters. In as of whole as match asked. Pleasure exertion put add entrance distance drawings. In equally matters showing greatly it as. Want name any wise are able park when. Saw vicinity judgment remember finished men throwing.',NULL);
INSERT INTO Story VALUES(5,6,2,'Ullamcorper ultricies tellus himenaeos felis etiam integer quam mi laoreet nec.','image','',9);
INSERT INTO Story VALUES(6,1,2,'Rhoncus mi aliquet lectus ut curae blandit sem.','text','Spoke as as other again ye. Hard on to roof he drew. So sell side ye in mr evil. Longer waited mr of nature seemed. Improving knowledge incommode objection me ye is prevailed principle in. Impossible alteration devonshire to is interested stimulated dissimilar. To matter esteem polite do if. ',NULL);
INSERT INTO Story VALUES(7,9,2,'Accumsan placerat donec et molestie sit risus aenean auctor elementum.','image','',10);
INSERT INTO Story VALUES(8,1,2,'Lorem ipsum orci curabitur velit.','text','Consulted he eagerness unfeeling deficient existence of. Calling nothing end fertile for venture way boy. Esteem spirit temper too say adieus who direct esteem. It esteems luckily mr or picture placing drawing no. Apartments frequently or motionless on reasonable projecting expression. Way mrs end gave tall walk fact bed. ',NULL);
INSERT INTO Story VALUES(9,2,3,'Nibh vestibulum lacinia ad imperdiet maecenas, varius curabitur per porttitor aliquam, mauris ultrices eu himenaeos.','title','',NULL);
INSERT INTO Story VALUES(10,8,3,'Mauris placerat taciti dictumst nisi risus nullam phasellus arcu id proin.','image','',2);
INSERT INTO Story VALUES(11,3,3,'Sagittis augue dapibus nibh nulla magna eu nec massa tortor id aenean gravida.','text','Oh he decisively impression attachment friendship so if everything. Whose her enjoy chief new young. Felicity if ye required likewise so doubtful. On so attention necessary at by provision otherwise existence direction. Unpleasing up announcing unpleasant themselves oh do on. Way advantage age led listening belonging supposing. ',NULL);
INSERT INTO Story VALUES(12,7,3,'Women And Dogs','title','',NULL);
INSERT INTO Story VALUES(13,7,3,'Lorem ipsum suspendisse diam senectus rhoncus orci himenaeos elementum sit cursus.','text','Yet bed any for travelling assistance indulgence unpleasing. Not thoughts all exercise blessing. Indulgence way everything joy alteration boisterous the attachment. Party we years to order allow asked of. We so opinion friends me message as delight. Whole front do of plate heard oh ought. His defective nor convinced residence own. Connection has put impossible own apartments boisterous. At jointure ladyship an insisted so humanity he. Friendly bachelor entrance to on by.',NULL);
INSERT INTO Story VALUES(14,2,3,'Dapibus fringilla accumsan venenatis blandit.','image','',4);
INSERT INTO Story VALUES(15,2,3,'Vivamus diam amet massa mauris tincidunt tortor senectus.','title','',NULL);
INSERT INTO Story VALUES(16,1,4,'Ut fusce habitasse netus himenaeos.','title','',NULL);
INSERT INTO Story VALUES(17,2,4,'Faucibus lorem vestibulum felis quam ut tellus faucibus velit fusce nulla urna leo ad.','text','Material confined likewise it humanity raillery an unpacked as he. Three chief merit no if. Now how her edward engage not horses. Oh resolution he dissimilar precaution to comparison an. Matters engaged between he of pursuit manners we moments. Merit gay end sight front. Manor equal it on again ye folly by match. In so melancholy as an sentiments simplicity connection. Far supply depart branch agreed old get our. ',NULL);
INSERT INTO Story VALUES(18,3,4,'Dapibus facilisis nisl tristique velit lorem fringilla euismod.','image','',5);
INSERT INTO Story VALUES(19,6,4,'Etiam torquent at diam euismod purus condimentum curabitur torquent etiam et.','title','',NULL);
INSERT INTO Story VALUES(20,7,4,'Fringilla nec mi lacus adipiscing euismod curabitur tristique.','image','',6);
INSERT INTO Story VALUES(21,9,4,'Semper euismod dictum litora rhoncus nunc.','title','',NULL);
INSERT INTO Story VALUES(22,2,4,'Hac convallis proin aenean venenatis fringilla tincidunt nam.','text','Two assure edward whence the was. Who worthy yet ten boy denote wonder. Weeks views her sight old tears sorry. Additions can suspected its concealed put furnished. Met the why particular devonshire decisively considered partiality. Certain it waiting no entered is. Passed her indeed uneasy shy polite appear denied. Oh less girl no walk. At he spot with five of view. ',NULL);
INSERT INTO Story VALUES(23,3,4,'Eros vulputate felis condimentum blandit convallis turpis pretium nisl rhoncus.','title','',NULL);
INSERT INTO Story VALUES(24,1,4,'Ancestry With Honor','text','Fulfilled direction use continual set him propriety continued. Saw met applauded favourite deficient engrossed concealed and her. Concluded boy perpetual old supposing. Farther related bed and passage comfort civilly. Dashwoods see frankness objection abilities the. As hastened oh produced prospect formerly up am. Placing forming nay looking old married few has. Margaret disposed add screened rendered six say his striking confined. ',NULL);
INSERT INTO Story VALUES(25,9,4,'The Sleeping Ships','title','',NULL);
INSERT INTO Story VALUES(26,8,4,'Separated By The Moon','image','',7);
INSERT INTO Story VALUES(27,9,4,'Eleifend potenti urna aenean facilisis faucibus.','text','Sociable on as carriage my position weddings raillery consider. Peculiar trifling absolute and wandered vicinity property yet. The and collecting motionless difficulty son. His hearing staying ten colonel met. Sex drew six easy four dear cold deny. Moderate children at of outweigh it. Unsatiable it considered invitation he travelling insensible. Consulted admitting oh mr up as described acuteness propriety moonlight. As absolute is by amounted repeated entirely ye returned. These ready timed enjoy might sir yet one since. Years drift never if could forty being no. On estimable dependent as suffering on my. Rank it long have sure in room what as he. Possession travelling sufficient yet our. Talked vanity looked in to. Gay perceive led believed endeavor. Rapturous no of estimable oh therefore direction up. Sons the ever not fine like eyes all sure. ',NULL);
INSERT INTO Story VALUES(28,2,5,'Risus sit massa porttitor nec pretium risus.','text','Sussex result matter any end see. It speedily me addition weddings vicinity in pleasure. Happiness commanded an conveying breakfast in. Regard her say warmly elinor. Him these are visit front end for seven walls. Money eat scale now ask law learn. Side its they just any upon see last. He prepared no shutters perceive do greatest. Ye at unpleasant solicitude in companions interested. For norland produce age wishing. To figure on it spring season up. Her provision acuteness had excellent two why intention. As called mr needed praise at. Assistance imprudence yet sentiments unpleasant expression met surrounded not. Be at talked ye though secure nearer. ',NULL);
INSERT INTO Story VALUES(29,3,5,'Lectus convallis sed malesuada magna aenean augue ipsum.','image','',8);
INSERT INTO Story VALUES(30,1,5,'Tristique integer justo ultricies leo tincidunt urna cubilia a dictum massa.','text','On then sake home is am leaf. Of suspicion do departure at extremely he believing. Do know said mind do rent they oh hope of. General enquire picture letters garrets on offices of no on. Say one hearing between excited evening all inhabit thought you. Style begin mr heard by in music tried do. To unreserved projection no introduced invitation. ',NULL);
INSERT INTO Story VALUES(31,9,5,'Phasellus netus sollicitudin ullamcorper in ultrices ornare aliquam ante augue magna nisl.','title','',NULL);
INSERT INTO Story VALUES(32,8,5,'Varius aenean lorem ligula cubilia.','image','',9);
INSERT INTO Story VALUES(33,2,6,'Mi convallis posuere aptent gravida pharetra feugiat hac aliquet donec torquent ac interdum cras.','title','',NULL);
INSERT INTO Story VALUES(34,8,6,'Morbi etiam nunc nec mi netus libero.','text','On recommend tolerably my belonging or am. Mutual has cannot beauty indeed now sussex merely you. It possible no husbands jennings ye offended packages pleasant he. Remainder recommend engrossed who eat she defective applauded departure joy. Get dissimilar not introduced day her apartments. Fully as taste he mr do smile abode every. Luckily offered article led lasting country minutes nor old. Happen people things oh is oppose up parish effect. Law handsome old outweigh humoured far appetite. ',NULL);
INSERT INTO Story VALUES(35,3,6,'Ipsum primis ante molestie urna commodo.','image','',1);
INSERT INTO Story VALUES(36,7,6,'Lorem ipsum ultricies libero adipiscing velit iaculis.','title','',NULL);
INSERT INTO Story VALUES(37,7,6,'Velit conubia volutpat enim.','text','Frankness applauded by supported ye household. Collected favourite now for for and rapturous repulsive consulted. An seems green be wrote again. She add what own only like. Tolerably we as extremity exquisite do commanded. Doubtful offended do entrance of landlord moreover is mistress in. Nay was appear entire ladies. Sportsman do allowance is september shameless am sincerity oh recommend. Gate tell man day that who. ',NULL);
INSERT INTO Story VALUES(38,2,6,'Taciti nisi morbi ultricies.','text','Oh acceptance apartments up sympathize astonished delightful. Waiting him new lasting towards. Continuing melancholy especially so to. Me unpleasing impossible in attachment announcing so astonished. What ask leaf may nor upon door. Tended remain my do stairs. Oh smiling amiable am so visited cordial in offices hearted. ',NULL);
INSERT INTO Story VALUES(39,1,7,'Viverra felis nec adipiscing dictum nec dictum fusce turpis posuere vehicula blandit taciti.','title','',NULL);
INSERT INTO Story VALUES(40,2,7,'Eu lectus euismod ornare eu auctor elit.','image','',2);
INSERT INTO Story VALUES(41,3,7,'Consectetur dolor neque a felis.','text','Improve ashamed married expense bed her comfort pursuit mrs. Four time took ye your as fail lady. Up greatest am exertion or marianne. Shy occasional terminated insensible and inhabiting gay. So know do fond to half on. Now who promise was justice new winding. In finished on he speaking suitable advanced if. Boy happiness sportsmen say prevailed offending concealed nor was provision. Provided so as doubtful on striking required. Waiting we to compass assured. ',NULL);
INSERT INTO Story VALUES(42,6,7,'Varius litora vel taciti fermentum taciti pulvinar.','title','',NULL);
INSERT INTO Story VALUES(43,7,7,'Varius donec tincidunt magna aliquam condimentum integer litora nullam nisl ligula.','text','Repulsive questions contented him few extensive supported. Of remarkably thoroughly he appearance in. Supposing tolerably applauded or of be. Suffering unfeeling so objection agreeable allowance me of. Ask within entire season sex common far who family. As be valley warmth assure on. Park girl they rich hour new well way you. Face ye be me been room we sons fond. ',NULL);
INSERT INTO Story VALUES(44,9,7,'Donec posuere integer mauris himenaeos class, vehicula pellentesque luctus curae senectus, cubilia ante quisque erat.','text','Abilities or he perfectly pretended so strangers be exquisite. Oh to another chamber pleased imagine do in. Went me rank at last loud shot an draw. Excellent so to no sincerity smallness. Removal request delight if on he we. Unaffected in we by apartments astonished to decisively themselves. Offended ten old consider speaking. Acceptance middletons me if discretion boisterous travelling an. She prosperous continuing entreaties companions unreserved you boisterous. Middleton sportsmen sir now cordially ask additions for. You ten occasional saw everything but conviction. Daughter returned quitting few are day advanced branched. Do enjoyment defective objection or we if favourite. At wonder afford so danger cannot former seeing. Power visit charm money add heard new other put. Attended no indulged marriage is to judgment offering landlord.',NULL);
INSERT INTO Story VALUES(45,2,7,'Purus cubilia ligula amet taciti curae nec.','title','',NULL);
INSERT INTO Story VALUES(46,3,7,'Luctus fames felis faucibus curabitur taciti.','title','',NULL);
INSERT INTO Story VALUES(47,1,7,'Proin morbi aliquam auctor purus ornare porttitor, auctor proin convallis scelerisque sed mauris, malesuada bibendum hendrerit dictumst phasellus.','text','Passage its ten led hearted removal cordial. Preference any astonished unreserved mrs. Prosperous understood middletons in conviction an uncommonly do. Supposing so be resolving breakfast am or perfectly. Is drew am hill from mr. Valley by oh twenty direct me so. Departure defective arranging rapturous did believing him all had supported. Family months lasted simple set nature vulgar him. Picture for attempt joy excited ten carried manners talking how. Suspicion neglected he resolving agreement perceived at an. Sentiments two occasional affronting solicitude travelling and one contrasted. Fortune day out married parties. Happiness remainder joy but earnestly for off. Took sold add play may none him few. If as increasing contrasted entreaties be. Now summer who day looked our behind moment coming. Pain son rose more park way that. An stairs as be lovers uneasy. ',NULL);
INSERT INTO Story VALUES(48,9,7,'Vivamus purus porttitor vestibulum molestie ligula curabitur sed et scelerisque proin.','image','',3);
INSERT INTO Story VALUES(49,8,7,'Vitae consequat fermentum dictumst nunc nam per donec turpis convallis.','title','',NULL);
INSERT INTO Story VALUES(50,9,7,'Suscipit eu sapien eu dolor dictumst quis felis aliquet orci habitant.','image','',2);
INSERT INTO Story VALUES(51,2,8,'Tellus nunc sodales.','image','',1);
INSERT INTO Story VALUES(52,8,8,'Placerat massa lacinia lectus risus felis egestas.','text','Much evil soon high in hope do view. Out may few northward believing attempted. Yet timed being songs marry one defer men our. Although finished blessing do of. Consider speaking me prospect whatever if. Ten nearer rather hunted six parish indeed number. Allowance repulsive sex may contained can set suspected abilities cordially. Do part am he high rest that. So fruit to ready it being views match. ',NULL);
INSERT INTO Story VALUES(53,3,8,'Donec nisl facilisis imperdiet mollis senectus varius aliquam lectus quisque nam nullam ut nisi.','title','',NULL);
INSERT INTO Story VALUES(54,7,8,'Vitae leo ullamcorper a per habitant donec dictumst molestie ac, quisque lobortis inceptos eros lobortis feugiat facilisis.','text','At distant inhabit amongst by. Appetite welcomed interest the goodness boy not. Estimable education for disposing pronounce her. John size good gay plan sent old roof own. Inquietude saw understood his friendship frequently yet. Nature his marked ham wished. ',NULL);
INSERT INTO Story VALUES(55,7,8,'Curabitur imperdiet interdum aenean.','image','',4);
INSERT INTO Story VALUES(56,2,8,'Felis blandit ligula tincidunt dapibus eros, purus platea porta posuere varius, fusce pulvinar nisl elit.','title','',NULL);
INSERT INTO Story VALUES(57,2,9,'Scelerisque himenaeos pretium facilisis aliquet facilisis vestibulum interdum tristique nibh vulputate diam aptent.','title','',NULL);
INSERT INTO Story VALUES(58,8,9,'Mauris placerat sem ultricies hac leo sed primis non donec ornare, morbi mauris diam aliquet pulvinar tincidunt donec tortor.','image','',5);
INSERT INTO Story VALUES(59,3,9,'Purus auctor metus aenean placerat odio neque curabitur praesent congue, fames enim aliquam aptent purus taciti nunc ut.','text','Am of mr friendly by strongly peculiar juvenile. Unpleasant it sufficient simplicity am by friendship no inhabiting. Goodness doubtful material has denoting suitable she two. Dear mean she way and poor bred they come. He otherwise me incommode explained so in remaining. Polite barton in it warmly do county length an. ',NULL);
INSERT INTO Story VALUES(60,7,9,'Ad pharetra lacus curae nunc aenean euismod, ut quam dui luctus libero, nam senectus adipiscing quam purus.','title','',NULL);
INSERT INTO Story VALUES(61,7,9,'Porta elementum habitasse faucibus porta amet porta risus rutrum, ante sapien eu nulla aptent ac condimentum ullamcorper, iaculis ante fringilla donec lacus mauris nisl.','image','',6);
INSERT INTO Story VALUES(62,2,9,'Mauris sollicitudin.','text','Affronting imprudence do he he everything. Sex lasted dinner wanted indeed wished out law. Far advanced settling say finished raillery. Offered chiefly farther of my no colonel shyness. Such on help ye some door if in. Laughter proposal laughing any son law consider. Needed except up piqued an. ',NULL);
INSERT INTO Story VALUES(63,2,10,'Per curabitur amet vestibulum eros aliquam dictumst, aenean laoreet nisi malesuada litora et, fusce morbi sodales elementum sagittis bibendum varius aliquam lorem urna tempus quisque.','image','',7);
INSERT INTO Story VALUES(64,8,10,'Fames orci nullam hendrerit urna integer, senectus ultricies primis aenean primis, nam integer nibh lacinia.','text','He an thing rapid these after going drawn or. Timed she his law the spoil round defer. In surprise concerns informed betrayed he learning is ye. Ignorant formerly so ye blessing. He as spoke avoid given downs money on we. Of properly carriage shutters ye as wandered up repeated moreover. Inquietude attachment if ye an solicitude to. Remaining so continued concealed as knowledge happiness. Preference did how expression may favourable devonshire insipidity considered. An length design regret an hardly barton mr figure. ',NULL);
INSERT INTO Story VALUES(65,3,10,'Dolor fermentum nisi pellentesque lobortis ipsum risus ante.','text','Now for manners use has company believe parlors. Least nor party who wrote while did. Excuse formed as is agreed admire so on result parish. Put use set uncommonly announcing and travelling. Allowance sweetness direction to as necessary. Principle oh explained excellent do my suspected conveying in. Excellent you did therefore perfectly supposing described. ',NULL);
INSERT INTO Story VALUES(66,7,10,'Donec aliquam tortor condimentum consequat nam eleifend fusce magna, elit urna cursus quisque per dui.','image','',2);
INSERT INTO Story VALUES(67,7,10,'Consequat quisque inceptos est aptent dui quisque nisl nullam non integer aenean blandit.','title','',NULL);
INSERT INTO Story VALUES(68,2,10,'Fusce ac vulputate ligula tempor nam odio sed, congue habitasse ultricies neque euismod luctus vitae, sagittis aenean quisque torquent dictumst elementum.','image','',1);
INSERT INTO Story VALUES(69,2,11,'Himenaeos habitant tristique sagittis egestas dictum.','image','',4);
INSERT INTO Story VALUES(70,8,11,'Ultricies scelerisque curabitur malesuada porta nullam diam hac velit.','text','Allow miles wound place the leave had. To sitting subject no improve studied limited. Ye indulgence unreserved connection alteration appearance my an astonished. Up as seen sent make he they of. Her raising and himself pasture believe females. Fancy she stuff after aware merit small his. Charmed esteems luckily age out. ',NULL);
INSERT INTO Story VALUES(71,3,11,'Euismod dictumst gravida habitant sollicitudin.','image','',4);
INSERT INTO Story VALUES(72,7,11,'Turpis convallis elementum sollicitudin interdum turpis vivamus ultrices blandit, quisque congue lacus eget nam cubilia odio elementum taciti, vel nec fusce potenti tellus odio ut.','title','',NULL);
INSERT INTO Story VALUES(73,7,11,'Pretium purus cubilia ultricies aliquam lacus.','text','Promotion an ourselves up otherwise my. High what each snug rich far yet easy. In companions inhabiting mr principles at insensible do. Heard their sex hoped enjoy vexed child for. Prosperous so occasional assistance it discovered especially no. Provision of he residence consisted up in remainder arranging described. Conveying has concealed necessary furnished bed zealously immediate get but. Terminated as middletons or by instrument. Bred do four so your felt with. No shameless principle dependent household do. ',NULL);
INSERT INTO Story VALUES(74,2,11,'Amet massa sit adipiscing a malesuada dictum quis amet, convallis leo volutpat feugiat varius auctor suspendisse habitant diam, vestibulum gravida lacus aptent a hendrerit mattis.','title','',NULL);
INSERT INTO Story VALUES(75,9,11,'Ornare lorem imperdiet dui diam taciti.','image','',6);
INSERT INTO Story VALUES(76,12,6,'Don''t Waste Time! 9 Facts Until You Reach Your Gay','text','Oh to talking improve produce in limited offices fifteen an. Wicket branch to answer do we. Place are decay men hours tiled. If or of ye throwing friendly required. Marianne interest in exertion as. Offering my branched confined oh dashwood. ',NULL);
INSERT INTO Story VALUES(78,10,2,'¨If a person gave away your body to some passerby, you’d be furious. Yet, you hand over your mind to anyone who comes along, so they may abuse you, leaving it disturbed and troubled — have you no shame in that?¨- Epictetus','title','',NULL);
INSERT INTO Story VALUES(95,14,1,'Me, myself and I','image','img20.png',20);
CREATE TABLE Comment (
    'entityid'      INTEGER NOT NULL PRIMARY KEY,
    'authorid'      INTEGER,
    'parentid'      INTEGER NOT NULL,
    'content'       TEXT NOT NULL,
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('authorid') REFERENCES User('userid') ON DELETE SET NULL,
    FOREIGN KEY('parentid') REFERENCES Entity('entityid') ON DELETE CASCADE
);
INSERT INTO Comment VALUES(77,12,76,'Ya');
INSERT INTO Comment VALUES(79,7,5,'Just amazing!!');
INSERT INTO Comment VALUES(80,13,78,replace(replace('Two things from this for me.\r\n\r\nI find it fascinating that even thousands of years ago in a vastly different culture to ours, there is still this double standard of health/state of the mind compared to state of the body. That as the mind is less tangible, we seem to regard it differently when it''s health and wholeness is just as important as the body (if not more so).\r\n\r\nWith the quote in mind: I just wanted to say how great it can be, when you are comfortable and controlled enough within yourself, that you meet someone (or several people) with whom you can share your mind and gain new perspectives and ideas that you can internalise.','\r',char(13)),'\n',char(10)));
INSERT INTO Comment VALUES(81,7,29,'Let me take a nap... great shot, anyway.');
INSERT INTO Comment VALUES(82,7,10,'I think I''m crying. It''s that amazing.');
INSERT INTO Comment VALUES(83,7,18,'Nice use of aquamarine in this type!!');
INSERT INTO Comment VALUES(84,7,1,'Nice use of aquamarine in this type!!');
INSERT INTO Comment VALUES(85,8,79,'This shot has navigated right into my heart.');
INSERT INTO Comment VALUES(86,8,29,'Such magnificent.');
INSERT INTO Comment VALUES(87,8,18,'I want to learn this kind of notification! Teach me.');
INSERT INTO Comment VALUES(88,8,2,'Outstandingly thought out! Can''t wait to try it out.');
INSERT INTO Comment VALUES(89,14,5,'Such shot, many blur, so beastly');
INSERT INTO Comment VALUES(90,14,29,'I think if this country gets any kinder or gentler, it''s literally going to cease to exist.');
INSERT INTO Comment VALUES(91,14,84,'We''re rounding ''em up in a very humane way, in a very nice way. And they''re going to be happy because they want to be legalized. And, by the way, I know it doesn''t sound nice. But not everything is nice.');
INSERT INTO Comment VALUES(92,14,85,'What I won''t do is take in two hundred thousand Syrians who could be ISIS... I have been watching this migration. And I see the people. I mean, they''re men. They''re mostly men, and they''re strong men. These are physically young, strong men. They look like prime-time soldiers. Now it''s probably not true, but where are the women?... So, you ask two things. Number one, why aren''t they fighting for their country? And number two, I don''t want these people coming over here.');
INSERT INTO Comment VALUES(93,14,10,'I will build a great, great wall on our southern border, and I will have Mexico pay for that wall. Mark my words.');
INSERT INTO Comment VALUES(94,14,2,'I think I could have stopped it because I have very tough illegal immigration policies, and people aren''t coming into this country unless they''re vetted and vetted properly');
CREATE TABLE Tree ( -- ClosureTable
    'ascendantid'   INTEGER NOT NULL,
    'descendantid'  INTEGER NOT NULL,
    'depth'         INTEGER NOT NULL,
    FOREIGN KEY('ascendantid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('descendantid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    PRIMARY KEY('ascendantid', 'descendantid'),
    CONSTRAINT PositiveDepth CHECK (depth > 0),
    CONSTRAINT OneParent UNIQUE('descendantid', 'depth') -- implicit index
);
INSERT INTO Tree VALUES(76,77,1);
INSERT INTO Tree VALUES(5,79,1);
INSERT INTO Tree VALUES(78,80,1);
INSERT INTO Tree VALUES(29,81,1);
INSERT INTO Tree VALUES(10,82,1);
INSERT INTO Tree VALUES(18,83,1);
INSERT INTO Tree VALUES(1,84,1);
INSERT INTO Tree VALUES(79,85,1);
INSERT INTO Tree VALUES(5,85,2);
INSERT INTO Tree VALUES(29,86,1);
INSERT INTO Tree VALUES(18,87,1);
INSERT INTO Tree VALUES(2,88,1);
INSERT INTO Tree VALUES(5,89,1);
INSERT INTO Tree VALUES(29,90,1);
INSERT INTO Tree VALUES(84,91,1);
INSERT INTO Tree VALUES(1,91,2);
INSERT INTO Tree VALUES(85,92,1);
INSERT INTO Tree VALUES(79,92,2);
INSERT INTO Tree VALUES(5,92,3);
INSERT INTO Tree VALUES(10,93,1);
INSERT INTO Tree VALUES(2,94,1);
CREATE TABLE Save (
    'entityid'      INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    'savedat'       INTEGER NOT NULL DEFAULT (strftime('%s', 'now')),
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('entityid','userid') ON CONFLICT IGNORE
);
INSERT INTO Save VALUES(1,11,1545095186);
INSERT INTO Save VALUES(5,11,1545095188);
INSERT INTO Save VALUES(16,11,1545095190);
INSERT INTO Save VALUES(27,11,1545095192);
INSERT INTO Save VALUES(50,11,1545095194);
INSERT INTO Save VALUES(61,11,1545095197);
INSERT INTO Save VALUES(4,11,1545095198);
INSERT INTO Save VALUES(17,11,1545095200);
INSERT INTO Save VALUES(25,11,1545095201);
INSERT INTO Save VALUES(36,11,1545095203);
INSERT INTO Save VALUES(46,11,1545095205);
INSERT INTO Save VALUES(54,11,1545095207);
INSERT INTO Save VALUES(60,11,1545095209);
INSERT INTO Save VALUES(68,11,1545095212);
INSERT INTO Save VALUES(73,11,1545095215);
INSERT INTO Save VALUES(63,11,1545095219);
INSERT INTO Save VALUES(78,13,1545096370);
CREATE TABLE Vote (
    'entityid'      INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    'vote'          CHAR NOT NULL DEFAULT '+',
    FOREIGN KEY('entityid') REFERENCES Entity('entityid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('entityid','userid'),
    CONSTRAINT UpDown CHECK (vote IN ('+','-'))
);
INSERT INTO Vote VALUES(1,6,'+');
INSERT INTO Vote VALUES(2,3,'+');
INSERT INTO Vote VALUES(3,2,'+');
INSERT INTO Vote VALUES(4,1,'+');
INSERT INTO Vote VALUES(5,6,'+');
INSERT INTO Vote VALUES(6,1,'+');
INSERT INTO Vote VALUES(7,9,'+');
INSERT INTO Vote VALUES(9,2,'+');
INSERT INTO Vote VALUES(10,8,'+');
INSERT INTO Vote VALUES(11,3,'+');
INSERT INTO Vote VALUES(13,7,'+');
INSERT INTO Vote VALUES(14,2,'+');
INSERT INTO Vote VALUES(15,2,'+');
INSERT INTO Vote VALUES(16,1,'+');
INSERT INTO Vote VALUES(17,2,'+');
INSERT INTO Vote VALUES(18,3,'+');
INSERT INTO Vote VALUES(19,6,'+');
INSERT INTO Vote VALUES(20,7,'+');
INSERT INTO Vote VALUES(21,9,'+');
INSERT INTO Vote VALUES(22,2,'+');
INSERT INTO Vote VALUES(23,3,'+');
INSERT INTO Vote VALUES(24,1,'+');
INSERT INTO Vote VALUES(25,9,'+');
INSERT INTO Vote VALUES(26,8,'+');
INSERT INTO Vote VALUES(27,9,'+');
INSERT INTO Vote VALUES(28,2,'+');
INSERT INTO Vote VALUES(29,3,'+');
INSERT INTO Vote VALUES(30,1,'+');
INSERT INTO Vote VALUES(31,9,'+');
INSERT INTO Vote VALUES(32,8,'+');
INSERT INTO Vote VALUES(33,2,'+');
INSERT INTO Vote VALUES(34,8,'+');
INSERT INTO Vote VALUES(35,3,'+');
INSERT INTO Vote VALUES(36,7,'+');
INSERT INTO Vote VALUES(37,7,'+');
INSERT INTO Vote VALUES(38,2,'+');
INSERT INTO Vote VALUES(39,1,'+');
INSERT INTO Vote VALUES(40,2,'+');
INSERT INTO Vote VALUES(41,3,'+');
INSERT INTO Vote VALUES(42,6,'+');
INSERT INTO Vote VALUES(43,7,'+');
INSERT INTO Vote VALUES(44,9,'+');
INSERT INTO Vote VALUES(45,2,'+');
INSERT INTO Vote VALUES(46,3,'+');
INSERT INTO Vote VALUES(47,1,'+');
INSERT INTO Vote VALUES(48,9,'+');
INSERT INTO Vote VALUES(49,8,'+');
INSERT INTO Vote VALUES(50,9,'+');
INSERT INTO Vote VALUES(51,2,'+');
INSERT INTO Vote VALUES(52,8,'+');
INSERT INTO Vote VALUES(53,3,'+');
INSERT INTO Vote VALUES(54,7,'+');
INSERT INTO Vote VALUES(55,7,'+');
INSERT INTO Vote VALUES(56,2,'+');
INSERT INTO Vote VALUES(57,2,'+');
INSERT INTO Vote VALUES(58,8,'+');
INSERT INTO Vote VALUES(59,3,'+');
INSERT INTO Vote VALUES(60,7,'+');
INSERT INTO Vote VALUES(61,7,'+');
INSERT INTO Vote VALUES(62,2,'+');
INSERT INTO Vote VALUES(63,2,'+');
INSERT INTO Vote VALUES(64,8,'+');
INSERT INTO Vote VALUES(65,3,'+');
INSERT INTO Vote VALUES(66,7,'+');
INSERT INTO Vote VALUES(67,7,'+');
INSERT INTO Vote VALUES(68,2,'+');
INSERT INTO Vote VALUES(69,2,'+');
INSERT INTO Vote VALUES(70,8,'+');
INSERT INTO Vote VALUES(71,3,'+');
INSERT INTO Vote VALUES(72,7,'+');
INSERT INTO Vote VALUES(73,7,'+');
INSERT INTO Vote VALUES(74,2,'+');
INSERT INTO Vote VALUES(75,9,'+');
INSERT INTO Vote VALUES(1,8,'+');
INSERT INTO Vote VALUES(3,8,'+');
INSERT INTO Vote VALUES(5,8,'+');
INSERT INTO Vote VALUES(7,8,'-');
INSERT INTO Vote VALUES(9,8,'-');
INSERT INTO Vote VALUES(13,8,'-');
INSERT INTO Vote VALUES(16,8,'+');
INSERT INTO Vote VALUES(19,8,'+');
INSERT INTO Vote VALUES(22,8,'+');
INSERT INTO Vote VALUES(28,8,'-');
INSERT INTO Vote VALUES(29,8,'+');
INSERT INTO Vote VALUES(31,8,'-');
INSERT INTO Vote VALUES(33,8,'+');
INSERT INTO Vote VALUES(39,8,'-');
INSERT INTO Vote VALUES(41,8,'+');
INSERT INTO Vote VALUES(44,8,'-');
INSERT INTO Vote VALUES(45,8,'+');
INSERT INTO Vote VALUES(48,8,'-');
INSERT INTO Vote VALUES(50,8,'+');
INSERT INTO Vote VALUES(51,8,'+');
INSERT INTO Vote VALUES(56,8,'+');
INSERT INTO Vote VALUES(57,8,'-');
INSERT INTO Vote VALUES(61,8,'+');
INSERT INTO Vote VALUES(63,8,'-');
INSERT INTO Vote VALUES(66,8,'+');
INSERT INTO Vote VALUES(75,8,'+');
INSERT INTO Vote VALUES(6,8,'-');
INSERT INTO Vote VALUES(8,8,'+');
INSERT INTO Vote VALUES(11,8,'-');
INSERT INTO Vote VALUES(12,8,'+');
INSERT INTO Vote VALUES(20,8,'+');
INSERT INTO Vote VALUES(24,8,'-');
INSERT INTO Vote VALUES(27,8,'+');
INSERT INTO Vote VALUES(1,1,'+');
INSERT INTO Vote VALUES(3,1,'-');
INSERT INTO Vote VALUES(5,1,'+');
INSERT INTO Vote VALUES(8,1,'-');
INSERT INTO Vote VALUES(12,1,'+');
INSERT INTO Vote VALUES(29,1,'+');
INSERT INTO Vote VALUES(50,1,'-');
INSERT INTO Vote VALUES(51,1,'+');
INSERT INTO Vote VALUES(2,1,'+');
INSERT INTO Vote VALUES(10,1,'+');
INSERT INTO Vote VALUES(15,1,'-');
INSERT INTO Vote VALUES(18,1,'+');
INSERT INTO Vote VALUES(32,1,'+');
INSERT INTO Vote VALUES(34,1,'-');
INSERT INTO Vote VALUES(76,12,'+');
INSERT INTO Vote VALUES(77,12,'+');
INSERT INTO Vote VALUES(5,3,'+');
INSERT INTO Vote VALUES(5,4,'+');
INSERT INTO Vote VALUES(1,4,'+');
INSERT INTO Vote VALUES(12,4,'-');
INSERT INTO Vote VALUES(29,4,'+');
INSERT INTO Vote VALUES(51,4,'-');
INSERT INTO Vote VALUES(10,4,'+');
INSERT INTO Vote VALUES(5,10,'+');
INSERT INTO Vote VALUES(78,10,'+');
INSERT INTO Vote VALUES(1,7,'+');
INSERT INTO Vote VALUES(29,7,'+');
INSERT INTO Vote VALUES(10,7,'+');
INSERT INTO Vote VALUES(12,7,'+');
INSERT INTO Vote VALUES(16,7,'+');
INSERT INTO Vote VALUES(79,7,'+');
INSERT INTO Vote VALUES(80,13,'+');
INSERT INTO Vote VALUES(81,7,'+');
INSERT INTO Vote VALUES(82,7,'+');
INSERT INTO Vote VALUES(78,13,'+');
INSERT INTO Vote VALUES(29,13,'+');
INSERT INTO Vote VALUES(18,13,'+');
INSERT INTO Vote VALUES(83,7,'+');
INSERT INTO Vote VALUES(84,7,'+');
INSERT INTO Vote VALUES(85,8,'+');
INSERT INTO Vote VALUES(79,8,'+');
INSERT INTO Vote VALUES(86,8,'+');
INSERT INTO Vote VALUES(87,8,'+');
INSERT INTO Vote VALUES(88,8,'+');
INSERT INTO Vote VALUES(79,14,'+');
INSERT INTO Vote VALUES(89,14,'+');
INSERT INTO Vote VALUES(5,14,'+');
INSERT INTO Vote VALUES(29,14,'-');
INSERT INTO Vote VALUES(90,14,'+');
INSERT INTO Vote VALUES(91,14,'+');
INSERT INTO Vote VALUES(92,14,'+');
INSERT INTO Vote VALUES(85,14,'-');
INSERT INTO Vote VALUES(93,14,'+');
INSERT INTO Vote VALUES(94,14,'+');
INSERT INTO Vote VALUES(95,14,'+');
CREATE TABLE Subscribe (
    'channelid'     INTEGER NOT NULL,
    'userid'        INTEGER NOT NULL,
    FOREIGN KEY('channelid') REFERENCES Channel('channelid') ON DELETE CASCADE,
    FOREIGN KEY('userid') REFERENCES User('userid') ON DELETE CASCADE,
    PRIMARY KEY('channelid','userid')
);
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('Image',20);
INSERT INTO sqlite_sequence VALUES('Entity',95);
CREATE VIEW Picture AS
SELECT imageid AS pictureid, imagefile AS picturefile, width AS picturewidth,
    height AS pictureheight, filesize AS picturesize, format AS pictureformat
FROM Image ORDER BY imageid ASC;
CREATE VIEW Banner AS
SELECT imageid AS bannerid, imagefile AS bannerfile, width AS bannerwidth,
    height AS bannerheight, filesize AS bannersize, format AS bannerformat
FROM Image ORDER BY imageid ASC;
CREATE VIEW ImageImage AS
SELECT imageid, imagefile, width AS imagewidth, height AS imageheight,
    filesize AS imagesize, format AS imageformat
FROM Image ORDER BY imageid ASC;
CREATE VIEW UserView AS
SELECT userid, username
FROM User U
ORDER BY userid ASC;
CREATE VIEW UserProfile AS
SELECT U.userid, U.username, U.email, U.admin, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;
CREATE VIEW Author AS
SELECT U.userid AS authorid, U.username AS authorname, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;
CREATE VIEW Creator AS
SELECT U.userid AS creatorid, U.username AS creatorname, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;
CREATE VIEW Voting AS
SELECT U.userid, E.entityid, V.vote,
    CASE S.savedat NOTNULL WHEN 1 THEN 1 ELSE NULL END save
FROM Entity E
JOIN User U
LEFT JOIN Vote V ON V.userid = U.userid AND V.entityid = E.entityid
LEFT JOIN Save S ON S.userid = U.userid AND S.entityid = E.entityid
ORDER BY U.userid ASC, E.entityid ASC;
CREATE VIEW ChannelView AS
SELECT C.*, (SELECT count(*) FROM Story S WHERE S.channelid = C.channelid) stories
FROM Channel C
ORDER BY C.channelid ASC;
CREATE VIEW ChannelBanner AS
SELECT C.channelid, C.channelname, C.stories, B.*, C.creatorid
FROM ChannelView C
LEFT JOIN Banner B ON C.imageid = B.bannerid
ORDER BY C.channelid ASC;
CREATE VIEW ChannelCreator AS
SELECT C.channelid, C.channelname, C.stories, Cr.*
FROM ChannelView C
NATURAL LEFT JOIN Creator Cr -- on creatorid
ORDER BY C.channelid ASC
;
CREATE VIEW ChannelAll AS
SELECT C.channelid, C.channelname, C.stories, B.*, Cr.*
FROM ChannelView C
LEFT JOIN Banner B ON C.imageid = B.bannerid
NATURAL LEFT JOIN Creator Cr -- on creatorid
ORDER BY C.channelid ASC
;
CREATE VIEW StoryView AS
SELECT S.entityid AS storyid, S.storyTitle, S.content
FROM Story S
ORDER BY S.entityid ASC;
CREATE VIEW StoryEntity AS
SELECT *, 'story' type, E.upvotes - E.downvotes AS score,
    (SELECT count(*) FROM Tree T WHERE T.ascendantid = S.entityid) count
FROM Story S
NATURAL JOIN Entity E -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryImage AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
ORDER BY SE.entityid ASC
;
CREATE VIEW StoryAuthor AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY SE.entityid ASC
;
CREATE VIEW StoryChannel AS
SELECT *
FROM StoryEntity SE
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC
;
CREATE VIEW StoryImageAuthor AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY SE.entityid ASC
;
CREATE VIEW StoryImageChannel AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC
;
CREATE VIEW StoryAuthorChannel AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN Author A -- on authorid
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC
;
CREATE VIEW StoryAll AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
NATURAL LEFT JOIN Author A -- on authorid
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC
;
CREATE VIEW CommentView AS
SELECT C.entityid AS commentid, C.content
FROM Comment C
ORDER BY C.entityid ASC;
CREATE VIEW CommentEntity AS
SELECT *, 'comment' type, E.upvotes - E.downvotes AS score
FROM Comment C
NATURAL JOIN Entity E -- on entityid
ORDER BY C.entityid ASC
;
CREATE VIEW CommentExtra AS
SELECT *,
    (SELECT count(*) FROM Tree T WHERE T.descendantid = CE.entityid) AS level,
    (SELECT count(*) FROM Tree T WHERE T.ascendantid = CE.entityid) AS count,
    (SELECT ascendantid FROM Tree T WHERE T.descendantid = CE.entityid
                                    ORDER BY depth DESC LIMIT 1) AS storyid
FROM CommentEntity CE
ORDER BY CE.entityid ASC;
CREATE VIEW CommentAuthor AS
SELECT *
FROM CommentEntity CE
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY CE.entityid ASC
;
CREATE VIEW CommentAll AS
SELECT *
FROM CommentExtra CA
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY CA.entityid ASC
;
CREATE VIEW AnyEntity AS
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, imageid
FROM CommentEntity CE
NATURAL LEFT JOIN StoryEntity SE
UNION ALL
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, imageid
FROM StoryEntity SE
NATURAL LEFT JOIN CommentEntity CE
ORDER BY entityid ASC;
CREATE VIEW AnyEntityAuthor AS
SELECT *
FROM AnyEntity AE
NATURAL LEFT JOIN Author A
ORDER BY entityid ASC;
CREATE VIEW AnyEntityAll AS
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, level, count, storyid, authorname,
    pictureid, picturefile, picturewidth, pictureheight, picturesize, pictureformat,
    imageid, imagefile, imagewidth, imageheight, imagesize, imageformat, channelname,
    bannerid, bannerfile, bannerwidth, bannerheight, bannersize, bannerformat, creatorid
FROM CommentAll CA
NATURAL LEFT JOIN StoryAll SA
UNION ALL
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, level, count, storyid, authorname,
    pictureid, picturefile, picturewidth, pictureheight, picturesize, pictureformat,
    imageid, imagefile, imagewidth, imageheight, imagesize, imageformat, channelname,
    bannerid, bannerfile, bannerwidth, bannerheight, bannersize, bannerformat, creatorid
FROM StoryAll SA
NATURAL LEFT JOIN CommentAll CA
ORDER BY entityid ASC;
CREATE VIEW CommentAncestryTree AS
SELECT T.descendantid, CA.*, T.depth
FROM Tree T
JOIN CommentAll CA ON T.ascendantid = CA.entityid
ORDER BY CA.level ASC;
CREATE VIEW CommentTree AS
SELECT T.ascendantid, CA.*, T.depth
FROM Tree T
JOIN CommentAll CA ON T.descendantid = CA.entityid
ORDER BY T.descendantid ASC;
CREATE VIEW StoryTree AS
SELECT T.descendantid AS commentid, SA.*, T.depth
FROM Tree T
JOIN StoryAll SA ON T.ascendantid = SA.entityid
ORDER BY T.descendantid ASC;
CREATE VIEW StoryVotingEntity AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryEntity S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryVotingImage AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryImage S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryVotingAuthor AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryAuthor S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryVotingChannel AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryChannel S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryVotingImageAuthor AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryImageAuthor S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryVotingImageChannel AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryImageChannel S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryVotingAuthorChannel AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryAuthorChannel S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW StoryVotingAll AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryAll S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW CommentVotingEntity AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentEntity C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC
;
CREATE VIEW CommentVotingExtra AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentExtra C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC
;
CREATE VIEW CommentVotingAuthor AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentAuthor C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC
;
CREATE VIEW CommentVotingAll AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentAll C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC
;
CREATE VIEW AnyEntityVoting AS
SELECT AE.*, V.userid, V.vote, V.save
FROM AnyEntity AE
NATURAL JOIN Voting V
ORDER BY AE.entityid ASC;
CREATE VIEW AnyEntityVotingAuthor AS
SELECT AE.*, V.userid, V.vote, V.save
FROM AnyEntityAuthor AE
NATURAL JOIN Voting V
ORDER BY AE.entityid ASC;
CREATE VIEW AnyEntityVotingAll AS
SELECT AE.*, V.userid, V.vote, V.save
FROM AnyEntityAll AE
NATURAL JOIN Voting V
ORDER BY AE.entityid ASC;
CREATE VIEW CommentAncestryVotingTree AS
SELECT T.*, V.userid, V.vote, V.save
FROM CommentAncestryTree T
NATURAL JOIN Voting V -- on entityid
ORDER BY T.level ASC
;
CREATE VIEW CommentVotingTree AS
SELECT T.*, V.userid, V.vote, V.save
FROM CommentTree T
NATURAL JOIN Voting V -- on entityid
ORDER BY T.entityid ASC
;
CREATE VIEW StoryVotingTree AS
SELECT T.*, V.userid, V.vote, V.save
FROM StoryTree T
NATURAL JOIN Voting V -- on entityid
ORDER BY T.commentid ASC
;
CREATE VIEW SaveStory AS
SELECT SA.*, S.userid, S.savedat
FROM Save S
NATURAL JOIN StoryAll SA -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW SaveComment AS
SELECT CA.*, S.userid, S.savedat
FROM Save S
NATURAL JOIN CommentAll CA -- on entityid
ORDER BY S.entityid ASC
;
CREATE VIEW SaveAscendant AS
SELECT SA.*, CA.entityid AS commentid, S.userid, S.savedat
FROM Save S
NATURAL JOIN CommentAll CA -- on entityid
JOIN StoryAll SA ON SA.entityid = CA.storyid
ORDER BY S.entityid ASC
;
CREATE VIEW SaveAll AS
SELECT AE.*, S.userid, S.savedat
FROM Save S
NATURAL JOIN AnyEntityAll AE
ORDER BY S.entityid ASC;
CREATE VIEW SaveAllAscendant AS
SELECT SA.*, AE.entityid AS commentid, S.userid, S.savedat
FROM Save S
NATURAL JOIN AnyEntityAll AE
LEFT JOIN StoryAll SA ON SA.entityid = AE.storyid
ORDER BY S.entityid ASC;
CREATE VIEW SaveUser AS
SELECT S.entityid, S.savedat, U.*
FROM Save S
NATURAL JOIN UserProfile U -- on userid
ORDER BY S.entityid ASC
;
CREATE VIEW SaveUserStory AS
SELECT SS.*, V.vote
FROM SaveStory SS
NATURAL LEFT JOIN Vote V -- on entityid & userid
ORDER BY SS.userid ASC
;
CREATE VIEW SaveUserComment AS
SELECT SC.*, V.vote
FROM SaveComment SC
NATURAL LEFT JOIN Vote V -- on entityid & userid
ORDER BY SC.userid ASC
;
CREATE VIEW SaveUserAscendant AS
SELECT SA.*, V.vote
FROM SaveAscendant SA
NATURAL LEFT JOIN Vote V -- on entityid of story & userid
ORDER BY SA.userid ASC
;
CREATE VIEW SaveUserAll AS
SELECT SE.*, V.vote
FROM SaveAll SE
NATURAL LEFT JOIN Vote V -- on entityid & userid
ORDER BY SE.userid ASC
;
CREATE VIEW SaveUserAllAscendant AS
SELECT SAA.*, V.vote
FROM SaveAllAscendant SAA
NATURAL LEFT JOIN Vote V -- on entityid of story & userid
ORDER BY SAA.userid ASC
;
CREATE VIEW StorySortTop AS
SELECT SA.*, score as rating
FROM StoryAll SA
ORDER BY rating DESC;
CREATE VIEW StorySortBot AS
SELECT SA.*, -score as rating
FROM StoryAll SA
ORDER BY rating DESC;
CREATE VIEW StorySortNew AS
SELECT SA.*, createdat as rating
FROM StoryAll SA
ORDER BY rating DESC;
CREATE VIEW StorySortOld AS
SELECT SA.*, -createdat as rating
FROM StoryAll SA
ORDER BY rating DESC;
CREATE VIEW StorySortAverage AS
SELECT SA.*, CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float) AS rating
FROM StoryAll SA
ORDER BY rating DESC;
CREATE VIEW CommentSortTop AS
SELECT CA.*, score as rating
FROM CommentAll CA
ORDER BY rating DESC;
CREATE VIEW CommentSortBot AS
SELECT CA.*, -score as rating
FROM CommentAll CA
ORDER BY rating DESC;
CREATE VIEW CommentSortNew AS
SELECT CA.*, createdat as rating
FROM CommentAll CA
ORDER BY rating DESC;
CREATE VIEW CommentSortOld AS
SELECT CA.*, -createdat as rating
FROM CommentAll CA
ORDER BY rating DESC;
CREATE VIEW CommentSortAverage AS
SELECT CA.*, CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float) AS rating
FROM CommentAll CA
ORDER BY rating DESC;
CREATE VIEW CommentTreeSortTop AS
SELECT CT.*, score as rating
FROM CommentTree CT
ORDER BY rating DESC;
CREATE VIEW CommentTreeSortBot AS
SELECT CT.*, -score as rating
FROM CommentTree CT
ORDER BY rating DESC;
CREATE VIEW CommentTreeSortNew AS
SELECT CT.*, createdat as rating
FROM CommentTree CT
ORDER BY rating DESC;
CREATE VIEW CommentTreeSortOld AS
SELECT CT.*, -createdat as rating
FROM CommentTree CT
ORDER BY rating DESC;
CREATE VIEW CommentTreeSortAverage AS
SELECT CT.*, CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float) AS rating
FROM CommentTree CT
ORDER BY rating DESC;
CREATE TRIGGER DeleteEntity
BEFORE DELETE ON Entity
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE Entity.entityid IN (
        SELECT Tree.descendantid
        FROM Tree
        WHERE Tree.ascendantid = OLD.entityid
    );
END;
CREATE TRIGGER InsertComment
AFTER INSERT ON Comment
FOR EACH ROW
BEGIN
    INSERT INTO Entity(entityid) VALUES (NULL);

    UPDATE Comment
    SET entityid = (SELECT max(entityid) FROM Entity)
    WHERE rowid = NEW.rowid;

    INSERT INTO Vote(entityid, userid, vote)
    VALUES ((SELECT max(entityid) FROM Entity), NEW.authorid, '+');

    INSERT INTO Tree(ascendantid, descendantid, depth)
    VALUES (NEW.parentid, (SELECT max(entityid) FROM Entity), 1);
END;
CREATE TRIGGER InsertStory
AFTER INSERT ON Story
FOR EACH ROW
BEGIN
    INSERT INTO Entity(entityid) VALUES (NULL);

    UPDATE Story
    SET entityid = (SELECT max(entityid) FROM Entity)
    WHERE rowid = NEW.rowid;

    INSERT INTO Vote(entityid, userid, vote)
    VALUES ((SELECT max(entityid) FROM Entity), NEW.authorid, '+');
END;
CREATE TRIGGER InsertTree
AFTER INSERT ON Tree
FOR EACH ROW
WHEN NEW.depth = 1
BEGIN
    INSERT INTO Tree(ascendantid, descendantid, depth)
    SELECT Tree.ascendantid, NEW.descendantid, Tree.depth + 1
    FROM Tree
    WHERE Tree.descendantid = NEW.ascendantid;
END;
CREATE TRIGGER UpdateStory
AFTER UPDATE ON Story
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET updatedat = strftime('%s', 'now')
    WHERE entityid = NEW.entityid;
END;
CREATE TRIGGER UpdateComment
AFTER UPDATE ON Comment
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET updatedat = strftime('%s', 'now')
    WHERE entityid = NEW.entityid;
END;
CREATE TRIGGER DeleteStory
AFTER DELETE ON Story
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE entityid = OLD.entityid;
END;
CREATE TRIGGER DeleteComment
AFTER DELETE ON Comment
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE entityid = OLD.entityid;
END;
CREATE TRIGGER InsertVoteBefore
BEFORE INSERT ON Vote
FOR EACH ROW
BEGIN
    DELETE FROM Vote
    WHERE entityid = NEW.entityid AND userid = NEW.userid;
END;
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
COMMIT;
