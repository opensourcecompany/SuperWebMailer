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
  // default script only for subscribe and unsubscribe

  include_once("config.inc.php");
  include_once("templates.inc.php");
  include_once("newslettersubunsubcheck.inc.php");
  include_once("newslettersubunsub_ops.inc.php");
  include_once("replacements.inc.php");
  include_once("savedoptions.inc.php");
  @include_once("php_register_globals_off.inc.php");

  # is defaultnewsletter.php
  define('DefaultNewsletterPHP', 1);

  $errors = array();
  $_Ql1O8 = array();

  $_jj0JO = $commonmsgAnErrorOccured;

  if(isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == "HEAD" || $_SERVER['REQUEST_METHOD'] == "OPTIONS")){
        $_jCO1J = $commonmsgNoParameters;
        _ORECR($_jj0JO, $_jCO1J);
  }

  if(empty($_GET["key"]) && empty($_POST["key"])){
    if(
        (!isset($_GET["MailingListId"]) && !isset($_POST["MailingListId"]) && !isset($_GET["ML"]) && !isset($_POST["ML"]) && !isset($_GET["RecipientsListId"]) && !isset($_POST["RecipientsListId"]) && !isset($_GET["RL"]) && !isset($_POST["RL"]) )
        ||
        (!isset($_GET["FormId"]) && !isset($_POST["FormId"])) && (!isset($_GET["F"]) && !isset($_POST["F"]))

      ){
          $_jCO1J = $commonmsgNoParameters;
          _ORECR($_jj0JO, $_jCO1J);
    }
  }

  if (isset($_GET["RecipientsListId"]))
    $MailingListId =  $_GET["RecipientsListId"];
  if (isset($_POST["RecipientsListId"]))
    $MailingListId = $_POST["RecipientsListId"];

  if (isset($_GET["MailingListId"]))
    $MailingListId =  $_GET["MailingListId"];
  if (isset($_POST["MailingListId"]))
    $MailingListId = $_POST["MailingListId"];

  if (isset($_GET["ML"]))
    $MailingListId =  $_GET["ML"];
  if (isset($_POST["ML"]))
    $MailingListId = $_POST["ML"];

  if (isset($_GET["RL"]))
    $MailingListId =  $_GET["RL"];
  if (isset($_POST["RL"]))
    $MailingListId = $_POST["RL"];

  if (isset($MailingListId) && !is_numeric($MailingListId) ) {
    $MailingListId = _L0QRE($MailingListId);
  }

  if(isset($_GET["FormId"]))
     $FormId = $_GET["FormId"];
  if(isset($_POST["FormId"]))
     $FormId = $_POST["FormId"];

  if(isset($_GET["F"]))
     $FormId = $_GET["F"];
  if(isset($_POST["F"]))
     $FormId = $_POST["F"];

  if (isset($FormId) && !is_numeric($FormId) ) {
    $FormId = _L0QBJ($MailingListId, $FormId);
  }

  $_Qi8If = $_POST;
  if(count($_GET) > 2)
    foreach($_GET as $key => $_Q6ClO)
       if(substr($key, 0, 2) == "u_")
           $_Qi8If[$key] = $_Q6ClO;

  $Action = !empty($_GET["Action"]) ? $_GET["Action"] : "";
  $Action = !empty($_POST["Action"]) ? $_POST["Action"] : "";
  $_jCCCJ = !empty($_GET["key"]) ? $_GET["key"] : "";
  if( !empty($_POST["key"]) )
     $_jCCCJ = $_POST["key"];

  $key = $_jCCCJ;
  $rid = !empty($_GET["rid"]) ? $_GET["rid"] : "";

  // key parameter? override all
  if(!empty($key)){
    $_QLitI = 0;
    $MailingListId = 0;
    $FormId = 0;
    if(!_OA8LE($key, $_QLitI, $MailingListId, $FormId, $rid)) {
       $key = "";
       $_jCCCJ = "";
    }
    unset($_QLitI);
  } else {

    if(!empty($rid)){
       $_Q8otJ = explode("_", $rid);
       if(count($_Q8otJ) > 4){
         if($_Q8otJ[4]{0} == "x" && hexdec(substr($_Q8otJ[4], 1)))
           $FormId = hexdec(substr($_Q8otJ[4], 1));
       }
    }

  }

  $HTMLForm = "";
  if (isset($_GET["HTMLForm"]))
     $HTMLForm = $_GET["HTMLForm"];
  if(isset($_POST["HTMLForm"]))
     $HTMLForm = $_POST["HTMLForm"];

  // fix XSS
  if($HTMLForm != "" && $HTMLForm != "editform" && $HTMLForm != "unsubform" && $HTMLForm != "subform"){
    $HTMLForm = "";
  }

  $_Qi8If["HTMLForm"] = $HTMLForm;


  $FormId = intval($FormId);
  $_Qi8If["MailingListId"] = intval($MailingListId);
  $_Qi8If["FormId"] = $FormId;
  $MailingListId = intval($MailingListId);

  # check recipient groups START
  if(isset($_Qi8If["RecipientGroups"])){
     $_QllO8 = $_Qi8If["RecipientGroups"];
     if(!is_array($_QllO8))
        if (strpos($_QllO8, ",") === false) {
           $_QllO8 = array($_QllO8);
        }
        else
          $_QllO8 = explode(",", $_QllO8);
     unset($_Qi8If["RecipientGroups"]);
     $_Qi8If["RecipientGroups"] = array();
     // key = group id
     foreach($_QllO8 as $key => $_Q6ClO){
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

  $_QJlJ0 = "SELECT `users_id`, `SubscriptionUnsubscription`, `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `FormsTableName`, `GroupsTableName`, `ReasonsForUnsubscripeTableName`, `SendEMailToAdminOnOptIn`, `SendEMailToAdminOnOptOut`, `SendEMailToEMailAddressOnOptIn`, `SendEMailToEMailAddressOnOptOut`, `ExternalSubscriptionScript`, `ExternalUnsubscriptionScript`, `ExternalEditScript` FROM `$_Q60QL` WHERE `id`=".intval($MailingListId);
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
  $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE id=$_Q6Q1C[users_id]";
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
  if(!empty($Action) && _OC6FL($_ji0L6)){
    $_jCO1J = sprintf($commonmsgSubUnsubScriptRequestOverLimit, getOwnIP(), getOwnIP());
    _ORECR($_jj0JO, $_jCO1J);
    exit;
  }

  if(!empty($Action) && $_ji0i1 != "Allowed"){
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


  $_QJlJ0 = "SELECT $_QLI8o.*, $_QLI8o.id AS FormId, $_QLo0Q.*, $_Q880O.Theme FROM $_QLI8o LEFT JOIN $_QLo0Q ON $_QLo0Q.id=$_QLI8o.messages_id LEFT JOIN $_Q880O ON $_Q880O.id=$_QLI8o.ThemesId WHERE $_QLI8o.id=".intval($FormId);
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
  if($_j6ioL["UseCaptcha"]) {
     header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
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

  // edit fix form without new email address
  if($Action == "edit"){
     if( empty($_Qi8If["NewEMail"]) && empty($_Qi8If["Newu_EMail"]) && !empty($_Qi8If["u_EMail"]) )
       $_Qi8If["NewEMail"] = $_Qi8If["u_EMail"];
  }

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
          if ($_Ql00j["SubscriptionType"] == 'SingleOptIn' || isset($_Qi8If["DuplicateEntry"]) ) {

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

  if($Action == "edit") {

    if( !isset($_Qi8If["NewEMail"]) || $_Qi8If["NewEMail"] == ""  )
        if(isset($_Qi8If["EMail"]))
          $_Qi8If["NewEMail"] = $_Qi8If["EMail"];

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
            if($_jCitf || $_jCLI6) {
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
       $_QJlJ0 = "SELECT * FROM $_Qi8If[MaillistTableName] WHERE id=".$_Qi8If["RecipientId"];
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_jiJC0 = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       //remove or send confirmation mail when required
       $_QLitI = $_Qi8If["RecipientId"];
       if(_L0LAF($_QLitI, $_Ql00j, $_Qi8If, $_j6ioL, $Action, $errors, $_Ql1O8) === false) {
          _L08LL($_jiJC0, $_Ql00j["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
          exit;
       } else {
          if ($_Ql00j["UnsubscriptionType"] == 'SingleOptOut') {

               if($_j6ioL["SendOptOutConfirmedMail"] ) {
                  _L0RLJ("unsubscribeconfirmed", $_jiJC0, $_Ql00j, $_j6ioL, $errors, $_Ql1O8);
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

  // no subscribe || unsubscribe => error
  if(! ($Action == "subscribe" || $Action == "unsubscribe") && ($Action != "") ) {
     $errors[] = $_j6ioL["ErrorText"]."<br />".$resourcestrings[$INTERFACE_LANGUAGE]["unknown_Action"]."<br />";
     _L08LL(0, $_QlQC8, "SubscribeErrorPage", "ErrorText", $_j6ioL, $errors);
  }


  // ******** check the actions END

  if($HTMLForm == "editform" && $Action == "" && $_jCCCJ != "") {
    $_Qi8If["RecipientGroups"] = array();
  }

  // XSS leak, user can deactivate it
  if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){
    foreach($_Qi8If as $key => $_Q6ClO){
      if(is_string($_Q6ClO)) {
        $_Qi8If[$key] = str_ireplace("javascript", "java*cript", htmlspecialchars($_Q6ClO, ENT_COMPAT, 'UTF-8'));
      }
    }
  }

  // Get HTML form
  $_QJCJi = _L0OPJ($_Qi8If, $_j6ioL, $HTMLForm);

  // footer
  _LJ81E($_QJCJi);
  // footer END

  if(!isset($_Qi8If["Action"]) || $_Qi8If["Action"] == "")
     if($HTMLForm == "editform")
       $_Qi8If["Action"] = "edit";
       else
       if($HTMLForm == "unsubform")
         $_Qi8If["Action"] = "unsubscribe";
         else
         $_Qi8If["Action"] = "subscribe";

  if($HTMLForm == "editform" && $Action == "") {

    if(!empty($_jCCCJ))
      $_QJlJ0 = "SELECT * FROM $_QlQC8 WHERE `IdentString`="._OPQLR($_jCCCJ);
      else
      if(!empty($_Qi8If["u_EMail"]))
         $_QJlJ0 = "SELECT * FROM $_QlQC8 WHERE `u_EMail`="._OPQLR($_Qi8If["u_EMail"]);
         else
         $_QJlJ0 = "SELECT * FROM $_QlQC8 WHERE `u_EMail`="._OPQLR(chr(255)."Invalid recipient email address.");

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1) {
     $_jCO1J = $commonmsgNoParameters;
     _ORECR("Invalid recipient.", $_jCO1J);
    }

    if($_Q60l1 && mysql_num_rows($_Q60l1) == 0) {
      $_Ql1O8[] = $_j6ioL["EMailAddressNotInList"];
      _L08LL(0, $_QlQC8, "UnsubscribeErrorPage", "ErrorText", $_j6ioL, $_Ql1O8);
    }

    if($_Q60l1 && mysql_num_rows($_Q60l1)) {
      $_jiJLO = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_Qi8If["key"] = $_jiJLO["IdentString"];
      $_jiJLO["u_Birthday"] = _OAQOB($_jiJLO["u_Birthday"], $INTERFACE_LANGUAGE);
      $_QJlJ0 = "SELECT groups_id FROM $_QLI68 WHERE Member_id=$_jiJLO[id]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Qi8If["RecipientGroups"] = array();
      while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
        $_Qi8If["RecipientGroups"][] = $_Q6Q1C["groups_id"];
      }
      mysql_free_result($_Q60l1);
      reset($_I16jJ);
      foreach($_I16jJ as $key => $_Q6ClO) {
        if(isset($_jiJLO[$key]))
           $_Qi8If[$key] = $_jiJLO[$key];
      }
    }
  }

  // change this because MarkFields must find it
  if( isset($_Qi8If["RecipientGroups"]) && !$_j6ioL["GroupsAsCheckBoxes"] ) {
    $_Q6Oto = $_Qi8If["RecipientGroups"];
    unset($_Qi8If["RecipientGroups"]);
    $_Qi8If["RecipientGroups"] = array();
    reset($_Q6Oto);
    foreach($_Q6Oto as $_I1i8O => $_I1L81) {
      if(isset($_Q6Oto[$_I1i8O]) ) {
        $_Qi8If["RecipientGroups"][] = $_I1L81;
      }
    }
  }

  if( isset($_Qi8If["RecipientGroups"]) && $_j6ioL["GroupsAsCheckBoxes"] ) {
    $_Q6Oto = $_Qi8If["RecipientGroups"];
    unset($_Qi8If["RecipientGroups"]);
    reset($_Q6Oto);
    foreach($_Q6Oto as $_I1i8O => $_I1L81) {
      if(isset($_Q6Oto[$_I1i8O]) ) {
        $_QJCJi = str_replace('name="RecipientGroups[]" value="'.$_I1L81.'"', 'name="RecipientGroups[]" value="'.$_I1L81.'" checked="checked"', $_QJCJi);
      }
    }
  }

  $_QJCJi = _OPFJA($errors, $_Qi8If, $_QJCJi);

  if(count($errors) == 0)
    $_QJCJi = _OP6PQ($_QJCJi, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    else {
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", join("", $_Ql1O8));
      $_QJCJi = _OPR6L($_QJCJi, '<SHOWHIDE:ERRORTOPIC>', '<SHOWHIDE:ERRORTOPIC>', "");
      $_QJCJi = _OPR6L($_QJCJi, '</SHOWHIDE:ERRORTOPIC>', '</SHOWHIDE:ERRORTOPIC>', "");
    }

  SetHTMLHeaders($_Q6QQL);

  print $_QJCJi;

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
