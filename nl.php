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
  include_once("templates.inc.php");
  include_once("onlineupdate.inc.php");
  include_once("newslettersubunsubcheck.inc.php");
  include_once("newslettersubunsub_ops.inc.php");
  include_once("replacements.inc.php");
  include_once("savedoptions.inc.php");
  @include_once("php_register_globals_off.inc.php");

  # is nl.php
  define('UserNewsletterPHP', 1);

  $errors = array();
  $_Ql1O8 = array();

  $_jj0JO = $commonmsgAnErrorOccured;

  if(isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == "HEAD" || $_SERVER['REQUEST_METHOD'] == "OPTIONS")){
        $_jCO1J = $commonmsgNoParameters;
        _ORECR($_jj0JO, $_jCO1J);
  }

  if(
      count($_GET) == 0 && count($_POST) == 0
    ){
        $_jCO1J = $commonmsgNoGETPOSTParameters;
        _ORECR($_jj0JO, $_jCO1J);
  }

  if(
      !isset($_GET["Action"]) && !isset($_POST["Action"])
    ){
        $_jCO1J = $commonmsgNoParameters;
        _ORECR($_jj0JO, $_jCO1J);
  }

  $_Qi8If = array();
  $_Qi8If = array_merge($_Qi8If, $_POST, $_GET);
  $Action = !empty($_Qi8If["Action"]) ? $_Qi8If["Action"] : "";
  $key = !empty($_Qi8If["key"]) ? $_Qi8If["key"] : "";
  $rid = !empty($_Qi8If["rid"]) ? $_Qi8If["rid"] : "";

  # email address as parameter?
  if( isset($_Qi8If["u_EMail"]) && trim($_Qi8If["u_EMail"]) != "")
     $u_EMail = trim($_Qi8If["u_EMail"]);
     else
     if( isset($_Qi8If["EMail"]) && trim($_Qi8If["EMail"]) != "")
        $u_EMail = trim($_Qi8If["EMail"]);
  if(isset($u_EMail))
     $_Qi8If["u_EMail"] = $u_EMail;

  # change short to long format START
  if (isset($_Qi8If["ML"])) {
     $_Qi8If["MailingListId"] = $_Qi8If["ML"];
     unset($_Qi8If["ML"]);
  }
  if (isset($_Qi8If["RL"])) {
     $_Qi8If["MailingListId"] = $_Qi8If["RL"];
     unset($_Qi8If["RL"]);
  }
  if (isset($_Qi8If["RecipientsListId"])) {
     $_Qi8If["MailingListId"] = $_Qi8If["RecipientsListId"];
     unset($_Qi8If["RecipientsListId"]);
  }

  // key parameter?
  if( $key != "" ){
    $_QLitI = 0;
    $MailingListId = 0;
    $FormId = 0;
    if(_OA8LE($key, $_QLitI, $MailingListId, $FormId, $rid)) {
      unset($_QLitI);
      $_Qi8If["MailingListId"] = $MailingListId;
      $_Qi8If["FormId"] = $FormId;
      if( ($Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit") && (!isset($u_EMail) || $u_EMail == "") ) {
         $_Q6Q1C = _L0O6B($key, $MailingListId);
         if($_Q6Q1C) {
           $u_EMail = $_Q6Q1C["u_EMail"];
         } else {
           $u_EMail = "noEMail_RecipientNotFound@localhost.aax"; # recipient not found set a dummy address
         }
         $_Qi8If["u_EMail"] = $u_EMail;
      }
    }
  }

  // ever after $key check
  if (isset($_Qi8If["MailingListId"]) && !is_numeric($_Qi8If["MailingListId"]) ) {
    $_Qi8If["MailingListId"] = _L0QRE($_Qi8If["MailingListId"]);
  }

  if (isset($_Qi8If["F"])) {
     $_Qi8If["FormId"] = $_Qi8If["F"];
     unset($_Qi8If["F"]);
  }
  if (isset($_Qi8If["FormId"]) && !is_numeric($_Qi8If["FormId"]) ) {
    $_Qi8If["FormId"] = _L0QBJ($_Qi8If["MailingListId"], $_Qi8If["FormId"]);
  }


  if (isset($_Qi8If["RG"])) {
     $_Qi8If["RecipientGroups"] = $_Qi8If["RG"];
     unset($_Qi8If["RG"]);
  }

  if (isset($_Qi8If["Groups"])) {
     $_Qi8If["RecipientGroups"] = $_Qi8If["Groups"];
     unset($_Qi8If["Groups"]);
  }
  # change short to long format END

  # check recipient groups
  // &RecipientGroups=1
  // &RecipientGroups=1,2,3
  if( isset($_Qi8If["RecipientGroups"]) && !is_array($_Qi8If["RecipientGroups"]) ) {
     if (strpos($_Qi8If["RecipientGroups"], ",") === false) {
        // comma separated
        $_Qi8If["RecipientGroups"] = array( $_Qi8If["RecipientGroups"] );
     } else {
        $_QllO8 = explode(",", $_Qi8If["RecipientGroups"]);
        unset($_Qi8If["RecipientGroups"]);
        $_Qi8If["RecipientGroups"] = array();
        for($_Q6llo=0; $_Q6llo<count($_QllO8); $_Q6llo++) {
          $_Qi8If["RecipientGroups"][] = trim($_QllO8[$_Q6llo]);
        }
       }

     $_QllO8 = $_Qi8If["RecipientGroups"];
     unset($_Qi8If["RecipientGroups"]);
     $_Qi8If["RecipientGroups"] = array();
     // akey = group id
     foreach($_QllO8 as $_6jij6 => $_Q6ClO){
       if(!( (string)(intval($_Q6ClO) * 1) == $_Q6ClO )) {// name?
         $_Q6ClO = _L0QEJ($_Qi8If["MailingListId"], $_Q6ClO);
         if($_Q6ClO == false) {
           $_jCO1J = $commonmsgRGNotFound;
           _ORECR($_jj0JO, $_jCO1J);
           exit;
         }

       }
       $_Qi8If["RecipientGroups"][$_Q6ClO] = 1;
     }
  } else if(isset($_Qi8If["RecipientGroups"])){
     $_QllO8 = $_Qi8If["RecipientGroups"];
     unset($_Qi8If["RecipientGroups"]);
     $_Qi8If["RecipientGroups"] = array();
     // akey = group id
     foreach($_QllO8 as $_6jij6 => $_Q6ClO){
       if(!( (string)(intval($_Q6ClO) * 1) == $_Q6ClO )) {// name?
         $_Q6ClO = _L0QEJ($_Qi8If["MailingListId"], $_Q6ClO);
         if($_Q6ClO == false) {
           $_jCO1J = $commonmsgRGNotFound;
           _ORECR($_jj0JO, $_jCO1J);
           exit;
         }

       }
       $_Qi8If["RecipientGroups"][$_Q6ClO] = 1;
     }
  }
  # check recipient groups END

  # form encoding
  if(isset($_Qi8If["FormEncoding"]) && strtolower( $_Qi8If["FormEncoding"] ) != "utf-8") {
    foreach ($_Qi8If as $_6jij6 => $_Q6ClO) {
      if(!is_array($_Q6ClO) && !(strpos($_6jij6, "u_") === false) ) {
         $_QJCJi = unhtmlentities($_Q6ClO, $_Qi8If["FormEncoding"]);
         if(!IsUTF8string($_QJCJi)) {
           $_QJCJi = ConvertString($_Qi8If["FormEncoding"], "UTF-8", $_QJCJi, false);
         }
         if($_Q6ClO != $_QJCJi) {
            $_Qi8If[$_6jij6] = $_QJCJi;
         }
      }
    }
  }

  #######

  // edit fix form without new email address
  if($Action == "edit"){
     if( empty($_Qi8If["NewEMail"]) && empty($_Qi8If["Newu_EMail"]) && !empty($_Qi8If["u_EMail"]) )
       $_Qi8If["NewEMail"] = $_Qi8If["u_EMail"];
  }

  if( ($Action == "subscribeconfirm" || $Action == "unsubscribeconfirm" || $Action == "editconfirm" || $Action == "subscribereject" || $Action == "unsubscribereject" || $Action == "editreject") && $_Qi8If["key"] == "" ){
        $_jCO1J = $commonmsgParamKeyNotFound;
        _ORECR($_jj0JO, $_jCO1J);
  }

  if( $Action == "unsubscribe" && !isset($u_EMail) ){
        $u_EMail = "";
        $_Qi8If["u_EMail"] = $u_EMail;
  }

  # get parameters for subscribe, unsubscribe and edit
  if( $Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit") {

    if (!isset($_Qi8If["MailingListId"]) ||!isset($_Qi8If["FormId"])  ) {
        $_jCO1J = $commonmsgNoParameters;
        _ORECR($_jj0JO, $_jCO1J);
    }

   $_Qi8If["MailingListId"] = intval($_Qi8If["MailingListId"]);
   $_Qi8If["FormId"] = intval($_Qi8If["FormId"]);
   $MailingListId = $_Qi8If["MailingListId"];
   $FormId = $_Qi8If["FormId"];

   $_QJlJ0 = "SELECT `users_id`, `SubscriptionUnsubscription`, `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `FormsTableName`, `GroupsTableName`, `EditTableName`, `ReasonsForUnsubscripeTableName`, `SendEMailToAdminOnOptIn`, `SendEMailToAdminOnOptOut`, `SendEMailToEMailAddressOnOptIn`, `SendEMailToEMailAddressOnOptOut`, `ExternalSubscriptionScript`, `ExternalUnsubscriptionScript`, `ExternalEditScript` FROM `$_Q60QL` WHERE `id`=$MailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
     $_jCO1J = $commonmsgMailListNotFound;
     _ORECR($_jj0JO, $_jCO1J);
   }

   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QLI8o = $_Q6Q1C["FormsTableName"];
   $_Q6t6j = $_Q6Q1C["GroupsTableName"];
   $_QlQC8 = $_Q6Q1C["MaillistTableName"];
   $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
   $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
   $_jf1J1 = $_Q6Q1C["LocalDomainBlocklistTableName"];
   $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
   $_Qljli = $_Q6Q1C["EditTableName"];
   $_I8Jtl = $_Q6Q1C["ReasonsForUnsubscripeTableName"];
   $_jCitf = $_Q6Q1C["SendEMailToAdminOnOptIn"];
   $_jCiOC = $_Q6Q1C["SendEMailToAdminOnOptOut"];
   $_jCLI6 = $_Q6Q1C["SendEMailToEMailAddressOnOptIn"];
   $_jCL8t = $_Q6Q1C["SendEMailToEMailAddressOnOptOut"];
   $_jClQj = $_Q6Q1C["ExternalSubscriptionScript"];
   $_jCl6l = $_Q6Q1C["ExternalUnsubscriptionScript"];
   $_ji00l = $_Q6Q1C["ExternalEditScript"];
   $_ji0i1 = $_Q6Q1C["SubscriptionUnsubscription"];

   // tables
   $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$_Q6Q1C[users_id]";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_ji0L6 = mysql_fetch_assoc($_Q60l1);
   if(!$_ji0L6["IsActive"]) {
     _ORECR($_jj0JO, $commonmsgUserDisabled);
   }
   mysql_free_result($_Q60l1);

   _OP0D0($_ji0L6);
   _OP10J($_ji0L6["Language"]);
   _LQLRQ($_ji0L6["Language"]);
   _LQF1R();
   // Check Request Limit only for subscribe, unsubscribe and edit
   if( ($Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit") && _OC6FL($_ji0L6)){
     $_jCO1J = sprintf($commonmsgSubUnsubScriptRequestOverLimit, getOwnIP(), getOwnIP());
     _ORECR($_jj0JO, $_jCO1J);
     exit;
   }
   if(!empty($Action) && $_ji0i1 != "Allowed" && strpos($Action, "confirm") === false && strpos($Action, "reject") === false){
     if($_ji0i1 == "Denied"){
       $_jCO1J = $commonmsgSubUnsubNotAllowed;
       _ORECR($_jj0JO, $_jCO1J);
       exit;
     }
     if( ($Action == "subscribe" || $Action == "edit") && $_ji0i1 != "SubscribeOnly"){
       $_jCO1J = $commonmsgSubscriptionsNotAllowed;
       _ORECR($_jj0JO, $_jCO1J);
       exit;
     }
     if( $Action == "unsubscribe" && $_ji0i1 != "UnsubscribeOnly"){
       $_jCO1J = $commonmsgUnsubscriptionsNotAllowed;
       _ORECR($_jj0JO, $_jCO1J);
       exit;
     }
   }
   // ***

   $_QJlJ0 = "SELECT $_QLI8o.*, $_QLo0Q.*, $_Q880O.Theme FROM $_QLI8o LEFT JOIN $_QLo0Q ON $_QLo0Q.id=$_QLI8o.messages_id LEFT JOIN $_Q880O ON $_Q880O.id=$_QLI8o.ThemesId WHERE $_QLI8o.id=$FormId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
     $_jCO1J = $commonmsgHTMLFormNotFound;
     _ORECR($_jj0JO, $_jCO1J);
   }

   $_j6ioL = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   // we need this for confirmation string
   $_j6ioL["MailingListId"] = $MailingListId;
   $_j6ioL["FormId"] = $FormId;
   // External Scripts
   $_j6ioL["ExternalSubscriptionScript"] = $_jClQj;
   $_j6ioL["ExternalUnsubscriptionScript"] = $_jCl6l;
   $_j6ioL["ExternalEditScript"] = $_ji00l;
   $_j6ioL["ReasonsForUnsubscripeTableName"] = $_I8Jtl;

   # set theme and language for correct template
   $INTERFACE_STYLE = $_j6ioL["Theme"];
   $INTERFACE_LANGUAGE = $_j6ioL["Language"];

   _LQLRQ($INTERFACE_LANGUAGE);

   // set the user paths for images, attachments....
   _OP0AF($_Q6Q1C["users_id"]);

   // ********* fields
   if($_j6ioL["fields"] != "") {
       $_I16jJ = @unserialize($_j6ioL["fields"]);
       if($_I16jJ === false) {
         $_I16jJ = array();
         $_I16jJ["u_EMail"] = "visiblerequired";
       }
      }
      else
      $_I16jJ["u_EMail"] = "visiblerequired";


   // ************ captcha
   if(!defined("SWM")) {
     if (isset($_Qi8If["IsHTMLForm"]) && $_Qi8If["IsHTMLForm"] == 0 ) {
       if($Action != "subscribe") {
         $_j6ioL["UseCaptcha"] = false;
         $_j6ioL["UseReCaptcha"] = false;
       }
     }
   } else {
     if(defined("IsNLUPHP")) {
        $_j6ioL["UseCaptcha"] = false;
        $_j6ioL["UseReCaptcha"] = false;
     }
   }

   if($_j6ioL["UseCaptcha"]) {
      /* Session starten */
      @session_cache_limiter('public');
      @session_set_cookie_params(600, "/", "");
      @ini_set("session.cookie_path", "/");
      if(!ini_get("session.auto_start")) {
        @session_start();
      }
      /* Klassen einbinden */
      require_once './captcha/require/config.php';
      require_once './captcha/require/crypt.class.php';
      /* Crypt-Klasse initialisieren */
      $GLOBALS['crypt_class'] = new crypt_class();
   }
   // ************ captcha

   // ************ reCAPTCHA
   if($_j6ioL["UseReCaptcha"]) {
      /* Klassen einbinden */
      //recaptchav1 require_once('./captcha/recaptcha/recaptchalib.php');
   }
   // ************ reCAPTCHA


  } // if( $Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit")


  // ******** check the actions START

  if($Action == "subscribe") {
    if(_OFEOP($_Qi8If, $_I16jJ, $_j6ioL, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $_jf1J1, $errors, $_Ql1O8)) {
       $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$MailingListId";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(!$_Q60l1) {
        $_jCO1J = $commonmsgNoParameters;
        _ORECR("Invalid mailing list", $_jCO1J);
       }
       $_Ql00j = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       // duplicate subscribtion
       if(isset($_Qi8If["SubscriptionStatus"])) {
          $_QLitI = $_Qi8If["RecipientId"];
          if(_L0RLJ("subscribeconfirm", $_QLitI, $_Ql00j, $_j6ioL, $errors, $_Ql1O8)) {
             _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "SubscribeAcceptedPage", "SubscribeTextConfirmationRequired", $_j6ioL, $_Ql1O8);
             exit;
          } else {
             _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "SubscribeErrorPage", "EMailAddressAlwaysInList", $_j6ioL, $_Ql1O8);
             exit;
          }
       }

       //save and send confirmation mail when required
       $_QLitI = 0;
       if(_L0OEO($_QLitI, $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
          _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
          exit;
       } else {
          if ($_Ql00j["SubscriptionType"] == 'SingleOptIn' || isset($_Qi8If["DuplicateEntry"])) {

                if(!isset($_Qi8If["DuplicateEntry"]) && $_j6ioL["SendOptInConfirmedMail"] ) {
                   _L0RLJ("subscribeconfirmed", $_QLitI, $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
                }

                if(($_jCitf || $_jCLI6) && !isset($_Qi8If["DuplicateEntry"])) {
                   _L0R6A($_QLitI, $_Ql00j, array(), "subscribe", $_j6ioL);
                }

                if(!isset($_Qi8If["DuplicateEntry"])) {
                   _L08DC($_QLitI, $_Ql00j, array(), "subscribe", $_j6ioL);
                }

                _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "SubscribeConfirmationPage", "SubscribeOKText", $_j6ioL, $_Ql1O8);
              }
              else
              _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "SubscribeAcceptedPage", "SubscribeTextConfirmationRequired", $_j6ioL, $_Ql1O8);
          exit;
       }
    } else {
        _L08LL(0, $_QlQC8, "SubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
    }
  }

  if($Action == "subscribeconfirm" ) {
    if (!_OFEAQ($_Qi8If, $_j6ioL, $errors, $_Ql1O8)) {
      _L08LL($_Qi8If["RecipientId"], $_Qi8If["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
      exit;
    }
    $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$_Qi8If[MailingListId]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1) {
     $_jCO1J = $commonmsgNoParameters;
     _ORECR("Invalid mailing list", $_jCO1J);
    }
    $_Ql00j = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    // External Scripts
    $_j6ioL["ExternalSubscriptionScript"] = $_Ql00j["ExternalSubscriptionScript"];
    $_j6ioL["ExternalUnsubscriptionScript"] = $_Ql00j["ExternalUnsubscriptionScript"];
    $_j6ioL["ExternalEditScript"] = $_Ql00j["ExternalEditScript"];

    if(_L0OEO($_Qi8If["RecipientId"], $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
       _L08LL($_Qi8If["RecipientId"], $_Ql00j["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
       exit;
    } else {

       if( $_j6ioL["SendOptInConfirmedMail"] ) {
          _L0RLJ("subscribeconfirmed", $_Qi8If["RecipientId"], $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
       }

       if($_Ql00j["SendEMailToAdminOnOptIn"] || $_Ql00j["SendEMailToEMailAddressOnOptIn"]) {
          _L0R6A($_Qi8If["RecipientId"], $_Ql00j, array(), "subscribe", $_j6ioL);
       }

       _L08DC($_Qi8If["RecipientId"], $_Ql00j, array(), "subscribe", $_j6ioL);

       _L08LL($_Qi8If["RecipientId"], $_Ql00j["MaillistTableName"], "SubscribeConfirmationPage", "SubscribeTextOnConfirmationSucc", $_j6ioL, $_Ql1O8);
       exit;
    }
  }

  if($Action == "subscribereject" ) {
    if (!_OFFQO($_Qi8If, $_j6ioL, $errors, $_Ql1O8)) {
      _L08LL($_Qi8If["RecipientId"], $_Qi8If["MaillistTableName"], "UnsubscribeErrorPage", "UnsubscribeTextOnConfirmationFailure", $_j6ioL, $_Ql1O8);
      exit;
    }
    $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$_Qi8If[MailingListId]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1) {
     $_jCO1J = $commonmsgNoParameters;
     _ORECR("Invalid mailing list", $_jCO1J);
    }
    $_Ql00j = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT * FROM $_Qi8If[MaillistTableName] WHERE id=".$_Qi8If["RecipientId"];
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_jiJC0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    if(_L0JRQ($_Qi8If["RecipientId"], $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
       _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
       exit;
    } else {
       _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeConfirmationPage", "UnsubscribeOKText", $_j6ioL, $_Ql1O8);
       exit;
    }
  }

  if($Action == "unsubscribe") {
    if(_OFEP8($_Qi8If, $_I16jJ, $_j6ioL, $_QlQC8, $errors, $_Ql1O8)) {
       $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$MailingListId";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(!$_Q60l1) {
        $_jCO1J = $commonmsgNoParameters;
        _ORECR("Invalid mailing list", $_jCO1J);
       }
       $_Ql00j = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       // get recipient data a last time for personalization
       $_QJlJ0 = "SELECT * FROM $_Qi8If[MaillistTableName] WHERE id=".intval($_Qi8If["RecipientId"]);
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_jiJC0 = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       if( defined('IsNLUPHP') && $_j6ioL["UnsubscribeBridgePage"] > 0 && empty($_Qi8If["unsubscribeconfirmation"]) ) {
         _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeBridgePage", "", $_j6ioL, $_Ql1O8);
         exit;
       }

       //remove or send confirmation mail when required
       $_QLitI = intval($_Qi8If["RecipientId"]);
       if(_L0LAF($_QLitI, $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
          _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
          exit;
       } else {
          if ($_Ql00j["UnsubscriptionType"] == 'SingleOptOut') {

               if( $_j6ioL["SendOptOutConfirmedMail"] ) {
                  $rid = "";
                  if(isset($_Qi8If["rid"]))
                    $rid = $_Qi8If["rid"];
                  _L0RLJ("unsubscribeconfirmed", $_jiJC0, $_Ql00j, $_j6ioL, $errors, $_Ql1O8, $rid);
               }

               if($_jCiOC || $_jCL8t) {
                  _L0R6A($_QLitI, $_Ql00j, $_jiJC0, "unsubscribe", $_j6ioL);
               }

               _L08DC($_QLitI, $_Ql00j, $_jiJC0, "unsubscribe", $_j6ioL);

               _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeConfirmationPage", "UnsubscribeOKText", $_j6ioL, $_Ql1O8);
              }
              else
              _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeAcceptedPage", "UnsubscribeTextConfirmationRequired", $_j6ioL, $_Ql1O8);
          exit;
       }

    } else {
       _L08LL(0, $_QlQC8, "UnsubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
    }
  }

  if($Action == "unsubscribeconfirm" ) {
    if (!_OFFPJ($_Qi8If, $_j6ioL, $errors, $_Ql1O8)) {
      _L08LL($_Qi8If["RecipientId"], $_Qi8If["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
    } else {

      $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=".$_Qi8If["MailingListId"];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1) {
       $_jCO1J = $commonmsgNoParameters;
       _ORECR("Invalid mailing list", $_jCO1J);
      }
      $_Ql00j = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      // External Scripts
      $_j6ioL["ExternalSubscriptionScript"] = $_Ql00j["ExternalSubscriptionScript"];
      $_j6ioL["ExternalUnsubscriptionScript"] = $_Ql00j["ExternalUnsubscriptionScript"];
      $_j6ioL["ExternalEditScript"] = $_Ql00j["ExternalEditScript"];

      // get recipient data a last time for personalization
      $_QJlJ0 = "SELECT * FROM $_Qi8If[MaillistTableName] WHERE id=".intval($_Qi8If["RecipientId"]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_jiJC0 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

       $_QLitI = intval($_Qi8If["RecipientId"]);
       if(_L0LAF($_QLitI, $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
          _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
          exit;
       } else {

          if( $_j6ioL["SendOptOutConfirmedMail"] ) {
             _L0RLJ("unsubscribeconfirmed", $_jiJC0, $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
          }

          if($_Ql00j["SendEMailToAdminOnOptOut"] || $_Ql00j["SendEMailToEMailAddressOnOptOut"]) {
             _L0R6A($_QLitI, $_Ql00j, $_jiJC0, "unsubscribe", $_j6ioL);
          }
          _L08DC($_QLitI, $_Ql00j, $_jiJC0, "unsubscribe", $_j6ioL);

          _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeConfirmationPage", "UnsubscribeOKText", $_j6ioL, $_Ql1O8);
          exit;
       }
    }
  }

  if($Action == "unsubscribereject" ) {
    if (!_L006O($_Qi8If, $_j6ioL, $errors, $_Ql1O8)) {
      _L08LL($_Qi8If["RecipientId"], $_Qi8If["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
      exit;
    }
    $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$_Qi8If[MailingListId]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1) {
     $_jCO1J = $commonmsgNoParameters;
     _ORECR("Invalid mailing list", $_jCO1J);
    }
    $_Ql00j = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    if(_L06JL($_Qi8If["RecipientId"], $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
       _L08LL($_Qi8If["RecipientId"], $_Ql00j["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
       exit;
    } else {
       _L08LL($_Qi8If["RecipientId"], $_Ql00j["MaillistTableName"], "SubscribeConfirmationPage", "SubscribeOKText", $_j6ioL, $_Ql1O8);
       exit;
    }
  }

  if($Action == "edit") {

    if( !isset($_Qi8If["NewEMail"]) || $_Qi8If["NewEMail"] == ""  )
        if(isset($_Qi8If["EMail"]))
          $_Qi8If["NewEMail"] = $_Qi8If["EMail"];

    // we check it is in list
    if(_L00B8($_Qi8If, $_I16jJ, $_j6ioL, $_QlQC8, $_ItCCo, $_jf1J1, $errors, $_Ql1O8)) {
       $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$MailingListId";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(!$_Q60l1) {
        $_jCO1J = $commonmsgNoParameters;
        _ORECR("Invalid mailing list", $_jCO1J);
       }
       $_Ql00j = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       if(isset($_Qi8If["DuplicateEntry"]))
          unset($_Qi8If["DuplicateEntry"]);

       //save changes
       $_QLitI = $_Qi8If["RecipientId"];
       if(isset($_Qi8If["u_Birthday"])) {
          $_Qi8If["u_Birthday"] = _OAO0D($_Qi8If["u_Birthday"], $INTERFACE_LANGUAGE);
       }
       if(_L0OEO($_QLitI, $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
          _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "EditErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
          exit;
       } else {
           if($_QLitI != "editconfirm") {
              if($_Ql00j["SendEMailToAdminOnOptIn"] || $_Ql00j["SendEMailToEMailAddressOnOptIn"]) {
                _L0R6A($_QLitI, $_Ql00j, array(), "edit", $_j6ioL, $_Qi8If["u_EMail"]);
              }

              _L08DC($_QLitI, $_Ql00j, array(), "edit", $_j6ioL, $_Qi8If["u_EMail"]);

              _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "EditConfirmationPage", "", $_j6ioL, $_Ql1O8);
              exit;
          } else {
            $_QLitI = $_Qi8If["RecipientId"];
            _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "EditAcceptedPage", "", $_j6ioL, $_Ql1O8);
            exit;
          }
       }
    } else {
        _L08LL(0, $_QlQC8, "EditErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
    }
  }

  if($Action == "editconfirm" ) {
    if ( !_L00D8($_Qi8If, $_j6ioL, $errors, $_Ql1O8) || empty($_Qi8If["ChangedDetails"]) ) {
      _L08LL($_Qi8If["RecipientId"], $_Qi8If["MaillistTableName"], "EditErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
      exit;
    }
    $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$_Qi8If[MailingListId]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1) {
     $_jCO1J = $commonmsgNoParameters;
     _ORECR("Invalid mailing list", $_jCO1J);
    }
    $_Ql00j = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    // External Scripts
    $_j6ioL["ExternalSubscriptionScript"] = $_Ql00j["ExternalSubscriptionScript"];
    $_j6ioL["ExternalUnsubscriptionScript"] = $_Ql00j["ExternalUnsubscriptionScript"];
    $_j6ioL["ExternalEditScript"] = $_Ql00j["ExternalEditScript"];

    $_Qi8If["RecipientId"] = intval($_Qi8If["RecipientId"]);
    $_QLitI = $_Qi8If["RecipientId"];

    if(_L0OEO($_Qi8If["RecipientId"], $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
       _L08LL($_Qi8If["RecipientId"], $_Ql00j["MaillistTableName"], "EditErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
       exit;
    } else {

       if( $_j6ioL["SendEditConfirmedMail"] ) {
          _L0RLJ("editconfirmed", $_Qi8If["RecipientId"], $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
       }

       if($_Ql00j["SendEMailToAdminOnOptIn"] || $_Ql00j["SendEMailToEMailAddressOnOptIn"]) {
         _L0R6A($_QLitI, $_Ql00j, array(), "edit", $_j6ioL, $_Qi8If["u_EMail"]);
       }

       _L08DC($_QLitI, $_Ql00j, array(), "edit", $_j6ioL, $_Qi8If["u_EMail"]);

       _L08LL($_QLitI, $_Ql00j["MaillistTableName"], "EditConfirmationPage", "", $_j6ioL, $_Ql1O8);

       exit;
    }
  }

  if($Action == "editreject" ) {
    if (!_L01PA($_Qi8If, $_j6ioL, $errors, $_Ql1O8)) {
      _L08LL($_Qi8If["RecipientId"], $_Qi8If["MaillistTableName"], "EditErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
      exit;
    }
    $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$_Qi8If[MailingListId]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1) {
     $_jCO1J = $commonmsgNoParameters;
     _ORECR("Invalid mailing list", $_jCO1J);
    }
    $_Ql00j = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    // get recipient data for personalization
    $_QJlJ0 = "SELECT * FROM $_Qi8If[MaillistTableName] WHERE id=".intval($_Qi8If["RecipientId"]);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_jiJC0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    if(_L0RLO($_Qi8If["RecipientId"], $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
       _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "EditErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
       exit;
    } else {
       _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "EditRejectPage", "", $_j6ioL, $_Ql1O8);
       exit;
    }
  }


  // ******** check the actions END

  function _ORECR($_jj0JO, $_jCO1J) {
    global $AppName, $_Q6QQL, $_j6ioL;

    SetHTMLHeaders($_Q6QQL);

    if(!isset($_j6ioL) || empty($_j6ioL["UserDefinedFormsFolder"]))
      $_Q6ICj = join("", file(_O68QF()."default_errorpage.htm"));
      else
      $_Q6ICj = join("", file(_OBLDR(InstallPath.$_j6ioL["UserDefinedFormsFolder"])."default_errorpage.htm"));
    $_Q6ICj = _OPLPC("<title>", "<title>".$AppName." - ".$_jj0JO, $_Q6ICj);

    _LJ81E($_Q6ICj);

    $_Q6ICj = _OPLPC("<!--ERRORTEXT//-->", $_jj0JO, $_Q6ICj);
    $_Q6ICj = _OPLPC("<!--ERRORHTMLTEXT//-->", $_jCO1J, $_Q6ICj);
    print $_Q6ICj;
    exit;
  }

?>
