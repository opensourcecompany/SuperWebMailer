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

  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("mailcreate.inc.php");
  include_once("campaignstools.inc.php");
  include_once("splitteststools.inc.php");
  include_once("cron_campaigns.inc.php");


  function _LJRCR(&$_JIfo0) {
    global $_QLttI, $_QLl1Q, $_QLo06;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Ijt8j, $resourcestrings, $_I18lo, $_I1tQf;
    global $_Ijt0i, $_I8tfQ, $_jQ68I, $_QL88I, $_IQQot;
    global $_QLi60, $_jJL88, $_jfilQ;
    global $commonmsgHTMLMTANotFound;

    $_JIfo0 = "Split tests checking starts...<br />";
    $_J0J6C = 0;

    $_QLfol = "SELECT * FROM $_I18lo WHERE UserType='Admin' AND IsActive>0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
      _LRCOC();
      $UserId = $_QLO0f["id"];
      $OwnerUserId = 0;
      $Username = $_QLO0f["Username"];
      $UserType = $_QLO0f["UserType"];
      $AccountType = $_QLO0f["AccountType"];
      $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
      $INTERFACE_LANGUAGE = $_QLO0f["Language"];

      _LRPQ6($INTERFACE_LANGUAGE);

      _JQRLR($INTERFACE_LANGUAGE);

      $_QLfol = "SELECT Theme FROM $_I1tQf WHERE id=$INTERFACE_THEMESID";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      $_joQOt = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_joQOt[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);


      // check sent split tests

      $_QLfol = "SELECT `$_jJL88`.*, ";
      $_QLfol .= "`$_QL88I`.`users_id`, `$_QL88I`.`MaillistTableName`, `$_QL88I`.id AS MailingListId ";
      $_QLfol .= " FROM `$_jJL88` LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_jJL88`.`maillists_id`";
      $_QLfol .= " WHERE (`$_jJL88`.`SetupLevel`=99 AND `$_jJL88`.`SendScheduler`<>'SaveOnly')";
      $_JOJjJ = mysql_query($_QLfol, $_QLttI);
      while($_JOJjJ && $_joQOt = mysql_fetch_assoc($_JOJjJ)) {
        _LRCOC();

        // check for waiting/sent split tests
        $_QLfol = "SELECT * ";
        $_QLfol .= "FROM `$_joQOt[CurrentSendTableName]` WHERE (SendState='Waiting' AND SplitTestSendDone<>0) OR (SendState='SendingWinnerCampaign' AND SplitTestSendDone<>0 AND WinnerCampaignSendDone<>0)";
        $_J6QJI = mysql_query($_QLfol, $_QLttI);
        while($_J6QJI && $_IQjl0 = mysql_fetch_assoc($_J6QJI) ) {
           _LRCOC();

           $_joQOt["WinnerType"] = $_IQjl0["WinnerType"];
           $_joQOt["TestType"] = $_IQjl0["TestType"];
           $_joQOt["ListPercentage"] = $_IQjl0["ListPercentage"];

           // get Campaigns_id and CampaignsSendStat_id for outqueue checking
           $_QLfol = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_joQOt[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_IQjl0[id]";
           $_jjJfo = mysql_query($_QLfol, $_QLttI);
           $_joItI = array();
           while($_I1OfI = mysql_fetch_assoc($_jjJfo)) {
             $_joItI[] = array("Campaigns_id" => $_I1OfI["Campaigns_id"], "CampaignsSendStat_id" => $_I1OfI["CampaignsSendStat_id"], "RecipientsCount" => $_I1OfI["RecipientsCount"]);
           }
           mysql_free_result($_jjJfo);

           # anything of campaignS in outqueue?
           $_QLfol = "SELECT COUNT(id) FROM `$_IQQot` WHERE (`users_id`=$UserId AND `Source`='Campaign' AND `Additional_id`=0) AND ( ";

           $_QLlO6 = "";
           for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++) {
             if($_QLlO6 != "")
               $_QLlO6 .= " OR ";
             $_QLlO6 .= "(`Source_id`=".$_joItI[$_Qli6J]["Campaigns_id"]." AND `SendId`=".$_joItI[$_Qli6J]["CampaignsSendStat_id"].")";
           }
           $_QLfol .= $_QLlO6." )";

           $_jjJfo = mysql_query($_QLfol, $_QLttI);
           $_jj6L6 = mysql_fetch_row($_jjJfo);
           mysql_free_result($_jjJfo);
           if($_jj6L6[0] > 0) { // not done?
             continue;
           }

           // campaign sent to all members?
           if($_joQOt["TestType"] == 'TestSendToAllMembers' && $_IQjl0["SendState"] == 'Waiting'){ # Split Test is done completly

             mysql_query("BEGIN", $_QLttI);

             for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++) {
               // $_J61tJ
               $_QLfol = "SELECT `$_QLi60`.id, `$_QLi60`.Name AS CampaignsName, `$_QLi60`.Creator_users_id, `$_QLi60`.CurrentSendTableName, ";
               $_QLfol .= "`$_QLi60`.RStatisticsTableName, `$_QLi60`.MTAsTableName, `$_QLi60`.maillists_id, ";
               $_QLfol .= "`$_QLi60`.SendReportToYourSelf, `$_QLi60`.SendReportToListAdmin, `$_QLi60`.SendReportToMailingListUsers, `$_QLi60`.SendReportToEMailAddress, `$_QLi60`.SendReportToEMailAddressEMailAddress, ";
               $_QLfol .= "`$_QL88I`.users_id, `$_QL88I`.MaillistTableName, `$_QL88I`.id AS MailingListId ";
               $_QLfol .= " FROM `$_QLi60` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_QLi60`.maillists_id";
               $_QLfol .= " WHERE `$_QLi60`.id =".$_joItI[$_Qli6J]["Campaigns_id"];
               $_jjJfo = mysql_query($_QLfol, $_QLttI);
               $_J61tJ = mysql_fetch_assoc($_jjJfo);
               mysql_free_result($_jjJfo);
               
               # set Campaign done state
               $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET `CampaignSendDone`=1, EndSendDateTime=NOW() WHERE id=".$_joItI[$_Qli6J]["CampaignsSendStat_id"];
               mysql_query($_QLfol, $_QLttI);

               # check Campaign set state and send reports cron_campaigns.inc.php
               _LL6R6($_J61tJ, $_JIfo0);
             }

             // Update ReSendFlag
             $_QLfol = "UPDATE `$_jJL88` SET `ReSendFlag`=0 WHERE id=$_joQOt[id]";
             mysql_query($_QLfol, $_QLttI);

             // SET DONE STATE
             $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET EndSendDateTime=NOW(), SendState='Done', `WinnerCampaignSendDone`=1 WHERE id=$_IQjl0[id]";
             mysql_query($_QLfol, $_QLttI);

             mysql_query("COMMIT", $_QLttI);

             continue;

           } # if($_joQOt["TestType"] == 'TestSendToAllMembers' && $_IQjl0["SendState"] == 'Waiting')
           // campaign sent to all members? /

           // get winner campaign
           if($_joQOt["TestType"] == 'TestSendToListPercentage' && $_IQjl0["SendState"] == 'Waiting'){ # Split Test is sent now check for "Winner" to send campaign
             // get winner of split test
             $_JO616 = 0;
             $_JO6Co = _JL8L0($_joQOt, $_IQjl0, $_joItI, true, $_JO616);

             if($_JO6Co != 0) {
               # Get recipients count for winner campaign
               $_JOf6O = 0;
               for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++){
                 $_JOf6O += $_joItI[$_Qli6J]["RecipientsCount"];
               }
               if($_IQjl0["RecipientsCount"] - $_JOf6O < 0)
                 $_JOf6O = $_IQjl0["RecipientsCount"];

               mysql_query("BEGIN", $_QLttI);
               
               $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET EndSendDateTime=NOW(), SendState='PreparingWinnerCampaign', `Members_Prepared`=0, `WinnerCampaigns_id`=$_JO6Co, `WinnerCampaignsMaxClicks`=$_JO616, `WinnerRecipientsCount`=".intval($_IQjl0["RecipientsCount"] - $_JOf6O)." WHERE id=$_IQjl0[id]";
               mysql_query($_QLfol, $_QLttI);

               $_QLfol = "SELECT `CurrentSendTableName` FROM `$_QLi60` WHERE id=$_JO6Co";
               $_I1O6j = mysql_query($_QLfol, $_QLttI);
               $_J61tJ = mysql_fetch_assoc($_I1O6j);
               mysql_free_result($_I1O6j);

               for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++){
                 if($_joItI[$_Qli6J]["Campaigns_id"] == $_JO6Co) {
                   // Update winner RecipientsCount
                   $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET `RecipientsCount`=".intval($_IQjl0["RecipientsCount"] - $_JOf6O)." WHERE id=".$_joItI[$_Qli6J]["CampaignsSendStat_id"];
                   mysql_query($_QLfol, $_QLttI);
                   break;
                 }
               }
               
               mysql_query("COMMIT", $_QLttI);

               continue;
             }
           } // if($_joQOt["TestType"] == 'TestSendToListPercentage' && $_IQjl0["SendState"] == 'Waiting')
           // get winner campaign /

           // is winner campaign sent completly?
           if($_joQOt["TestType"] == 'TestSendToListPercentage' && $_IQjl0["SendState"] == 'SendingWinnerCampaign' && $_IQjl0["SplitTestSendDone"] != 0 && $_IQjl0["WinnerCampaignSendDone"] != 0){ # Split Test is sent now check for "Winner" to send campaign

             mysql_query("BEGIN", $_QLttI);

             for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++) {
               // $_J61tJ
               $_QLfol = "SELECT `$_QLi60`.id, `$_QLi60`.Name AS CampaignsName, `$_QLi60`.Creator_users_id, `$_QLi60`.CurrentSendTableName, ";
               $_QLfol .= "`$_QLi60`.RStatisticsTableName, `$_QLi60`.MTAsTableName, `$_QLi60`.maillists_id, ";
               $_QLfol .= "`$_QLi60`.SendReportToYourSelf, `$_QLi60`.SendReportToListAdmin, `$_QLi60`.SendReportToMailingListUsers, `$_QLi60`.SendReportToEMailAddress, `$_QLi60`.SendReportToEMailAddressEMailAddress, ";
               $_QLfol .= "`$_QL88I`.users_id, `$_QL88I`.MaillistTableName, `$_QL88I`.id AS MailingListId ";
               $_QLfol .= " FROM `$_QLi60` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_QLi60`.maillists_id";
               $_QLfol .= " WHERE `$_QLi60`.id =".$_joItI[$_Qli6J]["Campaigns_id"];
               $_jjJfo = mysql_query($_QLfol, $_QLttI);
               $_J61tJ = mysql_fetch_assoc($_jjJfo);
               mysql_free_result($_jjJfo);
               
               // fix recipients count
               $_QLfol = "SELECT COUNT(*) FROM $_J61tJ[RStatisticsTableName] WHERE SendStat_id=".$_joItI[$_Qli6J]["CampaignsSendStat_id"];
               $_jjJfo = mysql_query($_QLfol, $_QLttI);
               $_I016j = mysql_fetch_row($_jjJfo); $_jloJI = $_I016j[0];
               mysql_free_result($_jjJfo);

               # set Campaign done state
               $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET `RecipientsCount`=$_jloJI, `CampaignSendDone`=1, SendState='Sending', `EndSendDateTime`=NOW() WHERE id=".$_joItI[$_Qli6J]["CampaignsSendStat_id"];
               mysql_query($_QLfol, $_QLttI);

               # check Campaign set state and send reports cron_campaigns.inc.php
               _LL6R6($_J61tJ, $_JIfo0);

             }

             // Update ReSendFlag
             $_QLfol = "UPDATE `$_jJL88` SET `ReSendFlag`=0 WHERE `id`=$_joQOt[id]";
             mysql_query($_QLfol, $_QLttI);

             // SET DONE STATE
             $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `SendState`='Done' WHERE `id`=$_IQjl0[id]";
             mysql_query($_QLfol, $_QLttI);

             mysql_query("COMMIT", $_QLttI);
             
           } // if($_joQOt["TestType"] == 'TestSendToListPercentage' && $_IQjl0["SendState"] == 'SendingWinnerCampaign' && $_IQjl0["SplitTestSendDone"] != 0 && $_IQjl0["WinnerCampaignSendDone"] != 0)
           // is winner campaign sent completly? /


        } # while($_J6QJI && $_IQjl0 = mysql_fetch_assoc($_J6QJI) )
        mysql_free_result($_J6QJI);
        // check for waiting/sent split tests /

        // check for resend emails of split test

        $_QLfol = "SELECT * ";
        $_QLfol .= "FROM `$_joQOt[CurrentSendTableName]` WHERE SendState='ReSending'";
        $_J6QJI = mysql_query($_QLfol, $_QLttI);
        while($_J6QJI && $_IQjl0 = mysql_fetch_assoc($_J6QJI) ) {
           _LRCOC();

           // get Campaigns_id and CampaignsSendStat_id for outqueue checking
           $_QLfol = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_joQOt[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_IQjl0[id]";
           $_jjJfo = mysql_query($_QLfol, $_QLttI);
           $_joItI = array();
           while($_I1OfI = mysql_fetch_assoc($_jjJfo)) {
             $_joItI[] = array("Campaigns_id" => $_I1OfI["Campaigns_id"], "CampaignsSendStat_id" => $_I1OfI["CampaignsSendStat_id"], "RecipientsCount" => $_I1OfI["RecipientsCount"]);
           }
           mysql_free_result($_jjJfo);

           # anything of campaignS in outqueue?
           $_QLfol = "SELECT COUNT(id) FROM `$_IQQot` WHERE (`users_id`=$UserId AND `Source`='Campaign' AND `Additional_id`=0) AND ( ";

           $_QLlO6 = "";
           for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++) {
             if($_QLlO6 != "")
               $_QLlO6 .= " OR ";
             $_QLlO6 .= "(`Source_id`=".$_joItI[$_Qli6J]["Campaigns_id"]." AND `SendId`=".$_joItI[$_Qli6J]["CampaignsSendStat_id"].")";
           }
           $_QLfol .= $_QLlO6." )";

           $_jjJfo = mysql_query($_QLfol, $_QLttI);
           $_jj6L6 = mysql_fetch_row($_jjJfo);
           mysql_free_result($_jjJfo);
           if($_jj6L6[0] > 0) { // not done?
             continue;
           }

           mysql_query("BEGIN", $_QLttI);
           for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++) {
             // $_J61tJ
             $_QLfol = "SELECT `$_QLi60`.id, `$_QLi60`.Name AS CampaignsName, `$_QLi60`.Creator_users_id, `$_QLi60`.CurrentSendTableName, ";
             $_QLfol .= "`$_QLi60`.RStatisticsTableName, `$_QLi60`.MTAsTableName, `$_QLi60`.maillists_id, ";
             $_QLfol .= "`$_QLi60`.SendReportToYourSelf, `$_QLi60`.SendReportToListAdmin, `$_QLi60`.SendReportToMailingListUsers, `$_QLi60`.SendReportToEMailAddress, `$_QLi60`.SendReportToEMailAddressEMailAddress, ";
             $_QLfol .= "`$_QL88I`.users_id, `$_QL88I`.MaillistTableName, `$_QL88I`.id AS MailingListId ";
             $_QLfol .= " FROM `$_QLi60` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_QLi60`.maillists_id";
             $_QLfol .= " WHERE `$_QLi60`.id =".$_joItI[$_Qli6J]["Campaigns_id"];
             $_jjJfo = mysql_query($_QLfol, $_QLttI);
             $_J61tJ = mysql_fetch_assoc($_jjJfo);
             mysql_free_result($_jjJfo);

             # set Campaign done state
             $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET `CampaignSendDone`=1 WHERE id=".$_joItI[$_Qli6J]["CampaignsSendStat_id"];
             mysql_query($_QLfol, $_QLttI);

             # check Campaign set state and send reports cron_campaigns.inc.php
             _LL6R6($_J61tJ, $_JIfo0);

           }

           // SET DONE STATE
           $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET `SendState`='Done' WHERE `id`=$_IQjl0[id]";
           mysql_query($_QLfol, $_QLttI);
           
           mysql_query("COMMIT", $_QLttI);
        }
        mysql_free_result($_J6QJI);

        // check for resend emails of split test /

      } # while($_joQOt = mysql_fetch_assoc($_JOJjJ))

      if($_JOJjJ)
        mysql_free_result($_JOJjJ);

      // check sent split tests done

      // check to send split tests

      $_QLfol = "SELECT `$_jJL88`.* ";
      $_QLfol .= " FROM `$_jJL88` ";
      $_QLfol .= " WHERE (`$_jJL88`.SetupLevel=99 AND `$_jJL88`.SendScheduler<>'SaveOnly') ";

      $_QLfol .= " AND (";
      $_QLfol .= " IF(`$_jJL88`.SendScheduler = 'SendInFutureOnce', NOW()>=`$_jJL88`.SendInFutureOnceDateTime, 0)";
      $_QLfol .= " OR `$_jJL88`.SendScheduler = 'SendImmediately'";
      $_QLfol .= ")";

      $_JOJjJ = mysql_query($_QLfol, $_QLttI);

      while($_JOJjJ && $_joQOt = mysql_fetch_assoc($_JOJjJ)) {
        _LRCOC();

        // we can't check this in sql above
        if( $_joQOt["SendScheduler"] == 'SendImmediately' || $_joQOt["SendScheduler"] == 'SendInFutureOnce' ) {
          $_QLfol = "SELECT COUNT(id) FROM `$_joQOt[CurrentSendTableName]` WHERE `SendState`='Done'";
          $_J6QJI = mysql_query($_QLfol, $_QLttI);
          $_IQjl0 = mysql_fetch_row($_J6QJI);
          if($_IQjl0[0] > 0) { // always send?
            if($_joQOt["ReSendFlag"] < 1) {// send again?
               mysql_free_result($_J6QJI);
               continue;
            }
          }
          mysql_free_result($_J6QJI);
        }


        // CurrentSendTableName
        $_jlOJO = 0;
        $_JO6Co = 0;
        $_JO86I = 0;
        $_jloJI = 0;
        $_JO8iL = 0;
        $_JOtCC = 0;
        $_JOtLi = 0;
        $_j1ofo = 'Preparing';
        $_JOtl8 = 0;
        $_JOOQL = 0;
        $_JOOfI = "";

        $_QLfol = "SELECT * ";
        $_QLfol .= "FROM `$_joQOt[CurrentSendTableName]` WHERE SendState<>'Done' AND SendState<>'Paused'";

        $_J6QJI = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_J6QJI) > 0) {
          $_IQjl0 = mysql_fetch_assoc($_J6QJI);
          mysql_free_result($_J6QJI);
          if($_IQjl0["SplitTestSendDone"] && $_IQjl0["WinnerCampaigns_id"] == 0 ) // splittest always send completly, but no winner at this time?
            continue;
          if($_IQjl0["SplitTestSendDone"] && $_IQjl0["WinnerCampaignSendDone"]) // splittest and winner campaign always send completly?
            continue;
          $_jlOJO = $_IQjl0["LastMember_id"];
          $_JO86I = $_IQjl0["id"];
          $_jloJI = $_IQjl0["RecipientsCount"];
          $_JO8iL = $_IQjl0["WinnerRecipientsCount"];
          $_JOtCC = $_IQjl0["RecipientsCountForSplitTest"];
          $_JOtLi = $_IQjl0["CampaignsCount"];
          $_j1ofo = $_IQjl0["SendState"];
          $_JOtl8 = $_IQjl0["Members_Prepared"];
          $_JOOQL = $_IQjl0["RandomMembers_Prepared"];
          $_JOOfI = $_IQjl0["MembersTableName"];
          $_JOOlL = $_IQjl0["RandomMembersTableName"];
          $_JO6Co = $_IQjl0["WinnerCampaigns_id"];
          $_joQOt["WinnerType"] = $_IQjl0["WinnerType"];
          $_joQOt["TestType"] = $_IQjl0["TestType"];
          $_joQOt["ListPercentage"] = $_IQjl0["ListPercentage"];

        } else{
          mysql_free_result($_J6QJI);

          $_JOoiC = TablePrefix._L8A8P($_joQOt['Name']);
          $_JOOfI = _L8D00($_JOoiC."_testmembers");
          $_JOOlL = _L8D00($_JOoiC."_testrandommembers");

          $_IiIlQ = join("", file(_LOCFC()."splittest_members.sql"));
          $_IiIlQ = str_replace('`TABLE_SPLITTEST_MEMBERS`', $_JOOfI, $_IiIlQ);
          $_IiIlQ = str_replace('`TABLE_SPLITTEST_RANDOMMEMBERS`', $_JOOlL, $_IiIlQ);
          $_IijLl = explode(";", $_IiIlQ);

          for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
            if(trim($_IijLl[$_Qli6J]) == "") continue;
            $_JOolj = mysql_query($_IijLl[$_Qli6J]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
            if(!$_JOolj)
              $_JOolj = mysql_query($_IijLl[$_Qli6J], $_QLttI);
          }

          // CurrentSendTableName
          $_QLfol = "INSERT INTO `$_joQOt[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW(), `MembersTableName`="._LRAFO($_JOOfI).", `RandomMembersTableName`="._LRAFO($_JOOlL);
          $_QLfol .= ", `WinnerType`="._LRAFO($_joQOt["WinnerType"]).", `TestType`="._LRAFO($_joQOt["TestType"]).", `ListPercentage`=".$_joQOt["ListPercentage"];
          mysql_query($_QLfol, $_QLttI);
          $_J6QJI= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
          $_IQjl0=mysql_fetch_row($_J6QJI);
          $_JO86I = $_IQjl0[0];
          $_joQOt["CurrentSendId"] = $_JO86I;
          mysql_free_result($_J6QJI);

        }

        // Prepare members
        if($_j1ofo == 'Preparing' && !$_JOtl8){
          // get mailinglist
          $_QLfol = "SELECT `$_QL88I`.MaillistTableName, `$_QL88I`.GroupsTableName, `$_QL88I`.MailListToGroupsTableName, `$_QL88I`.LocalBlocklistTableName, `$_QL88I`.id AS MailingListId ";
          $_QLfol .= " FROM `$_QL88I` WHERE `$_QL88I`.id=$_joQOt[maillists_id]";
          $_j0L0O = mysql_query($_QLfol, $_QLttI);
          $_IQIfC = mysql_fetch_assoc($_j0L0O);
          $_I8I6o = $_IQIfC["MaillistTableName"];
          mysql_free_result($_j0L0O);

          $_QLfol = "SELECT COUNT(id) FROM `$_joQOt[CampaignsForSplitTestTableName]`";
          $_JOCl0 = mysql_query($_QLfol, $_QLttI);
          $_JOiLo = mysql_fetch_row($_JOCl0);
          $_JOtLi = $_JOiLo[0];
          mysql_free_result($_JOCl0);

          $_jloJI = 0;
          $_JOLLt = array();

          // get campaign_ids
          $_JOl10 = 0;
          $_QLfol = "SELECT Campaigns_id FROM `$_joQOt[CampaignsForSplitTestTableName]`";
          $_JOCl0 = mysql_query($_QLfol, $_QLttI);
          $_JOlli = "";
          while($_JOiLo = mysql_fetch_assoc($_JOCl0)){
            if($_jloJI == 0) {
              $_Jo001 = "";
              if($_JOl10 == 0)
                $_JOl10 = _LOL8J($_JOiLo["Campaigns_id"], $_Jo001, $_I8I6o, $_IQIfC["GroupsTableName"], $_IQIfC["MailListToGroupsTableName"], $_IQIfC["LocalBlocklistTableName"]);
              $_JOLLt[] = $_JOiLo["Campaigns_id"];
            }
            if($_JOlli == "") {
              $_JOlli = _LOQFJ($_JOiLo["Campaigns_id"], $_I8I6o, $_IQIfC["GroupsTableName"], $_IQIfC["MailListToGroupsTableName"], $_IQIfC["LocalBlocklistTableName"]);
              $_JOlli = str_replace("`$_I8I6o`.*", "`$_I8I6o`.id", $_JOlli);
              $_JOlli = substr($_JOlli, 0, strpos_reverse($_JOlli, ".id,", strpos($_JOlli, "`MembersAge`")) + 3  ) . substr($_JOlli, strpos($_JOlli, " FROM"));
            }
          }
          mysql_free_result($_JOCl0);
          if($_jloJI == 0 && $_JOl10 > 0)
            $_jloJI = $_JOl10;
          if($_JO8iL == 0 && $_JOl10 > 0)
            $_JO8iL = $_JOl10;


          if($_joQOt["TestType"] == 'TestSendToAllMembers'){
            $_joQOt["ListPercentage"] = 100;
          }

          $_JOtCC = floor($_jloJI * $_joQOt["ListPercentage"] / 100);
          if($_JOtCC < count($_JOLLt))
            $_JOtCC = count($_JOLLt);
          if( $_JOtCC > $_jloJI )
             $_JOtCC = $_jloJI;

          # we calculate it here but it can be changed later
          $_JO8iL -= $_JOtCC;

          // random array
          if(!$_JOOQL) {


           $_Jo0fl = $_JOlli;

           $_QLlO6 = substr($_Jo0fl, 0, strpos($_Jo0fl, ' ') + 1);
           $_jLI6l = substr($_Jo0fl, strpos($_Jo0fl, 'FROM'));
           if(strpos($_Jo0fl, "DISTINCT ") !== false)
              $_Jo0fl = $_QLlO6." DISTINCT `$_I8I6o`.`id` ".$_jLI6l;
              else
              $_Jo0fl = $_QLlO6." `$_I8I6o`.`id` ".$_jLI6l;

           $_Jo08Q = $_Jo0fl;
           $_Jo1f8 = intval($_joQOt["id"] + $_JO86I);
           if($_Jo1f8 == 0)
              $_Jo1f8 = 4781;
           $_Jo08Q .= " ORDER BY RAND(". $_Jo1f8 .")";

           #print $_Jo08Q."<br />";

           $_QLfol = "INSERT IGNORE INTO `$_JOOlL` (`id`) $_Jo08Q LIMIT 0, $_JOtCC";
           #print $_QLfol."<br />";

           mysql_query($_QLfol, $_QLttI);

           $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET `CampaignsCount`=$_JOtLi, `RecipientsCountForSplitTest`=$_JOtCC, `RandomMembers_Prepared`=1 WHERE id=$_JO86I";
           mysql_query($_QLfol, $_QLttI);

           if(mysql_error($_QLttI) == "")
              $_JOOQL = 1;
          }

          if($_JOOQL && !$_JOtl8) {
            # problems with email addresses starting with a number, we concat a 'A', mysql will convert it to zero
            if( strpos($_JOlli, "DISTINCT `$_I8I6o`.`u_EMail`,") !== false )
               $_JOlli = str_replace("DISTINCT `$_I8I6o`.`u_EMail`,", "DISTINCT CONCAT('A', `$_I8I6o`.`u_EMail`),", $_JOlli);

            $_Iiloo = 0;
            $_IlJlC = "SELECT `id` FROM `$_JOOlL`";
            if($_joQOt["TestType"] == 'TestSendToListPercentage'){
               $_IlJlC .= " LIMIT 0, $_JOtCC";
            }
            $_I1O6j = mysql_query($_IlJlC, $_QLttI);
            while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
              $_Jo08Q = $_JOlli;
              $_Jo08Q = str_replace(" FROM ", ", $_JOLLt[$_Iiloo] FROM ", $_JOlli);
              $_Jo08Q .= " AND (`$_I8I6o`.`id`=$_I1OfI[id])";

              if(strpos($_JOlli, "DISTINCT ") === false)
                $_QLfol = "INSERT IGNORE INTO `$_JOOfI` (`Member_id`, `Campaigns_id`) $_Jo08Q";
                else
                $_QLfol = "INSERT IGNORE INTO `$_JOOfI` (`id`, `Member_id`, `Campaigns_id`) $_Jo08Q";

              mysql_query($_QLfol, $_QLttI);
              #print $_QLfol."\r\n";

              $_Iiloo++;
              if($_Iiloo > count($_JOLLt) - 1)
                $_Iiloo = 0;
            }
            mysql_free_result($_I1O6j);

            $_JOtl8 = 1;
          }
        } // if($_j1ofo = 'Preparing')

        if($_j1ofo == 'Preparing' && $_JOtl8){

           # save space
           $_QLfol = "TRUNCATE TABLE `$_JOOlL`";
           mysql_query($_QLfol, $_QLttI);

          if($_JOtLi == 0) {
            $_QLfol = "SELECT COUNT(id) FROM `$_joQOt[CampaignsForSplitTestTableName]`";
            $_JOCl0 = mysql_query($_QLfol, $_QLttI);
            $_JOiLo = mysql_fetch_row($_JOCl0);
            $_JOtLi = $_JOiLo[0];
            mysql_free_result($_JOCl0);
          }

          $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET SendState='Sending', `Members_Prepared`=1, `RecipientsCount`=$_jloJI, `WinnerRecipientsCount`=$_JO8iL, `CampaignsCount`=$_JOtLi WHERE id=$_JO86I";
          mysql_query($_QLfol, $_QLttI);
          $_j1ofo = 'Sending';
        }

        // Prepare members /

        // send split test campaigns

        if( $_j1ofo == 'Sending' ){

          $_JoQQl = false;
          $_JoQJj = array();
          for($_Qli6J=0; $_Qli6J<$_JOtLi; $_Qli6J++)
            $_JoQJj[$_Qli6J] = array("Campaigns_id" => 0, "CampaignsSendStat_id" => 0, "RecipientsCount" => 0);
          $_QLfol = "SELECT `CampaignsSendStat_id`, `Campaigns_id`, `RecipientsCount` FROM `$_joQOt[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_JO86I";
          $_jjJfo = mysql_query($_QLfol, $_QLttI);
          if($_jjJfo && mysql_num_rows($_jjJfo) != 0){
            $_Qli6J = 0;
            while($_jj6L6 = mysql_fetch_assoc($_jjJfo)) {
              $_JoQJj[$_Qli6J++] = array("Campaigns_id" => $_jj6L6["Campaigns_id"], "CampaignsSendStat_id" => $_jj6L6["CampaignsSendStat_id"], "RecipientsCount" => $_jj6L6["RecipientsCount"]);
            }
            $_JoQQl = $_JOtLi == $_Qli6J;
          }
          if($_jjJfo)
            mysql_free_result($_jjJfo);

          if(!$_JoQQl ){

            mysql_query("BEGIN", $_QLttI);

            if(!isset($_JOLLt)) {
              $_QLfol = "SELECT Campaigns_id FROM `$_joQOt[CampaignsForSplitTestTableName]`";
              $_JOCl0 = mysql_query($_QLfol, $_QLttI);
              while($_JOiLo = mysql_fetch_assoc($_JOCl0)){
                $_JOLLt[] = $_JOiLo["Campaigns_id"];
              }
              mysql_free_result($_JOCl0);
            }

            // calculate recipients count for each campaign
            $_Iiloo = 0;
            $_JoQJJ = 0;
            while($_JoQJJ < $_JOtCC){
              $_JoQJj[$_Iiloo]["RecipientsCount"]++;
              $_JoQJJ++;
              $_Iiloo++;
              if($_Iiloo > count($_JoQJj) - 1)
                $_Iiloo = 0;
            }

            for($_Qli6J=0; $_Qli6J<$_JOtLi; $_Qli6J++){
              if($_JoQJj[$_Qli6J]["CampaignsSendStat_id"] != 0) continue; // is done
              $_JOf6O = $_JoQJj[$_Qli6J]["RecipientsCount"];
              $_JoQJj[$_Qli6J] = array("Campaigns_id" => $_JOLLt[$_Qli6J], "CampaignsSendStat_id" => _LJ861($_JOLLt[$_Qli6J], $_JOf6O), "RecipientsCount" => $_JOf6O);

              $_QLfol = "INSERT INTO `$_joQOt[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` SET `SplitTestSendStat_id`=$_JO86I, `Campaigns_id`=".$_JoQJj[$_Qli6J]["Campaigns_id"].", `CampaignsSendStat_id`=".$_JoQJj[$_Qli6J]["CampaignsSendStat_id"].", `RecipientsCount`=".$_JOf6O;
              mysql_query($_QLfol, $_QLttI);
            }

            mysql_query("COMMIT", $_QLttI);

            $_JoQQl = true;

          } # if(!$_JoQQl )

          if($_JoQQl ){

             // change 0based index of $_JoQJj to $Campaigns_id
             $_Ql0fO = array();
             for($_Qli6J=0; $_Qli6J<$_JOtLi; $_Qli6J++){
               $_Ql0fO[$_JoQJj[$_Qli6J]["Campaigns_id"]] = $_JoQJj[$_Qli6J];/*array("CampaignsSendStat_id" => $_JoQJj[$_Qli6J]["CampaignsSendStat_id"])*/
             }
             $_JoQJj = $_Ql0fO;


             $_QLfol = "SELECT * FROM `$_JOOfI` WHERE id > $_jlOJO ORDER BY id";
             $_JOolj = mysql_query($_QLfol, $_QLttI);

             $_JJjQJ = 0;
             if(mysql_num_rows($_JOolj) > 0)
                $_JIfo0 .= "checking $_joQOt[Name]...<br />";

             mysql_query("BEGIN", $_QLttI);

             # is split test sending done?
             if(mysql_num_rows($_JOolj) == 0) {
               $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET EndSendDateTime=NOW(), SplitTestSendDone=1, `SendState`='Waiting' WHERE id=$_JO86I";
               mysql_query($_QLfol, $_QLttI);
             }

             // ECGList
             if(!isset($_jlt10))
               $_jlt10 = _JOLQE("ECGListCheck");
             // ECGList /

             $_JoIIJ = 0;
            
             while($_JoIIC = mysql_fetch_assoc($_JOolj)){
               $_JoIIJ++; 
               // last campaigns send stat id
                $_J6I01 = $_JoQJj[$_JoIIC["Campaigns_id"]]["CampaignsSendStat_id"];

                if( !isset( $_JoQJj[$_JoIIC["Campaigns_id"]]["CampaignsRow"] ) ) {
                   // MailTextInfos
                   $_QLfol = "SELECT $_QLi60.*, $_QLi60.maillists_id AS MailingListId";
                   $_QLfol .= " FROM $_QLi60 ";
                   $_QLfol .= " WHERE $_QLi60.id=$_JoIIC[Campaigns_id]";

                   $_I1O6j = mysql_query($_QLfol, $_QLttI);
                   $_J61tJ = mysql_fetch_assoc($_I1O6j);
                   mysql_free_result($_I1O6j);
                   
                   // check all mtas_id are in CurrentUsedMTAsTableName
                   $_QLfol = "SELECT DISTINCT `$_J61tJ[MTAsTableName]`.mtas_id, `$_J61tJ[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_J61tJ[MTAsTableName]` LEFT JOIN `$_J61tJ[CurrentUsedMTAsTableName]` ON `$_J61tJ[CurrentUsedMTAsTableName]`.mtas_id = `$_J61tJ[MTAsTableName]`.mtas_id WHERE `$_J61tJ[MTAsTableName]`.`Campaigns_id`=$_J61tJ[id] AND `$_J61tJ[CurrentUsedMTAsTableName]`.`SendStat_id`=$_J6I01 ORDER BY sortorder";
                   $_J6j0L = mysql_query($_QLfol, $_QLttI);
                   if($_J6j0L)
                     $_jllCj = mysql_num_rows($_J6j0L);
                     else
                     $_jllCj = 0;

                   if(!$_J6j0L || $_jllCj == 0) {
                     $_JIfo0 .= $commonmsgHTMLMTANotFound;
                     continue;
                   }

                   while($_J6jJ6 = mysql_fetch_assoc($_J6j0L)) {
                     $_J00C0 = $_J6jJ6; // save it
                     if($_J00C0["Usedmtas_id"] == "NULL") {
                       $_QLfol = "INSERT INTO `$_J61tJ[CurrentUsedMTAsTableName]` SET `SendStat_id`=$_J6I01, `mtas_id`=$_J00C0[mtas_id]";
                       mysql_query($_QLfol, $_QLttI);
                     }
                   }
                   mysql_free_result($_J6j0L);

                   # one MTA only than we can get data and merge it directly
                   if($_jllCj == 1){
                     $_J61tJ["mtas_id"] = $_J00C0["mtas_id"];
                   }

                   // save for use again
                   $_J61tJ["MTAsCount"] = $_jllCj;
                  
                  
                   // cache MailTextInfos
                   $_JoQJj[$_JoIIC["Campaigns_id"]]["CampaignsRow"] = $_J61tJ;
                   
                } else { # if( !isset( $_JoQJj[$_JoIIC["Campaigns_id"]]["CampaignsRow"] ) )
                   $_J61tJ = $_JoQJj[$_JoIIC["Campaigns_id"]]["CampaignsRow"];
                   $_jllCj = $_J61tJ["MTAsCount"];
                }


                // ECGList - we must get email addresses for checking it
                if($_jlt10 && !isset($_J61tJ["ECGChecked"])){
                  // ECG List not more than 5000
                  if($_J61tJ["MaxEMailsToProcess"] > 5000)
                    $_J61tJ["MaxEMailsToProcess"] = 5000;
                  $_JoIlQ = 0;
                  $_Joj1I = array();
                  mysql_data_seek($_JOolj, $_JoIIJ - 1);
                  while($_JojI8 = mysql_fetch_assoc($_JOolj)){
                    if($_J61tJ["id"] != $_JojI8["Campaigns_id"]) continue;
                    $_Joj1I[] = $_JojI8["Member_id"];

                    // limit reached?
                    if($_JoIlQ >= $_J61tJ["MaxEMailsToProcess"]) break;

                    $_JoIlQ++;

                  }

                  if(!isset($_J61tJ["MaillistTableName"])){
                    $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE id=$_J61tJ[MailingListId]";
                    $_I1o8o = mysql_query($_QLfol, $_QLttI);
                    if($_IQIfC = mysql_fetch_row($_I1o8o))
                      $_J61tJ["MaillistTableName"] = $_IQIfC[0];
                    mysql_free_result($_I1o8o);
                  }
                  
                  $_J06Ji = array();
                  $_JojLL = "SELECT `id`, `u_EMail` FROM `$_J61tJ[MaillistTableName]` WHERE";
                  $_Qli6J = 0;
                  $_I016j = array();
                  while($_Qli6J<count($_Joj1I)){
                    $_I016j[] = $_Joj1I[$_Qli6J];
                    $_Qli6J++;
                    if($_Qli6J % 100 == 0 || $_Qli6J == count($_Joj1I) ){
                      $_QLlO6 = "";
                      for($_QliOt=0; $_QliOt<count($_I016j); $_QliOt++){
                        if($_QLlO6 != "")
                          $_QLlO6 .= " OR";
                        $_QLlO6 .= " `id`=$_I016j[$_QliOt]";
                      }
                      $_QLfol = $_JojLL . $_QLlO6;
                      $_I016j = array();

                      $_jjl0t = mysql_query($_QLfol, $_QLttI);
                      while($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)){
                        $_J06Ji[] = array("email" => $_I8fol["u_EMail"], "id" => $_I8fol["id"]);
                      }
                      mysql_free_result($_jjl0t);
                    }
                  }

                  $_J0fIj = array();
                  $_J08Q1 = "";
                  $_J0t0L = _L6AF6($_J06Ji, $_J0fIj, $_J08Q1);
                  if(!$_J0t0L) // request failed, is ever in ECG-liste
                    $_J0fIj = $_J06Ji;
                  unset($_J06Ji);
                  mysql_data_seek($_JOolj, $_JoIIJ);
                  $_J61tJ["ECGChecked"] = true;
                  $_JoQJj[$_JoIIC["Campaigns_id"]]["CampaignsRow"] = $_J61tJ;
                }
                // ECGList /

                // ECGList
                $_J0olI = false;
                if($_jlt10){
                  $_J0olI = array_search($_JoIIC["Member_id"], array_column($_J0fIj, 'id')) !== false;
                }  
                // ECGList /  

                // limit reached?
                if($_JJjQJ >= $_J61tJ["MaxEMailsToProcess"]) break;

                if(!isset($SubjectGenerator)) // set hole Subject
                  $SubjectGenerator = new SubjectGenerator($_J61tJ["MailSubject"]);
                $_J61tJ["MailSubject"] = $SubjectGenerator->_LEEPA($_JJjQJ);  

                $_QLfol = "INSERT INTO `$_J61tJ[RStatisticsTableName]` SET `SendStat_id`=$_J6I01, `MailSubject`="._LRAFO( unhtmlentities($_J61tJ["MailSubject"], $_QLo06, false) ).", `SendDateTime`=NOW(), `recipients_id`=$_JoIIC[Member_id], `Send`='Prepared'";
                mysql_query($_QLfol, $_QLttI);

                $_JJQ6I = 0;
                if(mysql_affected_rows($_QLttI) > 0) {
                  $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
                  $_JJIl0 = mysql_fetch_array($_JJQlj);
                  $_JJQ6I = $_JJIl0[0];
                  mysql_free_result($_JJQlj);
                } else {
                  if(mysql_errno($_QLttI) == 1062)  {// dup key
                    $_QLfol = "SELECT `id` FROM `$_J61tJ[RStatisticsTableName]` WHERE `SendStat_id`=$_J6I01 AND `recipients_id`=$_JoIIC[Member_id] AND `Send`='Prepared'";
                    $_JJQlj = mysql_query($_QLfol, $_QLttI);
                    if(mysql_num_rows($_JJQlj) > 0){
                      $_JJIl0=mysql_fetch_array($_JJQlj);
                      $_JJQ6I = $_JJIl0[0];
                      mysql_free_result($_JJQlj);
                    } else {
                      mysql_free_result($_JJQlj);
                      $_JJQ6I = 0;
                    }

                  } else {
                    $_JIfo0 .= "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
                    mysql_query("ROLLBACK", $_QLttI);
                    return false;
                   }
                }

                if($_JJQ6I) {
                  if($_jllCj > 1){
                    $_J00C0 = _LO8R8($_J61tJ["CurrentUsedMTAsTableName"], $_J61tJ["MTAsTableName"], $_J6I01);
                    $_J61tJ["mtas_id"] = $_J00C0["id"];
                  }

                  if(!$_J0olI){
                    $_QLfol = "INSERT INTO `$_IQQot` SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='Campaign', `Source_id`=$_J61tJ[id], `Additional_id`=0, `SendId`=$_J6I01, `maillists_id`=$_J61tJ[MailingListId], `recipients_id`=$_JoIIC[Member_id], `mtas_id`=$_J61tJ[mtas_id], `LastSending`=NOW(), `MailSubject`="._LRAFO( unhtmlentities($_J61tJ["MailSubject"], $_QLo06, false) );
                    mysql_query($_QLfol, $_QLttI);
                    if(mysql_error($_QLttI) != "") {
                      $_JIfo0 .=  "MySQL error while adding mail to out queue: ".mysql_error($_QLttI);
                      mysql_query("ROLLBACK", $_QLttI);
                      continue;
                    }


                    $_J0J6C++;
                  }else{
                    $_QLfol = "UPDATE `$_J61tJ[RStatisticsTableName]` SET `Send`='Failed', `SendResult`=" . _LRAFO("Recipient is in ECG-Liste.") . "  WHERE `id`=$_JJQ6I";
                    mysql_query($_QLfol, $_QLttI);
                    //$_JIfo0 .= "Email with subject '$_J61tJ[MailSubject]' was not sent to to '$_I8fol[u_EMail]', Recipient is in ECG-Liste.<br />";
                  }
                  $_JJjQJ++;
                }

                //$_JIfo0 .= "Email with subject '$_J61tJ[MailSubject]' was queued for sending to '$_I8fol[u_EMail]'<br />";

                # update last member id this is here id of Members table
                $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_JoIIC[id] WHERE id=$_JO86I";
                mysql_query($_QLfol, $_QLttI);


             } # while($_JoIIC = mysql_fetch_assoc($_JOolj))
             mysql_free_result($_JOolj);


             mysql_query("COMMIT", $_QLttI);

          } # if($_JoQQl )


        } // if( $_j1ofo == 'Sending' )

        // send split test campaigns /

        // prepare winner campaign

        if( $_j1ofo == 'PreparingWinnerCampaign' && !$_JOtl8 ){

            // get mailinglist
            $_QLfol = "SELECT $_QL88I.MaillistTableName, $_QL88I.GroupsTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId ";
            $_QLfol .= " FROM $_QL88I WHERE $_QL88I.id=$_joQOt[maillists_id]";
            $_j0L0O = mysql_query($_QLfol, $_QLttI);
            $_IQIfC = mysql_fetch_assoc($_j0L0O);
            $_I8I6o = $_IQIfC["MaillistTableName"];
            mysql_free_result($_j0L0O);

            // Campaigns SQL
            $_JOlli = _LOQFJ($_JO6Co, $_I8I6o, $_IQIfC["GroupsTableName"], $_IQIfC["MailListToGroupsTableName"], $_IQIfC["LocalBlocklistTableName"]);
            $_JOlli = str_replace("`$_I8I6o`.*", "`$_I8I6o`.id", $_JOlli);

            $_JOlli = substr($_JOlli, 0, strpos_reverse($_JOlli, ".id,", strpos($_JOlli, "`MembersAge`")) + 3  ) . substr($_JOlli, strpos($_JOlli, " FROM"));

            # problems with email addresses starting with a number, we concat a 'A', mysql will convert it to zero
            if( strpos($_JOlli, "DISTINCT `$_I8I6o`.`u_EMail`,") !== false )
               $_JOlli = str_replace("DISTINCT `$_I8I6o`.`u_EMail`,", "DISTINCT CONCAT('A', `$_I8I6o`.`u_EMail`),", $_JOlli);

            $_JOlli = str_replace(" FROM ", ", $_JO6Co FROM ", $_JOlli);

            mysql_query("BEGIN", $_QLttI);

            if(strpos($_JOlli, "DISTINCT ") === false)
              $_QLfol = "INSERT IGNORE INTO `$_JOOfI` (`Member_id`, `Campaigns_id`) $_JOlli";
              else
              $_QLfol = "INSERT IGNORE INTO `$_JOOfI` (`id`, `Member_id`, `Campaigns_id`) $_JOlli";
            mysql_query($_QLfol, $_QLttI);

            $_JoJfI = mysql_affected_rows($_QLttI);
            if($_JoJfI > 0)
              $_JO8iL = $_JoJfI;

            $_JOtl8 = 1;

            mysql_query("COMMIT", $_QLttI);
        }

        if( $_j1ofo == 'PreparingWinnerCampaign' && $_JOtl8 ){
          $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET SendState='SendingWinnerCampaign', `Members_Prepared`=1, `WinnerRecipientsCount`=$_JO8iL WHERE id=$_JO86I";
          mysql_query($_QLfol, $_QLttI);
          $_j1ofo = 'SendingWinnerCampaign';
        }

        // prepare winner campaign /

        // send winner campaign

        if( $_j1ofo == 'SendingWinnerCampaign' ){

          $_QLfol = "SELECT * FROM `$_JOOfI` WHERE id > $_jlOJO ORDER BY id";
          $_JOolj = mysql_query($_QLfol, $_QLttI);

          $_JJjQJ = 0;
          if(mysql_num_rows($_JOolj) > 0)
             $_JIfo0 .= "checking $_joQOt[Name]...<br />";

          # is sending done?
          $_JoJoC = 0;
          if(mysql_num_rows($_JOolj) == 0) {
            $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET EndSendDateTime=NOW(), `WinnerCampaignSendDone`=1 WHERE id=$_JO86I";
            mysql_query($_QLfol, $_QLttI);
            $_JoJoC = 1;
          }

          if(!$_JoJoC){
            // MailTextInfos
            $_QLfol = "SELECT $_QLi60.*, $_QLi60.maillists_id AS MailingListId ";
            $_QLfol .= " FROM $_QLi60 ";
            $_QLfol .= " WHERE $_QLi60.id=$_JO6Co";

            $_I1O6j = mysql_query($_QLfol, $_QLttI);
            $_J61tJ = mysql_fetch_assoc($_I1O6j);
            mysql_free_result($_I1O6j);
            $_QLfol = "SELECT `CampaignsSendStat_id` FROM `$_joQOt[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_JO86I AND `Campaigns_id`=$_J61tJ[id]";
            $_I1O6j = mysql_query($_QLfol, $_QLttI);
            $_jj6L6 = mysql_fetch_assoc($_I1O6j);
            mysql_free_result($_I1O6j);

            // last campaigns send stat id
            $_J6I01 = $_jj6L6["CampaignsSendStat_id"];
            
            // check all mtas_id are in CurrentUsedMTAsTableName
            $_QLfol = "SELECT DISTINCT `$_J61tJ[MTAsTableName]`.mtas_id, `$_J61tJ[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_J61tJ[MTAsTableName]` LEFT JOIN `$_J61tJ[CurrentUsedMTAsTableName]` ON `$_J61tJ[CurrentUsedMTAsTableName]`.mtas_id = `$_J61tJ[MTAsTableName]`.mtas_id WHERE `$_J61tJ[CurrentUsedMTAsTableName]`.`SendStat_id`=$_J6I01 ORDER BY sortorder";
            $_J6j0L = mysql_query($_QLfol, $_QLttI);
            if($_J6j0L)
              $_jllCj = mysql_num_rows($_J6j0L);
              else
              $_jllCj = 0;

            if(!$_J6j0L || $_jllCj == 0) {
              $_JIfo0 .= $commonmsgHTMLMTANotFound;
              continue;
            }

            while($_J6jJ6 = mysql_fetch_assoc($_J6j0L)) {
              $_J00C0 = $_J6jJ6; // save it
              if($_J00C0["Usedmtas_id"] == "NULL") {
                $_QLfol = "INSERT INTO `$_J61tJ[CurrentUsedMTAsTableName]` SET `SendStat_id`=$_J6I01, `mtas_id`=$_J00C0[mtas_id]";
                mysql_query($_QLfol, $_QLttI);
              }
            }
            mysql_free_result($_J6j0L);

            # one MTA only than we can get data and merge it directly
            if($_jllCj == 1){
              $_J61tJ["mtas_id"] = $_J00C0["mtas_id"];
            }

            // ECGList
            if(!isset($_jlt10))
              $_jlt10 = _JOLQE("ECGListCheck");
            // ECGList /

            $_JoIIJ = 0;
            while($_JoIIC = mysql_fetch_assoc($_JOolj)){
               $_JoIIJ++;
               
                // ECGList - we must get email addresses for checking it
                if($_jlt10 && !isset($_J61tJ["ECGChecked"])){
                  // ECG List not more than 5000
                  if($_J61tJ["MaxEMailsToProcess"] > 5000)
                    $_J61tJ["MaxEMailsToProcess"] = 5000;
                  $_JoIlQ = 0;
                  $_Joj1I = array();
                  mysql_data_seek($_JOolj, $_JoIIJ - 1);
                  while($_JojI8 = mysql_fetch_assoc($_JOolj)){
                    $_Joj1I[] = $_JojI8["Member_id"];

                    // limit reached?
                    if($_JoIlQ >= $_J61tJ["MaxEMailsToProcess"]) break;

                    $_JoIlQ++;

                  }

                  if(!isset($_J61tJ["MaillistTableName"])){
                    $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE id=$_J61tJ[MailingListId]";
                    $_I1o8o = mysql_query($_QLfol, $_QLttI);
                    if($_IQIfC = mysql_fetch_row($_I1o8o))
                      $_J61tJ["MaillistTableName"] = $_IQIfC[0];
                    mysql_free_result($_I1o8o);
                  }
                  
                  $_J06Ji = array();
                  $_JojLL = "SELECT `id`, `u_EMail` FROM `$_J61tJ[MaillistTableName]` WHERE";
                  $_Qli6J = 0;
                  $_I016j = array();
                  while($_Qli6J<count($_Joj1I)){
                    $_I016j[] = $_Joj1I[$_Qli6J];
                    $_Qli6J++;
                    if($_Qli6J % 100 == 0 || $_Qli6J == count($_Joj1I) ){
                      $_QLlO6 = "";
                      for($_QliOt=0; $_QliOt<count($_I016j); $_QliOt++){
                        if($_QLlO6 != "")
                          $_QLlO6 .= " OR";
                        $_QLlO6 .= " `id`=$_I016j[$_QliOt]";
                      }
                      $_QLfol = $_JojLL . $_QLlO6;
                      $_I016j = array();

                      $_jjl0t = mysql_query($_QLfol, $_QLttI);
                      while($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)){
                        $_J06Ji[] = array("email" => $_I8fol["u_EMail"], "id" => $_I8fol["id"]);
                      }
                      mysql_free_result($_jjl0t);
                    }
                  }

                  $_J0fIj = array();
                  $_J08Q1 = "";
                  $_J0t0L = _L6AF6($_J06Ji, $_J0fIj, $_J08Q1);
                  if(!$_J0t0L) // request failed, is ever in ECG-liste
                    $_J0fIj = $_J06Ji;
                  unset($_J06Ji);
                  mysql_data_seek($_JOolj, $_JoIIJ);
                  $_J61tJ["ECGChecked"] = true;
                }
                // ECGList /

                // ECGList
                $_J0olI = false;
                if($_jlt10){
                  $_J0olI = array_search($_JoIIC["Member_id"], array_column($_J0fIj, 'id')) !== false;
                }  
                // ECGList /  

               // limit reached?
               if($_JJjQJ >= $_J61tJ["MaxEMailsToProcess"]) break;

               mysql_query("BEGIN", $_QLttI);

               if(!isset($SubjectGenerator)) // set hole Subject
                  $SubjectGenerator = new SubjectGenerator($_J61tJ["MailSubject"]);
               $_J61tJ["MailSubject"] = $SubjectGenerator->_LEEPA($_JJjQJ);  

               $_QLfol = "INSERT INTO `$_J61tJ[RStatisticsTableName]` SET `SendStat_id`=$_J6I01, `MailSubject`="._LRAFO(unhtmlentities($_J61tJ["MailSubject"], $_QLo06, false)).", `SendDateTime`=NOW(), `recipients_id`=$_JoIIC[Member_id], `Send`='Prepared'";
               mysql_query($_QLfol, $_QLttI);

               $_JJQ6I = 0;
               if(mysql_affected_rows($_QLttI) > 0) {
                 $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
                 $_JJIl0=mysql_fetch_array($_JJQlj);
                 $_JJQ6I = $_JJIl0[0];
                 mysql_free_result($_JJQlj);
               } else {
                 if(mysql_errno($_QLttI) == 1062) { // dup key
                   $_QLfol = "SELECT `id` FROM `$_J61tJ[RStatisticsTableName]` WHERE `SendStat_id`=$_J6I01 AND `recipients_id`=$_JoIIC[Member_id] AND `Send`='Prepared'";
                   $_JJQlj = mysql_query($_QLfol, $_QLttI);
                   if(mysql_num_rows($_JJQlj) > 0) {
                     $_JJIl0 = mysql_fetch_array($_JJQlj);
                     $_JJQ6I = $_JJIl0[0];
                     mysql_free_result($_JJQlj);
                   } else {
                     mysql_free_result($_JJQlj);
                     $_JJQ6I = 0;
                   }
                 } else {
                   $_JIfo0 .= "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
                   mysql_query("ROLLBACK", $_QLttI);
                   return false;
                  }
               }

               if($_JJQ6I) {
                 if($_jllCj > 1){
                   $_J00C0 = _LO8R8($_J61tJ["CurrentUsedMTAsTableName"], $_J61tJ["MTAsTableName"], $_J6I01);
                   $_J61tJ["mtas_id"] = $_J00C0["id"];
                 }

                 if(!$_J0olI){
                   $_QLfol = "INSERT INTO `$_IQQot` SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='Campaign', `Source_id`=$_J61tJ[id], `Additional_id`=0, `SendId`=$_J6I01, `maillists_id`=$_J61tJ[MailingListId], `recipients_id`=$_JoIIC[Member_id], `mtas_id`=$_J61tJ[mtas_id], `LastSending`=NOW(), `MailSubject`="._LRAFO(unhtmlentities($_J61tJ["MailSubject"], $_QLo06, false));
                   mysql_query($_QLfol, $_QLttI);
                   if(mysql_error($_QLttI) != "") {
                     $_JIfo0 .=  "MySQL error while adding mail to out queue: ".mysql_error($_QLttI);
                     mysql_query("ROLLBACK", $_QLttI);
                     continue;
                   }


                   $_J0J6C++;
                 }else{
                   $_QLfol = "UPDATE `$_J61tJ[RStatisticsTableName]` SET `Send`='Failed', `SendResult`=" . _LRAFO("Recipient is in ECG-Liste.") . "  WHERE `id`=$_JJQ6I";
                   mysql_query($_QLfol, $_QLttI);
                   //$_JIfo0 .= "Email with subject '$_J61tJ[MailSubject]' was not sent to to '$_I8fol[u_EMail]', Recipient is in ECG-Liste.<br />";
                  }
                 $_JJjQJ++;
               }

               //$_JIfo0 .= "Email with subject '$_J61tJ[MailSubject]' was queued for sending to '$_I8fol[u_EMail]'<br />";

               # update last member id this is here id of Members table
               $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_JoIIC[id] WHERE id=$_JO86I";
               mysql_query($_QLfol, $_QLttI);


               mysql_query("COMMIT", $_QLttI);

            } # while($_JoIIC = mysql_fetch_assoc($_JOolj))
            mysql_free_result($_JOolj);


          } # if(!$_JoJoC)

        } # if( $_j1ofo == 'SendingWinnerCampaign' )
        // send winner campaign /

        if($_JJjQJ > 0) {
           $_JIfo0 .= "$_joQOt[Name] checked, $_JJjQJ email(s) sent to queue.<br />";
        }

      } // while($_JOJjJ && $_joQOt = mysql_fetch_assoc($_JOJjJ))
      mysql_free_result($_JOJjJ);

    } // while($_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
    mysql_free_result($_QL8i1);

    $_JIfo0 .= "<br />$_J0J6C emails sent to queue<br />";
    $_JIfo0 .= "<br />Split tests checking end.";

    if($_J0J6C)
      return true;
      else
      return -1;
  }

  function _LJ861($CampaignId, $_jloJI){
    global $_QLttI, $_QLi60;
    $_jlOCl = 0;

    $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$CampaignId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_J61tJ = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    mysql_query("BEGIN", $_QLttI);

    // CurrentSendTableName
    $_QLfol = "INSERT INTO `$_J61tJ[CurrentSendTableName]` SET `Campaigns_id`=$CampaignId, StartSendDateTime=NOW(), EndSendDateTime=NOW(), RecipientsCount=$_jloJI";
    mysql_query($_QLfol, $_QLttI);
    $_J6QJI= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
    $_IQjl0=mysql_fetch_row($_J6QJI);
    $_jlOCl = $_IQjl0[0];
    $_J61tJ["CurrentSendId"] = $_jlOCl;
    mysql_free_result($_J6QJI);

    /*
    // Twitter Update start

    .... cron_campaigns.inc.php

    // Twitter Update end
    */

    // SET Current used MTAs to zero
    $_QLfol = "INSERT INTO `$_J61tJ[CurrentUsedMTAsTableName]` SELECT 0, $_jlOCl, `mtas_id`, 0 FROM `$_J61tJ[MTAsTableName]` WHERE `Campaigns_id`=$_J61tJ[id] ORDER BY sortorder";
    mysql_query($_QLfol, $_QLttI);

    // Archive Table
    $_QLfol = "INSERT INTO `$_J61tJ[ArchiveTableName]` SET SendStat_id=$_jlOCl, ";
    $_QLfol .= "MailFormat="._LRAFO($_J61tJ["MailFormat"]).", ";
    $_QLfol .= "MailEncoding="._LRAFO($_J61tJ["MailEncoding"]).", ";
    $_QLfol .= "MailSubject="._LRAFO($_J61tJ["MailSubject"]).", ";
    $_QLfol .= "MailPlainText="._LRAFO($_J61tJ["MailPlainText"]).", ";
    $_QLfol .= "MailHTMLText="._LRAFO($_J61tJ["MailHTMLText"]).", ";
    $_QLfol .= "Attachments="._LRAFO($_J61tJ["Attachments"]);
    mysql_query($_QLfol, $_QLttI);

    mysql_query("COMMIT", $_QLttI);

    return $_jlOCl;
  }


?>
