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

 include_once("savedoptions.inc.php");

 function _OFEOP(&$_6Ijo6, $_I16jJ, $_QLl1Q, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $_jf1J1, &$errors, &$_Ql1O8) {
    global $_Ql8C0, $_Qlt66, $_Q61I1;
    if(!isset($_SESSION))
      global $_SESSION;

    if( $_QLl1Q["UseCaptcha"]  ) {
      if ( (!isset($_6Ijo6['user_captcha_string'])) || ($_6Ijo6['user_captcha_string'] == '') || $_6Ijo6['user_captcha_string'] != $GLOBALS['crypt_class']->base64_decode_advanced( $_SESSION['captcha_string']) ) {
         $errors[] = "user_captcha_string";
         $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."<br /><br />";
      }
      # FF Bug we can't forget word $_SESSION['captcha_string']) = ''; # forget the captcha word!
    }

    if(count($errors) > 0) return false;

    if( $_QLl1Q["UseReCaptcha"]  ) {
        if(empty($_6Ijo6["g-recaptcha-response"])){
          $errors[] = "user_captcha_string";
          $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."(1)<br /><br />";
        }
        if(!empty($_6Ijo6["g-recaptcha-response"])){
          $_JJQJ1 = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getOwnIP();
          $_Qf1i1 = "secret=".$_QLl1Q["PrivateReCaptchaKey"]."&response=".$_6Ijo6["g-recaptcha-response"]."&remoteip=".$_JJQJ1;
          $_j88of = "";
          $_j8t8L = "";

          if(function_exists("DoHTTPPOSTRequest"))
            $_6I61l = DoHTTPPOSTRequest("www.google.com", "/recaptcha/api/siteverify", $_Qf1i1, 443);
          else
            $_6I61l = _OCQ6E("www.google.com", "POST", "/recaptcha/api/siteverify", $_Qf1i1, 0, 443, false, "", "", $_j88of, $_j8t8L);

          #recaptchav1 recaptcha_check_answer ($_QLl1Q["PrivateReCaptchaKey"], isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getOwnIP(), $_6Ijo6["recaptcha_challenge_field"], $_6Ijo6["recaptcha_response_field"]);

          if (!$_6I61l) {
            $errors[] = "user_captcha_string";
            $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."(2)<br /><br />";
          }
          if ($_6I61l && strpos($_6I61l, '"success": true') === false) {
            $errors[] = "user_captcha_string";
            $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."(3)<br /><br />";
          }
        }
    }
    if(count($errors) > 0) return false;

    if($_QLl1Q["GroupsAsMandatoryField"] && $_QLl1Q["GroupsOption"] == 2) {
      if( !isset($_6Ijo6["RecipientGroups"]) || !is_array($_6Ijo6["RecipientGroups"]) || count($_6Ijo6["RecipientGroups"]) == 0 ){
         $errors[] = "RecipientGroups";
         $_Ql1O8[] = $_QLl1Q["GroupsAsMandatoryFieldError"]."<br /><br />";
      }
    }

    if(count($errors) > 0) return false;

    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      # first letter uppercase
      if($_I1i8O == "u_FirstName" || $_I1i8O == "u_LastName" || $_I1i8O == "u_MiddleName")
        if(isset($_6Ijo6[$_I1i8O]))
          $_6Ijo6[$_I1i8O] = ucfirst($_6Ijo6[$_I1i8O]);

      if ( $_I1L81 == "visiblerequired" && ( (!isset($_6Ijo6[$_I1i8O]) || trim($_6Ijo6[$_I1i8O]) == "") || ($_I1i8O == "u_Salutation" && $_6Ijo6[$_I1i8O] == "---" ) )  ) {
         $errors[] = $_I1i8O;
         if(count($_Ql1O8) == 0)
            $_Ql1O8[] = $_QLl1Q["RequiredFieldsErrorText"]."<br /><br />";
         $_Ql1O8[] = $_QLl1Q[$_I1i8O]."<br />";
         continue;
      }

      // spezial email address
      if($_I1i8O == "u_EMail") {
        if(!_OPAOJ($_6Ijo6[$_I1i8O])) {
           $errors[] = $_I1i8O;
           $_Ql1O8[] = $_QLl1Q["EMailFormatErrorText"]."<br />";
        }
      }

      // spezial birthday
      if($_I1i8O == "u_Birthday" && !empty($_6Ijo6[$_I1i8O]) && $_6Ijo6[$_I1i8O] != "") {
        if(strlen($_6Ijo6[$_I1i8O]) < 10) {
          $errors[] = $_I1i8O;
          if(count($_Ql1O8) == 0)
             $_Ql1O8[] = $_QLl1Q["RequiredFieldsErrorText"]."<br /><br />";
          $_Ql1O8[] = $_QLl1Q[$_I1i8O]."<br />";
        } else {
          $_6I6jO = ".";
          if( strpos($_6Ijo6[$_I1i8O], ".") === false ) {
            if( strpos($_6Ijo6[$_I1i8O], "-") === false ) {
              if( strpos($_6Ijo6[$_I1i8O], "/") === false ) {
              } else $_6I6jO = "/";
            }
            else $_6I6jO = "-";
          }

          $_Q8otJ = explode($_6I6jO, $_6Ijo6[$_I1i8O]);
          if ( count($_Q8otJ) != 3 ) {
            $errors[] = $_I1i8O;
            if(count($_Ql1O8) == 0)
               $_Ql1O8[] = $_QLl1Q["RequiredFieldsErrorText"]."<br /><br />";
            $_Ql1O8[] = $_QLl1Q[$_I1i8O]."<br />";
          }
        }

      } // spezial birthday END

    }

    // local blacklist check
    if(count($errors) == 0) {
      $_QJlJ0 = "SELECT id FROM $_ItCCo WHERE u_EMail="._OPQLR($_6Ijo6["u_EMail"]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $errors[] = "u_EMail";
        $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
      }
      mysql_free_result($_Q60l1);
    }

    if(!empty($_6Ijo6["u_EMail"]))
      $_jfjC6 = trim($_6Ijo6["u_EMail"]);
      else
      $_jfjC6 = "";
    $_jfjC6 = substr($_jfjC6, strpos($_jfjC6, '@') + 1);

    // local domain blacklist check
    if(count($errors) == 0) {
      $_QJlJ0 = "SELECT id FROM $_jf1J1 WHERE Domainname="._OPQLR($_jfjC6);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $errors[] = "u_EMail";
        $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
      }
      mysql_free_result($_Q60l1);
    }

    // global blacklist check
    if(count($errors) == 0) {
      $_QJlJ0 = "SELECT id FROM $_Ql8C0 WHERE u_EMail="._OPQLR($_6Ijo6["u_EMail"]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $errors[] = "u_EMail";
        $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
      }
      mysql_free_result($_Q60l1);
    }

    // global domain blacklist check
    if(count($errors) == 0) {
      $_QJlJ0 = "SELECT id FROM $_Qlt66 WHERE Domainname="._OPQLR($_jfjC6);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $errors[] = "u_EMail";
        $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
      }
      mysql_free_result($_Q60l1);
    }

    // ECGlist check
    if(count($errors) == 0 && _LQDLR("ECGListCheck")) {
      if( _OC0DR( $_6Ijo6["u_EMail"] ) ){
        $errors[] = "u_EMail";
        $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
      }
    }

    // all thinks OK ?
    if(count($errors) == 0) {

      // duplicate check
      $_QJlJ0 = "SELECT id, SubscriptionStatus FROM $_QlQC8 WHERE u_EMail="._OPQLR($_6Ijo6["u_EMail"]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $_Q8OiJ = mysql_fetch_row($_Q60l1);
        $_QLitI = $_Q8OiJ[0];
        $_jfI81 = $_Q8OiJ[1];
        mysql_free_result($_Q60l1);

        // send confirmation mail again, ignore groups
        if($_jfI81 == 'OptInConfirmationPending') {
          // tricky set SubscriptionStatus and send in main script opt-in mail again
          $_6Ijo6["SubscriptionStatus"] = 'OptInConfirmationPending';
          $_6Ijo6["RecipientId"] = $_QLitI;
          return true;
        }

        // Group check
        if(isset($_6Ijo6["RecipientGroups"])) {
          $_QJlJ0 = "SELECT $_Q6t6j.id FROM $_QLI68 LEFT JOIN $_Q6t6j ON groups_id=id WHERE Member_id=".intval($_QLitI);
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_IitLf = array();
          while($_Q8OiJ = mysql_fetch_row($_Q60l1)) {
            $_IitLf[] = $_Q8OiJ[0];
          }
          mysql_free_result($_Q60l1);
          $_6I6OQ = false;
          // remove duplicate groups
          for($_Q6llo=0; $_Q6llo<count($_IitLf); $_Q6llo++) {
            if(array_key_exists($_IitLf[$_Q6llo], $_6Ijo6["RecipientGroups"])) {
              $_6I6OQ = true;
              unset($_6Ijo6["RecipientGroups"][$_IitLf[$_Q6llo]]);
            }
          }
          if($_6I6OQ && count($_6Ijo6["RecipientGroups"]) == 0 ) {
            $errors[] = "u_EMail";
            $_Ql1O8[] = $_QLl1Q["EMailAddressAlwaysInList"];
          } else {
            // tricky set DuplicateEntry to change the groups
            $_6Ijo6["DuplicateEntry"] = $_QLitI; // exists but group change
          }

        } else {
          $errors[] = "u_EMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressAlwaysInList"];
        }

      }

    }

   return count($errors) == 0 ? true : false;
 }

 function _OFEP8(&$_6Ijo6, $_I16jJ, $_QLl1Q, $_QlQC8, &$errors, &$_Ql1O8, $_6If80 = false) {
    global $_Q61I1;
    if(!isset($_SESSION))
      global $_SESSION;

    if( $_QLl1Q["UseCaptcha"]  ) {
      if ( (!isset($_6Ijo6['user_captcha_string'])) || ($_6Ijo6['user_captcha_string'] == '') || $_6Ijo6['user_captcha_string'] != $GLOBALS['crypt_class']->base64_decode_advanced( $_SESSION['captcha_string']) ) {
         $errors[] = "user_captcha_string";
         $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."<br /><br />";
         @session_destroy();
      }
    }
    if(count($errors) > 0) return false;

    if( $_QLl1Q["UseReCaptcha"]  ) {
        if(empty($_6Ijo6["g-recaptcha-response"])){
          $errors[] = "user_captcha_string";
          $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."<br /><br />";
        }
        if(!empty($_6Ijo6["g-recaptcha-response"])){
          $_JJQJ1 = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getOwnIP();
          $_Qf1i1 = "secret=".$_QLl1Q["PrivateReCaptchaKey"]."&response=".$_6Ijo6["g-recaptcha-response"]."&remoteip=".$_JJQJ1;
          $_j88of = "";
          $_j8t8L = "";

          if(function_exists("DoHTTPPOSTRequest"))
            $_6I61l = DoHTTPPOSTRequest("www.google.com", "/recaptcha/api/siteverify", $_Qf1i1, 443);
          else
            $_6I61l = _OCQ6E("www.google.com", "POST", "/recaptcha/api/siteverify", $_Qf1i1, 0, 443, false, "", "", $_j88of, $_j8t8L);

          #recaptchav1 recaptcha_check_answer ($_QLl1Q["PrivateReCaptchaKey"], isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : getOwnIP(), $_6Ijo6["recaptcha_challenge_field"], $_6Ijo6["recaptcha_response_field"]);

          if (!$_6I61l) {
            $errors[] = "user_captcha_string";
            $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."<br /><br />";
          }
          if ($_6I61l && strpos($_6I61l, '"success": true') === false) {
            $errors[] = "user_captcha_string";
            $_Ql1O8[] = $_QLl1Q["CaptchaStringIncorrect"]."<br /><br />";
          }
        }
    }
    if(count($errors) > 0) return false;

    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if ( $_I1i8O == "u_EMail" && $_I1L81 == "visiblerequired" && ( (!isset($_6Ijo6[$_I1i8O]) || trim($_6Ijo6[$_I1i8O]) == "")  )  ) {
         $errors[] = $_I1i8O;
         if(count($_Ql1O8) == 0)
            $_Ql1O8[] = $_QLl1Q["RequiredFieldsErrorText"]."<br /><br />";
         $_Ql1O8[] = $_QLl1Q[$_I1i8O]."<br />";
         continue;
      }
    }

    if(count($errors) > 0) return false;

    $u_EMail = "";
    if( isset($_6Ijo6["u_EMail"]) && trim($_6Ijo6["u_EMail"]) != "")
       $u_EMail = trim($_6Ijo6["u_EMail"]);
       else
       if( isset($_6Ijo6["EMail"]) && trim($_6Ijo6["EMail"]) != "")
          $u_EMail = trim($_6Ijo6["EMail"]);
     $_6Ijo6["u_EMail"] = $u_EMail;

    // special email address
    if( $u_EMail != "" && !_OPAOJ($u_EMail) ) {
       $errors[] = "u_EMail";
       $_Ql1O8[] = $_QLl1Q["EMailFormatErrorText"]."<br />";
    }

    // special email address
    if( $u_EMail == "" && !isset($_6Ijo6["key"]) ) {
       $errors[] = "u_EMail";
       $_Ql1O8[] = $_QLl1Q["EMailFormatErrorText"]."<br />";
    }

    if(count($errors) == 0) {
      // check it is in list
      $_6Ijo6["MaillistTableName"] = $_QlQC8;
      // email address or key specified?
      if($u_EMail != "") {
        $_QJlJ0 = "SELECT id, SubscriptionStatus FROM $_QlQC8 WHERE u_EMail="._OPQLR($_6Ijo6["u_EMail"]);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_Q60l1) == 0) {
          $errors[] = "u_EMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
        } else {
           $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
           $_6Ijo6["RecipientId"] = $_Q6Q1C["id"];
           if($_6If80) {
             if($_Q6Q1C["SubscriptionStatus"] == 'OptInConfirmationPending') {
               $errors[] = "u_EMail";
               $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
             }
           }

        }
      } else {
        $_Q60l1 = _L0O80($_6Ijo6, $_QLl1Q, $errors, $_Ql1O8);
        if(!$_Q60l1) return false;

        // 3. check recipient exists
        $_QJlJ0 = "SELECT IdentString, id, SubscriptionStatus FROM $_6Ijo6[MaillistTableName] WHERE IdentString="._OPQLR($_6Ijo6["key"]);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
           $errors[] = $_QLl1Q["EMailAddressNotInList"];
           $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
           return false;
        }
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);

        # is id = recipient id?
        $_6Ijo6["RecipientId"] = intval($_6Ijo6["RecipientId"]);
        if($_Q6Q1C["id"] != $_6Ijo6["RecipientId"]) {
           $errors[] = $_QLl1Q["EMailAddressNotInList"];;
           $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];;
           return false;
        }

        if($_6If80) {
          if($_Q6Q1C["SubscriptionStatus"] == 'OptInConfirmationPending') {
            $errors[] = "u_EMail";
            $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
          }
        }

      }
      mysql_free_result($_Q60l1);
    }

   return count($errors) == 0 ? true : false;
 }

 function _OFEAQ(&$_6Ijo6, &$_QLl1Q, &$errors, &$_Ql1O8) {
   global $_Q61I1;
   $_Q60l1 = _L0O80($_6Ijo6, $_QLl1Q, $errors, $_Ql1O8);
   if(!$_Q60l1) return false;

   // 3. check recipient exists
   $_QJlJ0 = "SELECT IdentString, id, SubscriptionStatus FROM $_6Ijo6[MaillistTableName] WHERE IdentString="._OPQLR($_6Ijo6["key"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      return false;
   }
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   # is id = recipient id?
   $_6Ijo6["RecipientId"] = intval($_6Ijo6["RecipientId"]);
   if($_Q6Q1C["id"] != $_6Ijo6["RecipientId"]) {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      return false;
   }

   # check SubscriptionStatus
   if($_Q6Q1C["SubscriptionStatus"] != 'OptInConfirmationPending') {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _OFFQO(&$_6Ijo6, &$_QLl1Q, &$errors, &$_Ql1O8) {
   global $_Q61I1;

   $_Q60l1 = _L0O80($_6Ijo6, $_QLl1Q, $errors, $_Ql1O8);
   if(!$_Q60l1) return false;

   // 3. check recipient exists
   $_QJlJ0 = "SELECT IdentString, id, SubscriptionStatus FROM $_6Ijo6[MaillistTableName] WHERE IdentString="._OPQLR($_6Ijo6["key"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $errors[] = $_QLl1Q["EMailAddressNotInList"];
      $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
      return false;
   }
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   # is id = recipient id?
   $_6Ijo6["RecipientId"] = intval($_6Ijo6["RecipientId"]);
   if($_Q6Q1C["id"] != $_6Ijo6["RecipientId"]) {
      $errors[] = $_QLl1Q["EMailAddressNotInList"];
      $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
      return false;
   }

   # check SubscriptionStatus
   if($_Q6Q1C["SubscriptionStatus"] != 'OptInConfirmationPending') {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _OFFPJ(&$_6Ijo6, &$_QLl1Q, &$errors, &$_Ql1O8) {
   global $_Q61I1;
   $_Q60l1 = _L0O80($_6Ijo6, $_QLl1Q, $errors, $_Ql1O8);
   if(!$_Q60l1) return false;

   // 3. check recipient exists
   $_QJlJ0 = "SELECT IdentString, id, SubscriptionStatus, u_EMail FROM $_6Ijo6[MaillistTableName] WHERE IdentString="._OPQLR($_6Ijo6["key"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $errors[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   $_6Ijo6["u_EMail"] = $_Q6Q1C["u_EMail"];

   # is id = recipient id?
   $_6Ijo6["RecipientId"] = intval($_6Ijo6["RecipientId"]);
   if($_Q6Q1C["id"] != $_6Ijo6["RecipientId"]) {
      $errors[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }

   # check SubscriptionStatus
   if($_Q6Q1C["SubscriptionStatus"] != 'OptOutConfirmationPending') {
      $errors[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _L006O(&$_6Ijo6, &$_QLl1Q, &$errors, &$_Ql1O8) {
   global $_Q61I1;

   $_Q60l1 = _L0O80($_6Ijo6, $_QLl1Q, $errors, $_Ql1O8);
   if(!$_Q60l1) return false;

   // 3. check recipient exists
   $_QJlJ0 = "SELECT IdentString, id, SubscriptionStatus FROM $_6Ijo6[MaillistTableName] WHERE IdentString="._OPQLR($_6Ijo6["key"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $errors[] = $_QLl1Q["EMailAddressNotInList"];
      $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
      return false;
   }
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   # is id = recipient id?
   $_6Ijo6["RecipientId"] = intval($_6Ijo6["RecipientId"]);
   if($_Q6Q1C["id"] != $_6Ijo6["RecipientId"]) {
      $errors[] = $_QLl1Q["EMailAddressNotInList"];
      $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
      return false;
   }

   # check SubscriptionStatus
   if($_Q6Q1C["SubscriptionStatus"] != 'OptOutConfirmationPending') {
      $errors[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["UnsubscribeTextOnConfirmationFailure"];
      return false;
   }


   return count($errors) == 0 ? true : false;
 }

 function _L00B8(&$_6Ijo6, $_I16jJ, $_QLl1Q, $_QlQC8, $_ItCCo, $_jf1J1, &$errors, &$_Ql1O8) {
    global $_Ql8C0, $_Qlt66, $_Q61I1, $INTERFACE_LANGUAGE;

    foreach($_I16jJ as $_I1i8O => $_I1L81) {
      if ( $_I1i8O == "u_EMail" && $_I1L81 == "visiblerequired" && ( (!isset($_6Ijo6[$_I1i8O]) || trim($_6Ijo6[$_I1i8O]) == "")  )  ) {
         $errors[] = $_I1i8O;
         if(count($_Ql1O8) == 0)
            $_Ql1O8[] = $_QLl1Q["RequiredFieldsErrorText"]."<br /><br />";
         $_Ql1O8[] = $_QLl1Q[$_I1i8O]."<br />";
         continue;
      }
    }

    if(count($errors) > 0) return false;

    if( isset($_6Ijo6["NewEMail"]) ) {
      $_6Ijo6["NewEMail"] = trim($_6Ijo6["NewEMail"]);
      if(!_OPAOJ($_6Ijo6["NewEMail"])) {
        $errors[] = "NewEMail";
        $_Ql1O8[] = $_QLl1Q["EMailFormatErrorText"]."<br />";
        return false;
      }

      // always exists?
      if(strtolower($_6Ijo6["NewEMail"]) != strtolower(trim($_6Ijo6["u_EMail"]))) {
        $_QJlJ0 = "SELECT id FROM $_QlQC8 WHERE u_EMail="._OPQLR($_6Ijo6["NewEMail"]);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_Q60l1) > 0) {
          $errors[] = "NewEMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressAlwaysInList"];
          mysql_free_result($_Q60l1);
          return false;
        }
        mysql_free_result($_Q60l1);
      }

      $_jfjC6 = trim($_6Ijo6["NewEMail"]);
      $_jfjC6 = substr($_jfjC6, strpos($_jfjC6, '@') + 1);

      // local blacklist check
      if(count($errors) == 0) {
        $_QJlJ0 = "SELECT id FROM $_ItCCo WHERE u_EMail="._OPQLR($_6Ijo6["NewEMail"]);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_Q60l1) > 0) {
          $errors[] = "NewEMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
          mysql_free_result($_Q60l1);
          return false;
        }
        mysql_free_result($_Q60l1);
      }

      // local domain blacklist check
      if(count($errors) == 0) {
        $_QJlJ0 = "SELECT id FROM $_jf1J1 WHERE Domainname="._OPQLR($_jfjC6);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_Q60l1) > 0) {
          $errors[] = "NewEMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
          mysql_free_result($_Q60l1);
          return false;
        }
        mysql_free_result($_Q60l1);
      }

      // global blacklist check
      if(count($errors) == 0) {
        $_QJlJ0 = "SELECT id FROM $_Ql8C0 WHERE u_EMail="._OPQLR($_6Ijo6["NewEMail"]);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_Q60l1) > 0) {
          $errors[] = "NewEMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
          mysql_free_result($_Q60l1);
          return false;
        }
        mysql_free_result($_Q60l1);
      }

      // global domain blacklist check
      if(count($errors) == 0) {
        $_QJlJ0 = "SELECT id FROM $_Qlt66 WHERE Domainname="._OPQLR($_jfjC6);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_Q60l1) > 0) {
          $errors[] = "NewEMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
          mysql_free_result($_Q60l1);
          return false;
        }
        mysql_free_result($_Q60l1);
      }

      // ECGlist check
      if(count($errors) == 0 && _LQDLR("ECGListCheck")) {
        if( _OC0DR( $_6Ijo6["NewEMail"] ) ){
          $errors[] = "NewEMail";
          $_Ql1O8[] = $_QLl1Q["EMailAddressBlacklisted"];
          return false;
        }
      }

    }

    if($_QLl1Q["GroupsAsMandatoryField"] && $_QLl1Q["GroupsOption"] == 2) {
      if( !isset($_6Ijo6["RecipientGroups"]) || !is_array($_6Ijo6["RecipientGroups"]) || count($_6Ijo6["RecipientGroups"]) == 0 ){
         $errors[] = "RecipientGroups";
         $_Ql1O8[] = $_QLl1Q["GroupsAsMandatoryFieldError"]."<br /><br />";
      }
    }

   return _OFEP8($_6Ijo6, $_I16jJ, $_QLl1Q, $_QlQC8, $errors, $_Ql1O8, true);
 }

 function _L00D8(&$_6Ijo6, &$_QLl1Q, &$errors, &$_Ql1O8) {
   global $_Q61I1;
   $_Q60l1 = _L0O80($_6Ijo6, $_QLl1Q, $errors, $_Ql1O8);
   if(!$_Q60l1) return false;

   // 3. check recipient exists
   $_QJlJ0 = "SELECT IdentString, id, SubscriptionStatus, u_EMail FROM $_6Ijo6[MaillistTableName] WHERE IdentString="._OPQLR($_6Ijo6["key"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      return false;
   }
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_6Ijo6["u_EMail"] = $_Q6Q1C["u_EMail"];

   # is id = recipient id?
   $_6Ijo6["RecipientId"] = intval($_6Ijo6["RecipientId"]);
   if($_Q6Q1C["id"] != $_6Ijo6["RecipientId"]) {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationFailure"];
      return false;
   }

   # check Edit Status
   $_QJlJ0 = "SELECT `ChangedDetails` FROM $_6Ijo6[EditTableName] WHERE `Member_id`=$_6Ijo6[RecipientId]";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0) {
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
     $_6Ijo6["ChangedDetails"] = $_Q6Q1C["ChangedDetails"];
   } else {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      return false;
   }

   return count($errors) == 0 ? true : false;
 }

 function _L01PA(&$_6Ijo6, &$_QLl1Q, &$errors, &$_Ql1O8) {
   global $_Q61I1;

   $_Q60l1 = _L0O80($_6Ijo6, $_QLl1Q, $errors, $_Ql1O8);
   if(!$_Q60l1) return false;

   // 3. check recipient exists
   $_QJlJ0 = "SELECT IdentString, id, SubscriptionStatus FROM $_6Ijo6[MaillistTableName] WHERE IdentString="._OPQLR($_6Ijo6["key"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $errors[] = $_QLl1Q["EMailAddressNotInList"];
      $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
      return false;
   }
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   # is id = recipient id?
   $_6Ijo6["RecipientId"] = intval($_6Ijo6["RecipientId"]);
   if($_Q6Q1C["id"] != $_6Ijo6["RecipientId"]) {
      $errors[] = $_QLl1Q["EMailAddressNotInList"];
      $_Ql1O8[] = $_QLl1Q["EMailAddressNotInList"];
      return false;
   }

   # check Edit Status
   $_QJlJ0 = "SELECT `Member_id` FROM `$_6Ijo6[EditTableName]` WHERE `Member_id`=".intval($_6Ijo6["RecipientId"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) == 0) {
      $errors[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      $_Ql1O8[] = $_QLl1Q["SubscribeTextOnConfirmationAgain"];
      return false;
   }
    else
     if($_Q60l1)
       mysql_free_result($_Q60l1);


   return count($errors) == 0 ? true : false;
 }


 function _L0QRE($_IIJi1, $_6IfCt = true) {
   global $_Q60QL, $_Q61I1;

   $_QJlJ0 = "SELECT id FROM $_Q60QL WHERE Name="._OPQLR($_IIJi1);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     return $_Q6Q1C[0];
   } else {
     if($_Q60l1)
       mysql_free_result($_Q60l1);

     if($_6IfCt && !IsUtf8String($_IIJi1)) {
       $_IIJi1 = utf8_encode($_IIJi1);
       return _L0QRE($_IIJi1, false);
     }
   }
   return $_IIJi1;
 }

 function _L0QBJ($MailingListId, $_IIJi1, $_6IfCt = true) {
   global $_Q60QL, $_Q61I1;

   if(!IsUtf8String($_IIJi1))
     $_IIJi1 = utf8_encode($_IIJi1);

   $_QJlJ0 = "SELECT FormsTableName FROM $_Q60QL WHERE id=".intval($MailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
     $_QJlJ0 = "SELECT id FROM $_Q6Q1C[FormsTableName] WHERE Name="._OPQLR($_IIJi1);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       mysql_free_result($_Q60l1);
       return $_Q6Q1C[0];
     }
   } else {

     if($_Q60l1)
       mysql_free_result($_Q60l1);

     if($_6IfCt && !IsUtf8String($_IIJi1)) {
       $_IIJi1 = utf8_encode($_IIJi1);
       return _L0QBJ($MailingListId, $_IIJi1, false);
     }

   }
   return $_IIJi1;
 }

 function _L0QEJ($ML, $_6IfiO, $_6IfCt = true) {
   global $_Q60QL, $_Q61I1;

   if( (string)(intval($_6IfiO) * 1) == (string)$_6IfiO ) // name?
      return $_6IfiO;

   $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=".intval($ML);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     $_Q6t6j = $_Q6Q1C[0];
   } else
     return false;

   $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR($_6IfiO);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     return $_Q6Q1C[0];
   } else {
     if($_Q60l1)
       mysql_free_result($_Q60l1);

     if($_6IfCt && !IsUtf8String($_6IfiO)) {
       $_6IfiO = utf8_encode($_6IfiO);
       return _L0QEJ($ML, $_6IfiO, false);
     }
   }
   return false;
 }

 function _L0O6B($key, $MailingListId) {
   global $_Q60QL, $_Q61I1;

   $_QJlJ0 = "SELECT MaillistTableName FROM $_Q60QL WHERE id=".intval($MailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
     $_QJlJ0 = "SELECT * FROM $_Q6Q1C[MaillistTableName] WHERE IdentString="._OPQLR($key);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
   }
   return false;
 }

 include_once("onlineupdate.inc.php");

 function _L0O80(&$_6Ijo6, &$_QLl1Q, &$errors, &$_Ql1O8) {
   global $commonmsgMailListNotFound, $commonmsgHTMLFormNotFound, $commonmsgParamKeyInvalid, $_jJO6j, $_Q60QL, $_Q880O, $_Q8f1L;
   global $_QolLi, $_Qofoi, $_ICljl, $_QLo0Q, $_Q88iO, $_Ql8C0;
   global $_Q61I1;

   global $INTERFACE_STYLE, $INTERFACE_LANGUAGE;

   $_jj0JO = "";

   if( !isset($_6Ijo6["key"]) ) {
     $_jCO1J = $commonmsgParamKeyInvalid;
     _ORECR($_jj0JO, $_jCO1J);
     return false;
   }

   $key = $_6Ijo6["key"];
   if(!_OA8LE($key, $_QLitI, $MailingListId, $FormId, !empty($_6Ijo6["rid"]) ? $_6Ijo6["rid"] : "" )) {
     $_jCO1J = $commonmsgParamKeyInvalid;
     _ORECR($_jj0JO, $_jCO1J);
     return false;
   }

   $_6Ijo6["MailingListId"] = intval($MailingListId);
   $_6Ijo6["FormId"] = intval($FormId);
   $_6Ijo6["RecipientId"] = intval($_QLitI);

   // 1. check maillist exists
   $_QJlJ0 = "SELECT users_id, MaillistTableName, StatisticsTableName, FormsTableName, EditTableName FROM $_Q60QL WHERE id=".intval($MailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
     $_jCO1J = $commonmsgMailListNotFound;
     _ORECR($_jj0JO, $_jCO1J);
     return false;
   }
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QlQC8 = $_Q6Q1C["MaillistTableName"];
   $_QLI8o = $_Q6Q1C["FormsTableName"];
   $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
   $_Qljli = $_Q6Q1C["EditTableName"];

   // tables
   $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$_Q6Q1C[users_id]";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_ji0L6 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   _OP0D0($_ji0L6);

   _LQLRQ($INTERFACE_LANGUAGE);

   // set the user paths for images, attachments....
   _OP0AF($_Q6Q1C["users_id"]);

   $_jolLf = "";
   $_jC0t8 = 0;
   $_6I8lO = 0;
   if(!_L0P8D($_jolLf, $_jC0t8, $_6I8lO, false))
       mysql_query("DELETE FROM $_Q88iO WHERE id=$_6I8lO", $_Q61I1);
   $_QJlJ0 = "SELECT Dashboard FROM $_Q88iO LIMIT 0,1";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   $_Q8otJ = _LQEQR($_Q8OiJ);
   if(count($_Q8otJ) == 0 ||
      !isset($_Q8otJ["DashboardDate"]) ||
      $_Q8otJ["DashboardDate"] == "" ||
      !isset($_Q8otJ["DashboardUser"]) ||
      $_Q8otJ["DashboardUser"] == "" ||
      !isset($_Q8otJ["DashboardTag"]) ||
      $_Q8otJ["DashboardTag"] == "" ||
      (strlen($_Q8otJ["DashboardTag"]) != $_jJO6j - 108)

     ) {
       print "@KEYINVALID";
       exit;
     }
   // ***

   $_6Ijo6["MaillistTableName"] = $_QlQC8;
   $_6Ijo6["FormsTableName"] = $_QLI8o;
   $_6Ijo6["StatisticsTableName"] = $_QlIf6;
   $_6Ijo6["EditTableName"] = $_Qljli;

   // 2. check form exists
   $_QJlJ0 = "SELECT $_QLI8o.*, $_QLI8o.id AS FormId, $_QLo0Q.*, $_Q880O.Theme FROM $_QLI8o LEFT JOIN $_QLo0Q ON $_QLo0Q.id=$_QLI8o.messages_id LEFT JOIN $_Q880O ON $_Q880O.id=$_QLI8o.ThemesId WHERE $_QLI8o.id=".intval($FormId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
     $_jCO1J = $commonmsgHTMLFormNotFound;
     _ORECR($_jj0JO, $_jCO1J);
   }

   $_QLl1Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QLl1Q["MailingListId"] = intval($MailingListId);

   # set theme and language for correct template
   $INTERFACE_STYLE = $_QLl1Q["Theme"];
   $INTERFACE_LANGUAGE = $_QLl1Q["Language"];

   _LQLRQ($INTERFACE_LANGUAGE);

   return count($errors) == 0 ? true : false;
 }


?>
