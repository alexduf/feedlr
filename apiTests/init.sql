
-- delete last tests
delete from userPost where exists (select 1 from user where user.userId = userPost.userId and user.login = 'test');
delete from subscription where exists (select 1 from user where user.userId = subscription.userId and user.login = 'test');
delete from subscription where title like '%Delicieuse%';
delete from post where externalId like 'test%' or externalId like '%delicieuse%';
delete from feed where title in ('test1', 'test2', 'test3', 'Delicieuse Musique');
delete from category where exists (select 1 from user where user.userId = category.userId and user.login = 'test');
delete from user where login='test';

-- create the test user, password = sha256('testsalt'), so his password is 'test', and the salt is 'salt'
insert into user (login, password, mail, created) values ('test', '4edf07edc95b2fdcbcaf2378fd12d8ac212c2aa6e326c59c3e629be3039d6432', 'test@example.com', now());
-- retrieve its ID
select @userId:=LAST_INSERT_ID();

-- create 2 categories
insert into category (userId, caption) values (@userId, 'music');
select @category1:=LAST_INSERT_ID();

insert into category (userId, caption) values (@userId, 'news');
select @category2:=LAST_INSERT_ID();

-- add 3 feeds
insert into feed (title, subtitle, url, link, updated, type) values ('test1', 'that''s a subtitle', 'http://korben.info','http://korben.info/feed', now(), 'atom');
select @feed1:=LAST_INSERT_ID();

insert into feed (title, subtitle, url, link, updated, type) values ('test2', 'that''s again a subtitle', 'http://www.pcinpact.com', 'http://www.pcinpact.com/rss/news.xml', now(),'rss2.0');
select @feed2:=LAST_INSERT_ID();

insert into feed (title, subtitle, url, link, updated, type) values ('test3', 'that''s a subtitle', 'http://www.lemonde.fr/','http://rss.lemonde.fr/c/205/f/3050/index.rss', now(),'rss2.0');
select @feed3:=LAST_INSERT_ID();

-- add 7 posts
insert into post (feedId, externalId, title, link, mobileLink, updated, summary, content) values (@feed1, 'test', 'Edito du 07/07/2012', 'http://korben.info/edito-du-07072012.html', 'http://korben.info/edito-du-07072012.html', '2012-07-07 17:17:21', 'Hello, Une fois n''est pas coutume, voici un petit edito en direct de la Comicon à Paris. La bise.', 'big content');
select @post1:=LAST_INSERT_ID();

insert into post (feedId, externalId, title, link, mobileLink, updated, summary, content) values (@feed1, 'test', 'Intégrer Github sur votre site', 'http://korben.info/embed-github-site-pages.html', 'http://korben.info/embed-github-site-pages.html', '2012-07-05 15:28:21', 'Hello, Une fois n''est pas coutume, voici un petit edito en direct de la Comicon à Paris. La bise.', 'big content');
select @post2:=LAST_INSERT_ID();

insert into post (feedId, externalId, title, link, mobileLink, updated, summary, content) values (@feed2, 'testPost', 'UFC Que Choisir : réaction suite à l''annonce de l''indemnisation d''Orange', 'http://www.pcinpact.com/news/72276-ufc-que-choisir-reaction-suite-a-annonce-indeminisation-dorange.htm', 'http://www.pcinpact.com/news/72276-ufc-que-choisir-reaction-suite-a-annonce-indeminisation-dorange.htm', '2012-07-07 18:10:12', 'Suite à l''annonce d''indemnisation d''Orange, qui a connu une panne de plus de douze heures dans la journée d''hier et une partie de la nuit, nous avons contacté l''UFC Que Choisir afin de recueillir la réaction de l''association, par l''intermédiaire d''Édouard Barreiro.', 'big content');
select @post3:=LAST_INSERT_ID();

insert into post (feedId, externalId, title, link, mobileLink, updated, summary, content) values (@feed2, 'test', '[MàJ] Orange détaille l’indemnisation proposée aux clients suite à la panne', 'http://www.pcinpact.com/news/72269-indisponibilites-sur-reseau-mobidorange-au-niveau-national.htm', 'http://www.pcinpact.com/news/72269-indisponibilites-sur-reseau-mobidorange-au-niveau-national.htm', '2012-07-07 16:40:21', 'Nous avions constaté des coupures intervenir sur le réseau mobile d''Orange. L''opérateur vient de confirmer l''information. La panne touche tout le territoire et aucune solution n''est pour l''instant trouvée par les équipes techniques.', 'big content');
select @post4:=LAST_INSERT_ID();

