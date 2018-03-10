<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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

 function _LLEQD($Username, $UserType, $Language, $AccountType = 'Unlimited', $_f0jjf = 1000) {
    global $_Q8f1L, $INTERFACE_LANGUAGE, $resourcestrings, $_f0jOC, $_Q61I1;
    $Username = trim($Username);
    if($Language == "")
      $Language = $INTERFACE_LANGUAGE;
    if($INTERFACE_LANGUAGE == "")
      $INTERFACE_LANGUAGE = $Language;

    _LQLRQ($INTERFACE_LANGUAGE);

    // create tables
    $_f0jOl = _OALO0(TablePrefix.$Username."_globalblocklist");
    $_f0JQi = _OALO0(TablePrefix.$Username."_globaldomainblocklist");
    $_f0JJi = _OALO0(TablePrefix.$Username."_inboxes");;
    $_f060i = _OALO0(TablePrefix.$Username."_mtas");
    $_f06l6 = _OALO0(TablePrefix.$Username."_pages");
    $_f0ffl = _OALO0(TablePrefix.$Username."_templates");
    $_f0fOt = _OALO0(TablePrefix.$Username."_templates_users");
    $_f0866 = _OALO0(TablePrefix.$Username."_functions");
    $_f0t0f = _OALO0(TablePrefix.$Username."_messages");
    $_f0to1 = _OALO0(TablePrefix.$Username."_nas");
    $_f0O06 = _OALO0(TablePrefix.$Username."_campaigns");
    $_f0Oo8 = _OALO0(TablePrefix.$Username."_birthdayresponders");
    $_f0o0i = _OALO0(TablePrefix.$Username."_birthdayresponders_statistics");
    $_f0off = _OALO0(TablePrefix.$Username."_eventresponders");
    $_f0CIi = _OALO0(TablePrefix.$Username."_eventresponders_statistics");
    $_f0Cll = _OALO0(TablePrefix.$Username."_autoresponders");
    $_f0iOj = _OALO0(TablePrefix.$Username."_autoresponders_statistics");
    $_f0LtI = _OALO0(TablePrefix.$Username."_followupresponders");
    $_f0Lt6 = _OALO0(TablePrefix.$Username."_mailssent");

    $_f0Ltt = _OALO0(TablePrefix.$Username."_rss2emailresponders");
    $_f0LLC = _OALO0(TablePrefix.$Username."_rss2emailresponders_statistics");

    $_f0l6f = _OALO0(TablePrefix.$Username."_autoimports");

    $_f0lo6 = _OALO0(TablePrefix.$Username."_textblocks");

    $_f10fi = _OALO0(TablePrefix.$Username."_splittests");

    $_f10li = _OALO0(TablePrefix.$Username."_smscampaigns");

    $_f11CJ = _OALO0(TablePrefix.$Username."_distriblists");
    $_f1Q6j = _OALO0(TablePrefix.$Username."_distriblistsentries");

    $_f1IjJ = _OALO0(TablePrefix.$Username."_targetgroups");

    $_f1It0 = _OALO0(TablePrefix.$Username."_subunsubltd");

    $_f1ILL = 0;
    if($UserType == "SuperAdmin")
      $_f1ILL = 1;

    $_QJlJ0 = "INSERT INTO $_Q8f1L (`TermsOfUseAccepted`, `Language`, `Username`, `UserType`, `AccountType`, `AccountTypeLimitedMailCountLimited`, `LimitSubUnsubScriptsTableName`, `GlobalBlockListTableName`, `GlobalDomainBlockListTableName`, `NewsletterArchivesTableName`, `InboxesTableName`, `AutoImportsTableName`, `MTAsTableName`, `PagesTableName`, `TemplatesTableName`, `TemplatesToUsersTableName`, `MessagesTableName`, `FunctionsTableName`, `TextBlocksTableName`, `TargetGroupsTableName`, `CampaignsTableName`, `SplitTestsTableName`, `BirthdayMailsTableName`, `BirthdayMailsStatisticsTableName`, `RSS2EMailMailsTableName`, `RSS2EMailMailsStatisticsTableName`, `EventMailsTableName`, `EventMailsStatisticsTableName`, `AutoresponderMailsTableName`, `AutoresponderStatisticsTableName`, `FollowUpMailsTableName`, `SMSCampaignsTableName`, `DistributionListsTableName`, `DistributionListsEntriesTableName`, `MailsSentTableName`) ";

    $_QJlJ0 .= "VALUES($_f1ILL, "._OPQLR($Language).", "._OPQLR($Username).", '$UserType', "."'$AccountType', "."$_f0jjf, ";
    $_QJlJ0 .=
            _OPQLR($_f1It0).", ".
            _OPQLR($_f0jOl).", ".
            _OPQLR($_f0JQi).", ".
            _OPQLR($_f0to1).", ".
            _OPQLR($_f0JJi).", ".
            _OPQLR($_f0l6f).", ".

            _OPQLR($_f060i).", ".
            _OPQLR($_f06l6).", ".
            _OPQLR($_f0ffl).", ".
            _OPQLR($_f0fOt).", ".

            _OPQLR($_f0t0f).", ".
            _OPQLR($_f0866).", ".
            _OPQLR($_f0lo6).", ".

            _OPQLR($_f1IjJ).", ".

            _OPQLR($_f0O06).", ".
            _OPQLR($_f10fi).", ".

            _OPQLR($_f0Oo8).", ".
            _OPQLR($_f0o0i).", ".

            _OPQLR($_f0Ltt).", ".
            _OPQLR($_f0LLC).", ".

            _OPQLR($_f0off).", ".
            _OPQLR($_f0CIi).", ".

            _OPQLR($_f0Cll).", ".
            _OPQLR($_f0iOj).", ".

            _OPQLR($_f0LtI).", ".

            _OPQLR($_f10li).", ".

            _OPQLR($_f11CJ).", ".
            _OPQLR($_f1Q6j).", ".

            _OPQLR($_f0Lt6)
            ;


    $_QJlJ0 .= ")";


    $_Ij6Io = join("", file(_O68A8()."newadminuser.sql"));

    $_Ij6Io = str_replace('`TABLE_LIMITSUBUNSUBSCRIPTS`', $_f1It0, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_GLOBALBLOCKLIST`', $_f0jOl, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_GLOBALDOMAINBLOCKLIST`', $_f0JQi, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_NEWSLETTERARCHIVES`', $_f0to1, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_INBOXES`', $_f0JJi, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_AUTOIMPORTS`', $_f0l6f, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_MTAS`', $_f060i, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_PAGES`', $_f06l6, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_TEMPLATES`', $_f0ffl, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_TEMPLATESTOUSERS`', $_f0fOt, $_Ij6Io);

    $_Ij6Io = str_replace('`TABLE_MESSAGES`', $_f0t0f, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_FUNCTIONS`', $_f0866, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_TEXTBLOCKS`', $_f0lo6, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_TARGETGROUPS`', $_f1IjJ, $_Ij6Io);

    $_Ij6Io = str_replace('`TABLE_CAMPAIGNS`', $_f0O06, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_SPLITTESTS`', $_f10fi, $_Ij6Io);

    $_Ij6Io = str_replace('`TABLE_AUTORESPONDERS`', $_f0Cll, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_AUTORESPONDERS_STATISTICS`', $_f0iOj, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_BIRTHDAYMAILS`', $_f0Oo8, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_BIRTHDAYMAILS_STATISTICS`', $_f0o0i, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_RSS2EMAILMAILS`', $_f0Ltt, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_RSS2EMAILMAILS_STATISTICS`', $_f0LLC, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_EVENTMAILS`', $_f0off, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_EVENTMAILS_STATISTICS`', $_f0CIi, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_FOLLOWUPRESPONDERS`', $_f0LtI, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_MAILSSENTTABLENAME`', $_f0Lt6, $_Ij6Io);

    $_Ij6Io = str_replace('`TABLE_SMSCAMPAIGNS`', $_f10li, $_Ij6Io);

    $_Ij6Io = str_replace('`TABLE_DISTRIB_LISTS`', $_f11CJ, $_Ij6Io);
    $_Ij6Io = str_replace('`TABLE_DISTRIB_LISTS_ENTRIES`', $_f1Q6j, $_Ij6Io);

    $_Ij6il = explode(";", $_Ij6Io);

    for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
      if(trim($_Ij6il[$_Q6llo]) == "") continue;
      $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
      if(!$_Q60l1)
        $_Q60l1 = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
      if(!$_Q60l1){
        if(!defined("Setup"))
           _OAL8F($_Ij6il[$_Q6llo]);
           else {
             print mysql_error($_Q61I1);
             return false;
           }
      }
    }

    // insert admin itself
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1)
      if(!defined("Setup"))
        _OAL8F($_QJlJ0);
        else {
          print mysql_error($_Q61I1);
          return false;
        }

    $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
    $_Q6Q1C=mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QlLfl = $_Q6Q1C[0];

    if($UserType == "SuperAdmin" || $UserType == "Admin") {
      mt_srand((double)microtime()*1000000);
      $_IflL6 = mt_rand(8, 64);
      $apikey = "";
      for ($_Q6llo = 0; $_Q6llo < $_IflL6; $_Q6llo++) {
        $_QL8Q8 = mt_rand(1, 255);
        $apikey .= sprintf("%02X", $_QL8Q8);
      }
      $apikey = md5($apikey);
      $apikey = substr($apikey, 0, 63);
      $_QJlJ0 = "UPDATE `$_Q8f1L` SET `apikey`="._OPQLR($apikey)." WHERE id=$_QlLfl";
      mysql_query($_QJlJ0, $_Q61I1);
    }

    if($UserType == "Admin") {
       // paths
       $_Qt6oI = $_QlLfl."/";
       $_jjC06 = InstallPath."userfiles/".$_Qt6oI;
       _OBL6Q(_OBLCO($_jjC06), 0777);
       @chmod (_OBLCO($_jjC06), 0777);
       if(!is_dir(_OBLCO($_jjC06)))
         $_f0jOC[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _OBLCO($_jjC06));
         else{
           _LLF0B($_jjC06);
         }

       $_I0lQJ = $_jjC06."import/";
       _OBL6Q(_OBLCO($_I0lQJ), 0777);
       @chmod (_OBLCO($_I0lQJ), 0777);
       if(!is_dir(_OBLCO($_I0lQJ)))
         $_f0jOC[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _OBLCO($_I0lQJ));
         else{
           _LLF0B($_I0lQJ);
         }

       $_jji0C = $_jjC06."export/";
       _OBL6Q(_OBLCO($_jji0C), 0777);
       @chmod (_OBLCO($_jji0C), 0777);
       if(!is_dir(_OBLCO($_jji0C)))
         $_f0jOC[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _OBLCO($_jji0C));
         else{
           _LLF0B($_jji0C);
         }

       $_QOCJo = $_jjC06."file/";
       _OBL6Q(_OBLCO($_QOCJo), 0777);
       @chmod (_OBLCO($_QOCJo), 0777);
       if(!is_dir(_OBLCO($_QOCJo)))
         $_f0jOC[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _OBLCO($_QOCJo));
         else{
           _LLF0B($_QOCJo);
         }

       _OBL6Q(_OBLCO($_jjC06."image/"), 0777);
       @chmod (_OBLCO($_jjC06."image/"), 0777);
       if(!is_dir(_OBLCO($_jjC06."image/")))
         $_f0jOC[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _OBLCO($_jjC06."image/"));
         else{
           _LLF0B($_jjC06."image/");
         }

       _OBL6Q(_OBLCO($_jjC06."media/"), 0777);
       @chmod (_OBLCO($_jjC06."media/"), 0777);
       if(!is_dir(_OBLCO($_jjC06."media/")))
         $_f0jOC[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _OBLCO($_jjC06."media/"));
         else{
           _LLF0B($_jjC06."media/");
         }

       _OBL6Q(_OBLCO($_jjC06."templates/"), 0777);
       @chmod (_OBLCO($_jjC06."templates/"), 0777);
       if(!is_dir(_OBLCO($_jjC06."templates/")))
         $_f0jOC[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MKDIR_ERROR"], _OBLCO($_jjC06."templates/"));
         else{
           _LLF0B($_jjC06."templates/");
         }

       _OBL6Q(_OBLCO($_jjC06."flash/"), 0777);
       @chmod (_OBLCO($_jjC06."flash/"), 0777);
       _LLF0B($_jjC06."flash/");

    }

    return $_QlLfl;
  }

  function _LLF0B($_jt8IL){
    $_QCioi = @fopen(_OBLDR($_jt8IL)."index.php", "w");
    if($_QCioi != false) {
      fwrite($_QCioi, '<?php  ?>');
      fclose($_QCioi);
    }
  }
?>
