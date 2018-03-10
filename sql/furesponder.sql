CREATE TABLE IF NOT EXISTS `TABLE_FUMAILS` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `CreateDate` DATETIME NOT NULL ,
  `sort_order` int(11) NOT NULL default '0',
  `Name` varchar(255) NOT NULL,
  `SendInterval` int(11) NOT NULL,
  `SendIntervalType` enum('Minute','Hour','Day','Week','Month','Quarter','Year') NOT NULL default 'Minute',
  `FirstRecipientsAction` enum('Subscribed','CampaignOpened','CampaignLinkClicked','CampaignSpecialLinkClicked') NOT NULL default 'Subscribed',
  `FirstRecipientsActionCampaign_id` int(11) NOT NULL default '0',
  `FirstRecipientsActionCampaignLink_id` int(11) NOT NULL default '0',
  `LastRecipientsAction` enum('WasSent','WasOpened','HasLinkClicked','HasSpecialLinkClicked') NOT NULL default 'WasSent',
  `LastRecipientsActionLink_id` int(11) NOT NULL default '0',
  `SenderFromName` varchar(255) NOT NULL,
  `SenderFromAddress` varchar(255) NOT NULL,
  `ReplyToEMailAddress` varchar(255) NOT NULL,
  `ReturnPathEMailAddress` varchar(255) NOT NULL,
  `UserMailHeaderFields` text,
  `CcEMailAddresses` text NOT NULL,
  `BCcEMailAddresses` text NOT NULL,
  `ReturnReceipt` tinyint(1) NOT NULL default '0',
  `BCCSending` tinyint(1) NOT NULL default '0',
  `BCCRecipientsCount` tinyint(4) NOT NULL default '10',
  `BCCSenderEMailAddress` varchar(255) NOT NULL,
  `MailFormat` enum('PlainText','HTML','Multipart') NOT NULL default 'Multipart',
  `MailPriority` enum('Low','Normal','High') NOT NULL default 'Normal',
  `MailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `MailSubject` varchar(255) NOT NULL,
  `MailPreHeaderText` varchar(255) NOT NULL,
  `MailPlainText` mediumtext NOT NULL,
  `MailHTMLText` mediumtext NOT NULL,
  `Attachments` mediumtext NOT NULL,
  `PersAttachments` mediumtext NOT NULL,
  `SendEMailWithoutPersAttachment` tinyint(1) NOT NULL default '1',
  `Caching` tinyint(1) NOT NULL default '1',
  `LinksTableName` varchar(255) NOT NULL,
  `TrackingOpeningsTableName` varchar(255) NOT NULL,
  `TrackingOpeningsByRecipientTableName` varchar(255) NOT NULL,
  `TrackingLinksTableName` varchar(255) NOT NULL,
  `TrackingLinksByRecipientTableName` varchar(255) NOT NULL,
  `TrackingUserAgentsTableName` varchar(255) NOT NULL,
  `TrackingOSsTableName` varchar(255) NOT NULL,
  `TrackEMailOpeningsImageURL` varchar(255) NOT NULL,
  `TrackEMailOpeningsByRecipientImageURL` varchar(255) NOT NULL,
  PRIMARY KEY ( `id` ),
  KEY ( `sort_order` )
);

CREATE TABLE IF NOT EXISTS `TABLE_ML_FU_REFERENCE` (
  `Member_id` int(11) NOT NULL,
  `LastSending` datetime NOT NULL,
  `NextFollowUpID` int(11) default '0',
  `OnFollowUpDoneActionDone` tinyint(1) NOT NULL default '0',
  PRIMARY KEY ( `Member_id` ),
  KEY ( `LastSending` ),
  KEY ( `NextFollowUpID` )
);

CREATE TABLE IF NOT EXISTS `TABLE_FU_STATISTICS` (
  `id` int(11) NOT NULL auto_increment,
  `MailSubject` varchar(255) NOT NULL,
  `SendDateTime` datetime NOT NULL,
  `recipients_id` int(11) NOT NULL,
  `fumails_id` int(11) NOT NULL,
  `Send` enum('Prepared','Sent','Failed','PossiblySent') NOT NULL default 'Prepared',
  `SendResult` varchar(255) NOT NULL,
  `IsSMS` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY ( `fumails_id` ),
  KEY ( `recipients_id` ),
  UNIQUE `recipients_idfumails_id` (`recipients_id`, `fumails_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_FU_GROUPS` (
  `ml_groups_id` int(11) NOT NULL,
  PRIMARY KEY ( `ml_groups_id` )
);

CREATE TABLE IF NOT EXISTS `TABLE_FU_NOTINGROUPS` (
  `ml_groups_id` int(11) NOT NULL,
  PRIMARY KEY ( `ml_groups_id` )
);