insert into post (feedId, externalId, title, link, mobileLink, updated, summary, content) values (@feed2, 'test', '[Brève] Megaupload : Kim Dotcom provoque la Hadopi sur Twitter', 'http://www.pcinpact.com/breve/72275-megaupload-kim-dotcom-provoque-hadopi-sur-twitter.htm', 'http://www.pcinpact.com/breve/72275-megaupload-kim-dotcom-provoque-hadopi-sur-twitter.htm', '2012-07-07 16:16:27', 'Depuis quelques temps, Kim Dotcom, fondateur de l''empire Mega dont le site phare Megaupload avait été mis hors ligne par le FBI en janvier dernier, joue la provocation sur Twitter. Il semble décidé à faire parler de lui de manière plus locale en s''attaquant aux lois spécifiques à chaque pays. Il vient ainsi de promettre la mort prochaine de l''HADOPI, rien de moins.', 'big content');
select @post5:=LAST_INSERT_ID();

insert into post (feedId, externalId, title, link, mobileLink, updated, summary, content) values (@feed3, 'test', 'Pour la première fois, la police expose ses archives de la rafle du Vel d''Hiv', 'http://www.lemonde.fr/societe/article/2012/07/08/pour-la-premiere-fois-la-police-expose-ses-archives-de-la-rafle-du-vel-d-hiv_1730780_3224.html#xtor=RSS-3208', 'http://www.lemonde.fr/societe/article/2012/07/08/pour-la-premiere-fois-la-police-expose-ses-archives-de-la-rafle-du-vel-d-hiv_1730780_3224.html#xtor=RSS-3208', '2012-07-08 12:12:00', 'Listes de juifs arrêtés, comptabilité de leurs biens saisis, notes des RG sur l''état d''esprit de la population : pour la première fois, la préfecture de police de Paris expose ses archives de la rafle du Vel d''Hiv en juillet 1942.', 'big content');
select @post6:=LAST_INSERT_ID();

insert into post (feedId, externalId, title, link, mobileLink, updated, summary, content) values (@feed3, 'test', 'Entre Federer et Murray, des records et des premières en jeu', 'http://www.lemonde.fr/sport/article/2012/07/08/entre-federer-et-murray-des-records-et-des-premieres-en-jeu_1730781_3242.html#xtor=RSS-3208', 'http://www.lemonde.fr/sport/article/2012/07/08/entre-federer-et-murray-des-records-et-des-premieres-en-jeu_1730781_3242.html#xtor=RSS-3208', '2012-07-08 11:53:21', 'Lors de la finale de Wimbledon dimanche, le Suisse visera la première place mondiale et plusieurs records, tandis que le Britannique cherchera à donner à son pays son premier titre majeur depuis 1936.', 'big content');
select @post7:=LAST_INSERT_ID();

-- we have data, now we can link it to the user

-- feeds
insert into subscription (categoryId, userId, feedId, title) values (@category1, @userId, @feed1, 'Korben');
select @subscription1:=LAST_INSERT_ID();
insert into subscription (categoryId, userId, feedId, title) values (@category2, @userId, @feed2, 'PCI');
select @subscription2:=LAST_INSERT_ID();
insert into subscription (categoryId, userId, feedId, title) values (null, @userId, @feed3, 'Le monde');
select @subscription3:=LAST_INSERT_ID();

-- posts
insert into userPost (userId, postId, subscriptionId, readed, favourited) values (@userId, @post1, @subscription1, false, false);
insert into userPost (userId, postId, subscriptionId, readed, favourited) values (@userId, @post2, @subscription1, false, false);
insert into userPost (userId, postId, subscriptionId, readed, favourited) values (@userId, @post3, @subscription2, false, false);
insert into userPost (userId, postId, subscriptionId, readed, favourited) values (@userId, @post4, @subscription2, true, false);
insert into userPost (userId, postId, subscriptionId, readed, favourited) values (@userId, @post5, @subscription2, true, false);
insert into userPost (userId, postId, subscriptionId, readed, favourited) values (@userId, @post6, @subscription3, false, false);
insert into userPost (userId, postId, subscriptionId, readed, favourited) values (@userId, @post7, @subscription3, false, false);


