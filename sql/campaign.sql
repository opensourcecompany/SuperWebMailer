CREATE TABLE IF NOT EXISTS `TABLE_CURRENT_SENDTABLE` (
  `id` int(11) NOT NULL auto_increment,

  -- add
  `Campaigns_id` int(11) NOT NULL default '0',

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
  `CampaignSendDone` tinyint(1) NOT NULL default '0',
  `LastMember_id` int(11) NOT NULL default '0',
  `TwitterUpdate` enum('NotActivated','Done','Failed') NOT NULL default 'NotActivated',
  `TwitterUpdateErrorText` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),

  -- add
  KEY `Campaigns_id` (`Campaigns_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_CURRENT_USED_MTAS` (
  `id` int(11) NOT NULL auto_increment,
  `SendStat_id` int(11) NOT NULL,
  `mtas_id` int(11) NOT NULL,
  `MailCount` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `SendStat_id`  (`SendStat_id`),
  KEY `mtas_id`  (`mtas_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_ARCHIVETABLE` (
  `id` int(11) NOT NULL auto_increment,
  `SendStat_id` int(11) NOT NULL,
  `MailFormat` enum('PlainText','HTML','Multipart') NOT NULL default 'Multipart',
  `MailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `MailSubject` text NOT NULL,
  `MailPlainText` mediumtext NOT NULL,
  `MailHTMLText` mediumtext NOT NULL,
  `Attachments` mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `SendStat_id`  (`SendStat_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_C_STATISTICS` (
  `id` int(11) NOT NULL auto_increment,
  `MailSubject` text NOT NULL,
  `SendDateTime` datetime NOT NULL,
  `recipients_id` int(11) NOT NULL,
  `Send` enum('Prepared','Sent','Failed','PossiblySent','Hardbounced') NOT NULL default 'Prepared',
  `SendResult` text NOT NULL,
  `SendStat_id` int(11) NOT NULL,
  `IsSMS` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `SendStat_id`  (`SendStat_id`),
  UNIQUE `recipients_idSendStat_id` (`recipients_id`, `SendStat_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_GROUPS` (
  -- add
  `Campaigns_id` int(11) NOT NULL default '0',

  `ml_groups_id` int(11) NOT NULL,
  -- change
  -- PRIMARY KEY ( `ml_groups_id` )
  PRIMARY KEY `uniquegroups` (`Campaigns_id`, `ml_groups_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_NOTINGROUPS` (
  -- add
  `Campaigns_id` int(11) NOT NULL default '0',

  `ml_groups_id` int(11) NOT NULL,
  -- change
  -- PRIMARY KEY ( `ml_groups_id` )
  PRIMARY KEY `uniquegroups` (`Campaigns_id`, `ml_groups_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_MTAS` (
  -- add
  `Campaigns_id` int(11) NOT NULL default '0',

  `mtas_id` int(11) NOT NULL,
  `sortorder` int(4) NOT NULL default '0',

  -- change
  -- PRIMARY KEY `mtas_id` (`mtas_id`)
  PRIMARY KEY `uniquemtas` (`Campaigns_id`, `mtas_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_CAMPAIGNLINKS` (
  `id` int(11) NOT NULL auto_increment,

  -- add
  `Campaigns_id` int(11) NOT NULL default '0',

  `IsActive` tinyint(1) NOT NULL default '1',
  `Link` text NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `Link` (`Link`(255)),

  -- add
  KEY `Campaigns_id` (`Campaigns_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_CAMPAIGNTRACKINGOPENINGS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  `Country` varchar(128) default 'UNKNOWN_COUNTRY',
  KEY `SendStat_id` (`SendStat_id`),
  KEY `IP` (`IP`),
  KEY `Country` (`Country`)
);

CREATE TABLE IF NOT EXISTS `TABLE_CAMPAIGNTRACKINGOPENINGSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_CAMPAIGNTRACKINGLINKS` (
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

CREATE TABLE IF NOT EXISTS `TABLE_CAMPAIGNTRACKINGLINKSBYRECIPIENT` (
  `SendStat_id` int(11) NOT NULL,
  `Links_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `ADateTime` datetime default NULL,
  `Member_id` int(11) NOT NULL,
  KEY `Links_id` (`Links_id`),
  KEY `SendStat_id` (`SendStat_id`),
  KEY `Member_id` (`Member_id`)
);


CREATE TABLE IF NOT EXISTS `TABLE_CAMPAIGNTRACKINGUSERAGENTS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `UserAgent` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `UserAgent` (`UserAgent`),
  KEY `IP` (`IP`)
);

CREATE TABLE IF NOT EXISTS `TABLE_CAMPAIGNTRACKINGOSS` (
  `SendStat_id` int(11) NOT NULL,
  `Clicks` int(11) default '1',
  `OS` varchar(255) default NULL,
  `ADateTime` datetime default NULL,
  `IP` varchar(64) default NULL,
  KEY `SendStat_id` (`SendStat_id`),
  KEY `OS` (`OS`),
  KEY `IP` (`IP`)
);
