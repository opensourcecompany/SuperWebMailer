CREATE TABLE IF NOT EXISTS `sml_auth_options` (
  `id` int(11) NOT NULL auto_increment,
  `AuthType` enum('db','db2fa','ldap') NOT NULL default 'db',

  `dbUserPrivileges` mediumtext NOT NULL,
  `dbUserMailingListAccess` mediumtext NOT NULL,

  `ldap_default_db_owner_admin_username_dn` varchar(255) NOT NULL,
  `ldap_default_db_owner_admin_username_id` int(11) NOT NULL default '0',

  `ldap_address` varchar(255) NOT NULL,
  `ldap_port` int(2) NOT NULL default '389',
  `ldap_ssl` tinyint(1) NOT NULL default '0',
  `ldap_tls` tinyint(1) NOT NULL default '0',
  `ldap_base_dn` varchar(255) NOT NULL,
  `ldap_protocol_version` int(2) NOT NULL default '3',
  `ldap_external_auth_username` varchar(255) NOT NULL,
  `ldap_external_auth_password` varchar(255) NOT NULL,

  `ldap_field_uid` varchar(255) NOT NULL default 'uid',
  `ldap_field_email` varchar(255) NOT NULL default 'mail',
  `ldap_field_owner_admin` varchar(255) NOT NULL default 'manager',
  `ldap_field_useraccountcontrol` varchar(255) NOT NULL default 'userAccountControl',
  `ldap_useraccountcontrol_useractivevalue` varchar(255) NOT NULL default '512',
  `ldap_user_filter` varchar(255) NOT NULL,

  `2FADiscrepancy` int(1) NOT NULL default '1',
  `2FAForSuperAdmin` tinyint(1) NOT NULL default '0',

  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `sml_failed_logins` (
  `id` int(11) NOT NULL auto_increment,
  `LoginDateTime` datetime NOT NULL,
  `ip` varchar(64) NOT NULL,
  `LoginCount` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `LoginDateTime` (`LoginDateTime`),
  UNIQUE KEY `ip` (`ip`)
);

CREATE TABLE IF NOT EXISTS `sml_2fa_blacklist` (
  `users_id` int(11) NOT NULL,
  `LoginDateTime` datetime NOT NULL,
  `2FACode` varchar(64) NOT NULL,
  UNIQUE KEY `unique2FACodeUsers_id` (`users_id`, `2FACode`),
  KEY `LoginDateTime` (`LoginDateTime`)
);

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_cronlog`
--

CREATE TABLE IF NOT EXISTS `sml_cronlog` (
  `id` int(11) NOT NULL auto_increment,
  `cronoptions_id` int(11) NOT NULL,
  `StartDateTime` datetime NOT NULL,
  `EndDateTime` datetime NOT NULL,
  `Result` int(11) NOT NULL,
  `ResultText` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cronoptions_id` (`cronoptions_id`),
  KEY `StartDateTime` (`StartDateTime`),
  KEY `EndDateTime` (`EndDateTime`)
);


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_cronoptions`
--

CREATE TABLE IF NOT EXISTS `sml_cronoptions` (
  `id` int(11) NOT NULL auto_increment,
  `JobType` enum('OptInOptOutExpirationCheck','CronLogCleanUp','MailingListStatCleanUp','AutoImport','ResponderStatCleanUp','TrackingStatCleanUp','BounceChecking','AutoresponderChecking','FollowUpResponderChecking','BirthdayResponderChecking','RSS2EMailResponderChecking','EventResponderChecking','SendEngineChecking','CampaignChecking','SplitTestChecking','SMSCampaignChecking','DistribListChecking') NOT NULL,
  `JobEnabled` tinyint(1) NOT NULL default '1',
  `JobWorkingInterval` int(11) NOT NULL,
  `JobWorkingIntervalType` enum('Second','Minute','Hour','Day','Month') NOT NULL default 'Minute',
  `LastExecution` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `JobType` (`JobType`)
);

--
-- Daten fuer Tabelle `sml_cronoptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_emailoptions`
--

CREATE TABLE IF NOT EXISTS `sml_emailoptions` (
  `id` int(11) NOT NULL,
  `CRLF` enum('auto','CRLF','CR','LF') NOT NULL default 'auto',
  `Head_Encoding` enum('auto','quoted-printable','base64') NOT NULL default 'auto',
  `Text_Encoding` enum('auto','7bit','8bit','base64','quoted-printable') NOT NULL default 'auto',
  `HTML_Encoding` enum('auto','7bit','8bit','base64','quoted-printable') NOT NULL default 'auto',
  `Attachment_Encoding` enum('auto','base64') NOT NULL default 'auto',
  `XMailer` varchar(128) NOT NULL,
  `AddUniqueIdHeaderField` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
);

--
-- Daten fuer Tabelle `sml_emailoptions`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_fieldnames`
--

