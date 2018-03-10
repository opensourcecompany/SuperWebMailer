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

  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("fums_ops.inc.php");


  function _OR6FF(&$_j6O8O) {
    global $_Q61I1, $_Q6JJJ;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Qo8OO, $resourcestrings, $_Q8f1L, $_Q880O;
    global $_jjC06, $_jjCtI, $_I0lQJ, $_jji0C, $_QOCJo, $_QCo6j, $_jji0i;
    global $_Q60QL, $_Qofoi, $_ICljl, $_QLo0Q, $_Ql8C0, $_I88i8, $_QtjLI;
    global $_Q6jOo, $_IIl8O, $_IjQIf, $_IQL81, $_II8J0, $_QCLCI;

    global $_QltCO, $_QlQC8, $_QlIf6, $_QLI68, $MailingListId, $_QljIQ, $_Qljli, $_IoO1t, $_QtIiC;


    $_j6O8O = "Follow Up Responder checking starts...<br />";
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
      $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_Q8OiJ[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);


      $_QJlJ0 = "SELECT $_QCLCI.`id` AS FUResponders_id, $_QCLCI.`Name` AS FUResponders_Name, $_QCLCI.`ResponderType` AS FUResponders_ResponderType, $_QCLCI.`FUMailsTableName`, $_QCLCI.`ML_FU_RefTableName`, $_QCLCI.`RStatisticsTableName`, $_QCLCI.MaxEMailsToProcess, $_QCLCI.`OnFollowUpDoneAction`, $_QCLCI.`OnFollowUpDoneScriptURL`, ";
      $_QJlJ0 .= "$_QCLCI.`OnFollowUpDoneCopyToMailList`, $_QCLCI.`OnFollowUpDoneMoveToMailList`, $_QCLCI.`forms_id`, $_QCLCI.`StartDateOfFirstFUMail`, $_QCLCI.`FirstFollowUpMailDateFieldName`, $_QCLCI.`FormatOfFirstFollowUpMailDateField`, $_QCLCI.`GroupsTableName` AS FUResponders_GroupsTableName,";
      $_QJlJ0 .= " $_QCLCI.`NotInGroupsTableName` AS FUResponders_NotInGroupsTableName, $_QCLCI.`SendTimeVariant`, $_QCLCI.`SendTime`,";
      $_QJlJ0 .= "$_Qofoi.`id` AS MTA_id, $_Q60QL.`MaillistTableName`, $_Q60QL.`LocalBlocklistTableName`, $_Q60QL.`id` AS MailingListId, $_Q60QL.`MailListToGroupsTableName`, $_Q60QL.`StatisticsTableName`, $_Q60QL.`MailLogTableName`, $_Q60QL.`EditTableName`, $_Q60QL.`Name` AS MailingListName FROM `$_QCLCI` LEFT JOIN `$_Qofoi` ON $_Qofoi.`id`=$_QCLCI.`mtas_id`";
      $_QJlJ0 .= " LEFT JOIN $_Q60QL ON $_Q60QL.id=$_QCLCI.maillists_id WHERE $_QCLCI.IsActive=1";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "")
        $_j6O8O .= "MySQL error while selecting data: ".mysql_error($_Q61I1);
      while($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
        _OPQ6J();

        $_j6O8O .= "Checking $_Q8OiJ[FUResponders_Name]...<br />";


        $_jflIQ = 0;

        $_QlQC8 = $_Q8OiJ["MaillistTableName"];
        $_ItCCo = $_Q8OiJ["LocalBlocklistTableName"];
        $_QlIf6 = $_Q8OiJ["StatisticsTableName"];
        $_QLI68 = $_Q8OiJ["MailListToGroupsTableName"];
        $_QljIQ = $_Q8OiJ["MailLogTableName"];
        $MailingListId = $_Q8OiJ["MailingListId"];
        $_Qljli = $_Q8OiJ["EditTableName"];
        $_ItiO1 = $_Q8OiJ["FUResponders_GroupsTableName"];
        $_ItL8J = $_Q8OiJ["FUResponders_NotInGroupsTableName"];

        $_QJlJ0 = "SELECT COUNT(`ml_groups_id`) FROM `$_ItiO1`";
        $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
        $_IO0Jo = 0;
        if($_ItlJl && $_IO08Q = mysql_fetch_row($_ItlJl)) {
          $_IO0Jo = $_IO08Q[0];
          mysql_free_result($_ItlJl);
        }

        $_IO1I1 = 0;
        if($_IO0Jo > 0){
          $_QJlJ0 = "SELECT COUNT(`ml_groups_id`) FROM `$_ItL8J`";
          $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
          if($_ItlJl && $_IO08Q = mysql_fetch_row($_ItlJl)) {
            $_IO1I1 = $_IO08Q[0];
            mysql_free_result($_ItlJl);
          }
        }

        // copy / move recipients
        if( $_Q8OiJ["OnFollowUpDoneAction"] > 1 || !empty($_Q8OiJ["OnFollowUpDoneScriptURL"]) ) {
          $_QJlJ0 = "SELECT sort_order FROM `$_Q8OiJ[FUMailsTableName]` ORDER BY sort_order DESC LIMIT 0, 1";
          $_jttL1 = mysql_query($_QJlJ0, $_Q61I1);
          $_jttLJ=1;
          if($_jttL1 && $_jtO66 = mysql_fetch_assoc($_jttL1)) {
             $_jttLJ = $_jtO66["sort_order"] + 1;
          }
          if($_jttL1)
            mysql_free_result($_jttL1);

          $_QJlJ0 = "SELECT `$_QlQC8`.id FROM `$_QlQC8` LEFT JOIN `$_Q8OiJ[ML_FU_RefTableName]` ON `$_Q8OiJ[ML_FU_RefTableName]`.`Member_id`=`$_Q8OiJ[MaillistTableName]`.id WHERE `$_Q8OiJ[ML_FU_RefTableName]`.`NextFollowUpID` IS NOT NULL AND `$_Q8OiJ[ML_FU_RefTableName]`.`NextFollowUpID`>=$_jttLJ AND `$_Q8OiJ[ML_FU_RefTableName]`.`OnFollowUpDoneActionDone`=0";
          $_jttL1 = mysql_query($_QJlJ0, $_Q61I1);
          $_QltCO = array();
          while($_jtO66 = mysql_fetch_assoc($_jttL1)){
             // in outqueue?
             $_QJlJ0 = "SELECT COUNT(id) FROM `$_QtjLI` WHERE `users_id`=$UserId AND `maillists_id`=$_Q8OiJ[MailingListId] AND `recipients_id`=$_jtO66[id]";
             $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
             $_IO08Q = mysql_fetch_row($_ItlJl);
             mysql_free_result($_ItlJl);
             if($_IO08Q[0] == 0) {
               $_QltCO[] = $_jtO66["id"];
             }
          }
          mysql_free_result($_jttL1);

          if(count($_QltCO)) {

            if($_Q8OiJ["OnFollowUpDoneAction"] > 1){

              $_IoO1t = array();
              $_QtIiC = array();

              $_QJlJ0 = "SELECT `MaillistTableName`, `StatisticsTableName`, `MailLogTableName`, `EditTableName` FROM `$_Q60QL` WHERE id=";
              if( $_Q8OiJ["OnFollowUpDoneAction"] == 2){
                 $_QJlJ0 .= "$_Q8OiJ[OnFollowUpDoneCopyToMailList]";
                 $_jtoIO = "copied";
                 }
                 else
                 if( $_Q8OiJ["OnFollowUpDoneAction"] == 3){
                   $_QJlJ0 .= "$_Q8OiJ[OnFollowUpDoneMoveToMailList]";
                   $_jtoIO = "moved";
                 }
                 else
                 if( $_Q8OiJ["OnFollowUpDoneAction"] == 4){
                   $_jtoIO = "disabled";
                 }
                 else
                 if( $_Q8OiJ["OnFollowUpDoneAction"] == 5){
                   $_jtoIO = "removed";
                 }

              if( $_Q8OiJ["OnFollowUpDoneAction"] < 4){
                $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
                if($_ItlJl && $_IO08Q = mysql_fetch_assoc($_ItlJl)) {

                  $_jtoOQ = "SYSTEM: Follow up responder '$_Q8OiJ[FUResponders_Name]' has " . $_jtoIO . " recipient from mailing list '$_Q8OiJ[MailingListName]'";
                  _L11LC($_QltCO, $_IoO1t, $_IO08Q["MaillistTableName"], $_IO08Q["StatisticsTableName"], $_IO08Q["MailLogTableName"], $_IO08Q["EditTableName"], $_Q8OiJ["OnFollowUpDoneAction"] == 3, $_jtoOQ);
                }
                if($_ItlJl)
                  mysql_free_result($_ItlJl);
              }

              if( $_Q8OiJ["OnFollowUpDoneAction"] == 4){
                $_jtoOQ = "SYSTEM: Follow up responder '$_Q8OiJ[FUResponders_Name]' has " . $_jtoIO . " recipient in mailing list '$_Q8OiJ[MailingListName]'";
                _L1J66(false, $_QltCO, $_QtIiC, $_jtoOQ);
              }

              if( $_Q8OiJ["OnFollowUpDoneAction"] == 5){
                // $_jtoOQ = "SYSTEM: Follow up responder '$_Q8OiJ[FUResponders_Name]' has " . $_jtoIO . " recipient in mailing list '$_Q8OiJ[MailingListName]'";
                _L10CL($_QltCO, $_QtIiC, true);
              }

              if( $_Q8OiJ["OnFollowUpDoneAction"] != 3 && $_Q8OiJ["OnFollowUpDoneAction"] != 5 ){ // not on move, remove

                mysql_query("BEGIN", $_Q61I1);

                reset($_QltCO);
                $_QtjtL = array();
                foreach($_QltCO as $_Q6llo => $_Q6ClO) {
                  $_QtjtL[] = "`Member_id`=".$_QltCO[$_Q6llo];
                  if(count($_QtjtL) > 20){
                    $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_FU_RefTableName]` SET `OnFollowUpDoneActionDone`=1 WHERE ".join(" OR ", $_QtjtL);
                    mysql_query($_QJlJ0, $_Q61I1);
                    $_QtjtL = array();
                  }
                }

                if(count($_QtjtL)){
                  $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_FU_RefTableName]` SET `OnFollowUpDoneActionDone`=1 WHERE ".join(" OR ", $_QtjtL);
                  mysql_query($_QJlJ0, $_Q61I1);
                }

                mysql_query("COMMIT", $_Q61I1);
              }

            }

            if(!empty($_Q8OiJ["OnFollowUpDoneScriptURL"])){
             for($_Q6llo=0; $_Q6llo<count($_QltCO); $_Q6llo++){
               _ORR1Q($_Q8OiJ["OnFollowUpDoneScriptURL"], $_QltCO[$_Q6llo]);
               if($_Q8OiJ["OnFollowUpDoneAction"] < 2){
                  $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_FU_RefTableName]` SET `OnFollowUpDoneActionDone`=1 WHERE `Member_id`=".$_QltCO[$_Q6llo];
                  mysql_query($_QJlJ0, $_Q61I1);
               }
             }
            }

          }
        }

        //

        $_IO1Oj = " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
        $_IO1Oj .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
        $_IOQf6 = " `$_QlQC8`.`IsActive`=1 AND `$_QlQC8`.`SubscriptionStatus`<>'OptInConfirmationPending'".$_Q6JJJ;
        $_IOQf6 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ".$_Q6JJJ;

        $_IOI16 = "";
        $_IOILL = "";
        if($_IO0Jo > 0) {

          $_IOI16 .= " LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
          $_IOI16 .= " LEFT JOIN `$_ItiO1` ON `$_ItiO1`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
          if($_IO1I1 > 0) {
            $_IOI16 .= "  LEFT JOIN ( ".$_Q6JJJ;

            $_IOI16 .= "    SELECT `$_QlQC8`.`id`".$_Q6JJJ;
            $_IOI16 .= "    FROM `$_QlQC8`".$_Q6JJJ;

            $_IOI16 .= "    LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
            $_IOI16 .= "    LEFT JOIN `$_ItL8J` ON".$_Q6JJJ;
            $_IOI16 .= "    `$_ItL8J`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
            $_IOI16 .= "    WHERE `$_ItL8J`.`ml_groups_id` IS NOT NULL".$_Q6JJJ;

            $_IOI16 .= "  ) AS `subquery` ON `subquery`.`id`=`$_QlQC8`.`id`".$_Q6JJJ;
          }

          if($_IO0Jo > 0) {
            $_IOILL .= " AND (`$_ItiO1`.`ml_groups_id` IS NOT NULL)".$_Q6JJJ;
            if($_IO1I1 > 0) {
             $_IOILL .= " AND (`subquery`.`id` IS NULL)".$_Q6JJJ;
            }
          }

        }

        $_IOjof = "";
        if($_Q8OiJ["StartDateOfFirstFUMail"] == 1 && $_Q8OiJ["FirstFollowUpMailDateFieldName"] != "")
           $_IOjof = ", `$_QlQC8`.`$_Q8OiJ[FirstFollowUpMailDateFieldName]` ";

        if($_IO0Jo > 1) {
          $_IOJ8I = "DISTINCT `$_QlQC8`.`u_EMail`";
        } else
          $_IOJ8I = "`$_QlQC8`.u_EMail";

        $_QJlJ0 = "SELECT $_IOJ8I, `$_QlQC8`.id, `$_QlQC8`.DateOfOptInConfirmation, `$_Q8OiJ[ML_FU_RefTableName]`.NextFollowUpID, `$_Q8OiJ[ML_FU_RefTableName]`.LastSending ".$_IOjof;
        $_QJlJ0 .= " FROM `$_Q8OiJ[MaillistTableName]` $_IO1Oj $_IOI16 LEFT JOIN `$_Q8OiJ[ML_FU_RefTableName]` ON `$_Q8OiJ[ML_FU_RefTableName]`.Member_id=`$_Q8OiJ[MaillistTableName]`.id";
        $_QJlJ0 .= " WHERE $_IOQf6 $_IOILL";
        $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);

        if(mysql_error($_Q61I1) != "")
          $_j6O8O .= "MySQL error while selecting data(2): ".mysql_error($_Q61I1);

        while($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {

          // limit reached?
          if($_jflIQ >= $_Q8OiJ["MaxEMailsToProcess"]) break;

          _OPQ6J();

          $_IOCO0 = "";

          if($_Q8OiJ["FUResponders_ResponderType"] == FUResponderTypeActionBased){
             $_IOifl = "";
             $_IOLJ0 = "";
             if($_QlftL["NextFollowUpID"] == NULL || $_QlftL["NextFollowUpID"] <= 0){ // has never got an email take first mail
                 $_IOifl = ", `FirstRecipientsAction`, `FirstRecipientsActionCampaign_id`, `FirstRecipientsActionCampaignLink_id`";
                 $_IOLJ0 = " ORDER BY `sort_order` LIMIT 0, 1";
               }
               else{
                 $_IOifl = ", `LastRecipientsAction`, `LastRecipientsActionLink_id`";
                 $_IOLJ0 = " WHERE `sort_order`=$_QlftL[NextFollowUpID]";
               }

            $_QJlJ0 = "SELECT `sort_order` $_IOifl FROM `$_Q8OiJ[FUMailsTableName]`";
            $_QJlJ0 .= $_IOLJ0;

            $_Qllf1 = mysql_query($_QJlJ0, $_Q61I1);
            if(!$_Qllf1 || !($_IOlIQ = mysql_fetch_assoc($_Qllf1)) ) continue;

            mysql_free_result($_Qllf1);
            if($_IOlIQ === FALSE) continue;


            if($_QlftL["NextFollowUpID"] == NULL || $_QlftL["NextFollowUpID"] <= 0){ # first email?
               if( !($_IOCO0 = _OP0O0($_IOlIQ["FirstRecipientsAction"], $_IOlIQ["FirstRecipientsActionCampaign_id"], $_IOlIQ["FirstRecipientsActionCampaignLink_id"], $_QlftL["id"])) ){
                 continue;
               }

            } else{
              if( (!$_IOCO0 = _OP06R($_IOlIQ["LastRecipientsAction"], $_IOlIQ["LastRecipientsActionLink_id"], $_Q8OiJ["FUMailsTableName"], $_QlftL["NextFollowUpID"], $_QlftL["id"])) ){
                continue;
              }
            }
            if($_IOCO0 === true)
               $_IOCO0 = "";  // variant email was sent, ever take standard of time based fums
               else
               $_IOCO0 = "'$_IOCO0'";
          }

          $_QJlJ0 = "SELECT id, sort_order, SendInterval, SendIntervalType FROM `$_Q8OiJ[FUMailsTableName]`";

          if($_QlftL["NextFollowUpID"] == NULL || $_QlftL["NextFollowUpID"] <= 0) { // has never got an email take first mail
            $_QJlJ0 .= " ORDER BY sort_order LIMIT 0, 1";
            $_Qllf1 = mysql_query($_QJlJ0, $_Q61I1);
            if(!$_Qllf1) continue;
            $_IOlIQ = mysql_fetch_assoc($_Qllf1);
            mysql_free_result($_Qllf1);

            $_QJlJ0 = "SELECT id, MailSubject, SendInterval, SendIntervalType FROM `$_Q8OiJ[FUMailsTableName]`";
            if($_Q8OiJ["StartDateOfFirstFUMail"] == 0) {


               if($_Q8OiJ["FUResponders_ResponderType"] == FUResponderTypeActionBased && $_IOCO0 != ""){
                 $_IOl8C = $_IOCO0;
               } else {
                 $_IOl8C = "'" . $_QlftL["DateOfOptInConfirmation"] . "'";
               }

               if($_Q8OiJ["SendTimeVariant"] == 'sendingWithoutSendTime')
                  $_QJlJ0 .= " WHERE sort_order=$_IOlIQ[sort_order] AND NOW() >= DATE_ADD($_IOl8C, INTERVAL $_IOlIQ[SendInterval] $_IOlIQ[SendIntervalType]) ";
                  else
                  $_QJlJ0 .= " WHERE sort_order=$_IOlIQ[sort_order] AND NOW() >= DATE_ADD($_IOl8C, INTERVAL $_IOlIQ[SendInterval] $_IOlIQ[SendIntervalType]) AND CURRENT_TIME() >= '$_Q8OiJ[SendTime]' ";
               } else{
                if($_Q8OiJ["FirstFollowUpMailDateFieldName"] != "u_Birthday") {

                  if($_Q8OiJ["FormatOfFirstFollowUpMailDateField"] == "") {
                     if($INTERFACE_LANGUAGE != "de")
                       $_Q8OiJ["FormatOfFirstFollowUpMailDateField"] = "yyyy-mm-dd";
                       else
                       $_Q8OiJ["FormatOfFirstFollowUpMailDateField"] = "dd.mm.yyyy";
                  }

                  $_I1L81 = trim($_QlftL[$_Q8OiJ["FirstFollowUpMailDateFieldName"]]);
                  if($_Q8OiJ["FormatOfFirstFollowUpMailDateField"] == "dd.mm.yyyy") {
                    $_Io01I = explode(".", $_I1L81);
                    while(count($_Io01I) < 3)
                       $_Io01I[] = "f";
                    $_IOJ8I = $_Io01I[0];
                    $_Io0t6 = $_Io01I[1];
                    $_Io0l8 = $_Io01I[2];
                  } else
                    if($_Q8OiJ["FormatOfFirstFollowUpMailDateField"] == "yyyy-mm-dd") {
                     $_Io01I = explode("-", $_I1L81);
                     while(count($_Io01I) < 3)
                        $_Io01I[] = "f";
                     $_IOJ8I = $_Io01I[2];
                     $_Io0t6 = $_Io01I[1];
                     $_Io0l8 = $_Io01I[0];
                    } else
                      if($_Q8OiJ["FormatOfFirstFollowUpMailDateField"] == "mm-dd-yyyy") {
                        $_Io01I = explode("-", $_I1L81);
                        while(count($_Io01I) < 3)
                           $_Io01I[] = "f";
                        $_IOJ8I = $_Io01I[1];
                        $_Io0t6 = $_Io01I[0];
                        $_Io0l8 = $_Io01I[2];
                      }

                  if(strlen($_Io0l8) == 2)
                    $_Io0l8 = "19".$_Io0l8;
                  if( ! (
                      (intval($_IOJ8I) > 0 && intval($_IOJ8I) < 32) &&
                      (intval($_Io0t6) > 0 && intval($_Io0t6) < 13)
                        )
                    ) // error in date
                    $_I1L81 = "0000-00-00";
                    else
                    $_I1L81 = "$_Io0l8-$_Io0t6-$_IOJ8I";


                  if($_Q8OiJ["FUResponders_ResponderType"] == FUResponderTypeTimeBased || $_IOCO0 == ""){
                    if($_I1L81 != "0000-00-00")
                      $_QlftL[$_Q8OiJ["FirstFollowUpMailDateFieldName"]] = $_I1L81;
                      else
                      $_QlftL[$_Q8OiJ["FirstFollowUpMailDateFieldName"]] = $_QlftL["DateOfOptInConfirmation"];
                  } else {
                    $_QlftL[$_Q8OiJ["FirstFollowUpMailDateFieldName"]] = $_IOCO0;
                  }
                }

                if($_Q8OiJ["SendTimeVariant"] == 'sendingWithoutSendTime')
                  $_QJlJ0 .= " WHERE sort_order=$_IOlIQ[sort_order] AND NOW() >= DATE_ADD('".$_QlftL[$_Q8OiJ["FirstFollowUpMailDateFieldName"]]."', INTERVAL $_IOlIQ[SendInterval] $_IOlIQ[SendIntervalType]) ";
                  else
                  $_QJlJ0 .= " WHERE sort_order=$_IOlIQ[sort_order] AND NOW() >= DATE_ADD('".$_QlftL[$_Q8OiJ["FirstFollowUpMailDateFieldName"]]."', INTERVAL $_IOlIQ[SendInterval] $_IOlIQ[SendIntervalType])  AND CURRENT_TIME() >= '$_Q8OiJ[SendTime]' ";

               }

          } else {
            $_QJlJ0 .= " WHERE sort_order=$_QlftL[NextFollowUpID]";
            $_Qllf1 = mysql_query($_QJlJ0, $_Q61I1);
            if(!$_Qllf1 || !($_IOlIQ = mysql_fetch_assoc($_Qllf1)) ) continue;

            mysql_free_result($_Qllf1);
            if($_IOlIQ === FALSE) continue;

            $_QJlJ0 = "SELECT id, MailSubject FROM `$_Q8OiJ[FUMailsTableName]`";

            if($_Q8OiJ["FUResponders_ResponderType"] == FUResponderTypeActionBased && $_IOCO0 != ""){
               $_IOl8C = $_IOCO0;
            } else {
               $_IOl8C = "'" . $_QlftL["LastSending"] . "'";
            }

            if($_Q8OiJ["SendTimeVariant"] == 'sendingWithoutSendTime')
              $_QJlJ0 .= " WHERE sort_order=$_QlftL[NextFollowUpID] AND NOW() >= DATE_ADD($_IOl8C, INTERVAL $_IOlIQ[SendInterval] $_IOlIQ[SendIntervalType]) ";
              else
              $_QJlJ0 .= " WHERE sort_order=$_QlftL[NextFollowUpID] AND NOW() >= DATE_ADD($_IOl8C, INTERVAL $_IOlIQ[SendInterval] $_IOlIQ[SendIntervalType]) AND CURRENT_TIME() >= '$_Q8OiJ[SendTime]' ";
          }


          $_Io1j1 = mysql_query($_QJlJ0, $_Q61I1);

          while($_Io1j1 && $_IoQ11 = mysql_fetch_assoc($_Io1j1)) {
            _OPQ6J();

            mysql_query("BEGIN", $_Q61I1);

            $_QJlJ0 = "INSERT INTO `$_Q8OiJ[RStatisticsTableName]` SET `MailSubject`="._OPQLR($_IoQ11["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `fumails_id`=$_IoQ11[id], `Send`='Prepared'";
            mysql_query($_QJlJ0, $_Q61I1);

            $_jfiol = 0;
            if(mysql_affected_rows($_Q61I1) > 0) {
              $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
              $_jfl1j=mysql_fetch_array($_jfLII);
              $_jfiol = $_jfl1j[0];
              mysql_free_result($_jfLII);
            } else {
              if(mysql_errno($_Q61I1) == 1062) { // dup key
                $_QJlJ0 = "SELECT `id` FROM `$_Q8OiJ[RStatisticsTableName]` WHERE `recipients_id`=$_QlftL[id] AND `fumails_id`=$_IoQ11[id] AND `Send`='Prepared'";
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

            if($_jfiol){
              // SendId = follow up mail id
              $_QJlJ0 = "INSERT INTO $_QtjLI SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='FollowUpResponder', `Source_id`=$_Q8OiJ[FUResponders_id], `Additional_id`=$_IoQ11[id], `SendId`=$_IoQ11[id], `maillists_id`=$_Q8OiJ[MailingListId], `recipients_id`=$_QlftL[id], `mtas_id`=$_Q8OiJ[MTA_id], `LastSending`=NOW() ";
              mysql_query($_QJlJ0, $_Q61I1);
              if(mysql_error($_Q61I1) != "") {
                $_j6O8O .= "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
                mysql_query("ROLLBACK", $_Q61I1);
                continue;
              }

              $_jflIQ++;
              $_jIojl++;

              $_j6O8O .= "Email with subject '$_IoQ11[MailSubject]' was queued for sending to '$_QlftL[u_EMail]'<br />";
              // Update FUResponder statistics
              $_QJlJ0 = "UPDATE $_QCLCI SET EMailsSent=EMailsSent+1 WHERE id=$_Q8OiJ[FUResponders_id]";
              mysql_query($_QJlJ0, $_Q61I1);
            }

            // increase NextFollowUpID
            if($_Q8OiJ["SendTimeVariant"] == 'sendingWithoutSendTime')
               $_jtCQ0 = "NOW()";
               else
               $_jtCQ0 = "CONCAT(CURDATE(), ' ', '$_Q8OiJ[SendTime]')";

            $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_FU_RefTableName]` SET NextFollowUpID=NextFollowUpID+1, LastSending=$_jtCQ0 WHERE Member_id=$_QlftL[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_error($_Q61I1) != "" && $_Q8OiJ["SendTimeVariant"] != 'sendingWithoutSendTime') {
               $_jtCQ0 = "NOW()";
               $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_FU_RefTableName]` SET NextFollowUpID=NextFollowUpID+1, LastSending=$_jtCQ0 WHERE Member_id=$_QlftL[id]";
               mysql_query($_QJlJ0, $_Q61I1);
            }

            if(mysql_affected_rows($_Q61I1) == 0) { // new?
              $_QJlJ0 = "INSERT INTO `$_Q8OiJ[ML_FU_RefTableName]` SET NextFollowUpID=2, Member_id=$_QlftL[id], LastSending=$_jtCQ0";
              mysql_query($_QJlJ0, $_Q61I1);
              if(mysql_error($_Q61I1) != "" && $_Q8OiJ["SendTimeVariant"] != 'sendingWithoutSendTime') {
                $_jtCQ0 = "NOW()";
                $_QJlJ0 = "INSERT INTO `$_Q8OiJ[ML_FU_RefTableName]` SET NextFollowUpID=2, Member_id=$_QlftL[id], LastSending=$_jtCQ0";
                mysql_query($_QJlJ0, $_Q61I1);
              }
            }


            mysql_query("COMMIT", $_Q61I1);

          } # while($_IoQ11 = mysql_fetch_array($_Io1j1))
          if($_Io1j1)
            mysql_free_result($_Io1j1);

        }
        if($_IOOt1)
          mysql_free_result($_IOOt1);

        $_j6O8O .= "Done.<br />";

      } # while($_Q8OiJ = mysql_fetch_array($_Q8Oj8))
      if($_Q8Oj8)
        mysql_free_result($_Q8Oj8);
    } # while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
    mysql_free_result($_Q60l1);

    $_j6O8O .= "<br />$_jIojl emails sent to queue<br />";
    $_j6O8O .= "<br />Follow Up Responder checking end.";

    if($_jIojl)
      return true;
      else
      return -1;
  }


  function _ORR1Q($_jtCJo, $ID) {
     global $AppName;
     if($_jtCJo == "") return true;

     $_j88of = 0;
     $_j8t8L = "";
     $_j8O60 = 80;
     if(strpos($_jtCJo, "http://") !== false) {
        $_j8O8t = substr($_jtCJo, 7);
     } elseif(strpos($_jtCJo, "https://") !== false) {
       $_j8O60 = 443;
       $_j8O8t = substr($_jtCJo, 8);
     }
     $_QCoLj = substr($_j8O8t, strpos($_j8O8t, "/"));
     $_j8O8t = substr($_j8O8t, 0, strpos($_j8O8t, "/"));

     $_Qf1i1 = "AppName=$AppName&ID=$ID";
     _OCQDE($_j8O8t, "GET", $_QCoLj, $_Qf1i1, 0, $_j8O60, false, "", "", $_j88of, $_j8t8L);
  }

?>
