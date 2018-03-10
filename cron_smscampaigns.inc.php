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
  include_once("smscampaignstools.inc.php");
  include_once("smsout.inc.php");

  function _ORADR(&$_j6O8O) {
    global $_Q61I1, $_Q6JJJ;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $resourcestrings, $_Q8f1L, $_Q880O;
    global $_Qofoi, $_Ql8C0, $_I88i8, $_Q60QL, $_QtjLI;
    global $_IoCtL, $_jJt8t;
    global $commonmsgHTMLMTANotFound;

    $_j6O8O = "SMS campaigns checking starts...<br />";
    $_jOo68 = 0;

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
      $_j8o0f = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_j8o0f[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);

      // check sent campaigns
      $_QJlJ0 = "SELECT $_IoCtL.id, $_IoCtL.Name AS CampaignsName, $_IoCtL.Creator_users_id, $_IoCtL.CurrentSendTableName, ";
      $_QJlJ0 .= "$_IoCtL.RStatisticsTableName, $_Q60QL.MTAsTableName, $_IoCtL.maillists_id, ";
      $_QJlJ0 .= "$_IoCtL.SendReportToYourSelf, $_IoCtL.SendReportToListAdmin, $_IoCtL.SendReportToMailingListUsers, $_IoCtL.SendReportToEMailAddress, $_IoCtL.SendReportToEMailAddressEMailAddress, $_IoCtL.SendScheduler, ";
      $_QJlJ0 .= "$_Q60QL.users_id, $_Q60QL.MaillistTableName, $_Q60QL.id AS MailingListId ";
      $_QJlJ0 .= " FROM $_IoCtL LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IoCtL.maillists_id";
      $_QJlJ0 .= " WHERE ($_IoCtL.SetupLevel=99 AND $_IoCtL.SendScheduler<>'SaveOnly' )"; // AND $_IoCtL.SendScheduler<>'SendManually' -> checked in CheckForSentCampaigns
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      while($_Q8Oj8 && $_j8o0f = mysql_fetch_assoc($_Q8Oj8)) {
        _OPQ6J();

        _ORBPJ($_j8o0f, $_j6O8O);

      } # while($_j8o0f = mysql_fetch_assoc($_Q8Oj8))

      if($_Q8Oj8)
        mysql_free_result($_Q8Oj8);


      // check sent campaigns done



      // check to send campaigns

      // MailTextInfos
      $_QJlJ0 = "SELECT $_IoCtL.*, $_Q60QL.MaillistTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.id AS MailingListId ";
      $_QJlJ0 .= " FROM $_IoCtL LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IoCtL.maillists_id";
      $_QJlJ0 .= " WHERE ($_IoCtL.SetupLevel=99 AND $_IoCtL.SendScheduler<>'SaveOnly' AND $_IoCtL.SendScheduler<>'SendManually') ";

      $_QJlJ0 .= " AND (";
      $_QJlJ0 .= " IF($_IoCtL.SendScheduler = 'SendInFutureOnce', NOW()>=$_IoCtL.SendInFutureOnceDateTime, 0)";
      $_QJlJ0 .= " OR $_IoCtL.SendScheduler='SendImmediately'";
      $_QJlJ0 .= " OR IF($_IoCtL.SendScheduler = 'SendInFutureMultiple',

                CURTIME()>=$_IoCtL.SendInFutureMultipleTime

                AND

                (
                IF(FIND_IN_SET('every day', $_IoCtL.SendInFutureMultipleDays) > 0, 1, FIND_IN_SET( CAST(DAYOFMONTH(NOW()) AS CHAR), $_IoCtL.SendInFutureMultipleDays) > 0)

                OR

                IF(FIND_IN_SET('every day', $_IoCtL.SendInFutureMultipleDayNames) > 0, 1, FIND_IN_SET( CAST(WEEKDAY(NOW()) AS CHAR), $_IoCtL.SendInFutureMultipleDayNames) > 0)
                )

                AND

                IF(FIND_IN_SET('every month', $_IoCtL.SendInFutureMultipleMonths) > 0, 1, FIND_IN_SET( CAST(MONTH(NOW()) AS CHAR), $_IoCtL.SendInFutureMultipleMonths) > 0)

              , 0)";
      $_QJlJ0 .= ")";

      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "") {
        # bug in some mysql versions Illegal mix of collations (latin1_swedish_ci,COERCIBLE) and (utf8_general_ci,IMPLICIT) for operation 'find_in_set'
        if(stripos(mysql_error($_Q61I1), "Illegal mix of collations") !== false) {
          $_QJlJ0 = "ALTER TABLE `$_IoCtL` CHANGE `SendInFutureMultipleDays` `SendInFutureMultipleDays` SET( 'none', 'every day', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none' ";
          mysql_query($_QJlJ0, $_Q61I1);
          $_QJlJ0 = "ALTER TABLE `$_IoCtL` CHANGE `SendInFutureMultipleDayNames` `SendInFutureMultipleDayNames` SET( 'none', 'every day', '0', '1', '2', '3', '4', '5', '6' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none' ";
          mysql_query($_QJlJ0, $_Q61I1);
          $_QJlJ0 = "ALTER TABLE `$_IoCtL` CHANGE `SendInFutureMultipleMonths` `SendInFutureMultipleMonths` SET( 'none', 'every month', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none'";
          mysql_query($_QJlJ0, $_Q61I1);
          $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br /> table structure was changed to latin charset<br />";
          continue; // we do it next time
        }
        $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
        continue;
      }

      $_jOI0f = new _LODEB();

      while($_Q8Oj8 && $_j8o0f = mysql_fetch_assoc($_Q8Oj8)) {
        _OPQ6J();

        // we can't check this in sql above
        if( $_j8o0f["SendScheduler"] == 'SendImmediately' || $_j8o0f["SendScheduler"] == 'SendInFutureOnce' ) {
          $_QJlJ0 = "SELECT COUNT(id) FROM `$_j8o0f[CurrentSendTableName]` WHERE SendState='Done'";
          $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Qt6f8 = mysql_fetch_row($_j8oC1);
          if($_Qt6f8[0] > 0) { // always send?
            if($_j8o0f["ReSendFlag"] < 1) {// send again?
               mysql_free_result($_j8oC1);
               continue;
            }
          }
          mysql_free_result($_j8oC1);
        }

        if($_j8o0f["SendScheduler"] == 'SendInFutureMultiple') {
          $_QJlJ0 = "SELECT COUNT(id) FROM `$_j8o0f[CurrentSendTableName]` WHERE SendState='Done' AND TO_DAYS(StartSendDateTime)=TO_DAYS(NOW())";
          $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Qt6f8 = mysql_fetch_row($_j8oC1);
          if($_Qt6f8[0] > 0) { // always send?
            mysql_free_result($_j8oC1);
            continue;
          }
          mysql_free_result($_j8oC1);
        }

        $MailingListId = $_j8o0f["maillists_id"];
        $FormId = $_j8o0f["forms_id"];
        $_Q6t6j = $_j8o0f["GroupsTableName"];
        $_QlQC8 = $_j8o0f["MaillistTableName"];
        $_QLI68 = $_j8o0f["MailListToGroupsTableName"];
        $_ItCCo = $_j8o0f["LocalBlocklistTableName"];

        // CurrentSendTableName
        $_jQlit = 0;
        $_j8Cji = 0;
        $_jI0Oo = 0;
        $_jIQfo = 0;

        $_QJlJ0 = "SELECT * ";
        $_QJlJ0 .= "FROM `$_j8o0f[CurrentSendTableName]` WHERE SendState<>'Done' AND SendState<>'Paused'";

        $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_j8oC1) > 0) {
          $_Qt6f8 = mysql_fetch_assoc($_j8oC1);
          mysql_free_result($_j8oC1);
          if($_Qt6f8["CampaignSendDone"]) // campaign always prepared completly?
            continue;
          $_jQlit = $_Qt6f8["LastMember_id"];
          $_j8Cji = $_Qt6f8["id"];
          $_jI0Oo = $_Qt6f8["RecipientsCount"];
          $_jIQfo = $_Qt6f8["ReportSent"];
        } else{
          mysql_free_result($_j8oC1);

          // CurrentSendTableName
          $_QJlJ0 = "INSERT INTO `$_j8o0f[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW()";
          mysql_query($_QJlJ0, $_Q61I1);
          $_j8oC1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
          $_Qt6f8=mysql_fetch_array($_j8oC1);
          $_j8Cji = $_Qt6f8[0];
          $_j8o0f["CurrentSendId"] = $_j8Cji;
          mysql_free_result($_j8oC1);
        }


        if($_jI0Oo == 0) {
          $_jI0Oo = _LOAFR($_j8o0f, $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
          mysql_query("UPDATE `$_j8o0f[CurrentSendTableName]` SET `RecipientsCount`=$_jI0Oo WHERE `id`=$_j8Cji", $_Q61I1);
        }

        $_QJlJ0 = _LOP8R($_j8o0f, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
        $_QJlJ0 .= " AND `$_QlQC8`.id>$_jQlit ORDER BY `$_QlQC8`.id LIMIT 0, $_j8o0f[MaxSMSToProcess]";

        $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);

        $_jOoio = 0;
        $_jOCjt = false;
        if(mysql_num_rows($_IOOt1) > 0) {
           $_j6O8O .= "checking $_j8o0f[Name]...<br />";
           $_jOI0f->SMSoutUsername = $_j8o0f["SMSoutUsername"];
           $_jOI0f->SMSoutPassword = $_j8o0f["SMSoutPassword"];
           $_Q8COf = $_jOI0f->Login();
           if(!$_Q8COf){
             $_j6O8O .= "Error logging in ".$_jOI0f->SMSoutLastErrorNo . " ". $_jOI0f->SMSoutLastErrorString."<br /><br />";
             $_jOCjt = true;
           }
        }

        # is campaign sending done?
        if(mysql_num_rows($_IOOt1) == 0) {
          $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET EndSendDateTime=NOW(), CampaignSendDone=1 WHERE id=$_j8Cji";
          mysql_query($_QJlJ0, $_Q61I1);
        }

        while($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {

          // limit reached?
          if($_jOoio >= $_j8o0f["MaxSMSToProcess"]) break;

          _OPQ6J();
          mysql_query("BEGIN", $_Q61I1);

          // prevent sending same SMS a second time
          $_QJlJ0 = "SELECT `id` FROM `$_j8o0f[RStatisticsTableName]` WHERE SendStat_id=$_j8Cji AND `recipients_id`=$_QlftL[id]";
          $_jfLII = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_num_rows($_jfLII) > 0) {
            mysql_free_result($_jfLII);
            # update last member id
            $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_QlftL[id] WHERE id=$_j8Cji AND LastMember_id<$_QlftL[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            mysql_query("COMMIT", $_Q61I1);
            continue;
          } else {
            @mysql_free_result($_jfLII);
          }

          $_Q8COf = true;
          $_II1Ot = array();
          if( ($_QlftL["u_CellNumber"] = trim($_QlftL["u_CellNumber"])) == ""  || !_LOC1E($_QlftL["u_CellNumber"], $_II1Ot) ){
            $_Q8COf = false;
            $_jOI0f->SMSoutLastErrorNo = -1;
            if(count($_II1Ot) == 0) {
               $_jOI0f->SMSoutLastErrorString = "Cell phone number shouldn't be empty.";
              }
              else {
                $_jOI0f->SMSoutLastErrorString = join(" ", $_II1Ot);
              }
          }

          $_jOiQ8 = $_j8o0f["SMSText"];
          $_jOiQ8 = _L1ERL($_QlftL, $MailingListId, $_jOiQ8, "utf-8", false, array());
          $_jOiiI = ConvertString("utf-8", "iso-8859-1", $_jOiQ8, false);

          if($_j8o0f["SMSSendVariant"] < 6)
            $_jOiLi = 160;
            else
            $_jOiLi = 1560;

          if(strlen($_jOiiI) > $_jOiLi) {
            $_jOiiI = chunk_split($_jOiiI, $_jOiLi - 1, chr(255));
            $_jOiiI = explode(chr(255), $_jOiiI);
          } else
            $_jOiiI = array($_jOiiI);

          $_jOLJ0 = 0;
          if($_Q8COf) {
            # Demo version
            if(defined("DEMO") || defined("SimulateMailSending")) {
              $_Q8COf = true;
            }
            else {
              if(!$_jOCjt) {
                for($_jOLtJ=0; $_jOLtJ<count($_jOiiI); $_jOLtJ++){
                  if($_jOiiI[$_jOLtJ] == "") continue;
                  $_Q8COf = $_jOI0f->SendSingleSMS($_j8o0f["SMSSendVariant"], $_QlftL["u_CellNumber"], $_j8o0f["SMSCampaignName"], $_jOiiI[$_jOLtJ]);
                  if(!$_Q8COf) break;
                  $_jOLJ0 += $_jOI0f->SMSoutLastErrorString;
                }
              } else {
                $_Q8COf = false;
              }
            }
          }


          if($_Q8COf) {
            $_jIlC0 = true;

            if(defined("DEMO") || defined("SimulateMailSending") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()))
              $_jj0JO = sprintf("Sent successfully, %s", "DEMO, ".sprintf("%01.2f", $_jOLJ0)." EUR");
            else
              $_jj0JO = sprintf("Sent successfully, %s", sprintf("%01.2f", $_jOLJ0)." EUR");

            // UpdateResponderStatistics
            // str_replace(",", ".", $_jOLJ0) weil anscheinend einige PHP versionen bei der Ausgabe aus dem Punkt ein Komma machen
            $_QJlJ0 = "INSERT INTO `$_j8o0f[RStatisticsTableName]` SET SendStat_id=$_j8Cji, `MailSubject`="._OPQLR($_jOiQ8).", `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Sent', SendResult='OK', `SMSCosts`="._OPQLR(str_replace(",", ".", $_jOLJ0));
            mysql_query($_QJlJ0, $_Q61I1);

            $_jOo68++;
            $_jOoio++;

          } else{
            if(!$_jOCjt)
              $_jj0JO = sprintf("Sending failed, Error code: %d, Error text: %s. ", $_jOI0f->SMSoutLastErrorNo, $_jOI0f->SMSoutLastErrorString);
              else
              $_jj0JO = "Login failed.";

            // UpdateResponderStatistics
            $_QJlJ0 = "INSERT INTO `$_j8o0f[RStatisticsTableName]` SET SendStat_id=$_j8Cji, `MailSubject`="._OPQLR($_jOiQ8).", `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Failed', SendResult="._OPQLR("SMS to $_QlftL[u_CellNumber] is undeliverable, Error:<br />".$_jOI0f->SMSoutLastErrorString." (".$_jOI0f->SMSoutLastErrorNo.")");
            mysql_query($_QJlJ0, $_Q61I1);

          }

          $_jfiol = 0;
          if(mysql_affected_rows($_Q61I1) > 0) {
            $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
            $_jfl1j = mysql_fetch_array($_jfLII);
            $_jfiol = $_jfl1j[0];
            mysql_free_result($_jfLII);
          } else {
            if(mysql_errno($_Q61I1) == 1062) { // dup key we do nothing here
            } else {
              $_j6O8O .= "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
              mysql_query("ROLLBACK", $_Q61I1);
              return false;
            }
          }

          //$_j6O8O .= "Email with subject '$_j8o0f[MailSubject]' was queued for sending to '$_QlftL[u_EMail]'<br />";

          # update last member id
          $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_QlftL[id] WHERE id=$_j8Cji";
          mysql_query($_QJlJ0, $_Q61I1);

          mysql_query("COMMIT", $_Q61I1);

        } # while($_QlftL = mysql_fetch_array($_IOOt1))
        if($_IOOt1)
          mysql_free_result($_IOOt1);

        if($_jOoio > 0) {
           $_j6O8O .= "$_j8o0f[Name] checked, $_jOoio SMS sent.<br />";
        }

      } # while($_j8o0f = mysql_fetch_assoc($_Q8Oj8))
      if($_Q8Oj8)
        mysql_free_result($_Q8Oj8);
    } # while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) )
    mysql_free_result($_Q60l1);


    $_j6O8O .= "<br />$_jOo68 SMS sent<br />";
    $_j6O8O .= "<br />SMS campaigns checking end.";

    if($_jOo68)
      return true;
      else
      return -1;
  }

  function _ORBPJ($_j8o0f, &$_j6O8O){
    global $_Q61I1, $UserId, $_QtjLI, $_IoCtL, $_Qofoi;
    global $commonmsgHTMLMTANotFound;
    $_QJlJ0 = "SELECT * ";
    $_QJlJ0 .= "FROM `$_j8o0f[CurrentSendTableName]` WHERE SendState<>'Done' AND CampaignSendDone<>0";

    $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_j8oC1 && $_Qt6f8 = mysql_fetch_assoc($_j8oC1) ) {
       _OPQ6J();

       if( (isset($_j8o0f["SendScheduler"]) && $_j8o0f["SendScheduler"] == 'SendManually') && $_Qt6f8["SendState"] != 'ReSending' ) {
         continue;
       }

       # anything of campaign in outqueue?
       $_QJlJ0 = "SELECT COUNT(id) FROM `$_QtjLI` WHERE `users_id`=$UserId AND `Source`='SMSCampaign' AND `Source_id`=$_j8o0f[id] AND `Additional_id`=0 AND `SendId`=$_Qt6f8[id]";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       if($_IO08Q[0] > 0) continue; // not done?

       mysql_query("BEGIN", $_Q61I1);

       // Update ReSendFlag
       $_QJlJ0 = "UPDATE `$_IoCtL` SET `ReSendFlag`=0 WHERE id=$_j8o0f[id]";
       mysql_query($_QJlJ0, $_Q61I1);

       // Count things
       $_QJlJ0 = "SELECT COUNT(id) FROM `$_j8o0f[RStatisticsTableName]` WHERE SendStat_id=$_Qt6f8[id] AND Send='Sent'";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       $_jI1Ql = $_IO08Q[0];

       $_QJlJ0 = "SELECT COUNT(id) FROM `$_j8o0f[RStatisticsTableName]` WHERE SendStat_id=$_Qt6f8[id] AND Send='Failed'";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       $_jI1tt = $_IO08Q[0];

       $_QJlJ0 = "SELECT COUNT(id) FROM `$_j8o0f[RStatisticsTableName]` WHERE SendStat_id=$_Qt6f8[id] AND Send='PossiblySent'";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       $_jIQ0i = $_IO08Q[0];

       // when resend from campaignlog than EndSendDateTime <> 0
       $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET EndSendDateTime=NOW() WHERE id=$_Qt6f8[id] AND EndSendDateTime=0";
       mysql_query($_QJlJ0, $_Q61I1);

       $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET SentCountSucc=$_jI1Ql, SentCountFailed=$_jI1tt, SentCountPossiblySent=$_jIQ0i, SendState='Done', ReportSent=1 WHERE id=$_Qt6f8[id]";
       mysql_query($_QJlJ0, $_Q61I1);

       mysql_query("COMMIT", $_Q61I1);

       $_j8o0f["ReportSent"] = $_Qt6f8["ReportSent"];

       if( $_j8o0f["SendReportToYourSelf"] || $_j8o0f["SendReportToListAdmin"] || $_j8o0f["SendReportToMailingListUsers"] || $_j8o0f["SendReportToEMailAddress"] ) {

         // MTA
         $_QJlJ0 = "SELECT $_Qofoi.* FROM $_Qofoi RIGHT JOIN $_j8o0f[MTAsTableName] ON $_j8o0f[MTAsTableName].mtas_id=$_Qofoi.id ORDER BY $_j8o0f[MTAsTableName].sortorder LIMIT 0, 1"; // only the first
         $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
         if($_ItlJl && mysql_num_rows($_ItlJl) > 0) {
           $_jIfO0 = mysql_fetch_assoc($_ItlJl);
           mysql_free_result($_ItlJl);

          // merge text with mail send settings
          if(isset($_jIfO0["id"])) {
            unset($_jIfO0["id"]);
            unset($_jIfO0["CreateDate"]);
            unset($_jIfO0["IsDefault"]);
            unset($_jIfO0["Name"]);
          }
          $_j8o0f = array_merge($_j8o0f, $_jIfO0);
          // send report
          _ORCL8($_j8o0f, $_Qt6f8["id"]);
         }

       } # if( $_j8o0f["SendReportToYourSelf"] || $_j8o0f["SendReportToListAdmin"] || $_j8o0f["SendReportToMailingListUsers"] || $_j8o0f["SendReportToEMailAddress"] )


    } # while( $_Qt6f8 = mysql_fetch_assoc($_j8oC1) )
    if($_j8oC1)
      mysql_free_result($_j8oC1);
  }


  function _ORCL8($_IiICC, $_If010) {
      global $_Q61I1, $_Q8f1L, $_Q6fio, $_jJJjO, $resourcestrings, $INTERFACE_LANGUAGE;
      global $_Q6JJJ, $_Q6QQL, $_jji0C;
      if(!$_IiICC["ReportSent"]) {


        $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
        $_If0Ql = "'%d.%m.%Y'";
        if($INTERFACE_LANGUAGE != "de") {
           $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
           $_If0Ql = "'%Y-%m-%d'";
        }

        // ************** creating report *********
        $_j8ilC = array();
        $From = array();

        if($_IiICC["SendReportToEMailAddress"] && !empty($_IiICC["SendReportToEMailAddressEMailAddress"]))
          $_j8ilC[0] = array("address" => $_IiICC["SendReportToEMailAddressEMailAddress"], "name" => "");

        $_Iijl0 = array();
        if($_IiICC["SendReportToYourSelf"])
          $_Iijl0[] = "id=$_IiICC[Creator_users_id]";
        if($_IiICC["SendReportToListAdmin"])
          $_Iijl0[] = "id=$_IiICC[users_id]";
        if($_IiICC["SendReportToMailingListUsers"]) {
          $_QJlJ0 = "SELECT users_id FROM $_Q6fio WHERE maillists_id=$_IiICC[maillists_id]";
          $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
          while($_I6JII=mysql_fetch_assoc($_j8Loj))
             $_Iijl0[] = "id=$_I6JII[users_id]";
          mysql_free_result($_j8Loj);
        }

        if(count($_Iijl0) > 0) {
          $_QJlJ0 = "SELECT id, EMail, FirstName, LastName FROM $_Q8f1L WHERE ".join(" OR ", $_Iijl0);
          $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
          while($_I6JII=mysql_fetch_assoc($_j8Loj)) {
             if( !array_key_exists($_I6JII["id"], $_j8ilC) )
                $_j8ilC[$_I6JII["id"]] = array("address" => $_I6JII["EMail"], "name" => trim($_I6JII["FirstName"]." ".$_I6JII["LastName"]));
             if($_I6JII["id"] == $_IiICC["Creator_users_id"])
                $From[] = array("address" => $_I6JII["EMail"], "name" => trim($_I6JII["FirstName"]." ".$_I6JII["LastName"]));
          }
          mysql_free_result($_j8Loj);

        } // if(count($_Iijl0) > 0)

        if(count($_j8ilC) > 0) {

          // mail class
          $_IiJit = new _OF0EE(mtInternalReportMail);

          if(count($From) == 0) {
            $_QJlJ0 = "SELECT id, EMail, FirstName, LastName FROM $_Q8f1L WHERE id=$_IiICC[Creator_users_id]";
            $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_num_rows($_j8Loj) > 0) {
              $_I6JII=mysql_fetch_assoc($_j8Loj);
              $From[] = array("address" => $_I6JII["EMail"], "name" => trim($_I6JII["FirstName"]." ".$_I6JII["LastName"]));
            } else {
              $From[] = array("address" => "webmaster@localhost", "name" => "");
            }
            mysql_free_result($_j8Loj);
          }

          $_IiJit->_OEADF();
          $_IiJit->_OE868();
          $_IiJit->From[] = array("address" => $From[0]["address"], "name" => $From[0]["name"] );
          foreach($_j8ilC as $key => $_Q6ClO)
             $_IiJit->To[] = array("address" => $_j8ilC[$key]["address"], "name" =>  $_j8ilC[$key]["name"]);

          _OPQ6J();
          $_Q8otJ = @file(_O68QF()."smscampaign_email_report.htm");
          if($_Q8otJ)
            $_Q6ICj = join("", $_Q8otJ);
            else
            $_Q6ICj = join("", file(InstallPath._O68QF()."smscampaign_email_report.htm"));

          $_Q8otJ = @file(_O68QF()."smscampaign_email_report.txt");
          if($_Q8otJ)
            $_I11oJ = join($_Q6JJJ, $_Q8otJ);
            else
            $_I11oJ = join($_Q6JJJ, file(InstallPath._O68QF()."smscampaign_email_report.txt"));
          $_I11oJ = utf8_encode($_I11oJ); // ANSI2UTF8

          $_IiJit->Subject = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001653"], $_IiICC["CampaignsName"] );
          _LJ81E($_Q6ICj);
          _LJ81E($_I11oJ);

          $_QJlJ0 = "SELECT *, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, ";
          $_QJlJ0 .= "DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated, ";
          $_QJlJ0 .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
          $_QJlJ0 .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_Q6QiO) AS SendEstEndTime ";
          $_QJlJ0 .= "FROM `$_IiICC[CurrentSendTableName]` WHERE id=$_If010";
          $_j8Li8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_num_rows($_j8Li8) > 0) {
            $_Q6Q1C = mysql_fetch_assoc($_j8Li8);
            $_jQlit = $_Q6Q1C["LastMember_id"];
            $_j8Cji = $_Q6Q1C["id"];
            $_jI0Oo = $_Q6Q1C["RecipientsCount"];
            $_jII6j = $_Q6Q1C["StartSendDateTimeFormated"];
            $_jIjJi = $_Q6Q1C["EndSendDateTimeFormated"];
            $_jIj6l = $_Q6Q1C["SendDuration"];
            $_jIjC1 = $_Q6Q1C["SendEstEndTime"];
            $_jI1Ql = $_Q6Q1C["SentCountSucc"];
            $_jI1tt = $_Q6Q1C["SentCountFailed"];
            $_jIQ0i = $_Q6Q1C["SentCountPossiblySent"];
            mysql_free_result($_j8Li8);
          }

          $_Q6ICj = str_replace('href="css/', 'href="'.ScriptBaseURL.'css/', $_Q6ICj);

          // html
          $_Q6ICj = _OPR6L($_Q6ICj, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_IiICC["CampaignsName"] );
          $_Q6ICj = _OPR6L($_Q6ICj, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jI0Oo);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jI1Ql);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jI1tt);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jIQ0i);

          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:START>", "</SENDING:START>", $_jII6j);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:END>", "</SENDING:END>", $_jIjJi);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jIj6l);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jIjC1);

          // text
          $_I11oJ = _OPR6L($_I11oJ, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_IiICC["CampaignsName"] );
          $_I11oJ = _OPR6L($_I11oJ, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jI0Oo);
          $_I11oJ = _OPR6L($_I11oJ, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jI1Ql);
          $_I11oJ = _OPR6L($_I11oJ, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jI1tt);
          $_I11oJ = _OPR6L($_I11oJ, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jIQ0i);

          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:START>", "</SENDING:START>", $_jII6j);
          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:END>", "</SENDING:END>", $_jIjJi);
          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jIj6l);
          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jIjC1);

          $_IiJit->TextPart = $_I11oJ;
          $_IiJit->HTMLPart = $_Q6ICj;
          $_IiJit->Priority = mpNormal;

          $_QJlJ0 = "SELECT * FROM $_jJJjO";
          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q8OiJ = mysql_fetch_array($_Q8Oj8);
          mysql_free_result($_Q8Oj8);
          $_IiJit->crlf = $_Q8OiJ["CRLF"];
          $_IiJit->head_encoding = $_Q8OiJ["Head_Encoding"];
          $_IiJit->text_encoding = $_Q8OiJ["Text_Encoding"];
          $_IiJit->html_encoding = $_Q8OiJ["HTML_Encoding"];
          $_IiJit->attachment_encoding = $_Q8OiJ["Attachment_Encoding"];
          $_IiJit->XMailer = $_Q8OiJ["XMailer"];

          $_IiJit->charset = $_Q6QQL;

          $_IiJit->Sendvariant = $_IiICC["Type"]; // mail, sendmail, smtp, smtpmx, text

          $_IiJit->PHPMailParams = $_IiICC["PHPMailParams"];
          $_IiJit->HELOName = $_IiICC["HELOName"];

          $_IiJit->SMTPpersist = (bool)$_IiICC["SMTPPersist"];
          $_IiJit->SMTPpipelining = (bool)$_IiICC["SMTPPipelining"];
          $_IiJit->SMTPTimeout = $_IiICC["SMTPTimeout"];
          $_IiJit->SMTPServer = $_IiICC["SMTPServer"];
          $_IiJit->SMTPPort = $_IiICC["SMTPPort"];
          $_IiJit->SMTPAuth = (bool)$_IiICC["SMTPAuth"];
          $_IiJit->SMTPUsername = $_IiICC["SMTPUsername"];
          $_IiJit->SMTPPassword = $_IiICC["SMTPPassword"];
          if(isset($_IiICC["SMTPSSL"]))
            $_IiJit->SSLConnection = (bool)$_IiICC["SMTPSSL"];

          $_IiJit->sendmail_path = $_IiICC["sendmail_path"];
          $_IiJit->sendmail_args = $_IiICC["sendmail_args"];

          $_IiJit->SignMail = (bool)$_IiICC["SMIMESignMail"];
          $_IiJit->SMIMEMessageAsPlainText = (bool)$_IiICC["SMIMEMessageAsPlainText"];

          $_IiJit->SignCert = $_IiICC["SMIMESignCert"];
          $_IiJit->SignPrivKey = $_IiICC["SMIMESignPrivKey"];
          $_IiJit->SignPrivKeyPassword = $_IiICC["SMIMESignPrivKeyPassword"];
          $_IiJit->SignTempFolder = $_jji0C;

          $_IiJit->SMIMEIgnoreSignErrors = (bool)$_IiICC["SMIMEIgnoreSignErrors"];

          $_IiJit->DKIM = (bool)$_IiICC["DKIM"];
          $_IiJit->DomainKey = (bool)$_IiICC["DomainKey"];
          $_IiJit->DKIMSelector = $_IiICC["DKIMSelector"];
          $_IiJit->DKIMPrivKey = $_IiICC["DKIMPrivKey"];
          $_IiJit->DKIMPrivKeyPassword = $_IiICC["DKIMPrivKeyPassword"];
          $_IiJit->DKIMIgnoreSignErrors = (bool)$_IiICC["DKIMIgnoreSignErrors"];

          $_Ii6QI = "";
          $_Ii6lO = "";
          _OPQ6J();
          if($_IiJit->_OED01($_Ii6QI, $_Ii6lO)) {
            _OPQ6J();
            $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);
          } else {
            // ignore errors here
          }


        } // if(count($_j8ilC) > 0)

        // ************** creating report  / *********
      } // if(!$_IiICC["ReportSent"])
  }


?>