CREATE TABLE IF NOT EXISTS `sml_fieldnames` (
  `id` int(11) NOT NULL auto_increment,
  `fieldname` varchar(64) NOT NULL,
  `text` varchar(128) NOT NULL,
  `language` varchar(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fieldname` (`fieldname`),
  KEY `language` (`language`),
  UNIQUE KEY `uniqueFieldNames` (`fieldname`, `text`, `language`)
);

--
-- Daten fuer Tabelle `sml_fieldnames`
--

INSERT INTO `sml_fieldnames` (`id`, `fieldname`, `text`, `language`) VALUES
(0, 'u_EMail', 'E-Mail-Adresse', 'de'),
(0, 'u_EMailFormat', 'E-Mail-Format', 'de'),
(0, 'u_CustomerNo', 'Kundennummer', 'de'),
(0, 'u_Firm', 'Firma', 'de'),
(0, 'u_Gender', 'Geschlecht', 'de'),
(0, 'u_Salutation', 'Anrede', 'de'),
(0, 'u_Profession', 'Akademischer Grad', 'de'),
(0, 'u_FirstName', 'Vorname', 'de'),
(0, 'u_MiddleName', '2. Vorname', 'de'),
(0, 'u_LastName', 'Nachname', 'de'),
(0, 'u_CellNumber', 'Mobilfunknummer', 'de'),
(0, 'u_Birthday', 'Geburtsdatum', 'de'),
(0, 'u_MessengerICQ', 'Messenger ICQ', 'de'),
(0, 'u_MessengerMSN', 'Messenger MSN', 'de'),
(0, 'u_MessengerYAHOO', 'Messenger YAHOO', 'de'),
(0, 'u_MessengerAOL', 'Messenger AOL', 'de'),
(0, 'u_MessengerOther', 'Messenger anderer', 'de'),
(0, 'u_PrivateStreet', 'Stra&szlig;e', 'de'),
(0, 'u_PrivateZIPCode', 'PLZ', 'de'),
(0, 'u_PrivateCity', 'Stadt', 'de'),
(0, 'u_PrivateState', 'Bundesland', 'de'),
(0, 'u_PrivateCountry', 'Land', 'de'),
(0, 'u_PrivateWebsite', 'Webseite', 'de'),
(0, 'u_PrivateTelephone', 'Telefonnummer', 'de'),
(0, 'u_PrivateFax', 'Faxnummer', 'de'),
(0, 'u_BusinessStreet', 'Stra&szlig;e gesch&auml;ftlich', 'de'),
(0, 'u_BusinessZIPCode', 'PLZ gesch&auml;ftlich', 'de'),
(0, 'u_BusinessCity', 'Stadt gesch&auml;ftlich', 'de'),
(0, 'u_BusinessState', 'Bundesland gesch&auml;ftlich', 'de'),
(0, 'u_BusinessCountry', 'Land gesch&auml;ftlich', 'de'),
(0, 'u_BusinessWebsite', 'Webseite gesch&auml;ftlich', 'de'),
(0, 'u_BusinessTelephone', 'Telefonnummer', 'de'),
(0, 'u_BusinessFax', 'Faxnummer', 'de'),
(0, 'u_BusinessPosition', 'Position', 'de'),
(0, 'u_BusinessDepartment', 'Abteilung', 'de'),
(0, 'u_Comments', 'Kommentare', 'de'),
(0, 'u_Username', 'Benutzername', 'de'),
(0, 'u_Password', 'Kennwort', 'de'),
(0, 'u_Language', 'Sprache', 'de'),
(0, 'u_UserFieldString1', 'Zeichenkette 1', 'de'),
(0, 'u_UserFieldString2', 'Zeichenkette 2', 'de'),
(0, 'u_UserFieldString3', 'Zeichenkette 3', 'de'),
(0, 'u_UserFieldInt1', 'Ganzzahl 1', 'de'),
(0, 'u_UserFieldInt2', 'Ganzzahl 2', 'de'),
(0, 'u_UserFieldInt3', 'Ganzzahl 3', 'de'),
(0, 'u_UserFieldBool1', 'Logisches Feld 1', 'de'),
(0, 'u_UserFieldBool2', 'Logisches Feld 2', 'de'),
(0, 'u_UserFieldBool3', 'Logisches Feld 3', 'de'),
(0, 'u_PersonalizedTracking', 'Personalisiertes Tracking erlaubt', 'de'),
(0, 'u_EMail', 'Email address', 'en'),
(0, 'u_EMailFormat', 'Email format', 'en'),
(0, 'u_CustomerNo', 'Customer number', 'en'),
(0, 'u_Firm', 'Firm', 'en'),
(0, 'u_Gender', 'Gender', 'en'),
(0, 'u_Salutation', 'Salutation', 'en'),
(0, 'u_Profession', 'Profession', 'en'),
(0, 'u_FirstName', 'First name', 'en'),
(0, 'u_MiddleName', 'Middle name', 'en'),
(0, 'u_LastName', 'Last name', 'en'),
(0, 'u_CellNumber', 'Cell number', 'en'),
(0, 'u_Birthday', 'Date of Birth', 'en'),
(0, 'u_MessengerICQ', 'Messenger ICQ', 'en'),
(0, 'u_MessengerMSN', 'Messenger MSN', 'en'),
(0, 'u_MessengerYAHOO', 'Messenger YAHOO', 'en'),
(0, 'u_MessengerAOL', 'Messenger AOL', 'en'),
(0, 'u_MessengerOther', 'Messenger other', 'en'),
(0, 'u_PrivateStreet', 'Street', 'en'),
(0, 'u_PrivateZIPCode', 'ZIP code', 'en'),
(0, 'u_PrivateCity', 'City', 'en'),
(0, 'u_PrivateState', 'State', 'en'),
(0, 'u_PrivateCountry', 'Country', 'en'),
(0, 'u_PrivateWebsite', 'Webpage', 'en'),
(0, 'u_PrivateTelephone', 'Phone', 'en'),
(0, 'u_PrivateFax', 'Fax', 'en'),
(0, 'u_BusinessStreet', 'Street business', 'en'),
(0, 'u_BusinessZIPCode', 'ZIP code business', 'en'),
(0, 'u_BusinessCity', 'City business', 'en'),
(0, 'u_BusinessState', 'State business', 'en'),
(0, 'u_BusinessCountry', 'Country business', 'en'),
(0, 'u_BusinessWebsite', 'Webpage business', 'en'),
(0, 'u_BusinessTelephone', 'Phone number', 'en'),
(0, 'u_BusinessFax', 'Fax', 'en'),
(0, 'u_BusinessPosition', 'Position', 'en'),
(0, 'u_BusinessDepartment', 'Department', 'en'),
(0, 'u_Comments', 'Comments', 'en'),
(0, 'u_Username', 'Username', 'en'),
(0, 'u_Password', 'Password', 'en'),
(0, 'u_Language', 'Language', 'en'),
(0, 'u_UserFieldString1', 'String 1', 'en'),
(0, 'u_UserFieldString2', 'String 2', 'en'),
(0, 'u_UserFieldString3', 'String 3', 'en'),
(0, 'u_UserFieldInt1', 'Integer 1', 'en'),
(0, 'u_UserFieldInt2', 'Integer 2', 'en'),
(0, 'u_UserFieldInt3', 'Integer 3', 'en'),
(0, 'u_UserFieldBool1', 'Boolean field 1', 'en'),
(0, 'u_UserFieldBool2', 'Boolean field 2', 'en'),
(0, 'u_UserFieldBool3', 'Boolean field 3', 'en'),
(0, 'u_PersonalizedTracking', 'Personalized tracking allowed', 'en');

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_languages`
--

CREATE TABLE IF NOT EXISTS `sml_languages` (
  `id` int(11) NOT NULL auto_increment,
  `Language` varchar(4) NOT NULL default 'de',
  `Text` varchar(128) NOT NULL default 'Deutsch',
  PRIMARY KEY  (`id`)
);

--
-- Daten fuer Tabelle `sml_languages`
--

INSERT INTO `sml_languages` (`id`, `Language`, `Text`) VALUES
(1, 'de', 'Deutsch'),
(2, 'en', 'English');

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_maillists`
--

CREATE TABLE IF NOT EXISTS `sml_maillists` (
  `id` int(11) NOT NULL auto_increment,
  `CreateDate` datetime NOT NULL,
  `users_id` int(11) NOT NULL default '0',
  `SubscriptionUnsubscription` enum('Allowed','SubscribeOnly','UnsubscribeOnly', 'Denied') NOT NULL default 'Allowed',
  `MaillistTableName` text NOT NULL,
  `GroupsTableName` text NOT NULL,
  `MailListToGroupsTableName` text NOT NULL,
  `LocalBlocklistTableName` text NOT NULL,
  `LocalDomainBlocklistTableName` text NOT NULL,
  `StatisticsTableName` text NOT NULL,
  `FormsTableName` text NOT NULL,
  `MTAsTableName` text NOT NULL,
  `InboxesTableName` text NOT NULL,
  `MailLogTableName` text NOT NULL,
  `EditTableName` text NOT NULL,
  `ReasonsForUnsubscripeTableName` text NOT NULL,
  `ReasonsForUnsubscripeStatisticsTableName` text NOT NULL,
  `forms_id` int(11) NOT NULL default '1',
  `ExternalBounceScript` text NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Description` text NOT NULL,
  `SenderFromName` text NOT NULL,
  `SenderFromAddress` text NOT NULL,
  `ReplyToEMailAddress` text NOT NULL,
  `CcEMailAddresses` text NOT NULL,
  `BCcEMailAddresses` text NOT NULL,
  `ReturnPathEMailAddress` text NOT NULL,
  `AllowOverrideSenderEMailAddressesWhileMailCreating` tinyint(1) NOT NULL default '1',
  `SendEMailToAdminOnOptIn` tinyint(1) NOT NULL default '0',
  `SendEMailToAdminOnOptOut` tinyint(1) NOT NULL default '0',
  `SendEMailToEMailAddressOnOptIn` tinyint(1) NOT NULL default '0',
  `SendEMailToEMailAddressOnOptOut` tinyint(1) NOT NULL default '0',
  `EMailAddressOnOptInEMailAddress` text NOT NULL,
  `EMailAddressOnOptOutEMailAddress` text NOT NULL,
  `SubscriptionType` enum('SingleOptIn','DoubleOptIn') NOT NULL,
  `UnsubscriptionType` enum('SingleOptOut','DoubleOptOut') NOT NULL,
  `SubscriptionExpirationDays` int(11) NOT NULL default '30',
  `UnsubscriptionExpirationDays` int(11) NOT NULL default '30',
  `AddUnsubscribersToLocalBlacklist` tinyint(1) NOT NULL default '0',
  `AddUnsubscribersToGlobalBlacklist` tinyint(1) NOT NULL default '0',
  `OnSubscribeAlsoAddToMailList` int(11) NOT NULL default '0',
  `OnSubscribeAlsoRemoveFromMailList` int(11) NOT NULL default '0',
  `OnUnsubscribeAlsoAddToMailList` int(11) NOT NULL default '0',
  `OnUnsubscribeAlsoRemoveFromMailList` int(11) NOT NULL default '0',
  `ExternalSubscriptionScript` text NOT NULL,
  `ExternalUnsubscriptionScript` text NOT NULL,
  `ExternalEditScript` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `users_id` (`users_id`),
  KEY `Name` (`Name`),
  KEY `forms_id` (`forms_id`)
);
--
-- Daten fuer Tabelle `sml_maillists`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_maillists_users`
--

CREATE TABLE IF NOT EXISTS `sml_maillists_users` (
  `users_id` int(11) NOT NULL,
  `maillists_id` int(11) NOT NULL,
  KEY `users_id` (`users_id`,`maillists_id`)
);

--
-- Daten fuer Tabelle `sml_maillists_users`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_options`
--

CREATE TABLE IF NOT EXISTS `sml_options` (
  `id` int(11) NOT NULL auto_increment,
  `ScriptVersion` varchar(64) NOT NULL,
  `CronJobLock` tinyint(1) NOT NULL default '0',
  `CronJobLockTime` datetime NOT NULL,
  `ProductLogoURL` varchar(255) NOT NULL,
  `CronCleanUpDays` int(11) NOT NULL default '10',
  `MailingListStatCleanUpDays` int(11) NOT NULL default '10',
  `ResponderStatCleanUpDays` int(11) NOT NULL default '10',
  `TrackingStatCleanUpDays` int(11) NOT NULL default '180',
  `Dashboard` blob NOT NULL,
  `ECGListCheck` tinyint(1) NOT NULL default '0',
  `RemoveUnknownMailsAndSoftbounces` tinyint(1) NOT NULL default '0',
  `MailLoggerEnabled` tinyint(1) NOT NULL default '1',
  `HardbounceCount` int(11) NOT NULL default '3',
  `BounceEMailCount` int(11) NOT NULL default '30',
  `RemoveToOftenBouncedRecipients` tinyint(1) NOT NULL default '0',
  `AddBouncedRecipientsToLocalBlocklist` tinyint(1) NOT NULL default '0',
  `AddBouncedRecipientsToGlobalBlocklist` tinyint(1) NOT NULL default '0',
  `OptionsCronJobOptionsOnlyAsSuperAdmin` tinyint(1) NOT NULL default '0',
  `SendEngineRetryCount` int(11) NOT NULL default '3',
  `SendEngineMaxEMailsToSend` int(11) NOT NULL default '100',
  `SendEngineFIFO` tinyint(1) NOT NULL default '1',
  `SendEngineBooster` tinyint(1) NOT NULL default '0',
  `SpamTestExternal` tinyint(1) NOT NULL default '0',
  `spamassassinPath` varchar(255) NOT NULL default 'spamassassin',
  `spamassassinParameters` varchar(255) NOT NULL default '-t -L -x',
  `SpamTestExternalURL` varchar(255) NOT NULL,
  `GoogleDeveloperPublicKey` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
);

--
-- Daten fuer Tabelle `sml_options`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_themes`
--

CREATE TABLE IF NOT EXISTS `sml_themes` (
  `id` int(11) NOT NULL auto_increment,
  `Theme` varchar(64) NOT NULL,
  `Text` varchar(128) NOT NULL default 'Standard',
  PRIMARY KEY  (`id`)
);

--
-- Daten fuer Tabelle `sml_themes`
--

INSERT INTO `sml_themes` (`id`, `Theme`, `Text`) VALUES
(1, 'default', 'Standard');

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_users`
--

CREATE TABLE IF NOT EXISTS `sml_users` (
  `id` bigint(20) NOT NULL auto_increment,
  `IsActive` tinyint(1) NOT NULL default '1',
  `Username` varchar(255) NOT NULL,
  `EMail` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `2FASecret` text NOT NULL,
  `UserType` enum('SuperAdmin','Admin','User','Guest') NOT NULL,
  `apikey` text NOT NULL,

  `NewsletterTemplatesImported` tinyint(1) NOT NULL default '0',

  `AccountType` enum('Unlimited','Limited','Payed') NOT NULL DEFAULT 'Unlimited',
  `AccountTypeLimitedMailCountLimited` int(11) unsigned NOT NULL default '1000',
  `AccountTypeLimitedCurrentMonth` int(1) NOT NULL default '1',
  `AccountTypeLimitedCurrentMailCount` int(11) unsigned NOT NULL default '0',

  `LastLogin` datetime NOT NULL,

  `LimitSubUnsubScripts` enum('Unlimited','Limited') NOT NULL DEFAULT 'Unlimited',
  `LimitSubUnsubScriptsLimitedRequests` int(11) NOT NULL default '10',
  `LimitSubUnsubScriptsLimitedRequestsInterval` enum('Hour','Day') NOT NULL DEFAULT 'Hour',
  `LimitSubUnsubScriptsTableName` text NOT NULL,

  `Language` varchar(3) NOT NULL default 'de',
  `ThemesId` int(11) NOT NULL default '1',
  `FirstName` text NOT NULL,
  `LastName` text NOT NULL,

  `Salutation` text NOT NULL,
  `VATID` text NOT NULL,
  `Firm` text NOT NULL,
  `Street` text NOT NULL,
  `ZIPCode` text NOT NULL,
  `City` text NOT NULL,
  `State_` text NOT NULL,
  `Country` text NOT NULL,
  `Phone` text NOT NULL,
  `Fax` text NOT NULL,
  `CellPhone` text NOT NULL,
  `Website` text NOT NULL,
  `PrivacyPolicyURL` text NOT NULL,
  `Reference` tinyint(1) NOT NULL default '0',
  `ReferenceLogo` text NOT NULL,
  `TermsOfUseAccepted` tinyint(1) NOT NULL default '0',

  `SHOW_LOGGEDINUSER` tinyint(1) NOT NULL default '1',
  `SHOW_SUPPORTLINKS` tinyint(1) NOT NULL default '1',
  `SHOW_SHOWCOPYRIGHT` tinyint(1) NOT NULL default '1',
  `SHOW_PRODUCTVERSION` tinyint(1) NOT NULL default '1',
  `SHOW_TOOLTIPS` tinyint(1) NOT NULL default '1',
  `ProductLogoURL` text NOT NULL,
  `GlobalBlockListTableName` text NOT NULL,
  `GlobalDomainBlockListTableName` text NOT NULL,
  `FunctionsTableName` text NOT NULL,
  `TargetGroupsTableName` text NOT NULL,

  `TextBlocksTableName` text NOT NULL,
  `MessagesTableName` text NOT NULL,
  `MTAsTableName` text NOT NULL,
  `PagesTableName` text NOT NULL,
  `TemplatesTableName` text NOT NULL,
  `TemplatesToUsersTableName` text NOT NULL,
  `InboxesTableName` text NOT NULL,
  `AutoImportsTableName` text NOT NULL,
  `NewsletterArchivesTableName` text NOT NULL,
  `CampaignsTableName` text NOT NULL,

   -- new tables
  `CampaignsCurrentSendTableName` text NOT NULL,
  `CampaignsCurrentUsedMTAsTableName` text NOT NULL,
  `CampaignsArchiveTableName` text NOT NULL,
  `CampaignsGroupsTableName` text NOT NULL,
  `CampaignsNotInGroupsTableName` text NOT NULL,
  `CampaignsMTAsTableName` text NOT NULL,
  `CampaignsRStatisticsTableName` text NOT NULL,
  `CampaignsLinksTableName` text NOT NULL,
  `CampaignsTrackingOpeningsTableName` text NOT NULL,
  `CampaignsTrackingOpeningsByRecipientTableName` text NOT NULL,
  `CampaignsTrackingLinksTableName` text NOT NULL,
  `CampaignsTrackingLinksByRecipientTableName` text NOT NULL,
  `CampaignsTrackingUserAgentsTableName` text NOT NULL,
  `CampaignsTrackingOSsTableName` text NOT NULL,
   -- new tables /

  `SplitTestsTableName` text NOT NULL,
  `BirthdayMailsTableName` text NOT NULL,
  `BirthdayMailsStatisticsTableName` text NOT NULL,
  `AutoresponderMailsTableName` text NOT NULL,
  `AutoresponderStatisticsTableName` text NOT NULL,
  `FollowUpMailsTableName` text NOT NULL,
  `EventMailsTableName` text NOT NULL,
  `EventMailsStatisticsTableName` text NOT NULL,

  `RSS2EMailMailsTableName` text NOT NULL,
  `RSS2EMailMailsStatisticsTableName` text NOT NULL,

  `SMSCampaignsTableName` text NOT NULL,

  `DistributionListsTableName` text NOT NULL,
  `DistributionListsEntriesTableName` text NOT NULL,

  `MailsSentTableName` text NOT NULL,

  `PrivilegeMailingListCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeMailingListEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeMailingListRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeMailingListBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeMailingListUsersEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeRecipientCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeRecipientEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeRecipientRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeRecipientBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeLocalBlockListRecipientCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeLocalBlockListRecipientEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeLocalBlockListRecipientRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeLocalBlockListRecipientBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeGlobalBlockListRecipientCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeGlobalBlockListRecipientEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeGlobalBlockListRecipientRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeGlobalBlockListRecipientBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeImportBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeExportBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeFormCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeFormEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeFormRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeFormBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeFunctionCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeFunctionEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeFunctionRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeFunctionBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeTargetGroupsCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeTargetGroupsEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeTargetGroupsRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeTargetGroupsBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegePageCreate` tinyint(1) NOT NULL default '1',
  `PrivilegePageEdit` tinyint(1) NOT NULL default '1',
  `PrivilegePageRemove` tinyint(1) NOT NULL default '1',
  `PrivilegePageBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeMessageCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeMessageEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeMessageRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeMessageBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeMTACreate` tinyint(1) NOT NULL default '1',
  `PrivilegeMTAEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeMTARemove` tinyint(1) NOT NULL default '1',
  `PrivilegeMTABrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeInboxCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeInboxEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeInboxRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeInboxBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeMLSubUnsubStatBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeAllMLStatBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeMailsSentStatBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeUserCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeUserEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeUserRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeUserBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeOptionsEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeBrandingEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeDbRepair` tinyint(1) NOT NULL default '1',
  `PrivilegeSystemTest` tinyint(1) NOT NULL default '1',
  `PrivilegeViewProcessLog` tinyint(1) NOT NULL default '1',
  `PrivilegeCron` tinyint(1) NOT NULL default '1',
  `PrivilegeOnlineUpdate` tinyint(1) NOT NULL default '1',

  `PrivilegeLocalMessageCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeLocalMessageRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeLocalMessageBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeAutoImportCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeAutoImportEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeAutoImportRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeAutoImportBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeNewsletterArchiveCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeNewsletterArchiveEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeNewsletterArchiveRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeNewsletterArchiveBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeTemplateCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeTemplateEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeTemplateRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeTemplateBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeAutoresponderCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeAutoresponderEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeAutoresponderRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeAutoresponderBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeViewAutoresponderLog` tinyint(1) NOT NULL default '1',
  `PrivilegeViewAutoresponderTrackingStat` tinyint(1) NOT NULL default '1',

  `PrivilegeFUResponderCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeFUResponderEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeFUResponderRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeFUResponderBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeViewFUResponderLog` tinyint(1) NOT NULL default '1',
  `PrivilegeViewFUResponderTrackingStat` tinyint(1) NOT NULL default '1',

  `PrivilegeFUMMailsCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeFUMMailsEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeFUMMailsRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeFUMMailsBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeBirthdayMailsCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeBirthdayMailsEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeBirthdayMailsRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeBirthdayMailsBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeViewBirthdayMailsLog` tinyint(1) NOT NULL default '1',
  `PrivilegeViewBirthdayMailsTrackingStat` tinyint(1) NOT NULL default '1',

  `PrivilegeRSS2EMailMailsCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeRSS2EMailMailsEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeRSS2EMailMailsRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeRSS2EMailMailsBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeViewRSS2EMailMailsLog` tinyint(1) NOT NULL default '1',
  `PrivilegeViewRSS2EMailMailsTrackingStat` tinyint(1) NOT NULL default '1',

  `PrivilegeEventMailsCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeEventMailsEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeEventMailsRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeEventMailsBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeViewEventMailsLog` tinyint(1) NOT NULL default '1',
  `PrivilegeViewEventMailsTrackingStat` tinyint(1) NOT NULL default '1',

  `PrivilegeCampaignCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeCampaignEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeCampaignRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeCampaignBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeCampaignSending` tinyint(1) NOT NULL default '1',
  `PrivilegeViewCampaignLog` tinyint(1) NOT NULL default '1',
  `PrivilegeViewCampaignTrackingStat` tinyint(1) NOT NULL default '1',

  `PrivilegeSMSCampaignCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeSMSCampaignEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeSMSCampaignRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeSMSCampaignBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeSMSCampaignSending` tinyint(1) NOT NULL default '1',
  `PrivilegeViewSMSCampaignLog` tinyint(1) NOT NULL default '1',

  `PrivilegeDistribListCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeDistribListEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeDistribListRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeDistribListBrowse` tinyint(1) NOT NULL default '1',
  `PrivilegeViewDistribListLog` tinyint(1) NOT NULL default '1',
  `PrivilegeViewDistribListTrackingStat` tinyint(1) NOT NULL default '1',

  `PrivilegeDistribListEntriesRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeDistribListEntriesBrowse` tinyint(1) NOT NULL default '1',

  `PrivilegeTextBlockCreate` tinyint(1) NOT NULL default '1',
  `PrivilegeTextBlockEdit` tinyint(1) NOT NULL default '1',
  `PrivilegeTextBlockRemove` tinyint(1) NOT NULL default '1',
  `PrivilegeTextBlockBrowse` tinyint(1) NOT NULL default '1',

  `BrowseMailinglistFilter` text NOT NULL,
  `BrowseRecipientsFilter` text NOT NULL,
  `BrowseUsersFilter` text NOT NULL,
  `BrowseBlocklistsFilter` text NOT NULL,
  `BrowseDomainBlocklistsFilter` text NOT NULL,
  `BrowseFUMsFilter` text NOT NULL,
  `BrowseCampaignsFilter` text NOT NULL,
  `BrowseSplitTestsFilter` text NOT NULL,
  `FileImportOptions` text NOT NULL,
  `FileExportOptions` text NOT NULL,
  `BlockListImportOptions` text NOT NULL,
  `DomainBlockListImportOptions` text NOT NULL,
  `MySQLImportOptions` text NOT NULL,
  `RcptsListColumns` text NOT NULL,
  `BrowseSMSCampaignsFilter` text NOT NULL,
  `BrowseCampaignsTrackingFilter` text NOT NULL,
  `BrowseRespondersTrackingFilter` text NOT NULL,
  `BrowseDistribListsFilter` text NOT NULL,
  `BrowseTemplatesFilter` text NOT NULL,
  `PersTrackingRcptsListColumns` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Username` (`Username`)
);


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_users_owner`
--

CREATE TABLE IF NOT EXISTS `sml_users_owner` (
  `users_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  KEY `users_id` (`users_id`,`owner_id`)
);

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_users_login`
--

CREATE TABLE IF NOT EXISTS `sml_users_login` (
  `id` int(11) NOT NULL auto_increment,
  `users_id` int(11) NOT NULL,
  `LastLogin` datetime NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Logout` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `users_id` (`users_id`)
);


-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_outqueue`
--

CREATE TABLE IF NOT EXISTS `sml_outqueue` (
  `id` int(11) NOT NULL auto_increment,
  `CreateDate` date NOT NULL,
  `users_id` int(11) NOT NULL,
  `Source` enum('none','Autoresponder','FollowUpResponder','BirthdayResponder','Campaign','EventResponder','RSS2EMailResponder','SMSCampaign','DistributionList') NOT NULL,
  `MailSubject` text NOT NULL,
  `Source_id` int(11) NOT NULL,
  `Additional_id` int(11) NOT NULL default '0',
  `SendId` int(11) NOT NULL default '0',
  `maillists_id` int(11) NOT NULL,
  `recipients_id` int(11) NOT NULL,
  `mtas_id` int(11) NOT NULL,
  `statistics_id` int(11) NOT NULL,
  `LastSending` datetime NOT NULL,
  `SendEngineRetryCount` int(11) NOT NULL default '0',
  `IsSendingNowFlag` tinyint(1) NOT NULL default '0',

  `IsSendMultithreaded` tinyint(1) NOT NULL default '0',

  `multithreaded_errorcode` int(11) NOT NULL default '0',
  `multithreaded_errortext` text NOT NULL,

  `IsResponder` tinyint(1) NOT NULL default '0',

  PRIMARY KEY  (`id`),
  KEY `Source_id` (`Source_id`),
  KEY `maillists_id` (`maillists_id`),
  KEY `recipients_id` (`recipients_id`),
  KEY `users_id` (`users_id`),
  KEY `Additional_id` (`Additional_id`),
  KEY `SendId` (`SendId`),
  KEY `statistics_id` (`statistics_id`),
  KEY `mtas_id` (`mtas_id`),
  KEY `IsSendMultithreaded` (`IsSendMultithreaded`),
  KEY `IsResponder` (`IsResponder`)
);

-- --------------------------------------------------------

--
-- Tabellenstruktur fuer Tabelle `sml_localusermessages`
--

CREATE TABLE IF NOT EXISTS `sml_localusermessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `IsReaded` tinyint(1) NOT NULL default '0',
  `MessageDate` DATETIME NOT NULL ,
  `From_users_id` int(11) NOT NULL default '0',
  `To_users_id` int(11) NOT NULL,
  `MessageSubject` varchar(255) NOT NULL,
  `MessageText` text NOT NULL,
  `Attachment1` blob NOT NULL,
  `Attachment2` blob NOT NULL,
  `Attachment3` blob NOT NULL,
  PRIMARY KEY ( `id` ) ,
  KEY ( `From_users_id` ),
  KEY ( `To_users_id` )
);
