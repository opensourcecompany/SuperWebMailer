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
  include_once("mailcreate.inc.php");
  include_once("rssparser.php");

  function _OR8BL(&$_j6O8O) {
    global $_Q61I1, $_Q6JJJ;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Qo8OO, $resourcestrings, $_Q8f1L, $_Q880O;
    global $_QolLi, $_Qofoi, $_Ql8C0, $_Q60QL, $_QtjLI;
    global $_Q6jOo, $_IoOLJ, $_ICjCO;

    $_j6O8O = "RSS2EMail responder checking starts...<br />";
    $_jIojl = 0;
    $_jtiit = false;

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


      $_QJlJ0 = "SELECT $_IoOLJ.MailSubject, $_IoOLJ.id AS RSS2EMailResponders_id, ";
      $_QJlJ0 .= "$_IoOLJ.Name AS RSS2EMailResponders_Name, $_IoOLJ.ML_RM_RefTableName, ";
      $_QJlJ0 .= "$_IoOLJ.MaxEMailsToProcess, $_IoOLJ.RSSFeedURL, $_IoOLJ.RSSFeedMaxEntriesToRetrieve, ";
      $_QJlJ0 .= "$_IoOLJ.LastRSSFeedContentProcessed, $_IoOLJ.LastRSSFeedContentRetrieved, $_IoOLJ.LastRSSFeedContent, ";
      $_QJlJ0 .= "$_Qofoi.id AS MTA_id, $_Q60QL.MaillistTableName, $_Q60QL.LocalBlocklistTableName, ";
      $_QJlJ0 .= "$_Q60QL.id AS MailingListId, $_IoOLJ.forms_id FROM $_IoOLJ ";
      $_QJlJ0 .= "LEFT JOIN $_Qofoi ON $_Qofoi.id=$_IoOLJ.mtas_id LEFT JOIN $_Q60QL ON ";
      $_QJlJ0 .= "$_Q60QL.id=$_IoOLJ.maillists_id WHERE ($_IoOLJ.IsActive=1 AND ";
      $_QJlJ0 .= " ( (CURRENT_TIME() > $_IoOLJ.SendTime ) OR $_IoOLJ.LastRSSFeedContentProcessed = 0) )";

      $_QJlJ0 .= " AND ( ";

      $_QJlJ0 .= " ( $_IoOLJ.SendScheduler = 'SendOnNewPosts' ) ";

      $_QJlJ0 .= " OR ";

      $_QJlJ0 .= " ( $_IoOLJ.SendScheduler = 'SendAtDay' AND IF(LastRSSFeedContentRetrieved <> '0000-00-00 00:00:00', TO_DAYS(NOW()) <> TO_DAYS(LastRSSFeedContentRetrieved), 1) AND ($_IoOLJ.SendDayNames='every day' OR $_IoOLJ.SendDayNames= CAST(WEEKDAY(NOW()) AS CHAR) ) ) ";

      $_QJlJ0 .= " OR ";

      $_QJlJ0 .= " ( $_IoOLJ.SendScheduler = 'SendMonthDay' AND IF(LastRSSFeedContentRetrieved <> '0000-00-00 00:00:00', TO_DAYS(NOW()) <> TO_DAYS(LastRSSFeedContentRetrieved), 1) AND CAST(DAYOFMONTH(NOW()) AS CHAR) = $_IoOLJ.SendMonthDay ) ";

      $_QJlJ0 .= " OR ";

      $_QJlJ0 .= " ( $_IoOLJ.LastRSSFeedContentProcessed = 0 ) "; // not completly done

      $_QJlJ0 .= " )";

      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      while($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
        _OPQ6J();

        $_j6O8O .= "checking $_Q8OiJ[RSS2EMailResponders_Name]...<br />";
        $_jflIQ = 0;

        if($_Q8OiJ["LastRSSFeedContentProcessed"] != 0) {
          $_jtLtO = new _LQPCJ();
          if(!$_jtLtO->XMLAvailable){
            $_j6O8O .= "No XML parser activated.";
            $_jtLtO = null;
            $_jtiit = true;
            continue;
          }
          $_jtLtO->MaxEntries = $_Q8OiJ["RSSFeedMaxEntriesToRetrieve"];
          if(!$_jtLtO->_LQPEA($_Q8OiJ["RSSFeedURL"])) {
            $_j6O8O .= "Error while parsing RSS feed: ".$_jtLtO->RSSParseError;
            $_jtLtO = null;
            $_jtiit = true;
            continue;
          }
          $_jtl8I = array();
          $_jtl8I = base64_encode(serialize($_jtLtO->channel));
          $_QJlJ0 = "UPDATE $_IoOLJ SET LastRSSFeedContentProcessed=0, LastRSSFeedContentRetrieved=NOW(), LastRSSFeedContent="._OPQLR($_jtl8I)." WHERE id=$_Q8OiJ[RSS2EMailResponders_id] AND (LastRSSFeedContent IS NULL OR LastRSSFeedContent<>"._OPQLR($_jtl8I).")";
          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) == 0){
            $_jtLtO = null;
            $_j6O8O .= "No new entries found in feed $_Q8OiJ[RSSFeedURL] ".mysql_error($_Q61I1);
            continue;
          }
          $_QJlJ0 = "SELECT LastRSSFeedContentRetrieved, LastRSSFeedContent FROM $_IoOLJ WHERE id=$_Q8OiJ[RSS2EMailResponders_id]";
          $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);
          $_QlftL = mysql_fetch_assoc($_IOOt1);
          mysql_free_result($_IOOt1);
          $_Q8OiJ["LastRSSFeedContentRetrieved"] = $_QlftL["LastRSSFeedContentRetrieved"];
          $_jtl8J = $_jtLtO->channel;
          $_jtLtO = null;
        } else {
          $_jtl8J = @unserialize(base64_decode($_Q8OiJ["LastRSSFeedContent"]));
          if($_jtl8J === false)
             $_jtl8J = array();
        }


        $_QlQC8 = $_Q8OiJ["MaillistTableName"];
        $_ItCCo = $_Q8OiJ["LocalBlocklistTableName"];
        $_IO1Oj = " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
        $_IO1Oj .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
        $_IOQf6 = " `$_QlQC8`.IsActive=1 AND `$_QlQC8`.SubscriptionStatus<>'OptInConfirmationPending'".$_Q6JJJ;
        $_IOQf6 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ".$_Q6JJJ;

        $_QJlJ0 = "SELECT `$_Q8OiJ[MaillistTableName]`.id, `$_Q8OiJ[MaillistTableName]`.u_EMail, ";
        $_QJlJ0 .= " `$_Q8OiJ[ML_RM_RefTableName]`.`RSS_GUIDs` FROM `$_Q8OiJ[MaillistTableName]`";
        $_QJlJ0 .= " LEFT JOIN `$_Q8OiJ[ML_RM_RefTableName]` ON `$_Q8OiJ[ML_RM_RefTableName]`.Member_id=`$_Q8OiJ[MaillistTableName]`.id";
        $_QJlJ0 .= " $_IO1Oj ";
        $_QJlJ0 .= " WHERE $_IOQf6 ";
        $_QJlJ0 .= " AND IF(`$_Q8OiJ[ML_RM_RefTableName]`.`LastRSSFeedChecking` IS NOT NULL, `$_Q8OiJ[ML_RM_RefTableName]`.`LastRSSFeedChecking` <> "._OPQLR($_Q8OiJ["LastRSSFeedContentRetrieved"]).", 1)";

        $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);

        if(mysql_error($_Q61I1) != "")
           $_j6O8O .= "MySQL error while selecting data: ".mysql_error($_Q61I1);

        while($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {

          // limit reached?
          if($_jflIQ >= $_Q8OiJ["MaxEMailsToProcess"]) break;

          _OPQ6J();

          mysql_query("BEGIN", $_Q61I1);

          $_QlftL["RSS_GUIDs"] = @unserialize($_QlftL["RSS_GUIDs"]);
          if($_QlftL["RSS_GUIDs"] === false)
             $_QlftL["RSS_GUIDs"] = array();
          $_QlftL["New_RSS_Entries"] = array();

          if(isset($_jtl8J["ITEMS"])) {
            // find new GUIDs
            for($_Q6llo=0; $_Q6llo<count($_jtl8J["ITEMS"]); $_Q6llo++){
              if( !empty( $_jtl8J["ITEMS"][$_Q6llo]["GUID"] ) ){
                if(!in_array($_jtl8J["ITEMS"][$_Q6llo]["GUID"], $_QlftL["RSS_GUIDs"])) {
                   $_QlftL["RSS_GUIDs"][] = $_jtl8J["ITEMS"][$_Q6llo]["GUID"];
                   $_QlftL["New_RSS_Entries"][] = $_jtl8J["ITEMS"][$_Q6llo]["GUID"];
                }
              }
            }

            // remove old not existing GUIDs
            foreach($_QlftL["RSS_GUIDs"] as $key => $_Q6ClO) {
              $_Qo1oC = false;
              for($_Q6llo=0; $_Q6llo<count($_jtl8J["ITEMS"]); $_Q6llo++){
                if( !empty( $_jtl8J["ITEMS"][$_Q6llo]["GUID"] ) ){
                   if($_jtl8J["ITEMS"][$_Q6llo]["GUID"] == $_Q6ClO) {
                     $_Qo1oC = true;
                     break;
                   }
                }
              }
              if(!$_Qo1oC)
                unset($_QlftL["RSS_GUIDs"][$key]);
            }

          }

          # no new entries?
          if(count($_QlftL["New_RSS_Entries"]) == 0){
            $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _OPQLR($_Q8OiJ["LastRSSFeedContentRetrieved"]) . " WHERE `Member_id`=$_QlftL[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_affected_rows($_Q61I1) == 0) { // new?
              $_QJlJ0 = "INSERT INTO `$_Q8OiJ[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _OPQLR($_Q8OiJ["LastRSSFeedContentRetrieved"]) . ", `Member_id`=$_QlftL[id]";
              mysql_query($_QJlJ0, $_Q61I1);
            }
            mysql_query("COMMIT", $_Q61I1);
            continue;
          }

          $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _OPQLR($_Q8OiJ["LastRSSFeedContentRetrieved"]) . ", New_RSS_Entries="._OPQLR(serialize($_QlftL["New_RSS_Entries"])).", RSS_GUIDs="._OPQLR(serialize($_QlftL["RSS_GUIDs"]))." WHERE `Member_id`=$_QlftL[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) == 0) { // new?
            $_QJlJ0 = "INSERT INTO `$_Q8OiJ[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _OPQLR($_Q8OiJ["LastRSSFeedContentRetrieved"]) . ", New_RSS_Entries="._OPQLR(serialize($_QlftL["New_RSS_Entries"])).", RSS_GUIDs="._OPQLR(serialize($_QlftL["RSS_GUIDs"])).", `Member_id`=$_QlftL[id]";
            mysql_query($_QJlJ0, $_Q61I1);
          }

          _OPQ6J();

          $_jfiol = 0;

          $_QJlJ0 = "INSERT INTO `$_ICjCO` SET `rss2emailresponders_id`=$_Q8OiJ[RSS2EMailResponders_id], `MailSubject`="._OPQLR($_Q8OiJ["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Prepared'";
          mysql_query($_QJlJ0, $_Q61I1);

          if(mysql_affected_rows($_Q61I1) > 0) {
            $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
            $_jfl1j=mysql_fetch_array($_jfLII);
            $_jfiol = $_jfl1j[0];
            mysql_free_result($_jfLII);
          } else {
            if(mysql_errno($_Q61I1) == 1062) continue; // dup key, but no unique index set therefore it will never occur
            $_j6O8O .= "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
            mysql_query("ROLLBACK", $_Q61I1);
            return false;
          }

          # Mail?
          if($_jfiol != 0) {
            // SendId = RSS2EMail Id
            $_QJlJ0 = "INSERT INTO $_QtjLI SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='RSS2EMailResponder', `Source_id`=$_Q8OiJ[RSS2EMailResponders_id], `Additional_id`=0, `SendId`=$_Q8OiJ[RSS2EMailResponders_id], `maillists_id`=$_Q8OiJ[MailingListId], `recipients_id`=$_QlftL[id], `mtas_id`=$_Q8OiJ[MTA_id], `LastSending`=NOW() ";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_error($_Q61I1) != "") {
              $_j6O8O .= "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
              $_jtiit = true;
              mysql_query("ROLLBACK", $_Q61I1);
              continue;
            }
          }


          $_jIojl++;
          $_jflIQ++;

          $_j6O8O .= "Email with subject '$_Q8OiJ[MailSubject]' was queued for sending to '$_QlftL[u_EMail]'<br />";

          // save email send date time
          $_QJlJ0 = "UPDATE `$_Q8OiJ[ML_RM_RefTableName]` SET LastSending=NOW() WHERE Member_id=$_QlftL[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) == 0) { // new?
            $_QJlJ0 = "INSERT INTO `$_Q8OiJ[ML_RM_RefTableName]` SET Member_id=$_QlftL[id], LastSending=NOW()";
            mysql_query($_QJlJ0, $_Q61I1);
          }

          // Update RSS2EMail responder statistics
          $_QJlJ0 = "UPDATE $_IoOLJ SET EMailsSent=EMailsSent+1 WHERE id=$_Q8OiJ[RSS2EMailResponders_id]";
          mysql_query($_QJlJ0, $_Q61I1);

          mysql_query("COMMIT", $_Q61I1);
        }
        if($_IOOt1)
          mysql_free_result($_IOOt1);


        if($_jflIQ == 0){
          $_QJlJ0 = "UPDATE $_IoOLJ SET LastRSSFeedContentProcessed=1 WHERE id=$_Q8OiJ[RSS2EMailResponders_id]";
          mysql_query($_QJlJ0, $_Q61I1);
        }


      }
      if($_Q8Oj8)
        mysql_free_result($_Q8Oj8);
    }
    mysql_free_result($_Q60l1);


    $_j6O8O .= "<br />$_jIojl emails sent to queue<br />";
    $_j6O8O .= "<br />RSS2EMail responder checking end.";

    if($_jIojl)
      return true;
      if($_jtiit)
      return false;
       else
        return -1;
  }

?>
