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
  //include_once("twitter.inc.php");

  function _LLJPL(&$_JIfo0) {
    global $_QLttI, $_QLl1Q, $_QLo06;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Ijt8j, $resourcestrings, $_I18lo, $_I1tQf;
    global $_J18oI, $_jfOJj, $_ItL8f, $_J1t6J, $_IIlfi, $_IJi8f, $_J1tfC;
    global $_Ijt0i, $_I8tfQ, $_jQ68I, $_QL88I, $_IQQot;
    global $_QLi60, $_jfilQ;
    global $commonmsgHTMLMTANotFound;

    $_JIfo0 = "EMailings checking starts...<br />";
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
      $_J61tJ = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_J61tJ[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);

      // check sent campaigns
      $_QLfol = "SELECT $_QLi60.id, $_QLi60.Name AS CampaignsName, $_QLi60.Creator_users_id, $_QLi60.CurrentSendTableName, ";
      $_QLfol .= "$_QLi60.RStatisticsTableName, $_QLi60.MTAsTableName, $_QLi60.maillists_id, ";
      $_QLfol .= "$_QLi60.SendReportToYourSelf, $_QLi60.SendReportToListAdmin, $_QLi60.SendReportToMailingListUsers, $_QLi60.SendReportToEMailAddress, $_QLi60.SendReportToEMailAddressEMailAddress, $_QLi60.SendScheduler, ";
      $_QLfol .= "$_QL88I.users_id, $_QL88I.MaillistTableName, $_QL88I.id AS MailingListId ";
      $_QLfol .= " FROM $_QLi60 LEFT JOIN $_QL88I ON $_QL88I.id=$_QLi60.maillists_id";
      $_QLfol .= " WHERE ($_QLi60.SetupLevel=99 AND $_QLi60.SendScheduler<>'SaveOnly' )"; // AND $_QLi60.SendScheduler<>'SendManually' -> checked in CheckForSentCampaigns
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      while($_I1O6j && $_J61tJ = mysql_fetch_assoc($_I1O6j)) {
        _LRCOC();

        _LL6R6($_J61tJ, $_JIfo0);

      } # while($_J61tJ = mysql_fetch_assoc($_I1O6j))

      if($_I1O6j)
        mysql_free_result($_I1O6j);


      // check sent campaigns done



      // check to send campaigns

      // MailTextInfos
      $_QLfol = "SELECT $_QLi60.*, $_QL88I.MaillistTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId ";
      $_QLfol .= " FROM $_QLi60 LEFT JOIN $_QL88I ON $_QL88I.id=$_QLi60.maillists_id";
      $_QLfol .= " WHERE ($_QLi60.SetupLevel=99 AND $_QLi60.SendScheduler<>'SaveOnly' AND $_QLi60.SendScheduler<>'SendManually') ";

      $_QLfol .= " AND (";
      $_QLfol .= " IF($_QLi60.SendScheduler = 'SendInFutureOnce', NOW()>=$_QLi60.SendInFutureOnceDateTime, 0)";
      $_QLfol .= " OR $_QLi60.SendScheduler='SendImmediately'";
      $_QLfol .= " OR IF($_QLi60.SendScheduler = 'SendInFutureMultiple',

                CURTIME()>=$_QLi60.SendInFutureMultipleTime

                AND

                (
                IF(FIND_IN_SET('every day', $_QLi60.SendInFutureMultipleDays) > 0, 1, FIND_IN_SET( CAST(DAYOFMONTH(NOW()) AS CHAR), $_QLi60.SendInFutureMultipleDays) > 0)

                OR

                IF(FIND_IN_SET('every day', $_QLi60.SendInFutureMultipleDayNames) > 0, 1, FIND_IN_SET( CAST(WEEKDAY(NOW()) AS CHAR), $_QLi60.SendInFutureMultipleDayNames) > 0)
                )

                AND

                IF(FIND_IN_SET('every month', $_QLi60.SendInFutureMultipleMonths) > 0, 1, FIND_IN_SET( CAST(MONTH(NOW()) AS CHAR), $_QLi60.SendInFutureMultipleMonths) > 0)

              , 0)";
      $_QLfol .= ")";

      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != "") {
        # bug in some mysql versions Illegal mix of collations (latin1_swedish_ci,COERCIBLE) and (utf8_general_ci,IMPLICIT) for operation 'find_in_set'
        if(stripos(mysql_error($_QLttI), "Illegal mix of collations") !== false) {
          $_QLfol = "ALTER TABLE `$_QLi60` CHANGE `SendInFutureMultipleDays` `SendInFutureMultipleDays` SET( 'none', 'every day', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none' ";
          mysql_query($_QLfol, $_QLttI);
          $_QLfol = "ALTER TABLE `$_QLi60` CHANGE `SendInFutureMultipleDayNames` `SendInFutureMultipleDayNames` SET( 'none', 'every day', '0', '1', '2', '3', '4', '5', '6' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none' ";
          mysql_query($_QLfol, $_QLttI);
          $_QLfol = "ALTER TABLE `$_QLi60` CHANGE `SendInFutureMultipleMonths` `SendInFutureMultipleMonths` SET( 'none', 'every month', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none'";
          mysql_query($_QLfol, $_QLttI);
          $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br /> table structure was changed to latin charset<br />";
          continue; // we do it next time
        }
        $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
        continue;
      }
      while($_I1O6j && $_J61tJ = mysql_fetch_assoc($_I1O6j)) {
        _LRCOC();

        // we can't check this in sql above
        if( $_J61tJ["SendScheduler"] == 'SendImmediately' || $_J61tJ["SendScheduler"] == 'SendInFutureOnce' ) {
          $_QLfol = "SELECT COUNT(id) FROM `$_J61tJ[CurrentSendTableName]` WHERE `Campaigns_id`=$_J61tJ[id] AND SendState='Done'";
          $_J6QJI = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") continue;
          $_IQjl0 = mysql_fetch_row($_J6QJI);
          if(!is_array($_IQjl0)) continue;
          if($_IQjl0[0] > 0) { // always send?
            if($_J61tJ["ReSendFlag"] < 1) {// send again?
               mysql_free_result($_J6QJI);
               continue;
            }
          }
          mysql_free_result($_J6QJI);
        }

        if($_J61tJ["SendScheduler"] == 'SendInFutureMultiple') {
          $_QLfol = "SELECT COUNT(id) FROM `$_J61tJ[CurrentSendTableName]` WHERE `Campaigns_id`=$_J61tJ[id] AND SendState='Done' AND TO_DAYS(StartSendDateTime)=TO_DAYS(NOW())";
          $_J6QJI = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") continue;
          $_IQjl0 = mysql_fetch_row($_J6QJI);
          if(!is_array($_IQjl0)) continue;
          if($_IQjl0[0] > 0) { // always send?
            mysql_free_result($_J6QJI);
            continue;
          }
          mysql_free_result($_J6QJI);
        }

        $MailingListId = $_J61tJ["maillists_id"];
        $FormId = $_J61tJ["forms_id"];
        $_QljJi = $_J61tJ["GroupsTableName"];
        $_I8I6o = $_J61tJ["MaillistTableName"];
        $_IfJ66 = $_J61tJ["MailListToGroupsTableName"];
        $_jjj8f = $_J61tJ["LocalBlocklistTableName"];

        // CurrentSendTableName
        $_jlOJO = 0;
        $_J6I01 = 0;
        $_jloJI = 0;
        $_jlLQC = 0;
        $_J6ItQ = 0;

        $_QLfol = "SELECT * ";
        $_QLfol .= "FROM `$_J61tJ[CurrentSendTableName]` WHERE `Campaigns_id`=$_J61tJ[id] AND SendState<>'Done' AND SendState<>'Paused'";

        $_J6QJI = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_J6QJI) > 0) {
          $_IQjl0 = mysql_fetch_assoc($_J6QJI);
          mysql_free_result($_J6QJI);
          if($_IQjl0["CampaignSendDone"]) // campaign always prepared completly?
            continue;
          $_jlOJO = $_IQjl0["LastMember_id"];
          $_J6I01 = $_IQjl0["id"];
          $_jloJI = $_IQjl0["RecipientsCount"];
          $_jlLQC = $_IQjl0["ReportSent"];
          $_J6IoJ = $_IQjl0["SentCountSucc"];
        } else{
          mysql_free_result($_J6QJI);

          mysql_query("BEGIN", $_QLttI);

          // CurrentSendTableName
          $_QLfol = "INSERT INTO `$_J61tJ[CurrentSendTableName]` SET `Campaigns_id`=$_J61tJ[id], StartSendDateTime=NOW(), EndSendDateTime=NOW()";
          mysql_query($_QLfol, $_QLttI);
          $_J6QJI= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
          $_IQjl0=mysql_fetch_array($_J6QJI);
          $_J6I01 = $_IQjl0[0];
          $_J61tJ["CurrentSendId"] = $_J6I01;
          mysql_free_result($_J6QJI);
          $_J6IoJ = 0;

          /*
          // Twitter Update start
          $_J0QOL = "";
          $TwitterUpdateState = "TWITTER_UPDATE_NOT_CONFIGURED";
          if($_J61tJ["TwitterUpdate"]){
             $twitter = new _JJDPE($_J61tJ["TwitterUsername"], $_J61tJ["TwitterPassword"]);

             if($OwnerUserId == 0)
               $TrackingUserId = $UserId;
               else
               $TrackingUserId = $OwnerUserId;
             $ResponderTypeNum = _LPO6C("Campaign");
             $Identifier = sprintf("%02X", $_J6I01)."_".sprintf("%02X", $TrackingUserId)."_".sprintf("%02X", $ResponderTypeNum)."_".sprintf("%02X", $_J61tJ["id"]);
             // Twitter
             $key = sprintf("twitter-%02X-%02X", $MailingListId, $FormId);

             $twitterURL = $_jfilQ."?key=$key&rid=".$Identifier;

             $Error = "";
             $twitterURL = $twitter->TwitterGetShortURL($twitterURL, $Error);
             if($twitterURL !== false) {
                 $_IO08l = $_J61tJ["MailSubject"];
                 $fields = GetAllCampaignPersonalizingFields($_I8I6o);
                 reset($fields);
                 foreach($fields as $key => $_QltJO){
                   $_IO08l = str_ireplace("[$key]", "", $_IO08l);
                 }
                 if($twitter->TwitterSendStatusMessage("$_IO08l\r\n".$twitterURL, $Error)){
                    $TwitterUpdateState = "TWITTER_UPDATE_DONE";
                  }
                   else {
                     $TwitterUpdateState = "TWITTER_UPDATE_FAILED";
                     $_J0QOL = "TWITTER_UPDATE_POSTING_FAILED";
                   }
               }
               else {
                $TwitterUpdateState = "TWITTER_UPDATE_FAILED";
                $_J0QOL = "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE";
              }

              $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET ";

              if($TwitterUpdateState == "TWITTER_UPDATE_NOT_CONFIGURED")
                 $_QLfol .= "`TwitterUpdate`='NotActivated'";
                 else
                 if($TwitterUpdateState == "TWITTER_UPDATE_DONE")
                    $_QLfol .= "`TwitterUpdate`='Done'";
                    else
                    if($TwitterUpdateState == "TWITTER_UPDATE_FAILED")
                       $_QLfol .= "`TwitterUpdate`='Failed'";
              $_QLfol .= ", `TwitterUpdateErrorText`="._LRAFO($_J0QOL);
              $_QLfol .= " WHERE id=$_J6I01";
              mysql_query($_QLfol, $_QLttI);

          }
          // Twitter Update end
          */

          // SET Current used MTAs to zero
          $_QLfol = "INSERT INTO `$_J61tJ[CurrentUsedMTAsTableName]` SELECT 0, $_J6I01, `mtas_id`, 0 FROM `$_J61tJ[MTAsTableName]` WHERE `Campaigns_id`=$_J61tJ[id] ORDER BY sortorder";
          mysql_query($_QLfol, $_QLttI);

          // Archive Table
          $_QLfol = "INSERT INTO `$_J61tJ[ArchiveTableName]` SET SendStat_id=$_J6I01, ";
          $_QLfol .= "MailFormat="._LRAFO($_J61tJ["MailFormat"]).", ";
          $_QLfol .= "MailEncoding="._LRAFO($_J61tJ["MailEncoding"]).", ";
          $_QLfol .= "MailSubject="._LRAFO($_J61tJ["MailSubject"]).", ";
          $_QLfol .= "MailPlainText="._LRAFO($_J61tJ["MailPlainText"]).", ";
          $_QLfol .= "MailHTMLText="._LRAFO($_J61tJ["MailHTMLText"]).", ";
          $_QLfol .= "Attachments="._LRAFO($_J61tJ["Attachments"]);
          mysql_query($_QLfol, $_QLttI);

          mysql_query("COMMIT", $_QLttI);

        }

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

        if($_jloJI == 0) {
          $_jloJI = _LO6LA($_J61tJ, $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
          mysql_query("UPDATE `$_J61tJ[CurrentSendTableName]` SET RecipientsCount=$_jloJI WHERE id=$_J6I01", $_QLttI);
        }

        // ECGList
        if(!isset($_jlt10))
          $_jlt10 = _JOLQE("ECGListCheck");
        // ECG List not more than 5000
        if($_jlt10){
          if($_J61tJ["MaxEMailsToProcess"] > 5000)
            $_J61tJ["MaxEMailsToProcess"] = 5000;
        }  
        // ECGList /

        $_QLfol = _LOOCQ($_J61tJ, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
        $_QLfol .= " AND `$_I8I6o`.id>$_jlOJO ORDER BY `$_I8I6o`.id LIMIT 0, $_J61tJ[MaxEMailsToProcess]";

        $_jjl0t = mysql_query($_QLfol, $_QLttI);

        $_JJjQJ = 0;
        if(mysql_num_rows($_jjl0t) > 0)
           $_JIfo0 .= "checking $_J61tJ[Name]...<br />";

        # is campaign sending done?
        if(mysql_num_rows($_jjl0t) == 0) {
          $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET EndSendDateTime=NOW(), CampaignSendDone=1 WHERE id=$_J6I01";
          mysql_query($_QLfol, $_QLttI);
        }

        // ECGList
        if($_jlt10){
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
          if($_JJjQJ >= $_J61tJ["MaxEMailsToProcess"]) break;

          if(!isset($SubjectGenerator)) // set hole Subject
            $SubjectGenerator = new SubjectGenerator($_J61tJ["MailSubject"]);
          $_J61tJ["MailSubject"] = $SubjectGenerator->_LEEPA($_J6IoJ);  

          _LRCOC();

          //ECGList
          $_J0olI = false;
          if($_jlt10){
            $_J0olI = array_search($_I8fol["u_EMail"], array_column($_J0fIj, 'email')) !== false;
          }

          mysql_query("BEGIN", $_QLttI);

          $_QLfol = "INSERT INTO `$_J61tJ[RStatisticsTableName]` SET `SendStat_id`=$_J6I01, `MailSubject`="._LRAFO(unhtmlentities($_J61tJ["MailSubject"], $_QLo06, false)).", `SendDateTime`=NOW(), `recipients_id`=$_I8fol[id], `Send`='Prepared'";
          mysql_query($_QLfol, $_QLttI);

          $_JJQ6I = 0;
          if(mysql_affected_rows($_QLttI) > 0) {
            $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
            $_JJIl0 = mysql_fetch_array($_JJQlj);
            $_JJQ6I = $_JJIl0[0];
            mysql_free_result($_JJQlj);
          } else {
            if(mysql_errno($_QLttI) == 1062) {// dup key
               $_QLfol = "SELECT `id` FROM `$_J61tJ[RStatisticsTableName]` WHERE `SendStat_id`=$_J6I01 AND `recipients_id`=$_I8fol[id] AND `Send`='Prepared'";
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

            #$_JIfo0 .= "<br />mta_id: ".$_J61tJ["mtas_id"]."<br />";

            if(!$_J0olI){
              $_QLfol = "INSERT INTO `$_IQQot` SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='Campaign', `Source_id`=$_J61tJ[id], `Additional_id`=0, `SendId`=$_J6I01, `maillists_id`=$_J61tJ[MailingListId], `recipients_id`=$_I8fol[id], `mtas_id`=$_J61tJ[mtas_id], `LastSending`=NOW(), `MailSubject`=" . _LRAFO(unhtmlentities($_J61tJ["MailSubject"], $_QLo06, false));
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
            }
            $_J6IoJ++;
            $_JJjQJ++;
          }

          //$_JIfo0 .= "Email with subject '$_J61tJ[MailSubject]' was queued for sending to '$_I8fol[u_EMail]'<br />";

          # update last member id
          $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET EndSendDateTime=NOW(), LastMember_id=$_I8fol[id], `SentCountSucc`=$_J6IoJ WHERE id=$_J6I01";
          mysql_query($_QLfol, $_QLttI);

          mysql_query("COMMIT", $_QLttI);

        } # while($_I8fol = mysql_fetch_array($_jjl0t))
        if($_jjl0t)
          mysql_free_result($_jjl0t);
        if(isset($SubjectGenerator))
          unset($SubjectGenerator);

        if($_JJjQJ > 0) {
           $_JIfo0 .= "$_J61tJ[Name] checked, $_JJjQJ email(s) sent to queue.<br />";
        }

      } # while($_J61tJ = mysql_fetch_assoc($_I1O6j))
      if($_I1O6j)
        mysql_free_result($_I1O6j);
    } # while($_QLO0f = mysql_fetch_assoc($_QL8i1) )
    mysql_free_result($_QL8i1);


    $_JIfo0 .= "<br />$_J0J6C emails sent to queue<br />";
    $_JIfo0 .= "<br />EMailings checking end.";

    if($_J0J6C)
      return true;
      else
      return -1;
  }

  function _LL6R6($_J61tJ, &$_JIfo0){
    global $_QLttI, $UserId, $_IQQot, $_QLi60, $_Ijt0i;
    global $commonmsgHTMLMTANotFound;
    $_QLfol = "SELECT * ";
    $_QLfol .= "FROM `$_J61tJ[CurrentSendTableName]` WHERE `Campaigns_id`=$_J61tJ[id] AND SendState<>'Done' AND CampaignSendDone<>0";

    $_J6QJI = mysql_query($_QLfol, $_QLttI);
    while($_J6QJI && $_IQjl0 = mysql_fetch_assoc($_J6QJI) ) {
       _LRCOC();

       if( (isset($_J61tJ["SendScheduler"]) && $_J61tJ["SendScheduler"] == 'SendManually') && $_IQjl0["SendState"] != 'ReSending' ) {
         continue;
       }

       # anything of campaign in outqueue?
       $_QLfol = "SELECT COUNT(id) FROM `$_IQQot` WHERE `users_id`=$UserId AND `Source`='Campaign' AND `Source_id`=$_J61tJ[id] AND `Additional_id`=0 AND `SendId`=$_IQjl0[id]";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       if($_jj6L6[0] > 0) continue; // not done?

       _LBOOC($_J61tJ["MailingListId"], $UserId, 0, 'Campaign', $_J61tJ["id"]);

       // Update ReSendFlag
       $_QLfol = "UPDATE `$_QLi60` SET `ReSendFlag`=0 WHERE id=$_J61tJ[id]";
       mysql_query($_QLfol, $_QLttI);

       // Count things
       $_QLfol = "SELECT COUNT(id) FROM `$_J61tJ[RStatisticsTableName]` WHERE SendStat_id=$_IQjl0[id] AND Send='Sent'";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       $_jlolf = $_jj6L6[0];

       $_QLfol = "SELECT COUNT(id) FROM `$_J61tJ[RStatisticsTableName]` WHERE SendStat_id=$_IQjl0[id] AND Send='Failed'";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       $_jlCtf = $_jj6L6[0];

       $_QLfol = "SELECT COUNT(id) FROM `$_J61tJ[RStatisticsTableName]` WHERE SendStat_id=$_IQjl0[id] AND Send='PossiblySent'";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       $_jliJi = $_jj6L6[0];

       $_QLfol = "SELECT COUNT(id) FROM `$_J61tJ[RStatisticsTableName]` WHERE SendStat_id=$_IQjl0[id] AND Send='Failed' And `SendResult` LIKE '%permanently undeliverable%'";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       $_J6joC = $_jj6L6[0];

       // when resend from campaignlog than EndSendDateTime <> 0
       $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET EndSendDateTime=NOW() WHERE id=$_IQjl0[id] AND EndSendDateTime=0";
       mysql_query($_QLfol, $_QLttI);

       if(mysql_affected_rows($_QLttI)){
         $_QLfol = "UPDATE `$_QLi60` SET `LastSentDateTime`=NOW() WHERE `id`=$_J61tJ[id]";
         mysql_query($_QLfol, $_QLttI);
       }

       $_QLfol = "UPDATE `$_J61tJ[CurrentSendTableName]` SET SentCountSucc=$_jlolf, SentCountFailed=$_jlCtf, SentCountPossiblySent=$_jliJi, HardBouncesCount=$_J6joC, SendState='Done', ReportSent=1 WHERE id=$_IQjl0[id]";
       mysql_query($_QLfol, $_QLttI);

       $_J61tJ["ReportSent"] = $_IQjl0["ReportSent"];

       if( $_J61tJ["SendReportToYourSelf"] || $_J61tJ["SendReportToListAdmin"] || $_J61tJ["SendReportToMailingListUsers"] || $_J61tJ["SendReportToEMailAddress"] ) {

         // MTA
         if(!isset($_J61tJ["mtas_id"])) {
           $_QLfol = "SELECT mtas_id FROM `$_J61tJ[MTAsTableName]` WHERE `Campaigns_id`=$_J61tJ[id] ORDER BY sortorder LIMIT 0, 1"; // only the first
           $_J6j0L = mysql_query($_QLfol, $_QLttI);
           if(!$_J6j0L || mysql_num_rows($_J6j0L) == 0) {
             $_JIfo0 .= $commonmsgHTMLMTANotFound;
             continue;
           } else {
             $_J00C0 = mysql_fetch_assoc($_J6j0L);
             mysql_free_result($_J6j0L);
             $_J61tJ["mtas_id"] = $_J00C0["mtas_id"];
           }
         }

         $_QLfol = "SELECT * FROM `$_Ijt0i` WHERE id=$_J61tJ[mtas_id]";
         $_jjJfo = mysql_query($_QLfol, $_QLttI);
         if($_jjJfo && mysql_num_rows($_jjJfo) > 0) {
           $_J00C0 = mysql_fetch_assoc($_jjJfo);
           mysql_free_result($_jjJfo);

          // merge text with mail send settings
          if(isset($_J00C0["id"])) {
            unset($_J00C0["id"]);
            unset($_J00C0["CreateDate"]);
            unset($_J00C0["IsDefault"]);
            unset($_J00C0["Name"]);
          }
          $_J61tJ = array_merge($_J61tJ, $_J00C0);
          // send report
          _LLR0D($_J61tJ, $_IQjl0["id"]);
         }

       } # if( $_J61tJ["SendReportToYourSelf"] || $_J61tJ["SendReportToListAdmin"] || $_J61tJ["SendReportToMailingListUsers"] || $_J61tJ["SendReportToEMailAddress"] )


    } # while( $_IQjl0 = mysql_fetch_assoc($_J6QJI) )
    if($_J6QJI)
      mysql_free_result($_J6QJI);
  }


  function _LLR0D($_jf6Qi, $_j01OI) {
      global $_QLttI, $_I18lo, $_QlQot, $_JQ1I6, $resourcestrings, $INTERFACE_LANGUAGE, $UserId;
      global $_QLl1Q, $_QLo06, $_J1t6J;
      if(!$_jf6Qi["ReportSent"]) {


        $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
        $_j01CJ = "'%d.%m.%Y'";
        if($INTERFACE_LANGUAGE != "de") {
           $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
           $_j01CJ = "'%Y-%m-%d'";
        }

        // ************** creating report *********
        $_J6jCt = array();

        if(!empty($_jf6Qi["MTASenderEMailAddress"]))
           $From = array("address" => $_jf6Qi["MTASenderEMailAddress"], "name" => "");
           else{
             // sender address
             $_QLfol = "SELECT id, EMail, FirstName, LastName FROM $_I18lo WHERE id=$UserId";
             $_J6JOo = mysql_query($_QLfol, $_QLttI);
             $_Il6l0=mysql_fetch_assoc($_J6JOo);
             $From = array("address" => $_Il6l0["EMail"], "name" => trim($_Il6l0["FirstName"]." ".$_Il6l0["LastName"]));
             mysql_free_result($_J6JOo);

             $_QLfol = "SELECT id, EMail, FirstName, LastName FROM $_I18lo WHERE id=$_jf6Qi[Creator_users_id]";
             $_J6JOo = mysql_query($_QLfol, $_QLttI);
             if(mysql_num_rows($_J6JOo) > 0) {
               $_Il6l0=mysql_fetch_assoc($_J6JOo);
               $From = array("address" => $_Il6l0["EMail"], "name" => trim($_Il6l0["FirstName"]." ".$_Il6l0["LastName"]));
               mysql_free_result($_J6JOo);
             } else {
               $_QLfol = "SELECT id, EMail, FirstName, LastName FROM $_I18lo WHERE id=$_jf6Qi[users_id]";
               $_J6JOo = mysql_query($_QLfol, $_QLttI);
               if(mysql_num_rows($_J6JOo) > 0){
                 $_Il6l0=mysql_fetch_assoc($_J6JOo);
                 $From = array("address" => $_Il6l0["EMail"], "name" => trim($_Il6l0["FirstName"]." ".$_Il6l0["LastName"]));
                 mysql_free_result($_J6JOo);
               }
             }

           }

        if($_jf6Qi["SendReportToEMailAddress"] && !empty($_jf6Qi["SendReportToEMailAddressEMailAddress"]))
          $_J6jCt[0] = array("address" => $_jf6Qi["SendReportToEMailAddressEMailAddress"], "name" => "");

        $_jf8JI = array();
        if($_jf6Qi["SendReportToYourSelf"])
          $_jf8JI[] = "id=$_jf6Qi[Creator_users_id]";
        if($_jf6Qi["SendReportToListAdmin"])
          $_jf8JI[] = "id=$_jf6Qi[users_id]";
        if($_jf6Qi["SendReportToMailingListUsers"]) {
          $_QLfol = "SELECT users_id FROM $_QlQot WHERE maillists_id=$_jf6Qi[maillists_id]";
          $_J6JOo = mysql_query($_QLfol, $_QLttI);
          while($_Il6l0=mysql_fetch_assoc($_J6JOo))
             $_jf8JI[] = "id=$_Il6l0[users_id]";
          mysql_free_result($_J6JOo);
        }

        if(count($_jf8JI) > 0) {
          $_QLfol = "SELECT id, EMail, FirstName, LastName FROM $_I18lo WHERE ".join(" OR ", $_jf8JI);
          $_J6JOo = mysql_query($_QLfol, $_QLttI);
          while($_Il6l0=mysql_fetch_assoc($_J6JOo)) {
             if( !array_key_exists($_Il6l0["id"], $_J6jCt) )
                $_J6jCt[$_Il6l0["id"]] = array("address" => $_Il6l0["EMail"], "name" => trim($_Il6l0["FirstName"]." ".$_Il6l0["LastName"]));
          }
          mysql_free_result($_J6JOo);

        } // if(count($_jf8JI) > 0)

        if(count($_J6jCt) > 0) {

          // mail class
          $_j10IJ = new _LEFO8(mtInternalReportMail);

          $_j10IJ->_LEOPF();
          $_j10IJ->_LEQ1C();
          $_j10IJ->From[] = array("address" => $From["address"], "name" => $From["name"] );
          foreach($_J6jCt as $key => $_QltJO)
             $_j10IJ->To[] = array("address" => $_J6jCt[$key]["address"], "name" =>  $_J6jCt[$key]["name"]);

          _LRCOC();
          $_I1OoI = @file(_LOC8P()."campaign_email_report.htm");
          if($_I1OoI)
            $_QLoli = join("", $_I1OoI);
            else
            $_QLoli = join("", file(InstallPath._LOC8P()."campaign_email_report.htm"));

          $_J6616 = join("", @file(InstallPath."css/default.css"));
          if(!empty($_J6616)){
           $_J6616 = str_replace('../images/', ScriptBaseURL."images/", $_J6616);
           $_J6616 = '<style type="text/css">'.$_QLl1Q.$_J6616.$_QLl1Q.'</style>';
           $_QLoli = str_replace("</head>", $_J6616.$_QLl1Q."</head>", $_QLoli);
          }

          $_I1OoI = @file(_LOC8P()."campaign_email_report.txt");
          if($_I1OoI)
            $_IO08l = join($_QLl1Q, $_I1OoI);
            else
            $_IO08l = join($_QLl1Q, file(InstallPath._LOC8P()."campaign_email_report.txt"));
          $_IO08l = utf8_encode($_IO08l); // ANSI2UTF8

          $_j10IJ->Subject = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000653"], $_jf6Qi["CampaignsName"] );
          _JJCCF($_QLoli);
          _JJCCF($_IO08l);

          $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, ";
          $_QLfol .= "DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated, ";
          $_QLfol .= "SEC_TO_TIME( UNIX_TIMESTAMP(EndSendDateTime) - UNIX_TIMESTAMP(StartSendDateTime) ) AS SendDuration, ";
          $_QLfol .= "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL RecipientsCount * ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(StartSendDateTime) ) / (SentCountSucc+SentCountFailed) SECOND), $_QLo60) AS SendEstEndTime ";
          $_QLfol .= "FROM `$_jf6Qi[CurrentSendTableName]` WHERE id=$_j01OI";
          $_J66fJ = mysql_query($_QLfol, $_QLttI);
          if(mysql_num_rows($_J66fJ) > 0) {
            $_QLO0f = mysql_fetch_assoc($_J66fJ);
            $_jlOJO = $_QLO0f["LastMember_id"];
            $_J6I01 = $_QLO0f["id"];
            $_jloJI = $_QLO0f["RecipientsCount"];
            $_jlLL0 = $_QLO0f["StartSendDateTimeFormated"];
            $_jlLLO = $_QLO0f["EndSendDateTimeFormated"];
            $_jll1i = $_QLO0f["SendDuration"];
            $_jllQj = $_QLO0f["SendEstEndTime"];
            $_jlolf = $_QLO0f["SentCountSucc"];
            $_jlCtf = $_QLO0f["SentCountFailed"];
            $_jliJi = $_QLO0f["SentCountPossiblySent"];
            $_J66il = $_QLO0f["HardBouncesCount"];
            $_jLjOt = $_QLO0f["TwitterUpdate"];
            $_J0QOL = $_QLO0f["TwitterUpdateErrorText"];
            mysql_free_result($_J66fJ);
          }

          $_QLoli = str_replace('href="css/', 'href="'.ScriptBaseURL.'css/', $_QLoli);

          // html
          $_QLoli = _L81BJ($_QLoli, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", $_jf6Qi["CampaignsName"] );
          $_QLoli = _L81BJ($_QLoli, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jloJI);
          $_QLoli = _L81BJ($_QLoli, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jlolf);
          $_QLoli = _L81BJ($_QLoli, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jlCtf);
          $_QLoli = _L81BJ($_QLoli, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jliJi);
          $_QLoli = _L81BJ($_QLoli, "<SENTCOUNTBOUNCECOUNT>", "</SENTCOUNTBOUNCECOUNT>", $_J66il);

          $_QLoli = _L81BJ($_QLoli, "<SENDING:START>", "</SENDING:START>", $_jlLL0);
          $_QLoli = _L81BJ($_QLoli, "<SENDING:END>", "</SENDING:END>", $_jlLLO);
          $_QLoli = _L81BJ($_QLoli, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jll1i);
          $_QLoli = _L81BJ($_QLoli, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jllQj);

          if($_jLjOt == "NotActivated" || empty($_jLjOt))
             $_QLoli = _L81BJ($_QLoli, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
          if($_jLjOt == "Done")
             $_QLoli = _L81BJ($_QLoli, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
          if($_jLjOt == "Failed")
             if( $_J0QOL == "TWITTER_UPDATE_POSTING_FAILED" )
                $_QLoli = _L81BJ($_QLoli, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
                else
                if( $_J0QOL == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
                    $_QLoli = _L81BJ($_QLoli, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);

          // text
          $_IO08l = _L81BJ($_IO08l, "<CAMPAIGN_NAME>", "</CAMPAIGN_NAME>", unhtmlentities($_jf6Qi["CampaignsName"], $_QLo06) );
          $_IO08l = _L81BJ($_IO08l, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jloJI);
          $_IO08l = _L81BJ($_IO08l, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jlolf);
          $_IO08l = _L81BJ($_IO08l, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jlCtf);
          $_IO08l = _L81BJ($_IO08l, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jliJi);
          $_IO08l = _L81BJ($_IO08l, "<SENTCOUNTBOUNCECOUNT>", "</SENTCOUNTBOUNCECOUNT>", $_J66il);

          $_IO08l = _L81BJ($_IO08l, "<SENDING:START>", "</SENDING:START>", $_jlLL0);
          $_IO08l = _L81BJ($_IO08l, "<SENDING:END>", "</SENDING:END>", $_jlLLO);
          $_IO08l = _L81BJ($_IO08l, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jll1i);
          $_IO08l = _L81BJ($_IO08l, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jllQj);

          if($_jLjOt == "NotActivated")
             $_IO08l = _L81BJ($_IO08l, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001300"]);
          if($_jLjOt == "Done")
             $_IO08l = _L81BJ($_IO08l, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001301"]);
          if($_jLjOt == "Failed")
             if( $_J0QOL == "TWITTER_UPDATE_POSTING_FAILED" )
                $_IO08l = _L81BJ($_IO08l, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001302"]);
                else
                if( $_J0QOL == "TWITTER_UPDATE_NO_CONNECTION_TO_SHORT_URL_SERVICE" )
                    $_IO08l = _L81BJ($_IO08l, "<SENDING:TWITTER_UPDATE>", "</SENDING:TWITTER_UPDATE>", $resourcestrings[$INTERFACE_LANGUAGE]["001303"]);

          $_j10IJ->TextPart = $_IO08l;
          $_j10IJ->HTMLPart = $_QLoli;
          $_j10IJ->Priority = mpNormal;

          $_QLfol = "SELECT * FROM $_JQ1I6";
          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          $_I1OfI = mysql_fetch_assoc($_I1O6j);
          mysql_free_result($_I1O6j);
          $_j10IJ->crlf = $_I1OfI["CRLF"];
          $_j10IJ->head_encoding = $_I1OfI["Head_Encoding"];
          $_j10IJ->text_encoding = $_I1OfI["Text_Encoding"];
          $_j10IJ->html_encoding = $_I1OfI["HTML_Encoding"];
          $_j10IJ->attachment_encoding = $_I1OfI["Attachment_Encoding"];
          $_j10IJ->XMailer = $_I1OfI["XMailer"];

          $_j10IJ->charset = $_QLo06;

          $_j10IJ->Sendvariant = $_jf6Qi["Type"]; // mail, sendmail, smtp, smtpmx, text

          $_j10IJ->PHPMailParams = $_jf6Qi["PHPMailParams"];
          $_j10IJ->HELOName = $_jf6Qi["HELOName"];

          $_j10IJ->SMTPpersist = (bool)$_jf6Qi["SMTPPersist"];
          $_j10IJ->SMTPpipelining = (bool)$_jf6Qi["SMTPPipelining"];
          $_j10IJ->SMTPTimeout = $_jf6Qi["SMTPTimeout"];
          $_j10IJ->SMTPServer = $_jf6Qi["SMTPServer"];
          $_j10IJ->SMTPPort = $_jf6Qi["SMTPPort"];
          $_j10IJ->SMTPAuth = (bool)$_jf6Qi["SMTPAuth"];
          $_j10IJ->SMTPUsername = $_jf6Qi["SMTPUsername"];
          $_j10IJ->SMTPPassword = $_jf6Qi["SMTPPassword"];
          if(isset($_jf6Qi["SMTPSSL"]))
            $_j10IJ->SSLConnection = (bool)$_jf6Qi["SMTPSSL"];

          $_j10IJ->sendmail_path = $_jf6Qi["sendmail_path"];
          $_j10IJ->sendmail_args = $_jf6Qi["sendmail_args"];

          if($_j10IJ->Sendvariant == "savetodir"){
            $_j10IJ->savetodir_filepathandname = _LBQFJ($_jf6Qi["savetodir_pathname"]);
          }

          $_j10IJ->SignMail = (bool)$_jf6Qi["SMIMESignMail"];
          $_j10IJ->SMIMEMessageAsPlainText = (bool)$_jf6Qi["SMIMEMessageAsPlainText"];

          $_j10IJ->SignCert = $_jf6Qi["SMIMESignCert"];
          $_j10IJ->SignPrivKey = $_jf6Qi["SMIMESignPrivKey"];
          $_j10IJ->SignPrivKeyPassword = $_jf6Qi["SMIMESignPrivKeyPassword"];
          $_j10IJ->SignTempFolder = $_J1t6J;
          $_j10IJ->SignExtraCerts = $_jf6Qi["SMIMESignExtraCerts"];

          $_j10IJ->SMIMEIgnoreSignErrors = (bool)$_jf6Qi["SMIMEIgnoreSignErrors"];

          $_j10IJ->DKIM = (bool)$_jf6Qi["DKIM"];
          $_j10IJ->DomainKey = (bool)$_jf6Qi["DomainKey"];
          $_j10IJ->DKIMSelector = $_jf6Qi["DKIMSelector"];
          $_j10IJ->DKIMPrivKey = $_jf6Qi["DKIMPrivKey"];
          $_j10IJ->DKIMPrivKeyPassword = $_jf6Qi["DKIMPrivKeyPassword"];
          $_j10IJ->DKIMIgnoreSignErrors = (bool)$_jf6Qi["DKIMIgnoreSignErrors"];


          $_j108i = "";
          $_j10O1 = "";
          _LRCOC();
          if($_j10IJ->_LEJE8($_j108i, $_j10O1)) {
            _LRCOC();
            $_j10IJ->_LE6A8($_j108i, $_j10O1);
          } else {
            // ignore errors here
          }


        } // if(count($_J6jCt) > 0)

        // ************** creating report  / *********
      } // if(!$_jf6Qi["ReportSent"])
  }


/*
function GetAllCampaignPersonalizingFields($_I8I6o){
  global $AllDefaultPlaceholders;
  $fieldnames = array();
  _L8FPJ($_I8I6o, $fieldnames, array());
  $fieldnames = array_merge($AllDefaultPlaceholders, $fieldnames);
  reset($fieldnames);
  foreach($fieldnames as $key => $_QltJO) {
    $MembersRecord[$key] = "";
  }
  return $MembersRecord;
}
*/

?>
