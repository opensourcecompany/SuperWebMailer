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

  include_once("inboxcheck.php");
  include_once("savedoptions.inc.php");
  include_once("distribliststools.inc.php");

  function _OROED(&$_j6O8O) {
    global $_Q61I1;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE, $_IfO6l;
    global $resourcestrings, $_Q8f1L, $_Q880O, $_Q6JJJ;
    global $_QolLi, $_QtjLI, $commonmsgHTMLMTANotFound;
    global $_Q60QL, $_QoOft, $_Qoo8o;
    global $_QOCJo, $_QCo6j;

    $_j6O8O = "Distribution list checking starts...<br /><br />";
    $_jt00L = false;
    $_jt0OI = false;

    $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE `UserType`='Admin' AND `IsActive`>0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
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

      $_QJlJ0 = "SELECT `Theme` FROM `$_Q880O` WHERE `id`=$INTERFACE_THEMESID";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_Q8OiJ[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);

      $_IfO6l = $_Q6Q1C["EMail"];

      $_QJlJ0 = "SELECT `$_QoOft`.*, `$_QoOft`.id AS DistribLists_id, `$_QoOft`.Name AS DistribLists_Name, `$_QoOft`.`LeaveMessagesInInbox` AS DistribListLeaveMessagesInInbox, ";
      $_QJlJ0 .= "`$_QolLi`.*, `$_QolLi`.`Name` AS InboxName, `$_QolLi`.`LeaveMessagesInInbox` AS InboxLeaveMessagesInInbox, `$_Q60QL`.`MaillistTableName` FROM `$_QoOft` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.`id`=`$_QoOft`.`maillists_id` LEFT JOIN `$_QolLi` ON `$_QoOft`.`inboxes_id`=`$_QolLi`.`id`";
      $_QJlJ0 .= " WHERE `$_QoOft`.`IsActive`=1";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);

      if(mysql_error($_Q61I1) != "") {
        $_j6O8O .= "MySQL error while selecting data: ".mysql_error($_Q61I1);
        $_jt0OI = true;
      }

      while(!$_jt0OI && $_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {

        if(trim($_Q8OiJ["DistribListSubjects"]) != "")
          $_Q8OiJ["DistribListSubjects"] = explode(";", $_Q8OiJ["DistribListSubjects"]);
          else
          $_Q8OiJ["DistribListSubjects"] = array();

        if(trim($_Q8OiJ["AcceptSenderEMailAddresses"]) != "")
          $_Q8OiJ["AcceptSenderEMailAddresses"] = explode($_Q6JJJ, $_Q8OiJ["AcceptSenderEMailAddresses"]);
          else
          $_Q8OiJ["AcceptSenderEMailAddresses"] = array();

        _OPQ6J();

        $_j6O8O .= "checking distribution list $_Q8OiJ[DistribLists_Name] inbox: $_Q8OiJ[InboxName]...<br />";

        if(empty($_Q8OiJ["InboxName"]) || empty($_Q8OiJ["InboxType"])) continue; // database inconsistent

        $_jftQf = new _ODCLL();
        $_jftQf->Name = $_Q8OiJ["InboxName"];
        $_jftQf->InboxType = $_Q8OiJ["InboxType"]; // 'pop3', 'imap'
        $_jftQf->EMailAddress = $_Q8OiJ["EMailAddress"];
        $_jftQf->Servername = $_Q8OiJ["Servername"];
        $_jftQf->Serverport = $_Q8OiJ["Serverport"];
        $_jftQf->Username = $_Q8OiJ["Username"];
        $_jftQf->Password = $_Q8OiJ["Password"];
        $_jftQf->SSLConnection = $_Q8OiJ["SSL"];
        $_jftQf->LeaveMessagesInInbox = true; // remove after processing
        $LeaveMessagesInInbox = false;
        if($_Q8OiJ["DistribListLeaveMessagesInInbox"])
          $LeaveMessagesInInbox = true;
          else
          $LeaveMessagesInInbox = $_Q8OiJ["InboxLeaveMessagesInInbox"];
        $_jftQf->NumberOfEMailsToProcess = $_Q8OiJ["MaxEMailsToProcess"];
        $_jftQf->EntireMessage = true;
        $_jftQf->AttachmentsPath = $_QOCJo;
        $_jftQf->InlineImagesPath = $_QCo6j;

        if($_Q8OiJ["ResponderUIDL"] != "") {
             $_jftQf->UIDL = @unserialize($_Q8OiJ["ResponderUIDL"]);
             if($_jftQf->UIDL === false)
                $_jftQf->UIDL = array();
           }
           else
           $_jftQf->UIDL = array();

        if(isset($_IJ6Cf))
          unset($_IJ6Cf);

        $_jj0JO = "";
        $_jft86 = 0;
        $_jflIQ = 0;
        if(!$_jftQf->_ODA80($_jj0JO, $_IJ6Cf, $_jft86) ) {
           $_j6O8O .= "Error: ".$_jj0JO."; count of mails: ".$_jft86."<br />";
           $_jt0OI = true;

           // save UIDL
           $_QJlJ0 = "UPDATE $_QoOft SET ResponderUIDL="._OPQLR( serialize($_jftQf->UIDL) )." WHERE id=".$_Q8OiJ["DistribLists_id"];
           mysql_query($_QJlJ0, $_Q61I1);

        } else {

          $_j6O8O .= "Successfully; found new emails: ".$_jft86."; processable mails: ".count($_IJ6Cf)."<br />";
          $_jt1CC = array();
          if(count($_IJ6Cf) > 0) {
             for($_Q6llo=0; $_Q6llo<count($_IJ6Cf); $_Q6llo++) {
               _OPQ6J();
               $_jtQOQ = false;
               if( _ORLBL($_IJ6Cf[$_Q6llo], $_Q8OiJ, $Subject, $_jj0JO, $_jtQOQ) ) {
                  $_jt1CC[] = array("msg_id" => $_IJ6Cf[$_Q6llo]["Server_msg_id"], "uidl" => $_IJ6Cf[$_Q6llo]["Server_uidl"]);
                  $_j6O8O .= "mail with subject '$Subject' added for distribution<br />";
                  $_jflIQ++;
                  $_jt00L = $_jflIQ > 3; // more than 3, than we don't send anything at this script request
                 }
                 else {
                      if( $_jj0JO != "") {
                         $_j6O8O .= "error while processing mail with subject '$Subject', Error: $_jj0JO<br />";
                      }
                      if($_jtQOQ)
                        $_jt0OI = true;
                        else{
                          // ignore soft error e.g. wrong subject
                        }
                   }
             } // for $_Q6llo

             if(!$LeaveMessagesInInbox && count($_jt1CC)){
               if(!$_jftQf->deleteMessagesFromServer($_jt1CC, $_jj0JO)){
                    $_j6O8O .= "error while removing email from server, Error: $_jj0JO<br />";
               }
             }
          }

          // save processed emails and UIDL
          $_QJlJ0 = "UPDATE $_QoOft SET EMailsProcessed=EMailsProcessed + $_jflIQ, ResponderUIDL="._OPQLR( serialize($_jftQf->UIDL) )." WHERE id=".$_Q8OiJ["DistribLists_id"];
          mysql_query($_QJlJ0, $_Q61I1);

        }

        unset($_jftQf);

      }
      mysql_free_result($_Q8Oj8);

    }
    if($_Q60l1)
      mysql_free_result($_Q60l1);

    // Timing problems, sending it when there are no emails in inbox(es)
    if(!$_jt0OI && !$_jt00L){
       $_jIojl = 0;

       $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE `UserType`='Admin' AND `IsActive`>0";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
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

         $_QJlJ0 = "SELECT `Theme` FROM `$_Q880O` WHERE `id`=$INTERFACE_THEMESID";
         $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
         $INTERFACE_STYLE = $_Q8OiJ[0];
         mysql_free_result($_Q8Oj8);

         _OP0D0($_Q6Q1C);

         _OP0AF($UserId);


         // check sent distribution lists
         $_QJlJ0 = "SELECT `$_QoOft`.`id`, `$_QoOft`.`Name` AS `DistribLists_Name`, `$_QoOft`.Creator_users_id, `$_QoOft`.CurrentSendTableName, ";
         $_QJlJ0 .= "`$_QoOft`.RStatisticsTableName, `$_QoOft`.MTAsTableName, `$_QoOft`.maillists_id, ";
         $_QJlJ0 .= "`$_QoOft`.SendReportToDistribSenderEMailAddress, `$_QoOft`.SendReportToYourSelf, `$_QoOft`.SendReportToListAdmin, `$_QoOft`.SendReportToMailingListUsers, `$_QoOft`.SendReportToEMailAddress, `$_QoOft`.SendReportToEMailAddressEMailAddress, ";
         $_QJlJ0 .= "$_Q60QL.users_id, $_Q60QL.MaillistTableName, $_Q60QL.id AS MailingListId ";
         $_QJlJ0 .= " FROM `$_QoOft` LEFT JOIN $_Q60QL ON $_Q60QL.id=`$_QoOft`.maillists_id";
         $_QJlJ0 .= " WHERE (`$_QoOft`.`IsActive`=1)";
         $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
         while($_Q8Oj8 && $_jtIf8 = mysql_fetch_assoc($_Q8Oj8)) {
           _OPQ6J();

           _OR6LC($_jtIf8, $_j6O8O);

         } # while($_jtIf8 = mysql_fetch_assoc($_Q8Oj8))

         if($_Q8Oj8)
           mysql_free_result($_Q8Oj8);
         // check sent distribution lists done

         // check to send distribution lists

         // MailTextInfos
         $_QJlJ0 = "SELECT `$_QoOft`.*, `$_Qoo8o`.`id` AS `DistribListEntryId`, `$_Qoo8o`.`SendScheduler`, `$_Qoo8o`.`MailSubject`, `$_Qoo8o`.`DistribSenderEMailAddress`, ";
         $_QJlJ0 .= " $_Q60QL.MaillistTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.id AS MailingListId ";
         $_QJlJ0 .= " FROM `$_QoOft` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.`id`=`$_QoOft`.`maillists_id`";
         $_QJlJ0 .= " LEFT JOIN `$_Qoo8o` ON `$_Qoo8o`.`DistribList_id`=`$_QoOft`.`id`";
         $_QJlJ0 .= " WHERE (`$_QoOft`.`IsActive`=1 AND `$_Qoo8o`.`SendScheduler`='SendImmediately') ";

         $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);

         while($_Q8Oj8 && $_jtIf8 = mysql_fetch_assoc($_Q8Oj8)) {
           _OPQ6J();

           mysql_query("BEGIN", $_Q61I1);

           // we can't check this in sql above
           if( $_jtIf8["SendScheduler"] == 'SendImmediately' ) {
             $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_jtIf8[CurrentSendTableName]` WHERE `SendState`='Done' AND `distriblistentry_id`=$_jtIf8[DistribListEntryId]";
             $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
             $_Qt6f8 = mysql_fetch_row($_j8oC1);
             if($_Qt6f8[0] > 0) { // always sending done?
               mysql_free_result($_j8oC1);
               $_QJlJ0 = "UPDATE `$_Qoo8o` SET `SendScheduler`='Sent' WHERE `id`=$_jtIf8[DistribListEntryId]";
               mysql_query($_QJlJ0, $_Q61I1);
               if(mysql_affected_rows($_Q61I1))
                 $_j6O8O .= "<br />sending of distribution list $_jtIf8[Name] completed.<br />";
               mysql_query("COMMIT", $_Q61I1);
               continue;
             }
             mysql_free_result($_j8oC1);
           }

           $MailingListId = $_jtIf8["maillists_id"];
           $FormId = $_jtIf8["forms_id"];
           $_Q6t6j = $_jtIf8["GroupsTableName"];
           $_QlQC8 = $_jtIf8["MaillistTableName"];
           $_QLI68 = $_jtIf8["MailListToGroupsTableName"];
           $_ItCCo = $_jtIf8["LocalBlocklistTableName"];

           // CurrentSendTableName
           $_jQlit = 0;
           $_jtIOi = 0;
           $_jI0Oo = 0;
           $_jIQfo = 0;

           $_QJlJ0 = "SELECT * ";
           $_QJlJ0 .= "FROM `$_jtIf8[CurrentSendTableName]` WHERE `distriblistentry_id`=$_jtIf8[DistribListEntryId] AND `SendState`<>'Done' AND `SendState`<>'Paused'";

           $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
           if(mysql_num_rows($_j8oC1) > 0) {
             $_Qt6f8 = mysql_fetch_assoc($_j8oC1);
             mysql_free_result($_j8oC1);
             if($_Qt6f8["DistribEntrySendDone"]) { // distriblist always prepared completly?
               mysql_query("COMMIT", $_Q61I1);
               continue;
             }
             $_jQlit = $_Qt6f8["LastMember_id"];
             $_jtIOi = $_Qt6f8["id"];
             $_jI0Oo = $_Qt6f8["RecipientsCount"];
             $_jIQfo = $_Qt6f8["ReportSent"];
           } else{
             mysql_free_result($_j8oC1);

             // CurrentSendTableName
             $_QJlJ0 = "INSERT INTO `$_jtIf8[CurrentSendTableName]` SET `StartSendDateTime`=NOW(), `EndSendDateTime`=NOW(), `distriblistentry_id`=$_jtIf8[DistribListEntryId]";
             mysql_query($_QJlJ0, $_Q61I1);
             $_j8oC1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
             $_Qt6f8 = mysql_fetch_array($_j8oC1);
             $_jtIOi = $_Qt6f8[0];
             $_jtIf8["CurrentSendId"] = $_jtIOi;
             mysql_free_result($_j8oC1);

             // SET Current used MTAs to zero
             $_QJlJ0 = "INSERT INTO `$_jtIf8[CurrentUsedMTAsTableName]` SELECT 0, $_jtIf8[DistribListEntryId], $_jtIOi, `mtas_id`, 0 FROM `$_jtIf8[MTAsTableName]` ORDER BY sortorder";
             mysql_query($_QJlJ0, $_Q61I1);

           }

           // check all mtas_id are in CurrentUsedMTAsTableName
           $_QJlJ0 = "SELECT `$_jtIf8[MTAsTableName]`.mtas_id, `$_jtIf8[CurrentUsedMTAsTableName]`.mtas_id AS Usedmtas_id FROM `$_jtIf8[MTAsTableName]` LEFT JOIN `$_jtIf8[CurrentUsedMTAsTableName]` ON `$_jtIf8[CurrentUsedMTAsTableName]`.mtas_id = `$_jtIf8[MTAsTableName]`.mtas_id ";
           $_QJlJ0 .= "WHERE `$_jtIf8[CurrentUsedMTAsTableName]`.`SendStat_id`=$_jtIOi AND `$_jtIf8[CurrentUsedMTAsTableName]`.`distriblistentry_id`=$_jtIf8[DistribListEntryId] ORDER BY sortorder";
           $_j8Coj = mysql_query($_QJlJ0, $_Q61I1);
           if($_j8Coj)
             $_jI6Jo = mysql_num_rows($_j8Coj);
             else
             $_jI6Jo = 0;

           if(!$_j8Coj || $_jI6Jo == 0) {
             $_j6O8O .= $commonmsgHTMLMTANotFound;
             mysql_query("COMMIT", $_Q61I1);
             continue;
           }

           while($_j8i16 = mysql_fetch_assoc($_j8Coj)) {
             $_jIfO0 = $_j8i16; // save it
             if($_jIfO0["Usedmtas_id"] == "NULL") {
               $_QJlJ0 = "INSERT INTO `$_jtIf8[CurrentUsedMTAsTableName]` SET `distriblistentry_id`=$_jtIf8[DistribListEntryId], `SendStat_id`=$_jtIOi, `mtas_id`=$_jIfO0[mtas_id]";
               mysql_query($_QJlJ0, $_Q61I1);
             }
           }
           mysql_free_result($_j8Coj);

           # one MTA only than we can get data and merge it directly
           if($_jI6Jo == 1){
             $_jtIf8["mtas_id"] = $_jIfO0["mtas_id"];
           }

           if($_jI0Oo == 0) {
             $_jI0Oo = _O8R66($_jtIf8, $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
             mysql_query("UPDATE `$_jtIf8[CurrentSendTableName]` SET RecipientsCount=$_jI0Oo WHERE id=$_jtIOi", $_Q61I1);
           }

           $_QJlJ0 = _O8JPF($_jtIf8, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
           $_QJlJ0 .= " AND `$_QlQC8`.id>$_jQlit ORDER BY `$_QlQC8`.id LIMIT 0, $_jtIf8[MaxEMailsToSend]";

           $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);

           $_jflIQ = 0;
           if(mysql_num_rows($_IOOt1) > 0)
              $_j6O8O .= "<br />checking $_jtIf8[Name]...<br />";

           # is distriblist entry sending done?
           if(mysql_num_rows($_IOOt1) == 0) {
             $_QJlJ0 = "UPDATE `$_jtIf8[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `DistribEntrySendDone`=1 WHERE `id`=$_jtIOi";
             mysql_query($_QJlJ0, $_Q61I1);
           }

           while($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {

             // limit reached?
             if($_jflIQ >= $_jtIf8["MaxEMailsToSend"]) break;

             _OPQ6J();

             $_QJlJ0 = "INSERT INTO `$_jtIf8[RStatisticsTableName]` SET `distriblistentry_id`=$_jtIf8[DistribListEntryId], `SendStat_id`=$_jtIOi, `MailSubject`="._OPQLR($_jtIf8["MailSubject"]).", `SendDateTime`=NOW(), `recipients_id`=$_QlftL[id], `Send`='Prepared'";
             mysql_query($_QJlJ0, $_Q61I1);

             $_jtj8o = true;
             $_jfiol = 0;
             if(mysql_affected_rows($_Q61I1) > 0) {
               $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
               $_jfl1j = mysql_fetch_array($_jfLII);
               $_jfiol = $_jfl1j[0];
               mysql_free_result($_jfLII);
             } else {
               if(mysql_errno($_Q61I1) == 1062) {// dup key
                  $_QJlJ0 = "SELECT `id` FROM `$_jtIf8[RStatisticsTableName]` WHERE `distriblistentry_id`=$_jtIf8[DistribListEntryId] AND `SendStat_id`=$_jtIOi AND `recipients_id`=$_QlftL[id] AND `Send`='Prepared'";
                  $_jfLII = mysql_query($_QJlJ0, $_Q61I1);
                  $_jtj8o = false;
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
                 $_jIfO0 = _O88Q6($_jtIf8["CurrentUsedMTAsTableName"], $_jtIf8["MTAsTableName"], $_jtIOi, $_jtIf8["DistribListEntryId"]);
                 $_j8o0f["mtas_id"] = $_jIfO0["id"];
               }

               $_QJlJ0 = "INSERT INTO `$_QtjLI` SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='DistributionList', `Source_id`=$_jtIf8[id], `Additional_id`=$_jtIf8[DistribListEntryId], `SendId`=$_jtIOi, `maillists_id`=$_jtIf8[MailingListId], `recipients_id`=$_QlftL[id], `mtas_id`=$_jtIf8[mtas_id], `LastSending`=NOW() ";
               mysql_query($_QJlJ0, $_Q61I1);
               if(mysql_error($_Q61I1) != "") {
                 $_j6O8O .=  "MySQL error while adding mail to out queue: ".mysql_error($_Q61I1);
                 mysql_query("ROLLBACK", $_Q61I1);
                 continue;
               }

               $_jIojl++;
               if($_jtj8o)
                 $_jflIQ++;
             }

             //$_j6O8O .= "Email with subject '$_j8o0f[MailSubject]' was queued for sending to '$_QlftL[u_EMail]'<br />";

             # update last member id
             $_QJlJ0 = "UPDATE `$_jtIf8[CurrentSendTableName]` SET `EndSendDateTime`=NOW(), `LastMember_id`=$_QlftL[id] WHERE `id`=$_jtIOi";
             mysql_query($_QJlJ0, $_Q61I1);

           } # while($_QlftL = mysql_fetch_array($_IOOt1))
           if($_IOOt1)
             mysql_free_result($_IOOt1);

           if($_jflIQ > 0) {
              $_j6O8O .= "$_jtIf8[Name] checked, $_jflIQ email(s) with subject '".$_jtIf8["MailSubject"]."' sent to queue.<br />";
              // Update distribution list statistics, use $_jflIQ for this list
              $_QJlJ0 = "UPDATE $_QoOft SET EMailsSent=EMailsSent+$_jflIQ WHERE id=$_jtIf8[id]";
              mysql_query($_QJlJ0, $_Q61I1);
           }

           mysql_query("COMMIT", $_Q61I1);

         } # while($_jtIf8 = mysql_fetch_assoc($_Q8Oj8))
         if($_Q8Oj8)
           mysql_free_result($_Q8Oj8);
       } # while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) )


       $_j6O8O .= "<br />$_jIojl emails sent to queue<br />";
       if($_jIojl)
         $_jt00L = true;


    } // if(!$_jt0OI && !$_jt00L)

    $_j6O8O .= "<br />Distribution list checking end.";

    if($_jt0OI)
      return false;

    if($_jt00L)
      return true;
      else
      return -1;

  }

  function _ORLBL($_jtjL8, $_IftQ0, &$Subject, &$_jj0JO, &$_jtQOQ){
    global $_Q61I1, $UserId, $_IfO6l;
    global $_Q60QL, $_Qoo8o, $_Q6JJJ, $_Qofjo, $INTERFACE_LANGUAGE, $resourcestrings, $_Q6QQL, $_QOifL;
    global $_QOCJo, $_QCo6j;

    $_jj0JO = "";
    $_jtQOQ = false;

    if(!isset($_jtjL8["headers"]["subject"]))
      $_jtjL8["headers"]["subject"] = "";
    if(!isset($_jtjL8["headers"]["from"])) {
      $_jj0JO = "no from address found.";
      return false;
    }

    if($_IftQ0["IgnoreHeaderXLoop"]) {
      if( isset($_jtjL8["headers"]["x-loop"]) || isset($_jtjL8["headers"]["x-auto-response-suppress"]) || (isset($_jtjL8["headers"]["auto-submitted"]) && $_jtjL8["headers"]["auto-submitted"] == "auto-generated")  ) {
        $_jj0JO = "mail looping detected";
        return false;
      }
    }

    $charset = "";
    $_IQojt = $_jtjL8["headers"]["subject"];

    if(isset($_jtjL8["headers"]["content-type"])) {
      $charset = $_jtjL8["headers"]["content-type"];

      if(strpos($charset, "charset=") !== false)
        $charset = substr($charset, strpos($charset, "charset=") + 8);
      if(strpos($charset, ";") !== false)
        $charset = substr($charset, 0, strpos($charset, ";"));
      $charset = str_replace('"', '', $charset);
    }
    if(stripos($charset, "multipart/") !== false) {

      $charset = $_Q6QQL;
      $_Qfo8t = $_jtjL8["parts"];
      for($_Q6llo=0; $_Q6llo<count($_Qfo8t); $_Q6llo++){

         if( isset($_Qfo8t[$_Q6llo]["plaintext"]) && $_Qfo8t[$_Q6llo]["plaintext"]  ) {
           $charset  = $_Qfo8t[$_Q6llo]["charset"];
         }

         if( isset($_Qfo8t[$_Q6llo]["html"]) && $_Qfo8t[$_Q6llo]["html"] ) {
           $charset  = $_Qfo8t[$_Q6llo]["charset"];
         }

      }
    }

    if(strtolower($charset) == "us-ascii" && IsUtf8String($_IQojt)) // Apple Mail?
      $_IQojt = ConvertString("utf-8", $_Q6QQL, $_IQojt, false);
    else {
      if(strtolower($charset) == "us-ascii") // PHP doesn't support this
        $charset = "iso-8859-1";
      $_IQojt = ConvertString($charset, $_Q6QQL, $_IQojt, false);
    }

    if(strtolower($charset) == "us-ascii") // PHP doesn't support this
      $charset = "iso-8859-1";

    $_IQojt = str_replace("\r", "", $_IQojt);
    $_IQojt = str_replace("\n", "", $_IQojt);
    $Subject = $_IQojt;

    // security check
    if(count($_IftQ0["DistribListSubjects"]) > 0) {
      $_Qo1oC = false;
      for($_Q6llo=0; $_Q6llo<count($_IftQ0["DistribListSubjects"]); $_Q6llo++){
        if(stripos($Subject, $_IftQ0["DistribListSubjects"][$_Q6llo]) !== false) {
          $_Qo1oC = true;
          break;
        }
      }
      if(!$_Qo1oC) {
        $_jj0JO = "required mail subject not found";
        return false;
      }
    }

    $_IJ8oI = new Mail_RFC822();
    $From = $_jtjL8["headers"]["from"];
    $_IJo16 = "";
    $_IJO8j = $_IJ8oI->parseAddressList($From, null, null, false); // no ASCII check

    if ( !(IsPEARError($_IJO8j)) && isset($_IJO8j[0]->mailbox) && isset($_IJO8j[0]->host) ) {
      $_IJoII = $_IJO8j[0]->mailbox."@".$_IJO8j[0]->host;

      if(isset($_IJO8j[0]->personal))
        $_IJo16 = $_IJO8j[0]->personal;

    } else {
      $_jj0JO = "invalid from address.";
      return false;
    }

    $_jtJjL="";
    if(!empty($_jtjL8["headers"]["reply-to"])) {
      $_jtJjL = trim($_jtjL8["headers"]["reply-to"]);
      $_IJO8j = $_IJ8oI->parseAddressList($_jtJjL, null, null, false); // no ASCII check
      if ( !(IsPEARError($_IJO8j)) && isset($_IJO8j[0]->mailbox) && isset($_IJO8j[0]->host) ) {
        $_jtJjL = trim($_IJO8j[0]->mailbox."@".$_IJO8j[0]->host);
      }
    }

    unset($_IJ8oI);
    $_IJo16 = ConvertString($charset, $_Q6QQL, $_IJo16, false);
    $_IJo16 = str_replace('"', '', $_IJo16);
    $_IJo16 = trim(str_replace("'", '', $_IJo16));
    $_IJo16 = str_replace("\r", "", $_IJo16);
    $_IJo16 = str_replace("\n", "", $_IJo16);

    $_IJoII = trim(strtolower($_IJoII));
    $_IJoII = str_replace("\r", "", $_IJoII);
    $_IJoII = str_replace("\n", "", $_IJoII);

    $_jtJjL = trim(strtolower($_jtJjL));
    $_jtJjL = str_replace("\r", "", $_jtJjL);
    $_jtJjL = str_replace("\n", "", $_jtJjL);

    if(!$_IftQ0["AcceptAllSenderEMailAddresses"]){
       $_Qo1oC = false;
       for($_Q6llo=0; $_Q6llo<count($_IftQ0["AcceptSenderEMailAddresses"]); $_Q6llo++){
         if($_IJoII == strtolower($_IftQ0["AcceptSenderEMailAddresses"][$_Q6llo])){
           $_Qo1oC = true;
           break;
         }
       }
       if(!$_Qo1oC){
         $_jj0JO = "email address '$_IJoII' isn't allowed to send emails to this distribution list";
         return false;
       }
    } else
     if($_IftQ0["AcceptAllSenderEMailAddresses"] == 2){
       $_QJlJ0 = "SELECT `id` FROM `$_IftQ0[MaillistTableName]` WHERE `u_EMail`="._OPQLR($_IJoII);
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_num_rows($_Q60l1) == 0){
         mysql_free_result($_Q60l1);
         $_jj0JO = "email address '$_IJoII' isn't allowed to send emails to this distribution list";
         return false;
       }
       mysql_free_result($_Q60l1);
     }

    $_jtJJ0 = "";
    if( $_IftQ0["SendConfirmationRequired"] > 0 ){
       mt_srand((double)microtime()*1000000);
       $_IflL6 = mt_rand(8, 16);

       for ($_Q6llo = 0; $_Q6llo < $_IflL6; $_Q6llo++) {
         do {
          $_QL8Q8 = chr(mt_rand(48, 122));
         } while ( ($_QL8Q8 == '`') || ($_QL8Q8 == "'") || ($_QL8Q8 == "+") || ($_QL8Q8 == '"') || ($_QL8Q8 == "%") || ($_QL8Q8 == "&") || ($_QL8Q8 == "*") || ($_QL8Q8 == "?") || ($_QL8Q8 == "\\") || ($_QL8Q8 == '/') || ($_QL8Q8 == '"') || ($_QL8Q8 == '~') || ($_QL8Q8 == '{') || ($_QL8Q8 == '}') || ($_QL8Q8 == '[') || ($_QL8Q8 == ']') || ($_QL8Q8 == '>') || ($_QL8Q8 == '<') || ($_QL8Q8 == '_')  || ($_QL8Q8 == '-') );
         $_jtJJ0 .= $_QL8Q8;
       }
    }

    $_j1CLL = "Normal";
    $_IQC1o = "PlainText";
    $_IQCoo = strtolower($_Q6QQL);
    $_jtJLC = "";
    $_QCJtj = "";
    $_jt6O1 = "";
    $_jt6Ci = "";
    $attachments = array();
    $_jt6Lo = array();
    $_Qfo8t = $_jtjL8["parts"];

    if( count($_Qfo8t) == 0){
      if( stripos($_jtjL8["content_type"], "plain") === false )
        $_IQC1o = "HTML";
      $_jtJLC = $_jtjL8["body"];
      $_jt6O1  = $_jtjL8["charset"];
      if($_IQC1o == "HTML") { // plain html
        $_QCJtj = $_jtjL8["body"];
        $_jt6Ci = $_jtjL8["charset"];
        if(strtolower($_jt6Ci) == "us-ascii")
          $_jt6Ci = "iso-8859-1";

        $_jtJLC = _ODQAB($_jtJLC, $_jt6Ci);
      }
    } else{
      for($_Q6llo=0; $_Q6llo<count($_Qfo8t); $_Q6llo++){

        if( isset($_Qfo8t[$_Q6llo]["plaintext"]) && $_Qfo8t[$_Q6llo]["plaintext"]  ) {
          $_jtJLC = $_Qfo8t[$_Q6llo]["body"];
          $_jt6O1  = $_Qfo8t[$_Q6llo]["charset"];
        }

        if( isset($_Qfo8t[$_Q6llo]["html"]) && $_Qfo8t[$_Q6llo]["html"] ) {
          $_QCJtj = $_Qfo8t[$_Q6llo]["body"];
          $_jt6Ci  = $_Qfo8t[$_Q6llo]["charset"];
        }

        if( isset($_Qfo8t[$_Q6llo]["attachment"]) ) {
          $attachments[] = $_Qfo8t[$_Q6llo]["attachment"];
        }

        if( isset($_Qfo8t[$_Q6llo]["inlineimage"]) ) {
          $_jtfJI = _ORJRJ($_Qfo8t[$_Q6llo]["cid"]);
          $_jt6Lo[] = array( "inlineimage" => $_Qfo8t[$_Q6llo]["inlineimage"], "cid" => $_jtfJI, "used" => false );
        }

      }

      if(!empty($_jtJLC) && !empty($_QCJtj))
        $_IQC1o = "Multipart";
        else
        if(!empty($_QCJtj))
          $_IQC1o = "HTML";
    }

    // PHP doesn't support us-ascii
    if(strtolower($_jt6Ci) == "us-ascii")
      $_jt6Ci = "iso-8859-1";
    if(strtolower($_jt6O1) == "us-ascii")
      $_jt6O1 = "iso-8859-1";

    // replace inline images with local names
    if(!empty($_QCJtj) && count($_jt6Lo)){
      $_jt8IL = _OBLDR($_QCo6j);
      $_jt8IL = BasePath.substr($_jt8IL, strlen(InstallPath));
      for($_Q6llo=0; $_Q6llo<count($_jt6Lo); $_Q6llo++){
        $_jt8f1 = 0;
        $_QCJtj = str_replace("cid:".$_jt6Lo[$_Q6llo]["cid"], $_jt8IL.$_jt6Lo[$_Q6llo]["inlineimage"], $_QCJtj, $_jt8f1);
        if($_jt8f1)
          $_jt6Lo[$_Q6llo]["used"] = true;
        $_QCJtj = str_replace("\"".$_jt6Lo[$_Q6llo]["cid"]."\"", "\"".$_jt8IL.$_jt6Lo[$_Q6llo]["inlineimage"]."\"", $_QCJtj, $_jt8f1);
        if($_jt8f1)
          $_jt6Lo[$_Q6llo]["used"] = true;
      }
    }

    // add unused images as attachment
    $_jt8IL = _OBLDR($_QCo6j);
    for($_Q6llo=0; $_Q6llo<count($_jt6Lo); $_Q6llo++){
     if(!$_jt6Lo[$_Q6llo]["used"]){
       $_jt8LJ = $_jt6Lo[$_Q6llo]["inlineimage"];
       $_jt8LJ = _OBRJD($_QOCJo, $_jt8LJ);

       if(rename($_jt8IL.$_jt6Lo[$_Q6llo]["inlineimage"], _OBLDR($_QOCJo).$_jt8LJ)){
        $attachments[] = $_jt8LJ;
       }
       else if(copy($_jt8IL.$_jt6Lo[$_Q6llo]["inlineimage"], _OBLDR($_QOCJo).$_jt8LJ)){
        $attachments[] = $_jt8LJ;
        @unlink($_jt8IL.$_jt6Lo[$_Q6llo]["inlineimage"]);
       }
       else
        $attachments[] = $_jt6Lo[$_Q6llo]["inlineimage"]; // this will give an error while sending, file not exists
     }
    }

    // utf-8 encoding for saving in db
    if(!empty($_QCJtj)) {
      $_QCJtj = CleanUpHTML($_QCJtj);
      $_QCJtj = ConvertString($_jt6Ci, $_Q6QQL, $_QCJtj, true);

    }
    if(!empty($_jtJLC))
      $_jtJLC = ConvertString($_jt6O1, $_Q6QQL, $_jtJLC, false);

    /// email encoding
    if(!empty($_jt6Ci)) {
        $_IQCoo = strtolower($_jt6Ci);
        $_QCJtj = SetHTMLCharSet($_QCJtj, $_IQCoo, false);
      }
      else
      if(!empty($_jt6O1))
        $_IQCoo = strtolower($_jt6O1);

    // Priority
    if( isset($_jtjL8["headers"]["x-priority"]) ){
      if( $_jtjL8["headers"]["x-priority"] == 1 )
        $_j1CLL = "High";
      if( $_jtjL8["headers"]["x-priority"] == 5 )
        $_j1CLL = "Low";
    }

    mysql_query("BEGIN", $_Q61I1);

    $_QJlJ0 = "INSERT INTO `$_Qoo8o` SET `DistribList_id`=$_IftQ0[DistribLists_id], `CreateDate`=NOW(), ";
    $_QJlJ0 .= "`DistribSenderEMailAddress`="._OPQLR($_IJoII).", ";
    $_QJlJ0 .= "`SendConfirmationString`="._OPQLR($_jtJJ0).", ";

    if($_IftQ0["SendConfirmationRequired"] > 0)
      $_QJlJ0 .= "`SendScheduler`="._OPQLR("ConfirmationPending").", ";
      else
      $_QJlJ0 .= "`SendScheduler`="._OPQLR("SendImmediately").", ";

    if($_IftQ0["OverwriteSenderAddress"]) {
      $_QJlJ0 .= "`SenderFromName`="._OPQLR($_IftQ0["SenderFromName"]).", ";
      $_QJlJ0 .= "`SenderFromAddress`="._OPQLR($_IftQ0["SenderFromAddress"]).", ";
      $_QJlJ0 .= "`ReplyToEMailAddress`="._OPQLR($_IftQ0["ReplyToEMailAddress"]).", ";
    } else {
      $_QJlJ0 .= "`SenderFromName`="._OPQLR( $_IJo16 ).", ";
      $_QJlJ0 .= "`SenderFromAddress`="._OPQLR( $_IJoII ).", ";
      $_QJlJ0 .= "`ReplyToEMailAddress`="._OPQLR( $_jtJjL ).", ";
    }

    $_QJlJ0 .= "`ReturnPathEMailAddress`="._OPQLR($_IftQ0["ReturnPathEMailAddress"]).", ";
    $_QJlJ0 .= "`CcEMailAddresses`="._OPQLR($_IftQ0["CcEMailAddresses"]).", ";
    $_QJlJ0 .= "`BCcEMailAddresses`="._OPQLR($_IftQ0["BCcEMailAddresses"]).", ";
    $_QJlJ0 .= "`ReturnReceipt`="._OPQLR($_IftQ0["ReturnReceipt"]).", ";

    $_QJlJ0 .= "`MailFormat`="._OPQLR($_IQC1o).", ";
    $_QJlJ0 .= "`MailPriority`="._OPQLR($_j1CLL).", ";
    $_QJlJ0 .= "`MailEncoding`="._OPQLR($_IQCoo).", ";

    $_QJlJ0 .= "`MailSubject`="._OPQLR( $_IQojt ).", ";
    $_QJlJ0 .= "`MailPlainText`="._OPQLR($_jtJLC).", ";
    $_QJlJ0 .= "`MailHTMLText`="._OPQLR($_QCJtj).", ";

    $_QJlJ0 .= "`Attachments`="._OPQLR(serialize($attachments));

    mysql_query($_QJlJ0, $_Q61I1);

    if(mysql_error($_Q61I1) != ""){
      $_jj0JO = "Error while inserting new distribution list entry: ".mysql_error($_Q61I1);
      $_jtQOQ = true;
      mysql_query("ROLLBACK", $_Q61I1);
      return false;
    }

    $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_jt8lt = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);

    // fix encoding errors
    $_QJlJ0 = "SELECT `MailPlainText`, `MailHTMLText` FROM `$_Qoo8o` WHERE `id`=$_jt8lt";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if($_Q6Q1C["MailPlainText"] != $_jtJLC || $_Q6Q1C["MailHTMLText"] != $_QCJtj){
      $_QJlJ0 = "UPDATE `$_Qoo8o` SET  ";
      $_QJlJ0 .= "`MailPlainText`="._OPQLR( "xb64".base64_encode($_jtJLC) ).", ";
      $_QJlJ0 .= "`MailHTMLText`="._OPQLR( "xb64".base64_encode($_QCJtj) );
      $_QJlJ0 .= " WHERE id=$_jt8lt";
      mysql_query($_QJlJ0, $_Q61I1);
    }


    // Tracking?
    if($_QCJtj != "" && ($_IftQ0["TrackLinks"] || $_IftQ0["TrackLinksByRecipient"]) ){
       $_QOLIl = array();
       $_QOLCo = array();
       _OBBPD($_QCJtj, $_QOLIl, $_QOLCo);

       # Add links
       for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++) {
         if(strpos($_QOLIl[$_Q6llo], $_QOifL["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
         // Phishing?
         if( strpos($_QOLCo[$_Q6llo], "http://") !== false && strpos($_QOLCo[$_Q6llo], "http://") == 0 ) continue;
         if( strpos($_QOLCo[$_Q6llo], "https://") !== false && strpos($_QOLCo[$_Q6llo], "https://") == 0 ) continue;
         if( strpos($_QOLCo[$_Q6llo], "www.") !== false && strpos($_QOLCo[$_Q6llo], "www.") == 0 ) continue;
         $_QC6IO = 1;
         if(strpos($_QOLIl[$_Q6llo], "[") !== false)
            $_QC6IO = 0;

         $_QJlJ0 = "SELECT `id` FROM `$_IftQ0[LinksTableName]` WHERE `distriblistentry_id`=$_jt8lt AND `Link`="._OPQLR($_QOLIl[$_Q6llo]);
         $_QCfQJ = mysql_query($_QJlJ0, $_Q61I1);
         if( mysql_num_rows($_QCfQJ) > 0 ) {
           mysql_free_result($_QCfQJ);
         } else {

           $_QOLCo[$_Q6llo] = str_replace("&", " ", $_QOLCo[$_Q6llo]);
           $_QOLCo[$_Q6llo] = str_replace("\r\n", " ", $_QOLCo[$_Q6llo]);
           $_QOLCo[$_Q6llo] = str_replace("\r", " ", $_QOLCo[$_Q6llo]);
           $_QOLCo[$_Q6llo] = str_replace("\n", " ", $_QOLCo[$_Q6llo]);

           $_QJlJ0 = "INSERT INTO `$_IftQ0[LinksTableName]` SET `distriblistentry_id`=$_jt8lt, `IsActive`=$_QC6IO, `Link`="._OPQLR($_QOLIl[$_Q6llo]).", `Description`="._OPQLR(str_replace("&", " ", trim($_QOLCo[$_Q6llo])));
           mysql_query($_QJlJ0, $_Q61I1);
         }
       }
    }

    mysql_query("COMMIT", $_Q61I1);

    if($_IftQ0["SendConfirmationRequired"] > 0){
     $_IfO8i = $_IftQ0["DistribListConfirmationLinkMailPlainText"];
     $_I6016 = $_IftQ0["DistribListConfirmationLinkMailSubject"];

     if(empty($_I6016))
       $_I6016 = $resourcestrings[$INTERFACE_LANGUAGE]["002680"];

     if(empty($_IfO8i)) {
       $_IfO8i = join("", file(_O68QF()."distriblist_confirm.txt"));
       if(!IsUtf8String($_IfO8i))
         $_IfO8i = utf8_encode($_IfO8i);
     }
     $_IfO8i = str_replace('[DISTRIBLISTNAME]', $_IftQ0["DistribLists_Name"], $_IfO8i);
     $_IfO8i = str_replace('[SUBJECT]', $_IQojt, $_IfO8i);
     $_IfO8i = str_replace('[FROMADDRESS]', $_IJoII, $_IfO8i);

     $_IfOt1 = ScriptBaseURL."distriblistconfirm.php?entry=".rawurlencode(dechex($UserId)."_".$_jtJJ0);
     $_IfO8i = str_replace('[CONFIRMATIONLINK]', $_IfOt1, $_IfO8i);


     $_I6016 = str_replace('[DISTRIBLISTNAME]', $_IftQ0["DistribLists_Name"], $_I6016);
     $_I6016 = str_replace('[SUBJECT]', $_IQojt, $_I6016);
     $_I6016 = str_replace('[FROMADDRESS]', $_IJoII, $_I6016);

     $_QJlJ0 = "SELECT `mtas_id` FROM `$_IftQ0[MTAsTableName]` ORDER BY `sortorder`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C=mysql_fetch_row($_Q60l1))
        $_IQo0I = $_Q6Q1C[0];
        else
        $_IQo0I = 0;
     mysql_free_result($_Q60l1);


     if($_IftQ0["SendConfirmationRequired"] == 1) {
       if(!_O88P1($_IfO6l, $_IJoII, $_I6016, $_IfO8i, $_Q6QQL, $_IQo0I)) {
         $_jj0JO = "Error while sending email with confirmation link to: $_IJoII";
         $_jtQOQ = true;
         return false;
       }
     } else {

       if(!_O88P1($_IfO6l, $_IfO6l, $_I6016, $_IfO8i, $_Q6QQL, $_IQo0I)) {
         $_jj0JO = "Error while sending email with confirmation link to: $_IfO6l";
         $_jtQOQ = true;
         return false;
       }

       if($_IftQ0["SendInfoMailToSender"]){

         $_IfO8i = $_IftQ0["DistribListSenderInfoMailPlainText"];
         $_I6016 = $_IftQ0["DistribListSenderInfoMailSubject"];

         if(empty($_I6016))
           $_I6016 = $resourcestrings[$INTERFACE_LANGUAGE]["002680"];

         if(empty($_IfO8i)){
           $_IfO8i = join("", file(_O68QF()."distriblist_sender_info.txt"));
           if(!IsUtf8String($_IfO8i))
             $_IfO8i = utf8_encode($_IfO8i);
         }

         $_IfO8i = str_replace('[DISTRIBLISTNAME]', $_IftQ0["DistribLists_Name"], $_IfO8i);
         $_IfO8i = str_replace('[SUBJECT]', $_IQojt, $_IfO8i);
         $_IfO8i = str_replace('[FROMADDRESS]', $_IJoII, $_IfO8i);

         $_I6016 = str_replace('[DISTRIBLISTNAME]', $_IftQ0["DistribLists_Name"], $_I6016);
         $_I6016 = str_replace('[SUBJECT]', $_IQojt, $_I6016);
         $_I6016 = str_replace('[FROMADDRESS]', $_IJoII, $_I6016);

         if(!_O88P1($_IfO6l, $_IJoII, $_I6016, $_IfO8i, $_Q6QQL, $_IQo0I)) {
           $_jj0JO = "Error while sending email with info to: $_IJoII";
           $_jtQOQ = true;
           return false;
         }
       }

     }

    }

    return true;
  }

  function _ORJRJ($_QJCJi){
    $_Q6i6i = strpos($_QJCJi, '<');
    if($_Q6i6i === false) return $_QJCJi;
    $_QJCJi = substr($_QJCJi, 1, strlen($_QJCJi) - 2);
    return $_QJCJi;
  }


  function _OR6LC($_jtIf8, &$_j6O8O){
    global $_Q61I1, $UserId, $_QtjLI, $_QoOft, $_Qofoi, $_Qoo8o;
    global $commonmsgHTMLMTANotFound;
    $_QJlJ0 = "SELECT `$_jtIf8[CurrentSendTableName]`.*,  `$_Qoo8o`.`MailSubject`,  `$_Qoo8o`.`DistribSenderEMailAddress` ";
    $_QJlJ0 .= "FROM `$_jtIf8[CurrentSendTableName]` LEFT JOIN `$_Qoo8o` ON `$_Qoo8o`.`id`=`$_jtIf8[CurrentSendTableName]`.`distriblistentry_id` WHERE `SendState`<>'Done' AND `DistribEntrySendDone`<>0";

    $_j8oC1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_j8oC1 && $_Qt6f8 = mysql_fetch_assoc($_j8oC1) ) {
       _OPQ6J();

       # anything of distriblist in outqueue?
       $_QJlJ0 = "SELECT COUNT(id) FROM `$_QtjLI` WHERE `users_id`=$UserId AND `Source`='DistributionList' AND `Source_id`=$_jtIf8[id] AND `Additional_id`=$_Qt6f8[distriblistentry_id] AND `SendId`=$_Qt6f8[id]";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       if($_IO08Q[0] > 0) continue; // not done?

       // Count things
       $_QJlJ0 = "SELECT COUNT(id) FROM `$_jtIf8[RStatisticsTableName]` WHERE SendStat_id=$_Qt6f8[id] AND Send='Sent'";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       $_jI1Ql = $_IO08Q[0];

       $_QJlJ0 = "SELECT COUNT(id) FROM `$_jtIf8[RStatisticsTableName]` WHERE SendStat_id=$_Qt6f8[id] AND Send='Failed'";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       $_jI1tt = $_IO08Q[0];

       $_QJlJ0 = "SELECT COUNT(id) FROM `$_jtIf8[RStatisticsTableName]` WHERE SendStat_id=$_Qt6f8[id] AND Send='PossiblySent'";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       $_IO08Q = mysql_fetch_row($_ItlJl);
       mysql_free_result($_ItlJl);
       $_jIQ0i = $_IO08Q[0];

       mysql_query("BEGIN", $_Q61I1);

       // when resend from distriblistlog than EndSendDateTime <> 0
       $_QJlJ0 = "UPDATE `$_jtIf8[CurrentSendTableName]` SET EndSendDateTime=NOW() WHERE id=$_Qt6f8[id] AND EndSendDateTime=0";
       mysql_query($_QJlJ0, $_Q61I1);

       $_QJlJ0 = "UPDATE `$_jtIf8[CurrentSendTableName]` SET SentCountSucc=$_jI1Ql, SentCountFailed=$_jI1tt, SentCountPossiblySent=$_jIQ0i, SendState='Done', ReportSent=1 WHERE id=$_Qt6f8[id]";
       mysql_query($_QJlJ0, $_Q61I1);

       $_jtIf8["ReportSent"] = $_Qt6f8["ReportSent"];
       $_jtIf8["MailSubject"] = $_Qt6f8["MailSubject"];
       $_jtIf8["DistribSenderEMailAddress"] = $_Qt6f8["DistribSenderEMailAddress"];

       if( $_jtIf8["SendReportToDistribSenderEMailAddress"] || $_jtIf8["SendReportToYourSelf"] || $_jtIf8["SendReportToListAdmin"] || $_jtIf8["SendReportToMailingListUsers"] || $_jtIf8["SendReportToEMailAddress"] ) {

         // MTA
         if(!isset($_jtIf8["mtas_id"])) {
           $_QJlJ0 = "SELECT mtas_id FROM `$_jtIf8[MTAsTableName]` ORDER BY sortorder LIMIT 0, 1"; // only the first
           $_j8Coj = mysql_query($_QJlJ0, $_Q61I1);
           if(!$_j8Coj || mysql_num_rows($_j8Coj) == 0) {
             $_j6O8O .= $commonmsgHTMLMTANotFound;
             mysql_query("ROLLBACK", $_Q61I1);
             continue;
           } else {
             $_jIfO0 = mysql_fetch_assoc($_j8Coj);
             mysql_free_result($_j8Coj);
             $_jtIf8["mtas_id"] = $_jIfO0["mtas_id"];
           }
         }

         $_QJlJ0 = "SELECT * FROM `$_Qofoi` WHERE id=$_jtIf8[mtas_id]";
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
          $_jtIf8 = array_merge($_jtIf8, $_jIfO0);
          // send report
          _OR6DA($_jtIf8, $_Qt6f8["id"]);
         }

       } # if( $_jtIf8["SendReportToYourSelf"] || $_jtIf8["SendReportToListAdmin"] || $_jtIf8["SendReportToMailingListUsers"] || $_jtIf8["SendReportToEMailAddress"] )

       mysql_query("COMMIT", $_Q61I1);

    } # while( $_Qt6f8 = mysql_fetch_assoc($_j8oC1) )
    if($_j8oC1)
      mysql_free_result($_j8oC1);
  }

  function _OR6DA($_IiICC, $_If010) {
      global $_Q61I1, $_Q8f1L, $_Q6fio, $_jJJjO, $resourcestrings, $INTERFACE_LANGUAGE, $UserId, $_jji0C;
      global $_Q6JJJ, $_Q6QQL;
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

        $_IoioQ=0;
        if($_IiICC["SendReportToEMailAddress"] && !empty($_IiICC["SendReportToEMailAddressEMailAddress"])) {
          $_j8ilC[$_IoioQ++] = array("address" => $_IiICC["SendReportToEMailAddressEMailAddress"], "name" => "");
        }
        if($_IiICC["SendReportToDistribSenderEMailAddress"] && !empty($_IiICC["DistribSenderEMailAddress"])) {
          $_j8ilC[$_IoioQ++] = array("address" => $_IiICC["DistribSenderEMailAddress"], "name" => "");
        }

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
             $_Qo1oC = false;
             foreach($_j8ilC as $key => $_Q6ClO){
               if( strtolower($_Q6ClO["address"]) == strtolower($_I6JII["EMail"])) {
                 $_Qo1oC = true;
                 break;
               }
             }

             if(!$_Qo1oC)
                $_j8ilC[$_IoioQ++] = array("address" => $_I6JII["EMail"], "name" => trim($_I6JII["FirstName"]." ".$_I6JII["LastName"]));
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
          $_Q8otJ = @file(_O68QF()."distriblist_email_report.htm");
          if($_Q8otJ)
            $_Q6ICj = join("", $_Q8otJ);
            else
            $_Q6ICj = join("", file(InstallPath._O68QF()."distriblist_email_report.htm"));

          $_j8Li0 = join("", @file(InstallPath."css/default.css"));
          if(!empty($_j8Li0)){
           $_j8Li0 = str_replace('../images/', ScriptBaseURL."images/", $_j8Li0);
           $_j8Li0 = '<style type="text/css">'.$_Q6JJJ.$_j8Li0.$_Q6JJJ.'</style>';
           $_Q6ICj = str_replace("</head>", $_j8Li0.$_Q6JJJ."</head>", $_Q6ICj);
          }

          $_Q8otJ = @file(_O68QF()."distriblist_email_report.txt");
          if($_Q8otJ)
            $_I11oJ = join($_Q6JJJ, $_Q8otJ);
            else
            $_I11oJ = join($_Q6JJJ, file(InstallPath._O68QF()."distriblist_email_report.txt"));
          $_I11oJ = utf8_encode($_I11oJ); // ANSI2UTF8

          $_IiJit->Subject = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002681"], $_IiICC["DistribLists_Name"], $_IiICC["MailSubject"] );
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
            $_jtIOi = $_Q6Q1C["id"];
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
          $_Q6ICj = _OPR6L($_Q6ICj, "<DISTRIBLIST_NAME>", "</DISTRIBLIST_NAME>", $_IiICC["DistribLists_Name"] );
          $_Q6ICj = _OPR6L($_Q6ICj, "<MAILSUBJECT>", "</MAILSUBJECT>", $_IiICC["MailSubject"] );
          $_Q6ICj = _OPR6L($_Q6ICj, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_jI0Oo);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTSUCC>", "</SENTCOUNTSUCC>", $_jI1Ql);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTFAILED>", "</SENTCOUNTFAILED>", $_jI1tt);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENTCOUNTPOSSIBLYSENT>", "</SENTCOUNTPOSSIBLYSENT>", $_jIQ0i);

          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:START>", "</SENDING:START>", $_jII6j);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:END>", "</SENDING:END>", $_jIjJi);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:DURATION>", "</SENDING:DURATION>", $_jIj6l);
          $_Q6ICj = _OPR6L($_Q6ICj, "<SENDING:ESTENDTIME>", "</SENDING:ESTENDTIME>", $_jIjC1);

          // text
          $_I11oJ = _OPR6L($_I11oJ, "<DISTRIBLIST_NAME>", "</DISTRIBLIST_NAME>", $_IiICC["DistribLists_Name"] );
          $_I11oJ = _OPR6L($_I11oJ, "<MAILSUBJECT>", "</MAILSUBJECT>", $_IiICC["MailSubject"] );
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

?>
