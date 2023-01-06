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

  include_once("config.inc.php");
  include_once("replacements.inc.php");
  include_once("mail.php");
  if(defined("SWM")){
  include_once("tracking_inst.inc.php");
  include_once("googleanalytics.inc.php");
  }
  include_once("targetgroups.inc.php");

  // $_6j88I = Params to create email (from, subject, text, attachments...), mail send mta settings
  // isset($_6j88I["AltBrowserLink"]) alt browserlink we will not send mails
  function _LEJE8(&$_j10IJ, &$_j108i, &$_j10O1, $_jfo8L, $_6j88I, $_j11Io, $MailingListId, $_jlt8j, $_fIiiL, &$errors, &$_I816i, $AdditionalHeaders=array(), $ResponderId=0, $ResponderType="") {
    global $UserId, $OwnerUserId, $_IIlfi, $_IJi8f, $_J1t6J, $_Il06C;
    global $_JQ1I6, $_QLttI, $_JQj6J, $_QLl1Q, $_QLo06;
    global $_IolCJ, $_J1i1C, $_jfilQ, $_ICiQ1, $_IC0fL, $_ICitL, $_jlJ1o, $_JQoLt, $_JQol8;
    global $_J1OIO, $_J1Cf8, $_J1Clo;
    global $_J1tCf, $_J1OLl, $_J1oCI;
    global $_Ij08l, $_JQC0J;

    $_fILtI = array(); // => key is here with [], all others without

    $_j11Io["PersonalizeEMails"] = 1;
    if(isset($_6j88I["PersonalizeEMails"]))
      $_j11Io["PersonalizeEMails"] = $_6j88I["PersonalizeEMails"];

    if(isset($_6j88I["PrivacyPolicyURL"]))
      $_fILtI["[PrivacyPolicyURL]"] = $_6j88I["PrivacyPolicyURL"];

    // distriblists
    reset($_JQC0J);
    foreach($_JQC0J as $key => $_QltJO){
      if(isset($_6j88I[$key]))
        $_fILtI[$_QltJO] = $_6j88I[$key];
    }
    if(isset($_6j88I["DistribSenderFromToCC"]) && !is_array($_6j88I["DistribSenderFromToCC"]))
      unset($_6j88I["DistribSenderFromToCC"]);
    //

    $_j108i = "";
    $_j10O1 = "";
    if(isset($_6j88I["OverrideSubUnsubURL"])) # autoresponder can't have it
      $_j1IIf = $_6j88I["OverrideSubUnsubURL"];
      else
      $_j1IIf = "";

    if(!isset($_6j88I["MailFormat"]))
      $_6j88I["MailFormat"] = "Multipart";

    if(!isset($_j11Io["u_EMailFormat"]))
      $_j11Io["u_EMailFormat"] = "HTML";

    $errors = array();  
    $_I816i = array();  

    if(isset($_6j88I["GroupIds"]))
       $_6j88I["GroupIds"] = urlencode($_6j88I["GroupIds"]);

    // default
    $_fIlQC = true;
    $_fIl8t = false;
    $_fj0QO = false;

    if($_6j88I["MailFormat"] == "PlainText") {
       $_fj0QO = true;
       $_fIlQC = false;
    }
    else
     if($_6j88I["MailFormat"] == "HTML") {
        $_fIl8t = true;
        $_fIlQC = false;
     }

    // recipient has selected plain text and it is a multi part email
    if($_j11Io["u_EMailFormat"] == "PlainText" && $_fIlQC) {
      // mail_mime handles this automatically correct
      $_fIlQC = false;
      $_fIl8t = false;
      $_fj0QO = true;
    }

    // Unique String
    $_fj0ol = "";
    if( $ResponderId != 0 && $ResponderType != "" ) {
       $_j01OI = 0;
       if(isset($_6j88I["CurrentSendId"]))
         $_j01OI = $_6j88I["CurrentSendId"];

       if($OwnerUserId == 0)
         $_jfIoi = $UserId;
         else
         $_jfIoi = $OwnerUserId;

       $_fj1fI = _LPO6C($ResponderType);

       $_fj0ol = sprintf("%02X", $_j01OI)."_".sprintf("%02X", $_jfIoi)."_".sprintf("%02X", $_fj1fI)."_".sprintf("%02X", $ResponderId);
       if($_fIiiL != $_jlt8j)
         $_fj0ol .= "_"."x".sprintf("%02X", $_fIiiL);
    }

    // target groups support
    $_fjQ6o = false;
    $_fjI1J = false;
    if ($_fIlQC || $_fIl8t){
      if(!isset($_6j88I["TargetGroupsWithEmbeddedFilesIncluded"]))
        $_fjQ6o = _JJ8DO($_6j88I["MailHTMLText"]);
        else
        $_fjQ6o = (bool)$_6j88I["TargetGroupsWithEmbeddedFilesIncluded"];

      $_jLtli = array();
      if(!isset($_6j88I["TargetGroupsInHTMLPartIncluded"])){
        if(!$_fjQ6o)
           _JJREP($_6j88I["MailHTMLText"], $_jLtli, 1);
      } else{
        if(!$_fjQ6o && $_6j88I["TargetGroupsInHTMLPartIncluded"])
          $_jLtli[] = "dummy";
      }

      $_fjI1J = $_fjQ6o || count($_jLtli) > 0;
    }
    // target groups support /

    if( $_jfo8L || $_fjQ6o || !empty($_6j88I["PersAttachments"]) ) {
      $_j10IJ->_LEOPF();
    }

    // set mail object params
    $_j10IJ->_LEQ1C();
    $_Io6Lf = $_6j88I["SenderFromAddress"];

    // MUST overwrite it?
    if(!empty($_6j88I["MTASenderEMailAddress"]))
      $_Io6Lf = $_6j88I["MTASenderEMailAddress"];
      
    if(!isset($_6j88I["DistribSenderFromToCC"]) || !count($_6j88I["DistribSenderFromToCC"])){
    
      $_j10IJ->From[] = array("address" => _J1EBE($_j11Io, $MailingListId, $_Io6Lf, $_6j88I["MailEncoding"], false, array()), "name" => _J1EBE($_j11Io, $MailingListId, $_6j88I["SenderFromName"], $_6j88I["MailEncoding"], false, array()) );
      $_j10IJ->To[] = array("address" => $_j11Io["u_EMail"], "name" => _J1EBE($_j11Io, $MailingListId, $_j11Io["u_FirstName"]." ".$_j11Io["u_LastName"], $_6j88I["MailEncoding"], false, array()) );
      if(!empty($_6j88I["ReplyToEMailAddress"])){
         $_IjO6t = explode(",", $_6j88I["ReplyToEMailAddress"]);
         for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
           if(trim($_IjO6t[$_Qli6J]) != ""){
             $_IjO6t[$_Qli6J] = trim( _J1EBE($_j11Io, $MailingListId, trim($_IjO6t[$_Qli6J]), $_6j88I["MailEncoding"], false, array()) ); 
             if($_IjO6t[$_Qli6J] != "")
                $_j10IJ->ReplyTo[] = array("address" => $_IjO6t[$_Qli6J], "name" => "");
           }
      }else{
        if( isset($_6j88I["INBOXEMailAddress"]) && isset($_6j88I["NoPOSTToDistributionList"]) && !$_6j88I["NoPOSTToDistributionList"] )
          $_j10IJ->ReplyTo[] = array("address" => $_6j88I["INBOXEMailAddress"], "name" => "");
      }

      // https://www.ietf.org/rfc/rfc2369.txt
      if(isset($_6j88I["INBOXEMailAddress"])){
        if( isset($_6j88I["NoPOSTToDistributionList"]) && !$_6j88I["NoPOSTToDistributionList"] )
           $AdditionalHeaders["List-Post"] = "<mailto:" . $_6j88I["INBOXEMailAddress"] . ">";
           else
           if( isset($_6j88I["NoPOSTToDistributionList"]) && $_6j88I["NoPOSTToDistributionList"] )
              $AdditionalHeaders["List-Post"] = "NO";
      }  
      
      if(isset($_6j88I["ReturnReceipt"]) && $_6j88I["ReturnReceipt"] != 0) {
        $_j10IJ->ReturnReceiptTo[] = array("address" => _J1EBE($_j11Io, $MailingListId, $_6j88I["SenderFromAddress"], $_6j88I["MailEncoding"], false, array()), "name" => "");
      }
      
      if(!empty($_6j88I["ReturnPathEMailAddress"]))
        $_j10IJ->ReturnPath[] = array("address" => _J1EBE($_j11Io, $MailingListId, $_6j88I["ReturnPathEMailAddress"], $_6j88I["MailEncoding"], false, array()), "name" => "");
        else{
         if(isset($_6j88I["INBOXEMailAddress"])){
            $_j10IJ->ReturnPath[] = array("address" => $_6j88I["INBOXEMailAddress"], "name" => "");
         } 
        }

      if(!empty($_6j88I["CcEMailAddresses"])) {
         $_IjO6t = explode(",", $_6j88I["CcEMailAddresses"]);
         for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
           if(trim($_IjO6t[$_Qli6J]) != ""){
             $_IjO6t[$_Qli6J] = trim(_J1EBE($_j11Io, $MailingListId, trim($_IjO6t[$_Qli6J]), $_6j88I["MailEncoding"], false, array()));
             if($_IjO6t[$_Qli6J] != "")
               $_j10IJ->Cc[] = array("address" => $_IjO6t[$_Qli6J], "name" => "");
           }  
      }

      if(!empty($_6j88I["BCcEMailAddresses"])) {
         $_IjO6t = explode(",", $_6j88I["BCcEMailAddresses"]);
         for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
           if(trim($_IjO6t[$_Qli6J]) != ""){
             $_IjO6t[$_Qli6J] = trim(_J1EBE($_j11Io, $MailingListId, trim($_IjO6t[$_Qli6J]), $_6j88I["MailEncoding"], false, array()));
             if($_IjO6t[$_Qli6J] != "")
               $_j10IJ->BCc[] = array("address" => $_IjO6t[$_Qli6J], "name" => "");
           }  
      }
      
    }
   

    if(isset($_6j88I["DistribSenderFromToCC"]) && count($_6j88I["DistribSenderFromToCC"])){

      if(!empty($_6j88I["ReturnPathEMailAddress"])){
        $AdditionalHeaders["X-EnvelopeSender"] = _J1EBE($_j11Io, $MailingListId, $_6j88I["ReturnPathEMailAddress"], $_6j88I["MailEncoding"], false, array());
      }
      
      // https://www.ietf.org/rfc/rfc2369.txt
      if(isset($_6j88I["INBOXEMailAddress"])){
        if( isset($_6j88I["NoPOSTToDistributionList"]) && !$_6j88I["NoPOSTToDistributionList"] ){
           $AdditionalHeaders["List-Post"] = "<mailto:" . $_6j88I["INBOXEMailAddress"] . ">";
           $_j10IJ->ReplyTo[] = array("address" => $_6j88I["INBOXEMailAddress"], "name" => "");
        }
         else
          if( isset($_6j88I["NoPOSTToDistributionList"]) && $_6j88I["NoPOSTToDistributionList"] ){
             $AdditionalHeaders["List-Post"] = "NO";
          } 
      }
      
      $AdditionalHeaders["X-EnvelopeRecipients"] = array();
      $AdditionalHeaders["X-EnvelopeRecipients"][] = array("address" => $_j11Io["u_EMail"], "name" => "");
       
      if(!empty($_6j88I["CcEMailAddresses"])) {
         $_IjO6t = explode(",", $_6j88I["CcEMailAddresses"]);
         for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
           if(trim($_IjO6t[$_Qli6J]) != ""){
             $_IjO6t[$_Qli6J] = trim(_J1EBE($_j11Io, $MailingListId, trim($_IjO6t[$_Qli6J]), $_6j88I["MailEncoding"], false, array()));
             if($_IjO6t[$_Qli6J] != "")
               $AdditionalHeaders["X-EnvelopeRecipients"][] = array("address" => $_IjO6t[$_Qli6J], "name" => "");
           }  
      }

      if(!empty($_6j88I["BCcEMailAddresses"])) {
         $_IjO6t = explode(",", $_6j88I["BCcEMailAddresses"]);
         for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++)
           if(trim($_IjO6t[$_Qli6J]) != ""){
             $_IjO6t[$_Qli6J] = trim(_J1EBE($_j11Io, $MailingListId, trim($_IjO6t[$_Qli6J]), $_6j88I["MailEncoding"], false, array()));
             if($_IjO6t[$_Qli6J] != "")
               $AdditionalHeaders["X-EnvelopeRecipients"][] = array("address" => $_IjO6t[$_Qli6J], "name" => "");
           }  
      }

      $_j10IJ->From[] = $_6j88I["DistribSenderFromToCC"]["From"];
      for($_Qli6J=0; $_Qli6J<count($_j10IJ->From); $_Qli6J++)
        $_j10IJ->From[$_Qli6J]["name"] = ConvertString($_QLo06, $_6j88I["MailEncoding"], $_j10IJ->From[$_Qli6J]["name"], false);
      
      for($_Qli6J=0; $_Qli6J<count($_6j88I["DistribSenderFromToCC"]["To"]); $_Qli6J++){
         $_j10IJ->To[] = $_6j88I["DistribSenderFromToCC"]["To"][$_Qli6J];
         $_j10IJ->To[count($_j10IJ->To) - 1]["name"] = ConvertString($_QLo06, $_6j88I["MailEncoding"], $_j10IJ->To[count($_j10IJ->To) - 1]["name"], false);
      }   

      for($_Qli6J=0; $_Qli6J<count($_6j88I["DistribSenderFromToCC"]["Cc"]); $_Qli6J++){
         $_j10IJ->Cc[] = $_6j88I["DistribSenderFromToCC"]["Cc"][$_Qli6J];
         $_j10IJ->Cc[count($_j10IJ->Cc) - 1]["name"] = ConvertString($_QLo06, $_6j88I["MailEncoding"], $_j10IJ->Cc[count($_j10IJ->Cc) - 1]["name"], false);
      }   

    }  

    # UserMailHeaderFields, per campaign, not used
    if(isset($_6j88I["UserMailHeaderFields"]) && $_6j88I["UserMailHeaderFields"] != "") {
      if(!is_array($_6j88I["UserMailHeaderFields"])) {
        $_6j88I["UserMailHeaderFields"] = @unserialize($_6j88I["UserMailHeaderFields"]);
        if($_6j88I["UserMailHeaderFields"] === false)
           $_6j88I["UserMailHeaderFields"] = array();
      }
      $_j10IJ->UserHeaders = $_6j88I["UserMailHeaderFields"];
      if(is_array($_j10IJ->UserHeaders)) {
        foreach($_j10IJ->UserHeaders as $key => $_QltJO) {
          $_QltJO = _J1EBE($_j11Io, $MailingListId, $_QltJO, $_6j88I["MailEncoding"], false, $_fILtI);
          $_j10IJ->UserHeaders[$key] = $_QltJO;
        }
      }
    }

    // Autoresponder
    if(isset($_6j88I["OrgMailSubject"]) && !isset($_6j88I["DistributionListEntryId"])) { // DistributionListEntryId => is distribution list no autoresponder, we must not do this loop here
      reset($_IC0fL);
      foreach ($_IC0fL as $key => $_QltJO) {
         $_fILtI[$_QltJO] = $_6j88I[$key];
      }
    }

    // Birthday and campaign responder
    if(isset($_j11Io["MembersAge"])) {
      reset($_ICitL);
      foreach ($_ICitL as $key => $_QltJO) {
         if(isset($_j11Io[$key]))
           $_fILtI[$_QltJO] = $_j11Io[$key];
      }
    }

    if($MailingListId != 0 && $_jlt8j != 0 && isset($_j11Io["id"])) {
      $_ICCIo = array();
      $_ICCIo = array_merge($_ICCIo, $_IolCJ, $_Ij08l);
      if($_fj0ol != "")
        $_ICCIo = array_merge($_ICCIo, $_jlJ1o, $_JQoLt, $_ICiQ1, $_JQol8);
      reset($_ICCIo);
      foreach ($_ICCIo as $key => $_QltJO) {
         $_IOCjL = "";
         if ($_QltJO == '[UnsubscribeLink]') {
            $_I8I6o = "";
            if(isset($_6j88I["MaillistTableName"]))
              $_I8I6o = $_6j88I["MaillistTableName"];
            $_j11Io["IdentString"] = _LPQ8Q($_j11Io["IdentString"], $_j11Io["id"], $MailingListId, $_jlt8j, $_I8I6o);
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?key=".$_j11Io["IdentString"];
            if($_fj0ol != "")
              $_IOCjL .= "&rid=".$_fj0ol;
            if(isset($_6j88I["GroupIds"]))
              $_IOCjL .= "&RG=" . $_6j88I["GroupIds"];

         }
         if ($_QltJO == '[EditLink]') {
            $_I8I6o = "";
            if(isset($_6j88I["MaillistTableName"]))
              $_I8I6o = $_6j88I["MaillistTableName"];
            $_j11Io["IdentString"] = _LPQ8Q($_j11Io["IdentString"], $_j11Io["id"], $MailingListId, $_jlt8j, $_I8I6o);
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?key=".$_j11Io["IdentString"]."&ML=$MailingListId&F=$_jlt8j&HTMLForm=editform";
            if($_fj0ol != "")
              $_IOCjL .= "&rid=".$_fj0ol;
         }
         if (defined("SWM") && $_QltJO == '[AltBrowserLink]') {
            $_I8I6o = "";
            if(isset($_6j88I["MaillistTableName"]))
              $_I8I6o = $_6j88I["MaillistTableName"];
            $_j11Io["IdentString"] = _LPQ8Q($_j11Io["IdentString"], $_j11Io["id"], $MailingListId, $_jlt8j, $_I8I6o);
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1i1C : $_jfilQ)."?key=".$_j11Io["IdentString"];
            $_IOCjL .= "&rid=".$_fj0ol;
            if(isset($_6j88I["GroupIds"]))
              $_IOCjL .= "&RG=" . $_6j88I["GroupIds"];
         }
         // Social media links, AltBrowserLink_SME only
         if (defined("SWM") && $_QltJO == '[AltBrowserLink_SME]') {
            $_I8I6o = "";
            if(isset($_6j88I["MaillistTableName"]))
              $_I8I6o = $_6j88I["MaillistTableName"];
            $_j11Io["IdentString"] = _LPQ8Q($_j11Io["IdentString"], $_j11Io["id"], $MailingListId, $_jlt8j, $_I8I6o);
            $_fjIIi = $_j11Io["IdentString"];
            $_fjIIi = explode("-", $_fjIIi);
            $_fjIIi[0] = "sme";
            $_fjIIi = join("-", $_fjIIi);
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1i1C : $_jfilQ)."?key=".$_fjIIi;
            $_IOCjL .= "&rid=".$_fj0ol;
            if(isset($_6j88I["GroupIds"]))
              $_IOCjL .= "&RG=" . $_6j88I["GroupIds"];
         }
         // Social media links /
         if(empty($_j11Io["u_EMail"]) && $_QltJO != '[AltBrowserLink_SME]') // social media links we need the links
           $_fILtI[$_QltJO] = ""; // recipient doesn't exists e.g. browserlink, newsletter archive
           else
           $_fILtI[$_QltJO] = $_IOCjL;
      }
    }

    # MailHeaderFields for mta
    if(isset($_6j88I["MailHeaderFields"]) && $_6j88I["MailHeaderFields"] != "") {
      if(!is_array($_6j88I["MailHeaderFields"])) {
        $_6j88I["MailHeaderFields"] = @unserialize($_6j88I["MailHeaderFields"]);
        if($_6j88I["MailHeaderFields"] === false)
           $_6j88I["MailHeaderFields"] = array();
      }
      // AdditionalHeaders overrides MailHeaderFields
      $AdditionalHeaders = array_merge($_6j88I["MailHeaderFields"], $AdditionalHeaders);
    }

    # AdditionalHeaders
    reset($AdditionalHeaders);
    foreach($AdditionalHeaders as $key => $_QltJO){
      if(is_array($_QltJO)) continue; 
      $_QltJO = _J1EBE($_j11Io, $MailingListId, $_QltJO, $_6j88I["MailEncoding"], false, array());
       if($key == "X-Loop")
          $_QltJO = str_ireplace("%XLOOP-SENDERADDRESS%", _J1EBE($_j11Io, $MailingListId, $_Io6Lf, $_6j88I["MailEncoding"], false, array()), $_QltJO);
       if($key == "List-Unsubscribe") {
         if(isset($_fILtI["[UnsubscribeLink]"])) {
            if(isset($_6j88I["SubscriptionUnsubscription"]) && ($_6j88I["SubscriptionUnsubscription"] == 'Denied' || $_6j88I["SubscriptionUnsubscription"] == 'SubscribeOnly')){
               unset($AdditionalHeaders[$key]);
               continue;
            }
            $_fjIOL = (bool)$_6j88I["DKIM"] || defined("ListUnsubscribePostOnNoDKIMSignature") || ($_6j88I["Type"] == "smtp" && stripos($_6j88I["SMTPServer"], ".is-fun.net") !== false);
            $_QltJO = str_replace("[UnsubscribeLink]", $_fILtI["[UnsubscribeLink]"] . ($_fjIOL  ? '&lup=lup' : '' ), $_QltJO);
            $AdditionalHeaders[$key] = $_QltJO;
            if( $_fjIOL && !isset($AdditionalHeaders["List-Unsubscribe-Post"]) ){
               $AdditionalHeaders["List-Unsubscribe-Post"] = "List-Unsubscribe=One-Click";
            }
         } else{
           unset($AdditionalHeaders[$key]);
         }
         continue;
       }
       $AdditionalHeaders[$key] = $_QltJO;
    }

    if(empty($_6j88I["ModifiedEMailSubject"])) // // no distriblist
      $_j10IJ->Subject = _J1EBE($_j11Io, $MailingListId, $_6j88I["MailSubject"], $_6j88I["MailEncoding"], false, $_fILtI);
      else {
        if(!$_6j88I["DontModifyEMailSubjectOnReFw"]){
          $_j10IJ->Subject = _J1EBE($_j11Io, $MailingListId, $_6j88I["ModifiedEMailSubject"], $_6j88I["MailEncoding"], false, $_fILtI);
        } else{

          if( stripos($_6j88I["MailSubject"], "Re: ") === false && stripos($_6j88I["MailSubject"], "Aw: ") === false && stripos($_6j88I["MailSubject"], "Fw: ") === false && stripos($_6j88I["MailSubject"], "Wg: ") === false ) {
             $_j10IJ->Subject = _J1EBE($_j11Io, $MailingListId, $_6j88I["ModifiedEMailSubject"], $_6j88I["MailEncoding"], false, $_fILtI);
            }
            else
             // do nothing
             $_j10IJ->Subject = _J1EBE($_j11Io, $MailingListId, $_6j88I["MailSubject"], $_6j88I["MailEncoding"], false, $_fILtI);
        }
      }

    // Social media links, Mailsubject
    if(defined("SWM") && !empty($_fILtI[$_Ij08l["AltBrowserLink_SME"]])){
       $_fILtI['[AltBrowserLink_SME_URLEncoded]'] = urlencode($_fILtI["[AltBrowserLink_SME]"]);
       $_fILtI['[Mail_Subject_ISO88591]'] = $_j10IJ->Subject;
       $_6JiJ6 = ConvertString($_6j88I["MailEncoding"], "ISO-8859-1", $_j10IJ->Subject, false);
       if($_6JiJ6 != "")
          $_fILtI['[Mail_Subject_ISO88591]'] = $_6JiJ6;
       $_fILtI['[Mail_Subject_ISO88591_URLEncoded]'] = urlencode($_fILtI['[Mail_Subject_ISO88591]']);

       $_fILtI['[Mail_Subject_UTF8]'] = $_j10IJ->Subject;
       $_6JiJ6 = ConvertString($_6j88I["MailEncoding"], "UTF-8", $_j10IJ->Subject, false);
       if($_6JiJ6 != "")
          $_fILtI['[Mail_Subject_UTF8]'] = $_6JiJ6;
       $_fILtI['[Mail_Subject_UTF8_URLEncoded]'] = urlencode($_fILtI['[Mail_Subject_UTF8]']);
    }
    // Social media links

    if($_fIlQC || $_fj0QO){
        // distriblists
        if(isset($_6j88I["AddSignature"]) && $_6j88I["AddSignature"])
           $_6j88I["MailPlainText"] .= $_QLl1Q . $_6j88I["SignaturePlainText"];

        $_j10IJ->TextPart = _J1EBE($_j11Io, $MailingListId, $_6j88I["MailPlainText"], $_6j88I["MailEncoding"], false, $_fILtI);
      }
      else
      $_j10IJ->TextPart = "";

    if( ($_6j88I["MailFormat"] != "PlainText") && ($_fIlQC || $_fIl8t) ) {

       // distriblists
       if(isset($_6j88I["AddSignature"]) && $_6j88I["AddSignature"]){
          $_fjIlo = stripos($_6j88I["MailHTMLText"], '</body');
          if($_fjIlo === false)
             $_fjIlo = stripos($_6j88I["MailHTMLText"], '</html');
          if($_fjIlo === false){
            $_6j88I["MailHTMLText"] .= $_6j88I["SignatureHTMLText"];
          } else {
            $_6j88I["MailHTMLText"] = substr_replace($_6j88I["MailHTMLText"], $_6j88I["SignatureHTMLText"], $_fjIlo, 0);
          }
       }

       $_fjjjl = "";
       if(isset($_6j88I["MailPreHeaderText"]))
         $_fjjjl = $_6j88I["MailPreHeaderText"];

       $_66flC = $_6j88I["MailHTMLText"];

       // install tracking if field TrackLinks exists
       if(isset($_6j88I["TrackLinks"]) && defined("SWM")) {
          $_66flC = _JJDRF($_66flC, $_6j88I, $_j11Io, $MailingListId, $_jlt8j, $ResponderId, $ResponderType, $_fIiiL);
       }
       //

       // install Google Analytics if field GoogleAnalyticsActive exists
       if(isset($_6j88I["GoogleAnalyticsActive"]) && $_6j88I["GoogleAnalyticsActive"] && defined("SWM")) {
         $_66flC = _LBCQ6($_66flC, $_6j88I);
         if(isset($_fILtI["[AltBrowserLink]"])) {
           $_fILtI["[AltBrowserLink]"] .= "&".join("&", _LBCJL($_6j88I));
         }
       }
       //

       $_66flC = _LPFQD("<title>", "</title>", $_66flC, htmlspecialchars($_6j88I["MailSubject"], ENT_COMPAT, $_6j88I["MailEncoding"], false));
       $_66flC = _J1EBE($_j11Io, $MailingListId, $_66flC, $_6j88I["MailEncoding"], true, $_fILtI);
       $_66flC = SetHTMLCharSet($_66flC, $_6j88I["MailEncoding"], true);

       if(!empty($_fjjjl))
         $_fjjjl = _J1EBE($_j11Io, $MailingListId, htmlspecialchars($_fjjjl, ENT_COMPAT, $_6j88I["MailEncoding"], false), $_6j88I["MailEncoding"], true, $_fILtI);

       // inline images
       $_j10IJ->_LEQ1D();
       $_JiI11 = array();
       GetInlineFiles($_66flC, $_JiI11, true);
       $_Jf1C8 = InstallPath;
       if(isset($_6j88I["AltBrowserLink"])){
         $_Jf1C8 = ScriptBaseURL;
       }
       for($_Qli6J=0; $_Qli6J< count($_JiI11); $_Qli6J++) {
         if(!@file_exists($_JiI11[$_Qli6J])) {
           $_QLJfI = _LA6ED($_JiI11[$_Qli6J]);
           $_66flC = str_replace($_JiI11[$_Qli6J], $_QLJfI, $_66flC);
           $_JiI11[$_Qli6J] = $_QLJfI;
           if(!@file_exists($_JiI11[$_Qli6J]) && isset($_6j88I["DistributionListEntryId"])) {
             continue; // for distribution lists we ignore missing inline files
           }
         }
         $_j10IJ->InlineImages[] = array ("file" => $_JiI11[$_Qli6J], "c_type" => _LALJ6($_JiI11[$_Qli6J]), "name" => "", "isfile" => true );
       }

       // target groups support
       if($_fjI1J){
         if($_fIlQC || $_fj0QO){

           $_jLt1Q = true;
           if(isset($_6j88I["AutoCreateTextPart"])) // campaign can deactivate it
             $_jLt1Q = $_6j88I["AutoCreateTextPart"];

           if($_jLt1Q)
              $_j10IJ->TextPart = _LBDA8($_66flC, $_6j88I["MailEncoding"]);
         }
       }

       if(!empty($_fjjjl)){
        //$_66flC = explode('<body', $_66flC, 2);
        $_66flC = preg_split("/\<body/i", $_66flC);
        for($_Qli6J=1; $_Qli6J<count($_66flC); $_Qli6J++){
          if(strpos($_66flC[$_Qli6J], '>') !== false){
            $_IOO6C = strpos($_66flC[$_Qli6J], '>');
            $_66flC[$_Qli6J] = substr_replace($_66flC[$_Qli6J], sprintf($_JQj6J, $_fjjjl), $_IOO6C + 1, 0);
          }
        }
        $_66flC = join('<body', $_66flC);
       }
       $_j10IJ->HTMLPart = $_66flC;

    } else {
      $_j10IJ->_LEQ1D();
      $_j10IJ->HTMLPart = "";
    }

    // attachments
    $_j10IJ->_LEQFP();
    if(!is_array($_6j88I["Attachments"])) {
      if($_6j88I["Attachments"] != "") {
        $_6j88I["Attachments"] = @unserialize($_6j88I["Attachments"]);
        if( $_6j88I["Attachments"] === false)
          $_6j88I["Attachments"] = array();
      } else
        $_6j88I["Attachments"] = array();
    }

    $_Jf1C8 = $_IIlfi;
    if(isset($_6j88I["AltBrowserLink"])){
      $_Jf1C8 = ScriptBaseURL;
    }
    for($_Qli6J=0; $_Qli6J<count($_6j88I["Attachments"]); $_Qli6J++) {
      $_j10IJ->Attachments[] = array ("file" => $_Jf1C8.CheckFileNameForUTF8($_6j88I["Attachments"][$_Qli6J]), "c_type" => "application/octet-stream", "name" => "", "isfile" => true );
    }

    // pers attachments
    if(!isset($_6j88I["PersAttachments"]) || !defined("SWM"))
      $_6j88I["PersAttachments"] = array();

    if(!is_array($_6j88I["PersAttachments"])) {
      if($_6j88I["PersAttachments"] != "") {
        $_6j88I["PersAttachments"] = @unserialize($_6j88I["PersAttachments"]);
        if( $_6j88I["PersAttachments"] === false)
          $_6j88I["PersAttachments"] = array();
      } else
        $_6j88I["PersAttachments"] = array();
    }

    $_Jf1C8 = $_IIlfi;
    if(isset($_6j88I["AltBrowserLink"])){
      $_Jf1C8 = ScriptBaseURL;
    }

    // wildcard *
    $_fjjt1 = count($_6j88I["PersAttachments"]);
    for($_Qli6J=0; $_Qli6J<$_fjjt1; $_Qli6J++) {

      if(strpos($_6j88I["PersAttachments"][$_Qli6J], '*') === false) continue;

      $_JfIIf = $_6j88I["PersAttachments"][$_Qli6J];
      // there should be no visiblefilename
      $_fjJjC = "";
      $_QlOjt = strpos($_JfIIf, ";");
      if($_QlOjt !== false){
        $_fjJjC = trim(substr($_JfIIf, $_QlOjt + 1));
        $_JfIIf = substr($_JfIIf, 0, $_QlOjt);
      }

      $_JfIIf = trim(_J1EBE($_j11Io, $MailingListId, $_JfIIf, $_6j88I["MailEncoding"], false, $_fILtI));

      $_fj618 = dirname($_IIlfi.$_JfIIf);
      $_I016j = dirname($_JfIIf);
      if($_I016j == ".")
        $_I016j = "";
      if($_I016j !== "")
        $_I016j = $_I016j."/";
      $_fj6CI = opendir($_fj618);
      $_QlooO = array();
      while ($_fj6CI && $_QlCtl = readdir($_fj6CI)) {
        if(!is_dir($_fj618.'/'.$_QlCtl) && is_readable($_fj618.'/'.$_QlCtl)){
          $_QlooO[] = $_I016j.$_QlCtl;
        }
      }
      if($_fj6CI)
        closedir($_fj6CI);
        else{
           if(isset($_6j88I["SendEMailWithoutPersAttachment"]) && !$_6j88I["SendEMailWithoutPersAttachment"]){
             $errors[] = 9999;
             $_I816i[] = "Path '$_fj618' not found.";
             if(!isset($_6j88I["AltBrowserLink"])) { // # we want only AltBrowserLink text not sending email, attachments
               return false;
             }
           }
        }

      if(count($_QlooO)){
        $_6j88I["PersAttachments"][$_Qli6J] = $_QlooO[0];
        for($_I1OoI = 1; $_I1OoI < count($_QlooO); $_I1OoI++)
          $_6j88I["PersAttachments"][] = $_QlooO[$_I1OoI];
      }
    }

    for($_Qli6J=0; $_Qli6J<count($_6j88I["PersAttachments"]); $_Qli6J++) {
      $_JfIIf = $_6j88I["PersAttachments"][$_Qli6J];
      $_fjJjC = "";
      $_QlOjt = strpos($_JfIIf, ";");
      if($_QlOjt !== false){
        $_fjJjC = trim(substr($_JfIIf, $_QlOjt + 1));
        $_JfIIf = substr($_JfIIf, 0, $_QlOjt);
      }
      $_JfIIf = trim(_J1EBE($_j11Io, $MailingListId, $_JfIIf, $_6j88I["MailEncoding"], false, $_fILtI));
      $_JfIIf = CheckFileNameForUTF8($_JfIIf);
      if($_fjJjC){
        $_fjJjC = trim(_J1EBE($_j11Io, $MailingListId, $_fjJjC, $_6j88I["MailEncoding"], false, $_fILtI));
        $_fjJjC = CheckFileNameForUTF8($_fjJjC);
      }
      if(!empty($_JfIIf) && !is_dir($_IIlfi.$_JfIIf) && is_readable($_IIlfi.$_JfIIf)) # local filename check
         $_j10IJ->Attachments[] = array ("file" => $_Jf1C8.$_JfIIf, "c_type" => "application/octet-stream", "name" => $_fjJjC, "isfile" => true );
         else{
           if(isset($_6j88I["SendEMailWithoutPersAttachment"]) && !$_6j88I["SendEMailWithoutPersAttachment"]){
             $errors[] = 9999;
             $_I816i[] = "Attachment '$_Jf1C8".$_JfIIf."' not found.";
             if(!isset($_6j88I["AltBrowserLink"])) { // # we want only AltBrowserLink text not sending email, attachments
               return false;
             }
           }
         }
    }

    # we want only AltBrowserLink text not sending email
    if(isset($_6j88I["AltBrowserLink"])) {
      $_j10O1 = $_j10IJ->HTMLPart;
      if($_j10O1 == "")
        $_j10O1 = $_j10IJ->TextPart;
      return true;
    }

    switch ($_6j88I["MailPriority"]) {
      case 'Low' :
         $_j10IJ->Priority = mpLow;
         break;
      case 'Normal':
         $_j10IJ->Priority = mpNormal;
         break;
      case 'High'  :
         $_j10IJ->Priority = mpHighest;
    }

    // email options
    if($_j10IJ->EMailOptionsTag != "1") {
      $_QLfol = "SELECT * FROM `$_JQ1I6`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != ""){
        $errors[] = mysql_errno($_QLttI);
        $_I816i[] = mysql_error($_QLttI);
        return false;
      }
      $_I1OfI = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_j10IJ->crlf = $_I1OfI["CRLF"];
      $_j10IJ->head_encoding = $_I1OfI["Head_Encoding"];
      $_j10IJ->text_encoding = $_I1OfI["Text_Encoding"];
      $_j10IJ->html_encoding = $_I1OfI["HTML_Encoding"];
      $_j10IJ->attachment_encoding = $_I1OfI["Attachment_Encoding"];
      $_j10IJ->XMailer = $_I1OfI["XMailer"];
      if(isset($_I1OfI["AddUniqueIdHeaderField"]))
         $_j10IJ->Tag = $_I1OfI["AddUniqueIdHeaderField"];
      $_j10IJ->EMailOptionsTag = "1"; // we have load it
    }

    if($_6j88I["Type"] == "text") // for text ever CRLF
      $_j10IJ->crlf = $_QLl1Q;
    
    // AddUniqueIdHeaderField
    if( $_j10IJ->EMailOptionsTag && $_fj0ol != "" )
       $AdditionalHeaders[$_Il06C] = $_fj0ol;

    // AdditionalHeaders
    $_j10IJ->AdditionalHeaders = $AdditionalHeaders;

    $_j10IJ->charset = $_6j88I["MailEncoding"];

  // mail send settings
    $_j10IJ->Sendvariant = $_6j88I["Type"]; // mail, sendmail, smtp, smtpmx, text, savetodir

    $_j10IJ->PHPMailParams = $_6j88I["PHPMailParams"];
    $_j10IJ->HELOName = $_6j88I["HELOName"];

    $_j10IJ->SMTPpersist = (bool)$_6j88I["SMTPPersist"];
    $_j10IJ->SMTPpipelining = (bool)$_6j88I["SMTPPipelining"];
    $_j10IJ->SMTPTimeout = $_6j88I["SMTPTimeout"];
    $_j10IJ->SMTPServer = $_6j88I["SMTPServer"];
    $_j10IJ->SMTPPort = $_6j88I["SMTPPort"];
    $_j10IJ->SMTPAuth = (bool)$_6j88I["SMTPAuth"];
    $_j10IJ->SMTPUsername = $_6j88I["SMTPUsername"];
    $_j10IJ->SMTPPassword = $_6j88I["SMTPPassword"];
    if(isset($_6j88I["SMTPSSL"]))
      $_j10IJ->SSLConnection = (bool)$_6j88I["SMTPSSL"];

    $_j10IJ->sendmail_path = $_6j88I["sendmail_path"];
    $_j10IJ->sendmail_args = $_6j88I["sendmail_args"];

    $_j10IJ->savetodir_filepathandname = "";

    if($_j10IJ->Sendvariant == "savetodir"){
      $_j10IJ->savetodir_filepathandname = _LBQFJ($_6j88I["savetodir_pathname"], $MailingListId, (isset($_j11Io["id"]) ? $_j11Io["id"] : 0), (isset($_6j88I["CurrentSendId"]) ? $_6j88I["CurrentSendId"] : 0), (isset($_6j88I["MTAsId"]) ? $_6j88I["MTAsId"] : 0), $ResponderType, $ResponderId, 0);
    }

    $_j10IJ->SignMail = (bool)$_6j88I["SMIMESignMail"];
    $_j10IJ->SMIMEMessageAsPlainText = (bool)$_6j88I["SMIMEMessageAsPlainText"];

    $_j10IJ->SignCert = $_6j88I["SMIMESignCert"];
    $_j10IJ->SignPrivKey = $_6j88I["SMIMESignPrivKey"];
    $_j10IJ->SignPrivKeyPassword = $_6j88I["SMIMESignPrivKeyPassword"];
    $_j10IJ->SignTempFolder = $_J1t6J;
    $_j10IJ->SignExtraCerts = $_6j88I["SMIMESignExtraCerts"];

    $_j10IJ->SMIMEIgnoreSignErrors = (bool)$_6j88I["SMIMEIgnoreSignErrors"];

    $_j10IJ->DKIM = (bool)$_6j88I["DKIM"];
    $_j10IJ->DomainKey = (bool)$_6j88I["DomainKey"];
    $_j10IJ->DKIMSelector = $_6j88I["DKIMSelector"];
    $_j10IJ->DKIMPrivKey = $_6j88I["DKIMPrivKey"];
    $_j10IJ->DKIMPrivKeyPassword = $_6j88I["DKIMPrivKeyPassword"];
    $_j10IJ->DKIMIgnoreSignErrors = (bool)$_6j88I["DKIMIgnoreSignErrors"];

    $_j10IJ->ListId = $MailingListId."-";
    if(isset($_j11Io["id"]))
      $_j10IJ->ListId .= $_j11Io["id"];
      else
      $_j10IJ->ListId .= "0";
    $_j10IJ->ListId .= ".localhost";

    if($_6j88I["Type"] == "smtp" && stripos($_j10IJ->SMTPServer, ".is-fun.net") !== false)
      $_j10IJ->XCSAComplaints = true;

    _LRCOC();
    if(!$_j10IJ->_LEJE8($_j108i, $_j10O1)) {
       $errors[] = $_j10IJ->errors["errorcode"];
       $_I816i[] = $_j10IJ->errors["errortext"];
       return false;
    }

    return true;
  }
  
  class SubjectGenerator{

    // @private
    var $_fj6l1;
    var $_fjfL1 = -1;
    var $_fj8Jj = array();
    var $_fj8fl;
    var $_fjtQ0;
    
    function __construct($_fjO1O) {
      $this->_LECC8($_fjO1O, false);
    }

    function SubjectGenerator() {
      self::__construct();
    }

    function __destruct() {
    }
    
    function _LECC8($_fjO1O, $_fjOto){
      if ($_fjOto)
        if ($this->_fj6l1 != '' && $this->_fj6l1 == $_fjO1O) 
          return;
      $this->_fjfL1 = -1;    
      
      $this->_fj6l1 = $_fjO1O;
      $this->_fj8Jj = explode(EMailSubjectVariantsSeparator, $_fjO1O);    
      
      $this->_fj8fl = true;
      $this->_fjtQ0 = 1;
      if(count($this->_fj8Jj)){
        if ('random' == strtolower($this->_fj8Jj[count($this->_fj8Jj) - 1]))
          array_pop($this->_fj8Jj);
          else
          if( stripos($this->_fj8Jj[count($this->_fj8Jj) - 1], "changesubjectafter:") !== false ){
            $this->_fjtQ0 = intval( substr($this->_fj8Jj[count($this->_fj8Jj) - 1], strpos($this->_fj8Jj[count($this->_fj8Jj) - 1], ':') + 1) );
            if($this->_fjtQ0 <= 0)
              $this->_fjtQ0 = 1;
            $this->_fj8fl = false;
            array_pop($this->_fj8Jj);  
          }
      }
    }
  
    function _LED8C(){
      return count($this->_fj8Jj);
    }
  
    function _LEE0J(){
      $_ILJjL = 0;
      for ($_Qli6J=0; $_Qli6J<count($this->_fj8Jj); $_Qli6J++)
         if (strlen( rtrim( $this->_fj8Jj[$_Qli6J] ) ) > $_ILJjL )
           $_ILJjL = strlen( rtrim( $this->_fj8Jj[$_Qli6J] ) );
      return $_ILJjL;     
    }
  
    function _LEEPA($_JfiIt = 0, $_fjOCJ = true){
     if(count($this->_fj8Jj) == 1)
       return rtrim($this->_fj8Jj[0]);
     if($_JfiIt < 0) // return ever first entry => newsletter archive 
       return rtrim($this->_fj8Jj[0]);

     if(!$_fjOCJ){
       if($this->_fjfL1 < 0)
         return rtrim($this->_fj8Jj[0]);
       else
         return rtrim($this->_fj8Jj[$this->_fjfL1]);
     }

     if(!$this->_fj8fl && $this->_fjfL1 == -1 && $_JfiIt + 1 > $this->_fjtQ0){
       for($_Qli6J=0; $_Qli6J < $_JfiIt + 1; $_Qli6J += $this->_fjtQ0){
           $this->_fjfL1++;
            if ($this->_fjfL1 > count($this->_fj8Jj) - 1)
               $this->_fjfL1 = 0;
       }  
       return rtrim($this->_fj8Jj[$this->_fjfL1]); 
     }
     
     if ($this->_fj8fl){
        $_fjo6J = rand(0, count($this->_fj8Jj) - 1);
        $this->_fjfL1 = $_fjo6J;
     }
      else{
         if ($_JfiIt % $this->_fjtQ0 == 0){
             $this->_fjfL1++;
             if ($this->_fjfL1 > count($this->_fj8Jj) - 1)
               $this->_fjfL1 = 0;
           }
         if($this->_fjfL1 < 0) 
            $this->_fjfL1 = 0;
      }
      return rtrim($this->_fj8Jj[$this->_fjfL1]);
    }
  
    
  }
  
?>
