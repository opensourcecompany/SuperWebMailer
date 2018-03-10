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

  include_once("inboxcheck.php");
  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("mailcreate.inc.php");

  function _O6E8R(&$_j6O8O) {
    global $_Q61I1;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $resourcestrings, $_Q8f1L, $_Q880O;
    global $_QolLi, $_Qofoi;
    global $_IQL81, $_II8J0;

    $_j6O8O = "Autoresponder checking starts...<br />";
    $_jfflo = 0;

    $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE UserType='Admin' AND IsActive>0";
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

      $_QJlJ0 = "SELECT Theme FROM $_Q880O WHERE id=$INTERFACE_THEMESID";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_Q8OiJ[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);


      $_QJlJ0 = "SELECT $_IQL81.*, $_IQL81.id AS Autoresponders_id, $_IQL81.Name AS Autoresponders_Name, $_Qofoi.*, $_Qofoi.id AS MTA_id, $_QolLi.*, $_QolLi.Name AS InboxName FROM $_IQL81 LEFT JOIN $_Qofoi ON $_Qofoi.id=$_IQL81.mtas_id LEFT JOIN $_QolLi ON $_IQL81.inboxes_id=$_QolLi.id ";
      $_QJlJ0 .= "WHERE $_IQL81.IsActive=1 AND ";
      $_QJlJ0 .= "IF($_IQL81.NoTiming>0, 1, IF($_IQL81.TimingFrom<$_IQL81.TimingTo, (CURTIME()>=$_IQL81.TimingFrom AND CURTIME()<=$_IQL81.TimingTo), CURTIME()>=$_IQL81.TimingFrom OR CURTIME()<=$_IQL81.TimingTo) )";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);

      if(mysql_error($_Q61I1) != "")
        $_j6O8O .= "MySQL error while selecting data: ".mysql_error($_Q61I1);

      while($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
        if(trim($_Q8OiJ["EMailAnswerSubjects"]) != "")
          $_Q8OiJ["EMailAnswerSubjects"] = explode(";", $_Q8OiJ["EMailAnswerSubjects"]);
          else
          $_Q8OiJ["EMailAnswerSubjects"] = array();
        _OPQ6J();
        $_jf8CQ = 0;
        $_j6O8O .= "checking $_Q8OiJ[InboxName]...<br />";

        $_jftQf = new _ODCLL();
        $_jftQf->Name = $_Q8OiJ["InboxName"];
        $_jftQf->InboxType = $_Q8OiJ["InboxType"]; // 'pop3', 'imap'
        $_jftQf->EMailAddress = $_Q8OiJ["EMailAddress"];
        $_jftQf->Servername = $_Q8OiJ["Servername"];
        $_jftQf->Serverport = $_Q8OiJ["Serverport"];
        $_jftQf->Username = $_Q8OiJ["Username"];
        $_jftQf->Password = $_Q8OiJ["Password"];
        $_jftQf->SSLConnection = $_Q8OiJ["SSL"];
        $_jftQf->LeaveMessagesInInbox = true; // ever leave in inbox
        $_jftQf->NumberOfEMailsToProcess = $_Q8OiJ["MaxEMailsToProcess"];
        $_jftQf->EntireMessage = false; // header is enough
        if($_Q8OiJ["ResponderUIDL"] != "") {
             $_jftQf->UIDL = @unserialize($_Q8OiJ["ResponderUIDL"]);
             if($_jftQf->UIDL === false)
                $_jftQf->UIDL = array();
           }
           else
           $_jftQf->UIDL = array();

        if(isset($_IJ6Cf))
          unset($_IJ6Cf);
        $_IJ6Cf = array();
        $_jj0JO = "";
        $_jft86 = 0;
        if(!$_jftQf->_ODA80($_jj0JO, $_IJ6Cf, $_jft86) ) {
           $_j6O8O .= "Error: ".$_jj0JO."; count of mails: ".$_jft86."<br />";

           // save UIDL
           $_QJlJ0 = "UPDATE $_IQL81 SET ResponderUIDL="._OPQLR( serialize($_jftQf->UIDL) )." WHERE id=".$_Q8OiJ["Autoresponders_id"];
           mysql_query($_QJlJ0, $_Q61I1);

        } else {

          // save UIDL
          $_QJlJ0 = "UPDATE $_IQL81 SET ResponderUIDL="._OPQLR( serialize($_jftQf->UIDL) )." WHERE id=".$_Q8OiJ["Autoresponders_id"];
          mysql_query($_QJlJ0, $_Q61I1);

          $_j6O8O .= "Successfully; found new emails: ".$_jft86."; processable mails: ".count($_IJ6Cf)."<br />";
          if(count($_IJ6Cf) > 0) {
             for($_Q6llo=0; $_Q6llo<count($_IJ6Cf); $_Q6llo++) {
               _OPQ6J();
               if( _O6FQB($_IJ6Cf[$_Q6llo], $_Q8OiJ, $Subject, $_jj0JO) ) {
                  $_j6O8O .= "mail with subject '$Subject' answered<br />";
                  $_jf8CQ++;
                 }
                 else
                   if( $_jj0JO != "")
                      $_j6O8O .= "error while answering mail with subject '$Subject', Error: $_jj0JO<br />";
             } // for $_Q6llo

             // save AnsweredMails
             $_QJlJ0 = "UPDATE $_IQL81 SET EMailsProcessed=EMailsProcessed + $_jf8CQ WHERE id=".$_Q8OiJ["Autoresponders_id"];
             mysql_query($_QJlJ0, $_Q61I1);
          }
        }

        unset($_jftQf);

        $_jfflo += $_jf8CQ;
      }
      mysql_free_result($_Q8Oj8);
    }
    if($_Q60l1)
      mysql_free_result($_Q60l1);

    $_j6O8O .= "<br />Autoresponder checking end.";

    if($_jfflo)
      return true;
      else
      return -1;
  }

  function _O6FQB($_QiOo1, $_jfo1t, &$Subject, &$_jj0JO) {
    global $_Q61I1, $UserId, $_Q6QQL;
    global $_Q60QL, $_QlIf6, $_Q6JJJ, $_QtjLI, $_Qofjo, $_II8J0, $INTERFACE_LANGUAGE;

    $_jj0JO = "";

    if(!isset($_QiOo1["subject"]))
      $_QiOo1["subject"] = "";
    if(!isset($_QiOo1["from"])) {
      $_jj0JO = "no from address found.";
      return false;
    }

    $charset = "iso-8859-1";
    if(isset($_QiOo1["content-type"])) {
      $charset = $_QiOo1["content-type"];

      if(strpos($charset, "charset=") !== false)
        $charset = substr($charset, strpos($charset, "charset=") + 8);
      if(strpos($charset, ";") !== false)
        $charset = substr($charset, 0, strpos($charset, ";"));
      $charset = str_replace('"', '', $charset);
    }
    if(strpos($charset, "multipart/") !== false) {
     if(IsUtf8String($_QiOo1["subject"]) && utf8_decode($_QiOo1["subject"]) !== $_QiOo1["subject"] )
       $charset = "utf-8";
       else
       $charset = "iso-8859-1";
    }

    $Subject = ConvertString($charset, $_Q6QQL, $_QiOo1["subject"], false);

    if($_jfo1t["IgnoreHeaderXLoop"]) {
      if(isset($_QiOo1["x-loop"]) || isset($_QiOo1["x-auto-response-suppress"]) || (isset($_QiOo1["auto-submitted"]) && $_QiOo1["auto-submitted"] == "auto-generated" ) ) {
        $_jj0JO = "mail looping detected";
        return false;
      }
    }

    if(count($_jfo1t["EMailAnswerSubjects"]) > 0) {
      $_Qo1oC = false;
      for($_Q6llo=0; $_Q6llo<count($_jfo1t["EMailAnswerSubjects"]); $_Q6llo++){
        if(stripos($Subject, $_jfo1t["EMailAnswerSubjects"][$_Q6llo]) !== false) {
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
    $From = $_QiOo1["from"];
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
    unset($_IJ8oI);

    $charset = "iso-8859-1";
    if(isset($_QiOo1["content-type"])) {
      $charset = $_QiOo1["content-type"];

      if(strpos($charset, "charset=") !== false)
        $charset = substr($charset, strpos($charset, "charset=") + 8);
      if(strpos($charset, ";") !== false)
        $charset = substr($charset, 0, strpos($charset, ";"));
      $charset = str_replace('"', '', $charset);
    }
    if(strpos($charset, "multipart/") !== false) {
     if(IsUtf8String($_IJo16) && utf8_decode($_IJo16) !== $_IJo16 )
       $charset = "utf-8";
       else
       $charset = "iso-8859-1";
    }

    $_IJo16 = ConvertString($charset, $_Q6QQL, $_IJo16, false);

    $_jfo6I = true;
    $_QLitI = 0;
    $MailingListId = 0;
    $_jfoCi = 0;
    $_jfoLl = 0;
    $_QljIQ = "";
    $_QLI8o = "";

    if(_L0FRD($_IJoII)) {
     $_jj0JO = "member with email address '$_IJoII' is in global black list.";
     return false;
    }

    $_jfCtj = 0;

    if($_jfo1t["AutoresponderLimit"] && $_jfo1t["AutoresponderLimitPerDay"]){
      $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_II8J0` WHERE `autoresponders_id`=$_jfo1t[Autoresponders_id] AND `EMail`="._OPQLR($_IJoII)." AND DATE(`SendDateTime`)=DATE(NOW())";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
        $_jfCtj += $_Q6Q1C[0];
        mysql_free_result($_Q60l1);
        if($_jfCtj > $_jfo1t["AutoresponderLimitPerDay"]){
         $_jj0JO = "Daily autoresponder sending limit for '$_IJoII' reached.";
         return false;
        }
      }
    }

    if ($_jfo1t["maillists_id"] > 0) {
      $MailingListId = $_jfo1t["maillists_id"];
      $_QJlJ0 = "SELECT `Name`, `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName`, `MailLogTableName`, `FormsTableName`, `forms_id` FROM `$_Q60QL` WHERE id=$_jfo1t[maillists_id]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "")
        $_jj0JO .= "MySQL error while selecting data: ".mysql_error($_Q61I1);
      if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
        $_QlQC8 = $_Q6Q1C["MaillistTableName"];
        $_IiC6I = $_Q6Q1C["Name"];
        $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
        $_QljIQ = $_Q6Q1C["MailLogTableName"];
        $_QLI8o = $_Q6Q1C["FormsTableName"];
        $_jfoCi = $_Q6Q1C["forms_id"];
        $_jfoLl = $_jfo1t["forms_id"];
        mysql_free_result($_Q60l1);

        if(_L101P($_IJoII, $_jfo1t["maillists_id"], $_Q6Q1C["LocalBlocklistTableName"] )) {
         $_jj0JO = "member with email address '$_IJoII' is in local black list.";
         return false;
        }

        $_QJlJ0 = "SELECT `id`, `IsActive`, `SubscriptionStatus` FROM `$_QlQC8` WHERE `u_EMail`="._OPQLR($_IJoII);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
          $_Q6Q1C = mysql_fetch_array($_Q60l1);
          $_QLitI = $_Q6Q1C["id"];
          mysql_free_result($_Q60l1);
          if($_jfo1t["NoAnswerOnMemberExists"]) {
            $_jj0JO = "member with email address '$_IJoII' always exists in mailinglist '$_IiC6I'";
            return false;
          }
          if(!$_Q6Q1C["IsActive"]) {
            $_jj0JO = "member with email address '$_IJoII' is not active";
            return false;
          }
          if(!$_Q6Q1C["SubscriptionStatus"] == 'OptInConfirmationPending') {
            $_jj0JO = "member with email address '$_IJoII' is not subscribed";
            return false;
          }

          if($_jfo1t["AutoresponderLimit"] && $_jfo1t["AutoresponderLimitPerDay"]){
              $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_II8J0` WHERE `autoresponders_id`=$_jfo1t[Autoresponders_id] AND `recipients_id`=$_QLitI AND DATE(`SendDateTime`)=DATE(NOW())";
              $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
              if($_ItlJl && $_Q6Q1C = mysql_fetch_row($_ItlJl)) {
                 $_jfCtj += $_Q6Q1C[0];
                 mysql_free_result($_ItlJl);
                 if($_jfCtj > $_jfo1t["AutoresponderLimitPerDay"]){
                    $_jj0JO = "Daily autoresponder sending limit for '$_IJoII' reached.";
                    return false;
                 }
              }
          }


        } else {


          mysql_query("BEGIN", $_Q61I1);

          $_QJlJ0 = "INSERT INTO `$_QlQC8` SET IsActive=1, SubscriptionStatus='Subscribed', DateOfSubscription=NOW(), DateOfOptInConfirmation=NOW(), IPOnSubscription='Autoresponder - ".$_jfo1t["Autoresponders_Name"]."', u_EMail="._OPQLR($_IJoII);

          $_jfiIj = $_IJo16;
          $_jfiJo = "";
          if( strpos($_jfiIj, " ") !== false ) {
            $_jfiJo = trim(substr($_jfiIj, 0, strpos($_jfiIj, " ") ));
            $_jfiIj = trim(substr($_jfiIj, strpos($_jfiIj, " ") + 1));
            $_jfiJo = str_replace(',', '', $_jfiJo);
            $_jfiIj = str_replace(',', '', $_jfiIj);
          }

          if($_jfiIj || $_jfiJo) {
             $_jfiIj = str_replace('"', '', $_jfiIj);
             $_jfiJo = str_replace('"', '', $_jfiJo);
             $_QJlJ0 .= ", u_LastName="._OPQLR($_jfiIj).", u_FirstName="._OPQLR($_jfiJo);
          }

          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) > 0) {
           $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           $_QLitI = $_Q6Q1C[0];
           mysql_free_result($_Q60l1);
          }

          if($_QLitI != 0) {
            $_jfiOj = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Subscribed', Member_id=$_QLitI";
            mysql_query($_jfiOj, $_Q61I1);
          }

          mysql_query("COMMIT", $_Q61I1);

        }
      } else {
        $_jj0JO .= "Can't find mailing list with id $_jfo1t[maillists_id].";
        return false;
      }
    }

    if($_QLitI != 0) { // queue it?

      mysql_query("BEGIN", $_Q61I1);

      $_QJlJ0 = "INSERT INTO `$_II8J0` SET `autoresponders_id`=$_jfo1t[Autoresponders_id], `MailSubject`="._OPQLR($Subject).", `SendDateTime`=NOW(), `recipients_id`=$_QLitI, `Send`='Prepared'";
      mysql_query($_QJlJ0, $_Q61I1);

      $_jfiol = 0;
      if(mysql_affected_rows($_Q61I1) > 0) {
        $_jfLII = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
        $_jfl1j = mysql_fetch_array($_jfLII);
        $_jfiol = $_jfl1j[0];
        mysql_free_result($_jfLII);
      } else {
        if(mysql_errno($_Q61I1) == 1062) { // dup key, but no unique index set therefore it will never occur
          mysql_query("ROLLBACK", $_Q61I1);
          return true;
        }
        $_jj0JO = "MySQL error while adding to statistics table: ".mysql_error($_Q61I1);
        mysql_query("ROLLBACK", $_Q61I1);
        return false;
      }

      // $UserId is ever admin
      $_QJlJ0 = "INSERT INTO `$_QtjLI` SET `CreateDate`=NOW(), `statistics_id`=$_jfiol, `users_id`=$UserId, `Source`='Autoresponder', `MailSubject`="._OPQLR($Subject).", `Source_id`=$_jfo1t[Autoresponders_id], `maillists_id`=$MailingListId, `recipients_id`=$_QLitI, `mtas_id`=$_jfo1t[MTA_id], `LastSending`=NOW()";
      mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != "") {
        $_jj0JO = "MySQL error while adding to out queue: ".mysql_error($_Q61I1);
        mysql_query("ROLLBACK", $_Q61I1);
        return false;
      }

      mysql_query("COMMIT", $_Q61I1);


      return true;
    }

    // send it live we can't queue it

    $_jIiQ8 = array();
    $_QJlJ0 = "SELECT fieldname FROM `$_Qofjo` WHERE language='$INTERFACE_LANGUAGE'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_array($_Q60l1) )
      $_jIiQ8[$_Q6Q1C["fieldname"]] = "";
    mysql_free_result($_Q60l1);

    $_jIiQ8["id"] = 0;
    $_jIiQ8["u_EMail"] = $_IJoII;
    $_jIiQ8["u_EMailFormat"] = "HTML";

    if($_IJo16 != "")
      $_jIiQ8["u_LastName"] = $_IJo16;
    if( strpos($_jIiQ8["u_LastName"], " ") !== false ) {
      $_jIiQ8["u_FirstName"] = trim(substr($_jIiQ8["u_LastName"], 0, strpos($_jIiQ8["u_LastName"], " ") ));
      $_jIiQ8["u_LastName"] = trim(substr($_jIiQ8["u_LastName"], strpos($_jIiQ8["u_LastName"], " ") + 1));
      $_jIiQ8["u_FirstName"] = str_replace(',', '', $_jIiQ8["u_FirstName"]);
      $_jIiQ8["u_LastName"] = str_replace(',', '', $_jIiQ8["u_LastName"]);
    }

    return _O6FEF($_jfo1t, $_jIiQ8, $MailingListId, $_jfoCi, $_jfoLl, $_QLI8o, $_QljIQ, $Subject, $_jj0JO);
  }


  function _O6FEF($_jfo1t, $_jIiQ8, $MailingListId, $_jfoCi, $_jfoLl, $_QLI8o, $_QljIQ, $Subject, &$_jj0JO) {
    global $_II8J0;
    global $_Q61I1, $UserId, $OwnerUserId;
    global $_Q6JJJ, $_QtjLI;

    if($_QLI8o != ""){
      $_QJlJ0 = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_QLI8o` WHERE id=$_jfoLl";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      $_jfo1t["OverrideSubUnsubURL"] = $_Q6Q1C["OverrideSubUnsubURL"];
      $_jfo1t["OverrideTrackingURL"] = $_Q6Q1C["OverrideTrackingURL"];
      mysql_free_result($_Q60l1);
    }

    // mail object
    $_IiJit = new _OF0EE(mtAutoresponderEMail);

    $AdditionalHeaders = array();
    if(isset($_jfo1t["AddXLoop"]) && $_jfo1t["AddXLoop"])
       $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';//'<'.$_jfo1t["SenderFromAddress"].'>'
    if(isset($_jfo1t["AddListUnsubscribe"]) && $_jfo1t["AddListUnsubscribe"] && $MailingListId != 0 && $_jfoLl != 0)
       $AdditionalHeaders["List-Unsubscribe"] = '<'."[UnsubscribeLink]".'>';

    $errors = array();
    $_Ql1O8 = array();
    $_Ii6QI = "";
    $_Ii6lO = "";
    $_jfo1t["OrgMailSubject"] = $Subject;
    if(!_OED01($_IiJit, $_Ii6QI, $_Ii6lO, true, $_jfo1t, $_jIiQ8, $MailingListId, $_jfoCi, $_jfoLl, $errors, $_Ql1O8, $AdditionalHeaders) ) {
       $_jj0JO = join($_Q6JJJ, $_Ql1O8);
       _OBQEP($_jfo1t["SenderFromAddress"], join($_Q6JJJ, $_Ql1O8)."   ".$Subject);

       $_QJlJ0 = "INSERT INTO `$_II8J0` SET `autoresponders_id`=$_jfo1t[Autoresponders_id], `MailSubject`="._OPQLR(ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=0, `EMail`="._OPQLR($_jIiQ8["u_EMail"]).", `Send`='Failed', `SendResult`="._OPQLR($_jj0JO);
       mysql_query($_QJlJ0, $_Q61I1);

       return false;
    }

    # Demo version
    if(defined("DEMO")  || defined("SimulateMailSending"))
      $_IiJit->Sendvariant = "null";

    // send email
    $_Q8COf = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);
    unset($_Ii6QI);
    unset($_Ii6lO);

    if(!$_Q8COf) {
      $_jj0JO = "Error code: ".$_IiJit->errors["errorcode"]." Error text: ".$_IiJit->errors["errortext"];

      $_QJlJ0 = "INSERT INTO `$_II8J0` SET `autoresponders_id`=$_jfo1t[Autoresponders_id], `MailSubject`="._OPQLR(ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=0,`EMail`="._OPQLR($_jIiQ8["u_EMail"]).", `Send`='Failed', `SendResult`="._OPQLR($_jj0JO);
      mysql_query($_QJlJ0, $_Q61I1);

      unset($_IiJit);

      return false;
    }

    $_QJlJ0 = "INSERT INTO `$_II8J0` SET `autoresponders_id`=$_jfo1t[Autoresponders_id], `MailSubject`="._OPQLR(ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=0, `EMail`="._OPQLR($_jIiQ8["u_EMail"]).", `Send`='Sent', `SendResult`='OK'";
    mysql_query($_QJlJ0, $_Q61I1);

    $_IiJit->_OF0FL($_QljIQ, 0, ConvertString($_IiJit->charset, "UTF-8", $_IiJit->Subject, false));

    unset($_IiJit);
    return true;
  }

?>
