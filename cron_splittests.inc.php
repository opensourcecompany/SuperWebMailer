<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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


  function _ORCAO(&$_j6O8O) {
    global $_Q61I1, $_Q6JJJ;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Qo8OO, $resourcestrings, $_Q8f1L, $_Q880O;
    global $_Qofoi, $_Ql8C0, $_I88i8, $_Q60QL, $_QtjLI;
    global $_Q6jOo, $_IooOQ, $_jJQ66;
    global $commonmsgHTMLMTANotFound;

    $_j6O8O = "Split tests checking starts...<br />";
    $_jIojl = 0;

    $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE UserType='Admin' AND IsActive>0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
      _OPQ6J();
      $UserId = $_Q6Q1C["id"];
      $OwnerUserId = 0;
      $Username = $_Q6Q1C["Username"];
      $UserType = $_Q6Q1C["UserType"];
      $AccountType = $_Q6Q1C["AccountType"];
      $INTERFACE_THEMESID = $_Q6Q1C["ThemesId"];
      $INTERFACE_LANGUAGE = $_Q6Q1C["Language"];

      _OP10J($INTERFACE_LANGUAGE);

      _LQLRQ($INTERFACE_LANGUAGE);

      $_QJlJ0 = "SELECT Theme FROM $_Q880O WHERE id=$INTERFACE_THEMESID";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      $_IlC1O = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_IlC1O[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);


      // check sent split tests

      $_QJlJ0 = "SELECT `$_IooOQ`.*, ";
      $_QJlJ0 .= "`$_Q60QL`.`users_id`, `$_Q60QL`.`MaillistTableName`, `$_Q60QL`.id AS MailingListId ";
      $_QJlJ0 .= " FROM `$_IooOQ` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.`id`=`$_IooOQ`.`maillists_id`";
      $_QJlJ0 .= " WHERE (`$_IooOQ`.`SetupLevel`=99 AND `$_IooOQ`.`SendScheduler`<>'SaveOnly')";
      $_jOl01 = mysql_query($_QJlJ0, $_Q61I1);
      while($_jOl01 && $_IlC1O = mysql_fetch_assoc($_jOl01)) {
        _OPQ6J();

        // check for waiting/sent split tests
        $_QJlJ0 = "SELECT * ";
        $_QJlJ0 .= "FROM `$_IlC1O[CurrentSendTableName]` WHERE (SendState='Waiting' AND SplitTestSendDone<>0) OR (SendState='SendingWinnerCampaign' AND SplitTestSendDone<>0 AND WinnerCampaignSendDone<>0)";
        $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
        while($_j8oC1 && $_Qt6f8 = mysql_fetch_assoc($_j8oC1) ) {
           _OPQ6J();

           $_IlC1O["WinnerType"] = $_Qt6f8["WinnerType"];
           $_IlC1O["TestType"] = $_Qt6f8["TestType"];
           $_IlC1O["ListPercentage"] = $_Qt6f8["ListPercentage"];

           // get Campaigns_id and CampaignsSendStat_id for outqueue checking
           $_QJlJ0 = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_IlC1O[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_Qt6f8[id]";
           $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
           $_IlCQJ = array();
           while($_Q8OiJ = mysql_fetch_assoc($_ItlJl)) {
             $_IlCQJ[] = array("Campaigns_id" => $_Q8OiJ["Campaigns_id"], "CampaignsSendStat_id" => $_Q8OiJ["CampaignsSendStat_id"], "RecipientsCount" => $_Q8OiJ["RecipientsCount"]);
           }
           mysql_free_result($_ItlJl);

           # anything of campaignS in outqueue?
           $_QJlJ0 = "SELECT COUNT(id) FROM `$_QtjLI` WHERE (`users_id`=$UserId AND `Source`='Campaign' AND `Additional_id`=0) AND ( ";

           $_QtjtL = "";
           for($_Q6llo=0; $_Q6llo<count($_IlCQJ); $_Q6llo++) {
             if($_QtjtL != "")
               $_QtjtL .= " OR ";
             $_QtjtL .= "(`Source_id`=".$_IlCQJ[$_Q6llo]["Campaigns_id"]." AND `SendId`=".$_IlCQJ[$_Q6llo]["CampaignsSendStat_id"].")";
           }
           $_QJlJ0 .= $_QtjtL." )";

           $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
           $_IO08Q = mysql_fetch_row($_ItlJl);
           mysql_free_result($_ItlJl);
           if($_IO08Q[0] > 0) { // not done?
             continue;
           }

           // campaign sent to all members?
           if($_IlC1O["TestType"] == 'TestSendToAllMembers' && $_Qt6f8["SendState"] == 'Waiting'){ # Split Test is done completly

             for($_Q6llo=0; $_Q6llo<count($_IlCQJ); $_Q6llo++) {
               // $_j8o0f
               $_QJlJ0 = "SELECT `$_Q6jOo`.id, `$_Q6jOo`.Name AS CampaignsName, `$_Q6jOo`.Creator_users_id, `$_Q6jOo`.CurrentSendTableName, ";
               $_QJlJ0 .= "`$_Q6jOo`.RStatisticsTableName, `$_Q6jOo`.MTAsTableName, `$_Q6jOo`.maillists_id, ";
               $_QJlJ0 .= "`$_Q6jOo`.SendReportToYourSelf, `$_Q6jOo`.SendReportToListAdmin, `$_Q6jOo`.SendReportToMailingListUsers, `$_Q6jOo`.SendReportToEMailAddress, `$_Q6jOo`.SendReportToEMailAddressEMailAddress, ";
               $_QJlJ0 .= "`$_Q60QL`.users_id, `$_Q60QL`.MaillistTableName, `$_Q60QL`.id AS MailingListId ";
               $_QJlJ0 .= " FROM `$_Q6jOo` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_Q6jOo`.maillists_id";
               $_QJlJ0 .= " WHERE `$_Q6jOo`.id =".$_IlCQJ[$_Q6llo]["Campaigns_id"];
               $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
               $_j8o0f = mysql_fetch_assoc($_ItlJl);
               mysql_free_result($_ItlJl);

               # set Campaign done state
               $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET `CampaignSendDone`=1 WHERE id=".$_IlCQJ[$_Q6llo]["CampaignsSendStat_id"];
               mysql_query($_QJlJ0, $_Q61I1);

               # check Campaign set state and send reports cron_campaigns.inc.php
               _ORQOF($_j8o0f, $_j6O8O);

             }

             // Update ReSendFlag
             $_QJlJ0 = "UPDATE `$_IooOQ` SET `ReSendFlag`=0 WHERE id=$_IlC1O[id]";
             mysql_query($_QJlJ0, $_Q61I1);

             // SET DONE STATE
             $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET EndSendDateTime=NOW(), SendState='Done', `WinnerCampaignSendDone`=1 WHERE id=$_Qt6f8[id]";
             mysql_query($_QJlJ0, $_Q61I1);

             continue;

           } # if($_IlC1O["TestType"] == 'TestSendToAllMembers' && $_Qt6f8["SendState"] == 'Waiting')
           // campaign sent to all members? /

           // get winner campaign
           if($_IlC1O["TestType"] == 'TestSendToListPercentage' && $_Qt6f8["SendState"] == 'Waiting'){ # Split Test is sent now check for "Winner" to send campaign
             // get winner of split test
             $_jOljL = 0;
             $_jOlif = _LLLQF($_IlC1O, $_Qt6f8, $_IlCQJ, true, $_jOljL);

             if($_jOlif != 0) {
               # Get recipients count for winner campaign
               $_jo0Jl = 0;
               for($_Q6llo=0; $_Q6llo<count($_IlCQJ); $_Q6llo++){
                 $_jo0Jl += $_IlCQJ[$_Q6llo]["RecipientsCount"];
               }
               if($_Qt6f8["RecipientsCount"] - $_jo0Jl < 0)
                 $_jo0Jl = $_Qt6f8["RecipientsCount"];

               $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET EndSendDateTime=NOW(), SendState='PreparingWinnerCampaign', `Members_Prepared`=0, `WinnerCampaigns_id`=$_jOlif, `WinnerCampaignsMaxClicks`=$_jOljL, `WinnerRecipientsCount`=".intval($_Qt6f8["RecipientsCount"] - $_jo0Jl)." WHERE id=$_Qt6f8[id]";
               mysql_query($_QJlJ0, $_Q61I1);

               $_QJlJ0 = "SELECT `CurrentSendTableName` FROM `$_Q6jOo` WHERE id=$_jOlif";
               $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
               $_j8o0f = mysql_fetch_assoc($_Q8Oj8);
               mysql_free_result($_Q8Oj8);

               for($_Q6llo=0; $_Q6llo<count($_IlCQJ); $_Q6llo++){
                 if($_IlCQJ[$_Q6llo]["Campaigns_id"] == $_jOlif) {
                   // Update winner RecipientsCount
                   $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET `RecipientsCount`=".intval($_Qt6f8["RecipientsCount"] - $_jo0Jl)." WHERE id=".$_IlCQJ[$_Q6llo]["CampaignsSendStat_id"];
                   mysql_query($_QJlJ0, $_Q61I1);
                   break;
                 }
               }

               continue;
             }
           } // if($_IlC1O["TestType"] == 'TestSendToListPercentage' && $_Qt6f8["SendState"] == 'Waiting')
           // get winner campaign /

           // is winner campaign sent completly?
           if($_IlC1O["TestType"] == 'TestSendToListPercentage' && $_Qt6f8["SendState"] == 'SendingWinnerCampaign' && $_Qt6f8["SplitTestSendDone"] != 0 && $_Qt6f8["WinnerCampaignSendDone"] != 0){ # Split Test is sent now check for "Winner" to send campaign

             for($_Q6llo=0; $_Q6llo<count($_IlCQJ); $_Q6llo++) {
               // $_j8o0f
               $_QJlJ0 = "SELECT `$_Q6jOo`.id, `$_Q6jOo`.Name AS CampaignsName, `$_Q6jOo`.Creator_users_id, `$_Q6jOo`.CurrentSendTableName, ";
               $_QJlJ0 .= "`$_Q6jOo`.RStatisticsTableName, `$_Q6jOo`.MTAsTableName, `$_Q6jOo`.maillists_id, ";
               $_QJlJ0 .= "`$_Q6jOo`.SendReportToYourSelf, `$_Q6jOo`.SendReportToListAdmin, `$_Q6jOo`.SendReportToMailingListUsers, `$_Q6jOo`.SendReportToEMailAddress, `$_Q6jOo`.SendReportToEMailAddressEMailAddress, ";
               $_QJlJ0 .= "`$_Q60QL`.users_id, `$_Q60QL`.MaillistTableName, `$_Q60QL`.id AS MailingListId ";
               $_QJlJ0 .= " FROM `$_Q6jOo` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_Q6jOo`.maillists_id";
               $_QJlJ0 .= " WHERE `$_Q6jOo`.id =".$_IlCQJ[$_Q6llo]["Campaigns_id"];
               $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
               $_j8o0f = mysql_fetch_assoc($_ItlJl);
               mysql_free_result($_ItlJl);

               # set Campaign done state
               $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET `CampaignSendDone`=1 WHERE id=".$_IlCQJ[$_Q6llo]["CampaignsSendStat_id"];
               mysql_query($_QJlJ0, $_Q61I1);

               # check Campaign set state and send reports cron_campaigns.inc.php
               _ORQOF($_j8o0f, $_j6O8O);

             }

             // Update ReSendFlag
             $_QJlJ0 = "UPDATE `$_IooOQ` SET `ReSendFlag`=0 WHERE `id`=$_IlC1O[id]";
             mysql_query($_QJlJ0, $_Q61I1);

             // SET DONE STATE
             $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `SendState`='Done' WHERE `id`=$_Qt6f8[id]";
             mysql_query($_QJlJ0, $_Q61I1);


           } // if($_IlC1O["TestType"] == 'TestSendToListPercentage' && $_Qt6f8["SendState"] == 'SendingWinnerCampaign' && $_Qt6f8["SplitTestSendDone"] != 0 && $_Qt6f8["WinnerCampaignSendDone"] != 0)
           // is winner campaign sent completly? /


        } # while($_j8oC1 && $_Qt6f8 = mysql_fetch_assoc($_j8oC1) )
        mysql_free_result($_j8oC1);
        // check for waiting/sent split tests /

        // check for resend emails of split test

        $_QJlJ0 = "SELECT * ";
        $_QJlJ0 .= "FROM `$_IlC1O[CurrentSendTableName]` WHERE SendState='ReSending'";
        $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
        while($_j8oC1 && $_Qt6f8 = mysql_fetch_assoc($_j8oC1) ) {
           _OPQ6J();

           // get Campaigns_id and CampaignsSendStat_id for outqueue checking
           $_QJlJ0 = "SELECT `Campaigns_id`, `CampaignsSendStat_id`, `RecipientsCount` FROM `$_IlC1O[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_Qt6f8[id]";
           $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
           $_IlCQJ = array();
           while($_Q8OiJ = mysql_fetch_assoc($_ItlJl)) {
             $_IlCQJ[] = array("Campaigns_id" => $_Q8OiJ["Campaigns_id"], "CampaignsSendStat_id" => $_Q8OiJ["CampaignsSendStat_id"], "RecipientsCount" => $_Q8OiJ["RecipientsCount"]);
           }
           mysql_free_result($_ItlJl);

           # anything of campaignS in outqueue?
           $_QJlJ0 = "SELECT COUNT(id) FROM `$_QtjLI` WHERE (`users_id`=$UserId AND `Source`='Campaign' AND `Additional_id`=0) AND ( ";

           $_QtjtL = "";
           for($_Q6llo=0; $_Q6llo<count($_IlCQJ); $_Q6llo++) {
             if($_QtjtL != "")
               $_QtjtL .= " OR ";
             $_QtjtL .= "(`Source_id`=".$_IlCQJ[$_Q6llo]["Campaigns_id"]." AND `SendId`=".$_IlCQJ[$_Q6llo]["CampaignsSendStat_id"].")";
           }
           $_QJlJ0 .= $_QtjtL." )";

           $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
           $_IO08Q = mysql_fetch_row($_ItlJl);
           mysql_free_result($_ItlJl);
           if($_IO08Q[0] > 0) { // not done?
             continue;
           }

           for($_Q6llo=0; $_Q6llo<count($_IlCQJ); $_Q6llo++) {
             // $_j8o0f
             $_QJlJ0 = "SELECT `$_Q6jOo`.id, `$_Q6jOo`.Name AS CampaignsName, `$_Q6jOo`.Creator_users_id, `$_Q6jOo`.CurrentSendTableName, ";
             $_QJlJ0 .= "`$_Q6jOo`.RStatisticsTableName, `$_Q6jOo`.MTAsTableName, `$_Q6jOo`.maillists_id, ";
             $_QJlJ0 .= "`$_Q6jOo`.SendReportToYourSelf, `$_Q6jOo`.SendReportToListAdmin, `$_Q6jOo`.SendReportToMailingListUsers, `$_Q6jOo`.SendReportToEMailAddress, `$_Q6jOo`.SendReportToEMailAddressEMailAddress, ";
             $_QJlJ0 .= "`$_Q60QL`.users_id, `$_Q60QL`.MaillistTableName, `$_Q60QL`.id AS MailingListId ";
             $_QJlJ0 .= " FROM `$_Q6jOo` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_Q6jOo`.maillists_id";
             $_QJlJ0 .= " WHERE `$_Q6jOo`.id =".$_IlCQJ[$_Q6llo]["Campaigns_id"];
             $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
             $_j8o0f = mysql_fetch_assoc($_ItlJl);
             mysql_free_result($_ItlJl);

             # set Campaign done state
             $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET `CampaignSendDone`=1 WHERE id=".$_IlCQJ[$_Q6llo]["CampaignsSendStat_id"];
             mysql_query($_QJlJ0, $_Q61I1);

             # check Campaign set state and send reports cron_campaigns.inc.php
             _ORQOF($_j8o0f, $_j6O8O);

           }

           // SET DONE STATE
           $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET `SendState`='Done' WHERE `id`=$_Qt6f8[id]";
           mysql_query($_QJlJ0, $_Q61I1);


        }
        mysql_free_result($_j8oC1);

        // check for resend emails of split test /

      } # while($_IlC1O = mysql_fetch_assoc($_jOl01))

      if($_jOl01)
        mysql_free_result($_jOl01);

      // check sent split tests done

      // check to send split tests

      $_QJlJ0 = "SELECT `$_IooOQ`.* ";
      $_QJlJ0 .= " FROM `$_IooOQ` ";
      $_QJlJ0 .= " WHERE (`$_IooOQ`.SetupLevel=99 AND `$_IooOQ`.SendScheduler<>'SaveOnly') ";

      $_QJlJ0 .= " AND (";
      $_QJlJ0 .= " IF(`$_IooOQ`.SendScheduler = 'SendInFutureOnce', NOW()>=`$_IooOQ`.SendInFutureOnceDateTime, 0)";
      $_QJlJ0 .= " OR `$_IooOQ`.SendScheduler = 'SendImmediately'";
      $_QJlJ0 .= ")";

      $_jOl01 = mysql_query($_QJlJ0, $_Q61I1);

      while($_jOl01 && $_IlC1O = mysql_fetch_assoc($_jOl01)) {
        _OPQ6J();

        // we can't check this in sql above
        if( $_IlC1O["SendScheduler"] == 'SendImmediately' || $_IlC1O["SendScheduler"] == 'SendInFutureOnce' ) {
          $_QJlJ0 = "SELECT COUNT(id) FROM `$_IlC1O[CurrentSendTableName]` WHERE `SendState`='Done'";
          $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Qt6f8 = mysql_fetch_row($_j8oC1);
          if($_Qt6f8[0] > 0) { // always send?
            if($_IlC1O["ReSendFlag"] < 1) {// send again?
               mysql_free_result($_j8oC1);
               continue;
            }
          }
          mysql_free_result($_j8oC1);
        }


        // CurrentSendTableName
        $_jQlit = 0;
        $_jOlif = 0;
        $_jo1jj = 0;
        $_jI0Oo = 0;
        $_jo1oi = 0;
        $_joQJ1 = 0;
        $_joI08 = 0;
        $_I81l8 = 'Preparing';
        $_joIlj = 0;
        $_jojJo = 0;
        $_jojij = "";

        $_QJlJ0 = "SELECT * ";
        $_QJlJ0 .= "FROM `$_IlC1O[CurrentSendTableName]` WHERE SendState<>'Done' AND SendState<>'Paused'";

        $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_j8oC1) > 0) {
          $_Qt6f8 = mysql_fetch_assoc($_j8oC1);
          mysql_free_result($_j8oC1);
          if($_Qt6f8["SplitTestSendDone"] && $_Qt6f8["WinnerCampaigns_id"] == 0 ) // splittest always send completly, but no winner at this time?
            continue;
          if($_Qt6f8["SplitTestSendDone"] && $_Qt6f8["WinnerCampaignSendDone"]) // splittest and winner campaign always send completly?
            continue;
          $_jQlit = $_Qt6f8["LastMember_id"];
          $_jo1jj = $_Qt6f8["id"];
          $_jI0Oo = $_Qt6f8["RecipientsCount"];
          $_jo1oi = $_Qt6f8["WinnerRecipientsCount"];
          $_joQJ1 = $_Qt6f8["RecipientsCountForSplitTest"];
          $_joI08 = $_Qt6f8["CampaignsCount"];
          $_I81l8 = $_Qt6f8["SendState"];
          $_joIlj = $_Qt6f8["Members_Prepared"];
          $_jojJo = $_Qt6f8["RandomMembers_Prepared"];
          $_jojij = $_Qt6f8["MembersTableName"];
          $_joJjo = $_Qt6f8["RandomMembersTableName"];
          $_jOlif = $_Qt6f8["WinnerCampaigns_id"];
          $_IlC1O["WinnerType"] = $_Qt6f8["WinnerType"];
          $_IlC1O["TestType"] = $_Qt6f8["TestType"];
          $_IlC1O["ListPercentage"] = $_Qt6f8["ListPercentage"];

        } else{
          mysql_free_result($_j8oC1);

          $_jo60O = TablePrefix._OA0LA($_IlC1O['Name']);
          $_jojij = _OALO0($_jo60O."_testmembers");
          $_joJjo = _OALO0($_jo60O."_testrandommembers");

          $_Ij6Io = join("", file(_O68A8()."splittest_members.sql"));
          $_Ij6Io = str_replace('`TABLE_SPLITTEST_MEMBERS`', $_jojij, $_Ij6Io);
          $_Ij6Io = str_replace('`TABLE_SPLITTEST_RANDOMMEMBERS`', $_joJjo, $_Ij6Io);
          $_Ij6il = explode(";", $_Ij6Io);

          for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
            if(trim($_Ij6il[$_Q6llo]) == "") continue;
            $_jo6Qo = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
            if(!$_jo6Qo)
              $_jo6Qo = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
          }

          // CurrentSendTableName
          $_QJlJ0 = "INSERT INTO `$_IlC1O[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW(), `MembersTableName`="._OPQLR($_jojij).", `RandomMembersTableName`="._OPQLR($_joJjo);
          $_QJlJ0 .= ", `WinnerType`="._OPQLR($_IlC1O["WinnerType"]).", `TestType`="._OPQLR($_IlC1O["TestType"]).", `ListPercentage`=".$_IlC1O["ListPercentage"];
          mysql_query($_QJlJ0, $_Q61I1);
          $_j8oC1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
          $_Qt6f8=mysql_fetch_row($_j8oC1);
          $_jo1jj = $_Qt6f8[0];
          $_IlC1O["CurrentSendId"] = $_jo1jj;
          mysql_free_result($_j8oC1);

        }

        // Prepare members
        if($_I81l8 == 'Preparing' && !$_joIlj){
          // get mailinglist
          $_QJlJ0 = "SELECT `$_Q60QL`.MaillistTableName, `$_Q60QL`.GroupsTableName, `$_Q60QL`.MailListToGroupsTableName, `$_Q60QL`.LocalBlocklistTableName, `$_Q60QL`.id AS MailingListId ";
          $_QJlJ0 .= " FROM `$_Q60QL` WHERE `$_Q60QL`.id=$_IlC1O[maillists_id]";
          $_jo6Ij = mysql_query($_QJlJ0, $_Q61I1);
          $_QtJ8t = mysql_fetch_assoc($_jo6Ij);
          $_QlQC8 = $_QtJ8t["MaillistTableName"];
          mysql_free_result($_jo6Ij);

          $_QJlJ0 = "SELECT COUNT(id) FROM `$_IlC1O[CampaignsForSplitTestTableName]`";
          $_jofQ8 = mysql_query($_QJlJ0, $_Q61I1);
          $_jof8J = mysql_fetch_row($_jofQ8);
          $_joI08 = $_jof8J[0];
          mysql_free_result($_jofQ8);

          $_jI0Oo = 0;
          $_jo8fQ = array();

          // get campaign_ids
          $_jo8Oj = 0;
          $_QJlJ0 = "SELECT Campaigns_id FROM `$_IlC1O[CampaignsForSplitTestTableName]`";
          $_jofQ8 = mysql_query($_QJlJ0, $_Q61I1);
          $_jo8oI = "";
          while($_jof8J = mysql_fetch_assoc($_jofQ8)){
            if($_jI0Oo == 0) {
              $_jo8oL = "";
              if($_jo8Oj == 0)
                $_jo8Oj = _O6QLR($_jof8J["Campaigns_id"], $_jo8oL, $_QlQC8, $_QtJ8t["GroupsTableName"], $_QtJ8t["MailListToGroupsTableName"], $_QtJ8t["LocalBlocklistTableName"]);
              $_jo8fQ[] = $_jof8J["Campaigns_id"];
            }
            if($_jo8oI == "") {
              $_jo8oI = _O610A($_jof8J["Campaigns_id"], $_QlQC8, $_QtJ8t["GroupsTableName"], $_QtJ8t["MailListToGroupsTableName"], $_QtJ8t["LocalBlocklistTableName"]);
              $_jo8oI = str_replace("`$_QlQC8`.*", "`$_QlQC8`.id", $_jo8oI);
              $_jo8oI = substr($_jo8oI, 0, strpos_reverse($_jo8oI, ".id,", strpos($_jo8oI, "`MembersAge`")) + 3  ) . substr($_jo8oI, strpos($_jo8oI, " FROM"));
            }
          }
          mysql_free_result($_jofQ8);
          if($_jI0Oo == 0 && $_jo8Oj > 0)
            $_jI0Oo = $_jo8Oj;
          if($_jo1oi == 0 && $_jo8Oj > 0)
            $_jo1oi = $_jo8Oj;


          if($_IlC1O["TestType"] == 'TestSendToAllMembers'){
            $_IlC1O["ListPercentage"] = 100;
          }

          $_joQJ1 = floor($_jI0Oo * $_IlC1O["ListPercentage"] / 100);
          if($_joQJ1 < count($_jo8fQ))
            $_joQJ1 = count($_jo8fQ);
          if( $_joQJ1 > $_jI0Oo )
             $_joQJ1 = $_jI0Oo;

          # we calculate it here but it can be changed later
          $_jo1oi -= $_joQJ1;

          // random array
          if(!$_jojJo) {


           $_jotj8 = $_jo8oI;

           $_QtjtL = substr($_jotj8, 0, strpos($_jotj8, ' ') + 1);
           $_j1toJ = substr($_jotj8, strpos($_jotj8, 'FROM'));
           if(strpos($_jotj8, "DISTINCT ") !== false)
              $_jotj8 = $_QtjtL." DISTINCT `$_QlQC8`.`id` ".$_j1toJ;
              else
              $_jotj8 = $_QtjtL." `$_QlQC8`.`id` ".$_j1toJ;

           $_jot66 = $_jotj8;
           $_joOJI = intval($_IlC1O["id"] + $_jo1jj);
           if($_joOJI == 0)
              $_joOJI = 4781;
           $_jot66 .= " ORDER BY RAND(". $_joOJI .")";

           #print $_jot66."<br />";

           $_QJlJ0 = "INSERT IGNORE INTO `$_joJjo` (`id`) $_jot66 LIMIT 0, $_joQJ1";
           #print $_QJlJ0."<br />";

           mysql_query($_QJlJ0, $_Q61I1);

           $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET `CampaignsCount`=$_joI08, `RecipientsCountForSplitTest`=$_joQJ1, `RandomMembers_Prepared`=1 WHERE id=$_jo1jj";
           mysql_query($_QJlJ0, $_Q61I1);

           if(mysql_error($_Q61I1) == "")
              $_jojJo = 1;
          }

          if($_jojJo && !$_joIlj) {
            # problems with email addresses starting with a number, we concat a 'A', mysql will convert it to zero
            if( strpos($_jo8oI, "DISTINCT `$_QlQC8`.`u_EMail`,") !== false )
               $_jo8oI = str_replace("DISTINCT `$_QlQC8`.`u_EMail`,", "DISTINCT CONCAT('A', `$_QlQC8`.`u_EMail`),", $_jo8oI);

            $_IJQOL = 0;
            $_I6jtf = "SELECT `id` FROM `$_joJjo`";
            if($_IlC1O["TestType"] == 'TestSendToListPercentage'){
               $_I6jtf .= " LIMIT 0, $_joQJ1";
            }
            $_Q8Oj8 = mysql_query($_I6jtf, $_Q61I1);
            while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)){
              $_jot66 = $_jo8oI;
              $_jot66 = str_replace(" FROM ", ", $_jo8fQ[$_IJQOL] FROM ", $_jo8oI);
              $_jot66 .= " AND (`$_QlQC8`.`id`=$_Q8OiJ[id])";

              if(strpos($_jo8oI, "DISTINCT ") === false)
                $_QJlJ0 = "INSERT IGNORE INTO `$_jojij` (`Member_id`, `Campaigns_id`) $_jot66";
                else
                $_QJlJ0 = "INSERT IGNORE INTO `$_jojij` (`id`, `Member_id`, `Campaigns_id`) $_jot66";

              mysql_query($_QJlJ0, $_Q61I1);
              #print $_QJlJ0."\r\n";

              $_IJQOL++;
              if($_IJQOL > count($_jo8fQ) - 1)
                $_IJQOL = 0;
            }
            mysql_free_result($_Q8Oj8);

            $_joIlj = 1;
          }
        } // if($_I81l8 = 'Preparing')

        if($_I81l8 == 'Preparing' && $_joIlj){

           # save space
           $_QJlJ0 = "TRUNCATE TABLE `$_joJjo`";
           mysql_query($_QJlJ0, $_Q61I1);

          if($_joI08 == 0) {
            $_QJlJ0 = "SELECT COUNT(id) FROM `$_IlC1O[CampaignsForSplitTestTableName]`";
            $_jofQ8 = mysql_query($_QJlJ0, $_Q61I1);
            $_jof8J = mysql_fetch_row($_jofQ8);
            $_joI08 = $_jof8J[0];
            mysql_free_result($_jofQ8);
          }

          $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET SendState='Sending', `Members_Prepared`=1, `RecipientsCount`=$_jI0Oo, `WinnerRecipientsCount`=$_jo1oi, `CampaignsCount`=$_joI08 WHERE id=$_jo1jj";
          mysql_query($_QJlJ0, $_Q61I1);
          $_I81l8 = 'Sending';
        }

        // Prepare members /

        // send split test campaigns

        if( $_I81l8 == 'Sending' ){

          $_jooJ0 = false;
          $_joC1O = array();
          for($_Q6llo=0; $_Q6llo<$_joI08; $_Q6llo++)
            $_joC1O[$_Q6llo] = array("Campaigns_id" => 0, "CampaignsSendStat_id" => 0, "RecipientsCount" => 0);
          $_QJlJ0 = "SELECT `CampaignsSendStat_id`, `Campaigns_id`, `RecipientsCount` FROM `$_IlC1O[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_jo1jj";
          $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
          if($_ItlJl && mysql_num_rows($_ItlJl) != 0){
            $_Q6llo = 0;
            while($_IO08Q = mysql_fetch_assoc($_ItlJl)) {
              $_joC1O[$_Q6llo++] = array("Campaigns_id" => $_IO08Q["Campaigns_id"], "CampaignsSendStat_id" => $_IO08Q["CampaignsSendStat_id"], "RecipientsCount" => $_IO08Q["RecipientsCount"]);
            }
            $_jooJ0 = $_joI08 == $_Q6llo;
          }
          if($_ItlJl)
            mysql_free_result($_ItlJl);

          if(!$_jooJ0 ){

            mysql_query("BEGIN", $_Q61I1);

            if(!isset($_jo8fQ)) {
              $_QJlJ0 = "SELECT Campaigns_id FROM `$_IlC1O[CampaignsForSplitTestTableName]`";
              $_jofQ8 = mysql_query($_QJlJ0, $_Q61I1);
              while($_jof8J = mysql_fetch_assoc($_jofQ8)){
                $_jo8fQ[] = $_jof8J["Campaigns_id"];
              }
              mysql_free_result($_jofQ8);
            }

            // calculate recipients count for each campaign
            $_IJQOL = 0;
            $_joCCl = 0;
            while($_joCCl < $_joQJ1){
              $_joC1O[$_IJQOL]["RecipientsCount"]++;
              $_joCCl++;
              $_IJQOL++;
              if($_IJQOL > count($_joC1O) - 1)
                $_IJQOL = 0;
            }

            for($_Q6llo=0; $_Q6llo<$_joI08; $_Q6llo++){
              if($_joC1O[$_Q6llo]["CampaignsSendStat_id"] != 0) continue; // is done
              $_jo0Jl = $_joC1O[$_Q6llo]["RecipientsCount"];
              $_joC1O[$_Q6llo] = array("Campaigns_id" => $_jo8fQ[$_Q6llo], "CampaignsSendStat_id" => _ORD0L($_jo8fQ[$_Q6llo], $_jo0Jl), "RecipientsCount" => $_jo0Jl);

              $_QJlJ0 = "INSERT INTO `$_IlC1O[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` SET `SplitTestSendStat_id`=$_jo1jj, `Campaigns_id`=".$_joC1O[$_Q6llo]["Campaigns_id"].", `CampaignsSendStat_id`=".$_joC1O[$_Q6llo]["CampaignsSendStat_id"].", `RecipientsCount`=".$_jo0Jl;
              mysql_query($_QJlJ0, $_Q61I1);
            }

            mysql_query("COMMIT", $_Q61I1);

            $_jooJ0 = true;

          } # if(!$_jooJ0 )

          if($_jooJ0 ){

             // change 0based index of $_joC1O to $Campaigns_id
             $_Q66jQ = array();
             for($_Q6llo=0; $_Q6llo<$_joI08; $_Q6llo++){
               $_Q66jQ[$_joC1O[$_Q6llo]["Campaigns_id"]] = $_joC1O[$_Q6llo];/*array("CampaignsSendStat_id" => $_joC1O[$_Q6llo]["CampaignsSendStat_id"])*/
             }
             $_joC1O = $_Q66jQ;


             $_QJlJ0 = "SELECT * FROM `$_jojij` WHERE id > $_jQlit ORDER BY id";
             $_jo6Qo = mysql_query($_QJlJ0, $_Q61I1);

             $_jflIQ = 0;
             if(mysql_num_rows($_jo6Qo) > 0)
                $_j6O8O .= "checking $_IlC1O[Name]...<br />";

             mysql_query("BEGIN", $_Q61I1);

             # is split test sending done?
             if(mysql_num_rows($_jo6Qo) == 0) {
               $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET EndSendDateTime=NOW(), SplitTestSendDone=1, `SendState`='Waiting' WHERE id=$_jo1jj";
               mysql_query($_QJlJ0, $_Q61I1);
             }

             while($_joijI = mysql_fetch_assoc($_jo6Qo)){
                // last campaigns send stat id
                $_j8Cji = $_joC1O[$_joijI["Campaigns_id"]]["CampaignsSendStat_id"];

                if( !isset( $_joC1O[$_joijI["Campaigns_id"]]["CampaignsRow"] ) ) {
                   // MailTextInfos
                   $_QJlJ0 = "SELECT $_Q6jOo.*, $_Q6jOo.maillists_id AS MailingListId ";
                   $_QJlJ0 .= " FROM $_Q6jOo ";
                   $_QJlJ0 .= " WHERE $_Q6jOo.id=$_joijI[Campaigns_id]";

                   $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
                   $_j8o0f = mysql_fetch_assoc($_Q8Oj8);
                   mysql_free_result($_Q8Oj8);

                   // check all mtas_id are in CurrentUsedMTAsTableName
                   $_QJlJ0 = "SELECT `$_j8o0f[MTAsTableName]`.mtas_id, `$_j8o0f[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_j8o0f[MTAsTableName]` LEFT JOIN `$_j8o0f[CurrentUsedMTAsTableName]` ON `$_j8o0f[CurrentUsedMTAsTableName]`.mtas_id = `$_j8o0f[MTAsTableName]`.mtas_id WHERE `$_j8o0f[CurrentUsedMTAsTableName]`.`SendStat_id`=$_j8Cji ORDER BY sortorder";
                   $_j8Coj = mysql_query($_QJlJ0, $_Q61I1);
                   if($_j8Coj)
                     $_jI6Jo = mysql_num_rows($_j8Coj);
                     else
                     $_jI6Jo = 0;

                   if(!$_j8Coj || $_jI6Jo == 0) {
                     $_j6O8O .= $commonmsgHTMLMTANotFound;
                     continue;
                   }

                   while($_j8i16 = mysql_fetch_assoc($_j8Coj)) {
                     $_jIfO0 = $_j8i16; // save it
                     if($_jIfO0["Usedmtas_id"] == "NULL") {
                       $_QJlJ0 = "INSERT INTO `$_j8o0f[CurrentUsedMTAsTableName]` SET `SendStat_id`=$_j8Cji, `mtas_id`=$_jIfO0[mtas_id]";
                       mysql_query($_QJlJ0, $_Q61I1);
                     }
                   }
                   mysql_free_result($_j8Coj);

                   # one MTA only than we can get data and merge it directly
                   if($_jI6Jo == 1){
                     $_j8o0f["mtas_id"] = $_jIfO0["mtas_id"];
                   }

                   // save for use again
                   $_j8o0f["MTAsCount"] = $_jI6Jo;

                   // cache MailTextInfos
                   $_joC1O[$_joijI["Campaigns_id"]]["CampaignsRow"] = $_j8o0f;
                } else { # if( !isset( $_joC1O[$_joijI["Campaigns_id"]]["CampaignsRow"] ) )
                   $_j8o0f = $_joC1O[$_joijI["Campaigns_id"]]["CampaignsRow"];
                   $_jI6Jo = $_j8o0f["MTAsCount"];
                }

                // limit reached?
                if($_jflIQ >= $_j8o0f["MaxEMailsToProcess"]) break;

                $_QJlJ0 = "INSERT INTO `$_j8o0f[RStatisticsTableName]` SET `SendStat_id`=$_j8Cji, `MailSubject`="._OPQLR($_j8o0f["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_joijI[Member_id], `Send`='Prepared'";
                mysql_query($_QJlJ0, $_Q61I1);

                $_jfiol = 0;
                if(mysql_affected_rows($_Q61I1) > 0) {
                  $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
                  $_jfl1j = mysql_fetch_array($_jfLII);
                  $_jfiol = $_jfl1j[0];
                  mysql_free_result($_jfLII);
                } else {
                  if(mysql_errno($_Q61I1) == 1062)  {// dup key
                    $_QJlJ0 = "SELECT `id` FROM `$_j8o0f[RStatisticsTableName]` WHERE `SendStat_id`=$_j8Cji AND `recipients_id`=$_joijI[Member_id] AND `Send`='Prepared'";
                    $_jfLII = mysql_query($_QJlJ0, $_Q61I1);
                    if(mysql_num_rows($_jfLII) > 0){
                      $_jfl1j=mysql_fetch_array($_jfLII);
                      $_jfiol = $_jfl1j[0];
                      mysql_free_result($_jfLII);
                    } else {
                      mysql_free_result($_jfLII);
                      $_jfiol = 0;
                    }

                  } else {
                    $_j6O8O .= "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
                    mysql_query("ROLLBACK", $_Q61I1);
                    return false;
                   }
                }

                if($_jfiol) {
                  if($_jI6Jo > 1){
                    $_jIfO0 = _O6LLO($_j8o0f["CurrentUsedMTAsTableName"], $_j8o0f["MTAsTableName"], $_j8Cji);
                    $_j8o0f["mtas_id"] = $_jIfO0["id"];
                  }

                  $_QJlJ0 = "INSERT INTO `$_QtjLI` SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='Campaign', `Source_id`=$_j8o0f[id], `Additional_id`=0, `SendId`=$_j8Cji, `maillists_id`=$_j8o0f[MailingListId], `recipients_id`=$_joijI[Member_id], `mtas_id`=$_j8o0f[mtas_id], `LastSending`=NOW() ";
                  mysql_query($_QJlJ0, $_Q61I1);
                  if(mysql_error($_Q61I1) != "") {
                    $_j6O8O .=  "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
                    mysql_query("ROLLBACK", $_Q61I1);
                    continue;
                  }


                  $_jIojl++;
                  $_jflIQ++;
                }

                //$_j6O8O .= "Email with subject '$_j8o0f[MailSubject]' was queued for sending to '$frow[u_EMail]'<br />";

                # update last member id this is here id of Members table
                $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_joijI[id] WHERE id=$_jo1jj";
                mysql_query($_QJlJ0, $_Q61I1);


             } # while($_joijI = mysql_fetch_assoc($_jo6Qo))
             mysql_free_result($_jo6Qo);


             mysql_query("COMMIT", $_Q61I1);

          } # if($_jooJ0 )


        } // if( $_I81l8 == 'Sending' )

        // send split test campaigns /

        // prepare winner campaign

        if( $_I81l8 == 'PreparingWinnerCampaign' && !$_joIlj ){

            // get mailinglist
            $_QJlJ0 = "SELECT $_Q60QL.MaillistTableName, $_Q60QL.GroupsTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.id AS MailingListId ";
            $_QJlJ0 .= " FROM $_Q60QL WHERE $_Q60QL.id=$_IlC1O[maillists_id]";
            $_jo6Ij = mysql_query($_QJlJ0, $_Q61I1);
            $_QtJ8t = mysql_fetch_assoc($_jo6Ij);
            $_QlQC8 = $_QtJ8t["MaillistTableName"];
            mysql_free_result($_jo6Ij);

            // Campaigns SQL
            $_jo8oI = _O610A($_jOlif, $_QlQC8, $_QtJ8t["GroupsTableName"], $_QtJ8t["MailListToGroupsTableName"], $_QtJ8t["LocalBlocklistTableName"]);
            $_jo8oI = str_replace("`$_QlQC8`.*", "`$_QlQC8`.id", $_jo8oI);

            $_jo8oI = substr($_jo8oI, 0, strpos_reverse($_jo8oI, ".id,", strpos($_jo8oI, "`MembersAge`")) + 3  ) . substr($_jo8oI, strpos($_jo8oI, " FROM"));

            # problems with email addresses starting with a number, we concat a 'A', mysql will convert it to zero
            if( strpos($_jo8oI, "DISTINCT `$_QlQC8`.`u_EMail`,") !== false )
               $_jo8oI = str_replace("DISTINCT `$_QlQC8`.`u_EMail`,", "DISTINCT CONCAT('A', `$_QlQC8`.`u_EMail`),", $_jo8oI);

            $_jo8oI = str_replace(" FROM ", ", $_jOlif FROM ", $_jo8oI);

            mysql_query("BEGIN", $_Q61I1);

            if(strpos($_jo8oI, "DISTINCT ") === false)
              $_QJlJ0 = "INSERT IGNORE INTO `$_jojij` (`Member_id`, `Campaigns_id`) $_jo8oI";
              else
              $_QJlJ0 = "INSERT IGNORE INTO `$_jojij` (`id`, `Member_id`, `Campaigns_id`) $_jo8oI";
            mysql_query($_QJlJ0, $_Q61I1);

            $_joiff = mysql_affected_rows($_Q61I1);
            if($_joiff > 0)
              $_jo1oi = $_joiff;

            $_joIlj = 1;

            mysql_query("COMMIT", $_Q61I1);
        }

        if( $_I81l8 == 'PreparingWinnerCampaign' && $_joIlj ){
          $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET SendState='SendingWinnerCampaign', `Members_Prepared`=1, `WinnerRecipientsCount`=$_jo1oi WHERE id=$_jo1jj";
          mysql_query($_QJlJ0, $_Q61I1);
          $_I81l8 = 'SendingWinnerCampaign';
        }

        // prepare winner campaign /

        // send winner campaign

        if( $_I81l8 == 'SendingWinnerCampaign' ){

          $_QJlJ0 = "SELECT * FROM `$_jojij` WHERE id > $_jQlit ORDER BY id";
          $_jo6Qo = mysql_query($_QJlJ0, $_Q61I1);

          $_jflIQ = 0;
          if(mysql_num_rows($_jo6Qo) > 0)
             $_j6O8O .= "checking $_IlC1O[Name]...<br />";

          # is sending done?
          $_joiCf = 0;
          if(mysql_num_rows($_jo6Qo) == 0) {
            $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET EndSendDateTime=NOW(), `WinnerCampaignSendDone`=1 WHERE id=$_jo1jj";
            mysql_query($_QJlJ0, $_Q61I1);
            $_joiCf = 1;
          }

          if(!$_joiCf){
            // MailTextInfos
            $_QJlJ0 = "SELECT $_Q6jOo.*, $_Q6jOo.maillists_id AS MailingListId ";
            $_QJlJ0 .= " FROM $_Q6jOo ";
            $_QJlJ0 .= " WHERE $_Q6jOo.id=$_jOlif";

            $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
            $_j8o0f = mysql_fetch_assoc($_Q8Oj8);
            mysql_free_result($_Q8Oj8);

            $_QJlJ0 = "SELECT id FROM `$_j8o0f[CurrentSendTableName]` WHERE `SendState`='Sending' LIMIT 0, 1";
            $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
            $_IO08Q = mysql_fetch_assoc($_Q8Oj8);
            mysql_free_result($_Q8Oj8);

            // last campaigns send stat id
            $_j8Cji = $_IO08Q["id"];

            // check all mtas_id are in CurrentUsedMTAsTableName
            $_QJlJ0 = "SELECT `$_j8o0f[MTAsTableName]`.mtas_id, `$_j8o0f[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_j8o0f[MTAsTableName]` LEFT JOIN `$_j8o0f[CurrentUsedMTAsTableName]` ON `$_j8o0f[CurrentUsedMTAsTableName]`.mtas_id = `$_j8o0f[MTAsTableName]`.mtas_id WHERE `$_j8o0f[CurrentUsedMTAsTableName]`.`SendStat_id`=$_j8Cji ORDER BY sortorder";
            $_j8Coj = mysql_query($_QJlJ0, $_Q61I1);
            if($_j8Coj)
              $_jI6Jo = mysql_num_rows($_j8Coj);
              else
              $_jI6Jo = 0;

            if(!$_j8Coj || $_jI6Jo == 0) {
              $_j6O8O .= $commonmsgHTMLMTANotFound;
              continue;
            }

            while($_j8i16 = mysql_fetch_assoc($_j8Coj)) {
              $_jIfO0 = $_j8i16; // save it
              if($_jIfO0["Usedmtas_id"] == "NULL") {
                $_QJlJ0 = "INSERT INTO `$_j8o0f[CurrentUsedMTAsTableName]` SET `SendStat_id`=$_j8Cji, `mtas_id`=$_jIfO0[mtas_id]";
                mysql_query($_QJlJ0, $_Q61I1);
              }
            }
            mysql_free_result($_j8Coj);

            # one MTA only than we can get data and merge it directly
            if($_jI6Jo == 1){
              $_j8o0f["mtas_id"] = $_jIfO0["mtas_id"];
            }


            while($_joijI = mysql_fetch_assoc($_jo6Qo)){

               // limit reached?
               if($_jflIQ >= $_j8o0f["MaxEMailsToProcess"]) break;

               mysql_query("BEGIN", $_Q61I1);

               $_QJlJ0 = "INSERT INTO `$_j8o0f[RStatisticsTableName]` SET `SendStat_id`=$_j8Cji, `MailSubject`="._OPQLR($_j8o0f["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_joijI[Member_id], `Send`='Prepared'";
               mysql_query($_QJlJ0, $_Q61I1);

               $_jfiol = 0;
               if(mysql_affected_rows($_Q61I1) > 0) {
                 $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
                 $_jfl1j=mysql_fetch_array($_jfLII);
                 $_jfiol = $_jfl1j[0];
                 mysql_free_result($_jfLII);
               } else {
                 if(mysql_errno($_Q61I1) == 1062) { // dup key
                   $_QJlJ0 = "SELECT `id` FROM `$_j8o0f[RStatisticsTableName]` WHERE `SendStat_id`=$_j8Cji AND `recipients_id`=$_joijI[Member_id] AND `Send`='Prepared'";
                   $_jfLII = mysql_query($_QJlJ0, $_Q61I1);
                   if(mysql_num_rows($_jfLII) > 0) {
                     $_jfl1j = mysql_fetch_array($_jfLII);
                     $_jfiol = $_jfl1j[0];
                     mysql_free_result($_jfLII);
                   } else {
                     mysql_free_result($_jfLII);
                     $_jfiol = 0;
                   }
                 } else {
                   $_j6O8O .= "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
                   mysql_query("ROLLBACK", $_Q61I1);
                   return false;
                  }
               }

               if($_jfiol) {
                 if($_jI6Jo > 1){
                   $_jIfO0 = _O6LLO($_j8o0f["CurrentUsedMTAsTableName"], $_j8o0f["MTAsTableName"], $_j8Cji);
                   $_j8o0f["mtas_id"] = $_jIfO0["id"];
                 }

                 $_QJlJ0 = "INSERT INTO `$_QtjLI` SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='Campaign', `Source_id`=$_j8o0f[id], `Additional_id`=0, `SendId`=$_j8Cji, `maillists_id`=$_j8o0f[MailingListId], `recipients_id`=$_joijI[Member_id], `mtas_id`=$_j8o0f[mtas_id], `LastSending`=NOW() ";
                 mysql_query($_QJlJ0, $_Q61I1);
                 if(mysql_error($_Q61I1) != "") {
                   $_j6O8O .=  "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
                   mysql_query("ROLLBACK", $_Q61I1);
                   continue;
                 }


                 $_jIojl++;
                 $_jflIQ++;
               }

               //$_j6O8O .= "Email with subject '$_j8o0f[MailSubject]' was queued for sending to '$frow[u_EMail]'<br />";

               # update last member id this is here id of Members table
               $_QJlJ0 = "UPDATE `$_IlC1O[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_joijI[id] WHERE id=$_jo1jj";
               mysql_query($_QJlJ0, $_Q61I1);


               mysql_query("COMMIT", $_Q61I1);

            } # while($_joijI = mysql_fetch_assoc($_jo6Qo))
            mysql_free_result($_jo6Qo);


          } # if(!$_joiCf)

        } # if( $_I81l8 == 'SendingWinnerCampaign' )
        // send winner campaign /

        if($_jflIQ > 0) {
           $_j6O8O .= "$_IlC1O[Name] checked, $_jflIQ email(s) sent to queue.<br />";
        }

      } // while($_jOl01 && $_IlC1O = mysql_fetch_assoc($_jOl01))
      mysql_free_result($_jOl01);

    } // while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
    mysql_free_result($_Q60l1);

    $_j6O8O .= "<br />$_jIojl emails sent to queue<br />";
    $_j6O8O .= "<br />Split tests checking end.";

    if($_jIojl)
      return true;
      else
      return -1;
  }

  function _ORD0L($CampaignId, $_jI0Oo){
    global $_Q61I1, $_Q6jOo;
    $_jQll6 = 0;

    $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$CampaignId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_j8o0f = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    mysql_query("BEGIN", $_Q61I1);

    // CurrentSendTableName
    $_QJlJ0 = "INSERT INTO `$_j8o0f[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW(), RecipientsCount=$_jI0Oo";
    mysql_query($_QJlJ0, $_Q61I1);
    $_j8oC1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
    $_Qt6f8=mysql_fetch_row($_j8oC1);
    $_jQll6 = $_Qt6f8[0];
    $_j8o0f["CurrentSendId"] = $_jQll6;
    mysql_free_result($_j8oC1);

    /*
    // Twitter Update start

    .... cron_campaigns.inc.php

    // Twitter Update end
    */

    // SET Current used MTAs to zero
    $_QJlJ0 = "INSERT INTO `$_j8o0f[CurrentUsedMTAsTableName]` SELECT 0, $_jQll6, `mtas_id`, 0 FROM `$_j8o0f[MTAsTableName]` ORDER BY sortorder";
    mysql_query($_QJlJ0, $_Q61I1);

    // Archive Table
    $_QJlJ0 = "INSERT INTO `$_j8o0f[ArchiveTableName]` SET SendStat_id=$_jQll6, ";
    $_QJlJ0 .= "MailFormat="._OPQLR($_j8o0f["MailFormat"]).", ";
    $_QJlJ0 .= "MailEncoding="._OPQLR($_j8o0f["MailEncoding"]).", ";
    $_QJlJ0 .= "MailSubject="._OPQLR($_j8o0f["MailSubject"]).", ";
    $_QJlJ0 .= "MailPlainText="._OPQLR($_j8o0f["MailPlainText"]).", ";
    $_QJlJ0 .= "MailHTMLText="._OPQLR($_j8o0f["MailHTMLText"]).", ";
    $_QJlJ0 .= "Attachments="._OPQLR($_j8o0f["Attachments"]);
    mysql_query($_QJlJ0, $_Q61I1);

    mysql_query("COMMIT", $_Q61I1);

    return $_jQll6;
  }


?>
