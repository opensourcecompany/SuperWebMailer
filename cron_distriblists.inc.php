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

  include_once("inboxcheck.php");
  include_once("savedoptions.inc.php");
  include_once("distribliststools.inc.php");
  include_once("mailcreate.inc.php");

  function _LLR1O(&$_JIfo0) {
    global $_QLttI;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE, $_j1QJf;
    global $resourcestrings, $_I18lo, $_I1tQf, $_QLl1Q;
    global $_IjljI, $_IQQot, $commonmsgHTMLMTANotFound;
    global $_QL88I, $_IjC0Q, $_IjCfJ;
    global $_IIlfi, $_IJi8f, $_QLo06;

    $_JIfo0 = "Distribution list checking starts...<br /><br />";
    $_J6f1t = false;
    $_J6fI1 = false;

    $_QLfol = "SELECT * FROM `$_I18lo` WHERE `UserType`='Admin' AND `IsActive`>0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
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

      $_QLfol = "SELECT `Theme` FROM `$_I1tQf` WHERE `id`=$INTERFACE_THEMESID";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      $_I1OfI = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_I1OfI[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);

      $_j1QJf = $_QLO0f["EMail"];

      $_QLfol = "SELECT `$_IjC0Q`.*, `$_IjC0Q`.id AS DistribLists_id, `$_IjC0Q`.Name AS DistribLists_Name, `$_IjC0Q`.`LeaveMessagesInInbox` AS DistribListLeaveMessagesInInbox, ";
      $_QLfol .= " $_QL88I.MaillistTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId, $_QL88I.FormsTableName, ";
      $_QLfol .= "`$_IjljI`.*, `$_IjljI`.`Name` AS InboxName, `$_IjljI`.`LeaveMessagesInInbox` AS InboxLeaveMessagesInInbox, `$_QL88I`.`MaillistTableName` ";
      $_QLfol .= " FROM `$_IjC0Q` LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_IjC0Q`.`maillists_id` ";
      $_QLfol .= " LEFT JOIN `$_IjljI` ON `$_IjC0Q`.`inboxes_id`=`$_IjljI`.`id`";
      $_QLfol .= " WHERE `$_IjC0Q`.`IsActive`=1";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);

      if(mysql_error($_QLttI) != "") {
        $_JIfo0 .= "MySQL error while selecting data: ".mysql_error($_QLttI);
        $_J6fI1 = true;
      }

      while(!$_J6fI1 && $_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {

        if(isset($_I1OfI["DistribListSubjects"]) && trim($_I1OfI["DistribListSubjects"]) != "")
          $_I1OfI["DistribListSubjects"] = explode(";", $_I1OfI["DistribListSubjects"]);
          else
          $_I1OfI["DistribListSubjects"] = array();

        if( isset($_I1OfI["AcceptSenderEMailAddresses"]) && trim($_I1OfI["AcceptSenderEMailAddresses"]) != "")
          $_I1OfI["AcceptSenderEMailAddresses"] = explode($_QLl1Q, $_I1OfI["AcceptSenderEMailAddresses"]);
          else
          $_I1OfI["AcceptSenderEMailAddresses"] = array();

        _LRCOC();

        $_JIfo0 .= "checking distribution list $_I1OfI[DistribLists_Name] inbox: $_I1OfI[InboxName]...<br />";

        if(empty($_I1OfI["InboxName"]) || empty($_I1OfI["InboxType"])) continue; // database inconsistent

        $_Jji6J = new _LCC0O();
        $_Jji6J->Name = $_I1OfI["InboxName"];
        $_Jji6J->InboxType = $_I1OfI["InboxType"]; // 'pop3', 'imap'
        $_Jji6J->EMailAddress = $_I1OfI["EMailAddress"];
        $_Jji6J->Servername = $_I1OfI["Servername"];
        $_Jji6J->Serverport = $_I1OfI["Serverport"];
        $_Jji6J->Username = $_I1OfI["Username"];
        $_Jji6J->Password = $_I1OfI["Password"];
        $_Jji6J->SSLConnection = $_I1OfI["SSL"];
        $_Jji6J->LeaveMessagesInInbox = true; // remove after processing
        $LeaveMessagesInInbox = false;
        if($_I1OfI["DistribListLeaveMessagesInInbox"])
          $LeaveMessagesInInbox = true;
          else
          $LeaveMessagesInInbox = $_I1OfI["InboxLeaveMessagesInInbox"];
        $_Jji6J->NumberOfEMailsToProcess = $_I1OfI["MaxEMailsToProcess"];
        $_Jji6J->EntireMessage = true;
        $_Jji6J->AttachmentsPath = $_IIlfi;
        $_Jji6J->InlineImagesPath = $_IJi8f;
        $_Jji6J->_LCCOQ($_I1OfI["MaxEMailSize"]);

        if(isset($_I1OfI["ResponderUIDL"]) && $_I1OfI["ResponderUIDL"] != "") {
             $_Jji6J->UIDL = @unserialize($_I1OfI["ResponderUIDL"]);
             if($_Jji6J->UIDL === false)
                $_Jji6J->UIDL = array();
           }
           else
           $_Jji6J->UIDL = array();

        $_ILJIO = array();

        $_J0COJ = "";
        $_JjLJ1 = 0;
        $_JJjQJ = 0;
        if(!$_Jji6J->_LCACA($_J0COJ, $_ILJIO, $_JjLJ1) ) {
           $_JIfo0 .= "Error: ".$_J0COJ."; count of mails: ".$_JjLJ1."<br />";
           //$_J6fI1 = true;

           // save UIDL
           $_QLfol = "UPDATE $_IjC0Q SET ResponderUIDL="._LRAFO( serialize($_Jji6J->UIDL) )." WHERE id=".$_I1OfI["DistribLists_id"];
           mysql_query($_QLfol, $_QLttI);

        } else {

          $_JIfo0 .= "Successfully; found new emails: ".$_JjLJ1."; processable mails: ".count($_ILJIO)."<br />";
          $_J6fij = array();
          if(count($_ILJIO)) {
             for($_Qli6J=0; $_Qli6J<count($_ILJIO); $_Qli6J++) {
               _LRCOC();
               $_J68tI = false;
               if( _LLRJ8($_ILJIO[$_Qli6J], $_I1OfI, $Subject, $_J0COJ, $_J68tI) ) {
                  $Subject = _LA8F6($Subject);
                  $Subject = _LC6CP( str_replace("&amp;", "&", htmlspecialchars($Subject, ENT_COMPAT, $_QLo06, false)) );
                  $_J6fij[] = array("msg_id" => $_ILJIO[$_Qli6J]["Server_msg_id"], "uidl" => $_ILJIO[$_Qli6J]["Server_uidl"]);
                  $_JIfo0 .= "mail with subject '$Subject' added for distribution<br />";
                  $_JJjQJ++;
                  $_J6f1t = $_JJjQJ > 3; // more than 3, than we don't send anything at this script request
                 }
                 else {
                      $Subject = _LA8F6($Subject);
                      $Subject = _LC6CP( str_replace("&amp;", "&", htmlspecialchars($Subject, ENT_COMPAT, $_QLo06, false)) );
                      if( $_J0COJ != "") {
                         $_JIfo0 .= "error while processing mail with subject '$Subject', Error: $_J0COJ<br />";
                      }
                      if($_J68tI)
                        $_J6fI1 = true;
                        else{
                          // ignore soft error e.g. wrong subject
                        }
                   }
             } // for $_Qli6J

             if(!$LeaveMessagesInInbox && count($_J6fij)){
               if(!$_Jji6J->deleteMessagesFromServer($_J6fij, $_J0COJ)){
                    $_JIfo0 .= "error while removing email from server, Error: $_J0COJ<br />";
               }
             }
          }

          // save processed emails and UIDL
          $_QLfol = "UPDATE $_IjC0Q SET EMailsProcessed=EMailsProcessed + $_JJjQJ, ResponderUIDL="._LRAFO( serialize($_Jji6J->UIDL) )." WHERE id=".$_I1OfI["DistribLists_id"];
          mysql_query($_QLfol, $_QLttI);

        }

        unset($_Jji6J);

      }
      mysql_free_result($_I1O6j);

    }
    if($_QL8i1)
      mysql_free_result($_QL8i1);

    // Timing problems, sending it when there are no emails in inbox(es)
    if(!$_J6fI1 && !$_J6f1t){
       $_J0J6C = 0;

       $_QLfol = "SELECT * FROM `$_I18lo` WHERE `UserType`='Admin' AND `IsActive`>0";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
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

         $_QLfol = "SELECT `Theme` FROM `$_I1tQf` WHERE `id`=$INTERFACE_THEMESID";
         $_I1O6j = mysql_query($_QLfol, $_QLttI);
         $_I1OfI = mysql_fetch_row($_I1O6j);
         $INTERFACE_STYLE = $_I1OfI[0];
         mysql_free_result($_I1O6j);

         _LR8AP($_QLO0f);

         _LRRFJ($UserId);


         // check sent distribution lists
         $_QLfol = "SELECT `$_IjC0Q`.`id`, `$_IjC0Q`.`Name` AS `DistribLists_Name`, `$_IjC0Q`.Creator_users_id, `$_IjC0Q`.CurrentSendTableName, ";
         $_QLfol .= "`$_IjC0Q`.RStatisticsTableName, `$_IjC0Q`.MTAsTableName, `$_IjC0Q`.maillists_id, ";
         $_QLfol .= "`$_IjC0Q`.SendReportToDistribSenderEMailAddress, `$_IjC0Q`.SendReportToYourSelf, `$_IjC0Q`.SendReportToListAdmin, `$_IjC0Q`.SendReportToMailingListUsers, `$_IjC0Q`.SendReportToEMailAddress, `$_IjC0Q`.SendReportToEMailAddressEMailAddress, ";
         $_QLfol .= "$_QL88I.users_id, $_QL88I.MaillistTableName, $_QL88I.id AS MailingListId ";
         $_QLfol .= " FROM `$_IjC0Q` LEFT JOIN $_QL88I ON $_QL88I.id=`$_IjC0Q`.maillists_id";
         $_QLfol .= " WHERE (`$_IjC0Q`.`IsActive`=1)";
         $_I1O6j = mysql_query($_QLfol, $_QLttI);
         while($_I1O6j && $_J68ot = mysql_fetch_assoc($_I1O6j)) {
           _LRCOC();

           _LLP0O($_J68ot, $_JIfo0);

         } # while($_J68ot = mysql_fetch_assoc($_I1O6j))

         if($_I1O6j)
           mysql_free_result($_I1O6j);
         // check sent distribution lists done

         // check to send distribution lists

         // MailTextInfos
         $_QLfol = "SELECT `$_IjC0Q`.*, `$_IjCfJ`.`id` AS `DistribListEntryId`, `$_IjCfJ`.`SendScheduler`, `$_IjCfJ`.`MailSubject`, `$_IjCfJ`.`DistribSenderEMailAddress`, ";
         $_QLfol .= " $_QL88I.MaillistTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId ";
         $_QLfol .= " FROM `$_IjC0Q` LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_IjC0Q`.`maillists_id`";
         $_QLfol .= " LEFT JOIN `$_IjCfJ` ON `$_IjCfJ`.`DistribList_id`=`$_IjC0Q`.`id`";
         $_QLfol .= " WHERE (`$_IjC0Q`.`IsActive`=1 AND `$_IjCfJ`.`SendScheduler`='SendImmediately') ";

         $_I1O6j = mysql_query($_QLfol, $_QLttI);

         while($_I1O6j && $_J68ot = mysql_fetch_assoc($_I1O6j)) {
           _LRCOC();

           mysql_query("BEGIN", $_QLttI);

           // we can't check this in sql above
           if( $_J68ot["SendScheduler"] == 'SendImmediately' ) {
             $_QLfol = "SELECT COUNT(`id`) FROM `$_J68ot[CurrentSendTableName]` WHERE `SendState`='Done' AND `distriblistentry_id`=$_J68ot[DistribListEntryId]";
             $_J6QJI = mysql_query($_QLfol, $_QLttI);
             $_IQjl0 = mysql_fetch_row($_J6QJI);
             if($_IQjl0[0] > 0) { // always sending done?
               mysql_free_result($_J6QJI);
               $_QLfol = "UPDATE `$_IjCfJ` SET `SendScheduler`='Sent' WHERE `id`=$_J68ot[DistribListEntryId]";
               mysql_query($_QLfol, $_QLttI);
               if(mysql_affected_rows($_QLttI))
                 $_JIfo0 .= "<br />sending of distribution list $_J68ot[Name] completed.<br />";
               mysql_query("COMMIT", $_QLttI);
               continue;
             }
             mysql_free_result($_J6QJI);
           }

           $MailingListId = $_J68ot["maillists_id"];
           $FormId = $_J68ot["forms_id"];
           $_QljJi = $_J68ot["GroupsTableName"];
           $_I8I6o = $_J68ot["MaillistTableName"];
           $_IfJ66 = $_J68ot["MailListToGroupsTableName"];
           $_jjj8f = $_J68ot["LocalBlocklistTableName"];

           // CurrentSendTableName
           $_jlOJO = 0;
           $_J6t11 = 0;
           $_jloJI = 0;
           $_jlLQC = 0;

           $_QLfol = "SELECT * ";
           $_QLfol .= "FROM `$_J68ot[CurrentSendTableName]` WHERE `distriblistentry_id`=$_J68ot[DistribListEntryId] AND `SendState`<>'Done' AND `SendState`<>'Paused'";

           $_J6QJI = mysql_query($_QLfol, $_QLttI);
           if(mysql_num_rows($_J6QJI) > 0) {
             $_IQjl0 = mysql_fetch_assoc($_J6QJI);
             mysql_free_result($_J6QJI);
             if($_IQjl0["DistribEntrySendDone"]) { // distriblist always prepared completly?
               mysql_query("COMMIT", $_QLttI);
               continue;
             }
             $_jlOJO = $_IQjl0["LastMember_id"];
             $_J6t11 = $_IQjl0["id"];
             $_jloJI = $_IQjl0["RecipientsCount"];
             $_jlLQC = $_IQjl0["ReportSent"];
           } else{
             mysql_free_result($_J6QJI);

             // CurrentSendTableName
             $_QLfol = "INSERT INTO `$_J68ot[CurrentSendTableName]` SET `StartSendDateTime`=NOW(), `EndSendDateTime`=NOW(), `distriblistentry_id`=$_J68ot[DistribListEntryId]";
             mysql_query($_QLfol, $_QLttI);
             $_J6QJI = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
             $_IQjl0 = mysql_fetch_array($_J6QJI);
             $_J6t11 = $_IQjl0[0];
             $_J68ot["CurrentSendId"] = $_J6t11;
             mysql_free_result($_J6QJI);

             // SET Current used MTAs to zero
             $_QLfol = "INSERT INTO `$_J68ot[CurrentUsedMTAsTableName]` SELECT 0, $_J68ot[DistribListEntryId], $_J6t11, `mtas_id`, 0 FROM `$_J68ot[MTAsTableName]` ORDER BY sortorder";
             mysql_query($_QLfol, $_QLttI);

           }

           // check all mtas_id are in CurrentUsedMTAsTableName
           $_QLfol = "SELECT `$_J68ot[MTAsTableName]`.mtas_id, `$_J68ot[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_J68ot[MTAsTableName]` LEFT JOIN `$_J68ot[CurrentUsedMTAsTableName]` ON `$_J68ot[CurrentUsedMTAsTableName]`.mtas_id = `$_J68ot[MTAsTableName]`.mtas_id ";
           $_QLfol .= "WHERE `$_J68ot[CurrentUsedMTAsTableName]`.`SendStat_id`=$_J6t11 AND `$_J68ot[CurrentUsedMTAsTableName]`.`distriblistentry_id`=$_J68ot[DistribListEntryId] ORDER BY sortorder";
           $_J6j0L = mysql_query($_QLfol, $_QLttI);
           if($_J6j0L)
             $_jllCj = mysql_num_rows($_J6j0L);
             else
             $_jllCj = 0;

           if(!$_J6j0L || $_jllCj == 0) {
             $_JIfo0 .= $commonmsgHTMLMTANotFound;
             mysql_query("COMMIT", $_QLttI);
             continue;
           }

           while($_J6jJ6 = mysql_fetch_assoc($_J6j0L)) {
             $_J00C0 = $_J6jJ6; // save it
             if($_J00C0["Usedmtas_id"] == "NULL") {
               $_QLfol = "INSERT INTO `$_J68ot[CurrentUsedMTAsTableName]` SET `distriblistentry_id`=$_J68ot[DistribListEntryId], `SendStat_id`=$_J6t11, `mtas_id`=$_J00C0[mtas_id]";
               mysql_query($_QLfol, $_QLttI);
             }
           }
           mysql_free_result($_J6j0L);

           # one MTA only than we can get data and merge it directly
           if($_jllCj == 1){
             $_J68ot["mtas_id"] = $_J00C0["mtas_id"];
           }

           if($_jloJI == 0) {
             $_jloJI = _L6J61($_J68ot, $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
             mysql_query("UPDATE `$_J68ot[CurrentSendTableName]` SET RecipientsCount=$_jloJI WHERE id=$_J6t11", $_QLttI);
           }

           // ECGList
           if(!isset($_jlt10))
             $_jlt10 = _JOLQE("ECGListCheck");
           // ECG List not more than 5000
           if($_jlt10){
             if($_J68ot["MaxEMailsToProcess"] > 5000)
               $_J68ot["MaxEMailsToProcess"] = 5000;
           }  
           // ECGList /

           $_QLfol = _L6LOF($_J68ot, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
           $_QLfol .= " AND `$_I8I6o`.id>$_jlOJO ORDER BY `$_I8I6o`.id LIMIT 0, $_J68ot[MaxEMailsToSend]";

           $_jjl0t = mysql_query($_QLfol, $_QLttI);

           $_JJjQJ = 0;
           if(mysql_num_rows($_jjl0t) > 0)
              $_JIfo0 .= "<br />checking $_J68ot[Name]...<br />";

           # is distriblist entry sending done?
           if(mysql_num_rows($_jjl0t) == 0) {
             $_QLfol = "UPDATE `$_J68ot[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `DistribEntrySendDone`=1 WHERE `id`=$_J6t11";
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
             if($_JJjQJ >= $_J68ot["MaxEMailsToSend"]) break;

             _LRCOC();

             //ECGList
             $_J0olI = false;
             if($_jlt10){
               $_J0olI = array_search($_I8fol["u_EMail"], array_column($_J0fIj, 'email')) !== false;
             }

             $_QLfol = "INSERT INTO `$_J68ot[RStatisticsTableName]` SET `distriblistentry_id`=$_J68ot[DistribListEntryId], `SendStat_id`=$_J6t11, `MailSubject`="._LRAFO( unhtmlentities($_J68ot["MailSubject"], $_QLo06, false) ).", `SendDateTime`=NOW(), `recipients_id`=$_I8fol[id], `Send`='Prepared'";
             mysql_query($_QLfol, $_QLttI);

             $_J6t1L = true;
             $_JJQ6I = 0;
             if(mysql_affected_rows($_QLttI) > 0) {
               $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
               $_JJIl0 = mysql_fetch_array($_JJQlj);
               $_JJQ6I = $_JJIl0[0];
               mysql_free_result($_JJQlj);
             } else {
               if(mysql_errno($_QLttI) == 1062) {// dup key
                  $_QLfol = "SELECT `id` FROM `$_J68ot[RStatisticsTableName]` WHERE `distriblistentry_id`=$_J68ot[DistribListEntryId] AND `SendStat_id`=$_J6t11 AND `recipients_id`=$_I8fol[id] AND `Send`='Prepared'";
                  $_JJQlj = mysql_query($_QLfol, $_QLttI);
                  $_J6t1L = false;
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
                 $_J00C0 = _L66JQ($_J68ot["CurrentUsedMTAsTableName"], $_J68ot["MTAsTableName"], $_J6t11, $_J68ot["DistribListEntryId"]);
                 $_J61tJ["mtas_id"] = $_J00C0["id"];
               }

               if(!$_J0olI){
                 $_QLfol = "INSERT INTO `$_IQQot` SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='DistributionList', `Source_id`=$_J68ot[id], `Additional_id`=$_J68ot[DistribListEntryId], `SendId`=$_J6t11, `maillists_id`=$_J68ot[MailingListId], `recipients_id`=$_I8fol[id], `mtas_id`=$_J68ot[mtas_id], `LastSending`=NOW(), `MailSubject`=" . _LRAFO( unhtmlentities($_J68ot["MailSubject"], $_QLo06, false) );
                 mysql_query($_QLfol, $_QLttI);
                 if(mysql_error($_QLttI) != "") {
                   $_JIfo0 .=  "MySQL error while adding mail to out queue: ".mysql_error($_QLttI);
                   mysql_query("ROLLBACK", $_QLttI);
                   continue;
                 }

                 $_J0J6C++;
               }else{
                 $_QLfol = "UPDATE `$_J68ot[RStatisticsTableName]` SET `Send`='Failed', `SendResult`=" . _LRAFO("Recipient is in ECG-Liste.") . "  WHERE `id`=$_JJQ6I";
                 mysql_query($_QLfol, $_QLttI);
               }
               if($_J6t1L)
                 $_JJjQJ++;
             }

             //$_JIfo0 .= "Email with subject '$_J61tJ[MailSubject]' was queued for sending to '$_I8fol[u_EMail]'<br />";

             # update last member id
             $_QLfol = "UPDATE `$_J68ot[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `LastMember_id`=$_I8fol[id] WHERE `id`=$_J6t11";
             mysql_query($_QLfol, $_QLttI);

           } # while($_I8fol = mysql_fetch_array($_jjl0t))
           if($_jjl0t)
             mysql_free_result($_jjl0t);

           if($_JJjQJ > 0) {
              $_JIfo0 .= "$_J68ot[Name] checked, $_JJjQJ email(s) with subject '".$_J68ot["MailSubject"]."' sent to queue.<br />";
              // Update distribution list statistics, use $_JJjQJ for this list
              $_QLfol = "UPDATE $_IjC0Q SET EMailsSent=EMailsSent+$_JJjQJ WHERE id=$_J68ot[id]";
              mysql_query($_QLfol, $_QLttI);
           }

           mysql_query("COMMIT", $_QLttI);

         } # while($_J68ot = mysql_fetch_assoc($_I1O6j))
         if($_I1O6j)
           mysql_free_result($_I1O6j);
       } # while($_QLO0f = mysql_fetch_assoc($_QL8i1) )


       $_JIfo0 .= "<br />$_J0J6C emails sent to queue<br />";
       if($_J0J6C)
         $_J6f1t = true;


    } // if(!$_J6fI1 && !$_J6f1t)

    $_JIfo0 .= "<br />Distribution list checking end.";

    if($_J6fI1)
      return false;

    if($_J6f1t)
      return true;
      else
      return -1;

  }

  function _LLRJ8($_J6t80, $_j0i6j, &$Subject, &$_J0COJ, &$_J68tI){
    global $_QLttI, $UserId, $_j1QJf;
    global $_QL88I, $_IjCfJ, $_Ijt0i, $_QLl1Q, $_Ij8oL, $INTERFACE_LANGUAGE, $resourcestrings, $_QLo06, $_Ij08l;
    global $_IIlfi, $_IJi8f;

    // PHP doesn't support this encodings change to iso-8859-1
    $_Jjl11 = array("us-ascii", "ascii", "ibm852", "cp-850", "windows-1250"/*, "windows-1252"*/, "iso-8859-2", "iso-2022-jp");

    $_J0COJ = "";
    $_J68tI = false;

    if(!isset($_J6t80["headers"]["subject"]))
      $_J6t80["headers"]["subject"] = "";
    if(!isset($_J6t80["headers"]["from"])) {
      $_J0COJ = "no from address found.";
      return false;
    }

    if($_j0i6j["IgnoreHeaderXLoop"]) {
      if( isset($_J6t80["headers"]["x-loop"]) || isset($_J6t80["headers"]["x-auto-response-suppress"]) || (isset($_J6t80["headers"]["auto-submitted"]) && $_J6t80["headers"]["auto-submitted"] == "auto-generated")  ) {
        $_J0COJ = "mail looping detected";
        return false;
      }
    }

    $charset = "";
    $_IoOif = $_J6t80["headers"]["subject"];

    if(isset($_J6t80["headers"]["content-type"])) {
      $charset = $_J6t80["headers"]["content-type"];

      if(strpos($charset, "charset=") !== false)
        $charset = substr($charset, strpos($charset, "charset=") + 8);
      if(strpos($charset, ";") !== false)
        $charset = substr($charset, 0, strpos($charset, ";"));
      $charset = str_replace('"', '', $charset);
    }
    if(stripos($charset, "multipart/") !== false && isset($_J6t80["parts"])) {

      $charset = $_QLo06;
      $_I0iti = $_J6t80["parts"];
      for($_Qli6J=0; $_Qli6J<count($_I0iti); $_Qli6J++){

         if( isset($_I0iti[$_Qli6J]["plaintext"]) && $_I0iti[$_Qli6J]["plaintext"]  ) {
           $charset  = $_I0iti[$_Qli6J]["charset"];
         }

         if( isset($_I0iti[$_Qli6J]["html"]) && $_I0iti[$_Qli6J]["html"] ) {
           $charset  = $_I0iti[$_Qli6J]["charset"];
         }

      }
    }

    $charset = strtolower($charset);
    if($charset == "us-ascii" && IsUtf8String($_IoOif)){ // Apple Mail?
      $charset = "utf-8";
    }  

    if( in_arrayi($charset, $_Jjl11) ){ 
      $charset = "iso-8859-1";
    }  

    // Subject in header has other encoding as content of email
    if($charset == "utf-8" && !IsUtf8String($_IoOif)) 
      $_IoOif = ConvertString("iso-8859-1", $_QLo06, $_IoOif, false);
      else
      if($charset != "utf-8" && IsUtf8String($_IoOif)) 
        $_IoOif = ConvertString("utf-8", $charset, $_IoOif, false);
    
    $_IoOif = ConvertString($charset, $_QLo06, $_IoOif, false);

    $_IoOif = str_replace("\r", "", $_IoOif);
    $_IoOif = str_replace("\n", "", $_IoOif);
    $_IoOif = _LA8F6($_IoOif);
    $Subject = $_IoOif;

    // security check
    if(count($_j0i6j["DistribListSubjects"]) > 0) {
      $_QLCt1 = false;
      for($_Qli6J=0; $_Qli6J<count($_j0i6j["DistribListSubjects"]); $_Qli6J++){
        if(stripos($Subject, $_j0i6j["DistribListSubjects"][$_Qli6J]) !== false) {
          $_QLCt1 = true;
          break;
        }
      }
      if(!$_QLCt1) {
        $_J0COJ = "required mail subject not found";
        $_J68tI = true;
        return false;
      }
    }

    $_IL6Jt = new Mail_RFC822();
    $From = $_J6t80["headers"]["from"];
    $_ILfoj = "";
    $_ILf66 = $_IL6Jt->parseAddressList($From, null, null, false); // no ASCII check

    if ( !(IsPEARError($_ILf66)) && isset($_ILf66[0]->mailbox) && isset($_ILf66[0]->host) ) {
      $_IL8oI = $_ILf66[0]->mailbox."@".$_ILf66[0]->host;

      if(isset($_ILf66[0]->personal))
        $_ILfoj = $_ILf66[0]->personal;

    } else {
      $_J0COJ = "invalid 'from' address.";
      return false;
    }
    array_shift($_ILf66);
    $_J6OIC = $_ILf66;

    if($_j0i6j["WorkAsRealMailingList"]){
      // check to
      $_J6OjO = array();
      $_ILf66 = $_IL6Jt->parseAddressList($_J6t80["headers"]["to"], null, null, false); // no ASCII check
      if(IsPEARError($_ILf66)){
        $_J0COJ = "invalid 'to' address.";
        return false;
      }
      
      for($_Qli6J=0; $_Qli6J<count($_ILf66); $_Qli6J++){
        if ( isset($_ILf66[$_Qli6J]->mailbox) && isset($_ILf66[$_Qli6J]->host) ) {
          $_J6Off = $_ILf66[$_Qli6J]->mailbox."@".$_ILf66[$_Qli6J]->host;

          $_J6OoL = "";
          if(isset($_ILf66[$_Qli6J]->personal))
            $_J6OoL = $_ILf66[$_Qli6J]->personal;
          $_J6OjO[] = array("address" => $_J6Off, "name" => $_J6OoL);  
        }      
      }

      // check Cc
      $_J6o8Q = array(); 
      // when there are other From addresses
      for($_Qli6J=0; $_Qli6J<count($_J6OIC); $_Qli6J++){
        if ( isset($_J6OIC[$_Qli6J]->mailbox) && isset($_J6OIC[$_Qli6J]->host) ) {
          $_J6CJo = $_J6OIC[$_Qli6J]->mailbox."@".$_J6OIC[$_Qli6J]->host;

          $_J6Cof = "";
          if(isset($_J6OIC[$_Qli6J]->personal))
            $_J6Cof = $_J6OIC[$_Qli6J]->personal;
          $_J6o8Q[] = array("address" => $_J6CJo, "name" => $_J6Cof);  
        }      
      }
      
      if(isset($_J6t80["headers"]["cc"])){
        $_ILf66 = $_IL6Jt->parseAddressList($_J6t80["headers"]["cc"], null, null, false); // no ASCII check
        
        if(!IsPEARError($_ILf66)){
          for($_Qli6J=0; $_Qli6J<count($_ILf66); $_Qli6J++){
            if ( isset($_ILf66[$_Qli6J]->mailbox) && isset($_ILf66[$_Qli6J]->host) ) {
              $_J6Cof = "";
              $_J6CJo = $_ILf66[$_Qli6J]->mailbox."@".$_ILf66[$_Qli6J]->host;

              if(isset($_ILf66[$_Qli6J]->personal))
                $_J6Cof = $_ILf66[$_Qli6J]->personal;

              $_J6o8Q[] = array("address" => $_J6CJo, "name" => $_J6Cof);  
            }      
          }
        }
      }
      
    }

    $_J6iji="";
    if(!empty($_J6t80["headers"]["reply-to"])) {
      $_J6iji = trim($_J6t80["headers"]["reply-to"]);
      $_ILf66 = $_IL6Jt->parseAddressList($_J6iji, null, null, false); // no ASCII check
      if ( !(IsPEARError($_ILf66)) && isset($_ILf66[0]->mailbox) && isset($_ILf66[0]->host) ) {
        $_J6iji = trim($_ILf66[0]->mailbox."@".$_ILf66[0]->host);
      }
    }

    unset($_IL6Jt);

    if($charset == "utf-8" && !IsUtf8String($_ILfoj)) // From in header has other encoding as content of email
      $_ILfoj = ConvertString("iso-8859-1", $_QLo06, $_ILfoj, false);

    $_ILfoj = ConvertString($charset, $_QLo06, $_ILfoj, false);
    $_ILfoj = str_replace('"', '', $_ILfoj);
    $_ILfoj = trim(str_replace("'", '', $_ILfoj));
    $_ILfoj = str_replace("\r", "", $_ILfoj);
    $_ILfoj = str_replace("\n", "", $_ILfoj);
    $_ILfoj = _LA8F6($_ILfoj);

    $_IL8oI = trim(strtolower($_IL8oI));
    $_IL8oI = str_replace("\r", "", $_IL8oI);
    $_IL8oI = str_replace("\n", "", $_IL8oI);

    $_J6iji = trim(strtolower($_J6iji));
    $_J6iji = str_replace("\r", "", $_J6iji);
    $_J6iji = str_replace("\n", "", $_J6iji);

    $_J6i6i = array();
    if($_j0i6j["WorkAsRealMailingList"]){
      $_J6i6i["From"] = array("address" => $_IL8oI, "name" => $_ILfoj);
      for($_Qli6J=0; $_Qli6J<count($_J6OjO); $_Qli6J++){
        $_J6OjO[$_Qli6J]["address"] = str_replace("\r", "", $_J6OjO[$_Qli6J]["address"]); 
        $_J6OjO[$_Qli6J]["address"] = str_replace("\n", "", $_J6OjO[$_Qli6J]["address"]); 
        $_J6OjO[$_Qli6J]["name"] = str_replace('"', '', $_J6OjO[$_Qli6J]["name"]);
        $_J6OjO[$_Qli6J]["name"] = trim(str_replace("'", '', $_J6OjO[$_Qli6J]["name"]));
        $_J6OjO[$_Qli6J]["name"] = str_replace("\r", "", $_J6OjO[$_Qli6J]["name"]);
        $_J6OjO[$_Qli6J]["name"] = str_replace("\n", "", $_J6OjO[$_Qli6J]["name"]);
        $_J6OjO[$_Qli6J]["name"] = _LA8F6($_J6OjO[$_Qli6J]["name"]);
      }
      $_J6i6i["To"] = $_J6OjO;
      for($_Qli6J=0; $_Qli6J<count($_J6o8Q); $_Qli6J++){
        $_J6o8Q[$_Qli6J]["address"] = str_replace("\r", "", $_J6o8Q[$_Qli6J]["address"]); 
        $_J6o8Q[$_Qli6J]["address"] = str_replace("\n", "", $_J6o8Q[$_Qli6J]["address"]); 
        $_J6o8Q[$_Qli6J]["name"] = str_replace('"', '', $_J6o8Q[$_Qli6J]["name"]);
        $_J6o8Q[$_Qli6J]["name"] = trim(str_replace("'", '', $_J6o8Q[$_Qli6J]["name"]));
        $_J6o8Q[$_Qli6J]["name"] = str_replace("\r", "", $_J6o8Q[$_Qli6J]["name"]);
        $_J6o8Q[$_Qli6J]["name"] = str_replace("\n", "", $_J6o8Q[$_Qli6J]["name"]);
        $_J6o8Q[$_Qli6J]["name"] = _LA8F6($_J6o8Q[$_Qli6J]["name"]);
      }
      $_J6i6i["Cc"] = $_J6o8Q;
    }
    
    
    if(!$_j0i6j["AcceptAllSenderEMailAddresses"]){
       $_QLCt1 = in_arrayi($_IL8oI, $_j0i6j["AcceptSenderEMailAddresses"]);
       if(!$_QLCt1){
         $_J0COJ = "email address '$_IL8oI' isn't allowed to send emails to this distribution list";
         $_J68tI = true;
         return false;
       }
    } else
     if($_j0i6j["AcceptAllSenderEMailAddresses"] == 2){
       $_QLfol = _L6LOF($_j0i6j, $_j0i6j["MaillistTableName"], $_j0i6j["GroupsTableName"], $_j0i6j["MailListToGroupsTableName"], $_j0i6j["LocalBlocklistTableName"]);       
       $_QLfol .= " AND `$_j0i6j[MaillistTableName]`.`u_EMail`="._LRAFO($_IL8oI);
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(mysql_num_rows($_QL8i1) == 0){
         mysql_free_result($_QL8i1);
         $_J0COJ = "email address '$_IL8oI' isn't allowed to send emails to this distribution list";
         $_J68tI = true;
         return false;
       }
       mysql_free_result($_QL8i1);
     }

    if(isset($_J6t80["Server_MaxEMaiSizeReached"])) {

       $_QLfol = "SELECT `mtas_id` FROM `$_j0i6j[MTAsTableName]` ORDER BY `sortorder`";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if($_QLO0f=mysql_fetch_row($_QL8i1)){
          $_IotL0 = $_QLO0f[0];
          $_QLfol = "SELECT * FROM `$_Ijt0i` WHERE id=$_IotL0";
          $_j0L0O = mysql_query($_QLfol, $_QLttI);
          $_j0iLj = mysql_fetch_assoc($_j0L0O);
          mysql_free_result($_j0L0O);
       }
        else
          $_IotL0 = 0;
       mysql_free_result($_QL8i1);

       $_ILi8o = $resourcestrings[$INTERFACE_LANGUAGE]["DistribListsEMailSizeLimit"];

       $_j1IQL = join("", file(_LOC8P()."distriblist_emailsizelimit.txt"));
       if(!IsUtf8String($_j1IQL))
         $_j1IQL = utf8_encode($_j1IQL);

       $_j1IQL = str_replace('[DISTRIBLISTNAME]', $_j0i6j["DistribLists_Name"], $_j1IQL);
       $_ILi8o = str_replace('[DISTRIBLISTNAME]', $_j0i6j["DistribLists_Name"], $_ILi8o);
       $_j1IQL = str_replace('[SUBJECT]', $_IoOif, $_j1IQL);
       $_j1IQL = str_replace('[FROMADDRESS]', $_IL8oI, $_j1IQL);
       $_j1IQL = str_replace('[DISTRIBLISTMAXEMAILSIZE]', $_j0i6j["MaxEMailSize"], $_j1IQL);
       $_j1IQL = str_replace('[EMAILSIZE]', $_J6t80["Server_MaxEMaiSizeReached"], $_j1IQL);
       
       _L6R1E($_j1QJf, $_j1QJf, $_ILi8o, $_j1IQL, $_QLo06, $_IotL0);
       
       $_J0COJ = "email from '$_IL8oI' with subject '$Subject', " . $_J6t80["Server_MaxEMaiSizeReached"] . "bytes, exceeds defined size limit for this distribution list.";
       $_J68tI = true;
       return false;
    }
     
    $_J6ioj = "";
    if( $_j0i6j["SendConfirmationRequired"] > 0 ){
       $_j1881 = mt_rand(8, 48);

       do{
         $_J6ioj = dechex($_j0i6j["DistribLists_id"]) . _LBQB1($_j1881);
         $_QLfol = "SELECT `SendConfirmationString` FROM `$_IjCfJ` WHERE `SendConfirmationString`="._LRAFO($_J6ioj);
         $_jjJfo = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_jjJfo) > 0)
           $_J6ioj = "";
         mysql_free_result($_jjJfo);
       }while($_J6ioj == "");
    }

    $_jLtII = "Normal";
    $_IooIi = "PlainText";
    $_Ioolt = strtolower($_QLo06);
    $_J6LOL = "";
    $_IJfIf = "";
    $_J6LL8 = "";
    $_J6l6i = "";
    $attachments = array();
    $_J6llt = array();
    $_I0iti = $_J6t80["parts"];

    if( count($_I0iti) == 0){
      if( stripos($_J6t80["content_type"], "plain") === false )
        $_IooIi = "HTML";
      $_J6LOL = $_J6t80["body"];
      $_J6LL8  = $_J6t80["charset"];

      if(strtolower($_J6LL8) == "us-ascii" && IsUtf8String($_J6LOL)){ // Apple Mail?
         $_J6LL8 = "utf-8";
      }

      if($_IooIi == "HTML") { // plain html
        $_IJfIf = $_J6t80["body"];
        $_J6l6i = $_J6t80["charset"];

        if( strtolower($_J6l6i) == "us-ascii" && IsUtf8String($_IJfIf) || strtolower(GetHTMLCharSet($_IJfIf)) == "utf-8" ){ // Apple Mail?
           $_J6l6i = "utf-8";
        }
        
        if( in_arrayi(strtolower($_J6l6i), $_Jjl11) ){ 
          $_J6l6i = "iso-8859-1";
        }  
          
        $_J6LOL = _LBDA8($_J6LOL, $_J6l6i);
      }
    } else{
      for($_Qli6J=0; $_Qli6J<count($_I0iti); $_Qli6J++){

        if( isset($_I0iti[$_Qli6J]["plaintext"]) && $_I0iti[$_Qli6J]["plaintext"]  ) {
          $_J6LOL = $_I0iti[$_Qli6J]["body"];
          $_J6LL8  = $_I0iti[$_Qli6J]["charset"];
        }

        if( isset($_I0iti[$_Qli6J]["html"]) && $_I0iti[$_Qli6J]["html"] ) {
          $_IJfIf = $_I0iti[$_Qli6J]["body"];
          $_J6l6i  = $_I0iti[$_Qli6J]["charset"];
        }

        if( isset($_I0iti[$_Qli6J]["attachment"]) ) {
          $attachments[] = $_I0iti[$_Qli6J]["attachment"];
        }

        if( isset($_I0iti[$_Qli6J]["inlineimage"]) ) {
          $_Jf0Cl = _LL8LJ($_I0iti[$_Qli6J]["cid"]);
          $_J6llt[] = array( "inlineimage" => $_I0iti[$_Qli6J]["inlineimage"], "cid" => $_Jf0Cl, "used" => false );
        }

      }

      if(!empty($_J6LOL) && !empty($_IJfIf))
        $_IooIi = "Multipart";
        else
        if(!empty($_IJfIf))
          $_IooIi = "HTML";
    }

    $_Jf16J = "";
    if(!empty($_IJfIf))
      $_Jf16J = strtolower(GetHTMLCharSet($_IJfIf));
    
    if(strtolower($_J6LL8) == "us-ascii" && IsUtf8String($_J6LOL)){ // Apple Mail?
       $_J6LL8 = "utf-8";
    }

    if( strtolower($_J6l6i) == "us-ascii" && IsUtf8String($_IJfIf) || $_Jf16J == "utf-8" ){ // Apple Mail?
       $_J6l6i = "utf-8";
       $_Jf16J = "utf-8";
    }

    if( in_arrayi(strtolower($_J6l6i), $_Jjl11) ){ 
      if(!IsUtf8String($_IJfIf))
        $_J6l6i = "iso-8859-1";
        else
        $_J6l6i = "utf-8";
    }  
    if( in_arrayi(strtolower($_J6LL8), $_Jjl11) ){ 
      if(!IsUtf8String($_J6LOL))
        $_J6LL8 = "iso-8859-1";
        else
        $_J6LL8 = "utf-8";
    }  

    // when html defined charset <> defined part charset
    $_J6l6i = strtolower($_J6l6i);
    if($_Jf16J != "" && $_Jf16J != $_J6l6i){
      
        if( !in_arrayi($_Jf16J, $_Jjl11) ){ 
           $_J6l6i = $_Jf16J;
        }else
          $_IJfIf = SetHTMLCharSet($_IJfIf, $_J6l6i);
    }
    
    // replace inline images with local names
    if(!empty($_IJfIf) && count($_J6llt)){
      $_Jf1C8 = _LPC1C($_IJi8f);
      $_Jf1C8 = BasePath.substr($_Jf1C8, strlen(InstallPath));
      for($_Qli6J=0; $_Qli6J<count($_J6llt); $_Qli6J++){
        $_JfQ81 = 0;
        $_IJfIf = str_replace("cid:".$_J6llt[$_Qli6J]["cid"], $_Jf1C8.$_J6llt[$_Qli6J]["inlineimage"], $_IJfIf, $_JfQ81);
        if($_JfQ81)
          $_J6llt[$_Qli6J]["used"] = true;
        $_IJfIf = str_replace("\"".$_J6llt[$_Qli6J]["cid"]."\"", "\"".$_Jf1C8.$_J6llt[$_Qli6J]["inlineimage"]."\"", $_IJfIf, $_JfQ81);
        if($_JfQ81)
          $_J6llt[$_Qli6J]["used"] = true;
      }
    }

    // add unused images as attachment
    $_Jf1C8 = _LPC1C($_IJi8f);
    for($_Qli6J=0; $_Qli6J<count($_J6llt); $_Qli6J++){
     if(!$_J6llt[$_Qli6J]["used"]){
       $_JfIIf = $_J6llt[$_Qli6J]["inlineimage"];
       $_JfIIf = GetUniqueFileNameInPath($_IIlfi, $_JfIIf);

       if(rename($_Jf1C8.$_J6llt[$_Qli6J]["inlineimage"], _LPC1C($_IIlfi).$_JfIIf)){
        $attachments[] = $_JfIIf;
       }
       else if(copy($_Jf1C8.$_J6llt[$_Qli6J]["inlineimage"], _LPC1C($_IIlfi).$_JfIIf)){
        $attachments[] = $_JfIIf;
        @unlink($_Jf1C8.$_J6llt[$_Qli6J]["inlineimage"]);
       }
       else
        $attachments[] = $_J6llt[$_Qli6J]["inlineimage"]; // this will give an error while sending, file not exists
     }
    }

    // utf-8 encoding for saving in db
    if(!empty($_IJfIf)) {
      $_IJfIf = CleanUpHTML($_IJfIf);
      $_IJfIf = ConvertString($_J6l6i, $_QLo06, $_IJfIf, true);

    }
    if(!empty($_J6LOL))
      $_J6LOL = ConvertString($_J6LL8, $_QLo06, $_J6LOL, false);

    /// email encoding
    if(!empty($_J6l6i)) {
        $_Ioolt = strtolower($_J6l6i);
        $_IJfIf = SetHTMLCharSet($_IJfIf, $_Ioolt, false);
      }
      else
      if(!empty($_J6LL8))
        $_Ioolt = strtolower($_J6LL8);

    // Priority
    if( isset($_J6t80["headers"]["x-priority"]) ){
      if( $_J6t80["headers"]["x-priority"] == 1 )
        $_jLtII = "High";
      if( $_J6t80["headers"]["x-priority"] == 5 )
        $_jLtII = "Low";
    }

    $_J6LOL = _LC6CP($_J6LOL);
    $_IJfIf = _LC6CP($_IJfIf);
    
    mysql_query("BEGIN", $_QLttI);

    $_QLfol = "INSERT INTO `$_IjCfJ` SET `DistribList_id`=$_j0i6j[DistribLists_id], `CreateDate`=NOW(), ";
    $_QLfol .= "`DistribSenderEMailAddress`="._LRAFO($_IL8oI).", ";
    $_QLfol .= "`DistribSenderFromToCC`="._LRAFO( serialize($_J6i6i) ).", ";
    $_QLfol .= "`SendConfirmationString`="._LRAFO($_J6ioj).", ";

    if($_j0i6j["SendConfirmationRequired"] > 0)
      $_QLfol .= "`SendScheduler`="._LRAFO("ConfirmationPending").", ";
      else
      $_QLfol .= "`SendScheduler`="._LRAFO("SendImmediately").", ";

    if($_j0i6j["OverwriteSenderAddress"]) {
      $_QLfol .= "`SenderFromName`="._LRAFO( !empty($_j0i6j["SenderFromName"]) ? $_j0i6j["SenderFromName"] : str_replace("&amp;", "&", htmlspecialchars( _LC6CP($_ILfoj), ENT_COMPAT, $_QLo06, false)) ).", ";
      $_QLfol .= "`SenderFromAddress`="._LRAFO( !empty($_j0i6j["SenderFromAddress"]) ? $_j0i6j["SenderFromAddress"] : $_IL8oI ).", ";
      $_QLfol .= "`ReplyToEMailAddress`="._LRAFO( !empty($_j0i6j["ReplyToEMailAddress"]) ? $_j0i6j["ReplyToEMailAddress"] : $_J6iji ).", ";
    } else {
      $_QLfol .= "`SenderFromName`="._LRAFO( _LC6CP($_ILfoj) ).", ";
      $_QLfol .= "`SenderFromAddress`="._LRAFO( $_IL8oI ).", ";
      $_QLfol .= "`ReplyToEMailAddress`="._LRAFO( $_J6iji ).", ";
    }

    $_QLfol .= "`ReturnPathEMailAddress`="._LRAFO($_j0i6j["ReturnPathEMailAddress"]).", ";
    $_QLfol .= "`CcEMailAddresses`="._LRAFO($_j0i6j["CcEMailAddresses"]).", ";
    $_QLfol .= "`BCcEMailAddresses`="._LRAFO($_j0i6j["BCcEMailAddresses"]).", ";
    $_QLfol .= "`ReturnReceipt`="._LRAFO($_j0i6j["ReturnReceipt"]).", ";

    $_QLfol .= "`MailFormat`="._LRAFO($_IooIi).", ";
    $_QLfol .= "`MailPriority`="._LRAFO($_jLtII).", ";
    $_QLfol .= "`MailEncoding`="._LRAFO($_Ioolt).", ";

    $_QLfol .= "`MailSubject`="._LRAFO( _LC6CP( str_replace("&amp;", "&", htmlspecialchars($_IoOif, ENT_COMPAT, $_QLo06, false)) ) ).", ";
    $_QLfol .= "`MailPlainText`="._LRAFO($_J6LOL).", ";
    $_QLfol .= "`MailHTMLText`="._LRAFO($_IJfIf).", ";

    $_QLfol .= "`Attachments`="._LRAFO(serialize($attachments));

    mysql_query($_QLfol, $_QLttI);

    if(mysql_error($_QLttI) != ""){
      $_J0COJ = "Error while inserting new distribution list entry: ".mysql_error($_QLttI);
      $_J68tI = true;
      mysql_query("ROLLBACK", $_QLttI);
      return false;
    }

    $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_JfICO = $_QLO0f[0];
    mysql_free_result($_QL8i1);

    // fix encoding errors in `MailPlainText`, `MailHTMLText`
    $_QLfol = "SELECT * FROM `$_IjCfJ` WHERE `id`=$_JfICO";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_JfILO = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if($_JfILO["MailPlainText"] != $_J6LOL || $_JfILO["MailHTMLText"] != $_IJfIf){
      $_QLfol = "UPDATE `$_IjCfJ` SET  ";
      $_QLfol .= "`MailPlainText`="._LRAFO( "xb64".base64_encode($_J6LOL) ).", ";
      $_QLfol .= "`MailHTMLText`="._LRAFO( "xb64".base64_encode($_IJfIf) );
      $_QLfol .= " WHERE id=$_JfICO";
      mysql_query($_QLfol, $_QLttI);
      $_JfILO["MailPlainText"] = $_J6LOL;
      $_JfILO["MailHTMLText"] = $_IJfIf;
    }


    // Tracking?
    if($_IJfIf != "" && ($_j0i6j["TrackLinks"] || $_j0i6j["TrackLinksByRecipient"]) ){
       $_IjQI8 = array();
       $_IjQCO = array();
       _LAL0C($_IJfIf, $_IjQI8, $_IjQCO);

       # Add links
       for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++) {
         if(strpos($_IjQI8[$_Qli6J], $_Ij08l["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
         // Phishing?
         if( strpos($_IjQCO[$_Qli6J], "http://") !== false && strpos($_IjQCO[$_Qli6J], "http://") == 0 ) continue;
         if( strpos($_IjQCO[$_Qli6J], "https://") !== false && strpos($_IjQCO[$_Qli6J], "https://") == 0 ) continue;
         if( strpos($_IjQCO[$_Qli6J], "www.") !== false && strpos($_IjQCO[$_Qli6J], "www.") == 0 ) continue;
         $_IJ81i = 1;
         if(strpos($_IjQI8[$_Qli6J], "[") !== false)
            $_IJ81i = 0;

         $_QLfol = "SELECT `id` FROM `$_j0i6j[LinksTableName]` WHERE `distriblistentry_id`=$_JfICO AND `Link`="._LRAFO($_IjQI8[$_Qli6J]);
         $_IJ8QC = mysql_query($_QLfol, $_QLttI);
         if( mysql_num_rows($_IJ8QC) > 0 ) {
           mysql_free_result($_IJ8QC);
         } else {

           $_IjQCO[$_Qli6J] = preg_replace("/\&(?!\#)/", " ", $_IjQCO[$_Qli6J]); // replaces & with " ", but not for emojis!
           //$_IjQCO[$_Qli6J] = str_replace("&", " ", $_IjQCO[$_Qli6J]);
           $_IjQCO[$_Qli6J] = str_replace("\r\n", " ", $_IjQCO[$_Qli6J]);
           $_IjQCO[$_Qli6J] = str_replace("\r", " ", $_IjQCO[$_Qli6J]);
           $_IjQCO[$_Qli6J] = str_replace("\n", " ", $_IjQCO[$_Qli6J]);

           $_QLfol = "INSERT INTO `$_j0i6j[LinksTableName]` SET `distriblistentry_id`=$_JfICO, `IsActive`=$_IJ81i, `Link`="._LRAFO($_IjQI8[$_Qli6J]).", `Description`="._LRAFO( preg_replace("/\&(?!\#)/", " ", $_IjQCO[$_Qli6J]) );
           mysql_query($_QLfol, $_QLttI);
         }
       }
    }

    mysql_query("COMMIT", $_QLttI);

    if($_j0i6j["SendConfirmationRequired"] > 0){

     $_QLfol = "SELECT `mtas_id` FROM `$_j0i6j[MTAsTableName]` ORDER BY `sortorder`";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f=mysql_fetch_row($_QL8i1)){
        $_IotL0 = $_QLO0f[0];
        $_QLfol = "SELECT * FROM `$_Ijt0i` WHERE id=$_IotL0";
        $_j0L0O = mysql_query($_QLfol, $_QLttI);
        $_j0iLj = mysql_fetch_assoc($_j0L0O);
        mysql_free_result($_j0L0O);
     }
      else
        $_IotL0 = 0;
     mysql_free_result($_QL8i1);

     $_j0i6j = array_merge($_j0i6j, $_JfILO);

     $_j0Lif = "";
     $_j0lfj = "";
     if(!defined("DistribListConfirmationNoEMLAttachment")){
       $_j10IJ = new _LEFO8(mtInternalMail);
       $_j10IJ->DisableECGListCheck();
       $_j108i = "";
       $_j10O1 = "";
       $errors = array();
       $_I816i = array();
       $_j0i6j["TrackLinks"] = 0;
       $_j0i6j["TrackLinksByRecipient"] = 0;
       $_j0i6j["TrackEMailOpenings"] = 0;
       $_j0i6j["TrackEMailOpeningsByRecipient"] = 0;
       $_j0i6j["OverwriteSenderAddress"] = 0;
       $_j0i6j["AddSignature"] = 0;
       $_j0i6j["MailPreHeaderText"] = "";
       $_j0i6j["ModifiedEMailSubject"] = "";
       
       // remove this, can be placeholders inside
       if(isset($_j0i6j["CcEMailAddresses"]))
         unset($_j0i6j["CcEMailAddresses"]);
       if(isset($_j0i6j["BCcEMailAddresses"]))
         unset($_j0i6j["BCcEMailAddresses"]);

       $_j11Io = array("PersonalizeEMails" => 0, "u_EMail" => $_j0i6j["DistribSenderEMailAddress"], "u_FirstName" => "", "u_LastName" => "");
       if(_LEJE8($_j10IJ, $_j108i, $_j10O1, true, array_merge($_j0i6j, $_j0iLj, array("Type" => "text")), $_j11Io, $_j0i6j["maillists_id"], $_j0i6j["forms_id"], $_j0i6j["forms_id"], $errors, $_I816i, array(), $_j0i6j["DistribLists_id"], "DistributionList")){
         $_j10IJ->Sendvariant = "text";
         $_j0Lif = $_j10IJ->_LE6A8($_j108i, $_j10O1);
         $_j0lfj = _LPDDC( _LCRC8($_j0i6j["MailSubject"]) ) . ".eml";
       }
       unset($_j10IJ);
       $_j10IJ = null;
     }

     $_j1IQL = $_j0i6j["DistribListConfirmationLinkMailPlainText"];
     $_ILi8o = $_j0i6j["DistribListConfirmationLinkMailSubject"];

     if(empty($_ILi8o))
       $_ILi8o = $resourcestrings[$INTERFACE_LANGUAGE]["002680"];

     if(empty($_j1IQL)) {
       $_j1IQL = join("", file(_LOC8P()."distriblist_confirm.txt"));
       if(!IsUtf8String($_j1IQL))
         $_j1IQL = utf8_encode($_j1IQL);
     }
     $_j1IQL = str_replace('[DISTRIBLISTNAME]', $_j0i6j["DistribLists_Name"], $_j1IQL);
     $_j1IQL = str_replace('[SUBJECT]', $_IoOif, $_j1IQL);
     $_j1IQL = str_replace('[FROMADDRESS]', $_IL8oI, $_j1IQL);
     
     $_j1IIf = "";
     $_QLfol = "SELECT `OverrideSubUnsubURL` FROM $_j0i6j[FormsTableName] WHERE id=" . $_j0i6j["forms_id"];
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1 && $_I1OfI = mysql_fetch_row($_QL8i1)){
       $_j1IIf = $_I1OfI[0];
     }
     if($_QL8i1)
       mysql_free_result($_QL8i1);
     
     $_j1IO0 = (empty($_j1IIf) ? ScriptBaseURL : $_j1IIf) . "distriblistconfirm.php?entry=".rawurlencode(dechex($UserId)."_".$_J6ioj);
     $_j1IQL = str_replace('[CONFIRMATIONLINK]', $_j1IO0, $_j1IQL);


     $_ILi8o = str_replace('[DISTRIBLISTNAME]', $_j0i6j["DistribLists_Name"], $_ILi8o);
     $_ILi8o = str_replace('[SUBJECT]', $_IoOif, $_ILi8o);
     $_ILi8o = str_replace('[FROMADDRESS]', $_IL8oI, $_ILi8o);

     if($_j0i6j["SendConfirmationRequired"] == 1) {
       if(!_L6R1E($_j1QJf, $_IL8oI, $_ILi8o, $_j1IQL, $_QLo06, $_IotL0, $_j0Lif, $_j0lfj)) {
         $_J0COJ = "Error while sending email with confirmation link to: $_IL8oI";
         $_J68tI = true;
         return false;
       }
     } else {

       if($_j0i6j["SendConfirmationRequired"] == 2)
         if(!_L6R1E($_j1QJf, $_j1QJf, $_ILi8o, $_j1IQL, $_QLo06, $_IotL0, $_j0Lif, $_j0lfj)) {
           $_J0COJ = "Error while sending email with confirmation link to: $_j1QJf";
           $_J68tI = true;
           return false;
         }

       if($_j0i6j["SendConfirmationRequired"] == 3)
         if(!_L6R1E($_j1QJf, $_j0i6j["SendConfirmationLinkToThisEMailAddresses"], $_ILi8o, $_j1IQL, $_QLo06, $_IotL0, $_j0Lif, $_j0lfj)) {
           $_J0COJ = "Error while sending email with confirmation link to: $_j0i6j[SendConfirmationLinkToThisEMailAddresses]";
           $_J68tI = true;
           return false;
         }

       if($_j0i6j["SendInfoMailToSender"]){

         $_j1IQL = $_j0i6j["DistribListSenderInfoMailPlainText"];
         $_ILi8o = $_j0i6j["DistribListSenderInfoMailSubject"];

         if(empty($_ILi8o))
           $_ILi8o = $resourcestrings[$INTERFACE_LANGUAGE]["002680"];

         if(empty($_j1IQL)){
           $_j1IQL = join("", file(_LOC8P()."distriblist_sender_info.txt"));
           if(!IsUtf8String($_j1IQL))
             $_j1IQL = utf8_encode($_j1IQL);
         }

         $_j1IQL = str_replace('[DISTRIBLISTNAME]', $_j0i6j["DistribLists_Name"], $_j1IQL);
         $_j1IQL = str_replace('[SUBJECT]', $_IoOif, $_j1IQL);
         $_j1IQL = str_replace('[FROMADDRESS]', $_IL8oI, $_j1IQL);

         $_ILi8o = str_replace('[DISTRIBLISTNAME]', $_j0i6j["DistribLists_Name"], $_ILi8o);
         $_ILi8o = str_replace('[SUBJECT]', $_IoOif, $_ILi8o);
         $_ILi8o = str_replace('[FROMADDRESS]', $_IL8oI, $_ILi8o);

         if(!_L6R1E($_j1QJf, $_IL8oI, $_ILi8o, $_j1IQL, $_QLo06, $_IotL0)) {
           $_J0COJ = "Error while sending email with info to: $_IL8oI";
           $_J68tI = true;
           return false;
         }
       }

     }

    }

    return true;
  }

  function _LL8LJ($_QLJfI){
    $_QlOjt = strpos($_QLJfI, '<');
    if($_QlOjt === false) return $_QLJfI;
    $_QLJfI = substr($_QLJfI, 1, strlen($_QLJfI) - 2);
    return $_QLJfI;
  }


  function _LLP0O($_J68ot, &$_JIfo0){
    global $_QLttI, $UserId, $_IQQot, $_IjC0Q, $_Ijt0i, $_IjCfJ;
    global $commonmsgHTMLMTANotFound;
    $_QLfol = "SELECT `$_J68ot[CurrentSendTableName]`.*,  `$_IjCfJ`.`MailSubject`,  `$_IjCfJ`.`DistribSenderEMailAddress` ";
    $_QLfol .= "FROM `$_J68ot[CurrentSendTableName]` LEFT JOIN `$_IjCfJ` ON `$_IjCfJ`.`id`=`$_J68ot[CurrentSendTableName]`.`distriblistentry_id` WHERE `SendState`<>'Done' AND `DistribEntrySendDone`<>0";

    $_J6QJI = mysql_query($_QLfol, $_QLttI);
    while($_J6QJI && $_IQjl0 = mysql_fetch_assoc($_J6QJI) ) {
       _LRCOC();

       # anything of distriblist in outqueue?
       $_QLfol = "SELECT COUNT(id) FROM `$_IQQot` WHERE `users_id`=$UserId AND `Source`='DistributionList' AND `Source_id`=$_J68ot[id] AND `Additional_id`=$_IQjl0[distriblistentry_id] AND `SendId`=$_IQjl0[id]";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       if($_jj6L6[0] > 0) continue; // not done?

       _LBOOC($_J68ot["MailingListId"], $UserId, $_IQjl0["distriblistentry_id"], 'DistributionList', $_J68ot["id"]);

       // Count things
       $_QLfol = "SELECT COUNT(id) FROM `$_J68ot[RStatisticsTableName]` WHERE SendStat_id=$_IQjl0[id] AND Send='Sent'";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       $_jlolf = $_jj6L6[0];

       $_QLfol = "SELECT COUNT(id) FROM `$_J68ot[RStatisticsTableName]` WHERE SendStat_id=$_IQjl0[id] AND Send='Failed'";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       $_jlCtf = $_jj6L6[0];

       $_QLfol = "SELECT COUNT(id) FROM `$_J68ot[RStatisticsTableName]` WHERE SendStat_id=$_IQjl0[id] AND Send='PossiblySent'";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       $_jj6L6 = mysql_fetch_row($_jjJfo);
       mysql_free_result($_jjJfo);
       $_jliJi = $_jj6L6[0];

       mysql_query("BEGIN", $_QLttI);

       // when resend from distriblistlog than EndSendDateTime <> 0
       $_QLfol = "UPDATE `$_J68ot[CurrentSendTableName]` SET EndSendDateTime=NOW() WHERE id=$_IQjl0[id] AND EndSendDateTime=0";
       mysql_query($_QLfol, $_QLttI);

       $_QLfol = "UPDATE `$_J68ot[CurrentSendTableName]` SET SentCountSucc=$_jlolf, SentCountFailed=$_jlCtf, SentCountPossiblySent=$_jliJi, SendState='Done', ReportSent=1 WHERE id=$_IQjl0[id]";
       mysql_query($_QLfol, $_QLttI);

       $_J68ot["ReportSent"] = $_IQjl0["ReportSent"];
       $_J68ot["MailSubject"] = $_IQjl0["MailSubject"];
       $_J68ot["DistribSenderEMailAddress"] = $_IQjl0["DistribSenderEMailAddress"];

       if( $_J68ot["SendReportToDistribSenderEMailAddress"] || $_J68ot["SendReportToYourSelf"] || $_J68ot["SendReportToListAdmin"] || $_J68ot["SendReportToMailingListUsers"] || $_J68ot["SendReportToEMailAddress"] ) {

         // MTA
         if(!isset($_J68ot["mtas_id"])) {
           $_QLfol = "SELECT mtas_id FROM `$_J68ot[MTAsTableName]` ORDER BY sortorder LIMIT 0, 1"; // only the first
           $_J6j0L = mysql_query($_QLfol, $_QLttI);
           if(!$_J6j0L || mysql_num_rows($_J6j0L) == 0) {
             $_JIfo0 .= $commonmsgHTMLMTANotFound;
             mysql_query("ROLLBACK", $_QLttI);
             continue;
           } else {
             $_J00C0 = mysql_fetch_assoc($_J6j0L);
             mysql_free_result($_J6j0L);
             $_J68ot["mtas_id"] = $_J00C0["mtas_id"];
           }
         }

         $_QLfol = "SELECT * FROM `$_Ijt0i` WHERE id=$_J68ot[mtas_id]";
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
          $_J68ot = array_merge($_J68ot, $_J00C0);
          // send report
          _LLPBR($_J68ot, $_IQjl0["id"]);
         }

       } # if( $_J68ot["SendReportToYourSelf"] || $_J68ot["SendReportToListAdmin"] || $_J68ot["SendReportToMailingListUsers"] || $_J68ot["SendReportToEMailAddress"] )

       mysql_query("COMMIT", $_QLttI);

    } # while( $_IQjl0 = mysql_fetch_assoc($_J6QJI) )
    if($_J6QJI)
      mysql_free_result($_J6QJI);
  }

  function _LLPBR($_jf6Qi, $_j01OI) {
      global $_QLttI, $_I18lo, $_QlQot, $_JQ1I6, $resourcestrings, $INTERFACE_LANGUAGE, $UserId, $_J1t6J, $_QLo06;
      global $_QLl1Q, $_QLo06;
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

        $_j60Q0=0;
        if($_jf6Qi["SendReportToEMailAddress"] && !empty($_jf6Qi["SendReportToEMailAddressEMailAddress"])) {
          $_J6jCt[$_j60Q0++] = array("address" => $_jf6Qi["SendReportToEMailAddressEMailAddress"], "name" => "");
        }
        if($_jf6Qi["SendReportToDistribSenderEMailAddress"] && !empty($_jf6Qi["DistribSenderEMailAddress"])) {
          $_J6jCt[$_j60Q0++] = array("address" => $_jf6Qi["DistribSenderEMailAddress"], "name" => "");
        }

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
             $_QLCt1 = false;
             foreach($_J6jCt as $key => $_QltJO){
               if( strtolower($_QltJO["address"]) == strtolower($_Il6l0["EMail"])) {
                 $_QLCt1 = true;
                 break;
               }
             }

             if(!$_QLCt1)
                $_J6jCt[$_j60Q0++] = array("address" => $_Il6l0["EMail"], "name" => trim($_Il6l0["FirstName"]." ".$_Il6l0["LastName"]));
          }
          mysql_free_result($_J6JOo);

        } // if(count($_jf8JI) > 0)

        if(count($_J6jCt) > 0) {

          // mail class
          $_j10IJ = new _LEFO8(mtInternalReportMail);
          $_j10IJ->DisableECGListCheck();

          $_j10IJ->_LEOPF();
          $_j10IJ->_LEQ1C();
          $_j10IJ->From[] = array("address" => $From["address"], "name" => $From["name"] );
          foreach($_J6jCt as $key => $_QltJO)
             $_j10IJ->To[] = array("address" => $_J6jCt[$key]["address"], "name" =>  $_J6jCt[$key]["name"]);

          _LRCOC();
          $_I1OoI = @file(_LOC8P()."distriblist_email_report.htm");
          if($_I1OoI)
            $_QLoli = join("", $_I1OoI);
            else
            $_QLoli = join("", file(InstallPath._LOC8P()."distriblist_email_report.htm"));

          $_J6616 = join("", @file(InstallPath."css/default.css"));
          if(!empty($_J6616)){
           $_J6616 = str_replace('../images/', ScriptBaseURL."images/", $_J6616);
           $_J6616 = '<style type="text/css">'.$_QLl1Q.$_J6616.$_QLl1Q.'</style>';
           $_QLoli = str_replace("</head>", $_J6616.$_QLl1Q."</head>", $_QLoli);
          }

          $_I1OoI = @file(_LOC8P()."distriblist_email_report.txt");
          if($_I1OoI)
            $_IO08l = join($_QLl1Q, $_I1OoI);
            else
            $_IO08l = join($_QLl1Q, file(InstallPath._LOC8P()."distriblist_email_report.txt"));
          $_IO08l = utf8_encode($_IO08l); // ANSI2UTF8

          $_j10IJ->Subject = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002681"], $_jf6Qi["DistribLists_Name"], unhtmlentities( $_jf6Qi["MailSubject"], $_QLo06) );
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
            $_J6t11 = $_QLO0f["id"];
            $_jloJI = $_QLO0f["RecipientsCount"];
            $_jlLL0 = $_QLO0f["StartSendDateTimeFormated"];
            $_jlLLO = $_QLO0f["EndSendDateTimeFormated"];
            $_jll1i = $_QLO0f["SendDuration"];
            $_jllQj = $_QLO0f["SendEstEndTime"];
            $_jlolf = $_QLO0f["SentCountSucc"];
            $_jlCtf = $_QLO0f["SentCountFailed"];
            $_jliJi = $_QLO0f["SentCountPossiblySent"];
            mysql_free_result($_J66fJ);
          }

          $_QLoli = str_replace('href="css/', 'href="'.ScriptBaseURL.'css/', $_QLoli);

          // html
          $_QLoli = _L81BJ($_QLoli, "<DISTRIBLIST_NAME>", "</DISTRIBLIST_NAME>", $_jf6Qi["DistribLists_Name"] );
          $_QLoli = _L81BJ($_QLoli, "<MAILSUBJECT>", "</MAILSUBJECT>", $_jf6Qi["MailSubject"] );
          $_QLoli = _L81BJ($_QLoli, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jloJI);
          $_QLoli = _L81BJ($_QLoli, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jlolf);
          $_QLoli = _L81BJ($_QLoli, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jlCtf);
          $_QLoli = _L81BJ($_QLoli, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jliJi);

          $_QLoli = _L81BJ($_QLoli, "<SENDING:START>", "</SENDING:START>", $_jlLL0);
          $_QLoli = _L81BJ($_QLoli, "<SENDING:END>", "</SENDING:END>", $_jlLLO);
          $_QLoli = _L81BJ($_QLoli, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jll1i);
          $_QLoli = _L81BJ($_QLoli, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jllQj);

          // text
          $_IO08l = _L81BJ($_IO08l, "<DISTRIBLIST_NAME>", "</DISTRIBLIST_NAME>", unhtmlentities( $_jf6Qi["DistribLists_Name"], $_QLo06) );
          $_IO08l = _L81BJ($_IO08l, "<MAILSUBJECT>", "</MAILSUBJECT>", unhtmlentities( $_jf6Qi["MailSubject"], $_QLo06) );
          $_IO08l = _L81BJ($_IO08l, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jloJI);
          $_IO08l = _L81BJ($_IO08l, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jlolf);
          $_IO08l = _L81BJ($_IO08l, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jlCtf);
          $_IO08l = _L81BJ($_IO08l, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jliJi);

          $_IO08l = _L81BJ($_IO08l, "<SENDING:START>", "</SENDING:START>", $_jlLL0);
          $_IO08l = _L81BJ($_IO08l, "<SENDING:END>", "</SENDING:END>", $_jlLLO);
          $_IO08l = _L81BJ($_IO08l, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jll1i);
          $_IO08l = _L81BJ($_IO08l, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jllQj);

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

?>
