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

  include_once("config.inc.php");
  include_once("replacements.inc.php");
  include_once("mail.php");
  if(defined("SWM")){
  include_once("tracking_inst.inc.php");
  include_once("googleanalytics.inc.php");
  }
  include_once("targetgroups.inc.php");

  // $_Jf0Ii = Params to create email (from, subject, text, attachments...), mail send mta settings
  // isset($_Jf0Ii["AltBrowserLink"]) alt browserlink we will not send mails
  function _OED01(&$_IiJit, &$_Ii6QI, &$_Ii6lO, $_IitJj, $_Jf0Ii, $_jIiQ8, $MailingListId, $_jQLfQ, $_JiC8l, &$errors, &$_Ql1O8, $AdditionalHeaders=array(), $ResponderId=0, $ResponderType="") {
    global $UserId, $OwnerUserId, $_QOCJo, $_QCo6j, $_jji0i, $_jji0C, $_jJtJt;
    global $_jJJjO, $_Q61I1, $_jJ88O, $_Q6JJJ;
    global $_III0L, $_jJ1Li, $_jJQ66, $_Ij18l, $_III86, $_IjQQ8, $_jQt18, $_j601Q, $_j610t;
    global $_jjLO0, $_jJ088, $_jJ1Il;
    global $_jjiCt, $_jjlQ0, $_jjlC6;
    global $_QOifL, $_j616i;

    $_JiiQJ = array(); // => key is here with [], all others without

    $_jIiQ8["PersonalizeEMails"] = 1;
    if(isset($_Jf0Ii["PersonalizeEMails"]))
      $_jIiQ8["PersonalizeEMails"] = $_Jf0Ii["PersonalizeEMails"];

    // distriblists
    reset($_j616i);
    foreach($_j616i as $key => $_Q6ClO){
      if(isset($_Jf0Ii[$key]))
        $_JiiQJ[$_Q6ClO] = $_Jf0Ii[$key];
    }
    //

    $_Ii6QI = "";
    $_Ii6lO = "";
    if(isset($_Jf0Ii["OverrideSubUnsubURL"])) # autoresponder can't have it
      $_Iijft = $_Jf0Ii["OverrideSubUnsubURL"];
      else
      $_Iijft = "";

    if(!isset($_Jf0Ii["MailFormat"]))
      $_Jf0Ii["MailFormat"] = "Multipart";

    if(!isset($_jIiQ8["u_EMailFormat"]))
      $_jIiQ8["u_EMailFormat"] = "HTML";

    for($_Q6llo=count($errors) - 1; $_Q6llo>=0; $_Q6llo--)
       unset($errors[$_Q6llo]);

    for($_Q6llo=count($_Ql1O8) - 1; $_Q6llo>=0; $_Q6llo--)
       unset($_Ql1O8[$_Q6llo]);

    // default
    $_JiiL6 = true;
    $_JiL8L = false;
    $_Jil8I = false;

    if($_Jf0Ii["MailFormat"] == "PlainText") {
       $_Jil8I = true;
       $_JiiL6 = false;
    }
    else
     if($_Jf0Ii["MailFormat"] == "HTML") {
        $_JiL8L = true;
        $_JiiL6 = false;
     }

    // recipient has selected plain text and it is a multi part email
    if($_jIiQ8["u_EMailFormat"] == "PlainText" && $_JiiL6) {
      // mail_mime handles this automatically correct
      $_JiiL6 = false;
      $_JiL8L = false;
      $_Jil8I = true;
    }

    // Unique String
    $_Jill8 = "";
    if( $ResponderId != 0 && $ResponderType != "" ) {
       $_If010 = 0;
       if(isset($_Jf0Ii["CurrentSendId"]))
         $_If010 = $_Jf0Ii["CurrentSendId"];

       if($OwnerUserId == 0)
         $_Ii016 = $UserId;
         else
         $_Ii016 = $OwnerUserId;

       $_JL0f0 = _OAP0L($ResponderType);

       $_Jill8 = sprintf("%02X", $_If010)."_".sprintf("%02X", $_Ii016)."_".sprintf("%02X", $_JL0f0)."_".sprintf("%02X", $ResponderId);
       if($_JiC8l != $_jQLfQ)
         $_Jill8 .= "_"."x".sprintf("%02X", $_JiC8l);
    }

    // target groups support
    $_JL1IJ = false;
    $_JL1Oi = false;
    if ($_JiiL6 || $_JiL8L){
      if(!isset($_Jf0Ii["TargetGroupsWithEmbeddedFilesIncluded"]))
        $_JL1IJ = _LJOAD($_Jf0Ii["MailHTMLText"]);
        else
        $_JL1IJ = (bool)$_Jf0Ii["TargetGroupsWithEmbeddedFilesIncluded"];

      $_j1L1C = array();
      if(!isset($_Jf0Ii["TargetGroupsInHTMLPartIncluded"])){
        if(!$_JL1IJ)
           _LJO8O($_Jf0Ii["MailHTMLText"], $_j1L1C, 1);
      } else{
        if(!$_JL1IJ && $_Jf0Ii["TargetGroupsInHTMLPartIncluded"])
          $_j1L1C[] = "dummy";
      }

      $_JL1Oi = $_JL1IJ || count($_j1L1C) > 0;
    }
    // target groups support /

    if( $_IitJj || $_JL1IJ || !empty($_Jf0Ii["PersAttachments"]) ) {
      $_IiJit->_OEADF();
    }

    // set mail object params
    $_IiJit->_OE868();
    $_IQf88 = $_Jf0Ii["SenderFromAddress"];

    // MUST overwrite it?
    if(!empty($_Jf0Ii["MTASenderEMailAddress"]))
      $_IQf88 = $_Jf0Ii["MTASenderEMailAddress"];
    $_IiJit->From[] = array("address" => _L1ERL($_jIiQ8, $MailingListId, $_IQf88, $_Jf0Ii["MailEncoding"], false, array()), "name" => _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["SenderFromName"], $_Jf0Ii["MailEncoding"], false, array()) );
    $_IiJit->To[] = array("address" => $_jIiQ8["u_EMail"], "name" => _L1ERL($_jIiQ8, $MailingListId, $_jIiQ8["u_FirstName"]." ".$_jIiQ8["u_LastName"], $_Jf0Ii["MailEncoding"], false, array()) );
    if(!empty($_Jf0Ii["ReplyToEMailAddress"]))
       $_IiJit->ReplyTo[] = array("address" => _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["ReplyToEMailAddress"], $_Jf0Ii["MailEncoding"], false, array()), "name" => "");
    if(!empty($_Jf0Ii["ReturnPathEMailAddress"]))
      $_IiJit->ReturnPath[] = array("address" => _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["ReturnPathEMailAddress"], $_Jf0Ii["MailEncoding"], false, array()), "name" => "");

    if(!empty($_Jf0Ii["CcEMailAddresses"])) {
       $_Qot0C = explode(",", $_Jf0Ii["CcEMailAddresses"]);
       for($_Q6llo=0; $_Q6llo<count($_Qot0C); $_Q6llo++)
         if(trim($_Qot0C[$_Q6llo]) != "")
           $_IiJit->Cc[] = array("address" => _L1ERL($_jIiQ8, $MailingListId, trim($_Qot0C[$_Q6llo]), $_Jf0Ii["MailEncoding"], false, array()), "name" => "");
    }

    if(!empty($_Jf0Ii["BCcEMailAddresses"])) {
       $_Qot0C = explode(",", $_Jf0Ii["BCcEMailAddresses"]);
       for($_Q6llo=0; $_Q6llo<count($_Qot0C); $_Q6llo++)
         if(trim($_Qot0C[$_Q6llo]) != "")
           $_IiJit->BCc[] = array("address" => _L1ERL($_jIiQ8, $MailingListId, trim($_Qot0C[$_Q6llo]), $_Jf0Ii["MailEncoding"], false, array()), "name" => "");
    }

    if(isset($_Jf0Ii["ReturnReceipt"]) && $_Jf0Ii["ReturnReceipt"] != 0) {
      $_IiJit->ReturnReceiptTo[] = array("address" => _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["SenderFromAddress"], $_Jf0Ii["MailEncoding"], false, array()), "name" => "");
    }

    # UserMailHeaderFields, per campaign, not used
    if(isset($_Jf0Ii["UserMailHeaderFields"]) && $_Jf0Ii["UserMailHeaderFields"] != "") {
      if(!is_array($_Jf0Ii["UserMailHeaderFields"])) {
        $_Jf0Ii["UserMailHeaderFields"] = @unserialize($_Jf0Ii["UserMailHeaderFields"]);
        if($_Jf0Ii["UserMailHeaderFields"] === false)
           $_Jf0Ii["UserMailHeaderFields"] = array();
      }
      $_IiJit->UserHeaders = $_Jf0Ii["UserMailHeaderFields"];
      if(is_array($_IiJit->UserHeaders)) {
        foreach($_IiJit->UserHeaders as $key => $_Q6ClO) {
          $_Q6ClO = _L1ERL($_jIiQ8, $MailingListId, $_Q6ClO, $_Jf0Ii["MailEncoding"], false, $_JiiQJ);
          $_IiJit->UserHeaders[$key] = $_Q6ClO;
        }
      }
    }

    // Autoresponder
    if(isset($_Jf0Ii["OrgMailSubject"]) && !isset($_Jf0Ii["DistributionListEntryId"])) { // DistributionListEntryId => is distribution list no autoresponder, we must not do this loop here
      reset($_III86);
      foreach ($_III86 as $key => $_Q6ClO) {
         $_JiiQJ[$_Q6ClO] = $_Jf0Ii[$key];
      }
    }

    // Birthday and campaign responder
    if(isset($_jIiQ8["MembersAge"])) {
      reset($_IjQQ8);
      foreach ($_IjQQ8 as $key => $_Q6ClO) {
         if(isset($_jIiQ8[$key]))
           $_JiiQJ[$_Q6ClO] = $_jIiQ8[$key];
      }
    }

    if($MailingListId != 0 && $_jQLfQ != 0) {
      $_Ij0oj = array();
      $_Ij0oj = array_merge($_Ij0oj, $_III0L, $_QOifL);
      if($_Jill8 != "")
        $_Ij0oj = array_merge($_Ij0oj, $_jQt18, $_j601Q, $_Ij18l, $_j610t);
      reset($_Ij0oj);
      foreach ($_Ij0oj as $key => $_Q6ClO) {
         $_I1L81 = "";
         if ($_Q6ClO == '[UnsubscribeLink]') {
            $_QlQC8 = "";
            if(isset($_Jf0Ii["MaillistTableName"]))
              $_QlQC8 = $_Jf0Ii["MaillistTableName"];
            $_jIiQ8["IdentString"] = _OA81R($_jIiQ8["IdentString"], $_jIiQ8["id"], $MailingListId, $_jQLfQ, $_QlQC8);
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?key=".$_jIiQ8["IdentString"];
            if($_Jill8 != "")
              $_I1L81 .= "&rid=".$_Jill8;
            if(isset($_Jf0Ii["GroupIds"]))
              $_I1L81 .= "&RG=".$_Jf0Ii["GroupIds"];

         }
         if ($_Q6ClO == '[EditLink]') {
            $_QlQC8 = "";
            if(isset($_Jf0Ii["MaillistTableName"]))
              $_QlQC8 = $_Jf0Ii["MaillistTableName"];
            $_jIiQ8["IdentString"] = _OA81R($_jIiQ8["IdentString"], $_jIiQ8["id"], $MailingListId, $_jQLfQ, $_QlQC8);
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?key=".$_jIiQ8["IdentString"]."&ML=$MailingListId&F=$_jQLfQ&HTMLForm=editform";
            if($_Jill8 != "")
              $_I1L81 .= "&rid=".$_Jill8;
         }
         if (defined("SWM") && $_Q6ClO == '[AltBrowserLink]') {
            $_QlQC8 = "";
            if(isset($_Jf0Ii["MaillistTableName"]))
              $_QlQC8 = $_Jf0Ii["MaillistTableName"];
            $_jIiQ8["IdentString"] = _OA81R($_jIiQ8["IdentString"], $_jIiQ8["id"], $MailingListId, $_jQLfQ, $_QlQC8);
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jJ1Li : $_jJQ66)."?key=".$_jIiQ8["IdentString"];
            $_I1L81 .= "&rid=".$_Jill8;
            if(isset($_Jf0Ii["GroupIds"]))
              $_I1L81 .= "&RG=".$_Jf0Ii["GroupIds"];
         }
         // Social media links, AltBrowserLink_SME only
         if (defined("SWM") && $_Q6ClO == '[AltBrowserLink_SME]') {
            $_QlQC8 = "";
            if(isset($_Jf0Ii["MaillistTableName"]))
              $_QlQC8 = $_Jf0Ii["MaillistTableName"];
            $_jIiQ8["IdentString"] = _OA81R($_jIiQ8["IdentString"], $_jIiQ8["id"], $MailingListId, $_jQLfQ, $_QlQC8);
            $_JLQI1 = $_jIiQ8["IdentString"];
            $_JLQI1 = explode("-", $_JLQI1);
            $_JLQI1[0] = "sme";
            $_JLQI1 = join("-", $_JLQI1);
            $_I1L81 = (!empty($_Iijft) ? $_Iijft.$_jJ1Li : $_jJQ66)."?key=".$_JLQI1;
            $_I1L81 .= "&rid=".$_Jill8;
            if(isset($_Jf0Ii["GroupIds"]))
              $_I1L81 .= "&RG=".$_Jf0Ii["GroupIds"];
         }
         // Social media links /
         if(empty($_jIiQ8["u_EMail"]) && $_Q6ClO != '[AltBrowserLink_SME]') // social media links we need the links
           $_JiiQJ[$_Q6ClO] = ""; // recipient doesn't exists e.g. browserlink, newsletter archive
           else
           $_JiiQJ[$_Q6ClO] = $_I1L81;
      }
    }

    # MailHeaderFields for mta
    if(isset($_Jf0Ii["MailHeaderFields"]) && $_Jf0Ii["MailHeaderFields"] != "") {
      if(!is_array($_Jf0Ii["MailHeaderFields"])) {
        $_Jf0Ii["MailHeaderFields"] = @unserialize($_Jf0Ii["MailHeaderFields"]);
        if($_Jf0Ii["MailHeaderFields"] === false)
           $_Jf0Ii["MailHeaderFields"] = array();
      }
      // AdditionalHeaders overrides MailHeaderFields
      $AdditionalHeaders = array_merge($_Jf0Ii["MailHeaderFields"], $AdditionalHeaders);
    }

    # AdditionalHeaders
    reset($AdditionalHeaders);
    foreach($AdditionalHeaders as $key => $_Q6ClO){
       $_Q6ClO = _L1ERL($_jIiQ8, $MailingListId, $_Q6ClO, $_Jf0Ii["MailEncoding"], false, array());
       if($key == "X-Loop")
          $_Q6ClO = str_ireplace("%XLOOP-SENDERADDRESS%", _L1ERL($_jIiQ8, $MailingListId, $_IQf88, $_Jf0Ii["MailEncoding"], false, array()), $_Q6ClO);
       if($key == "List-Unsubscribe") {
         if(isset($_JiiQJ["[UnsubscribeLink]"])) {
            if(isset($_Jf0Ii["SubscriptionUnsubscription"]) && ($_Jf0Ii["SubscriptionUnsubscription"] == 'Denied' || $_Jf0Ii["SubscriptionUnsubscription"] == 'SubscribeOnly')){
               unset($AdditionalHeaders[$key]);
               continue;
            }
            $_Q6ClO = str_replace("[UnsubscribeLink]", $_JiiQJ["[UnsubscribeLink]"], $_Q6ClO);
            $AdditionalHeaders[$key] = $_Q6ClO;
         } else{
           unset($AdditionalHeaders[$key]);
         }
         continue;
       }
       $AdditionalHeaders[$key] = $_Q6ClO;
    }

    if(empty($_Jf0Ii["ModifiedEMailSubject"])) // // no distriblist
      $_IiJit->Subject = _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["MailSubject"], $_Jf0Ii["MailEncoding"], false, $_JiiQJ);
      else {
        if(!$_Jf0Ii["DontModifyEMailSubjectOnReFw"]){
          $_IiJit->Subject = _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["ModifiedEMailSubject"], $_Jf0Ii["MailEncoding"], false, $_JiiQJ);
        } else{

          if( strpos($_Jf0Ii["MailSubject"], "Re: ") === 0 || strpos($_Jf0Ii["MailSubject"], "Aw: ") === 0 || strpos($_Jf0Ii["MailSubject"], "Fw: ") === 0 || strpos($_Jf0Ii["MailSubject"], "Wg: ") === 0 ) {
               // do nothing
               $_IiJit->Subject = _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["MailSubject"], $_Jf0Ii["MailEncoding"], false, $_JiiQJ);
            }
            else
             $_IiJit->Subject = _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["ModifiedEMailSubject"], $_Jf0Ii["MailEncoding"], false, $_JiiQJ);
        }
      }

    // Social media links, Mailsubject
    if(defined("SWM") && !empty($_JiiQJ[$_QOifL["AltBrowserLink_SME"]])){
       $_JiiQJ['[AltBrowserLink_SME_URLEncoded]'] = urlencode($_JiiQJ["[AltBrowserLink_SME]"]);
       $_JiiQJ['[Mail_Subject_ISO88591]'] = $_IiJit->Subject;
       $_JIO8t = ConvertString($_Jf0Ii["MailEncoding"], "ISO-8859-1", $_IiJit->Subject, false);
       if($_JIO8t != "")
          $_JiiQJ['[Mail_Subject_ISO88591]'] = $_JIO8t;
       $_JiiQJ['[Mail_Subject_ISO88591_URLEncoded]'] = urlencode($_JiiQJ['[Mail_Subject_ISO88591]']);

       $_JiiQJ['[Mail_Subject_UTF8]'] = $_IiJit->Subject;
       $_JIO8t = ConvertString($_Jf0Ii["MailEncoding"], "UTF-8", $_IiJit->Subject, false);
       if($_JIO8t != "")
          $_JiiQJ['[Mail_Subject_UTF8]'] = $_JIO8t;
       $_JiiQJ['[Mail_Subject_UTF8_URLEncoded]'] = urlencode($_JiiQJ['[Mail_Subject_UTF8]']);
    }
    // Social media links

    if($_JiiL6 || $_Jil8I){
        // distriblists
        if(isset($_Jf0Ii["AddSignature"]) && $_Jf0Ii["AddSignature"])
           $_Jf0Ii["MailPlainText"] .= $_Q6JJJ . $_Jf0Ii["SignaturePlainText"];

        $_IiJit->TextPart = _L1ERL($_jIiQ8, $MailingListId, $_Jf0Ii["MailPlainText"], $_Jf0Ii["MailEncoding"], false, $_JiiQJ);
      }
      else
      $_IiJit->TextPart = "";

    if( ($_Jf0Ii["MailFormat"] != "PlainText") && ($_JiiL6 || $_JiL8L) ) {

       // distriblists
       if(isset($_Jf0Ii["AddSignature"]) && $_Jf0Ii["AddSignature"]){
          $_JLQ60 = stripos($_Jf0Ii["MailHTMLText"], '</body');
          if($_JLQ60 === false)
             $_JLQ60 = stripos($_Jf0Ii["MailHTMLText"], '</html');
          if($_JLQ60 === false){
            $_Jf0Ii["MailHTMLText"] .= $_Jf0Ii["SignatureHTMLText"];
          } else {
            $_Jf0Ii["MailHTMLText"] = substr_replace($_Jf0Ii["MailHTMLText"], $_Jf0Ii["SignatureHTMLText"], $_JLQ60, 0);
          }
       }

       $_JLIjo = "";
       if(isset($_Jf0Ii["MailPreHeaderText"]))
         $_JLIjo = $_Jf0Ii["MailPreHeaderText"];

       $_JjflQ = $_Jf0Ii["MailHTMLText"];

       // install tracking if field TrackLinks exists
       if(isset($_Jf0Ii["TrackLinks"]) && defined("SWM")) {
          $_JjflQ = _LJ8PF($_JjflQ, $_Jf0Ii, $_jIiQ8, $MailingListId, $_jQLfQ, $ResponderId, $ResponderType, $_JiC8l);
       }
       //

       // install Google Analytics if field GoogleAnalyticsActive exists
       if(isset($_Jf0Ii["GoogleAnalyticsActive"]) && $_Jf0Ii["GoogleAnalyticsActive"] && defined("SWM")) {
         $_JjflQ = _OCFOP($_JjflQ, $_Jf0Ii);
         if(isset($_JiiQJ["[AltBrowserLink]"])) {
           $_JiiQJ["[AltBrowserLink]"] .= "&".join("&", _OCFFQ($_Jf0Ii));
         }
       }
       //

       $_JjflQ = _OB8O0("<title>", "</title>", $_JjflQ, htmlspecialchars($_Jf0Ii["MailSubject"], ENT_COMPAT, $_Jf0Ii["MailEncoding"]));
       $_JjflQ = _L1ERL($_jIiQ8, $MailingListId, $_JjflQ, $_Jf0Ii["MailEncoding"], true, $_JiiQJ);
       $_JjflQ = SetHTMLCharSet($_JjflQ, $_Jf0Ii["MailEncoding"], true);

       if(!empty($_JLIjo))
         $_JLIjo = _L1ERL($_jIiQ8, $MailingListId, htmlspecialchars($_JLIjo, ENT_COMPAT, $_Jf0Ii["MailEncoding"]), $_Jf0Ii["MailEncoding"], true, $_JiiQJ);

       // inline images
       $_IiJit->_OEPOO();
       $_jitLI = array();
       GetInlineFiles($_JjflQ, $_jitLI, true);
       $_jt8IL = InstallPath;
       if(isset($_Jf0Ii["AltBrowserLink"])){
         $_jt8IL = ScriptBaseURL;
       }
       for($_Q6llo=0; $_Q6llo< count($_jitLI); $_Q6llo++) {
         if(!@file_exists($_jitLI[$_Q6llo])) {
           $_QJCJi = _OBEDB($_jitLI[$_Q6llo]);
           $_JjflQ = str_replace($_jitLI[$_Q6llo], $_QJCJi, $_JjflQ);
           $_jitLI[$_Q6llo] = $_QJCJi;
         }
         $_IiJit->InlineImages[] = array ("file" => $_jitLI[$_Q6llo], "c_type" => _OBCE8($_jitLI[$_Q6llo]), "name" => "", "isfile" => true );
       }

       // target groups support
       if($_JL1Oi){
         if($_JiiL6 || $_Jil8I){

           $_j1CJ0 = true;
           if(isset($_Jf0Ii["AutoCreateTextPart"])) // campaign can deactivate it
             $_j1CJ0 = $_Jf0Ii["AutoCreateTextPart"];

           if($_j1CJ0)
              $_IiJit->TextPart = _ODQAB($_JjflQ, $_Jf0Ii["MailEncoding"]);
         }
       }

       if(!empty($_JLIjo)){
        //$_JjflQ = explode('<body', $_JjflQ, 2);
        $_JjflQ = preg_split("/\<body/i", $_JjflQ, 2);
        if(count($_JjflQ) > 1 && strpos($_JjflQ[1], '>') !== false){
          $_I1t0l = strpos($_JjflQ[1], '>');
          $_JjflQ[1] = substr_replace($_JjflQ[1], sprintf($_jJ88O, $_JLIjo), $_I1t0l + 1, 0);
        }
        $_JjflQ = join('<body', $_JjflQ);
       }
       $_IiJit->HTMLPart = $_JjflQ;

    } else {
      $_IiJit->_OEPOO();
      $_IiJit->HTMLPart = "";
    }

    // attachments
    $_IiJit->_OEPFA();
    if(!is_array($_Jf0Ii["Attachments"])) {
      if($_Jf0Ii["Attachments"] != "") {
        $_Jf0Ii["Attachments"] = @unserialize($_Jf0Ii["Attachments"]);
        if( $_Jf0Ii["Attachments"] === false)
          $_Jf0Ii["Attachments"] = array();
      } else
        $_Jf0Ii["Attachments"] = array();
    }

    $_jt8IL = $_QOCJo;
    if(isset($_Jf0Ii["AltBrowserLink"])){
      $_jt8IL = ScriptBaseURL;
    }
    for($_Q6llo=0; $_Q6llo<count($_Jf0Ii["Attachments"]); $_Q6llo++) {
      $_IiJit->Attachments[] = array ("file" => $_jt8IL.CheckFileNameForUTF8($_Jf0Ii["Attachments"][$_Q6llo]), "c_type" => "application/octet-stream", "name" => "", "isfile" => true );
    }

    // pers attachments
    if(!isset($_Jf0Ii["PersAttachments"]) || !defined("SWM"))
      $_Jf0Ii["PersAttachments"] = array();

    if(!is_array($_Jf0Ii["PersAttachments"])) {
      if($_Jf0Ii["PersAttachments"] != "") {
        $_Jf0Ii["PersAttachments"] = @unserialize($_Jf0Ii["PersAttachments"]);
        if( $_Jf0Ii["PersAttachments"] === false)
          $_Jf0Ii["PersAttachments"] = array();
      } else
        $_Jf0Ii["PersAttachments"] = array();
    }

    $_jt8IL = $_QOCJo;
    if(isset($_Jf0Ii["AltBrowserLink"])){
      $_jt8IL = ScriptBaseURL;
    }

    // wildcard *
    $_JLI6j = count($_Jf0Ii["PersAttachments"]);
    for($_Q6llo=0; $_Q6llo<$_JLI6j; $_Q6llo++) {

      if(strpos($_Jf0Ii["PersAttachments"][$_Q6llo], '*') === false) continue;

      $_jt8LJ = $_Jf0Ii["PersAttachments"][$_Q6llo];
      // there should be no visiblefilename
      $_JLIOO = "";
      $_Q6i6i = strpos($_jt8LJ, ";");
      if($_Q6i6i !== false){
        $_JLIOO = trim(substr($_jt8LJ, $_Q6i6i + 1));
        $_jt8LJ = substr($_jt8LJ, 0, $_Q6i6i);
      }

      $_jt8LJ = trim(_L1ERL($_jIiQ8, $MailingListId, $_jt8LJ, $_Jf0Ii["MailEncoding"], false, $_JiiQJ));

      $_JLILQ = dirname($_QOCJo.$_jt8LJ);
      $_QllO8 = dirname($_jt8LJ);
      if($_QllO8 == ".")
        $_QllO8 = "";
      if($_QllO8 !== "")
        $_QllO8 = $_QllO8."/";
      $_JLIlt = opendir($_JLILQ);
      $_Q6LIL = array();
      while ($_JLIlt && $_Q6lfJ = readdir($_JLIlt)) {
        if(!is_dir($_JLILQ.'/'.$_Q6lfJ) && is_readable($_JLILQ.'/'.$_Q6lfJ)){
          $_Q6LIL[] = $_QllO8.$_Q6lfJ;
        }
      }
      if($_JLIlt)
        closedir($_JLIlt);
        else{
           if(isset($_Jf0Ii["SendEMailWithoutPersAttachment"]) && !$_Jf0Ii["SendEMailWithoutPersAttachment"]){
             $errors[] = 9999;
             $_Ql1O8[] = "Path '$_JLILQ' not found.";
             if(!isset($_Jf0Ii["AltBrowserLink"])) { // # we want only AltBrowserLink text not sending email, attachments
               return false;
             }
           }
        }

      if(count($_Q6LIL)){
        $_Jf0Ii["PersAttachments"][$_Q6llo] = $_Q6LIL[0];
        for($_Q8otJ = 1; $_Q8otJ < count($_Q6LIL); $_Q8otJ++)
          $_Jf0Ii["PersAttachments"][] = $_Q6LIL[$_Q8otJ];
      }
    }

    for($_Q6llo=0; $_Q6llo<count($_Jf0Ii["PersAttachments"]); $_Q6llo++) {
      $_jt8LJ = $_Jf0Ii["PersAttachments"][$_Q6llo];
      $_JLIOO = "";
      $_Q6i6i = strpos($_jt8LJ, ";");
      if($_Q6i6i !== false){
        $_JLIOO = trim(substr($_jt8LJ, $_Q6i6i + 1));
        $_jt8LJ = substr($_jt8LJ, 0, $_Q6i6i);
      }
      $_jt8LJ = trim(_L1ERL($_jIiQ8, $MailingListId, $_jt8LJ, $_Jf0Ii["MailEncoding"], false, $_JiiQJ));
      $_jt8LJ = CheckFileNameForUTF8($_jt8LJ);
      if($_JLIOO){
        $_JLIOO = trim(_L1ERL($_jIiQ8, $MailingListId, $_JLIOO, $_Jf0Ii["MailEncoding"], false, $_JiiQJ));
        $_JLIOO = CheckFileNameForUTF8($_JLIOO);
      }
      if(!empty($_jt8LJ) && !is_dir($_QOCJo.$_jt8LJ) && is_readable($_QOCJo.$_jt8LJ)) # local filename check
         $_IiJit->Attachments[] = array ("file" => $_jt8IL.$_jt8LJ, "c_type" => "application/octet-stream", "name" => $_JLIOO, "isfile" => true );
         else{
           if(isset($_Jf0Ii["SendEMailWithoutPersAttachment"]) && !$_Jf0Ii["SendEMailWithoutPersAttachment"]){
             $errors[] = 9999;
             $_Ql1O8[] = "Attachment '$_jt8IL".$_jt8LJ."' not found.";
             if(!isset($_Jf0Ii["AltBrowserLink"])) { // # we want only AltBrowserLink text not sending email, attachments
               return false;
             }
           }
         }
    }

    # we want only AltBrowserLink text not sending email
    if(isset($_Jf0Ii["AltBrowserLink"])) {
      $_Ii6lO = $_IiJit->HTMLPart;
      if($_Ii6lO == "")
        $_Ii6lO = $_IiJit->TextPart;
      return true;
    }

    switch ($_Jf0Ii["MailPriority"]) {
      case 'Low' :
         $_IiJit->Priority = mpLow;
         break;
      case 'Normal':
         $_IiJit->Priority = mpNormal;
         break;
      case 'High'  :
         $_IiJit->Priority = mpHighest;
    }

    // email options
    if($_IiJit->EMailOptionsTag != "1") {
      $_QJlJ0 = "SELECT * FROM `$_jJJjO`";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_error($_Q61I1) != ""){
        $errors[] = mysql_errno($_Q61I1);
        $_Ql1O8[] = mysql_error($_Q61I1);
        return false;
      }
      $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_IiJit->crlf = $_Q8OiJ["CRLF"];
      $_IiJit->head_encoding = $_Q8OiJ["Head_Encoding"];
      $_IiJit->text_encoding = $_Q8OiJ["Text_Encoding"];
      $_IiJit->html_encoding = $_Q8OiJ["HTML_Encoding"];
      $_IiJit->attachment_encoding = $_Q8OiJ["Attachment_Encoding"];
      $_IiJit->XMailer = $_Q8OiJ["XMailer"];
      if(isset($_Q8OiJ["AddUniqueIdHeaderField"]))
         $_IiJit->Tag = $_Q8OiJ["AddUniqueIdHeaderField"];
      $_IiJit->EMailOptionsTag = "1"; // we have load it
    }

    // AddUniqueIdHeaderField
    if( $_IiJit->EMailOptionsTag && $_Jill8 != "" )
       $AdditionalHeaders[$_jJtJt] = $_Jill8;

    // AdditionalHeaders
    $_IiJit->AdditionalHeaders = $AdditionalHeaders;

    $_IiJit->charset = $_Jf0Ii["MailEncoding"];

  // mail send settings
    $_IiJit->Sendvariant = $_Jf0Ii["Type"]; // mail, sendmail, smtp, smtpmx, text

    $_IiJit->PHPMailParams = $_Jf0Ii["PHPMailParams"];
    $_IiJit->HELOName = $_Jf0Ii["HELOName"];

    $_IiJit->SMTPpersist = (bool)$_Jf0Ii["SMTPPersist"];
    $_IiJit->SMTPpipelining = (bool)$_Jf0Ii["SMTPPipelining"];
    $_IiJit->SMTPTimeout = $_Jf0Ii["SMTPTimeout"];
    $_IiJit->SMTPServer = $_Jf0Ii["SMTPServer"];
    $_IiJit->SMTPPort = $_Jf0Ii["SMTPPort"];
    $_IiJit->SMTPAuth = (bool)$_Jf0Ii["SMTPAuth"];
    $_IiJit->SMTPUsername = $_Jf0Ii["SMTPUsername"];
    $_IiJit->SMTPPassword = $_Jf0Ii["SMTPPassword"];
    if(isset($_Jf0Ii["SMTPSSL"]))
      $_IiJit->SSLConnection = (bool)$_Jf0Ii["SMTPSSL"];

    $_IiJit->sendmail_path = $_Jf0Ii["sendmail_path"];
    $_IiJit->sendmail_args = $_Jf0Ii["sendmail_args"];

    $_IiJit->SignMail = (bool)$_Jf0Ii["SMIMESignMail"];
    $_IiJit->SMIMEMessageAsPlainText = (bool)$_Jf0Ii["SMIMEMessageAsPlainText"];

    $_IiJit->SignCert = $_Jf0Ii["SMIMESignCert"];
    $_IiJit->SignPrivKey = $_Jf0Ii["SMIMESignPrivKey"];
    $_IiJit->SignPrivKeyPassword = $_Jf0Ii["SMIMESignPrivKeyPassword"];
    $_IiJit->SignTempFolder = $_jji0C;

    $_IiJit->SMIMEIgnoreSignErrors = (bool)$_Jf0Ii["SMIMEIgnoreSignErrors"];

    $_IiJit->DKIM = (bool)$_Jf0Ii["DKIM"];
    $_IiJit->DomainKey = (bool)$_Jf0Ii["DomainKey"];
    $_IiJit->DKIMSelector = $_Jf0Ii["DKIMSelector"];
    $_IiJit->DKIMPrivKey = $_Jf0Ii["DKIMPrivKey"];
    $_IiJit->DKIMPrivKeyPassword = $_Jf0Ii["DKIMPrivKeyPassword"];
    $_IiJit->DKIMIgnoreSignErrors = (bool)$_Jf0Ii["DKIMIgnoreSignErrors"];

    $_IiJit->ListId = $MailingListId."-";
    if(isset($_jIiQ8["id"]))
      $_IiJit->ListId .= $_jIiQ8["id"];
      else
      $_IiJit->ListId .= "0";
    $_IiJit->ListId .= ".localhost";

    if($_Jf0Ii["Type"] == "smtp" && stripos($_IiJit->SMTPServer, ".is-fun.net") !== false)
      $_IiJit->XCSAComplaints = true;

    _OPQ6J();
    if(!$_IiJit->_OED01($_Ii6QI, $_Ii6lO)) {
       $errors[] = $_IiJit->errors["errorcode"];
       $_Ql1O8[] = $_IiJit->errors["errortext"];
       return false;
    }

    return true;
  }
?>
