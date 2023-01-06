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
  $_I816i = array();

  $_J0COJ = $commonmsgAnErrorOccured;

  if(isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == "HEAD" || $_SERVER['REQUEST_METHOD'] == "OPTIONS")){
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D($_J0COJ, $_JCIO0);
  }

  if(
      count($_GET) == 0 && count($_POST) == 0
    ){
        $_JCIO0 = $commonmsgNoGETPOSTParameters;
        _LJD1D($_J0COJ, $_JCIO0);
  }

  if(
      !isset($_GET["Action"]) && !isset($_POST["Action"])
    ){
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D($_J0COJ, $_JCIO0);
  }

  $_I6tLJ = array();
  $_I6tLJ = array_merge($_I6tLJ, $_POST, $_GET);
  $Action = !empty($_I6tLJ["Action"]) ? $_I6tLJ["Action"] : "";
  $key = !empty($_I6tLJ["key"]) ? $_I6tLJ["key"] : "";
  $rid = !empty($_I6tLJ["rid"]) ? $_I6tLJ["rid"] : "";

  # email address as parameter?
  if( isset($_I6tLJ["u_EMail"]) && trim($_I6tLJ["u_EMail"]) != "")
     $u_EMail = trim($_I6tLJ["u_EMail"]);
     else
     if( isset($_I6tLJ["EMail"]) && trim($_I6tLJ["EMail"]) != "")
        $u_EMail = trim($_I6tLJ["EMail"]);
  if(isset($u_EMail))
     $_I6tLJ["u_EMail"] = $u_EMail;

  # change short to long format START
  if (isset($_I6tLJ["ML"])) {
     $_I6tLJ["MailingListId"] = $_I6tLJ["ML"];
     unset($_I6tLJ["ML"]);
  }
  if (isset($_I6tLJ["RL"])) {
     $_I6tLJ["MailingListId"] = $_I6tLJ["RL"];
     unset($_I6tLJ["RL"]);
  }
  if (isset($_I6tLJ["RecipientsListId"])) {
     $_I6tLJ["MailingListId"] = $_I6tLJ["RecipientsListId"];
     unset($_I6tLJ["RecipientsListId"]);
  }

  // key parameter?
  if( $key != "" ){
    $_IfLJj = 0;
    $MailingListId = 0;
    $FormId = 0;
    if(_LPQEP($key, $_IfLJj, $MailingListId, $FormId, $rid)) {
      unset($_IfLJj);

      $_I6tLJ["MailingListId"] = $MailingListId;
      $_I6tLJ["FormId"] = $FormId;
      if( ($Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit") && (!isset($u_EMail) || $u_EMail == "") ) {
         $_QLO0f = _J01BO($key, $MailingListId);
         if($_QLO0f) {
           $u_EMail = $_QLO0f["u_EMail"];
         } else {
           $u_EMail = "noEMail_RecipientNotFound@localhost.aax"; # recipient not found set a dummy address
         }
         $_I6tLJ["u_EMail"] = $u_EMail;
      }
    } else
     $key = "";
  }

  // ever after $key check
  if (isset($_I6tLJ["MailingListId"]) && !is_numeric($_I6tLJ["MailingListId"]) ) {
    $_I6tLJ["MailingListId"] = _J00LP($_I6tLJ["MailingListId"]);
  }

  if (isset($_I6tLJ["F"])) {
     $_I6tLJ["FormId"] = $_I6tLJ["F"];
     unset($_I6tLJ["F"]);
  }
  if (isset($_I6tLJ["FormId"]) && !is_numeric($_I6tLJ["FormId"]) ) {
    $_I6tLJ["FormId"] = _J011E($_I6tLJ["MailingListId"], $_I6tLJ["FormId"]);
  }


  if (isset($_I6tLJ["RG"])) {
     $_I6tLJ["RecipientGroups"] = $_I6tLJ["RG"];
     unset($_I6tLJ["RG"]);
  }

  if (isset($_I6tLJ["Groups"])) {
     $_I6tLJ["RecipientGroups"] = $_I6tLJ["Groups"];
     unset($_I6tLJ["Groups"]);
  }
  # change short to long format END

  # check recipient groups
  // &RecipientGroups=1
  // &RecipientGroups=1,2,3
  if( isset($_I6tLJ["RecipientGroups"]) && !is_array($_I6tLJ["RecipientGroups"]) ) {
     if (strpos($_I6tLJ["RecipientGroups"], ",") === false) {
        // comma separated
        $_I6tLJ["RecipientGroups"] = array( $_I6tLJ["RecipientGroups"] );
     } else {
        $_I016j = explode(",", $_I6tLJ["RecipientGroups"]);
        unset($_I6tLJ["RecipientGroups"]);
        $_I6tLJ["RecipientGroups"] = array();
        for($_Qli6J=0; $_Qli6J<count($_I016j); $_Qli6J++) {
          $_I6tLJ["RecipientGroups"][] = trim($_I016j[$_Qli6J]);
        }
       }

     $_I016j = $_I6tLJ["RecipientGroups"];
     unset($_I6tLJ["RecipientGroups"]);
     $_I6tLJ["RecipientGroups"] = array();
     // akey = group id
     foreach($_I016j as $_foj86 => $_QltJO){
       if(!( (string)(intval($_QltJO) * 1) == $_QltJO )) {// name?
         $_QltJO = _J016F($_I6tLJ["MailingListId"], $_QltJO);
         if($_QltJO == false) {
           $_JCIO0 = $commonmsgRGNotFound;
           _LJD1D($_J0COJ, $_JCIO0);
           exit;
         }

       }
       $_I6tLJ["RecipientGroups"][$_QltJO] = 1;
     }
  } else if(isset($_I6tLJ["RecipientGroups"])){
     $_I016j = $_I6tLJ["RecipientGroups"];
     unset($_I6tLJ["RecipientGroups"]);
     $_I6tLJ["RecipientGroups"] = array();
     // akey = group id
     foreach($_I016j as $_foj86 => $_QltJO){
       if(!( (string)(intval($_QltJO) * 1) == $_QltJO )) {// name?
         $_QltJO = _J016F($_I6tLJ["MailingListId"], $_QltJO);
         if($_QltJO == false) {
           $_JCIO0 = $commonmsgRGNotFound;
           _LJD1D($_J0COJ, $_JCIO0);
           exit;
         }

       }
       $_I6tLJ["RecipientGroups"][$_QltJO] = 1;
     }
  }
  # check recipient groups END

  # form encoding
  if(isset($_I6tLJ["FormEncoding"]) && strtolower( $_I6tLJ["FormEncoding"] ) != "utf-8") {
    foreach ($_I6tLJ as $_foj86 => $_QltJO) {
      if(!is_array($_QltJO) && !(strpos($_foj86, "u_") === false) ) {
         $_QLJfI = unhtmlentities($_QltJO, $_I6tLJ["FormEncoding"]);
         if(!IsUTF8string($_QLJfI)) {
           $_QLJfI = ConvertString($_I6tLJ["FormEncoding"], "UTF-8", $_QLJfI, false);
         }
         if($_QltJO != $_QLJfI) {
            $_I6tLJ[$_foj86] = $_QLJfI;
         }
      }
    }
  }

  #######

  // edit fix form without new email address
  if($Action == "edit"){
     if( empty($_I6tLJ["NewEMail"]) && empty($_I6tLJ["Newu_EMail"]) && !empty($_I6tLJ["u_EMail"]) )
       $_I6tLJ["NewEMail"] = $_I6tLJ["u_EMail"];
  }

  if( ($Action == "subscribeconfirm" || $Action == "unsubscribeconfirm" || $Action == "editconfirm" || $Action == "subscribereject" || $Action == "unsubscribereject" || $Action == "editreject") && $_I6tLJ["key"] == "" ){
        $_JCIO0 = $commonmsgParamKeyNotFound;
        _LJD1D($_J0COJ, $_JCIO0);
  }

  if( $Action == "unsubscribe" && !isset($u_EMail) ){
        $u_EMail = "";
        $_I6tLJ["u_EMail"] = $u_EMail;
  }

  # get parameters for subscribe, unsubscribe and edit
  if( $Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit" || $Action == "unsubscribeconfirm") {

    if (!isset($_I6tLJ["MailingListId"]) ||!isset($_I6tLJ["FormId"])  ) {
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D($_J0COJ, $_JCIO0);
    }

   $_I6tLJ["MailingListId"] = intval($_I6tLJ["MailingListId"]);
   $_I6tLJ["FormId"] = intval($_I6tLJ["FormId"]);
   $MailingListId = $_I6tLJ["MailingListId"];
   $FormId = $_I6tLJ["FormId"];

   $_QLfol = "SELECT `users_id`, `SubscriptionUnsubscription`, `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `FormsTableName`, `GroupsTableName`, `EditTableName`, `ReasonsForUnsubscripeTableName`, `SendEMailToAdminOnOptIn`, `SendEMailToAdminOnOptOut`, `SendEMailToEMailAddressOnOptIn`, `SendEMailToEMailAddressOnOptOut`, `ExternalSubscriptionScript`, `ExternalUnsubscriptionScript`, `ExternalEditScript` FROM `$_QL88I` WHERE `id`=$MailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
     $_JCIO0 = $commonmsgMailListNotFound;
     _LJD1D($_J0COJ, $_JCIO0);
   }

   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   $_IfJoo = $_QLO0f["FormsTableName"];
   $_QljJi = $_QLO0f["GroupsTableName"];
   $_I8I6o = $_QLO0f["MaillistTableName"];
   $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
   $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
   $_Jj6f0 = $_QLO0f["LocalDomainBlocklistTableName"];
   $_I8jjj = $_QLO0f["StatisticsTableName"];
   $_I8Jti = $_QLO0f["EditTableName"];
   $_jQIIl = $_QLO0f["ReasonsForUnsubscripeTableName"];
   $_JC8t6 = $_QLO0f["SendEMailToAdminOnOptIn"];
   $_JCtfJ = $_QLO0f["SendEMailToAdminOnOptOut"];
   $_JCtOQ = $_QLO0f["SendEMailToEMailAddressOnOptIn"];
   $_JCtCJ = $_QLO0f["SendEMailToEMailAddressOnOptOut"];
   $_JCOJ6 = $_QLO0f["ExternalSubscriptionScript"];
   $_JCOit = $_QLO0f["ExternalUnsubscriptionScript"];
   $_JCojC = $_QLO0f["ExternalEditScript"];
   $_JCC1C = $_QLO0f["SubscriptionUnsubscription"];

   // tables
   $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_QLO0f[users_id]";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_JCC81 = mysql_fetch_assoc($_QL8i1);
   if(!$_JCC81["IsActive"]) {
     _LJD1D($_J0COJ, $commonmsgUserDisabled);
   }
   mysql_free_result($_QL8i1);

   _LR8AP($_JCC81);
   _LRPQ6($_JCC81["Language"]);
   _JQRLR($_JCC81["Language"]);
   _JOLFC();
   // Check Request Limit only for subscribe, unsubscribe and edit
   if( ($Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit") && _LAFQC($_JCC81)){
     $_JCIO0 = sprintf($commonmsgSubUnsubScriptRequestOverLimit, getOwnIP(), getOwnIP());
     _LJD1D($_J0COJ, $_JCIO0);
     exit;
   }
   if(!empty($Action) && $_JCC1C != "Allowed" && strpos($Action, "confirm") === false && strpos($Action, "reject") === false){
     if($_JCC1C == "Denied"){
       $_JCIO0 = $commonmsgSubUnsubNotAllowed;
       _LJD1D($_J0COJ, $_JCIO0);
       exit;
     }
     if( ($Action == "subscribe" || $Action == "edit") && $_JCC1C != "SubscribeOnly"){
       $_JCIO0 = $commonmsgSubscriptionsNotAllowed;
       _LJD1D($_J0COJ, $_JCIO0);
       exit;
     }
     if( $Action == "unsubscribe" && $_JCC1C != "UnsubscribeOnly"){
       $_JCIO0 = $commonmsgUnsubscriptionsNotAllowed;
       _LJD1D($_J0COJ, $_JCIO0);
       exit;
     }
   }
   // ***

   $_QLfol = "SELECT $_IfJoo.*, $_IfJoo.id AS FormId, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=$FormId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
     $_JCIO0 = $commonmsgHTMLFormNotFound;
     _LJD1D($_J0COJ, $_JCIO0);
   }

   $_Jj08l = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   unset($_Jj08l["id"]);

   // we need this for confirmation string
   $_Jj08l["MailingListId"] = $MailingListId;
   $_Jj08l["FormId"] = $FormId;
   $_Jj08l["SubscriptionUnsubscription"] = $_JCC1C;
   // External Scripts
   $_Jj08l["ExternalSubscriptionScript"] = $_JCOJ6;
   $_Jj08l["ExternalUnsubscriptionScript"] = $_JCOit;
   $_Jj08l["ExternalEditScript"] = $_JCojC;
   // Reasons for unsubscripe
   $_Jj08l["ReasonsForUnsubscripeTableName"] = $_jQIIl;

   $_Jj08l["Action"] = $Action;

   # set theme and language for correct template
   $INTERFACE_STYLE = $_Jj08l["Theme"];
   $INTERFACE_LANGUAGE = $_Jj08l["Language"];

   _JQRLR($INTERFACE_LANGUAGE);

   // set the user paths for images, attachments....
   _LRRFJ($_QLO0f["users_id"]);

   // ********* fields
   if($_Jj08l["fields"] != "") {
       $_IOJoI = @unserialize($_Jj08l["fields"]);
       if($_IOJoI === false) {
         $_IOJoI = array();
         $_IOJoI["u_EMail"] = "visiblerequired";
       }
      }
      else
      $_IOJoI["u_EMail"] = "visiblerequired";


   // ************ captcha
   if(!defined("SWM")) {
     if (isset($_I6tLJ["IsHTMLForm"]) && $_I6tLJ["IsHTMLForm"] == 0 ) {
       if($Action != "subscribe") {
         $_Jj08l["UseCaptcha"] = false;
         $_Jj08l["UseReCaptcha"] = false;
       }
     }
   } else {
     if(defined("IsNLUPHP")) {
        $_Jj08l["UseCaptcha"] = false;
        $_Jj08l["UseReCaptcha"] = false;
     }
   }

   if($_Jj08l["UseCaptcha"]) {
      /* Session starten */
      @session_cache_limiter('public');
      @session_set_cookie_params(600, "/", "");
      @ini_set("session.cookie_path", "/");
      if(!ini_get("session.auto_start")) {
        @session_start();
      }
      header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
      /* Klassen einbinden */
      require_once './captcha/require/config.php';
      require_once './captcha/require/crypt.class.php';
      /* Crypt-Klasse initialisieren */
      $GLOBALS['crypt_class'] = new crypt_class();
   }
   // ************ captcha

   // ************ reCAPTCHA
   if($_Jj08l["UseReCaptcha"]) {
      /* Klassen einbinden */
      //recaptchav1 require_once('./captcha/recaptcha/recaptchalib.php');
   }
   // ************ reCAPTCHA


  } // if( $Action == "subscribe" || $Action == "unsubscribe" || $Action == "edit")


  // ******** check the actions START
  if($Action == "subscribe") {
    if(_LFD8Q($_I6tLJ, $_IOJoI, $_Jj08l, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $_Jj6f0, $errors, $_I816i)) {
       $_QLfol = "SELECT * FROM $_QL88I WHERE id=$MailingListId";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(!$_QL8i1) {
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D("Invalid mailing list", $_JCIO0);
       }
       $_I80Jo = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);

       // duplicate subscribtion
       if(isset($_I6tLJ["SubscriptionStatus"])) {
          $_IfLJj = $_I6tLJ["RecipientId"];
          if(_J0LAA("subscribeconfirm", $_IfLJj, $_I80Jo, $_Jj08l, $errors, $_I816i)) {
             _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "SubscribeAcceptedPage", "SubscribeTextConfirmationRequired", $_Jj08l, $_I816i);
             exit;
          } else {
             _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "SubscribeErrorPage", "EMailAddressAlwaysInList", $_Jj08l, $_I816i);
             exit;
          }
       }

       //save and send confirmation mail when required
       $_IfLJj = 0;
       if(_J0OLC($_IfLJj, $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
          _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
          exit;
       } else {
          if ($_I80Jo["SubscriptionType"] == 'SingleOptIn' || isset($_I6tLJ["DuplicateEntry"])) {

                if(!isset($_I6tLJ["DuplicateEntry"]) && $_Jj08l["SendOptInConfirmedMail"] ) {
                   _J0LAA("subscribeconfirmed", $_IfLJj, $_I80Jo, $_Jj08l, $errors, $_I816i);
                }

                if(($_JC8t6 || $_JCtOQ) && !isset($_I6tLJ["DuplicateEntry"])) {
                   _J0JOF($_IfLJj, $_I80Jo, array(), "subscribe", $_Jj08l);
                }

                if(!isset($_I6tLJ["DuplicateEntry"])) {
                   _J0R06($_IfLJj, $_I80Jo, array(), "subscribe", $_Jj08l);
                }

                _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "SubscribeConfirmationPage", "SubscribeOKText", $_Jj08l, $_I816i);
              }
              else
              _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "SubscribeAcceptedPage", "SubscribeTextConfirmationRequired", $_Jj08l, $_I816i);
          exit;
       }
    } else {
        _J06JO(0, $_I8I6o, "SubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
    }
  }

  if($Action == "subscribeconfirm" ) {
    if (!_LFDAF($_I6tLJ, $_Jj08l, $errors, $_I816i)) {
      _J06JO($_I6tLJ["RecipientId"], $_I6tLJ["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
      exit;
    }
    $_QLfol = "SELECT * FROM $_QL88I WHERE id=$_I6tLJ[MailingListId]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1) {
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D("Invalid mailing list", $_JCIO0);
    }
    $_I80Jo = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    // External Scripts
    $_Jj08l["ExternalSubscriptionScript"] = $_I80Jo["ExternalSubscriptionScript"];
    $_Jj08l["ExternalUnsubscriptionScript"] = $_I80Jo["ExternalUnsubscriptionScript"];
    $_Jj08l["ExternalEditScript"] = $_I80Jo["ExternalEditScript"];

    if(_J0OLC($_I6tLJ["RecipientId"], $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
       _J06JO($_I6tLJ["RecipientId"], $_I80Jo["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
       exit;
    } else {

       if( $_Jj08l["SendOptInConfirmedMail"] ) {
          _J0LAA("subscribeconfirmed", $_I6tLJ["RecipientId"], $_I80Jo, $_Jj08l, $errors, $_I816i);
       }

       if($_I80Jo["SendEMailToAdminOnOptIn"] || $_I80Jo["SendEMailToEMailAddressOnOptIn"]) {
          _J0JOF($_I6tLJ["RecipientId"], $_I80Jo, array(), "subscribe", $_Jj08l);
       }

       _J0R06($_I6tLJ["RecipientId"], $_I80Jo, array(), "subscribe", $_Jj08l);

       _J06JO($_I6tLJ["RecipientId"], $_I80Jo["MaillistTableName"], "SubscribeConfirmationPage", "SubscribeTextOnConfirmationSucc", $_Jj08l, $_I816i);
       exit;
    }
  }

  if($Action == "subscribereject" ) {
    if (!_LFDER($_I6tLJ, $_Jj08l, $errors, $_I816i)) {
      _J06JO($_I6tLJ["RecipientId"], $_I6tLJ["MaillistTableName"], "UnsubscribeErrorPage", "UnsubscribeTextOnConfirmationFailure", $_Jj08l, $_I816i);
      exit;
    }
    $_QLfol = "SELECT * FROM $_QL88I WHERE id=$_I6tLJ[MailingListId]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1) {
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D("Invalid mailing list", $_JCIO0);
    }
    $_I80Jo = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    // External Scripts
    $_Jj08l["ExternalSubscriptionScript"] = $_I80Jo["ExternalSubscriptionScript"];
    $_Jj08l["ExternalUnsubscriptionScript"] = $_I80Jo["ExternalUnsubscriptionScript"];
    $_Jj08l["ExternalEditScript"] = $_I80Jo["ExternalEditScript"];

    $_QLfol = "SELECT * FROM $_I6tLJ[MaillistTableName] WHERE id=".$_I6tLJ["RecipientId"];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_JCl0i = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    if(_J0OFP($_I6tLJ["RecipientId"], $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
       _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
       exit;
    } else {
       _J0R06($_I6tLJ["RecipientId"], $_I80Jo, $_JCl0i, "unsubscribe", $_Jj08l); // reject = unsubscribe external

       _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeConfirmationPage", "UnsubscribeOKText", $_Jj08l, $_I816i);
       
       exit;
    }
  }

  if($Action == "unsubscribe") {
    if(_LFD8C($_I6tLJ, $_IOJoI, $_Jj08l, $_I8I6o, $errors, $_I816i)) {
       $_QLfol = "SELECT * FROM $_QL88I WHERE id=$MailingListId";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(!$_QL8i1) {
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D("Invalid mailing list", $_JCIO0);
       }
       $_I80Jo = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);

       // get recipient data a last time for personalization
       $_QLfol = "SELECT * FROM $_I6tLJ[MaillistTableName] WHERE id=".intval($_I6tLJ["RecipientId"]);
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_JCl0i = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);

       if( !defined('ListUnsubscribePOST') && defined('IsNLUPHP') && $_Jj08l["UnsubscribeBridgePage"] > 0 && empty($_I6tLJ["unsubscribeconfirmation"]) ) {
         _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeBridgePage", "", $_Jj08l, $_I816i);
         exit;
       }

       //remove or send confirmation mail when required
       $_IfLJj = intval($_I6tLJ["RecipientId"]);
       if(!_J0O61($_IfLJj, $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i)) {
          _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
          exit;
       } else {
          if ($_I80Jo["UnsubscriptionType"] == 'SingleOptOut') {

               if( $_Jj08l["SendOptOutConfirmedMail"] ) {
                  $rid = "";
                  if(isset($_I6tLJ["rid"]))
                    $rid = $_I6tLJ["rid"];
                  _J0LAA("unsubscribeconfirmed", $_JCl0i, $_I80Jo, $_Jj08l, $errors, $_I816i, $rid);
               }

               if($_JCtfJ || $_JCtCJ) {
                  _J0JOF($_IfLJj, $_I80Jo, $_JCl0i, "unsubscribe", $_Jj08l);
               }

               _J0R06($_IfLJj, $_I80Jo, $_JCl0i, "unsubscribe", $_Jj08l);

               _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeConfirmationPage", "UnsubscribeOKText", $_Jj08l, $_I816i);
              }
              else
              _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeAcceptedPage", "UnsubscribeTextConfirmationRequired", $_Jj08l, $_I816i);
          exit;
       }

    } else {
       _J06JO(0, $_I8I6o, "UnsubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
    }
  }

  if($Action == "unsubscribeconfirm" ) {
    if (!_LFE80($_I6tLJ, $_Jj08l, $errors, $_I816i)) {
      _J06JO($_I6tLJ["RecipientId"], $_I6tLJ["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
    } else {

      $_QLfol = "SELECT * FROM $_QL88I WHERE id=".$_I6tLJ["MailingListId"];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1) {
       $_JCIO0 = $commonmsgNoParameters;
       _LJD1D("Invalid mailing list", $_JCIO0);
      }
      $_I80Jo = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      // get recipient data a last time for personalization
      $_QLfol = "SELECT * FROM $_I6tLJ[MaillistTableName] WHERE id=".intval($_I6tLJ["RecipientId"]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_JCl0i = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

       $_IfLJj = intval($_I6tLJ["RecipientId"]);
       if(_J0O61($_IfLJj, $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
          _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
          exit;
       } else {

          if( $_Jj08l["SendOptOutConfirmedMail"] ) {
             _J0LAA("unsubscribeconfirmed", $_JCl0i, $_I80Jo, $_Jj08l, $errors, $_I816i);
          }

          if($_I80Jo["SendEMailToAdminOnOptOut"] || $_I80Jo["SendEMailToEMailAddressOnOptOut"]) {
             _J0JOF($_IfLJj, $_I80Jo, $_JCl0i, "unsubscribe", $_Jj08l);
          }
          _J0R06($_IfLJj, $_I80Jo, $_JCl0i, "unsubscribe", $_Jj08l);

          _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeConfirmationPage", "UnsubscribeOKText", $_Jj08l, $_I816i);
          exit;
       }
    }
  }

  if($Action == "unsubscribereject" ) {
    if (!_LFEBB($_I6tLJ, $_Jj08l, $errors, $_I816i)) {
      _J06JO($_I6tLJ["RecipientId"], $_I6tLJ["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
      exit;
    }
    $_QLfol = "SELECT * FROM $_QL88I WHERE id=$_I6tLJ[MailingListId]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1) {
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D("Invalid mailing list", $_JCIO0);
    }
    $_I80Jo = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    if(_J0LQ0($_I6tLJ["RecipientId"], $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
       _J06JO($_I6tLJ["RecipientId"], $_I80Jo["MaillistTableName"], "SubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
       exit;
    } else {
       _J06JO($_I6tLJ["RecipientId"], $_I80Jo["MaillistTableName"], "SubscribeConfirmationPage", "SubscribeOKText", $_Jj08l, $_I816i);
       exit;
    }
  }

  if($Action == "edit") {

    if( !isset($_I6tLJ["NewEMail"]) || $_I6tLJ["NewEMail"] == ""  )
        if(isset($_I6tLJ["EMail"]))
          $_I6tLJ["NewEMail"] = $_I6tLJ["EMail"];

    // we check it is in list
    if(_LFFPD($_I6tLJ, $_IOJoI, $_Jj08l, $_I8I6o, $_jjj8f, $_Jj6f0, $errors, $_I816i)) {
       $_QLfol = "SELECT * FROM $_QL88I WHERE id=$MailingListId";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(!$_QL8i1) {
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D("Invalid mailing list", $_JCIO0);
       }
       $_I80Jo = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);

       if(isset($_I6tLJ["DuplicateEntry"]))
          unset($_I6tLJ["DuplicateEntry"]);

       //save changes
       $_IfLJj = $_I6tLJ["RecipientId"];
       if(isset($_I6tLJ["u_Birthday"])) {
          $_I6tLJ["u_Birthday"] = _L8CJF($_I6tLJ["u_Birthday"], $INTERFACE_LANGUAGE);
       }
       if(_J0OLC($_IfLJj, $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
          _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "EditErrorPage", "ErrorText", $_Jj08l, $_I816i);
          exit;
       } else {
           if($_IfLJj != "editconfirm") {
              if($_I80Jo["SendEMailToAdminOnOptIn"] || $_I80Jo["SendEMailToEMailAddressOnOptIn"]) {
                _J0JOF($_IfLJj, $_I80Jo, array(), "edit", $_Jj08l, $_I6tLJ["u_EMail"]);
              }

              _J0R06($_IfLJj, $_I80Jo, array(), "edit", $_Jj08l, $_I6tLJ["u_EMail"]);

              _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "EditConfirmationPage", "", $_Jj08l, $_I816i);
              exit;
          } else {
            $_IfLJj = $_I6tLJ["RecipientId"];
            _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "EditAcceptedPage", "", $_Jj08l, $_I816i);
            exit;
          }
       }
    } else {
        _J06JO(0, $_I8I6o, "EditErrorPage", "ErrorText", $_Jj08l, $_I816i);
    }
  }

  if($Action == "editconfirm" ) {
    if ( !_LFFEL($_I6tLJ, $_Jj08l, $errors, $_I816i) || empty($_I6tLJ["ChangedDetails"]) ) {
      _J06JO($_I6tLJ["RecipientId"], $_I6tLJ["MaillistTableName"], "EditErrorPage", "ErrorText", $_Jj08l, $_I816i);
      exit;
    }
    $_QLfol = "SELECT * FROM $_QL88I WHERE id=$_I6tLJ[MailingListId]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1) {
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D("Invalid mailing list", $_JCIO0);
    }
    $_I80Jo = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    // External Scripts
    $_Jj08l["ExternalSubscriptionScript"] = $_I80Jo["ExternalSubscriptionScript"];
    $_Jj08l["ExternalUnsubscriptionScript"] = $_I80Jo["ExternalUnsubscriptionScript"];
    $_Jj08l["ExternalEditScript"] = $_I80Jo["ExternalEditScript"];

    $_I6tLJ["RecipientId"] = intval($_I6tLJ["RecipientId"]);
    $_IfLJj = $_I6tLJ["RecipientId"];

    if(_J0OLC($_I6tLJ["RecipientId"], $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
       _J06JO($_I6tLJ["RecipientId"], $_I80Jo["MaillistTableName"], "EditErrorPage", "ErrorText", $_Jj08l, $_I816i);
       exit;
    } else {

       if( $_Jj08l["SendEditConfirmedMail"] ) {
          _J0LAA("editconfirmed", $_I6tLJ["RecipientId"], $_I80Jo, $_Jj08l, $errors, $_I816i);
       }

       if($_I80Jo["SendEMailToAdminOnOptIn"] || $_I80Jo["SendEMailToEMailAddressOnOptIn"]) {
         _J0JOF($_IfLJj, $_I80Jo, array(), "edit", $_Jj08l, $_I6tLJ["u_EMail"]);
       }

       _J0R06($_IfLJj, $_I80Jo, array(), "edit", $_Jj08l, $_I6tLJ["u_EMail"]);

       _J06JO($_IfLJj, $_I80Jo["MaillistTableName"], "EditConfirmationPage", "", $_Jj08l, $_I816i);

       exit;
    }
  }

  if($Action == "editreject" ) {
    if (!_LFFEC($_I6tLJ, $_Jj08l, $errors, $_I816i)) {
      _J06JO($_I6tLJ["RecipientId"], $_I6tLJ["MaillistTableName"], "EditErrorPage", "ErrorText", $_Jj08l, $_I816i);
      exit;
    }
    $_QLfol = "SELECT * FROM $_QL88I WHERE id=$_I6tLJ[MailingListId]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1) {
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D("Invalid mailing list", $_JCIO0);
    }
    $_I80Jo = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    // get recipient data for personalization
    $_QLfol = "SELECT * FROM $_I6tLJ[MaillistTableName] WHERE id=".intval($_I6tLJ["RecipientId"]);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_JCl0i = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    if(_J0LJD($_I6tLJ["RecipientId"], $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
       _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "EditErrorPage", "ErrorText", $_Jj08l, $_I816i);
       exit;
    } else {
       _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "EditRejectPage", "", $_Jj08l, $_I816i);
       exit;
    }
  }


  // ******** check the actions END

  function _LJD1D($_J0COJ, $_JCIO0) {
    global $AppName, $_QLo06, $_Jj08l;

    SetHTMLHeaders($_QLo06, false);

    if(!isset($_Jj08l) || empty($_Jj08l["UserDefinedFormsFolder"]))
      $_QLoli = join("", file(_LOC8P()."default_errorpage.htm"));
      else
      $_QLoli = join("", file(_LPC1C(InstallPath.$_Jj08l["UserDefinedFormsFolder"])."default_errorpage.htm"));
    $_QLoli = _LRD81("<title>", "<title>".$AppName." - ".$_J0COJ, $_QLoli);

    _JJCCF($_QLoli);

    $_QLoli = _LRD81("<!--ERRORTEXT//-->", $_J0COJ, $_QLoli);
    $_QLoli = _LRD81("<!--ERRORHTMLTEXT//-->", $_JCIO0, $_QLoli);
    print $_QLoli;
    exit;
  }

?>
