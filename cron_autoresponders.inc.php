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
  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("mailcreate.inc.php");

  function _LLQ8B(&$_JIfo0) {
    global $_QLttI, $_QLo06;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $resourcestrings, $_I18lo, $_I1tQf;
    global $_IjljI, $_Ijt0i;
    global $_IoCo0, $_ICIJo;

    $_JIfo0 = "Autoresponder checking starts...<br />";
    $_JjCfi = 0;

    $_QLfol = "SELECT * FROM $_I18lo WHERE UserType='Admin' AND IsActive>0";
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

      $_QLfol = "SELECT Theme FROM $_I1tQf WHERE id=$INTERFACE_THEMESID";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      $_I1OfI = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_I1OfI[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);


      $_QLfol = "SELECT $_IoCo0.*, $_IoCo0.id AS Autoresponders_id, $_IoCo0.Name AS Autoresponders_Name, $_Ijt0i.*, $_Ijt0i.id AS MTA_id, $_IjljI.*, $_IjljI.Name AS InboxName FROM $_IoCo0 LEFT JOIN $_Ijt0i ON $_Ijt0i.id=$_IoCo0.mtas_id LEFT JOIN $_IjljI ON $_IoCo0.inboxes_id=$_IjljI.id ";
      $_QLfol .= "WHERE $_IoCo0.IsActive=1 AND ";
      $_QLfol .= "IF($_IoCo0.NoTiming>0, 1,

                  IF($_IoCo0.TimingFrom<$_IoCo0.TimingTo, (CURTIME()>=$_IoCo0.TimingFrom AND CURTIME()<=$_IoCo0.TimingTo), CURTIME()>=$_IoCo0.TimingFrom OR CURTIME()<=$_IoCo0.TimingTo)

                  AND

                  IF(FIND_IN_SET('every day', $_IoCo0.TimingMultipleDayNames) > 0, 1, FIND_IN_SET( CAST(WEEKDAY(NOW()) AS CHAR), $_IoCo0.TimingMultipleDayNames) > 0)

              )";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);

      if(mysql_error($_QLttI) != "")
        $_JIfo0 .= "MySQL error while selecting data: ".mysql_error($_QLttI);

      while($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
        if(trim($_I1OfI["EMailAnswerSubjects"]) != ""){
          $_I1OfI["EMailAnswerSubjects"] = explode(";", $_I1OfI["EMailAnswerSubjects"]);
          }
          else
          $_I1OfI["EMailAnswerSubjects"] = array();
        _LRCOC();
        $_JjiJl = 0;
        $_JIfo0 .= "checking $_I1OfI[InboxName]...<br />";

        $_Jji6J = new _LCC0O();
        $_Jji6J->Name = $_I1OfI["InboxName"];
        $_Jji6J->InboxType = $_I1OfI["InboxType"]; // 'pop3', 'imap'
        $_Jji6J->EMailAddress = $_I1OfI["EMailAddress"];
        $_Jji6J->Servername = $_I1OfI["Servername"];
        $_Jji6J->Serverport = $_I1OfI["Serverport"];
        $_Jji6J->Username = $_I1OfI["Username"];
        $_Jji6J->Password = $_I1OfI["Password"];
        $_Jji6J->SSLConnection = $_I1OfI["SSL"];
        $_Jji6J->LeaveMessagesInInbox = true; // ever leave in inbox
        $_Jji6J->NumberOfEMailsToProcess = $_I1OfI["MaxEMailsToProcess"];
        $_Jji6J->EntireMessage = false; // header is enough
        $_Jji6J->UnseenMessagesOnly = $_I1OfI["UnseenMsgsOnly"]; 
        if($_I1OfI["ResponderUIDL"] != "") {
             $_Jji6J->UIDL = @unserialize($_I1OfI["ResponderUIDL"]);
             if($_Jji6J->UIDL === false)
                $_Jji6J->UIDL = array();
           }
           else
           $_Jji6J->UIDL = array();

        if(isset($_ILJIO))
          unset($_ILJIO);
        $_ILJIO = array();
        $_J0COJ = "";
        $_JjLJ1 = 0;
        if(!$_Jji6J->_LCACA($_J0COJ, $_ILJIO, $_JjLJ1) ) {
           $_JIfo0 .= "Error: ".$_J0COJ."; count of mails: ".$_JjLJ1."<br />";

           // save UIDL
           $_QLfol = "UPDATE $_IoCo0 SET ResponderUIDL="._LRAFO( serialize($_Jji6J->UIDL) )." WHERE id=".$_I1OfI["Autoresponders_id"];
           mysql_query($_QLfol, $_QLttI);

        } else {

          // save UIDL
          $_QLfol = "UPDATE $_IoCo0 SET ResponderUIDL="._LRAFO( serialize($_Jji6J->UIDL) )." WHERE id=".$_I1OfI["Autoresponders_id"];
          mysql_query($_QLfol, $_QLttI);

          $_JIfo0 .= "Successfully; found new emails: ".$_JjLJ1."; processable mails: ".count($_ILJIO)."<br />";
          if(count($_ILJIO) > 0) {
             for($_Qli6J=0; $_Qli6J<count($_ILJIO); $_Qli6J++) {
               _LRCOC();
               if( _LLOQA($_ILJIO[$_Qli6J], $_I1OfI, $Subject, $_J0COJ) ) {
                  $Subject = _LA8F6($Subject);
                  $Subject = _LC6CP( str_replace("&amp;", "&", htmlspecialchars($Subject, ENT_COMPAT, $_QLo06, false)) );

                  $_JIfo0 .= "mail with subject '$Subject' answered<br />";
                  $_JjiJl++;
                 }
                 else
                   if( $_J0COJ != "") {
                      $Subject = _LA8F6($Subject);
                      $Subject = _LC6CP( str_replace("&amp;", "&", htmlspecialchars($Subject, ENT_COMPAT, $_QLo06, false)) );
                      $_JIfo0 .= "error while answering mail with subject '$Subject', Error: $_J0COJ<br />";
                   }
             } // for $_Qli6J

             // save AnsweredMails
             $_QLfol = "UPDATE $_IoCo0 SET EMailsProcessed=EMailsProcessed + $_JjiJl WHERE id=".$_I1OfI["Autoresponders_id"];
             mysql_query($_QLfol, $_QLttI);
          }
        }

        unset($_Jji6J);

        $_JjCfi += $_JjiJl;
      }
      mysql_free_result($_I1O6j);
    }
    if($_QL8i1)
      mysql_free_result($_QL8i1);

    $_JIfo0 .= "<br />Autoresponder checking end.";

    if($_JjCfi)
      return true;
      else
      return -1;
  }

  function _LLOQA($_I6C0o, $_JjLLC, &$Subject, &$_J0COJ) {
    global $_QLttI, $UserId, $_QLo06;
    global $_QL88I, $_I8jjj, $_QLl1Q, $_IQQot, $_Ij8oL, $_ICIJo, $INTERFACE_LANGUAGE;

    // PHP doesn't support this encodings change to iso-8859-1
    $_Jjl11 = array("us-ascii", "ascii", "ibm852", "cp-850", "windows-1250", "iso-8859-2", "iso-2022-jp");

    $_J0COJ = "";

    if(!isset($_I6C0o["subject"]))
      $_I6C0o["subject"] = "";
    if(!isset($_I6C0o["from"])) {
      $_J0COJ = "no from address found.";
      return false;
    }

    $charset = "iso-8859-1";
    if(isset($_I6C0o["content-type"])) {
      $charset = $_I6C0o["content-type"];

      if(strpos($charset, "charset=") !== false)
        $charset = substr($charset, strpos($charset, "charset=") + 8);
      if(strpos($charset, ";") !== false)
        $charset = substr($charset, 0, strpos($charset, ";"));
      $charset = str_replace('"', '', $charset);
    }

    $Subject = $_I6C0o["subject"]; 
    
    if(strpos($charset, "multipart/") !== false) {
     if(IsUtf8String($_I6C0o["subject"]) && utf8_decode($Subject) !== $Subject )
       $charset = "utf-8";
       else
       $charset = "iso-8859-1";
    }

    $charset = strtolower($charset);
    if($charset == "us-ascii" && IsUtf8String($Subject)){ // Apple Mail?
      $charset = "utf-8";
    }  

    if( in_arrayi($charset, $_Jjl11) ){ 
      $charset = "iso-8859-1";
    }  

    if($charset == "utf-8" && !IsUtf8String($Subject)) // Subject in header has other encoding as content of email
      $Subject = ConvertString("iso-8859-1", $_QLo06, $Subject, false);

    $Subject = ConvertString($charset, $_QLo06, $Subject, false);
    $Subject = _LA8F6($Subject);

    if($_JjLLC["IgnoreHeaderXLoop"]) {
      if(isset($_I6C0o["x-loop"]) || isset($_I6C0o["x-auto-response-suppress"]) || (isset($_I6C0o["auto-submitted"]) && $_I6C0o["auto-submitted"] == "auto-generated" ) ) {
        $_J0COJ = "mail looping detected";
        return false;
      }
    }

    if(count($_JjLLC["EMailAnswerSubjects"]) ) {
      $_QLCt1 = false;
      for($_Qli6J=0; $_Qli6J<count($_JjLLC["EMailAnswerSubjects"]); $_Qli6J++){
        if(stripos($Subject, $_JjLLC["EMailAnswerSubjects"][$_Qli6J]) !== false) {
          $_QLCt1 = true;
          break;
        }
      }
      if(!$_QLCt1) {
        $_J0COJ = "required mail subject not found";
        return false;
      }
    }

    $_IL6Jt = new Mail_RFC822();

    $From = "";
    if($_JjLLC["AnswerHeaderField"] == "ReturnPath" && isset($_I6C0o["return-path"]))
      $From = $_I6C0o["return-path"];
      else
      if($_JjLLC["AnswerHeaderField"] == "ReplyTo" && isset($_I6C0o["reply-to"]))
         $From = $_I6C0o["reply-to"];

    if(empty($From))
      $From = $_I6C0o["from"];

    $_ILfoj = "";
    $_ILf66 = $_IL6Jt->parseAddressList($From, null, null, false); // no ASCII check

    if ( !(IsPEARError($_ILf66)) && isset($_ILf66[0]->mailbox) && isset($_ILf66[0]->host) ) {
      $_IL8oI = $_ILf66[0]->mailbox."@".$_ILf66[0]->host;

      if(isset($_ILf66[0]->personal))
        $_ILfoj = $_ILf66[0]->personal;

    } else {
      $_J0COJ = "invalid from address.";
      return false;
    }
    unset($_IL6Jt);

    $charset = "iso-8859-1";
    if(isset($_I6C0o["content-type"])) {
      $charset = $_I6C0o["content-type"];

      if(strpos($charset, "charset=") !== false)
        $charset = substr($charset, strpos($charset, "charset=") + 8);
      if(strpos($charset, ";") !== false)
        $charset = substr($charset, 0, strpos($charset, ";"));
      $charset = str_replace('"', '', $charset);
    }
    if(strpos($charset, "multipart/") !== false) {
     if(IsUtf8String($_ILfoj) && utf8_decode($_ILfoj) !== $_ILfoj )
       $charset = "utf-8";
       else
       $charset = "iso-8859-1";
    }

    $_ILfoj = ConvertString($charset, $_QLo06, $_ILfoj, false);

    $_ILfoj = _LA8F6($_ILfoj);
    $_ILfoj = str_replace("&amp;", "&", htmlspecialchars($_ILfoj, ENT_COMPAT, $_QLo06, false));

    $_Jjl80 = true;
    $_IfLJj = 0;
    $MailingListId = 0;
    $_Jjlll = 0;
    $_JJ0t1 = 0;
    $_I8jLt = "";
    $_IfJoo = "";

    if(_J18DA($_IL8oI)) {
     $_J0COJ = "member with email address '$_IL8oI' is in global black list.";
     return false;
    }

    $_JJ1QJ = 0;

    if($_JjLLC["AutoresponderLimit"] && $_JjLLC["AutoresponderLimitPerDay"]){
      $_QLfol = "SELECT COUNT(`id`) FROM `$_ICIJo` WHERE `autoresponders_id`=$_JjLLC[Autoresponders_id] AND `EMail`="._LRAFO($_IL8oI)." AND DATE(`SendDateTime`)=DATE(NOW())";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
        $_JJ1QJ += $_QLO0f[0];
        mysql_free_result($_QL8i1);
        if($_JJ1QJ > $_JjLLC["AutoresponderLimitPerDay"]){
         $_J0COJ = "Daily autoresponder sending limit for '$_IL8oI' reached.";
         return false;
        }
      }
    }

    if ($_JjLLC["maillists_id"] > 0) {
      $MailingListId = $_JjLLC["maillists_id"];
      $_QLfol = "SELECT `Name`, `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName`, `MailLogTableName`, `FormsTableName`, `forms_id` FROM `$_QL88I` WHERE id=$_JjLLC[maillists_id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != "")
        $_J0COJ .= "MySQL error while selecting data: ".mysql_error($_QLttI);
      if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
        $_I8I6o = $_QLO0f["MaillistTableName"];
        $_jtICQ = $_QLO0f["Name"];
        $_I8jjj = $_QLO0f["StatisticsTableName"];
        $_I8jLt = $_QLO0f["MailLogTableName"];
        $_IfJoo = $_QLO0f["FormsTableName"];
        $_Jjlll = $_QLO0f["forms_id"];
        $_JJ0t1 = $_JjLLC["forms_id"];
        mysql_free_result($_QL8i1);

        if(_J18FQ($_IL8oI, $_JjLLC["maillists_id"], $_QLO0f["LocalBlocklistTableName"] )) {
         $_J0COJ = "member with email address '$_IL8oI' is in local black list.";
         return false;
        }

        $_QLfol = "SELECT `id`, `IsActive`, `SubscriptionStatus` FROM `$_I8I6o` WHERE `u_EMail`="._LRAFO($_IL8oI);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
          $_QLO0f = mysql_fetch_array($_QL8i1);
          $_IfLJj = $_QLO0f["id"];
          mysql_free_result($_QL8i1);
          if($_JjLLC["NoAnswerOnMemberExists"]) {
            $_J0COJ = "member with email address '$_IL8oI' always exists in mailinglist '$_jtICQ'";
            return false;
          }
          if(!$_QLO0f["IsActive"]) {
            $_J0COJ = "member with email address '$_IL8oI' is not active";
            return false;
          }
          if(!$_QLO0f["SubscriptionStatus"] == 'OptInConfirmationPending') {
            $_J0COJ = "member with email address '$_IL8oI' is not subscribed";
            return false;
          }

          if($_JjLLC["AutoresponderLimit"] && $_JjLLC["AutoresponderLimitPerDay"]){
              $_QLfol = "SELECT COUNT(`id`) FROM `$_ICIJo` WHERE `autoresponders_id`=$_JjLLC[Autoresponders_id] AND `recipients_id`=$_IfLJj AND DATE(`SendDateTime`)=DATE(NOW())";
              $_jjJfo = mysql_query($_QLfol, $_QLttI);
              if($_jjJfo && $_QLO0f = mysql_fetch_row($_jjJfo)) {
                 $_JJ1QJ += $_QLO0f[0];
                 mysql_free_result($_jjJfo);
                 if($_JJ1QJ > $_JjLLC["AutoresponderLimitPerDay"]){
                    $_J0COJ = "Daily autoresponder sending limit for '$_IL8oI' reached.";
                    return false;
                 }
              }
          }


        } else {


          mysql_query("BEGIN", $_QLttI);

          $_QLfol = "INSERT INTO `$_I8I6o` SET IsActive=1, SubscriptionStatus='Subscribed', DateOfSubscription=NOW(), DateOfOptInConfirmation=NOW(), IPOnSubscription='Autoresponder - ".$_JjLLC["Autoresponders_Name"]."', u_EMail="._LRAFO($_IL8oI);

          $_JJ1fi = $_ILfoj;
          $_JJQ1l = "";
          if( strpos($_JJ1fi, " ") !== false ) {
            $_JJQ1l = trim(substr($_JJ1fi, 0, strpos($_JJ1fi, " ") ));
            $_JJ1fi = trim(substr($_JJ1fi, strpos($_JJ1fi, " ") + 1));
            $_JJQ1l = str_replace(',', '', $_JJQ1l);
            $_JJ1fi = str_replace(',', '', $_JJ1fi);
          }

          if($_JJ1fi || $_JJQ1l) {
             $_JJ1fi = str_replace('"', '', $_JJ1fi);
             $_JJQ1l = str_replace('"', '', $_JJQ1l);
             $_QLfol .= ", u_LastName="._LRAFO($_JJ1fi).", u_FirstName="._LRAFO($_JJQ1l);
          }

          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) > 0) {
           $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           $_IfLJj = $_QLO0f[0];
           mysql_free_result($_QL8i1);
          }

          if($_IfLJj != 0) {
            $_JJQQi = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Subscribed', Member_id=$_IfLJj";
            mysql_query($_JJQQi, $_QLttI);
          }

          mysql_query("COMMIT", $_QLttI);

        }
      } else {
        $_J0COJ .= "Can't find mailing list with id $_JjLLC[maillists_id].";
        return false;
      }
    }

    if($_IfLJj != 0) { // queue it?

      mysql_query("BEGIN", $_QLttI);

      $_QLfol = "INSERT INTO `$_ICIJo` SET `autoresponders_id`=$_JjLLC[Autoresponders_id], `MailSubject`="._LRAFO($Subject).", `SendDateTime`=NOW(), `recipients_id`=$_IfLJj, `Send`='Prepared'";
      mysql_query($_QLfol, $_QLttI);

      $_JJQ6I = 0;
      if(mysql_affected_rows($_QLttI) > 0) {
        $_JJQlj = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
        $_JJIl0 = mysql_fetch_array($_JJQlj);
        $_JJQ6I = $_JJIl0[0];
        mysql_free_result($_JJQlj);
      } else {
        if(mysql_errno($_QLttI) == 1062) { // dup key, but no unique index set therefore it will never occur
          mysql_query("ROLLBACK", $_QLttI);
          return true;
        }
        $_J0COJ = "MySQL error while adding to statistics table: ".mysql_error($_QLttI);
        mysql_query("ROLLBACK", $_QLttI);
        return false;
      }

      // $UserId is ever admin
      $_QLfol = "INSERT INTO `$_IQQot` SET `CreateDate`=NOW(), `statistics_id`=$_JJQ6I, `users_id`=$UserId, `Source`='Autoresponder', `MailSubject`="._LRAFO($Subject).", `Source_id`=$_JjLLC[Autoresponders_id], `maillists_id`=$MailingListId, `recipients_id`=$_IfLJj, `mtas_id`=$_JjLLC[MTA_id], `LastSending`=NOW(), `IsResponder`=1";
      mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != "") {
        $_J0COJ = "MySQL error while adding to out queue: ".mysql_error($_QLttI);
        mysql_query("ROLLBACK", $_QLttI);
        return false;
      }

      mysql_query("COMMIT", $_QLttI);


      return true;
    }

    // send it live we can't queue it

    $_j11Io = array();
    $_QLfol = "SELECT fieldname FROM `$_Ij8oL` WHERE language='$INTERFACE_LANGUAGE'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_array($_QL8i1) )
      $_j11Io[$_QLO0f["fieldname"]] = "";
    mysql_free_result($_QL8i1);

    $_j11Io["id"] = 0;
    $_j11Io["u_EMail"] = $_IL8oI;
    $_j11Io["u_EMailFormat"] = "HTML";

    if($_ILfoj != "")
      $_j11Io["u_LastName"] = $_ILfoj;
    if( strpos($_j11Io["u_LastName"], " ") !== false ) {
      $_j11Io["u_FirstName"] = trim(substr($_j11Io["u_LastName"], 0, strpos($_j11Io["u_LastName"], " ") ));
      $_j11Io["u_LastName"] = trim(substr($_j11Io["u_LastName"], strpos($_j11Io["u_LastName"], " ") + 1));
      $_j11Io["u_FirstName"] = str_replace(',', '', $_j11Io["u_FirstName"]);
      $_j11Io["u_LastName"] = str_replace(',', '', $_j11Io["u_LastName"]);
    }

    return _LLOC0($_JjLLC, $_j11Io, $MailingListId, $_Jjlll, $_JJ0t1, $_IfJoo, $_I8jLt, $Subject, $_J0COJ);
  }


  function _LLOC0($_JjLLC, $_j11Io, $MailingListId, $_Jjlll, $_JJ0t1, $_IfJoo, $_I8jLt, $Subject, &$_J0COJ) {
    global $_ICIJo;
    global $_QLttI, $UserId, $OwnerUserId;
    global $_QLl1Q, $_IQQot;

    if($_IfJoo != ""){
      $_QLfol = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_IfJoo` WHERE id=$_JJ0t1";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      $_JjLLC["OverrideSubUnsubURL"] = $_QLO0f["OverrideSubUnsubURL"];
      $_JjLLC["OverrideTrackingURL"] = $_QLO0f["OverrideTrackingURL"];
      mysql_free_result($_QL8i1);
    }

    // mail object
    $_j10IJ = new _LEFO8(mtAutoresponderEMail);
    $_j10IJ->DisableECGListCheck(); // Autoresponder no ECG list

    $AdditionalHeaders = array();
    if(isset($_JjLLC["AddXLoop"]) && $_JjLLC["AddXLoop"])
       $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';//'<'.$_JjLLC["SenderFromAddress"].'>'
    if(isset($_JjLLC["AddListUnsubscribe"]) && $_JjLLC["AddListUnsubscribe"] && $MailingListId != 0 && $_JJ0t1 != 0)
       $AdditionalHeaders["List-Unsubscribe"] = '<'."[UnsubscribeLink]".'>';

    $errors = array();
    $_I816i = array();
    $_j108i = "";
    $_j10O1 = "";
    $_JjLLC["OrgMailSubject"] = $Subject;
    if(!_LEJE8($_j10IJ, $_j108i, $_j10O1, true, $_JjLLC, $_j11Io, $MailingListId, $_Jjlll, $_JJ0t1, $errors, $_I816i, $AdditionalHeaders) ) {
       $_J0COJ = join($_QLl1Q, $_I816i);
       _LPAOD($_JjLLC["SenderFromAddress"], join($_QLl1Q, $_I816i)."   ".$Subject);

       $_QLfol = "INSERT INTO `$_ICIJo` SET `autoresponders_id`=$_JjLLC[Autoresponders_id], `MailSubject`="._LRAFO(ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=0, `EMail`="._LRAFO($_j11Io["u_EMail"]).", `Send`='Failed', `SendResult`="._LRAFO($_J0COJ);
       mysql_query($_QLfol, $_QLttI);

       return false;
    }

    # Demo version
    if(defined("DEMO")  || defined("SimulateMailSending"))
      $_j10IJ->Sendvariant = "null";

    // send email
    $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);
    unset($_j108i);
    unset($_j10O1);

    if(!$_I1o8o) {
      $_J0COJ = "Error code: ".$_j10IJ->errors["errorcode"]." Error text: ".$_j10IJ->errors["errortext"];

      $_QLfol = "INSERT INTO `$_ICIJo` SET `autoresponders_id`=$_JjLLC[Autoresponders_id], `MailSubject`="._LRAFO(ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=0,`EMail`="._LRAFO($_j11Io["u_EMail"]).", `Send`='Failed', `SendResult`="._LRAFO($_J0COJ);
      mysql_query($_QLfol, $_QLttI);

      unset($_j10IJ);

      return false;
    }

    $_QLfol = "INSERT INTO `$_ICIJo` SET `autoresponders_id`=$_JjLLC[Autoresponders_id], `MailSubject`="._LRAFO(ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false)).", `SendDateTime`=NOW(), `recipients_id`=0, `EMail`="._LRAFO($_j11Io["u_EMail"]).", `Send`='Sent', `SendResult`='OK'";
    mysql_query($_QLfol, $_QLttI);

    $_j10IJ->_LF0QR($_I8jLt, 0, ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false));

    unset($_j10IJ);
    return true;
  }

?>
