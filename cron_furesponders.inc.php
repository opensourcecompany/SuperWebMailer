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
  include_once("fums_ops.inc.php");


  function _LLAOR(&$_JIfo0) {
    global $_QLttI, $_QLl1Q, $_QLo06;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Ijt8j, $resourcestrings, $_I18lo, $_I1tQf;
    global $_J18oI, $_jfOJj, $_ItL8f, $_J1t6J, $_IIlfi, $_IJi8f, $_J1tfC;
    global $_QL88I, $_Ijt0i, $_jfQtI, $_Ifi1J, $_I8tfQ, $_jQ68I, $_IQQot;
    global $_QLi60, $_ICo0J, $_ICl0j, $_IoCo0, $_ICIJo, $_I616t;

    global $_I8oIJ, $_I8I6o, $_I8jjj, $_IfJ66, $MailingListId, $_I8jLt, $_I8Jti, $_QljJi, $_jJi11, $_IQ0Cj;


    $_JIfo0 = "Follow Up Responder checking starts...<br />";
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
      $_I1OfI = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_I1OfI[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);


      $_QLfol = "SELECT $_I616t.`id` AS FUResponders_id, $_I616t.`Name` AS FUResponders_Name, $_I616t.`ResponderType` AS FUResponders_ResponderType, $_I616t.`FUMailsTableName`, $_I616t.`ML_FU_RefTableName`, $_I616t.`RStatisticsTableName`, $_I616t.MaxEMailsToProcess, $_I616t.`OnFollowUpDoneAction`, $_I616t.`OnFollowUpDoneScriptURL`, ";
      $_QLfol .= "$_I616t.`OnFollowUpDoneCopyToMailList`, $_I616t.`OnFollowUpDoneMoveToMailList`, $_I616t.`forms_id`, $_I616t.`StartDateOfFirstFUMail`, $_I616t.`FirstFollowUpMailDateFieldName`, $_I616t.`FormatOfFirstFollowUpMailDateField`, $_I616t.`GroupsTableName` AS FUResponders_GroupsTableName,";
      $_QLfol .= " $_I616t.`NotInGroupsTableName` AS FUResponders_NotInGroupsTableName, $_I616t.`SendTimeVariant`, $_I616t.`SendTime`,";
      $_QLfol .= "$_Ijt0i.`id` AS MTA_id, $_QL88I.`MaillistTableName`, $_QL88I.`LocalBlocklistTableName`, $_QL88I.`id` AS MailingListId, $_QL88I.`MailListToGroupsTableName`, $_QL88I.`StatisticsTableName`, $_QL88I.`MailLogTableName`, $_QL88I.`EditTableName`, $_QL88I.`GroupsTableName` AS MailLists_GroupsTableName, $_QL88I.`Name` AS MailingListName FROM `$_I616t` LEFT JOIN `$_Ijt0i` ON $_Ijt0i.`id`=$_I616t.`mtas_id`";
      $_QLfol .= " LEFT JOIN $_QL88I ON $_QL88I.id=$_I616t.maillists_id WHERE $_I616t.IsActive=1";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != "")
        $_JIfo0 .= "MySQL error while selecting data: ".mysql_error($_QLttI);
      while($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
        _LRCOC();

        $_JIfo0 .= "Checking $_I1OfI[FUResponders_Name]...<br />";


        $_JJjQJ = 0;

        $_I8I6o = $_I1OfI["MaillistTableName"];
        $_jjj8f = $_I1OfI["LocalBlocklistTableName"];
        $_I8jjj = $_I1OfI["StatisticsTableName"];
        $_IfJ66 = $_I1OfI["MailListToGroupsTableName"];
        $_I8jLt = $_I1OfI["MailLogTableName"];
        $MailingListId = $_I1OfI["MailingListId"];
        $_I8Jti = $_I1OfI["EditTableName"];
        $_QljJi = $_I1OfI["MailLists_GroupsTableName"];
        $_jjjCL = $_I1OfI["FUResponders_GroupsTableName"];
        $_jjJ00 = $_I1OfI["FUResponders_NotInGroupsTableName"];

        $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_jjjCL`";
        $_jjJfo = mysql_query($_QLfol, $_QLttI);
        $_jj6f1 = 0;
        if($_jjJfo && $_jj6L6 = mysql_fetch_row($_jjJfo)) {
          $_jj6f1 = $_jj6L6[0];
          mysql_free_result($_jjJfo);
        }

        $_jjfiO = 0;
        if($_jj6f1 > 0){
          $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_jjJ00`";
          $_jjJfo = mysql_query($_QLfol, $_QLttI);
          if($_jjJfo && $_jj6L6 = mysql_fetch_row($_jjJfo)) {
            $_jjfiO = $_jj6L6[0];
            mysql_free_result($_jjJfo);
          }
        }

        // copy / move recipients
        if( $_I1OfI["OnFollowUpDoneAction"] > 1 || !empty($_I1OfI["OnFollowUpDoneScriptURL"]) ) {
          $_QLfol = "SELECT `sort_order` FROM `$_I1OfI[FUMailsTableName]` ORDER BY sort_order DESC LIMIT 0, 1";
          $_JfIlJ = mysql_query($_QLfol, $_QLttI);
          $_Jfjtj=1;
          if($_JfIlJ && $_JfJ0J = mysql_fetch_assoc($_JfIlJ)) {
             $_Jfjtj = $_JfJ0J["sort_order"] + 1;
          }
          if($_JfIlJ)
            mysql_free_result($_JfIlJ);

          $_QLfol = "SELECT `$_I8I6o`.id FROM `$_I8I6o` LEFT JOIN `$_I1OfI[ML_FU_RefTableName]` ON `$_I1OfI[ML_FU_RefTableName]`.`Member_id`=`$_I1OfI[MaillistTableName]`.id WHERE `$_I1OfI[ML_FU_RefTableName]`.`NextFollowUpID` IS NOT NULL AND `$_I1OfI[ML_FU_RefTableName]`.`NextFollowUpID`>=$_Jfjtj AND `$_I1OfI[ML_FU_RefTableName]`.`OnFollowUpDoneActionDone`=0";
          $_JfIlJ = mysql_query($_QLfol, $_QLttI);
          $_I8oIJ = array();
          while($_JfJ0J = mysql_fetch_assoc($_JfIlJ)){
             // in outqueue?
             $_QLfol = "SELECT COUNT(id) FROM `$_IQQot` WHERE `users_id`=$UserId AND `maillists_id`=$_I1OfI[MailingListId] AND `recipients_id`=$_JfJ0J[id]";
             $_jjJfo = mysql_query($_QLfol, $_QLttI);
             $_jj6L6 = mysql_fetch_row($_jjJfo);
             mysql_free_result($_jjJfo);
             if($_jj6L6[0] == 0) {
               $_I8oIJ[] = $_JfJ0J["id"];
             }
          }
          mysql_free_result($_JfIlJ);

          if(count($_I8oIJ)) {

            if($_I1OfI["OnFollowUpDoneAction"] > 1){

              $_jJi11 = array();
              $_IQ0Cj = array();

              $_QLfol = "SELECT `MaillistTableName`, `StatisticsTableName`, `MailLogTableName`, `EditTableName`, `GroupsTableName`, `MailListToGroupsTableName` FROM `$_QL88I` WHERE id=";
              if( $_I1OfI["OnFollowUpDoneAction"] == 2){
                 $_QLfol .= "$_I1OfI[OnFollowUpDoneCopyToMailList]";
                 $_JfJt1 = "copied";
                 }
                 else
                 if( $_I1OfI["OnFollowUpDoneAction"] == 3){
                   $_QLfol .= "$_I1OfI[OnFollowUpDoneMoveToMailList]";
                   $_JfJt1 = "moved";
                 }
                 else
                 if( $_I1OfI["OnFollowUpDoneAction"] == 4){
                   $_JfJt1 = "disabled";
                 }
                 else
                 if( $_I1OfI["OnFollowUpDoneAction"] == 5){
                   $_JfJt1 = "removed";
                 }

              if( $_I1OfI["OnFollowUpDoneAction"] < 4){
                $_jjJfo = mysql_query($_QLfol, $_QLttI);
                if($_jjJfo && $_jj6L6 = mysql_fetch_assoc($_jjJfo)) {

                  $_JfJtO = "SYSTEM: Follow up responder '$_I1OfI[FUResponders_Name]' has " . $_JfJt1 . " recipient from mailing list '$_I1OfI[MailingListName]'";
                  _J1O6L($_I8oIJ, $_jJi11, $_jj6L6["MaillistTableName"], $_jj6L6["StatisticsTableName"], $_jj6L6["MailLogTableName"], $_jj6L6["EditTableName"], $_I1OfI["OnFollowUpDoneAction"] == 3, $_JfJtO, true, $_jj6L6["GroupsTableName"], $_jj6L6["MailListToGroupsTableName"]);
                }
                if($_jjJfo)
                  mysql_free_result($_jjJfo);
              }

              if( $_I1OfI["OnFollowUpDoneAction"] == 4){
                $_JfJtO = "SYSTEM: Follow up responder '$_I1OfI[FUResponders_Name]' has " . $_JfJt1 . " recipient in mailing list '$_I1OfI[MailingListName]'";
                _J1RD0(false, $_I8oIJ, $_IQ0Cj, $_JfJtO);
              }

              if( $_I1OfI["OnFollowUpDoneAction"] == 5){
                // $_JfJtO = "SYSTEM: Follow up responder '$_I1OfI[FUResponders_Name]' has " . $_JfJt1 . " recipient in mailing list '$_I1OfI[MailingListName]'";
                _J1OQP($_I8oIJ, $_IQ0Cj, true);
              }

              if( $_I1OfI["OnFollowUpDoneAction"] != 3 && $_I1OfI["OnFollowUpDoneAction"] != 5 ){ // not on move, remove

                mysql_query("BEGIN", $_QLttI);

                reset($_I8oIJ);
                $_QLlO6 = array();
                foreach($_I8oIJ as $_Qli6J => $_QltJO) {
                  $_QLlO6[] = "`Member_id`=".$_I8oIJ[$_Qli6J];
                  if(count($_QLlO6) > 20){
                    $_QLfol = "UPDATE `$_I1OfI[ML_FU_RefTableName]` SET `OnFollowUpDoneActionDone`=1 WHERE ".join(" OR ", $_QLlO6);
                    mysql_query($_QLfol, $_QLttI);
                    $_QLlO6 = array();
                  }
                }

                if(count($_QLlO6)){
                  $_QLfol = "UPDATE `$_I1OfI[ML_FU_RefTableName]` SET `OnFollowUpDoneActionDone`=1 WHERE ".join(" OR ", $_QLlO6);
                  mysql_query($_QLfol, $_QLttI);
                }

                mysql_query("COMMIT", $_QLttI);
              }

            }

            if(!empty($_I1OfI["OnFollowUpDoneScriptURL"])){
             for($_Qli6J=0; $_Qli6J<count($_I8oIJ); $_Qli6J++){
               _LLALJ($_I1OfI["OnFollowUpDoneScriptURL"], $_I8oIJ[$_Qli6J]);
               if($_I1OfI["OnFollowUpDoneAction"] < 2){
                  $_QLfol = "UPDATE `$_I1OfI[ML_FU_RefTableName]` SET `OnFollowUpDoneActionDone`=1 WHERE `Member_id`=".$_I8oIJ[$_Qli6J];
                  mysql_query($_QLfol, $_QLttI);
               }
             }
            }

          }
        }

        //

        $_jj8Ci = " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
        $_jj8Ci .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
        $_jjtQf = " `$_I8I6o`.`IsActive`=1 AND `$_I8I6o`.`SubscriptionStatus`<>'OptInConfirmationPending'".$_QLl1Q;
        $_jjtQf .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ".$_QLl1Q;

        $_jjt6l = "";
        $_jjOj1 = "";
        if($_jj6f1 > 0) {

          $_jjt6l .= " LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
          $_jjt6l .= " LEFT JOIN `$_jjjCL` ON `$_jjjCL`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
          if($_jjfiO > 0) {
            $_jjt6l .= "  LEFT JOIN ( ".$_QLl1Q;

            $_jjt6l .= "    SELECT `$_I8I6o`.`id`".$_QLl1Q;
            $_jjt6l .= "    FROM `$_I8I6o`".$_QLl1Q;

            $_jjt6l .= "    LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
            $_jjt6l .= "    LEFT JOIN `$_jjJ00` ON".$_QLl1Q;
            $_jjt6l .= "    `$_jjJ00`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
            $_jjt6l .= "    WHERE `$_jjJ00`.`ml_groups_id` IS NOT NULL".$_QLl1Q;

            $_jjt6l .= "  ) AS `subquery` ON `subquery`.`id`=`$_I8I6o`.`id`".$_QLl1Q;
          }

          if($_jj6f1 > 0) {
            $_jjOj1 .= " AND (`$_jjjCL`.`ml_groups_id` IS NOT NULL)".$_QLl1Q;
            if($_jjfiO > 0) {
             $_jjOj1 .= " AND (`subquery`.`id` IS NULL)".$_QLl1Q;
            }
          }

        }

        $_jjOLf = "";
        if($_I1OfI["StartDateOfFirstFUMail"] == 1 && isset($_I1OfI["FirstFollowUpMailDateFieldName"]) && $_I1OfI["FirstFollowUpMailDateFieldName"] != "")
           $_jjOLf = ", `$_I8I6o`.`$_I1OfI[FirstFollowUpMailDateFieldName]` ";

        if($_jj6f1 > 1) {
          $_jjOlo = "DISTINCT `$_I8I6o`.`u_EMail`";
        } else
          $_jjOlo = "`$_I8I6o`.u_EMail";

        $_QLfol = "SELECT $_jjOlo, `$_I8I6o`.id, `$_I8I6o`.DateOfOptInConfirmation, `$_I1OfI[ML_FU_RefTableName]`.NextFollowUpID, `$_I1OfI[ML_FU_RefTableName]`.LastSending ".$_jjOLf;
        $_QLfol .= " FROM `$_I1OfI[MaillistTableName]` $_jj8Ci $_jjt6l LEFT JOIN `$_I1OfI[ML_FU_RefTableName]` ON `$_I1OfI[ML_FU_RefTableName]`.Member_id=`$_I1OfI[MaillistTableName]`.id";
        $_QLfol .= " WHERE $_jjtQf $_jjOj1";
        $_jjl0t = mysql_query($_QLfol, $_QLttI);

        if(mysql_error($_QLttI) != "")
          $_JIfo0 .= "MySQL error while selecting data(2): ".mysql_error($_QLttI);

        // ECGList
        if(!isset($_jlt10))
          $_jlt10 = _JOLQE("ECGListCheck");
        if($_jlt10){
          // ECG List not more than 5000
          if($_I1OfI["MaxEMailsToProcess"] > 5000)
            $_I1OfI["MaxEMailsToProcess"] = 5000;
          $_J06Ji = array();                        
          while($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)){ 
            $_J06Ji[] = array("email" => $_I8fol["u_EMail"]/*, "id" => $_QLO0f["id"]*/);
          }  
            
          $_J0fIj = array();
          $_J08Q1 = "";
          $_J0t0L = _L6AF6($_J06Ji, $_J0fIj, $_J08Q1);    
          if(!$_J0t0L) // request failed, is ever in ECG-liste
            $_J0fIj = $_J06Ji;
          unset($_J06Ji); 
          mysql_data_seek($_jjl0t, 0);
        }  
        // ECGList /

        while($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)) {

          // limit reached?
          if($_JJjQJ >= $_I1OfI["MaxEMailsToProcess"]) break;

          _LRCOC();

          //ECGList
          $_J0olI = false;
          if($_jlt10){
            $_J0olI = array_search($_I8fol["u_EMail"], array_column($_J0fIj, 'email')) !== false;
          }

          $_jJ0iJ = "";

          if($_I1OfI["FUResponders_ResponderType"] == FUResponderTypeActionBased){
             $_jJ1it = "";
             $_jJ1LJ = "";
             if($_I8fol["NextFollowUpID"] == NULL || $_I8fol["NextFollowUpID"] <= 0){ // has never got an email take first mail
                 $_jJ1it = ", `FirstRecipientsAction`, `FirstRecipientsActionCampaign_id`, `FirstRecipientsActionCampaignLink_id`";
                 $_jJ1LJ = " ORDER BY `sort_order` LIMIT 0, 1";
               }
               else{
                 $_jJ1it = ", `LastRecipientsAction`, `LastRecipientsActionLink_id`";
                 $_jJ1LJ = " WHERE `sort_order`=$_I8fol[NextFollowUpID]";
               }

            $_QLfol = "SELECT `sort_order` $_jJ1it FROM `$_I1OfI[FUMailsTableName]`";
            $_QLfol .= $_jJ1LJ;

            $_It16Q = mysql_query($_QLfol, $_QLttI);
            if(!$_It16Q || !($_jJQ0L = mysql_fetch_assoc($_It16Q)) ) continue;

            mysql_free_result($_It16Q);
            if($_jJQ0L === FALSE) continue;


            if($_I8fol["NextFollowUpID"] == NULL || $_I8fol["NextFollowUpID"] <= 0){ # first email?
               if( !($_jJ0iJ = _LR6LJ($_jJQ0L["FirstRecipientsAction"], $_jJQ0L["FirstRecipientsActionCampaign_id"], $_jJQ0L["FirstRecipientsActionCampaignLink_id"], $_I8fol["id"])) ){
                 continue;
               }

            } else{
              if( (!$_jJ0iJ = _LRROB($_jJQ0L["LastRecipientsAction"], $_jJQ0L["LastRecipientsActionLink_id"], $_I1OfI["FUMailsTableName"], $_I8fol["NextFollowUpID"], $_I8fol["id"])) ){
                continue;
              }
            }
            if($_jJ0iJ === true)
               $_jJ0iJ = "";  // variant email was sent, ever take standard of time based fums
               else
               $_jJ0iJ = "'$_jJ0iJ'";
          }

          $_QLfol = "SELECT id, sort_order, SendInterval, SendIntervalType FROM `$_I1OfI[FUMailsTableName]`";

          if($_I8fol["NextFollowUpID"] == NULL || $_I8fol["NextFollowUpID"] <= 0) { // has never got an email take first mail
            $_QLfol .= " ORDER BY sort_order LIMIT 0, 1";
            $_It16Q = mysql_query($_QLfol, $_QLttI);
            if(!$_It16Q || !($_jJQ0L = mysql_fetch_assoc($_It16Q))) continue;
            mysql_free_result($_It16Q);
            if($_jJQ0L === FALSE) continue;

            $_QLfol = "SELECT id, MailSubject, SendInterval, SendIntervalType FROM `$_I1OfI[FUMailsTableName]`";
            if($_I1OfI["StartDateOfFirstFUMail"] == 0) {


               if($_I1OfI["FUResponders_ResponderType"] == FUResponderTypeActionBased && isset($_jJ0iJ) && $_jJ0iJ != ""){
                 $_jJQjO = $_jJ0iJ;
               } else {
                 $_jJQjO = "'" . $_I8fol["DateOfOptInConfirmation"] . "'";
               }

               if($_I1OfI["SendTimeVariant"] == 'sendingWithoutSendTime')
                  $_QLfol .= " WHERE sort_order=$_jJQ0L[sort_order] AND NOW() >= DATE_ADD($_jJQjO, INTERVAL $_jJQ0L[SendInterval] $_jJQ0L[SendIntervalType]) ";
                  else
                  $_QLfol .= " WHERE sort_order=$_jJQ0L[sort_order] AND NOW() >= DATE_ADD($_jJQjO, INTERVAL $_jJQ0L[SendInterval] $_jJQ0L[SendIntervalType]) AND CURRENT_TIME() >= '$_I1OfI[SendTime]' ";
               } else{
                if($_I1OfI["FirstFollowUpMailDateFieldName"] != "u_Birthday") {

                  if($_I1OfI["FormatOfFirstFollowUpMailDateField"] == "") {
                     if($INTERFACE_LANGUAGE != "de")
                       $_I1OfI["FormatOfFirstFollowUpMailDateField"] = "yyyy-mm-dd";
                       else
                       $_I1OfI["FormatOfFirstFollowUpMailDateField"] = "dd.mm.yyyy";
                  }

                  $_IOCjL = trim($_I8fol[$_I1OfI["FirstFollowUpMailDateFieldName"]]);
                  if($_I1OfI["FormatOfFirstFollowUpMailDateField"] == "dd.mm.yyyy") {
                    $_jJQOo = explode(".", $_IOCjL);
                    while(count($_jJQOo) < 3)
                       $_jJQOo[] = "f";
                    $_jjOlo = $_jJQOo[0];
                    $_jJIft = $_jJQOo[1];
                    $_jJjQi = $_jJQOo[2];
                  } else
                    if($_I1OfI["FormatOfFirstFollowUpMailDateField"] == "yyyy-mm-dd") {
                     $_jJQOo = explode("-", $_IOCjL);
                     while(count($_jJQOo) < 3)
                        $_jJQOo[] = "f";
                     $_jjOlo = $_jJQOo[2];
                     $_jJIft = $_jJQOo[1];
                     $_jJjQi = $_jJQOo[0];
                    } else
                      if($_I1OfI["FormatOfFirstFollowUpMailDateField"] == "mm-dd-yyyy") {
                        $_jJQOo = explode("-", $_IOCjL);
                        while(count($_jJQOo) < 3)
                           $_jJQOo[] = "f";
                        $_jjOlo = $_jJQOo[1];
                        $_jJIft = $_jJQOo[0];
                        $_jJjQi = $_jJQOo[2];
                      }

                  if(strlen($_jJjQi) == 2)
                    $_jJjQi = "19".$_jJjQi;
                  if( ! (
                      (intval($_jjOlo) > 0 && intval($_jjOlo) < 32) &&
                      (intval($_jJIft) > 0 && intval($_jJIft) < 13)
                        )
                    ) // error in date
                    $_IOCjL = "0000-00-00";
                    else
                    $_IOCjL = "$_jJjQi-$_jJIft-$_jjOlo";


                  if($_I1OfI["FUResponders_ResponderType"] == FUResponderTypeTimeBased || $_jJ0iJ == ""){
                    if($_IOCjL != "0000-00-00")
                      $_I8fol[$_I1OfI["FirstFollowUpMailDateFieldName"]] = $_IOCjL;
                      else
                      $_I8fol[$_I1OfI["FirstFollowUpMailDateFieldName"]] = $_I8fol["DateOfOptInConfirmation"];
                  } else {
                    $_I8fol[$_I1OfI["FirstFollowUpMailDateFieldName"]] = $_jJ0iJ;
                  }
                }

                if($_I1OfI["SendTimeVariant"] == 'sendingWithoutSendTime')
                  $_QLfol .= " WHERE sort_order=$_jJQ0L[sort_order] AND NOW() >= DATE_ADD('".$_I8fol[$_I1OfI["FirstFollowUpMailDateFieldName"]]."', INTERVAL $_jJQ0L[SendInterval] $_jJQ0L[SendIntervalType]) ";
                  else
                  $_QLfol .= " WHERE sort_order=$_jJQ0L[sort_order] AND NOW() >= DATE_ADD('".$_I8fol[$_I1OfI["FirstFollowUpMailDateFieldName"]]."', INTERVAL $_jJQ0L[SendInterval] $_jJQ0L[SendIntervalType])  AND CURRENT_TIME() >= '$_I1OfI[SendTime]' ";

               }

          } else {
            $_QLfol .= " WHERE sort_order=$_I8fol[NextFollowUpID]";
            $_It16Q = mysql_query($_QLfol, $_QLttI);
            if(!$_It16Q || !($_jJQ0L = mysql_fetch_assoc($_It16Q)) ) continue;

            mysql_free_result($_It16Q);
            if($_jJQ0L === FALSE) continue;

            $_QLfol = "SELECT id, MailSubject FROM `$_I1OfI[FUMailsTableName]`";

            if($_I1OfI["FUResponders_ResponderType"] == FUResponderTypeActionBased && isset($_jJ0iJ) &&  $_jJ0iJ != ""){
               $_jJQjO = $_jJ0iJ;
            } else {
               $_jJQjO = "'" . $_I8fol["LastSending"] . "'";
            }

            if($_I1OfI["SendTimeVariant"] == 'sendingWithoutSendTime')
              $_QLfol .= " WHERE sort_order=$_I8fol[NextFollowUpID] AND NOW() >= DATE_ADD($_jJQjO, INTERVAL $_jJQ0L[SendInterval] $_jJQ0L[SendIntervalType]) ";
              else
              $_QLfol .= " WHERE sort_order=$_I8fol[NextFollowUpID] AND NOW() >= DATE_ADD($_jJQjO, INTERVAL $_jJQ0L[SendInterval] $_jJQ0L[SendIntervalType]) AND CURRENT_TIME() >= '$_I1OfI[SendTime]' ";
          }


          $_jJjff = mysql_query($_QLfol, $_QLttI);

          while($_jJjff && $_jJJQt = mysql_fetch_assoc($_jJjff)) {
            _LRCOC();

            mysql_query("BEGIN", $_QLttI);

            $_QLfol = "INSERT INTO `$_I1OfI[RStatisticsTableName]` SET `MailSubject`="._LRAFO( unhtmlentities($_jJJQt["MailSubject"], $_QLo06, false) ).", `SendDateTime`=NOW(), `recipients_id`=$_I8fol[id], `fumails_id`=$_jJJQt[id], `Send`='Prepared'";
            mysql_query($_QLfol, $_QLttI);

            $_JJQ6I = 0;
            if(mysql_affected_rows($_QLttI) > 0) {
              $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
              $_JJIl0=mysql_fetch_array($_JJQlj);
              $_JJQ6I = $_JJIl0[0];
              mysql_free_result($_JJQlj);
            } else {
              if(mysql_errno($_QLttI) == 1062) { // dup key
                $_QLfol = "SELECT `id` FROM `$_I1OfI[RStatisticsTableName]` WHERE `recipients_id`=$_I8fol[id] AND `fumails_id`=$_jJJQt[id] AND `Send`='Prepared'";
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

            if($_JJQ6I){

              _LBOOC($_I1OfI["MailingListId"], $UserId, $_jJJQt["id"], 'FollowUpResponder', $_I1OfI["FUResponders_id"]);

              if(!$_J0olI){
                // SendId = follow up mail id
                $_QLfol = "INSERT INTO $_IQQot SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='FollowUpResponder', `Source_id`=$_I1OfI[FUResponders_id], `Additional_id`=$_jJJQt[id], `SendId`=$_jJJQt[id], `maillists_id`=$_I1OfI[MailingListId], `recipients_id`=$_I8fol[id], `mtas_id`=$_I1OfI[MTA_id], `LastSending`=NOW(), `IsResponder`=1, `MailSubject`="._LRAFO( unhtmlentities($_jJJQt["MailSubject"], $_QLo06, false) );
                mysql_query($_QLfol, $_QLttI);
                if(mysql_error($_QLttI) != "") {
                  $_JIfo0 .= "MySQL error while adding mail to out queue: ".mysql_error($_QLttI);
                  mysql_query("ROLLBACK", $_QLttI);
                  continue;
                }

                $_J0J6C++;

                $_JIfo0 .= "Email with subject '$_jJJQt[MailSubject]' was queued for sending to '$_I8fol[u_EMail]'<br />";
                // Update FUResponder statistics
                $_QLfol = "UPDATE $_I616t SET EMailsSent=EMailsSent+1 WHERE id=$_I1OfI[FUResponders_id]";
                mysql_query($_QLfol, $_QLttI);
              }else{
                $_QLfol = "UPDATE `$_I1OfI[RStatisticsTableName]` SET `Send`='Failed', `SendResult`=" . _LRAFO("Recipient is in ECG-Liste.") . "  WHERE `id`=$_JJQ6I";
                mysql_query($_QLfol, $_QLttI);
                $_JIfo0 .= "Email with subject '$_jJJQt[MailSubject]' was not sent to '$_I8fol[u_EMail]', Recipient is in ECG-Liste.<br />";
              }
              $_JJjQJ++;
            }

            // increase NextFollowUpID
            if($_I1OfI["SendTimeVariant"] == 'sendingWithoutSendTime')
               $_JfJif = "NOW()";
               else
               $_JfJif = "CONCAT(CURDATE(), ' ', '$_I1OfI[SendTime]')";

            $_QLfol = "UPDATE `$_I1OfI[ML_FU_RefTableName]` SET NextFollowUpID=NextFollowUpID+1, LastSending=$_JfJif WHERE Member_id=$_I8fol[id]";
            mysql_query($_QLfol, $_QLttI);
            if(mysql_error($_QLttI) != "" && $_I1OfI["SendTimeVariant"] != 'sendingWithoutSendTime') {
               $_JfJif = "NOW()";
               $_QLfol = "UPDATE `$_I1OfI[ML_FU_RefTableName]` SET NextFollowUpID=NextFollowUpID+1, LastSending=$_JfJif WHERE Member_id=$_I8fol[id]";
               mysql_query($_QLfol, $_QLttI);
            }

            if(mysql_affected_rows($_QLttI) == 0) { // new?
              $_QLfol = "INSERT INTO `$_I1OfI[ML_FU_RefTableName]` SET NextFollowUpID=2, Member_id=$_I8fol[id], LastSending=$_JfJif";
              mysql_query($_QLfol, $_QLttI);
              if(mysql_error($_QLttI) != "" && $_I1OfI["SendTimeVariant"] != 'sendingWithoutSendTime') {
                $_JfJif = "NOW()";
                $_QLfol = "INSERT INTO `$_I1OfI[ML_FU_RefTableName]` SET NextFollowUpID=2, Member_id=$_I8fol[id], LastSending=$_JfJif";
                mysql_query($_QLfol, $_QLttI);
              }
            }


            mysql_query("COMMIT", $_QLttI);

          } # while($_jJJQt = mysql_fetch_array($_jJjff))
          if($_jJjff)
            mysql_free_result($_jJjff);

        }
        if($_jjl0t)
          mysql_free_result($_jjl0t);

        $_JIfo0 .= "Done.<br />";

      } # while($_I1OfI = mysql_fetch_array($_I1O6j))
      if($_I1O6j)
        mysql_free_result($_I1O6j);
    } # while($_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
    mysql_free_result($_QL8i1);

    $_JIfo0 .= "<br />$_J0J6C emails sent to queue<br />";
    $_JIfo0 .= "<br />Follow Up Responder checking end.";

    if($_J0J6C)
      return true;
      else
      return -1;
  }


  function _LLALJ($_Jf61l, $ID) {
     global $AppName;
     if($_Jf61l == "") return true;

     $_JJl1I = 0;
     $_J600J = "";
     $_J608j = 80;
     if(strpos($_Jf61l, "http://") !== false) {
        $_J60tC = substr($_Jf61l, 7);
     } elseif(strpos($_Jf61l, "https://") !== false) {
       $_J608j = 443;
       $_J60tC = substr($_Jf61l, 8);
     }
     $_IJL6o = substr($_J60tC, strpos($_J60tC, "/"));
     $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

     $_I0QjQ = "AppName=$AppName&ID=$ID";
     _LABJA($_J60tC, "GET", $_IJL6o, $_I0QjQ, 0, $_J608j, false, "", "", $_JJl1I, $_J600J);
  }

?>
