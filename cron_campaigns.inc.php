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
  //include_once("twitter.inc.php");

  function _OR1LE(&$_j6O8O) {
    global $_Q61I1, $_Q6JJJ;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Qo8OO, $resourcestrings, $_Q8f1L, $_Q880O;
    global $_jjC06, $_jjCtI, $_I0lQJ, $_jji0C, $_QOCJo, $_QCo6j, $_jji0i;
    global $_Qofoi, $_Ql8C0, $_I88i8, $_Q60QL, $_QtjLI;
    global $_Q6jOo, $_jJQ66;
    global $commonmsgHTMLMTANotFound;

    $_j6O8O = "EMailings checking starts...<br />";
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
      $_j8o0f = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_j8o0f[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);

      // check sent campaigns
      $_QJlJ0 = "SELECT $_Q6jOo.id, $_Q6jOo.Name AS CampaignsName, $_Q6jOo.Creator_users_id, $_Q6jOo.CurrentSendTableName, ";
      $_QJlJ0 .= "$_Q6jOo.RStatisticsTableName, $_Q6jOo.MTAsTableName, $_Q6jOo.maillists_id, ";
      $_QJlJ0 .= "$_Q6jOo.SendReportToYourSelf, $_Q6jOo.SendReportToListAdmin, $_Q6jOo.SendReportToMailingListUsers, $_Q6jOo.SendReportToEMailAddress, $_Q6jOo.SendReportToEMailAddressEMailAddress, $_Q6jOo.SendScheduler, ";
      $_QJlJ0 .= "$_Q60QL.users_id, $_Q60QL.MaillistTableName, $_Q60QL.id AS MailingListId ";
      $_QJlJ0 .= " FROM $_Q6jOo LEFT JOIN $_Q60QL ON $_Q60QL.id=$_Q6jOo.maillists_id";
      $_QJlJ0 .= " WHERE ($_Q6jOo.SetupLevel=99 AND $_Q6jOo.SendScheduler<>'SaveOnly' )"; // AND $_Q6jOo.SendScheduler<>'SendManually' -> checked in CheckForSentCampaigns
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      while($_Q8Oj8 && $_j8o0f = mysql_fetch_assoc($_Q8Oj8)) {
        _OPQ6J();

        _ORQOF($_j8o0f, $_j6O8O);

      } # while($_j8o0f = mysql_fetch_assoc($_Q8Oj8))

      if($_Q8Oj8)
        mysql_free_result($_Q8Oj8);


      // check sent campaigns done



      // check to send campaigns

      // MailTextInfos
      $_QJlJ0 = "SELECT $_Q6jOo.*, $_Q60QL.MaillistTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.id AS MailingListId ";
      $_QJlJ0 .= " FROM $_Q6jOo LEFT JOIN $_Q60QL ON $_Q60QL.id=$_Q6jOo.maillists_id";
      $_QJlJ0 .= " WHERE ($_Q6jOo.SetupLevel=99 AND $_Q6jOo.SendScheduler<>'SaveOnly' AND $_Q6jOo.SendScheduler<>'SendManually') ";

      $_QJlJ0 .= " AND (";
      $_QJlJ0 .= " IF($_Q6jOo.SendScheduler = 'SendInFutureOnce', NOW()>=$_Q6jOo.SendInFutureOnceDateTime, 0)";
      $_QJlJ0 .= " OR $_Q6jOo.SendScheduler='SendImmediately'";
      $_QJlJ0 .= " OR IF($_Q6jOo.SendScheduler = 'SendInFutureMultiple',

                CURTIME()>=$_Q6jOo.SendInFutureMultipleTime

                AND

                (
                IF(FIND_IN_SET('every day', $_Q6jOo.SendInFutureMultipleDays) > 0, 1, FIND_IN_SET( CAST(DAYOFMONTH(NOW()) AS CHAR), $_Q6jOo.SendInFutureMultipleDays) > 0)

                OR

                IF(FIND_IN_SET('every day', $_Q6jOo.SendInFutureMultipleDayNames) > 0, 1, FIND_IN_SET( CAST(WEEKDAY(NOW()) AS CHAR), $_Q6jOo.SendInFutureMultipleDayNames) > 0)
                )

                AND

                IF(FIND_IN_SET('every month', $_Q6jOo.SendInFutureMultipleMonths) > 0, 1, FIND_IN_SET( CAST(MONTH(NOW()) AS CHAR), $_Q6jOo.SendInFutureMultipleMonths) > 0)

              , 0)";
      $_QJlJ0 .= ")";

      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "") {
        # bug in some mysql versions Illegal mix of collations (latin1_swedish_ci,COERCIBLE) and (utf8_general_ci,IMPLICIT) for operation 'find_in_set'
        if(stripos(mysql_error($_Q61I1), "Illegal mix of collations") !== false) {
          $_QJlJ0 = "ALTER TABLE `$_Q6jOo` CHANGE `SendInFutureMultipleDays` `SendInFutureMultipleDays` SET( 'none', 'every day', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none' ";
          mysql_query($_QJlJ0, $_Q61I1);
          $_QJlJ0 = "ALTER TABLE `$_Q6jOo` CHANGE `SendInFutureMultipleDayNames` `SendInFutureMultipleDayNames` SET( 'none', 'every day', '0', '1', '2', '3', '4', '5', '6' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none' ";
          mysql_query($_QJlJ0, $_Q61I1);
          $_QJlJ0 = "ALTER TABLE `$_Q6jOo` CHANGE `SendInFutureMultipleMonths` `SendInFutureMultipleMonths` SET( 'none', 'every month', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none'";
          mysql_query($_QJlJ0, $_Q61I1);
          $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br /> table structure was changed to latin charset<br />";
          continue; // we do it next time
        }
        $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
        continue;
      }
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

          mysql_query("BEGIN", $_Q61I1);

          // CurrentSendTableName
          $_QJlJ0 = "INSERT INTO `$_j8o0f[CurrentSendTableName]` SET StartSendDateTime=NOW(), EndSendDateTime=NOW()";
          mysql_query($_QJlJ0, $_Q61I1);
          $_j8oC1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
          $_Qt6f8=mysql_fetch_array($_j8oC1);
          $_j8Cji = $_Qt6f8[0];
          $_j8o0f["CurrentSendId"] = $_j8Cji;
          mysql_free_result($_j8oC1);

          /*
          // Twitter Update start
          $_jItfj = "";
          $TwitterUpdateState = "TWITTER_UPDATE_NOT_CONFIGURED";
          if($_j8o0f["TwitterUpdate"]){
             $twitter = new _LJPOA($_j8o0f["TwitterUsername"], $_j8o0f["TwitterPassword"]);

             if($OwnerUserId == 0)
               $TrackingUserId = $UserId;
               else
               $TrackingUserId = $OwnerUserId;
             $ResponderTypeNum = _OAP0L("Campaign");
             $Identifier = sprintf("%02X", $_j8Cji)."_".sprintf("%02X", $TrackingUserId)."_".sprintf("%02X", $ResponderTypeNum)."_".sprintf("%02X", $_j8o0f["id"]);
             // Twitter
             $key = sprintf("twitter-%02X-%02X", $MailingListId, $FormId);

             $twitterURL = $_jJQ66."?key=$key&rid=".$Identifier;

             $Error = "";
             $twitterURL = $twitter->TwitterGetShortURL($twitterURL, $Error);
             if($twitterURL !== false) {
                 $_I11oJ = $_j8o0f["MailSubject"];
                 $fields = GetAllCampaignPersonalizingFields($_QlQC8);
                 reset($fields);
                 foreach($fields as $key => $_Q6ClO){
                   $_I11oJ = str_ireplace("[$key]", "", $_I11oJ);
                 }
                 if($twitter->TwitterSendStatusMessage("$_I11oJ\r\n".$twitterURL, $Error)){
                    $TwitterUpdateState = "TWITTER_UPDATE_DONE";
                  }
                   else {
                     $TwitterUpdateState = "TWITTER_UPDATE_FAILED";
                     $_jItfj = "TWITTER_UPDATE_POSTING_FAILED";
                   }
               }
               else {
                $TwitterUpdateState = "TWITTER_UPDATE_FAILED";
                $_jItfj = "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE";
              }

              $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET ";

              if($TwitterUpdateState == "TWITTER_UPDATE_NOT_CONFIGURED")
                 $_QJlJ0 .= "`TwitterUpdate`='NotActivated'";
                 else
                 if($TwitterUpdateState == "TWITTER_UPDATE_DONE")
                    $_QJlJ0 .= "`TwitterUpdate`='Done'";
                    else
                    if($TwitterUpdateState == "TWITTER_UPDATE_FAILED")
                       $_QJlJ0 .= "`TwitterUpdate`='Failed'";
              $_QJlJ0 .= ", `TwitterUpdateErrorText`="._OPQLR($_jItfj);
              $_QJlJ0 .= " WHERE id=$_j8Cji";
              mysql_query($_QJlJ0, $_Q61I1);

          }
          // Twitter Update end
          */

          // SET Current used MTAs to zero
          $_QJlJ0 = "INSERT INTO `$_j8o0f[CurrentUsedMTAsTableName]` SELECT 0, $_j8Cji, `mtas_id`, 0 FROM `$_j8o0f[MTAsTableName]` ORDER BY sortorder";
          mysql_query($_QJlJ0, $_Q61I1);

          // Archive Table
          $_QJlJ0 = "INSERT INTO `$_j8o0f[ArchiveTableName]` SET SendStat_id=$_j8Cji, ";
          $_QJlJ0 .= "MailFormat="._OPQLR($_j8o0f["MailFormat"]).", ";
          $_QJlJ0 .= "MailEncoding="._OPQLR($_j8o0f["MailEncoding"]).", ";
          $_QJlJ0 .= "MailSubject="._OPQLR($_j8o0f["MailSubject"]).", ";
          $_QJlJ0 .= "MailPlainText="._OPQLR($_j8o0f["MailPlainText"]).", ";
          $_QJlJ0 .= "MailHTMLText="._OPQLR($_j8o0f["MailHTMLText"]).", ";
          $_QJlJ0 .= "Attachments="._OPQLR($_j8o0f["Attachments"]);
          mysql_query($_QJlJ0, $_Q61I1);

          mysql_query("COMMIT", $_Q61I1);

        }

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

        if($_jI0Oo == 0) {
          $_jI0Oo = _O6QAL($_j8o0f, $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
          mysql_query("UPDATE `$_j8o0f[CurrentSendTableName]` SET RecipientsCount=$_jI0Oo WHERE id=$_j8Cji", $_Q61I1);
        }

        $_QJlJ0 = _O61RO($_j8o0f, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
        $_QJlJ0 .= " AND `$_QlQC8`.id>$_jQlit ORDER BY `$_QlQC8`.id LIMIT 0, $_j8o0f[MaxEMailsToProcess]";

        $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);

        $_jflIQ = 0;
        if(mysql_num_rows($_IOOt1) > 0)
           $_j6O8O .= "checking $_j8o0f[Name]...<br />";

        # is campaign sending done?
        if(mysql_num_rows($_IOOt1) == 0) {
          $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET EndSendDateTime=NOW(), CampaignSendDone=1 WHERE id=$_j8Cji";
          mysql_query($_QJlJ0, $_Q61I1);
        }

        while($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {

          // limit reached?
          if($_jflIQ >= $_j8o0f["MaxEMailsToProcess"]) break;

          _OPQ6J();

          mysql_query("BEGIN", $_Q61I1);

          $_QJlJ0 = "INSERT INTO `$_j8o0f[RStatisticsTableName]` SET `SendStat_id`=$_j8Cji, `MailSubject`="._OPQLR($_j8o0f["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Prepared'";
          mysql_query($_QJlJ0, $_Q61I1);

          $_jfiol = 0;
          if(mysql_affected_rows($_Q61I1) > 0) {
            $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
            $_jfl1j = mysql_fetch_array($_jfLII);
            $_jfiol = $_jfl1j[0];
            mysql_free_result($_jfLII);
          } else {
            if(mysql_errno($_Q61I1) == 1062) {// dup key
               $_QJlJ0 = "SELECT `id` FROM `$_j8o0f[RStatisticsTableName]` WHERE `SendStat_id`=$_j8Cji AND `recipients_id`=$_QlftL[id] AND `Send`='Prepared'";
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

            #$_j6O8O .= "<br />mta_id: ".$_j8o0f["mtas_id"]."<br />";
            $_QJlJ0 = "INSERT INTO `$_QtjLI` SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='Campaign', `Source_id`=$_j8o0f[id], `Additional_id`=0, `SendId`=$_j8Cji, `maillists_id`=$_j8o0f[MailingListId], `recipients_id`=$_QlftL[id], `mtas_id`=$_j8o0f[mtas_id], `LastSending`=NOW() ";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_error($_Q61I1) != "") {
              $_j6O8O .=  "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
              mysql_query("ROLLBACK", $_Q61I1);
              continue;
            }

            $_jIojl++;
            $_jflIQ++;
          }

          //$_j6O8O .= "Email with subject '$_j8o0f[MailSubject]' was queued for sending to '$_QlftL[u_EMail]'<br />";

          # update last member id
          $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_QlftL[id] WHERE id=$_j8Cji";
          mysql_query($_QJlJ0, $_Q61I1);

          mysql_query("COMMIT", $_Q61I1);

        } # while($_QlftL = mysql_fetch_array($_IOOt1))
        if($_IOOt1)
          mysql_free_result($_IOOt1);

        if($_jflIQ > 0) {
           $_j6O8O .= "$_j8o0f[Name] checked, $_jflIQ email(s) sent to queue.<br />";
        }

      } # while($_j8o0f = mysql_fetch_assoc($_Q8Oj8))
      if($_Q8Oj8)
        mysql_free_result($_Q8Oj8);
    } # while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) )
    mysql_free_result($_Q60l1);


    $_j6O8O .= "<br />$_jIojl emails sent to queue<br />";
    $_j6O8O .= "<br />EMailings checking end.";

    if($_jIojl)
      return true;
      else
      return -1;
  }

  function _ORQOF($_j8o0f, &$_j6O8O){
    global $_Q61I1, $UserId, $_QtjLI, $_Q6jOo, $_Qofoi;
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
       $_QJlJ0 = "SELECT COUNT(id) FROM `$_QtjLI` WHERE `users_id`=$UserId AND `Source`='Campaign' AND `Source_id`=$_j8o0f[id] AND `Additional_id`=0 AND `SendId`=$_Qt6f8[id]";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       if($_IO08Q[0] > 0) continue; // not done?

       mysql_query("BEGIN", $_Q61I1);

       // Update ReSendFlag
       $_QJlJ0 = "UPDATE `$_Q6jOo` SET `ReSendFlag`=0 WHERE id=$_j8o0f[id]";
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

       $_QJlJ0 = "SELECT COUNT(id) FROM `$_j8o0f[RStatisticsTableName]` WHERE SendStat_id=$_Qt6f8[id] AND Send='Failed' And `SendResult` LIKE '%permanently undeliverable%'";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       $_j8il0 = $_IO08Q[0];

       // when resend from campaignlog than EndSendDateTime <> 0
       $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET EndSendDateTime=NOW() WHERE id=$_Qt6f8[id] AND EndSendDateTime=0";
       mysql_query($_QJlJ0, $_Q61I1);

       $_QJlJ0 = "UPDATE `$_j8o0f[CurrentSendTableName]` SET SentCountSucc=$_jI1Ql, SentCountFailed=$_jI1tt, SentCountPossiblySent=$_jIQ0i, HardBouncesCount=$_j8il0, SendState='Done', ReportSent=1 WHERE id=$_Qt6f8[id]";
       mysql_query($_QJlJ0, $_Q61I1);

       mysql_query("COMMIT", $_Q61I1);

       $_j8o0f["ReportSent"] = $_Qt6f8["ReportSent"];

       if( $_j8o0f["SendReportToYourSelf"] || $_j8o0f["SendReportToListAdmin"] || $_j8o0f["SendReportToMailingListUsers"] || $_j8o0f["SendReportToEMailAddress"] ) {

         // MTA
         if(!isset($_j8o0f["mtas_id"])) {
           $_QJlJ0 = "SELECT mtas_id FROM `$_j8o0f[MTAsTableName]` ORDER BY sortorder LIMIT 0, 1"; // only the first
           $_j8Coj = mysql_query($_QJlJ0, $_Q61I1);
           if(!$_j8Coj || mysql_num_rows($_j8Coj) == 0) {
             $_j6O8O .= $commonmsgHTMLMTANotFound;
             continue;
           } else {
             $_jIfO0 = mysql_fetch_assoc($_j8Coj);
             mysql_free_result($_j8Coj);
             $_j8o0f["mtas_id"] = $_jIfO0["mtas_id"];
           }
         }

         $_QJlJ0 = "SELECT * FROM `$_Qofoi` WHERE id=$_j8o0f[mtas_id]";
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
          _ORO1F($_j8o0f, $_Qt6f8["id"]);
         }

       } # if( $_j8o0f["SendReportToYourSelf"] || $_j8o0f["SendReportToListAdmin"] || $_j8o0f["SendReportToMailingListUsers"] || $_j8o0f["SendReportToEMailAddress"] )


    } # while( $_Qt6f8 = mysql_fetch_assoc($_j8oC1) )
    if($_j8oC1)
      mysql_free_result($_j8oC1);
  }


  function _ORO1F($_IiICC, $_If010) {
      global $_Q61I1, $_Q8f1L, $_Q6fio, $_jJJjO, $resourcestrings, $INTERFACE_LANGUAGE, $UserId;
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

        if(!empty($_IiICC["MTASenderEMailAddress"]))
           $From = array("address" => $_IiICC["MTASenderEMailAddress"], "name" => "");
           else{
             // sender address
             $_QJlJ0 = "SELECT id, EMail, FirstName, LastName FROM $_Q8f1L WHERE id=$UserId";
             $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
             $_I6JII=mysql_fetch_assoc($_j8Loj);
             $From = array("address" => $_I6JII["EMail"], "name" => trim($_I6JII["FirstName"]." ".$_I6JII["LastName"]));
             mysql_free_result($_j8Loj);

             $_QJlJ0 = "SELECT id, EMail, FirstName, LastName FROM $_Q8f1L WHERE id=$_IiICC[Creator_users_id]";
             $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
             if(mysql_num_rows($_j8Loj) > 0) {
               $_I6JII=mysql_fetch_assoc($_j8Loj);
               $From = array("address" => $_I6JII["EMail"], "name" => trim($_I6JII["FirstName"]." ".$_I6JII["LastName"]));
               mysql_free_result($_j8Loj);
             } else {
               $_QJlJ0 = "SELECT id, EMail, FirstName, LastName FROM $_Q8f1L WHERE id=$_IiICC[users_id]";
               $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
               if(mysql_num_rows($_j8Loj) > 0){
                 $_I6JII=mysql_fetch_assoc($_j8Loj);
                 $From = array("address" => $_I6JII["EMail"], "name" => trim($_I6JII["FirstName"]." ".$_I6JII["LastName"]));
                 mysql_free_result($_j8Loj);
               }
             }

           }

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
          }
          mysql_free_result($_j8Loj);

        } // if(count($_Iijl0) > 0)

        if(count($_j8ilC) > 0) {

          // mail class
          $_IiJit = new _OF0EE(mtInternalReportMail);

          $_IiJit->_OEADF();
          $_IiJit->_OE868();
          $_IiJit->From[] = array("address" => $From["address"], "name" => $From["name"] );
          foreach($_j8ilC as $key => $_Q6ClO)
             $_IiJit->To[] = array("address" => $_j8ilC[$key]["address"], "name" =>  $_j8ilC[$key]["name"]);

          _OPQ6J();
          $_Q8otJ = @file(_O68QF()."campaign_email_report.htm");
          if($_Q8otJ)
            $_Q6ICj = join("", $_Q8otJ);
            else
            $_Q6ICj = join("", file(InstallPath._O68QF()."campaign_email_report.htm"));

          $_j8Li0 = join("", @file(InstallPath."css/default.css"));
          if(!empty($_j8Li0)){
           $_j8Li0 = str_replace('../images/', ScriptBaseURL."images/", $_j8Li0);
           $_j8Li0 = '<style type="text/css">'.$_Q6JJJ.$_j8Li0.$_Q6JJJ.'</style>';
           $_Q6ICj = str_replace("</head>", $_j8Li0.$_Q6JJJ."</head>", $_Q6ICj);
          }

          $_Q8otJ = @file(_O68QF()."campaign_email_report.txt");
          if($_Q8otJ)
            $_I11oJ = join($_Q6JJJ, $_Q8otJ);
            else
            $_I11oJ = join($_Q6JJJ, file(InstallPath._O68QF()."campaign_email_report.txt"));
          $_I11oJ = utf8_encode($_I11oJ); // ANSI2UTF8

          $_IiJit->Subject = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000653"], $_IiICC["CampaignsName"] );
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
            $_j8lIJ = $_Q6Q1C["HardBouncesCount"];
            $_j1tCi = $_Q6Q1C["TwitterUpdate"];
            $_jItfj = $_Q6Q1C["TwitterUpdateErrorText"];
            mysql_free_result($_j8Li8);
          }

          $_Q6ICj = str_replace('href="css/', 'href="'.ScriptBaseURL.'css/', $_Q6ICj);

          // html
          $_Q6ICj = _OPR6L($_Q6ICj, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_IiICC["CampaignsName"] );
          $_Q6ICj = _OPR6L($_Q6ICj, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jI0Oo);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jI1Ql);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jI1tt);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jIQ0i);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTBOUNCECOUNT>", "</SENTCOUNTBOUNCECOUNT>", $_j8lIJ);

          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:START>", "</SENDING:START>", $_jII6j);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:END>", "</SENDING:END>", $_jIjJi);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jIj6l);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jIjC1);

          if($_j1tCi == "NotActivated" || empty($_j1tCi))
             $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
          if($_j1tCi == "Done")
             $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
          if($_j1tCi == "Failed")
             if( $_jItfj == "TWITTER_UPDATE_POSTING_FAILED" )
                $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
                else
                if( $_jItfj == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
                    $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);

          // text
          $_I11oJ = _OPR6L($_I11oJ, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_IiICC["CampaignsName"] );
          $_I11oJ = _OPR6L($_I11oJ, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jI0Oo);
          $_I11oJ = _OPR6L($_I11oJ, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jI1Ql);
          $_I11oJ = _OPR6L($_I11oJ, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jI1tt);
          $_I11oJ = _OPR6L($_I11oJ, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jIQ0i);
          $_I11oJ = _OPR6L($_I11oJ, "<SENTCOUNTBOUNCECOUNT>", "</SENTCOUNTBOUNCECOUNT>", $_j8lIJ);

          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:START>", "</SENDING:START>", $_jII6j);
          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:END>", "</SENDING:END>", $_jIjJi);
          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jIj6l);
          $_I11oJ = _OPR6L($_I11oJ, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jIjC1);

          if($_j1tCi == "NotActivated")
             $_I11oJ = _OPR6L($_I11oJ, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
          if($_j1tCi == "Done")
             $_I11oJ = _OPR6L($_I11oJ, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
          if($_j1tCi == "Failed")
             if( $_jItfj == "TWITTER_UPDATE_POSTING_FAILED" )
                $_I11oJ = _OPR6L($_I11oJ, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
                else
                if( $_jItfj == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
                    $_I11oJ = _OPR6L($_I11oJ, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);

          $_IiJit->TextPart = $_I11oJ;
          $_IiJit->HTMLPart = $_Q6ICj;
          $_IiJit->Priority = mpNormal;

          $_QJlJ0 = "SELECT * FROM $_jJJjO";
          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
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


/*
function GetAllCampaignPersonalizingFields($_QlQC8){
  global $AllDefaultPlaceholders;
  $fieldnames = array();
  _OA60P($_QlQC8, $fieldnames, array());
  $fieldnames = array_merge($AllDefaultPlaceholders, $fieldnames);
  reset($fieldnames);
  foreach($fieldnames as $key => $_Q6ClO) {
    $MembersRecord[$key] = "";
  }
  return $MembersRecord;
}
*/

?>
