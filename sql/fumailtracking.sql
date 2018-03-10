CREATE TABLE IF NOT EXISTS `TABLE_FUMAILLINKS` (
  `id` int(11) NOT NULL auto_increment,
  `IsActive` tinyint(1) NOT NULL default '1',
  `Link` varchar(255) default NULL,
  `Description` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `Link` (`Link`)
);

CREATE TABLE IF NOT EXISTS `TABLE_FUMAILTRACKINGOPENINGS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  `Country` varchar(128) default 'UNKNOWN_COUNTRY',
  KEY `SendStat_id` (`SendStat_id`),
  KEY `IP` (`IP`),
  KEY `Country` (`Country`)
);

CREATE TABLE IF NOT EXISTS `TABLE_FUMAILTRACKINGOPENINGSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_FUMAILTRACKINGLINKS` (
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

CREATE TABLE IF NOT EXISTS `TABLE_FUMAILTRACKINGLINKSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Links_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `Links_id` (`Links_id`),
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_FUMAILTRACKINGUSERAGENTS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `UserAgent` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `UserAgent` (`UserAgent`),
  KEY `IP` (`IP`)
);

CREATE TABLE IF NOT EXISTS `TABLE_FUMAILTRACKINGOSS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `OS` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `OS` (`OS`),
  KEY `IP` (`IP`)
);

