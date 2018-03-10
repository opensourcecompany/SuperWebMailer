CREATE TABLE IF NOT EXISTS `TABLE_CURRENT_SENDTABLE` (
  `id` int(11) NOT NULL auto_increment,
  `ReportSent` tinyint(1) NOT NULL default '0',
  `RecipientsCount` int(11) NOT NULL default '0',
  `SentCountSucc` int(11) NOT NULL default '0',
  `SentCountFailed` int(11) NOT NULL default '0',
  `SentCountPossiblySent` int(11) NOT NULL default '0',
  `StartSendDateTime` datetime NOT NULL,
  `EndSendDateTime` datetime NOT NULL,
  `SendState` enum('Sending','ReSending','Done') NOT NULL default 'Sending',
  `CampaignSendDone` tinyint(1) NOT NULL default '0',
  `LastMember_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_C_STATISTICS` (
  `id` int(11) NOT NULL auto_increment,
  `MailSubject` text NOT NULL,
  `SendDateTime` datetime NOT NULL,
  `recipients_id` int(11) NOT NULL,
  `Send` enum('Prepared','Sent','Failed','PossiblySent') NOT NULL default 'Prepared',
  `SendResult` varchar(255) NOT NULL,
  `SendStat_id` int(11) NOT NULL,
  `IsSMS` tinyint(1) NOT NULL default '1',
  `SMSCosts` float NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `SendStat_id`  (`SendStat_id`),
  UNIQUE `recipients_idSendStat_id` (`recipients_id`, `SendStat_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_GROUPS` (
  `ml_groups_id` int(11) NOT NULL,
  PRIMARY KEY ( `ml_groups_id` )
);

CREATE TABLE IF NOT EXISTS `TABLE_NOTINGROUPS` (
  `ml_groups_id` int(11) NOT NULL,
  PRIMARY KEY ( `ml_groups_id` )
);

