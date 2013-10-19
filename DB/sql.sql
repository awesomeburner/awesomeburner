DROP TABLE IF EXISTS `feed_feeds`;
CREATE TABLE `feed_feeds` (
  `feed_id` int(255) NOT NULL auto_increment,
  `feed_url` varchar(255) NOT NULL COMMENT 'http://lenta.ru/rss.xml',
  `lastindex` bigint(14) NOT NULL COMMENT '20090321101010',
  `lastbuilddate_int` bigint(14) default NULL,
  `pubdate_int` bigint(14) default NULL,
  `update` bigint(14) default NULL,
  `title` varchar(255) default NULL,
  `link` varchar(255) default NULL,
  `description` text,
  `language` varchar(50) default NULL,
  `copyright` varchar(150) default NULL,
  `managingeditor` varchar(155) default NULL,
  `webmaster` varchar(155) default NULL,
  `pubdate` varchar(100) default NULL,
  `lastbuilddate` varchar(100) default NULL,
  `category` varchar(150) default NULL,
  `generator` varchar(155) default NULL,
  `docs` varchar(255) default NULL,
  `cloud` varchar(255) default NULL,
  `ttl` int(5) default NULL,
  `skiphours` varchar(100) default NULL,
  `skipdays` varchar(100) default NULL,
  `image_url` varchar(255) default NULL COMMENT 'URL images GIF, JPEG or PNG',
  `image_title` varchar(255) default NULL,
  `image_link` varchar(255) default NULL,
  PRIMARY KEY  (`feed_id`,`feed_url`),
  UNIQUE KEY `url` (`feed_url`)
) ENGINE=MyISAM	 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for feed_items
-- ----------------------------
DROP TABLE IF EXISTS `feed_items`;
CREATE TABLE `feed_items` (
  `feed_id` int(255) NOT NULL,
  `item_id` int(255) NOT NULL auto_increment,
  `pubdate_int` bigint(14) default NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) default NULL,
  `description` text,
  `author` varchar(100) default NULL,
  `category` varchar(150) default NULL,
  `comments` varchar(255) default NULL,
  `enclousure` varchar(100) default NULL,
  `guid` varchar(100) default NULL,
  `pubdate` varchar(150) default NULL,
  `source` varchar(255) default NULL,
  PRIMARY KEY  (`item_id`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for feed_saves_temp
-- ----------------------------
DROP TABLE IF EXISTS `feed_saves_temp`;
CREATE TABLE `feed_saves_temp` (
  `save_id` bigint(255) NOT NULL auto_increment,
  `feed_id` bigint(255) NOT NULL,
  `datetime` bigint(8) NOT NULL,
  `size` bigint(20) NOT NULL,
  `md5sum` varchar(64) NOT NULL,
  `file` text NOT NULL,
  PRIMARY KEY  (`save_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_deny_strings_url`;
CREATE TABLE `sys_deny_strings_url` (
  `index_string` varchar(100) NOT NULL,
  `approve` int(1) NOT NULL default '0',
  UNIQUE KEY `string` (`index_string`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_domains`;
CREATE TABLE `sys_domains` (
  `domain_id` int(11) NOT NULL auto_increment,
  `domain` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`domain_id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_keywords`;
CREATE TABLE `sys_keywords` (
  `keyword_id` bigint(255) NOT NULL auto_increment,
  `keyword` varchar(150) NOT NULL,
  PRIMARY KEY  (`keyword_id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_nodes`;
CREATE TABLE `sys_nodes` (
  `node_id` int(11) NOT NULL auto_increment,
  `status` enum('Y','N') NOT NULL,
  `address` varchar(150) NOT NULL,
  `path` varchar(150) NOT NULL,
  `available` bigint(32) NOT NULL,
  `used` bigint(32) NOT NULL,
  PRIMARY KEY  (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_query_log`;
CREATE TABLE `sys_query_log` (
  `query` varchar(255) character set latin1 default NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `elapsed` float default NULL,
  `results` int(11) default NULL,
  KEY `query_key` (`query`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_stat_urls`;
CREATE TABLE `sys_stat_urls` (
  `stat_date` int(8) NOT NULL,
  `domains` int(11) NOT NULL,
  `sites` int(11) NOT NULL,
  `links` int(11) NOT NULL,
  `links_saved` bigint(11) NOT NULL default '0',
  `saves` bigint(11) NOT NULL default '0',
  `size_archive` bigint(255) NOT NULL default '0',
  PRIMARY KEY  (`stat_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_tags`;
CREATE TABLE `sys_tags` (
  `tag_id` int(100) NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY  (`tag_id`,`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sys_users`;
CREATE TABLE `sys_users` (
  `user_id` int(100) NOT NULL auto_increment,
  `fname` varchar(150) NOT NULL,
  `lname` varchar(150) default NULL,
  `email` varchar(150) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `level` int(3) NOT NULL,
  `icq` int(20) default NULL,
  `skype` varchar(150) default NULL,
  PRIMARY KEY  (`user_id`,`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `web_links`;
CREATE TABLE `web_links` (
  `link_id` int(11) NOT NULL auto_increment,
  `site_id` int(11) default NULL,
  `url` varchar(255) character set latin1 NOT NULL,
  `index_date` int(8) default NULL,
  `size` bigint(20) default NULL,
  `md5sum` varchar(32) default NULL,
  `visible` int(11) default '0',
  `level` int(11) default NULL,
  PRIMARY KEY  (`link_id`),
  UNIQUE KEY `url` (`url`),
  KEY `md5key` (`md5sum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `web_pending`;
CREATE TABLE `web_pending` (
  `site_id` int(11) default NULL,
  `temp_id` varchar(32) default NULL,
  `level` int(11) default NULL,
  `count` int(11) default NULL,
  `num` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `web_saves`;
CREATE TABLE `web_saves` (
  `save_id` bigint(255) NOT NULL auto_increment,
  `link_id` bigint(255) NOT NULL,
  `mime_type` varchar(155) NOT NULL,
  `date_saved` bigint(8) NOT NULL,
  `size_document` bigint(20) NOT NULL,
  `md5sum` varchar(64) NOT NULL,
  `location_node` int(100) NOT NULL,
  `location_subone` int(3) NOT NULL,
  `location_subtwo` int(3) NOT NULL,
  PRIMARY KEY  (`save_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `web_saves_20080303`;
CREATE TABLE `web_saves_20080303` (
  `save_id` bigint(255) NOT NULL auto_increment,
  `link_id` bigint(255) NOT NULL,
  `mime_type` varchar(155) NOT NULL,
  `date_saved` bigint(8) NOT NULL,
  `size_document` bigint(20) NOT NULL,
  `md5sum` int(32) NOT NULL,
  `location_node` varchar(100) NOT NULL,
  `location_subone` int(3) NOT NULL,
  `location_subtwo` int(3) NOT NULL,
  PRIMARY KEY  (`save_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `web_sites`;
CREATE TABLE `web_sites` (
  `site_id` int(11) NOT NULL auto_increment,
  `url` varchar(255) default NULL,
  `index_date` int(8) default NULL,
  `spider_depth` int(11) default '2',
  `required` text character set latin1,
  `disallowed` text character set latin1,
  `can_leave_domain` tinyint(1) default NULL,
  PRIMARY KEY  (`site_id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `web_temp`;
CREATE TABLE `web_temp` (
  `link` varchar(255) NOT NULL default '',
  `level` int(11) default NULL,
  `id` varchar(32) default NULL,
  PRIMARY KEY  (`link`),
  UNIQUE KEY `link` (`link`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `sys_deny_strings_url` VALUES ('?', '1'), ('C=D;O=A', '1'), ('C=D;O=D', '1'), ('C=M;O=A', '1'), ('C=M;O=D', '1'), ('C=N;O=A', '1'), ('C=N;O=D', '1'), ('C=S;O=A', '1'), ('C=S;O=D', '1'), ('D=A', '1'), ('D=D', '1'), ('M=A', '1'), ('M=D', '1'), ('N=A', '1'), ('N=D', '1'), ('S=A', '1'), ('S=D', '1'), ('sort=date', '1');
