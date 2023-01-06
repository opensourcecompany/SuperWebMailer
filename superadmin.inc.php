<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
#                    Alle Rechte vorbehalten.                               #
#                http://www.supermailinglist.de/                            #
#                http://www.superwebmailer.de/                              #
#   Support SuperMailingList: support@supermailinglist.de                   #
#   Support SuperWebMailer: support@superwebmailer.de                       #
#   Support-Forum: http://board.superscripte.de/                            #
#                                                                           #
#   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
#   muss eine Lizenz erworben werden.                                       #
#                                                                           #
#   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
#   verwendet oder weiterverkauft werden.                                   #
#                                                                           #
#   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
#                                                                           #
#   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
#   Veraenderungen koennen nicht supported werden.                          #
#                                                                           #
#############################################################################

 /*NOT SAME STRUCTURE IN SML*/

 function _JJQOE($Username, $UserType, $Language, $AccountType = 'Unlimited', $_8O1LQ = 1000) {
    global $_I18lo, $INTERFACE_LANGUAGE, $resourcestrings, $_8OQO8, $_QLttI;
    $Username = trim($Username);
    if($Language == "")
      $Language = $INTERFACE_LANGUAGE;
    if($INTERFACE_LANGUAGE == "")
      $INTERFACE_LANGUAGE = $Language;

    _JQRLR($INTERFACE_LANGUAGE);

    // create tables
    $_8OIt0 = _L8D00(TablePrefix.$Username."_globalblocklist");
    $_8Oj0t = _L8D00(TablePrefix.$Username."_globaldomainblocklist");
    $_8OjJo = _L8D00(TablePrefix.$Username."_inboxes");;
    $_8OJjO = _L8D00(TablePrefix.$Username."_mtas");
    $_8OJJ8 = _L8D00(TablePrefix.$Username."_pages");
    $_8OJJL = _L8D00(TablePrefix.$Username."_templates");
    $_8O60O = _L8D00(TablePrefix.$Username."_templates_users");
    $_8O6QI = _L8D00(TablePrefix.$Username."_functions");
    $_8Of1O = _L8D00(TablePrefix.$Username."_messages");
    $_8Oftl = _L8D00(TablePrefix.$Username."_nas");
    $_8O88o = _L8D00(TablePrefix.$Username."_campaigns");
    $_8Ot6Q = _L8D00(TablePrefix.$Username."_birthdayresponders");
    $_8Ot8i = _L8D00(TablePrefix.$Username."_birthdayresponders_statistics");
    $_8OtLO = _L8D00(TablePrefix.$Username."_eventresponders");
    $_8OOit = _L8D00(TablePrefix.$Username."_eventresponders_statistics");
    $_8Ooj8 = _L8D00(TablePrefix.$Username."_autoresponders");
    $_8OC01 = _L8D00(TablePrefix.$Username."_autoresponders_statistics");
    $_8OCfO = _L8D00(TablePrefix.$Username."_followupresponders");
    $_8OijQ = _L8D00(TablePrefix.$Username."_mailssent");

    $_8Oiti = _L8D00(TablePrefix.$Username."_rss2emailresponders");
    $_8OiC1 = _L8D00(TablePrefix.$Username."_rss2emailresponders_statistics");

    $_8OLJ1 = _L8D00(TablePrefix.$Username."_autoimports");

    $_8Ol1I = _L8D00(TablePrefix.$Username."_textblocks");

    $_8Olof = _L8D00(TablePrefix.$Username."_splittests");

    $_8OlLl = _L8D00(TablePrefix.$Username."_smscampaigns");

    $_8o0JO = _L8D00(TablePrefix.$Username."_distriblists");
    $_8o0LC = _L8D00(TablePrefix.$Username."_distriblistsentries");

    $_8o1IL = _L8D00(TablePrefix.$Username."_targetgroups");

    $_8o1O8 = _L8D00(TablePrefix.$Username."_subunsubltd");

    // global compaign tables
    $_8oQJ6 = _L8D00(TablePrefix.$Username."_gc_sendstate");
    $_8oII1 = _L8D00(TablePrefix.$Username."_gc_currentusedmtas");
    $_8oIo0 = _L8D00(TablePrefix.$Username."_gc_archive");
    $_8ojQI = _L8D00(TablePrefix.$Username."_gc_groups");
    $_8ojLI = _L8D00(TablePrefix.$Username."_gc_nogroups");
    $_8oJji = _L8D00(TablePrefix.$Username."_gc_mtas");
    $_8o6Qo = _L8D00(TablePrefix.$Username."_gc_statistics");
    $_8o6j6 = _L8D00(TablePrefix.$Username."_gc_links");
    $_8of08 = _L8D00(TablePrefix.$Username."_gc_topenings");
    $_8ofol = _L8D00(TablePrefix.$Username."_gc_tropenings");
    $_8o8fC = _L8D00(TablePrefix.$Username."_gc_tlinks");
    $_8o8l6 = _L8D00(TablePrefix.$Username."_gc_trlinks");
    $_8oto6 = _L8D00(TablePrefix.$Username."_gc_useragents");
    $_8oOjt = _L8D00(TablePrefix.$Username."_gc_oss");
    // global compaign tables /

    $_8oo1C = 0;
    if($UserType == "SuperAdmin")
      $_8oo1C = 1;

    $_QLfol = "INSERT INTO $_I18lo (`TermsOfUseAccepted`, `Language`, `Username`, `UserType`, `AccountType`, `AccountTypeLimitedMailCountLimited`, `LimitSubUnsubScriptsTableName`,
    `GlobalBlockListTableName`, `GlobalDomainBlockListTableName`, `NewsletterArchivesTableName`, `InboxesTableName`, `AutoImportsTableName`, `MTAsTableName`, `PagesTableName`,
    `TemplatesTableName`, `TemplatesToUsersTableName`, `MessagesTableName`, `FunctionsTableName`, `TextBlocksTableName`, `TargetGroupsTableName`, `CampaignsTableName`,
    `CampaignsCurrentSendTableName`, `CampaignsCurrentUsedMTAsTableName`, `CampaignsArchiveTableName`, `CampaignsGroupsTableName`, `CampaignsNotInGroupsTableName`, `CampaignsMTAsTableName`,
    `CampaignsRStatisticsTableName`, `CampaignsLinksTableName`, `CampaignsTrackingOpeningsTableName`, `CampaignsTrackingOpeningsByRecipientTableName`,
    `CampaignsTrackingLinksTableName`, `CampaignsTrackingLinksByRecipientTableName`, `CampaignsTrackingUserAgentsTableName`, `CampaignsTrackingOSsTableName`,
    `SplitTestsTableName`,
    `BirthdayMailsTableName`, `BirthdayMailsStatisticsTableName`, `RSS2EMailMailsTableName`, `RSS2EMailMailsStatisticsTableName`, `EventMailsTableName`, `EventMailsStatisticsTableName`,
    `AutoresponderMailsTableName`, `AutoresponderStatisticsTableName`, `FollowUpMailsTableName`, `SMSCampaignsTableName`, `DistributionListsTableName`, `DistributionListsEntriesTableName`,
    `MailsSentTableName`) ";

    $_QLfol .= "VALUES($_8oo1C, "._LRAFO($Language).", "._LRAFO($Username).", '$UserType', "."'$AccountType', "."$_8O1LQ, ";
    $_QLfol .=
            _LRAFO($_8o1O8).", ".
            _LRAFO($_8OIt0).", ".
            _LRAFO($_8Oj0t).", ".
            _LRAFO($_8Oftl).", ".
            _LRAFO($_8OjJo).", ".
            _LRAFO($_8OLJ1).", ".

            _LRAFO($_8OJjO).", ".
            _LRAFO($_8OJJ8).", ".
            _LRAFO($_8OJJL).", ".
            _LRAFO($_8O60O).", ".

            _LRAFO($_8Of1O).", ".
            _LRAFO($_8O6QI).", ".
            _LRAFO($_8Ol1I).", ".

            _LRAFO($_8o1IL).", ".

            _LRAFO($_8O88o).", ".

            _LRAFO($_8oQJ6).", ".
            _LRAFO($_8oII1).", ".
            _LRAFO($_8oIo0).", ".
            _LRAFO($_8ojQI).", ".
            _LRAFO($_8ojLI).", ".
            _LRAFO($_8oJji).", ".
            _LRAFO($_8o6Qo).", ".
            _LRAFO($_8o6j6).", ".
            _LRAFO($_8of08).", ".
            _LRAFO($_8ofol).", ".
            _LRAFO($_8o8fC).", ".
            _LRAFO($_8o8l6).", ".
            _LRAFO($_8oto6).", ".
            _LRAFO($_8oOjt).", ".

            _LRAFO($_8Olof).", ".

            _LRAFO($_8Ot6Q).", ".
            _LRAFO($_8Ot8i).", ".

            _LRAFO($_8Oiti).", ".
            _LRAFO($_8OiC1).", ".

            _LRAFO($_8OtLO).", ".
            _LRAFO($_8OOit).", ".

            _LRAFO($_8Ooj8).", ".
            _LRAFO($_8OC01).", ".

            _LRAFO($_8OCfO).", ".

            _LRAFO($_8OlLl).", ".

            _LRAFO($_8o0JO).", ".
            _LRAFO($_8o0LC).", ".

            _LRAFO($_8OijQ)
            ;


    $_QLfol .= ")";


    $_IiIlQ = join("", file(_LOCFC()."newadminuser.sql"));

    $_IiIlQ = str_replace('`TABLE_LIMITSUBUNSUBSCRIPTS`', $_8o1O8, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_GLOBALBLOCKLIST`', $_8OIt0, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_GLOBALDOMAINBLOCKLIST`', $_8Oj0t, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_NEWSLETTERARCHIVES`', $_8Oftl, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_INBOXES`', $_8OjJo, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_AUTOIMPORTS`', $_8OLJ1, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_MTAS`', $_8OJjO, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_PAGES`', $_8OJJ8, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_TEMPLATES`', $_8OJJL, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_TEMPLATESTOUSERS`', $_8O60O, $_IiIlQ);

    $_IiIlQ = str_replace('`TABLE_MESSAGES`', $_8Of1O, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_FUNCTIONS`', $_8O6QI, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_TEXTBLOCKS`', $_8Ol1I, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_TARGETGROUPS`', $_8o1IL, $_IiIlQ);

    $_IiIlQ = str_replace('`TABLE_CAMPAIGNS`', $_8O88o, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_SPLITTESTS`', $_8Olof, $_IiIlQ);

    $_IiIlQ = str_replace('`TABLE_AUTORESPONDERS`', $_8Ooj8, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_AUTORESPONDERS_STATISTICS`', $_8OC01, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_BIRTHDAYMAILS`', $_8Ot6Q, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_BIRTHDAYMAILS_STATISTICS`', $_8Ot8i, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_RSS2EMAILMAILS`', $_8Oiti, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_RSS2EMAILMAILS_STATISTICS`', $_8OiC1, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_EVENTMAILS`', $_8OtLO, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_EVENTMAILS_STATISTICS`', $_8OOit, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_FOLLOWUPRESPONDERS`', $_8OCfO, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_MAILSSENTTABLENAME`', $_8OijQ, $_IiIlQ);

    $_IiIlQ = str_replace('`TABLE_SMSCAMPAIGNS`', $_8OlLl, $_IiIlQ);

    $_IiIlQ = str_replace('`TABLE_DISTRIB_LISTS`', $_8o0JO, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_DISTRIB_LISTS_ENTRIES`', $_8o0LC, $_IiIlQ);

    // global compaign tables
    $_IiIlQ .= "\n" . join("", file(_LOCFC()."campaign.sql"));
    $_IiIlQ = str_replace('`TABLE_CURRENT_SENDTABLE`', $_8oQJ6, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CURRENT_USED_MTAS`', $_8oII1, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_ARCHIVETABLE`', $_8oIo0, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_C_STATISTICS`', $_8o6Qo, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_GROUPS`', $_8ojQI, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_NOTINGROUPS`', $_8ojLI, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_MTAS`', $_8oJji, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNLINKS`', $_8o6j6, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGS`', $_8of08, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGSBYRECIPIENT`', $_8ofol, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGLINKS`', $_8o8fC, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGLINKSBYRECIPIENT`', $_8o8l6, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGUSERAGENTS`', $_8oto6, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOSS`', $_8oOjt, $_IiIlQ);
    // global compaign tables /

    $_IijLl = explode(";", $_IiIlQ);

    for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
      if(trim($_IijLl[$_Qli6J]) == "") continue;
      $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
      if(!$_QL8i1)
        $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
      if(!$_QL8i1){
        if(!defined("Setup"))
           _L8D88($_IijLl[$_Qli6J]);
           else {
             print mysql_error($_QLttI);
             return false;
           }
      }
    }

    // insert admin itself
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1)
      if(!defined("Setup"))
        _L8D88($_QLfol);
        else {
          print mysql_error($_QLttI);
          return false;
        }

    $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
    $_QLO0f=mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_I8l8o = $_QLO0f[0];

    if($UserType == "SuperAdmin" || $UserType == "Admin") {
      mt_srand(time());
      $_j1881 = mt_rand(8, 64);
      $apikey = "";
      for ($_Qli6J = 0; $_Qli6J < $_j1881; $_Qli6J++) {
        $_Ift08 = mt_rand(1, 255);
        $apikey .= sprintf("%02X", $_Ift08);
      }
      $apikey = md5($apikey);
      $apikey = substr($apikey, 0, 63);
      $_QLfol = "UPDATE `$_I18lo` SET `apikey`="._LRAFO($apikey)." WHERE id=$_I8l8o";
      mysql_query($_QLfol, $_QLttI);
    }

    if($UserType == "Admin") {
       // paths
       $_Qll8O = $_I8l8o."/";
       $_J18oI = InstallPath."userfiles/".$_Qll8O;
       _LPABA(_LPBCC($_J18oI), 0777);
       @chmod (_LPBCC($_J18oI), 0777);
       if(!is_dir(_LPBCC($_J18oI)))
         $_8OQO8[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _LPBCC($_J18oI));
         else{
           _JJOQ8($_J18oI);
         }

       $_ItL8f = $_J18oI."import/";
       _LPABA(_LPBCC($_ItL8f), 0777);
       @chmod (_LPBCC($_ItL8f), 0777);
       if(!is_dir(_LPBCC($_ItL8f)))
         $_8OQO8[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _LPBCC($_ItL8f));
         else{
           _JJOQ8($_ItL8f);
         }

       $_J1t6J = $_J18oI."export/";
       _LPABA(_LPBCC($_J1t6J), 0777);
       @chmod (_LPBCC($_J1t6J), 0777);
       if(!is_dir(_LPBCC($_J1t6J)))
         $_8OQO8[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _LPBCC($_J1t6J));
         else{
           _JJOQ8($_J1t6J);
         }

       $_IIlfi = $_J18oI."file/";
       _LPABA(_LPBCC($_IIlfi), 0777);
       @chmod (_LPBCC($_IIlfi), 0777);
       if(!is_dir(_LPBCC($_IIlfi)))
         $_8OQO8[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _LPBCC($_IIlfi));
         else{
           _JJOQ8($_IIlfi);
         }

       _LPABA(_LPBCC($_J18oI."image/"), 0777);
       @chmod (_LPBCC($_J18oI."image/"), 0777);
       if(!is_dir(_LPBCC($_J18oI."image/")))
         $_8OQO8[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _LPBCC($_J18oI."image/"));
         else{
           _JJOQ8($_J18oI."image/");
         }

       _LPABA(_LPBCC($_J18oI."media/"), 0777);
       @chmod (_LPBCC($_J18oI."media/"), 0777);
       if(!is_dir(_LPBCC($_J18oI."media/")))
         $_8OQO8[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _LPBCC($_J18oI."media/"));
         else{
           _JJOQ8($_J18oI."media/");
         }

       _LPABA(_LPBCC($_J18oI."templates/"), 0777);
       @chmod (_LPBCC($_J18oI."templates/"), 0777);
       if(!is_dir(_LPBCC($_J18oI."templates/")))
         $_8OQO8[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _LPBCC($_J18oI."templates/"));
         else{
           _JJOQ8($_J18oI."templates/");
         }

       _LPABA(_LPBCC($_J18oI."flash/"), 0777);
       @chmod (_LPBCC($_J18oI."flash/"), 0777);
       _JJOQ8($_J18oI."flash/");

    }

    return $_I8l8o;
  }

  function _JJOQ8($_Jf1C8){
    $_I60fo = @fopen(_LPC1C($_Jf1C8)."index.php", "w");
    if($_I60fo != false) {
      fwrite($_I60fo, '<?php  ?>');
      fclose($_I60fo);
    }
  }
?>
