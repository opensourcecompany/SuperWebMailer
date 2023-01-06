CREATE TABLE IF NOT EXISTS `TABLE_MAILLIST` (
  `id` int(11) NOT NULL auto_increment,
  `IsActive` tinyint(1) default 1,
  `SubscriptionStatus` enum('OptInConfirmationPending','Subscribed','OptOutConfirmationPending') default NULL,
  `LastChangeDate` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DateOfSubscription` datetime default NULL,
  `DateOfOptInConfirmation` datetime default NULL,
  `DateOfUnsubscription` datetime default NULL,
  `PrivacyPolicyAccepted` tinyint(1) default 0,
  `IPOnSubscription` text NOT NULL,
  `IPOnUnsubscription` text NOT NULL,
  `IdentString` varchar(64) default NULL,
  `LastEMailSent` datetime default NULL,
  `BounceStatus` enum('none', 'PermanentlyBounced','TemporarilyBounced') default 'none',
  `SoftbounceCount` int(11) NOT NULL default '0',
  `HardbounceCount` int(11) NOT NULL default '0',
  `u_EMail` varchar(255) NOT NULL,
  `u_EMailFormat` enum('PlainText', 'HTML') default 'HTML',
  `u_CustomerNo` text NOT NULL,
  `u_Firm` text NOT NULL,
  `u_Gender` enum('m', 'w', 'd', 'undefined') default 'undefined',
  `u_Salutation` text NOT NULL,
  `u_Profession` text NOT NULL,
  `u_FirstName` text NOT NULL,
  `u_MiddleName` text NOT NULL,
  `u_LastName` text NOT NULL,
  `u_CellNumber` text NOT NULL,
  `u_Birthday` date NOT NULL,
  `u_MessengerICQ` text NOT NULL,
  `u_MessengerMSN` text NOT NULL,
  `u_MessengerYAHOO` text NOT NULL,
  `u_MessengerAOL` text NOT NULL,
  `u_MessengerOther` text NOT NULL,
  `u_PrivateStreet` text NOT NULL,
  `u_PrivateZIPCode` text NOT NULL,
  `u_PrivateCity` text NOT NULL,
  `u_PrivateState` text NOT NULL,
  `u_PrivateCountry` text NOT NULL,
  `u_PrivateWebsite` text NOT NULL,
  `u_PrivateTelephone` text NOT NULL,
  `u_PrivateFax` text NOT NULL,
  `u_BusinessStreet` text NOT NULL,
  `u_BusinessZIPCode` text NOT NULL,
  `u_BusinessCity` text NOT NULL,
  `u_BusinessState` text NOT NULL,
  `u_BusinessCountry` text NOT NULL,
  `u_BusinessWebsite` text NOT NULL,
  `u_BusinessTelephone` text NOT NULL,
  `u_BusinessFax` text NOT NULL,
  `u_BusinessPosition` text NOT NULL,
  `u_BusinessDepartment` text NOT NULL,
  `u_Comments` mediumtext NOT NULL,
  `u_Username` text NOT NULL,
  `u_Password` text NOT NULL,
  `u_Language` text NOT NULL,
  `u_UserFieldString1` text NOT NULL,
  `u_UserFieldString2` text NOT NULL,
  `u_UserFieldString3` text NOT NULL,
  `u_UserFieldInt1` int(11) default 0,
  `u_UserFieldInt2` int(11) default 0,
  `u_UserFieldInt3` int(11) default 0,
  `u_UserFieldBool1` tinyint(1) default 0,
  `u_UserFieldBool2` tinyint(1) default 0,
  `u_UserFieldBool3` tinyint(1) default 0,
  `u_PersonalizedTracking` tinyint(1) default 1,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `u_EMail` (`u_EMail`),
  KEY `IsActive` (`IsActive`),
  KEY `SubscriptionStatus` (`SubscriptionStatus`),
  KEY `u_LastName` (`u_LastName`(255)),
  KEY `u_FirstName` (`u_FirstName`(255)),
  KEY `u_Salutation` (`u_Salutation`(255)),
  UNIQUE KEY `IdentString` (`IdentString`)
);

CREATE TABLE IF NOT EXISTS `TABLE_GROUPS` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `CreateDate` DATETIME NOT NULL ,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY ( `id` ),
  UNIQUE KEY `Name` (`Name`)
);

CREATE TABLE IF NOT EXISTS `TABLE_MAILLISTTOGROUP` (
  `groups_id` int(11) NOT NULL,
  `Member_id` int(11) NOT NULL,
  KEY `groups_id` (`groups_id`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_LOCALBLOCKLIST` (
  `id` int(11) NOT NULL auto_increment,
  `u_EMail` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `u_EMail` (`u_EMail`)
);

CREATE TABLE IF NOT EXISTS `TABLE_LOCALDOMAINBLOCKLIST` (
  `id` int(11) NOT NULL auto_increment,
  `Domainname` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Domainname` (`Domainname`)
);

CREATE TABLE IF NOT EXISTS `TABLE_STATISTICS` (
  `ActionDate` datetime NOT NULL,
  `Action` enum('OptInConfirmationPending', 'Subscribed', 'OptOutConfirmationPending', 'Unsubscribed','Edited','BlackListed','Bounced','Activated','Deactivated') NOT NULL,
  `AText` varchar(255) NOT NULL,
  `Member_id` int(11),
  KEY `ActionDate` (`ActionDate`),
  KEY `Member_id` (`Member_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_FORMS` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `IsDefault` tinyint(1) NOT NULL default '0',
  `CreateDate` DATETIME NOT NULL ,
  `messages_id` INT NOT NULL DEFAULT '1',
  `Name` varchar(255) NOT NULL,
  `Language` varchar(3) NOT NULL default 'de',
  `ThemesId` int(11) NOT NULL default '1',
  `PrivacyPolicyURL` text default '',
  `PrivacyPolicyAcceptanceAsMandatoryField` tinyint(1) NOT NULL default '0',
  `PrivacyPolicyURLText` mediumtext NOT NULL,
  `UseCaptcha` tinyint(1) NOT NULL default '0',
  `UseReCaptcha` tinyint(1) NOT NULL default '0',
  `UseReCaptchav3` tinyint(1) NOT NULL default '0',
  `PublicReCaptchaKey` text NOT NULL,
  `PrivateReCaptchaKey` text NOT NULL,
  `InternalForm` tinyint(1) NOT NULL default '1',
  `ExternalFormEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `OverrideSubUnsubURL` text NOT NULL,
  `OverrideTrackingURL` text NOT NULL,
  `UserDefinedFormsFolder` text NOT NULL,
  `RequestReasonForUnsubscription` tinyint(1) NOT NULL default '0',
  `ExternalReasonForUnsubscriptionScript` text NOT NULL,
  `fields` mediumtext NOT NULL,
  `GroupsOption` INT NOT NULL default '1',
  `groups_id` INT NOT NULL default '0',
  `GroupsAsCheckBoxes` tinyint(1) NOT NULL default '0',
  `GroupsAsMandatoryField` tinyint(1) NOT NULL default '0',
  `GroupsDescriptionLabel` text NOT NULL,
  `SenderFromName` text NOT NULL,
  `SenderFromAddress` text NOT NULL,
  `ReplyToEMailAddress` text NOT NULL,
  `ReturnPathEMailAddress` text NOT NULL,
  `CcEMailAddresses` text NOT NULL,
  `BCcEMailAddresses` text NOT NULL,
  `SubscribeErrorPage` int(11) NOT NULL default '1',
  `SubscribeAcceptedPage` int(11) NOT NULL default '3',
  `SubscribeConfirmationPage` int(11) NOT NULL default '2',
  `UnsubscribeErrorPage` int(11) NOT NULL default '1',
  `UnsubscribeAcceptedPage` int(11) NOT NULL default '5',
  `UnsubscribeConfirmationPage` int(11) NOT NULL default '4',
  `EditAcceptedPage` int(11) NOT NULL default '6',
  `EditConfirmationPage` int(11) NOT NULL default '7',
  `EditRejectPage` int(11) NOT NULL default '8',
  `EditErrorPage` int(11) NOT NULL default '9',
  `UnsubscribeBridgePage` int(11) NOT NULL default '0',
  `RFUSurveyConfirmationPage` int(11) NOT NULL default '0',
  `OptInConfirmationMailFormat` enum('PlainText','HTML') NOT NULL default 'PlainText',
  `OptInConfirmationMailPriority` enum('Low','Normal','High') NOT NULL default 'Normal',
  `OptInConfirmationMailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `OptInConfirmationMailSubject` text NOT NULL,
  `OptInConfirmationMailPlainText` mediumtext NOT NULL,
  `OptInConfirmationMailHTMLText` mediumtext NOT NULL,
  `OptOutConfirmationMailFormat` enum('PlainText','HTML') NOT NULL default 'PlainText',
  `OptOutConfirmationMailPriority` enum('Low','Normal','High') NOT NULL default 'Normal',
  `OptOutConfirmationMailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `OptOutConfirmationMailSubject` text NOT NULL,
  `OptOutConfirmationMailPlainText` mediumtext NOT NULL,
  `OptOutConfirmationMailHTMLText` mediumtext NOT NULL,
  `EditConfirmationMailFormat` enum('PlainText','HTML') NOT NULL default 'PlainText',
  `EditConfirmationMailPriority` enum('Low','Normal','High') NOT NULL default 'Normal',
  `EditConfirmationMailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `EditConfirmationMailSubject` text NOT NULL,
  `EditConfirmationMailPlainText` mediumtext NOT NULL,
  `EditConfirmationMailHTMLText` mediumtext NOT NULL,
  `SendOptInConfirmedMail` tinyint(1) NOT NULL default '0',
  `OptInConfirmedMailFormat` enum('PlainText','HTML') NOT NULL default 'PlainText',
  `OptInConfirmedMailPriority` enum('Low','Normal','High') NOT NULL default 'Normal',
  `OptInConfirmedMailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `OptInConfirmedMailSubject` text NOT NULL,
  `OptInConfirmedMailPlainText` mediumtext NOT NULL,
  `OptInConfirmedMailHTMLText` mediumtext NOT NULL,
  `OptInConfirmedAttachments` mediumtext NOT NULL,
  `SendOptOutConfirmedMail` tinyint(1) NOT NULL,
  `OptOutConfirmedMailFormat` enum('PlainText','HTML') NOT NULL default 'PlainText',
  `OptOutConfirmedMailPriority` enum('Low','Normal','High') NOT NULL default 'Normal',
  `OptOutConfirmedMailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `OptOutConfirmedMailSubject` text NOT NULL,
  `OptOutConfirmedMailPlainText` mediumtext NOT NULL,
  `OptOutConfirmedMailHTMLText` mediumtext NOT NULL,
  `OptOutConfirmedAttachments` mediumtext NOT NULL,
  `SendEditConfirmedMail` tinyint(1) NOT NULL,
  `EditConfirmedMailFormat` enum('PlainText','HTML') NOT NULL default 'PlainText',
  `EditConfirmedMailPriority` enum('Low','Normal','High') NOT NULL default 'Normal',
  `EditConfirmedMailEncoding` varchar(20) NOT NULL default 'iso-8859-1',
  `EditConfirmedMailSubject` text NOT NULL,
  `EditConfirmedMailPlainText` mediumtext NOT NULL,
  `EditConfirmedMailHTMLText` mediumtext NOT NULL,
  `EditConfirmedAttachments` mediumtext NOT NULL,

  `InfoBarActive` tinyint(1) NOT NULL default '1',
  `InfoBarSchemeColorName` text NOT NULL,
  `InfoBarSrcLanguage` text NOT NULL,
  `InfoBarSpacer` text NOT NULL,
  `InfoBarSupportedTranslationLanguages` mediumtext NOT NULL,
  `InfoBarLinksArray` mediumtext NOT NULL,


  PRIMARY KEY ( `id` ) ,
  KEY ( `Name` ),
  KEY ( `messages_id` ),
  KEY ( `SubscribeErrorPage` ),
  KEY ( `SubscribeAcceptedPage` ),
  KEY ( `SubscribeConfirmationPage` ),
  KEY ( `UnsubscribeErrorPage` ),
  KEY ( `UnsubscribeAcceptedPage` ),
  KEY ( `UnsubscribeConfirmationPage` ),
  KEY ( `EditAcceptedPage` ),
  KEY ( `EditConfirmationPage` )
);

CREATE TABLE IF NOT EXISTS `TABLE_MTAS` (
  `mtas_id` int(11) NOT NULL,
  sortorder int(4) NOT NULL default '0',
  KEY `mtas_id` (`mtas_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_INBOXES` (
  `inboxes_id` int(11) NOT NULL,
  sortorder int(4) NOT NULL default '0',
  KEY `inboxes_id` (`inboxes_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_MAILLOG` (
  `Member_id` int(11) NOT NULL,
  `MailLog` LONGtext NOT NULL,
  PRIMARY KEY ( `Member_id` )
);

CREATE TABLE IF NOT EXISTS `TABLE_EDIT` (
  `Member_id` int(11) NOT NULL,
  `ChangeDate` datetime default NULL,
  `ChangedDetails` text NOT NULL,
  PRIMARY KEY ( `Member_id` )
);

CREATE TABLE IF NOT EXISTS `TABLE_REASONSFORUNSUBSCRIPE` (
  `id` int(11) NOT NULL auto_increment,
  `forms_id` int(11) NOT NULL,
  `Reason` varchar(255) NOT NULL,
  `ReasonType` enum('Radio','Text') NOT NULL default 'Radio',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY ( `id` ),
  KEY `forms_id` (`forms_id`)
);

CREATE TABLE IF NOT EXISTS `TABLE_REASONSFORUNSUBSCRIPESTATISTICS` (
  `ReasonsForUnsubscripe_id` int(11) NOT NULL,
  `VoteDate` datetime default NULL,
  `ReasonText` text NOT NULL,
  KEY `ReasonsForUnsubscripe_id` (`ReasonsForUnsubscripe_id`)
);

