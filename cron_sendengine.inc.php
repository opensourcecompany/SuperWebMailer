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

  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("mailcreate.inc.php");
  include_once("savedoptions.inc.php");
  include_once("templates.inc.php");
  include_once("replacements.inc.php");
  if(defined("SWM")){
    include_once("smsout.inc.php");
    include_once("rss2emailreplacements.inc.php");
    include_once("googleanalytics.inc.php");
  }

  function _OR8BP(&$_j6O8O) {
    $_j6O8O = "Send engine checking starts...<br />";

    _ORPPC($_j6O8O, $_jtlLf, $_jO0tC);

    $_j6O8O .= "<br />";
    $_j6O8O .= "$_jtlLf email(s) sent, $_jO0tC failed.<br />";

    $_j6O8O .= "Send engine checking end.<br />";

    if($_jtlLf)
      return true;
      else
      if($_jO0tC)
        return false;
        else
        return -1;
  }

  function _ORPPC(&$_j6O8O, &$_jtlLf, &$_jO0tC) {
    global $_Q61I1, $_Q6QQL;

    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Qo8OO, $resourcestrings, $_Q8f1L, $_Q880O, $_jJt8t;
    global $_jjC06, $_jjCtI, $_I0lQJ, $_jji0C, $_QOCJo, $_QCo6j, $_jji0i;
    global $_Q60QL, $_Qofoi, $_ICljl, $_QLo0Q, $_Ql8C0, $_I88i8;
    global $_Q6jOo, $_IIl8O, $_IjQIf, $_IQL81, $_II8J0;
    global $_QCLCI, $_QtjLI, $_ICjQ6;
    global $_IoOLJ, $_ICjCO;
    global $_QoOft, $_Qoo8o;
    global $_QlQC8, $_QlIf6, $_Q6JJJ, $_QOifL;

    $_jtlLf = 0;
    $_jO0tC = 0;
    $_IitJj = true;
    $_jO0oI = 0;
    $_jO1OC = "";
    $_Iijft = "";
    $_IijO6 = "";
    $_jOQ1o = "";
    $_jOQQC = _LQDLR("SendEngineRetryCount");
    $_jOI0f = null;

    if(_LQDLR("SendEngineFIFO", 1)>0)
      $_QJlJ0 = "SELECT * FROM `$_QtjLI` ORDER BY `LastSending` ASC, `users_id` ASC, `mtas_id` ASC, `maillists_id` ASC, `Source_id` ASC LIMIT 0, "._LQDLR("SendEngineMaxEMailsToSend");
      else
      $_QJlJ0 = "SELECT * FROM `$_QtjLI` ORDER BY `LastSending` DESC, `users_id` ASC, `mtas_id` ASC, `maillists_id` ASC, `Source_id` ASC LIMIT 0, "._LQDLR("SendEngineMaxEMailsToSend");
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_error($_Q61I1) != "") {
      $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
      return false;
    }
    $_jOIQi = 0;
    $_jOIIi = 0;
    $_jOIoJ = 0;
    $_jOj0i = 0;
    $_jOjtt = 0;
    while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $MailType = mtInternalMail;
      // mail object
      if(!isset($_IiJit))
        $_IiJit = new _OF0EE($MailType);
      $_IiJit->_OE868();

      if($_jOIIi != $_Q6Q1C["users_id"]) {
         $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE id=$_Q6Q1C[users_id]";
         $_Ii16i = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_error($_Q61I1) != "") {
           $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
           return false;
         }
         if($_Ii16i && mysql_num_rows($_Ii16i) > 0) {
           $_ICQQo = mysql_fetch_assoc($_Ii16i);

           if(!$_ICQQo["IsActive"]) {
             $_j6O8O .= "<br />$_ICQQo[Username] is disabled.";
             continue;
           }

           $UserId = $_ICQQo["id"];
           $OwnerUserId = 0;
           $Username = $_ICQQo["Username"];
           $UserType = $_ICQQo["UserType"];
           $AccountType = $_ICQQo["AccountType"];
           $INTERFACE_THEMESID = $_ICQQo["ThemesId"];
           $INTERFACE_LANGUAGE = $_ICQQo["Language"];
           $_jOJQJ = $_ICQQo["EMail"];

           _OP10J($INTERFACE_LANGUAGE);

           _LQLRQ($INTERFACE_LANGUAGE);

           $_QJlJ0 = "SELECT `Theme` FROM `$_Q880O` WHERE `id`=$INTERFACE_THEMESID";
           $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
           $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
           $INTERFACE_STYLE = $_Q8OiJ[0];
           mysql_free_result($_Q8Oj8);

           _OP0D0($_ICQQo);

           _OP0AF($UserId);

           mysql_free_result($_Ii16i);

           $_jOIIi = $UserId;
           $_jOIQi = 0;
           $_jO0oI = 0;
           $_jO1OC = "";
           $_jOIoJ = 0;
           $_jOj0i = 0;
           $_jOjtt = 0;
           $_Iijft = "";
           $_IijO6 = "";
           $_jOQ1o = "";
           $_IitJj = true;
         }  else {
            // remove entry, we can't send it, mailing list removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "user id=$_Q6Q1C[users_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "user id=$_Q6Q1C[users_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
        }
      }

      if($_Q6Q1C["Source"] == '') {
        $_j6O8O .= "WARNING: Source is empty, check table structure of table `$_QtjLI`.<br /><br />";
        continue;
      }

      if($_Q6Q1C["Source"] == 'SMSCampaign') {
        $_j6O8O .= "SMS campaigns ignored";
        continue;
      }

      if($_Q6Q1C["Source"] == 'Autoresponder') {
        $MailType = mtAutoresponderEMail;
        $_j08fl = $_II8J0; // same var as in FU Responder
        $_IitJj = ($_jO0oI != $_Q6Q1C["Source_id"]) || ($_jO1OC != $_Q6Q1C["Source"]) || ($_jOIoJ != $_Q6Q1C["maillists_id"]);

        if($_IitJj){
          $_QJlJ0 = "SELECT `$_IQL81`.*, `$_Q60QL`.`forms_id` AS `MLFormsId`, ";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IQL81.SenderFromName <> '', $_IQL81.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IQL81.SenderFromAddress <> '', $_IQL81.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IQL81.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IQL81.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress,";
          $_QJlJ0 .= " $_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating";
          $_QJlJ0 .= " FROM $_IQL81 LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IQL81.maillists_id WHERE $_IQL81.id=$_Q6Q1C[Source_id]";

          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Q8Oj8) {
              continue;
            }
          }
          if($_Q8Oj8 && $_IiICC = mysql_fetch_assoc($_Q8Oj8)) {
            mysql_free_result($_Q8Oj8);
            $_jO0oI = $_Q6Q1C["Source_id"];
            $_jO1OC = $_Q6Q1C["Source"];
            $_jOj0i = 0;
            if($_IiICC["maillists_id"] == 0)
              $_IiICC["forms_id"] = $_IiICC["MLFormsId"];

          } else {
            // remove entry, we can't send it, responder removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "autoresponder id=$_Q6Q1C[Source_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "autoresponder id=$_Q6Q1C[Source_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
          }
        } else{
            if(!$_IiICC["Caching"])
              $_IitJj = true;
        }
      }
      else
      if($_Q6Q1C["Source"] == 'FollowUpResponder') {
        $MailType = mtFollowUpResponderEMail;
        $_IitJj = ($_jO0oI != $_Q6Q1C["Source_id"]) || ($_jO1OC != $_Q6Q1C["Source"]) || ($_jOj0i != $_Q6Q1C["Additional_id"]) || ($_jOIoJ != $_Q6Q1C["maillists_id"]);

        if($_IitJj){
          $_QJlJ0 = "SELECT `FUMailsTableName`, `ML_FU_RefTableName`, `RStatisticsTableName`, $_QCLCI.`forms_id`, $_QCLCI.`GroupsTableName`, $_QCLCI.`NotInGroupsTableName`, `AddXLoop`, `AddListUnsubscribe`, ";
          $_QJlJ0 .= " PersonalizeEMails, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackingIPBlocking, `$_Q60QL`.`forms_id` AS `MLFormsId`, ";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QCLCI.SenderFromName <> '', $_QCLCI.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QCLCI.SenderFromAddress <> '', $_QCLCI.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QCLCI.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QCLCI.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress,";
          $_QJlJ0 .= " $_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating";
          $_QJlJ0 .= " FROM $_QCLCI LEFT JOIN $_Q60QL ON $_Q60QL.id=$_QCLCI.maillists_id WHERE $_QCLCI.id=$_Q6Q1C[Source_id]";

          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Q8Oj8) {
              continue;
            }
          }
          if($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
            $_ItJIf = $_Q8OiJ["FUMailsTableName"];
            $_It6OJ = $_Q8OiJ["ML_FU_RefTableName"];
            $_j08fl = $_Q8OiJ["RStatisticsTableName"];
            $_jO0oI = $_Q6Q1C["Source_id"];
            $_jO1OC = $_Q6Q1C["Source"];
            mysql_free_result($_Q8Oj8);

            // get group ids if specified for unsubscribe link
            $_IitLf = array();
            $_jIOjL = "SELECT * FROM `$_Q8OiJ[GroupsTableName]`";
            $_jIOff = mysql_query($_jIOjL, $_Q61I1);
            while($_jIOio = mysql_fetch_row($_jIOff)) {
              $_IitLf[] = $_jIOio[0];
            }
            mysql_free_result($_jIOff);
            if(count($_IitLf) > 0) {
              // remove groups
              $_jIOjL = "SELECT * FROM `$_Q8OiJ[NotInGroupsTableName]`";
              $_jIOff = mysql_query($_jIOjL, $_Q61I1);
              while($_jIOio = mysql_fetch_row($_jIOff)) {
                $_IJQOL = array_search($_jIOio[0], $_IitLf);
                if($_IJQOL !== false)
                   unset($_IitLf[$_IJQOL]);
              }
              mysql_free_result($_jIOff);
            }
            if(count($_IitLf) > 0)
              $_Q8OiJ["GroupIds"] = join(",", $_IitLf);
              else
              if(isset($_Q8OiJ["GroupIds"]))
                unset($_Q8OiJ["GroupIds"]);
            // we don't need this here
            unset($_Q8OiJ["GroupsTableName"]);
            unset($_Q8OiJ["NotInGroupsTableName"]);
            //

          } else {
            // remove entry, we can't send it, responder removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "follow up responder id=$_Q6Q1C[Source_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "follow up responder id=$_Q6Q1C[Source_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
          }

          $_QJlJ0 = "SELECT * FROM `$_ItJIf` WHERE `id`=$_Q6Q1C[Additional_id]";
          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Q8Oj8) {
              continue;
            }
          }
          if($_Q8Oj8 && $_IiICC = mysql_fetch_assoc($_Q8Oj8)) {
            $_jOj0i = $_Q6Q1C["Additional_id"];

            // we don't need this fields
            unset($_Q8OiJ["FUMailsTableName"]);
            unset($_Q8OiJ["ML_FU_RefTableName"]);
            unset($_Q8OiJ["RStatisticsTableName"]);
            if(!$_Q8OiJ["AllowOverrideSenderEMailAddressesWhileMailCreating"])
               $_IiICC = array_merge($_IiICC, $_Q8OiJ); // override it
               else
               $_IiICC = array_merge($_Q8OiJ, $_IiICC); // override it

            mysql_free_result($_Q8Oj8);
          } else {
            // remove entry, we can't send it, mail removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "follow up responder email text id=$_Q6Q1C[Additional_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "follow up responder email text id=$_Q6Q1C[Additional_id] not found", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
          }
        } else {
           if(!$_IiICC["Caching"])
             $_IitJj = true;
        }
      }
      else
      if($_Q6Q1C["Source"] == 'BirthdayResponder') {
        $MailType = mtBirthdayResponderEMail;
        $_j08fl = $_IjQIf; // same var as in FU Responder
        $_IitJj = ($_jO0oI != $_Q6Q1C["Source_id"]) || ($_jO1OC != $_Q6Q1C["Source"]) || ($_jOIoJ != $_Q6Q1C["maillists_id"]);;

        if($_IitJj) {
          if($_jOI0f !== null) $_jOI0f = null;
          $_QJlJ0 = "SELECT `$_IIl8O`.*, `$_Q60QL`.`forms_id` AS `MLFormsId`, ";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_IIl8O`.SenderFromName <> '', `$_IIl8O`.SenderFromName, `$_Q60QL`.SenderFromName) AS SenderFromName,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_IIl8O`.SenderFromAddress <> '', `$_IIl8O`.SenderFromAddress, `$_Q60QL`.SenderFromAddress) AS SenderFromAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_IIl8O`.ReplyToEMailAddress, `$_Q60QL`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_IIl8O`.ReturnPathEMailAddress, `$_Q60QL`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QJlJ0 .= " FROM `$_IIl8O` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_IIl8O`.maillists_id WHERE `$_IIl8O`.id=$_Q6Q1C[Source_id]";

          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Q8Oj8) {
              continue;
            }
          }
          if($_Q8Oj8 && $_IiICC = mysql_fetch_assoc($_Q8Oj8)) {
            $_jO0oI = $_Q6Q1C["Source_id"];
            $_jO1OC = $_Q6Q1C["Source"];
            $_jOj0i = 0;
            mysql_free_result($_Q8Oj8);
          } else {
            // remove entry, we can't send it, responder removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE id=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "birthday responder id=$_Q6Q1C[Source_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "birthday responder id=$_Q6Q1C[Source_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
          }
        } else {
           if(!$_IiICC["Caching"])
             $_IitJj = true;
        }
      }
      else
      if($_Q6Q1C["Source"] == 'RSS2EMailResponder') {
        $MailType = mtRSS2EMailResponderEMail;
        $_j08fl = $_ICjCO; // same var as in FU Responder
        $_IitJj = ($_jO0oI != $_Q6Q1C["Source_id"]) || ($_jO1OC != $_Q6Q1C["Source"]) || ($_jOIoJ != $_Q6Q1C["maillists_id"]);

        if($_IitJj){
          $_jOQ1o = "";
          $_QJlJ0 = "SELECT `$_IoOLJ`.*, `$_Q60QL`.`forms_id` AS `MLFormsId`, ";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IoOLJ.SenderFromName <> '', $_IoOLJ.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IoOLJ.SenderFromAddress <> '', $_IoOLJ.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IoOLJ.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IoOLJ.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QJlJ0 .= " FROM `$_IoOLJ` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_IoOLJ`.maillists_id WHERE $_IoOLJ.id=$_Q6Q1C[Source_id]";

          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Q8Oj8) {
              continue;
            }
          }
          if($_Q8Oj8 && $_IiICC = mysql_fetch_assoc($_Q8Oj8)) {
            $_jO0oI = $_Q6Q1C["Source_id"];
            $_jO1OC = $_Q6Q1C["Source"];
            $_jOj0i = 0;
            $_jOQ1o = "";
            mysql_free_result($_Q8Oj8);

            // Backup texts and subject, because RSS feed entries can be differ for each member
            $_IiICC["internalBackupMailSubject"] = $_IiICC["MailSubject"];
            $_IiICC["internalBackupMailHTMLText"] = $_IiICC["MailHTMLText"];


          } else {
            // remove entry, we can't send it, responder removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "RSS2EMail responder id=$_Q6Q1C[Source_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "RSS2EMail responder id=$_Q6Q1C[Source_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
          }
        } else {
           if(!$_IiICC["Caching"])
             $_IitJj = true;
        }

        if($_IitJj)
          $_jOQ1o = "";


      }
      else
      if($_Q6Q1C["Source"] == 'EventResponder') {
        $MailType = mtEventResponderEMail;
        $_j08fl = $_ICjQ6; // same var as in FU Responder
        $_IitJj = ($_jO0oI != $_Q6Q1C["Source_id"]) || ($_jO1OC != $_Q6Q1C["Source"]) || ($_jOIoJ != $_Q6Q1C["maillists_id"]);;
        if($_IitJj){
          $_jO0oI = $_Q6Q1C["Source_id"];
          $_jO1OC = $_Q6Q1C["Source"];
          $_jOj0i = 0;
        }
      }
      else
      if($_Q6Q1C["Source"] == 'Campaign') {
        $MailType = mtCampaignEMail;

        $_IitJj = ($_jO0oI != $_Q6Q1C["Source_id"]) || ($_jO1OC != $_Q6Q1C["Source"]) || ($_jOIoJ != $_Q6Q1C["maillists_id"]);;

        if($_IitJj){
          $_QJlJ0 = "SELECT `$_Q6jOo`.*, `$_Q6jOo`.Name AS CampaignsName, `$_Q60QL`.MaillistTableName, `$_Q60QL`.MailListToGroupsTableName, `$_Q60QL`.LocalBlocklistTableName, `$_Q60QL`.id AS MailingListId, `$_Q60QL`.FormsTableName, `$_Q60QL`.StatisticsTableName, `$_Q60QL`.MailLogTableName, `$_Q60QL`.users_id, `$_Q60QL`.`forms_id` AS `MLFormsId`,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_Q6jOo`.SenderFromName <> '', `$_Q6jOo`.SenderFromName, `$_Q60QL`.SenderFromName) AS SenderFromName,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_Q6jOo`.SenderFromAddress <> '', `$_Q6jOo`.SenderFromAddress, `$_Q60QL`.SenderFromAddress) AS SenderFromAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_Q6jOo`.ReplyToEMailAddress, `$_Q60QL`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_Q6jOo`.ReturnPathEMailAddress, `$_Q60QL`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QJlJ0 .= " FROM `$_Q6jOo` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_Q6jOo`.maillists_id WHERE `$_Q6jOo`.id=$_Q6Q1C[Source_id]";

          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Q8Oj8) {
              continue;
            }
          }
          if($_Q8Oj8 && $_IiICC = mysql_fetch_assoc($_Q8Oj8)) {
            $_jO0oI = $_Q6Q1C["Source_id"];
            $_jO1OC = $_Q6Q1C["Source"];
            $_jOj0i = 0;
            $_j08fl = $_IiICC["RStatisticsTableName"];
            unset($_IiICC["RStatisticsTableName"]);
            mysql_free_result($_Q8Oj8);

            // get group ids if specified for unsubscribe link
            $_IitLf = array();
            $_jIOjL = "SELECT * FROM `$_IiICC[GroupsTableName]`";
            $_jIOff = mysql_query($_jIOjL, $_Q61I1);
            while($_jIOio = mysql_fetch_row($_jIOff)) {
              $_IitLf[] = $_jIOio[0];
            }
            mysql_free_result($_jIOff);
            if(count($_IitLf) > 0) {
              // remove groups
              $_jIOjL = "SELECT * FROM `$_IiICC[NotInGroupsTableName]`";
              $_jIOff = mysql_query($_jIOjL, $_Q61I1);
              while($_jIOio = mysql_fetch_row($_jIOff)) {
                $_IJQOL = array_search($_jIOio[0], $_IitLf);
                if($_IJQOL !== false)
                   unset($_IitLf[$_IJQOL]);
              }
              mysql_free_result($_jIOff);
            }
            if(count($_IitLf) > 0)
              $_IiICC["GroupIds"] = join(",", $_IitLf);
              else
              if(isset($_IiICC["GroupIds"]))
                unset($_IiICC["GroupIds"]);


          } else {
            // remove entry, we can't send it, responder removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "mailing id=$_Q6Q1C[Source_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "mailing id=$_Q6Q1C[Source_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
          }
        } else {
            if(!$_IiICC["Caching"])
              $_IitJj = true;
        }
      }
      else
      if($_Q6Q1C["Source"] == 'DistributionList') {
        $MailType = mtDistributionListEMail;

        $_IitJj = ($_jO0oI != $_Q6Q1C["Source_id"]) || ($_jO1OC != $_Q6Q1C["Source"]) || ($_jOj0i != $_Q6Q1C["Additional_id"]) || ($_jOIoJ != $_Q6Q1C["maillists_id"]);

        if($_IitJj){
          $_QJlJ0 = "SELECT `$_QoOft`.*, `$_QoOft`.`Name` AS `DistribListsName`, `$_QoOft`.`Description` AS `DistribListsDescription`, `$_Q60QL`.`Name` AS `MailingListName`, `$_Q60QL`.MaillistTableName, `$_Q60QL`.MailListToGroupsTableName, `$_Q60QL`.LocalBlocklistTableName, `$_Q60QL`.id AS MailingListId, `$_Q60QL`.FormsTableName, `$_Q60QL`.StatisticsTableName, `$_Q60QL`.MailLogTableName, `$_Q60QL`.users_id, `$_Q60QL`.`AllowOverrideSenderEMailAddressesWhileMailCreating`, `$_Q60QL`.`forms_id` AS `MLFormsId`,";
          $_QJlJ0 .= " IF(`$_Q60QL`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_QoOft`.SenderFromName <> '', `$_QoOft`.SenderFromName, `$_Q60QL`.SenderFromName) AS SenderFromName,";
          $_QJlJ0 .= " IF(`$_Q60QL`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_QoOft`.SenderFromAddress <> '', `$_QoOft`.SenderFromAddress, `$_Q60QL`.SenderFromAddress) AS SenderFromAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.`AllowOverrideSenderEMailAddressesWhileMailCreating`, `$_QoOft`.ReplyToEMailAddress, `$_Q60QL`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QJlJ0 .= " IF(`$_Q60QL`.`AllowOverrideSenderEMailAddressesWhileMailCreating`, `$_QoOft`.ReturnPathEMailAddress, `$_Q60QL`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QJlJ0 .= " FROM `$_QoOft` LEFT JOIN `$_Q60QL` ON `$_Q60QL`.id=`$_QoOft`.`maillists_id` WHERE `$_QoOft`.id=$_Q6Q1C[Source_id]";

          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Q8Oj8) {
              continue;
            }
          }
          if($_Q8Oj8 && $_IiICC = mysql_fetch_assoc($_Q8Oj8)) {
            $_jO0oI = $_Q6Q1C["Source_id"];
            $_jO1OC = $_Q6Q1C["Source"];
            $_jOj0i = $_Q6Q1C["Additional_id"];
            $_j08fl = $_IiICC["RStatisticsTableName"];
            unset($_IiICC["RStatisticsTableName"]);

            // save mem
            $_jffIC = array("ResponderUIDL", "DistribListConfirmationLinkMailSubject", "DistribListConfirmationLinkMailPlainText", "DistribListSenderInfoMailSubject", "DistribListSenderInfoMailPlainText", "DistribListSenderInfoConfirmMailSubject", "DistribListSenderInfoConfirmMailPlainText");

            foreach($_jffIC as $key => $_Q6ClO){
              unset($_IiICC[$_Q6ClO]);
            }

            mysql_free_result($_Q8Oj8);

            // DistributionListEntries
            $_QJlJ0 = "SELECT * FROM `$_Qoo8o` WHERE `id`=$_jOj0i";
            $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
            if(!$_Q8Oj8 || mysql_num_rows($_Q8Oj8) == 0) {
              // entry not found?
              mysql_free_result($_Q8Oj8);
              $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
              mysql_query($_QJlJ0, $_Q61I1);
              $_jO0tC++;
              $_j6O8O .= "distribution list entry id=$_jOj0i not found.<br />";
              _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "distribution list entry id=$_jOj0i not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
              $_jO0oI = 0;
              $_jOj0i =0;
              continue;
            }
            $_Iij08 = mysql_fetch_assoc($_Q8Oj8);

            if(substr($_Iij08["MailPlainText"], 0, 4) == "xb64"){
              $_Iij08["MailPlainText"] = base64_decode( substr($_Iij08["MailPlainText"], 4) );
            }

            if(substr($_Iij08["MailHTMLText"], 0, 4) == "xb64"){
              $_Iij08["MailHTMLText"] = base64_decode( substr($_Iij08["MailHTMLText"], 4) );
            }

            $_IiICC["DistributionListEntryId"] = $_jOj0i;
            $_IiICC["UseInternalText"] = $_Iij08["UseInternalText"];
        				$_IiICC["ExternalTextURL"] = $_Iij08["ExternalTextURL"];

        				$_IiICC["MailFormat"] = $_Iij08["MailFormat"];
        				$_IiICC["MailPriority"] = $_Iij08["MailPriority"];
        				$_IiICC["MailEncoding"] = $_Iij08["MailEncoding"];
            $_IiICC["MailSubject"] = $_Iij08["MailSubject"];
            $_IiICC["OrgMailSubject"] = $_Iij08["MailSubject"];
            $_IiICC["MailPlainText"] = $_Iij08["MailPlainText"];
            $_IiICC["MailHTMLText"] = $_Iij08["MailHTMLText"];
            $_IiICC["Attachments"] = $_Iij08["Attachments"];
            $_IiICC["DistribSenderEMailAddress"] = $_Iij08["DistribSenderEMailAddress"];

            if($_IiICC["AllowOverrideSenderEMailAddressesWhileMailCreating"]){
              $_IiICC["SenderFromName"] = $_Iij08["SenderFromName"];
              $_IiICC["SenderFromAddress"] = $_Iij08["SenderFromAddress"];
              $_IiICC["ReplyToEMailAddress"] = $_Iij08["ReplyToEMailAddress"];
              $_IiICC["ReturnPathEMailAddress"] = $_Iij08["ReturnPathEMailAddress"];
            }
            unset( $_IiICC["AllowOverrideSenderEMailAddressesWhileMailCreating"] );
            unset($_Iij08);

            mysql_free_result($_Q8Oj8);

            // get group ids if specified for unsubscribe link
            $_IitLf = array();
            $_jIOjL = "SELECT * FROM `$_IiICC[GroupsTableName]`";
            $_jIOff = mysql_query($_jIOjL, $_Q61I1);
            while($_jIOio = mysql_fetch_row($_jIOff)) {
              $_IitLf[] = $_jIOio[0];
            }
            mysql_free_result($_jIOff);
            if(count($_IitLf) > 0) {
              // remove groups
              $_jIOjL = "SELECT * FROM `$_IiICC[NotInGroupsTableName]`";
              $_jIOff = mysql_query($_jIOjL, $_Q61I1);
              while($_jIOio = mysql_fetch_row($_jIOff)) {
                $_IJQOL = array_search($_jIOio[0], $_IitLf);
                if($_IJQOL !== false)
                   unset($_IitLf[$_IJQOL]);
              }
              mysql_free_result($_jIOff);
            }
            if(count($_IitLf) > 0)
              $_IiICC["GroupIds"] = join(",", $_IitLf);
              else
              if(isset($_IiICC["GroupIds"]))
                unset($_IiICC["GroupIds"]);


          } else {
            // remove entry, we can't send it, responder removed
            $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_jO0tC++;
            $_j6O8O .= "distribution list id=$_Q6Q1C[Source_id] not found.<br />";
            _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "distribution list=$_Q6Q1C[Source_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
            continue;
          }
        } else {
            if(!$_IiICC["Caching"])
              $_IitJj = true;
        }
      }

      // CurrentSendId for Tracking and AltBrowserLink
      $_IiICC["CurrentSendId"] = $_Q6Q1C["SendId"];

      if($_jOIQi != $_Q6Q1C["mtas_id"] && $_Q6Q1C["mtas_id"] != $_jJt8t) { # ignore SMSoutGatewayId
         $_QJlJ0 = "SELECT * FROM `$_Qofoi` WHERE `id`=$_Q6Q1C[mtas_id]";
         $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_error($_Q61I1) != "") {
           $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
           if($_Q8Oj8) {
             continue;
           }
         }
         if($_Q8Oj8 && mysql_num_rows($_Q8Oj8) > 0) {
           $_jIfO0 = mysql_fetch_assoc($_Q8Oj8);
           mysql_free_result($_Q8Oj8);
           $_jOIQi = $_jIfO0["id"];
         } else {
           // remove entry, we can't send it, MTA removed
           $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
           mysql_query($_QJlJ0, $_Q61I1);
           $_jO0tC++;
           $_j6O8O .= "MTA id=$_Q6Q1C[mtas_id] not found.<br />";
           _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "MTA id=$_Q6Q1C[mtas_id] not found", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
           continue;
         }
      }

      # other mailinglist?
      if($_jOIoJ != $_Q6Q1C["maillists_id"]) {
        $_QJlJ0 = "SELECT `MaillistTableName`, `StatisticsTableName`, `MailLogTableName`, `FormsTableName`, `ExternalBounceScript`, `SubscriptionUnsubscription` FROM `$_Q60QL` WHERE `id`=$_Q6Q1C[maillists_id]";
        $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_error($_Q61I1) != "") {
          $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
          if($_Q8Oj8) {
            continue;
          }
        }
        if($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
          mysql_free_result($_Q8Oj8);
          $_QlQC8 = $_Q8OiJ["MaillistTableName"];
          $_QlIf6 = $_Q8OiJ["StatisticsTableName"];
          $_QljIQ = $_Q8OiJ["MailLogTableName"];
          $MailingListId = $_Q6Q1C["maillists_id"];
          $_jOIoJ = $_Q6Q1C["maillists_id"];
          $_IiICC["ExternalBounceScript"] = $_Q8OiJ["ExternalBounceScript"];
          $_IiICC["SubscriptionUnsubscription"] = $_Q8OiJ["SubscriptionUnsubscription"];

          $_jOjtt = 0;

        } else {
          // remove entry, we can't send it, mailing list removed
          $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          $_jO0tC++;
          $_j6O8O .= "mailing list id=$_Q6Q1C[maillists_id] not found.<br />";
          _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "mailing list id=$_Q6Q1C[maillists_id] not found", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
          $_jOjtt = 0;
          continue;
        }
      }

      if($_jOjtt != $_IiICC["forms_id"]){
          $_QJlJ0 = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_Q8OiJ[FormsTableName]` WHERE `id`=$_IiICC[forms_id]";
          $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);
          if($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {
            $_Iijft = $_QlftL["OverrideSubUnsubURL"];
            $_IijO6 = $_QlftL["OverrideTrackingURL"];
            mysql_free_result($_IOOt1);
          } else{
            $_Iijft = "";
            $_IijO6 = "";
          }
          $_jOjtt = $_IiICC["forms_id"];
      }

      if($_Q6Q1C["Source"] == 'BirthdayResponder') {
        if($_IiICC["SendIntervalDays"] >= 0) {
           $_Iijl0 =
               'TO_DAYS(

                   DATE_ADD(
                  `u_Birthday`,
                       INTERVAL
                         (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                           IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 1, 0 )
                  )
                         YEAR)
                  )
                  -
                  TO_DAYS( CURRENT_DATE() )
                  AS `Days_to_Birthday`';
        } else {

           $_Iijl0 =
               'TO_DAYS(

                   DATE_ADD(
                  `u_Birthday`,
                       INTERVAL
                         (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                           IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 0, 1 )
                  )
                         YEAR)
                  )
                  -
                  TO_DAYS( CURRENT_DATE() )
                  AS `Days_to_Birthday`';

        }

        $_QJlJ0 = "SELECT *, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS `MembersAge`, $_Iijl0 FROM `$_QlQC8` WHERE `id`=$_Q6Q1C[recipients_id]";

      }
      else
        if($_Q6Q1C["Source"] == 'Campaign')
          $_QJlJ0 = "SELECT *, IF(`$_QlQC8`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_QlQC8`.`u_Birthday`), 0) AS `MembersAge` FROM `$_QlQC8` WHERE `id`=$_Q6Q1C[recipients_id]";
         else
          $_QJlJ0 = "SELECT * FROM `$_QlQC8` WHERE `id`=$_Q6Q1C[recipients_id]";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "") {
        $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
        if($_Q8Oj8) {
          continue;
        }
      }
      if($_Q8Oj8 && mysql_num_rows($_Q8Oj8) > 0) {
        $_jIiQ8 = mysql_fetch_assoc($_Q8Oj8);

        // birthday responder
        if(isset($_jIiQ8["Days_to_Birthday"]))
         $_jIiQ8["Days_to_Birthday"] = abs($_jIiQ8["Days_to_Birthday"]);

        // DistribList
        if(isset($_IiICC["DistribSenderEMailAddress"]))
          $_jIiQ8["DistribSenderEMailAddress"] = $_IiICC["DistribSenderEMailAddress"];

        mysql_free_result($_Q8Oj8);
      } else {
          if($_Q8Oj8)
            mysql_free_result($_Q8Oj8);
          // remove entry, we can't send it, recipient removed
          $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          $_jO0tC++;
          $_j6O8O .= "member id=$_Q6Q1C[recipients_id] not found.<br />";
          _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "member id=$_Q6Q1C[recipients_id] not found.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
          continue;
      }

      ###################
      // special for RSS2EMailResponder we must inserting RSS feed entries and refreshing tracking links
      if($_Q6Q1C["Source"] == 'RSS2EMailResponder') {
          $_QJlJ0 = "SELECT `New_RSS_Entries` FROM `$_IiICC[ML_RM_RefTableName]` WHERE `Member_id`=$_Q6Q1C[recipients_id]";
          $_Ii80i = mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_error($_Q61I1) != "") {
            $_j6O8O .= "<br />$_QJlJ0<br />mysql_error: ".mysql_error($_Q61I1)."<br />";
            if($_Ii80i) {
              continue;
            }
          }
          $_Ii816 = mysql_fetch_assoc($_Ii80i);
          mysql_free_result($_Ii80i);
          # other entries?
          $_Ii8Cl = false;
          if($_Ii816["New_RSS_Entries"] != $_jOQ1o) {
            $_Ii8Cl = true;
            $_jOQ1o = $_Ii816["New_RSS_Entries"];
          }

          if($_IitJj || $_Ii8Cl) {
             // restore original texts
             $_IiICC["MailSubject"] = $_IiICC["internalBackupMailSubject"];
             $_IiICC["MailHTMLText"] = $_IiICC["internalBackupMailHTMLText"];

             $_Ii816["New_RSS_Entries"] = @unserialize($_Ii816["New_RSS_Entries"]);
             if($_Ii816["New_RSS_Entries"] === false) {
                $_Ii816["New_RSS_Entries"] = array();
                $_j6O8O .= "<br />Can't read new RSS entries UIDLs for user $_Q6Q1C[recipients_id], was it removed?";
                // remove entry, we can't send it without UIDLs
                $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE id=$_Q6Q1C[id]";
                mysql_query($_QJlJ0, $_Q61I1);
                $_jO0tC++;
                _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "Can't read new RSS entries UIDLs for user $_Q6Q1C[recipients_id], was it removed?", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
                continue;
             }
             if(!is_array($_IiICC["LastRSSFeedContent"])) {
                $_IiICC["LastRSSFeedContent"] = @unserialize(base64_decode($_IiICC["LastRSSFeedContent"]));
                if($_IiICC["LastRSSFeedContent"] === false) {
                   $_IiICC["LastRSSFeedContent"] = array();
                   $_IiICC["LastRSSFeedContent"]["ITEMS"] = array();

                   $_j6O8O .= "<br />Can't read last RSS feed content, was URL or recipients changed? member_id=$_Q6Q1C[recipients_id]";
                   // remove entry, we can't send it without UIDLs
                   $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE id=$_Q6Q1C[id]";
                   mysql_query($_QJlJ0, $_Q61I1);
                   $_jO0tC++;
                   _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "Can't read last RSS feed content, was URL or recipients list changed? member_id=$_Q6Q1C[recipients_id]", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);
                   continue;
                }
             }
             _LQRFC($_IiICC["MailHTMLText"], $_IiICC["MailSubject"], $_Ii816["New_RSS_Entries"], $_IiICC["LastRSSFeedContent"], $_IiICC["EverSendLastRSSFeedMaxEntries"]);

             // auto create plaintext part
             $_IiICC["MailPlainText"] = _ODQAB ( $_IiICC["MailHTMLText"], $_Q6QQL );

             // Tracking?
             if($_IiICC["TrackLinks"] || $_IiICC["TrackLinksByRecipient"]){
               $_QOLIl = array();
               $_QOLCo = array();
               _OBBPD($_IiICC["MailHTMLText"], $_QOLIl, $_QOLCo);

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

                 $_QJlJ0 = "SELECT `id` FROM `$_IiICC[LinksTableName]` WHERE `Link`="._OPQLR($_QOLIl[$_Q6llo]);
                 $_QCfQJ = mysql_query($_QJlJ0, $_Q61I1);
                 if( mysql_num_rows($_QCfQJ) > 0 ) {
                   mysql_free_result($_QCfQJ);
                 } else {

                   $_QOLCo[$_Q6llo] = str_replace("&", " ", $_QOLCo[$_Q6llo]);
                   $_QOLCo[$_Q6llo] = str_replace("\r\n", " ", $_QOLCo[$_Q6llo]);
                   $_QOLCo[$_Q6llo] = str_replace("\r", " ", $_QOLCo[$_Q6llo]);
                   $_QOLCo[$_Q6llo] = str_replace("\n", " ", $_QOLCo[$_Q6llo]);

                   $_QJlJ0 = "INSERT INTO `$_IiICC[LinksTableName]` SET `IsActive`=$_QC6IO, `Link`="._OPQLR($_QOLIl[$_Q6llo]).", `Description`="._OPQLR(str_replace("&", " ", trim($_QOLCo[$_Q6llo])));
                   mysql_query($_QJlJ0, $_Q61I1);
                 }
               }
             }
          }
      }
      ###################

      // merge text with mail send settings
      if(isset($_jIfO0) && isset($_jIfO0["id"])) {
        unset($_jIfO0["id"]);
        unset($_jIfO0["CreateDate"]);
        unset($_jIfO0["IsDefault"]);
        unset($_jIfO0["Name"]);
      }
      if(isset($_jIfO0))
        $_IiICC = array_merge($_IiICC, $_jIfO0);

      // Looping protection
      $AdditionalHeaders = array();
      if(isset($_IiICC["AddXLoop"]) && $_IiICC["AddXLoop"])
         $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';
      if(isset($_IiICC["AddListUnsubscribe"]) && $_IiICC["AddListUnsubscribe"])
         $AdditionalHeaders["List-Unsubscribe"] = '<'."[UnsubscribeLink]".'>';

      if(isset($errors))
        unset($errors);
      if(isset($_Ql1O8))
        unset($_Ql1O8);

      $errors = array();
      $_Ql1O8 = array();
      $_Ii6QI = "";
      $_Ii6lO = "";
      // set OrgMailSubject for reply, DistribList is set above
      if($_Q6Q1C["Source"] == 'Autoresponder')
         $_IiICC["OrgMailSubject"] = $_Q6Q1C["MailSubject"];

      // override Link?
      $_IiICC["OverrideSubUnsubURL"] = $_Iijft;
      $_IiICC["OverrideTrackingURL"] = $_IijO6;

      _OPQ6J();

      # when there are errors we rollback
      mysql_query("ROLLBACK", $_Q61I1);
      mysql_query("BEGIN", $_Q61I1);

      # SMS
      if($_Q6Q1C["mtas_id"] == $_jJt8t){
         if($_jOI0f == null){
           $_jOI0f = new _LODEB();
         }
         if($_jOI0f->SMSoutUsername != $_IiICC["SMSoutUsername"]){
           $_jOI0f->Logout();
           $_jOI0f->SMSoutUsername = $_IiICC["SMSoutUsername"];
           $_jOI0f->SMSoutPassword = $_IiICC["SMSoutPassword"];
         }
         $SMSText = _L1ERL($_jIiQ8, $MailingListId, $_IiICC["SMSText"], "utf-8", false, array());
         if(defined("DEMO") || defined("SimulateMailSending")){
            $_Q8COf = true;
            $_Ql1O8[] = "DEMO, SMS sent.";
           }
           else {
             if(!$_jOI0f->IsLoggedIn()){
               $_Q8COf = $_jOI0f->Login();
               $_Ql1O8 = array();
               $_Ql1O8[] = $_jOI0f->SMSoutLastErrorNo . " ". $_jOI0f->SMSoutLastErrorString;
             }
             if($_jOI0f->IsLoggedIn()) {
                $SMSCampaignName = "";
                if(!empty($_IiICC["SMSCampaignName"]))
                  $SMSCampaignName = $_IiICC["SMSCampaignName"];
                $_Q8COf = $_jOI0f->SendSingleSMS($_IiICC["SMSoutSendVariant"], $_jIiQ8["u_CellNumber"], $SMSCampaignName, ConvertString("utf-8", "iso-8859-1", $SMSText, false));
                $_Ql1O8 = array();
                $_Ql1O8[] = $_jOI0f->SMSoutLastErrorNo . " ". $_jOI0f->SMSoutLastErrorString;
             }
           }
         $_IiJit->Subject = $SMSText;
         $_IiJit->charset = "UTF-8";
         $_jIfO0["Type"] = "SMS";
         $_jOIQi = -1;
      }

      if($_Q6Q1C["mtas_id"] != $_jJt8t){

        if(!isset($_IiICC["TargetGroupsInHTMLPartIncluded"])){
          // target groups support
          // save time and check it once at start up so _OED01() must NOT do this
          if($_IiICC["MailFormat"] == "HTML" || $_IiICC["MailFormat"] == "Multipart"){
            $_IiICC["TargetGroupsWithEmbeddedFilesIncluded"] = _LJOAD($_IiICC["MailHTMLText"]);

            $_j1L1C = array();
            if(!$_IiICC["TargetGroupsWithEmbeddedFilesIncluded"]){
              _LJO8O($_IiICC["MailHTMLText"], $_j1L1C, 1);
            }
            $_IiICC["TargetGroupsInHTMLPartIncluded"] = $_IiICC["TargetGroupsWithEmbeddedFilesIncluded"] || count($_j1L1C) > 0;
          }
          // target groups support /
        }

        $_IiJit->MailType = $MailType;
        if(!_OED01($_IiJit, $_Ii6QI, $_Ii6lO, $_IitJj, $_IiICC, $_jIiQ8, $MailingListId, $_IiICC["MLFormsId"], $_IiICC["forms_id"], $errors, $_Ql1O8, $AdditionalHeaders, $_Q6Q1C["Source_id"], $_Q6Q1C["Source"]) ) {
           $_jj0JO = join($_Q6JJJ, $_Ql1O8);
           _OBQEP($_Q6Q1C["Source"] == 'DistributionList' ? $_jOJQJ : $_IiICC["SenderFromAddress"], "'".$_IiICC["MailSubject"]."' ".join($_Q6JJJ, $_Ql1O8));

           $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE id=$_Q6Q1C[id]";
           mysql_query($_QJlJ0, $_Q61I1);
           $_j6O8O .= "Email was not createable ($_jj0JO), it was removed from out queue.<br /><br />";
           $_jO0tC++;
           _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "Email was not createable ($_jj0JO), it was removed from out queue.", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);

           mysql_query("COMMIT", $_Q61I1);

           continue;
        }

        # Demo version
        if(defined("DEMO") || defined("SimulateMailSending") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()))
          $_IiJit->Sendvariant = "null";

        _OPQ6J();
        // send email
        $_IiJit->debug = false;
        //$_IiJit->debugfilename = InstallPath."_email.log";

        $_IiJit->writeEachEmailToFile = false;
        //$_IiJit->writeEachEmailToDirectory = _OBLDR(InstallPath)."eml/";

        // incr SendEngineRetryCount BEFORE sending it
        $_QJlJ0 = "UPDATE `$_QtjLI` SET `SendEngineRetryCount`=`SendEngineRetryCount` + 1, `LastSending`=NOW(), `IsSendingNowFlag`=1 WHERE `id`=$_Q6Q1C[id]";
        mysql_query($_QJlJ0, $_Q61I1);

        // hope to prevent script timeout
        if($_Q6Q1C["SendEngineRetryCount"] > 1 && $_IiJit->SMTPTimeout == 0 && ($_IiJit->Sendvariant == "smtp" || $_IiJit->Sendvariant == "smtpmx"))
          $_IiJit->SMTPTimeout = 20; // 20 Sec.

        if($_Q6Q1C["IsSendingNowFlag"] > 0) { // script was terminated last time while sending this email, we don't know it is sent or not
          $_Q8COf = true;
        }
        else {
          $_Q8COf = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);
        }
        _OPQ6J();
      } # if($_Q6Q1C["mtas_id"] != $_jJt8t)

      if($_Q8COf) {
        if($_Q6Q1C["mtas_id"] != $_jJt8t) {
           $_IiJit->_OF0FL($_QljIQ, $_jIiQ8["id"], ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false));
           unset($_Ii6QI);
           unset($_Ii6lO);
        }

        $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE id=$_Q6Q1C[id]";
        mysql_query($_QJlJ0, $_Q61I1);

        # prevent sending more than once
        mysql_query("COMMIT", $_Q61I1);
        mysql_query("BEGIN", $_Q61I1);

        if($_Q6Q1C["mtas_id"] != $_jJt8t && $_IiJit->Sendvariant == "smtpmx") {
          // update last email sent datetime and reset bounce status
          $_QJlJ0 = "UPDATE `$_QlQC8` SET `LastEMailSent`=NOW(), `BounceStatus`='', `SoftbounceCount`=0, `HardbounceCount`=0, `LastChangeDate`=`LastChangeDate` WHERE `id`=$_jIiQ8[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          if(!empty($_jIiQ8['BounceStatus'])) {
              $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE `Action`='Bounced' AND `Member_id`=$_jIiQ8[id]";
              mysql_query($_QJlJ0, $_Q61I1);
          }
        } # if($_Q6Q1C["mtas_id"] != $_jJt8t && $_IiJit->Sendvariant == "smtpmx")
        else if($_Q6Q1C["mtas_id"] != $_jJt8t) {
          // update last email sent datetime
          $_QJlJ0 = "UPDATE `$_QlQC8` SET `LastEMailSent`=NOW(), `LastChangeDate`=`LastChangeDate` WHERE `id`=$_jIiQ8[id]";
          mysql_query($_QJlJ0, $_Q61I1);
          if(!empty($_jIiQ8['BounceStatus'])) {
              $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE `Action`='Bounced' AND `Member_id`=$_jIiQ8[id]";
              mysql_query($_QJlJ0, $_Q61I1);
          }
        }

        $_jtlLf++;

        $_jO6QC = "OK";
        if($_Q6Q1C["IsSendingNowFlag"] > 0)
          $_jO6QC = "POSSIBLY SEND, Script was terminated while sending email on last script call, I don't know sending status.";

        if($_Q6Q1C["mtas_id"] != $_jJt8t){
            if($_Q6Q1C["IsSendingNowFlag"] == 0)
              _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Sent', $_jO6QC, $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false) );
              else
              _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'PossiblySent', $_jO6QC, $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false) );
          }
          else {
            if($_Q6Q1C["IsSendingNowFlag"] == 0)
              _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Sent', $_jO6QC, $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], "SMS" );
              else
              _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'PossiblySent', $_jO6QC, $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], "SMS" );
          }

      } else {
        $_jO0tC++;
        if( _ORPER($_IiJit->errors["errorcode"], $_IiJit->errors["errortext"], $_jIfO0["Type"]) ) {
           $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE id=$_Q6Q1C[id]";
           mysql_query($_QJlJ0, $_Q61I1);

           if($_Q6Q1C["mtas_id"] != $_jJt8t){
             // problems here, should I delete the member itself???
             $_j6O8O .= "mail to $_jIiQ8[u_EMail] permanently undeliverable, Error:<br />".$_IiJit->errors["errortext"]."<br />it was removed from queue<br /><br />";

             if($_IiJit->errors["errorcode"] < 9000){ # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _ORALQ($_QlQC8, $_QlIf6, $_Q6Q1C["recipients_id"], true, false, $_IiJit->errors["errorcode"]." ".$_IiJit->errors["errortext"], !empty($_IiICC["ExternalBounceScript"]) ? $_IiICC["ExternalBounceScript"] : "");
              }

             _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "mail to $_jIiQ8[u_EMail] permanently undeliverable, Error: ".$_IiJit->errors["errortext"]."", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false) );

           } else{
             $_j6O8O .= "SMS to $_jIiQ8[u_CellNumber] is undeliverable, Error:<br />".join("", $_Ql1O8)."<br />it was removed from queue<br /><br />";
             _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "SMS to $_jIiQ8[u_CellNumber] is undeliverable, Error: ".join("", $_Ql1O8)." it was removed from queue", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], "SMS" );
           }


        } else {
           if($_Q6Q1C["SendEngineRetryCount"] + 1 < $_jOQQC) {

             // IsSendingNowFlag
             $_QJlJ0 = "UPDATE `$_QtjLI` SET `IsSendingNowFlag`=0 WHERE `id`=$_Q6Q1C[id]";
             mysql_query($_QJlJ0, $_Q61I1);

             if($_IiJit->errors["errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
               _ORALQ($_QlQC8, $_QlIf6, $_Q6Q1C["recipients_id"], false, true, $_IiJit->errors["errorcode"]." ".$_IiJit->errors["errortext"], !empty($_IiICC["ExternalBounceScript"]) ? $_IiICC["ExternalBounceScript"] : "");

             // temporarily undeliverable
             $_j6O8O .= "mail to $_jIiQ8[u_EMail] temporarily undeliverable, Error:<br />".$_IiJit->errors["errortext"]."<br /><br />";
             _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Prepared', "mail to $_jIiQ8[u_EMail] temporarily undeliverable, Error: ".$_IiJit->errors["errortext"]."", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false) );

           } else {
             $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `id`=$_Q6Q1C[id]";
             mysql_query($_QJlJ0, $_Q61I1);
             $_j6O8O .= "mail to  $_jIiQ8[u_EMail] temporarily undeliverable after $_jOQQC retries, Error:<br />".$_IiJit->errors["errortext"]."<br />it was removed from queue<br /><br />";

             if($_IiJit->errors["errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _ORALQ($_QlQC8, $_QlIf6, $_Q6Q1C["recipients_id"], false, true, $_IiJit->errors["errorcode"]." ".$_IiJit->errors["errortext"], !empty($_IiICC["ExternalBounceScript"]) ? $_IiICC["ExternalBounceScript"] : "");

             _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "mail to $_jIiQ8[u_EMail] temporarily undeliverable after $_jOQQC retries, Error: ".$_IiJit->errors["errortext"]."", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"], ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false) );

           }
        }
      }

      mysql_query("COMMIT", $_Q61I1);

      if( $_Q6Q1C["mtas_id"] != $_jJt8t && isset($_IiICC["SleepInMailSendingLoop"]) && $_IiICC["SleepInMailSendingLoop"] > 0 )
        if(function_exists('usleep'))
           usleep($_IiICC["SleepInMailSendingLoop"] * 1000); // mikrosekunden
           else
           sleep( (int) ($_IiICC["SleepInMailSendingLoop"] / 1000) );   // sekunden

    } # while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1))
    if($_Q60l1)
      mysql_free_result($_Q60l1);
  }


  function _ORPER($Code, $_jOfjQ, $_jOf6Q) {
    if($Code == 250) return false;

    if($Code == 11001) return true; // host not found

    if($Code == MonthlySendQuotaExceeded || $Code == SendQuotaExceeded || $Code == RecipientIsInECGList){
      return true;
    }

    if($_jOf6Q == "mail")
      if (strpos($_jOfjQ, "returned failure") !== false) // PHP mail error
         return true;
    if($_jOf6Q == "SMS")
       return true;

    // SMTP errors
    if($Code == 450) return false; // z.B. too much email
    if($Code == 553) return false; // Auth
    if($Code == 535) return false; // Auth
    if($Code == 421) return true;
    if($Code >= 451 && $Code <= 452 ) return true;
    if(
       ($Code >= 500 && $Code <= 534) ||
       ($Code >= 536 && $Code <= 552) ||
       ($Code >= 554 && $Code <= 699)
      )
        return true;

    /*if(stripos($_jOfjQ, 'harvester') !== false)
       return true;
    if(stripos($_jOfjQ, ' spam ') !== false)
       return true;*/

    if($_jOf6Q == 'smtpmx') {
      if(stripos($_jOfjQ, 'relaying') !== false)
        return true;
      if(stripos($_jOfjQ, 'dynamic') !== false)
        return true;
      if(stripos($_jOfjQ, '11001') !== false)
        return true;
    }

    return false;
  }

  function _ORALQ($_QlQC8, $_QlIf6, $_jOfC1, $_jO8o6, $_jOtj0, $_I0600 = "", $_j8JIJ = "") {
     global $_Q61I1;
     if( $_jOfC1 <= 0 )
       return true;

     if($_jO8o6) {
       $_QJlJ0 = "UPDATE `$_QlQC8` SET `BounceStatus`='PermanentlyBounced', `HardbounceCount`=HardbounceCount+1 WHERE `id`=$_jOfC1";
       mysql_query($_QJlJ0, $_Q61I1);
       $_QJlJ0 = "INSERT INTO `$_QlIf6` SET `ActionDate`=NOW(), `Action`='Bounced', `AText`="._OPQLR($_I0600).", `Member_id`=$_jOfC1";
       mysql_query($_QJlJ0, $_Q61I1);
       if(!empty($_j8JIJ)){
          $_QJlJ0 = "SELECT `u_EMail`, `HardbounceCount` FROM `$_QlQC8` WHERE `id`=$_jOfC1";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
            CallExternalBounceScript($_j8JIJ, $_Q6Q1C["u_EMail"], 'PermanentlyBounced', $_Q6Q1C["HardbounceCount"]);
            mysql_free_result($_Q60l1);
          }
       }

     } else
       if($_jOtj0) {
         $_QJlJ0 = "UPDATE `$_QlQC8` SET `BounceStatus`='TemporarilyBounced', `SoftbounceCount`=`SoftbounceCount`+1 WHERE `id`=$_jOfC1";
         mysql_query($_QJlJ0, $_Q61I1);

         if(!empty($_j8JIJ)){
            $_QJlJ0 = "SELECT `u_EMail`, `SoftbounceCount` FROM `$_QlQC8` WHERE `id`=$_jOfC1";
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
              CallExternalBounceScript($_j8JIJ, $_Q6Q1C["u_EMail"], 'TemporarilyBounced', $_Q6Q1C["SoftbounceCount"]);
              mysql_free_result($_Q60l1);
            }
         }

       } else{
         $_QtIiC = array();
         _L1J0J(array($_jOfC1), $_QtIiC);
       }

     return true;
  }

  function _ORA61($_ICCti, $_jOt88, $_jfiol, $_jOOjQ, $_QtJo6, $_jOO8l = 0, $_jOfC1 = 0, $_jOOio = 0, $_IQojt = "") {
    global $_Q61I1;
    if( ($_ICCti == "Autoresponder" || $_ICCti == "BirthdayResponder" || $_ICCti == "RSS2EMailResponder" || $_ICCti == "EventResponder" || $_ICCti == "Campaign" || $_ICCti == "DistributionList") && $_jOO8l != 0 ) {
        $_QJlJ0 = "UPDATE `$_jOt88` SET `Send`='$_jOOjQ', `SendResult`="._OPQLR($_QtJo6);
        if($_IQojt != "")
           $_QJlJ0 .= ", MailSubject="._OPQLR($_IQojt);
      }
      else
      if($_ICCti == "FollowUpResponder" && $_jOfC1 != 0 && $_jOOio != 0) {
         $_QJlJ0 = "UPDATE `$_jOt88` SET `Send`='$_jOOjQ', `SendResult`="._OPQLR($_QtJo6);
         if($_IQojt != "")
            $_QJlJ0 .= ", MailSubject="._OPQLR($_IQojt);
        }
        else
         return false;
    $_QJlJ0 .= " WHERE `id`=$_jfiol";
    mysql_query($_QJlJ0, $_Q61I1);
  }

  // => cron_bounces.inc.php, cron_sendenginge.inc.php
  if(!function_exists("CallExternalBounceScript")){
    function CallExternalBounceScript($_j8JIJ, $EMail, $BounceType, $BounceCount) {
       global $AppName;
       if($_j8JIJ == "") return true;

       $_j88of = 0;
       $_j8t8L = "";
       $_j8O60 = 80;
       if(strpos($_j8JIJ, "http://") !== false) {
          $_j8O8t = substr($_j8JIJ, 7);
       } elseif(strpos($_j8JIJ, "https://") !== false) {
         $_j8O60 = 443;
         $_j8O8t = substr($_j8JIJ, 8);
       }
       $_QCoLj = substr($_j8O8t, strpos($_j8O8t, "/"));
       $_j8O8t = substr($_j8O8t, 0, strpos($_j8O8t, "/"));

       $_Qf1i1 = "AppName=$AppName&EMail=$EMail&BounceType=$BounceType&BounceCount=$BounceCount";
       _OCQDE($_j8O8t, "GET", $_QCoLj, $_Qf1i1, 0, $_j8O60, false, "", "", $_j88of, $_j8t8L);
    }
  }


?>
