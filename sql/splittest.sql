CREATE TABLE IF NOT EXISTS `TABLE_SPLITTEST_CAMPAIGNS` (
  `id` int(11) NOT NULL auto_increment,
  `Campaigns_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `Campaigns_id`  (`Campaigns_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_SPLITTEST_CURRENT_SENDTABLE` (
  `id` int(11) NOT NULL auto_increment,
  `MembersTableName` varchar(255) NOT NULL,
  `Members_Prepared` tinyint(1) NOT NULL default '0',

  `RandomMembers_Prepared` tinyint(1) NOT NULL default '0',
  `RandomMembersTableName` varchar(255) NOT NULL,

  `RecipientsCount` int(11) NOT NULL default '0',
  `RecipientsCountForSplitTest` int(11) NOT NULL default '0',
  `CampaignsCount` int(11) NOT NULL default '0',
  `StartSendDateTime` datetime NOT NULL,
  `EndSendDateTime` datetime NOT NULL,
  `SendState` enum('Preparing','Sending','Waiting','PreparingWinnerCampaign','SendingWinnerCampaign','Done','ReSending') NOT NULL default 'Preparing',
  `SplitTestSendDone` tinyint(1) NOT NULL default '0',
  `LastMember_id` int(11) NOT NULL default '0',
  `WinnerType` enum('WinnerOpens','WinnerClicks') NOT NULL default 'WinnerClicks',
  `TestType` enum('TestSendToAllMembers','TestSendToListPercentage') NOT NULL default 'TestSendToAllMembers',
  `ListPercentage` int(11) NOT NULL default 10,
  `WinnerCampaignsMaxClicks` int(11) NOT NULL default '0',
  `WinnerRecipientsCount` int(11) NOT NULL default '0',
  `WinnerCampaignSendDone` tinyint(1) NOT NULL default '0',
  `WinnerCampaigns_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_SPLITTEST_CURRENT_SENDTABLE_TO_CAMPAIGNS_SENDTABLE` (
  `SplitTestSendStat_id` int(11) NOT NULL,
  `Campaigns_id` int(11) NOT NULL,
  `CampaignsSendStat_id` int(11) NOT NULL,
  `RecipientsCount` int(11) NOT NULL default '0',
  KEY `SplitTestSendStat_id`  (`SplitTestSendStat_id`),
  KEY `Campaigns_id`  (`Campaigns_id`),
  KEY `CampaignsSendStat_id`  (`CampaignsSendStat_id`)
);
