CREATE TABLE IF NOT EXISTS `TABLE_ML_BM_REFERENCE` (
  `Member_id` int(11) NOT NULL,
  `LastSending` datetime NOT NULL,
  PRIMARY KEY ( `Member_id` ),
  KEY ( `LastSending` )
);

CREATE TABLE IF NOT EXISTS `TABLE_BMLINKS` (
  `id` int(11) NOT NULL auto_increment,
  `IsActive` tinyint(1) NOT NULL default '1',
  `Link` text NOT NULL,
  `Description` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `Link` (`Link`(255))
);

CREATE TABLE IF NOT EXISTS `TABLE_BMTRACKINGOPENINGS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  `Country` varchar(128) default 'UNKNOWN_COUNTRY',
  KEY `SendStat_id` (`SendStat_id`),
  KEY `IP` (`IP`),
  KEY `Country` (`Country`)
);

CREATE TABLE IF NOT EXISTS `TABLE_BMTRACKINGOPENINGSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_BMTRACKINGLINKS` (
  `SendStat_id` int(11) NOT NULL,
  `Links_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  `Country` varchar(128) default 'UNKNOWN_COUNTRY',
  KEY `Links_id` (`Links_id`),
  KEY `SendStat_id` (`SendStat_id`),
  KEY `IP` (`IP`),
  KEY `Country` (`Country`)
);

CREATE TABLE IF NOT EXISTS `TABLE_BMTRACKINGLINKSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Links_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `Links_id` (`Links_id`),
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_BMTRACKINGUSERAGENTS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `UserAgent` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `UserAgent` (`UserAgent`),
  KEY `IP` (`IP`)
);

CREATE TABLE IF NOT EXISTS `TABLE_BMTRACKINGOSS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `OS` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `OS` (`OS`),
  KEY `IP` (`IP`)
);
