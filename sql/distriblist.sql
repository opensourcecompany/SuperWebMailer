CREATE TABLE IF NOT EXISTS `TABLE_CURRENT_SENDTABLE` (
  `id` int(11) NOT NULL auto_increment,
  `distriblistentry_id` int(11) NOT NULL,
  `ReportSent` tinyint(1) NOT NULL default '0',
  `RecipientsCount` int(11) NOT NULL default '0',
  `SentCountSucc` int(11) NOT NULL default '0',
  `SentCountFailed` int(11) NOT NULL default '0',
  `SentCountPossiblySent` int(11) NOT NULL default '0',
  `HardBouncesCount` int(11) NOT NULL default '0',
  `SoftBouncesCount` int(11) NOT NULL default '0',
  `UnsubscribesCount` int(11) NOT NULL default '0',
  `StartSendDateTime` datetime NOT NULL,
  `EndSendDateTime` datetime NOT NULL,
  `SendState` enum('Sending','ReSending','Done') NOT NULL default 'Sending',
  `DistribEntrySendDone` tinyint(1) NOT NULL default '0',
  `LastMember_id` int(11) NOT NULL default '0',
  `TwitterUpdate` enum('NotActivated','Done','Failed') NOT NULL default 'NotActivated',
  `TwitterUpdateErrorText` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY (`distriblistentry_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_CURRENT_USED_MTAS` (
  `id` int(11) NOT NULL auto_increment,
  `distriblistentry_id` int(11) NOT NULL,
  `SendStat_id` int(11) NOT NULL,
  `mtas_id` int(11) NOT NULL,
  `MailCount` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `SendStat_id`  (`SendStat_id`),
  KEY (`distriblistentry_id`),
  KEY `mtas_id`  (`mtas_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_C_STATISTICS` (
  `id` int(11) NOT NULL auto_increment,
  `distriblistentry_id` int(11) NOT NULL,
  `MailSubject` varchar(255) NOT NULL,
  `SendDateTime` datetime NOT NULL,
  `recipients_id` int(11) NOT NULL,
  `Send` enum('Prepared','Sent','Failed','PossiblySent','Hardbounced') NOT NULL default 'Prepared',
  `SendResult` varchar(255) NOT NULL,
  `SendStat_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY (`distriblistentry_id`),
  KEY `SendStat_id`  (`SendStat_id`),
  UNIQUE `distriblistentry_idrecipients_idSendStat_id` (`distriblistentry_id`, `recipients_id`, `SendStat_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_GROUPS` (
  `ml_groups_id` int(11) NOT NULL,
  PRIMARY KEY ( `ml_groups_id` )
);

CREATE TABLE IF NOT EXISTS `TABLE_NOTINGROUPS` (
  `ml_groups_id` int(11) NOT NULL,
  PRIMARY KEY ( `ml_groups_id` )
);

CREATE TABLE IF NOT EXISTS `TABLE_MTAS` (
  `mtas_id` int(11) NOT NULL,
  `sortorder` int(4) NOT NULL default '0',
  PRIMARY KEY `mtas_id` (`mtas_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_DISTRIBLISTLINKS` (
  `id` int(11) NOT NULL auto_increment,
  `distriblistentry_id` int(11) NOT NULL,
  `IsActive` tinyint(1) NOT NULL default '1',
  `Link` varchar(255) default NULL,
  `Description` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY (`distriblistentry_id`),
  KEY `Link` (`Link`)
);

CREATE TABLE IF NOT EXISTS `TABLE_DISTRIBLISTTRACKINGOPENINGS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  `Country` varchar(128) default 'UNKNOWN_COUNTRY',
  KEY `SendStat_id` (`SendStat_id`),
  KEY `IP` (`IP`),
  KEY `Country` (`Country`)
);

CREATE TABLE IF NOT EXISTS `TABLE_DISTRIBLISTTRACKINGOPENINGSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_DISTRIBLISTTRACKINGLINKS` (
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

CREATE TABLE IF NOT EXISTS `TABLE_DISTRIBLISTTRACKINGLINKSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Links_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `Links_id` (`Links_id`),
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);


CREATE TABLE IF NOT EXISTS `TABLE_DISTRIBLISTTRACKINGUSERAGENTS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `UserAgent` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `UserAgent` (`UserAgent`),
  KEY `IP` (`IP`)
);

CREATE TABLE IF NOT EXISTS `TABLE_DISTRIBLISTTRACKINGOSS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `OS` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `OS` (`OS`),
  KEY `IP` (`IP`)
);
