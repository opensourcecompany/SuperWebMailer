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
  include_once("mailcreate.inc.php");
  include_once("rssparser.php");
  include_once("rss2emailrespondertools.inc.php");

  function _LLD0O(&$_JIfo0) {
    global $_QLttI, $_QLl1Q, $_QLo06;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Ijt8j, $resourcestrings, $_I18lo, $_I1tQf;
    global $_IjljI, $_Ijt0i, $_I8tfQ, $_QL88I, $_IQQot;
    global $_QLi60, $_jJLQo, $_j68Co;

    $_JIfo0 = "RSS2EMail responder checking starts...<br />";
    $_J0J6C = 0;
    $_JfOIL = false;

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


      $_QLfol = "SELECT $_jJLQo.MailSubject, $_jJLQo.id AS RSS2EMailResponders_id, ";
      $_QLfol .= "$_jJLQo.Name AS RSS2EMailResponders_Name, $_jJLQo.ML_RM_RefTableName, ";
      $_QLfol .= "$_jJLQo.`GroupsTableName`, $_jJLQo.`NotInGroupsTableName`, ";
      $_QLfol .= "$_jJLQo.MaxEMailsToProcess, $_jJLQo.RSSFeedURL, $_jJLQo.RSSFeedMaxEntriesToRetrieve, ";
      $_QLfol .= "$_jJLQo.LastRSSFeedContentProcessed, $_jJLQo.LastRSSFeedContentRetrieved, $_jJLQo.LastRSSFeedContent, ";
      $_QLfol .= "$_Ijt0i.id AS MTA_id, $_QL88I.MaillistTableName, $_QL88I.LocalBlocklistTableName, ";
      $_QLfol .= "$_QL88I.`GroupsTableName` AS MailListsGroupsTableName, $_QL88I.`MailListToGroupsTableName`, ";
      $_QLfol .= "$_QL88I.id AS MailingListId, $_jJLQo.forms_id FROM $_jJLQo ";
      $_QLfol .= "LEFT JOIN $_Ijt0i ON $_Ijt0i.id=$_jJLQo.mtas_id LEFT JOIN $_QL88I ON ";
      $_QLfol .= "$_QL88I.id=$_jJLQo.maillists_id WHERE ($_jJLQo.IsActive=1 AND ";
      $_QLfol .= " ( (CURRENT_TIME() > $_jJLQo.SendTime ) OR $_jJLQo.LastRSSFeedContentProcessed = 0) )";

      $_QLfol .= " AND ( ";

      $_QLfol .= " ( $_jJLQo.SendScheduler = 'SendOnNewPosts' ) ";

      $_QLfol .= " OR ";

      $_QLfol .= " ( $_jJLQo.SendScheduler = 'SendAtDay' AND IF(LastRSSFeedContentRetrieved <> '0000-00-00 00:00:00', TO_DAYS(NOW()) <> TO_DAYS(LastRSSFeedContentRetrieved), 1) AND ($_jJLQo.SendDayNames='every day' OR $_jJLQo.SendDayNames= CAST(WEEKDAY(NOW()) AS CHAR) ) ) ";

      $_QLfol .= " OR ";

      $_QLfol .= " ( $_jJLQo.SendScheduler = 'SendMonthDay' AND IF(LastRSSFeedContentRetrieved <> '0000-00-00 00:00:00', TO_DAYS(NOW()) <> TO_DAYS(LastRSSFeedContentRetrieved), 1) AND CAST(DAYOFMONTH(NOW()) AS CHAR) = $_jJLQo.SendMonthDay ) ";

      $_QLfol .= " OR ";

      $_QLfol .= " ( $_jJLQo.LastRSSFeedContentProcessed = 0 ) "; // not completly done

      $_QLfol .= " )";

      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      while($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
        _LRCOC();

        $_JIfo0 .= "checking $_I1OfI[RSS2EMailResponders_Name]...<br />";
        $_JJjQJ = 0;

        _LBOOC($_I1OfI["MailingListId"], $UserId, 0, 'RSS2EMailResponder', $_I1OfI["RSS2EMailResponders_id"]);

        if($_I1OfI["LastRSSFeedContentProcessed"] != 0) {
          $_JfOiJ = new _JQE86();
          if(!$_JfOiJ->XMLAvailable){
            $_JIfo0 .= "No XML parser activated.";
            unset($_JfOiJ);
            $_JfOiJ = null;
            $_JfOIL = true;
            continue;
          }
          $_JfOiJ->MaxEntries = $_I1OfI["RSSFeedMaxEntriesToRetrieve"];
          if(!$_JfOiJ->_JQF0F($_I1OfI["RSSFeedURL"])) {
            $_JIfo0 .= "Error while parsing RSS feed: ".$_JfOiJ->RSSParseError;
            unset($_JfOiJ);
            $_JfOiJ = null;
            $_JfOIL = true;
            continue;
          }
          $_Jfo0j = array();
          $_Jfo0j = base64_encode(serialize($_JfOiJ->channel));
          $_QLfol = "UPDATE `$_jJLQo` SET `LastRSSFeedContentProcessed`=0, `LastRSSFeedContentRetrieved`=NOW(), `LastRSSFeedContent`="._LRAFO($_Jfo0j)." WHERE `id`=$_I1OfI[RSS2EMailResponders_id] AND (`LastRSSFeedContent` IS NULL OR `LastRSSFeedContent`<>"._LRAFO($_Jfo0j).")";
          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) == 0){
            unset($_JfOiJ);
            $_JfOiJ = null;
            $_JIfo0 .= "No new entries found in feed $_I1OfI[RSSFeedURL] ".mysql_error($_QLttI);
            continue;
          }
          $_QLfol = "SELECT `LastRSSFeedContentRetrieved`, `LastRSSFeedContent` FROM `$_jJLQo` WHERE `id`=$_I1OfI[RSS2EMailResponders_id]";
          $_jjl0t = mysql_query($_QLfol, $_QLttI);
          $_I8fol = mysql_fetch_assoc($_jjl0t);
          mysql_free_result($_jjl0t);
          $_I1OfI["LastRSSFeedContentRetrieved"] = $_I8fol["LastRSSFeedContentRetrieved"];
          $_JfooL = $_JfOiJ->channel;
          unset($_JfOiJ);
          $_JfOiJ = null;
        } else {
          $_JfooL = @unserialize(base64_decode($_I1OfI["LastRSSFeedContent"]));
          if($_JfooL === false)
             $_JfooL = array();
        }


        $_I8I6o = $_I1OfI["MaillistTableName"];
        $_jjj8f = $_I1OfI["LocalBlocklistTableName"];
        $_JfCoi = $_I1OfI["MailListsGroupsTableName"];
        $_IfJ66 =  $_I1OfI["MailListToGroupsTableName"];
        
        $_QLfol = _JQBFE($_I1OfI, $_I8I6o, $_JfCoi, $_IfJ66, $_jjj8f);

        // special condition for new RSS feed entries
        $_QLfol .= " AND IF(`$_I1OfI[ML_RM_RefTableName]`.`LastRSSFeedChecking` IS NOT NULL, `$_I1OfI[ML_RM_RefTableName]`.`LastRSSFeedChecking` <> "._LRAFO($_I1OfI["LastRSSFeedContentRetrieved"]).", 1)";

        $_jjl0t = mysql_query($_QLfol, $_QLttI);

        if(mysql_error($_QLttI) != "")
           $_JIfo0 .= "MySQL error while selecting data: ".mysql_error($_QLttI);

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

          mysql_query("BEGIN", $_QLttI);

          $_I8fol["RSS_GUIDs"] = @unserialize($_I8fol["RSS_GUIDs"]);
          if($_I8fol["RSS_GUIDs"] === false)
             $_I8fol["RSS_GUIDs"] = array();
          $_I8fol["New_RSS_Entries"] = array();

          if(isset($_JfooL["ITEMS"])) {
            // find new GUIDs
            for($_Qli6J=0; $_Qli6J<count($_JfooL["ITEMS"]); $_Qli6J++){
              if( !empty( $_JfooL["ITEMS"][$_Qli6J]["GUID"] ) ){
                if(!in_array($_JfooL["ITEMS"][$_Qli6J]["GUID"], $_I8fol["RSS_GUIDs"])) {
                   $_I8fol["RSS_GUIDs"][] = $_JfooL["ITEMS"][$_Qli6J]["GUID"];
                   $_I8fol["New_RSS_Entries"][] = $_JfooL["ITEMS"][$_Qli6J]["GUID"];
                }
              }
            }

            // remove old not existing GUIDs
            foreach($_I8fol["RSS_GUIDs"] as $key => $_QltJO) {
              $_QLCt1 = false;
              for($_Qli6J=0; $_Qli6J<count($_JfooL["ITEMS"]); $_Qli6J++){
                if( !empty( $_JfooL["ITEMS"][$_Qli6J]["GUID"] ) ){
                   if($_JfooL["ITEMS"][$_Qli6J]["GUID"] == $_QltJO) {
                     $_QLCt1 = true;
                     break;
                   }
                }
              }
              if(!$_QLCt1)
                unset($_I8fol["RSS_GUIDs"][$key]);
            }

          }

          # no new entries?
          if(count($_I8fol["New_RSS_Entries"]) == 0){
            $_QLfol = "UPDATE `$_I1OfI[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _LRAFO($_I1OfI["LastRSSFeedContentRetrieved"]) . " WHERE `Member_id`=$_I8fol[id]";
            mysql_query($_QLfol, $_QLttI);
            if(mysql_affected_rows($_QLttI) == 0) { // new?
              $_QLfol = "INSERT INTO `$_I1OfI[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _LRAFO($_I1OfI["LastRSSFeedContentRetrieved"]) . ", `Member_id`=$_I8fol[id]";
              mysql_query($_QLfol, $_QLttI);
            }
            mysql_query("COMMIT", $_QLttI);
            continue;
          }

          $_QLfol = "UPDATE `$_I1OfI[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _LRAFO($_I1OfI["LastRSSFeedContentRetrieved"]) . ", New_RSS_Entries="._LRAFO(serialize($_I8fol["New_RSS_Entries"])).", RSS_GUIDs="._LRAFO(serialize($_I8fol["RSS_GUIDs"]))." WHERE `Member_id`=$_I8fol[id]";
          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) == 0) { // new?
            $_QLfol = "INSERT INTO `$_I1OfI[ML_RM_RefTableName]` SET `LastRSSFeedChecking`=" . _LRAFO($_I1OfI["LastRSSFeedContentRetrieved"]) . ", New_RSS_Entries="._LRAFO(serialize($_I8fol["New_RSS_Entries"])).", RSS_GUIDs="._LRAFO(serialize($_I8fol["RSS_GUIDs"])).", `Member_id`=$_I8fol[id]";
            mysql_query($_QLfol, $_QLttI);
          }

          _LRCOC();

          //ECGList
          $_J0olI = false;
          if($_jlt10){
            $_J0olI = array_search($_I8fol["u_EMail"], array_column($_J0fIj, 'email')) !== false;
          }

          $_JJQ6I = 0;

          $_QLfol = "INSERT INTO `$_j68Co` SET `rss2emailresponders_id`=$_I1OfI[RSS2EMailResponders_id], `MailSubject`="._LRAFO(unhtmlentities($_I1OfI["MailSubject"], $_QLo06, false)).", `SendDateTime`=NOW(), `recipients_id`=$_I8fol[id], `Send`='Prepared'";
          mysql_query($_QLfol, $_QLttI);

          if(mysql_affected_rows($_QLttI) > 0) {
            $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
            $_JJIl0=mysql_fetch_array($_JJQlj);
            $_JJQ6I = $_JJIl0[0];
            mysql_free_result($_JJQlj);
          } else {
            if(mysql_errno($_QLttI) == 1062) continue; // dup key, but no unique index set therefore it will never occur
            $_JIfo0 .= "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
            mysql_query("ROLLBACK", $_QLttI);
            return false;
          }

          # Mail?
          if($_JJQ6I != 0) {
            if(!$_J0olI){
              // SendId = RSS2EMail Id
              $_QLfol = "INSERT INTO $_IQQot SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='RSS2EMailResponder', `Source_id`=$_I1OfI[RSS2EMailResponders_id], `Additional_id`=0, `SendId`=$_I1OfI[RSS2EMailResponders_id], `maillists_id`=$_I1OfI[MailingListId], `recipients_id`=$_I8fol[id], `mtas_id`=$_I1OfI[MTA_id], `LastSending`=NOW(), `IsResponder`=1, `MailSubject`="._LRAFO(unhtmlentities($_I1OfI["MailSubject"], $_QLo06, false));
              mysql_query($_QLfol, $_QLttI);
              if(mysql_error($_QLttI) != "") {
                $_JIfo0 .= "MySQL error while adding mail to out queue: ".mysql_error($_QLttI);
                $_JfOIL = true;
                mysql_query("ROLLBACK", $_QLttI);
                continue;
              }
              $_J0J6C++;
              $_JIfo0 .= "Email with subject '$_I1OfI[MailSubject]' was queued for sending to '$_I8fol[u_EMail]'<br />";

              // Update RSS2EMail responder statistics
              $_QLfol = "UPDATE $_jJLQo SET EMailsSent=EMailsSent+1 WHERE id=$_I1OfI[RSS2EMailResponders_id]";
              mysql_query($_QLfol, $_QLttI);
            }else{
              $_QLfol = "UPDATE `$_j68Co` SET `Send`='Failed', `SendResult`=" . _LRAFO("Recipient is in ECG-Liste.") . "  WHERE `id`=$_JJQ6I";
              mysql_query($_QLfol, $_QLttI);
              $_JIfo0 .= "Email with subject '$_I1OfI[MailSubject]' was not sent to '$_I8fol[u_EMail]', Recipient is in ECG-Liste.<br />";
            }
          }


          $_JJjQJ++;

          // save email send date time
          $_QLfol = "UPDATE `$_I1OfI[ML_RM_RefTableName]` SET LastSending=NOW() WHERE Member_id=$_I8fol[id]";
          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) == 0) { // new?
            $_QLfol = "INSERT INTO `$_I1OfI[ML_RM_RefTableName]` SET Member_id=$_I8fol[id], LastSending=NOW()";
            mysql_query($_QLfol, $_QLttI);
          }

          mysql_query("COMMIT", $_QLttI);
        }
        if($_jjl0t)
          mysql_free_result($_jjl0t);


        if($_JJjQJ == 0){
          $_QLfol = "UPDATE $_jJLQo SET LastRSSFeedContentProcessed=1 WHERE id=$_I1OfI[RSS2EMailResponders_id]";
          mysql_query($_QLfol, $_QLttI);
        }


      }
      if($_I1O6j)
        mysql_free_result($_I1O6j);
    }
    mysql_free_result($_QL8i1);


    $_JIfo0 .= "<br />$_J0J6C emails sent to queue<br />";
    $_JIfo0 .= "<br />RSS2EMail responder checking end.";

    if($_J0J6C)
      return true;
      if($_JfOIL)
      return false;
       else
        return -1;
  }

?>
