<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

 function _LFDR8($_ftO66, $_IflO6, &$errors, &$_I816i){
   if(!isset($_SESSION))
     global $_SESSION;

    if( $_IflO6["UseCaptcha"]  ) {
      if ( (!isset($_ftO66['user_captcha_string'])) || ($_ftO66['user_captcha_string'] == '') || $_ftO66['user_captcha_string'] != $GLOBALS['crypt_class']->base64_decode_advanced( $_SESSION['captcha_string']) ) {
         $errors[] = "user_captcha_string";
         $_I816i[] = $_IflO6["CaptchaStringIncorrect"]."<br /><br />";
         @session_destroy();
      }
      # FF Bug we can't forget word $_SESSION['captcha_string']) = ''; # forget the captcha word!
    }

    if(count($errors) > 0) return false;

    if( $_IflO6["UseReCaptcha"] || $_IflO6["UseReCaptchav3"]  ) {
        // https://developers.google.com/recaptcha/docs/v3
        if(empty($_ftO66["g-recaptcha-response"])){
          $errors[] = "user_captcha_string";
          $_I816i[] = $_IflO6["CaptchaStringIncorrect"]."(1)<br /><br />";
        }
        if(!empty($_ftO66["g-recaptcha-response"])){
          $_6f6oC = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getOwnIP();
          $_I0QjQ = "secret=".$_IflO6["PrivateReCaptchaKey"]."&response=".$_ftO66["g-recaptcha-response"]."&remoteip=".$_6f6oC;
          $_JJl1I = "";
          $_J600J = "";

          if(function_exists("DoHTTPPOSTRequest"))
            $_ftoQt = DoHTTPPOSTRequest("www.google.com", "/recaptcha/api/siteverify", $_I0QjQ, 443);
          else
            $_ftoQt = DoHTTPRequest("www.google.com", "POST", "/recaptcha/api/siteverify", $_I0QjQ, 0, 443, false, "", "", $_JJl1I, $_J600J);

          #recaptchav1 recaptcha_check_answer ($_IflO6["PrivateReCaptchaKey"], isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getOwnIP(), $_ftO66["recaptcha_challenge_field"], $_ftO66["recaptcha_response_field"]);

          if (!$_ftoQt) {
            $errors[] = "user_captcha_string";
            $_I816i[] = $_IflO6["CaptchaStringIncorrect"]."(2)<br /><br />";
          }
          
          $_ftC0C = json_decode($_ftoQt,true);
          if( !isset($_ftC0C) ){
            $errors[] = "user_captcha_string";
            $_I816i[] = $_IflO6["CaptchaStringIncorrect"]."(2.1)<br /><br />";
          }
          
          if ( !isset($_ftC0C["success"]) || !$_ftC0C["success"] ) {
            $errors[] = "user_captcha_string";
            $_I816i[] = $_IflO6["CaptchaStringIncorrect"]."(3)<br /><br />";
          }
          if ( $_IflO6["UseReCaptchav3"] && (!isset($_ftC0C["action"]) || $_ftC0C["action"] != "newslettersubunsub") ) {
            $errors[] = "user_captcha_string";
            $_I816i[] = $_IflO6["CaptchaStringIncorrect"]."(4)<br /><br />";
          }

          $_ftCiL = 0.5;
          if ( $_IflO6["UseReCaptchav3"] && ( !isset($_ftC0C["score"]) || floatval(str_replace(",", ".", $_ftC0C["score"])) < $_ftCiL ) ) {
            $errors[] = "user_captcha_string";
            $_I816i[] = $_IflO6["CaptchaStringIncorrect"]."(5)<br /><br />";
          }
        }
    }

    if(count($errors) > 0) return false;
    
    return true;
 }

 function _LFD8Q(&$_ftO66, $_IOJoI, $_IflO6, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $_Jj6f0, &$errors, &$_I816i) {
    global $_I8tfQ, $_I8OoJ, $_QLttI;

    if(!_LFDR8($_ftO66, $_IflO6, $errors, $_I816i))
      return false;


    if($_IflO6["GroupsAsMandatoryField"] && $_IflO6["GroupsOption"] == 2) {
      if( !isset($_ftO66["RecipientGroups"]) || !is_array($_ftO66["RecipientGroups"]) || count($_ftO66["RecipientGroups"]) == 0 ){
         $errors[] = "RecipientGroups";
         $_I816i[] = $_IflO6["GroupsAsMandatoryFieldError"]."<br /><br />";
      }
    }

    if($_IflO6["PrivacyPolicyAcceptanceAsMandatoryField"]){
      if (!isset($_ftO66["PrivacyPolicyAccepted"]) || trim($_ftO66["PrivacyPolicyAccepted"]) != "1"){
         $errors[] = "PrivacyPolicyAccepted";
         $_I816i[] = $_IflO6["PrivacyPolicyAcceptanceError"]."<br /><br />";
      }
    }

    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      # first letter uppercase
      if($_IOLil == "u_FirstName" || $_IOLil == "u_LastName" || $_IOLil == "u_MiddleName")
        if(isset($_ftO66[$_IOLil]))
          $_ftO66[$_IOLil] = ucfirst($_ftO66[$_IOLil]);

       # when it's a checkbox, value can't be set
       # same checking on Subscribe and Edit
       # don't check for empty("0"), this gives true
      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");
      if ( ($_IOLil == "u_PersonalizedTracking" || strpos($_IOLil, "u_UserFieldBool") !== false) && ($_IOCjL == "visiblerequired" || $_IOCjL == "visible") && $_ftCL8  )
        $_ftO66[$_IOLil] = ($_IOCjL == "visiblerequired") ? "" : "0";

      // u_PersonalizedTracking must be ever 0 or 1
      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");
      if ( $_IOLil == "u_PersonalizedTracking" && ($_IOCjL == "visiblerequired" || $_IOCjL == "visible") && ( $_ftCL8 || !is_numeric($_ftO66[$_IOLil]) ) )
        $_ftO66[$_IOLil] = ($_IOCjL == "visiblerequired") ? "" : "0";

      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");
      if ( $_IOLil == "u_PersonalizedTracking" && !$_ftCL8 && (intval($_ftO66[$_IOLil]) > 1 || intval($_ftO66[$_IOLil]) < 0) )
        $_ftO66[$_IOLil] = ($_IOCjL == "visiblerequired") ? "" : "0";

      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");
      if ( $_IOCjL == "visiblerequired" && ( $_ftCL8 || ($_IOLil == "u_Salutation" && $_ftO66[$_IOLil] == "---" ) )  ) {
         $errors[] = $_IOLil;
         if(count($_I816i) == 0)
            $_I816i[] = $_IflO6["RequiredFieldsErrorText"]."<br /><br />";
         $_I816i[] = $_IflO6[$_IOLil]."<br />";
         continue;
      }

      // spezial email address
      if($_IOLil == "u_EMail") {
        if(!_L8JEL($_ftO66[$_IOLil])) {
           $errors[] = $_IOLil;
           $_I816i[] = $_IflO6["EMailFormatErrorText"]."<br />";
        } else{
          $_ftO66[$_IOLil] = _L86JE($_ftO66[$_IOLil]);
        }
      }

      // spezial birthday
      if($_IOLil == "u_Birthday" && !empty($_ftO66[$_IOLil]) && $_ftO66[$_IOLil] != "") {
        if(strlen($_ftO66[$_IOLil]) < 10) {
          $errors[] = $_IOLil;
          if(count($_I816i) == 0)
             $_I816i[] = $_IflO6["RequiredFieldsErrorText"]."<br /><br />";
          $_I816i[] = $_IflO6[$_IOLil]."<br />";
        } else {
          $_fti1L = ".";
          if( strpos($_ftO66[$_IOLil], ".") === false ) {
            if( strpos($_ftO66[$_IOLil], "-") === false ) {
              if( strpos($_ftO66[$_IOLil], "/") === false ) {
              } else $_fti1L = "/";
            }
            else $_fti1L = "-";
          }

          $_I1OoI = explode($_fti1L, $_ftO66[$_IOLil]);
          if ( count($_I1OoI) != 3 ) {
            $errors[] = $_IOLil;
            if(count($_I816i) == 0)
               $_I816i[] = $_IflO6["RequiredFieldsErrorText"]."<br /><br />";
            $_I816i[] = $_IflO6[$_IOLil]."<br />";
          }
        }

      } // spezial birthday END

    }

    // local blacklist check
    if(count($errors) == 0) {
      $_QLfol = "SELECT id FROM $_jjj8f WHERE u_EMail="._LRAFO($_ftO66["u_EMail"]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $errors[] = "u_EMail";
        $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
      }
      mysql_free_result($_QL8i1);
    }

    if(!empty($_ftO66["u_EMail"]))
      $_JjOjI = trim($_ftO66["u_EMail"]);
      else
      $_JjOjI = "";
    $_JjOjI = substr($_JjOjI, strpos($_JjOjI, '@') + 1);

    // local domain blacklist check
    if(count($errors) == 0) {
      $_QLfol = "SELECT id FROM $_Jj6f0 WHERE Domainname="._LRAFO($_JjOjI);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $errors[] = "u_EMail";
        $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
      }
      mysql_free_result($_QL8i1);
    }

    // global blacklist check
    if(count($errors) == 0) {
      $_QLfol = "SELECT id FROM $_I8tfQ WHERE u_EMail="._LRAFO($_ftO66["u_EMail"]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $errors[] = "u_EMail";
        $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
      }
      mysql_free_result($_QL8i1);
    }

    // global domain blacklist check
    if(count($errors) == 0) {
      $_QLfol = "SELECT id FROM $_I8OoJ WHERE Domainname="._LRAFO($_JjOjI);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $errors[] = "u_EMail";
        $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
      }
      mysql_free_result($_QL8i1);
    }

    // ECGlist check
    if(count($errors) == 0 && _JOLQE("ECGListCheck")) {
      $_I016j = _L6AJP( $_ftO66["u_EMail"] );
      if( (is_bool($_I016j) && $_I016j) || is_string($_I016j) ){
        $errors[] = "u_EMail";
        $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
      }
    }

    // all thinks OK ?
    if(count($errors) == 0) {

      // duplicate check
      $_QLfol = "SELECT `id`, `SubscriptionStatus`, `IsActive` FROM `$_I8I6o` WHERE `u_EMail`="._LRAFO($_ftO66["u_EMail"]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $_I1OfI = mysql_fetch_row($_QL8i1);
        $_IfLJj = $_I1OfI[0];
        $_Jj8iQ = $_I1OfI[1];
        $_IjIfQ = $_I1OfI[2];
        mysql_free_result($_QL8i1);

        if(!$_IjIfQ){
          $errors[] = "u_EMail";
          $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
          return false;
        }

        // send confirmation mail again, ignore groups
        if($_Jj8iQ == 'OptInConfirmationPending') {
          // tricky set SubscriptionStatus and send in main script opt-in mail again
          $_ftO66["SubscriptionStatus"] = 'OptInConfirmationPending';
          $_ftO66["RecipientId"] = $_IfLJj;
          return true;
        }

        // Group check
        if(isset($_ftO66["RecipientGroups"])) {
          $_QLfol = "SELECT $_QljJi.id FROM $_IfJ66 LEFT JOIN $_QljJi ON groups_id=id WHERE Member_id=".intval($_IfLJj);
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_jt0QC = array();
          while($_I1OfI = mysql_fetch_row($_QL8i1)) {
            $_jt0QC[] = $_I1OfI[0];
          }
          mysql_free_result($_QL8i1);
          $_ftiC1 = false;
          // remove duplicate groups
          for($_Qli6J=0; $_Qli6J<count($_jt0QC); $_Qli6J++) {
            if(array_key_exists($_jt0QC[$_Qli6J], $_ftO66["RecipientGroups"])) {
              $_ftiC1 = true;
              unset($_ftO66["RecipientGroups"][$_jt0QC[$_Qli6J]]);
            }
          }
          if($_ftiC1 && count($_ftO66["RecipientGroups"]) == 0 ) {
            $errors[] = "u_EMail";
            $_I816i[] = $_IflO6["EMailAddressAlwaysInList"];
          } else {
            // tricky set DuplicateEntry to change the groups
            $_ftO66["DuplicateEntry"] = $_IfLJj; // exists but group change
          }

        } else {
          $errors[] = "u_EMail";
          $_I816i[] = $_IflO6["EMailAddressAlwaysInList"];
        }

      }

    }

   return count($errors) == 0 ? true : false;
 }

 function _LFD8C(&$_ftO66, $_IOJoI, $_IflO6, $_I8I6o, &$errors, &$_I816i, $_ftL0O = false) {
    global $_QLttI;

    // UnsubscribeLink in emails IsNLUPHP
    if( !defined('IsNLUPHP') && !_LFDR8($_ftO66, $_IflO6, $errors, $_I816i))
      return false;

    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      if ( $_IOLil == "u_EMail" && $_IOCjL == "visiblerequired" && ( (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "")  )  ) {
         $errors[] = $_IOLil;
         if(count($_I816i) == 0)
            $_I816i[] = $_IflO6["RequiredFieldsErrorText"]."<br /><br />";
         $_I816i[] = $_IflO6[$_IOLil]."<br />";
         continue;
      }
    }

    if(count($errors) > 0) return false;

    $u_EMail = "";
    if( isset($_ftO66["u_EMail"]) && trim($_ftO66["u_EMail"]) != "")
       $u_EMail = trim($_ftO66["u_EMail"]);
       else
       if( isset($_ftO66["EMail"]) && trim($_ftO66["EMail"]) != "")
          $u_EMail = trim($_ftO66["EMail"]);
     $_ftO66["u_EMail"] = _L86JE($u_EMail);

    // special email address
    if( $u_EMail != "" && !_L8JLR($u_EMail) ) {
       $errors[] = "u_EMail";
       $_I816i[] = $_IflO6["EMailFormatErrorText"]."<br />";
    }

    // special email address
    if( $u_EMail == "" && !isset($_ftO66["key"]) ) {
       $errors[] = "u_EMail";
       $_I816i[] = $_IflO6["EMailFormatErrorText"]."<br />";
    }

    if(count($errors) == 0) {
      // check it is in list
      $_ftO66["MaillistTableName"] = $_I8I6o;
      // email address or key specified?
      if($u_EMail != "") {
        $_QLfol = "SELECT id, SubscriptionStatus FROM $_I8I6o WHERE u_EMail="._LRAFO($_ftO66["u_EMail"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_QL8i1) == 0) {
          $errors[] = "u_EMail";
          $_I816i[] = $_IflO6["EMailAddressNotInList"];
        } else {
           $_QLO0f = mysql_fetch_assoc($_QL8i1);
           $_ftO66["RecipientId"] = $_QLO0f["id"];
           if($_ftL0O) {
             if($_QLO0f["SubscriptionStatus"] == 'OptInConfirmationPending') {
               $errors[] = "u_EMail";
               $_I816i[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
             }
           }

        }
      } else {
        $_QL8i1 = _J0QQJ($_ftO66, $_IflO6, $errors, $_I816i);
        if(!$_QL8i1) return false;

        // 3. check recipient exists
        $_QLfol = "SELECT IdentString, id, SubscriptionStatus FROM $_ftO66[MaillistTableName] WHERE IdentString="._LRAFO($_ftO66["key"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
           $errors[] = $_IflO6["EMailAddressNotInList"];
           $_I816i[] = $_IflO6["EMailAddressNotInList"];
           return false;
        }
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);

        # is id = recipient id?
        $_ftO66["RecipientId"] = intval($_ftO66["RecipientId"]);
        if($_QLO0f["id"] != $_ftO66["RecipientId"]) {
           $errors[] = $_IflO6["EMailAddressNotInList"];;
           $_I816i[] = $_IflO6["EMailAddressNotInList"];;
           return false;
        }

        if($_ftL0O) {
          if($_QLO0f["SubscriptionStatus"] == 'OptInConfirmationPending') {
            $errors[] = "u_EMail";
            $_I816i[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
          }
        }

      }
      mysql_free_result($_QL8i1);
    }

   return count($errors) == 0 ? true : false;
 }

 function _LFDAF(&$_ftO66, &$_IflO6, &$errors, &$_I816i) {
   global $_QLttI;
   $_QL8i1 = _J0QQJ($_ftO66, $_IflO6, $errors, $_I816i);
   if(!$_QL8i1) return false;

   // 3. check recipient exists
   $_QLfol = "SELECT IdentString, id, SubscriptionStatus FROM $_ftO66[MaillistTableName] WHERE IdentString="._LRAFO($_ftO66["key"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      return false;
   }
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   # is id = recipient id?
   $_ftO66["RecipientId"] = intval($_ftO66["RecipientId"]);
   if($_QLO0f["id"] != $_ftO66["RecipientId"]) {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      return false;
   }

   # check SubscriptionStatus
   if($_QLO0f["SubscriptionStatus"] != 'OptInConfirmationPending') {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _LFDER(&$_ftO66, &$_IflO6, &$errors, &$_I816i) {
   global $_QLttI;

   $_QL8i1 = _J0QQJ($_ftO66, $_IflO6, $errors, $_I816i);
   if(!$_QL8i1) return false;

   // 3. check recipient exists
   $_QLfol = "SELECT IdentString, id, SubscriptionStatus FROM $_ftO66[MaillistTableName] WHERE IdentString="._LRAFO($_ftO66["key"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $errors[] = $_IflO6["EMailAddressNotInList"];
      $_I816i[] = $_IflO6["EMailAddressNotInList"];
      return false;
   }
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   # is id = recipient id?
   $_ftO66["RecipientId"] = intval($_ftO66["RecipientId"]);
   if($_QLO0f["id"] != $_ftO66["RecipientId"]) {
      $errors[] = $_IflO6["EMailAddressNotInList"];
      $_I816i[] = $_IflO6["EMailAddressNotInList"];
      return false;
   }

   # check SubscriptionStatus
   if($_QLO0f["SubscriptionStatus"] != 'OptInConfirmationPending') {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _LFE80(&$_ftO66, &$_IflO6, &$errors, &$_I816i) {
   global $_QLttI;
   $_QL8i1 = _J0QQJ($_ftO66, $_IflO6, $errors, $_I816i);
   if(!$_QL8i1) return false;

   // 3. check recipient exists
   $_QLfol = "SELECT IdentString, id, SubscriptionStatus, u_EMail FROM $_ftO66[MaillistTableName] WHERE IdentString="._LRAFO($_ftO66["key"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $errors[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   $_ftO66["u_EMail"] = $_QLO0f["u_EMail"];

   # is id = recipient id?
   $_ftO66["RecipientId"] = intval($_ftO66["RecipientId"]);
   if($_QLO0f["id"] != $_ftO66["RecipientId"]) {
      $errors[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }

   # check SubscriptionStatus
   if($_QLO0f["SubscriptionStatus"] != 'OptOutConfirmationPending') {
      $errors[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _LFEBB(&$_ftO66, &$_IflO6, &$errors, &$_I816i) {
   global $_QLttI;

   $_QL8i1 = _J0QQJ($_ftO66, $_IflO6, $errors, $_I816i);
   if(!$_QL8i1) return false;

   // 3. check recipient exists
   $_QLfol = "SELECT IdentString, id, SubscriptionStatus FROM $_ftO66[MaillistTableName] WHERE IdentString="._LRAFO($_ftO66["key"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $errors[] = $_IflO6["EMailAddressNotInList"];
      $_I816i[] = $_IflO6["EMailAddressNotInList"];
      return false;
   }
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   # is id = recipient id?
   $_ftO66["RecipientId"] = intval($_ftO66["RecipientId"]);
   if($_QLO0f["id"] != $_ftO66["RecipientId"]) {
      $errors[] = $_IflO6["EMailAddressNotInList"];
      $_I816i[] = $_IflO6["EMailAddressNotInList"];
      return false;
   }

   # check SubscriptionStatus
   if($_QLO0f["SubscriptionStatus"] != 'OptOutConfirmationPending') {
      $errors[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _LFFPD(&$_ftO66, $_IOJoI, $_IflO6, $_I8I6o, $_jjj8f, $_Jj6f0, &$errors, &$_I816i) {
    global $_I8tfQ, $_I8OoJ, $_QLttI, $INTERFACE_LANGUAGE;

    if(!_LFDR8($_ftO66, $_IflO6, $errors, $_I816i))
      return false;

    if($_IflO6["GroupsAsMandatoryField"] && $_IflO6["GroupsOption"] == 2) {
      if( !isset($_ftO66["RecipientGroups"]) || !is_array($_ftO66["RecipientGroups"]) || count($_ftO66["RecipientGroups"]) == 0 ){
         $errors[] = "RecipientGroups";
         $_I816i[] = $_IflO6["GroupsAsMandatoryFieldError"]."<br /><br />";
      }
    }

    if($_IflO6["PrivacyPolicyAcceptanceAsMandatoryField"]){
      if (!isset($_ftO66["PrivacyPolicyAccepted"]) || trim($_ftO66["PrivacyPolicyAccepted"]) != "1"){
         $errors[] = "PrivacyPolicyAccepted";
         $_I816i[] = $_IflO6["PrivacyPolicyAcceptanceError"]."<br /><br />";
      }
    }

    // see CheckOnSubscribe
    foreach($_IOJoI as $_IOLil => $_IOCjL) {
      # first letter uppercase
      if($_IOLil == "u_FirstName" || $_IOLil == "u_LastName" || $_IOLil == "u_MiddleName")
        if(isset($_ftO66[$_IOLil]))
          $_ftO66[$_IOLil] = ucfirst($_ftO66[$_IOLil]);

       # when it's a checkbox, value can't be set
       # same checking on Subscribe and Edit
       # don't check for empty("0"), this gives true
      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");
      if ( ($_IOLil == "u_PersonalizedTracking" || strpos($_IOLil, "u_UserFieldBool") !== false) && ($_IOCjL == "visiblerequired" || $_IOCjL == "visible") && $_ftCL8  )
        $_ftO66[$_IOLil] = ($_IOCjL == "visiblerequired") ? "" : "0";

      // u_PersonalizedTracking must be ever 0 or 1
      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");
      if ( $_IOLil == "u_PersonalizedTracking" && ($_IOCjL == "visiblerequired" || $_IOCjL == "visible") && ( $_ftCL8 || !is_numeric($_ftO66[$_IOLil]) ) )
        $_ftO66[$_IOLil] = ($_IOCjL == "visiblerequired") ? "" : "0";
      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");
      if ( $_IOLil == "u_PersonalizedTracking" && !$_ftCL8 && (intval($_ftO66[$_IOLil]) > 1 || intval($_ftO66[$_IOLil]) < 0) )
        $_ftO66[$_IOLil] = ($_IOCjL == "visiblerequired") ? "" : "0";

      $_ftCL8 = (!isset($_ftO66[$_IOLil]) || trim($_ftO66[$_IOLil]) == "");

      if ( $_IOCjL == "visiblerequired" && ( $_ftCL8 || ($_IOLil == "u_Salutation" && $_ftO66[$_IOLil] == "---" ) )  ) {
         $errors[] = $_IOLil;
         if(count($_I816i) == 0)
            $_I816i[] = $_IflO6["RequiredFieldsErrorText"]."<br /><br />";
         $_I816i[] = $_IflO6[$_IOLil]."<br />";
         continue;
      }

      // spezial email address
      if($_IOLil == "u_EMail") {
        if(!_L8JEL($_ftO66[$_IOLil])) {
           $errors[] = $_IOLil;
           $_I816i[] = $_IflO6["EMailFormatErrorText"]."<br />";
        }else{
         $_ftO66[$_IOLil] = _L86JE($_ftO66[$_IOLil]);
        }
      }

      // spezial birthday
      if($_IOLil == "u_Birthday" && !empty($_ftO66[$_IOLil]) && $_ftO66[$_IOLil] != "") {
        if(strlen($_ftO66[$_IOLil]) < 10) {
          $errors[] = $_IOLil;
          if(count($_I816i) == 0)
             $_I816i[] = $_IflO6["RequiredFieldsErrorText"]."<br /><br />";
          $_I816i[] = $_IflO6[$_IOLil]."<br />";
        } else {
          $_fti1L = ".";
          if( strpos($_ftO66[$_IOLil], ".") === false ) {
            if( strpos($_ftO66[$_IOLil], "-") === false ) {
              if( strpos($_ftO66[$_IOLil], "/") === false ) {
              } else $_fti1L = "/";
            }
            else $_fti1L = "-";
          }

          $_I1OoI = explode($_fti1L, $_ftO66[$_IOLil]);
          if ( count($_I1OoI) != 3 ) {
            $errors[] = $_IOLil;
            if(count($_I816i) == 0)
               $_I816i[] = $_IflO6["RequiredFieldsErrorText"]."<br /><br />";
            $_I816i[] = $_IflO6[$_IOLil]."<br />";
          }
        }

      } // spezial birthday END

    }

    if(count($errors) > 0) return false;
    //

    if( isset($_ftO66["NewEMail"]) ) {
      $_ftO66["NewEMail"] = trim($_ftO66["NewEMail"]);
      if(!_L8JEL($_ftO66["NewEMail"])) {
        $errors[] = "NewEMail";
        $_I816i[] = $_IflO6["EMailFormatErrorText"]."<br />";
        return false;
      }else{
        $_ftO66["NewEMail"] = _L86JE($_ftO66["NewEMail"]);
      }

      // always exists?
      if(strtolower($_ftO66["NewEMail"]) != strtolower(trim($_ftO66["u_EMail"]))) {
        $_QLfol = "SELECT id FROM $_I8I6o WHERE u_EMail="._LRAFO($_ftO66["NewEMail"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_QL8i1) > 0) {
          $errors[] = "NewEMail";
          $_I816i[] = $_IflO6["EMailAddressAlwaysInList"];
          mysql_free_result($_QL8i1);
          return false;
        }
        mysql_free_result($_QL8i1);
      }

      $_JjOjI = trim($_ftO66["NewEMail"]);
      $_JjOjI = substr($_JjOjI, strpos($_JjOjI, '@') + 1);

      // local blacklist check
      if(count($errors) == 0) {
        $_QLfol = "SELECT id FROM $_jjj8f WHERE u_EMail="._LRAFO($_ftO66["NewEMail"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_QL8i1) > 0) {
          $errors[] = "NewEMail";
          $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
          mysql_free_result($_QL8i1);
          return false;
        }
        mysql_free_result($_QL8i1);
      }

      // local domain blacklist check
      if(count($errors) == 0) {
        $_QLfol = "SELECT id FROM $_Jj6f0 WHERE Domainname="._LRAFO($_JjOjI);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_QL8i1) > 0) {
          $errors[] = "NewEMail";
          $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
          mysql_free_result($_QL8i1);
          return false;
        }
        mysql_free_result($_QL8i1);
      }

      // global blacklist check
      if(count($errors) == 0) {
        $_QLfol = "SELECT id FROM $_I8tfQ WHERE u_EMail="._LRAFO($_ftO66["NewEMail"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_QL8i1) > 0) {
          $errors[] = "NewEMail";
          $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
          mysql_free_result($_QL8i1);
          return false;
        }
        mysql_free_result($_QL8i1);
      }

      // global domain blacklist check
      if(count($errors) == 0) {
        $_QLfol = "SELECT id FROM $_I8OoJ WHERE Domainname="._LRAFO($_JjOjI);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_QL8i1) > 0) {
          $errors[] = "NewEMail";
          $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
          mysql_free_result($_QL8i1);
          return false;
        }
        mysql_free_result($_QL8i1);
      }

      // ECGlist check
      if(count($errors) == 0 && _JOLQE("ECGListCheck")) {
        $_I016j = _L6AJP( $_ftO66["NewEMail"] );
        if( (is_bool($_I016j) && $_I016j) || is_string($_I016j) ){
          $errors[] = "NewEMail";
          $_I816i[] = $_IflO6["EMailAddressBlacklisted"];
          return false;
        }
      }

    }

    if($_IflO6["GroupsAsMandatoryField"] && $_IflO6["GroupsOption"] == 2) {
      if( !isset($_ftO66["RecipientGroups"]) || !is_array($_ftO66["RecipientGroups"]) || count($_ftO66["RecipientGroups"]) == 0 ){
         $errors[] = "RecipientGroups";
         $_I816i[] = $_IflO6["GroupsAsMandatoryFieldError"]."<br /><br />";
      }
    }

   // no Captcha on Edit
   $_IflO6["UseCaptcha"] = 0;
   $_IflO6["UseReCaptcha"] = 0;
   return _LFD8C($_ftO66, $_IOJoI, $_IflO6, $_I8I6o, $errors, $_I816i, true);
 }

 function _LFFEL(&$_ftO66, &$_IflO6, &$errors, &$_I816i) {
   global $_QLttI;
   $_QL8i1 = _J0QQJ($_ftO66, $_IflO6, $errors, $_I816i);
   if(!$_QL8i1) return false;

   // 3. check recipient exists
   $_QLfol = "SELECT IdentString, id, SubscriptionStatus, u_EMail FROM $_ftO66[MaillistTableName] WHERE IdentString="._LRAFO($_ftO66["key"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      return false;
   }
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_ftO66["u_EMail"] = $_QLO0f["u_EMail"];

   # is id = recipient id?
   $_ftO66["RecipientId"] = intval($_ftO66["RecipientId"]);
   if($_QLO0f["id"] != $_ftO66["RecipientId"]) {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationFailure"];
      return false;
   }

   # check Edit Status
   $_QLfol = "SELECT `ChangedDetails` FROM $_ftO66[EditTableName] WHERE `Member_id`=$_ftO66[RecipientId]";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0) {
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
     $_ftO66["ChangedDetails"] = $_QLO0f["ChangedDetails"];
   } else {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      return false;
   }

   return count($errors) == 0 ? true : false;
 }

 function _LFFEC(&$_ftO66, &$_IflO6, &$errors, &$_I816i) {
   global $_QLttI;

   $_QL8i1 = _J0QQJ($_ftO66, $_IflO6, $errors, $_I816i);
   if(!$_QL8i1) return false;

   // 3. check recipient exists
   $_QLfol = "SELECT IdentString, id, SubscriptionStatus FROM $_ftO66[MaillistTableName] WHERE IdentString="._LRAFO($_ftO66["key"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $errors[] = $_IflO6["EMailAddressNotInList"];
      $_I816i[] = $_IflO6["EMailAddressNotInList"];
      return false;
   }
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   # is id = recipient id?
   $_ftO66["RecipientId"] = intval($_ftO66["RecipientId"]);
   if($_QLO0f["id"] != $_ftO66["RecipientId"]) {
      $errors[] = $_IflO6["EMailAddressNotInList"];
      $_I816i[] = $_IflO6["EMailAddressNotInList"];
      return false;
   }

   # check Edit Status
   $_QLfol = "SELECT `Member_id` FROM `$_ftO66[EditTableName]` WHERE `Member_id`=".intval($_ftO66["RecipientId"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) == 0) {
      $errors[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      $_I816i[] = $_IflO6["SubscribeTextOnConfirmationAgain"];
      return false;
   }
    else
     if($_QL8i1)
       mysql_free_result($_QL8i1);


   return count($errors) == 0 ? true : false;
 }


 function _J00LP($_IC1C6, $_ftLi0 = true) {
   global $_QL88I, $_QLttI;

   $_QLfol = "SELECT id FROM $_QL88I WHERE Name="._LRAFO($_IC1C6);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
     $_QLO0f = mysql_fetch_row($_QL8i1);
     mysql_free_result($_QL8i1);
     return $_QLO0f[0];
   } else {
     if($_QL8i1)
       mysql_free_result($_QL8i1);

     if($_ftLi0 && !IsUtf8String($_IC1C6)) {
       $_IC1C6 = utf8_encode($_IC1C6);
       return _J00LP($_IC1C6, false);
     }
   }
   return $_IC1C6;
 }

 function _J011E($MailingListId, $_IC1C6, $_ftLi0 = true) {
   global $_QL88I, $_QLttI;

   if(!IsUtf8String($_IC1C6))
     $_IC1C6 = utf8_encode($_IC1C6);

   $_QLfol = "SELECT FormsTableName FROM $_QL88I WHERE id=".intval($MailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
     $_QLfol = "SELECT id FROM $_QLO0f[FormsTableName] WHERE Name="._LRAFO($_IC1C6);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
       $_QLO0f = mysql_fetch_row($_QL8i1);
       mysql_free_result($_QL8i1);
       return $_QLO0f[0];
     }
   } else {

     if($_QL8i1)
       mysql_free_result($_QL8i1);

     if($_ftLi0 && !IsUtf8String($_IC1C6)) {
       $_IC1C6 = utf8_encode($_IC1C6);
       return _J011E($MailingListId, $_IC1C6, false);
     }

   }
   return $_IC1C6;
 }

 function _J016F($ML, $_j1liQ, $_ftLi0 = true) {
   global $_QL88I, $_QLttI;

   if( (string)(intval($_j1liQ) * 1) == (string)$_j1liQ ) // name?
      return $_j1liQ;

   $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=".intval($ML);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
     $_QLO0f = mysql_fetch_row($_QL8i1);
     mysql_free_result($_QL8i1);
     $_QljJi = $_QLO0f[0];
   } else
     return false;

   $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO($_j1liQ);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
     $_QLO0f = mysql_fetch_row($_QL8i1);
     mysql_free_result($_QL8i1);
     return $_QLO0f[0];
   } else {
     if($_QL8i1)
       mysql_free_result($_QL8i1);

     if($_ftLi0 && !IsUtf8String($_j1liQ)) {
       $_j1liQ = utf8_encode($_j1liQ);
       return _J016F($ML, $_j1liQ, false);
     }
   }
   return false;
 }

 function _J01BO($key, $MailingListId) {
   global $_QL88I, $_QLttI;

   $_QLfol = "SELECT MaillistTableName FROM $_QL88I WHERE id=".intval($MailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
     $_QLfol = "SELECT * FROM $_QLO0f[MaillistTableName] WHERE IdentString="._LRAFO($key);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
   }
   return false;
 }

 include_once("onlineupdate.inc.php");

 function _J0QQJ(&$_ftO66, &$_IflO6, &$errors, &$_I816i) {
   global $commonmsgMailListNotFound, $commonmsgHTMLFormNotFound, $commonmsgParamKeyInvalid, $_JQJll, $_QL88I, $_I1tQf, $_I18lo;
   global $_IjljI, $_Ijt0i, $_jfQtI, $_Ifi1J, $_I1O0i, $_I8tfQ;
   global $_QLttI;

   global $INTERFACE_STYLE, $INTERFACE_LANGUAGE;

   $_J0COJ = "";

   if( !isset($_ftO66["key"]) ) {
     $_JCIO0 = $commonmsgParamKeyInvalid;
     _LJD1D($_J0COJ, $_JCIO0);
     return false;
   }

   $key = $_ftO66["key"];
   if(!_LPQEP($key, $_IfLJj, $MailingListId, $FormId, !empty($_ftO66["rid"]) ? $_ftO66["rid"] : "" )) {
     $_JCIO0 = $commonmsgParamKeyInvalid;
     _LJD1D($_J0COJ, $_JCIO0);
     return false;
   }

   $_ftO66["MailingListId"] = intval($MailingListId);
   $_ftO66["FormId"] = intval($FormId);
   $_ftO66["RecipientId"] = intval($_IfLJj);

   // 1. check maillist exists
   $_QLfol = "SELECT users_id, MaillistTableName, StatisticsTableName, FormsTableName, EditTableName, `ReasonsForUnsubscripeTableName`, `ExternalSubscriptionScript`, `ExternalUnsubscriptionScript`, `ExternalEditScript` FROM $_QL88I WHERE id=".intval($MailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
     $_JCIO0 = $commonmsgMailListNotFound;
     _LJD1D($_J0COJ, $_JCIO0);
     return false;
   }
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_I8I6o = $_QLO0f["MaillistTableName"];
   $_IfJoo = $_QLO0f["FormsTableName"];
   $_I8jjj = $_QLO0f["StatisticsTableName"];
   $_I8Jti = $_QLO0f["EditTableName"];
   $_jQIIl = $_QLO0f["ReasonsForUnsubscripeTableName"];
   $_JCOJ6 = $_QLO0f["ExternalSubscriptionScript"];
   $_JCOit = $_QLO0f["ExternalUnsubscriptionScript"];
   $_JCojC = $_QLO0f["ExternalEditScript"];

   // tables
   $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_QLO0f[users_id]";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_JCC81 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   _LR8AP($_JCC81);

   _JQRLR($INTERFACE_LANGUAGE);

   // set the user paths for images, attachments....
   _LRRFJ($_QLO0f["users_id"]);

   $_JoiQi = "";
   $_JoiJL = 0;
   $_ftLil = 0;
   if(!_J0RCP($_JoiQi, $_JoiJL, $_ftLil, false))
       mysql_query("DELETE FROM $_I1O0i WHERE id=$_ftLil", $_QLttI);
   $_QLfol = "SELECT Dashboard FROM $_I1O0i LIMIT 0,1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1OfI = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   $_I1OoI = _JOLOA($_I1OfI);
   if(count($_I1OoI) == 0 ||
      !isset($_I1OoI["DashboardDate"]) ||
      $_I1OoI["DashboardDate"] == "" ||
      !isset($_I1OoI["DashboardUser"]) ||
      $_I1OoI["DashboardUser"] == "" ||
      !isset($_I1OoI["DashboardTag"]) ||
      $_I1OoI["DashboardTag"] == "" ||
      (strlen($_I1OoI["DashboardTag"]) != $_JQJll - 108)

     ) {
       print "@KEYINVALID";
       exit;
     }
   // ***

   $_ftO66["MaillistTableName"] = $_I8I6o;
   $_ftO66["FormsTableName"] = $_IfJoo;
   $_ftO66["StatisticsTableName"] = $_I8jjj;
   $_ftO66["EditTableName"] = $_I8Jti;

   // 2. check form exists
   $_QLfol = "SELECT $_IfJoo.*, $_IfJoo.id AS FormId, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=".intval($FormId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
     $_JCIO0 = $commonmsgHTMLFormNotFound;
     _LJD1D($_J0COJ, $_JCIO0);
   }

   $_IflO6 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_IflO6["MailingListId"] = intval($MailingListId);
   // External Scripts
   $_IflO6["ExternalSubscriptionScript"] = $_JCOJ6;
   $_IflO6["ExternalUnsubscriptionScript"] = $_JCOit;
   $_IflO6["ExternalEditScript"] = $_JCojC;
   // Reasons for unsubscripe
   $_IflO6["ReasonsForUnsubscripeTableName"] = $_jQIIl;

   if(isset($_ftO66["Action"]))
     $_IflO6["Action"] = $_ftO66["Action"];

   # set theme and language for correct template
   $INTERFACE_STYLE = $_IflO6["Theme"];
   $INTERFACE_LANGUAGE = $_IflO6["Language"];

   _JQRLR($INTERFACE_LANGUAGE);

   return count($errors) == 0 ? true : false;
 }


?>
