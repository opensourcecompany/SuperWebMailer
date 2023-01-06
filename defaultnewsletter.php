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
  $_I816i = array();

  $_J0COJ = $commonmsgAnErrorOccured;

  if(isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == "HEAD" || $_SERVER['REQUEST_METHOD'] == "OPTIONS")){
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D($_J0COJ, $_JCIO0);
  }

  if(empty($_GET["key"]) && empty($_POST["key"])){
    if(
        (!isset($_GET["MailingListId"]) && !isset($_POST["MailingListId"]) && !isset($_GET["ML"]) && !isset($_POST["ML"]) && !isset($_GET["RecipientsListId"]) && !isset($_POST["RecipientsListId"]) && !isset($_GET["RL"]) && !isset($_POST["RL"]) )
        ||
        (!isset($_GET["FormId"]) && !isset($_POST["FormId"])) && (!isset($_GET["F"]) && !isset($_POST["F"]))

      ){
          $_JCIO0 = $commonmsgNoParameters;
          _LJD1D($_J0COJ, $_JCIO0);
    }
  }

  $Action = !empty($_GET["Action"]) ? $_GET["Action"] : "";
  $Action = !empty($_POST["Action"]) ? $_POST["Action"] : "";

  if(strpos($Action, "confirm") !== false || strpos($Action, "reject") !== false){
    include_once("nlu.php");
    exit;
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
    $MailingListId = _J00LP($MailingListId);
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
    $FormId = _J011E($MailingListId, $FormId);
  }

  $_I6tLJ = $_POST;
  if(count($_GET) > 2)
    foreach($_GET as $key => $_QltJO)
       if(substr($key, 0, 2) == "u_" || $key == "RecipientGroups")
           $_I6tLJ[$key] = $_QltJO;

  $_JC6Jf = !empty($_GET["key"]) ? $_GET["key"] : "";
  if( !empty($_POST["key"]) )
     $_JC6Jf = $_POST["key"];

  $key = $_JC6Jf;
  $rid = !empty($_GET["rid"]) ? $_GET["rid"] : "";

  // key parameter? override all
  if(!empty($key)){
    $_IfLJj = 0;
    $MailingListId = 0;
    $FormId = 0;
    $_JC6oo = _LPQEP($key, $_IfLJj, $MailingListId, $FormId, $rid);
    if(!$_JC6oo || ($_JC6oo && !_J01BO($key, $MailingListId)) ) {
       $key = "";
       $_JC6Jf = "";
       _LJD1D($_J0COJ, $commonmsgParamKeyInvalid);
    }
    unset($_IfLJj);
    if(!empty($rid))
      $_I6tLJ["rid"] = $rid;
  } else{
    // rid can ever override form-id
    if(!empty($rid)){
       $_I6tLJ["rid"] = $rid;
       $_I1OoI = explode("_", $rid);
       if(count($_I1OoI) > 4){
         if($_I1OoI[4][0] == "x" && hexdec(substr($_I1OoI[4], 1)))
           $FormId = hexdec(substr($_I1OoI[4], 1));
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

  $_I6tLJ["HTMLForm"] = $HTMLForm;


  $FormId = intval($FormId);
  $_I6tLJ["MailingListId"] = intval($MailingListId);
  $_I6tLJ["FormId"] = $FormId;
  $MailingListId = intval($MailingListId);

  # check recipient groups START
  if(isset($_I6tLJ["RecipientGroups"])){
     $_I016j = $_I6tLJ["RecipientGroups"];
     if(!is_array($_I016j))
        if (strpos($_I016j, ",") === false) {
           $_I016j = array($_I016j);
        }
        else
          $_I016j = explode(",", $_I016j);
     unset($_I6tLJ["RecipientGroups"]);
     $_I6tLJ["RecipientGroups"] = array();
     // key = group id
     foreach($_I016j as $key => $_QltJO){
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

  $_QLfol = "SELECT `users_id`, `SubscriptionUnsubscription`, `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `FormsTableName`, `GroupsTableName`, `ReasonsForUnsubscripeTableName`, `SendEMailToAdminOnOptIn`, `SendEMailToAdminOnOptOut`, `SendEMailToEMailAddressOnOptIn`, `SendEMailToEMailAddressOnOptOut`, `ExternalSubscriptionScript`, `ExternalUnsubscriptionScript`, `ExternalEditScript` FROM `$_QL88I` WHERE `id`=".intval($MailingListId);
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
  $_QLfol = "SELECT * FROM `$_I18lo` WHERE id=$_QLO0f[users_id]";
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
  if(!empty($Action) && _LAFQC($_JCC81)){
    $_JCIO0 = sprintf($commonmsgSubUnsubScriptRequestOverLimit, getOwnIP(), getOwnIP());
    _LJD1D($_J0COJ, $_JCIO0);
    exit;
  }

  if(!empty($Action) && $_JCC1C != "Allowed"){
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


  $_QLfol = "SELECT $_IfJoo.*, $_IfJoo.id AS FormId, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=".intval($FormId);
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

  // edit fix form without new email address
  if($Action == "edit"){
     if(empty($_I6tLJ["key"]))
       unset($_I6tLJ["key"]);
     if(isset($_I6tLJ["key"])){
       if($_QLO0f = _J01BO($_I6tLJ["key"], intval($MailingListId)))
         $_I6tLJ["u_EMail"] = $_QLO0f["u_EMail"];
         else
         unset($_I6tLJ["u_EMail"]);
     }
     if( empty($_I6tLJ["NewEMail"]) && empty($_I6tLJ["Newu_EMail"]) && !empty($_I6tLJ["u_EMail"]) )
       $_I6tLJ["NewEMail"] = $_I6tLJ["u_EMail"];
  }

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
          if ($_I80Jo["SubscriptionType"] == 'SingleOptIn' || isset($_I6tLJ["DuplicateEntry"]) ) {

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

  if($Action == "edit") {

    if( !isset($_I6tLJ["NewEMail"]) || $_I6tLJ["NewEMail"] == ""  )
        if(isset($_I6tLJ["EMail"]))
          $_I6tLJ["NewEMail"] = $_I6tLJ["EMail"];

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
            if($_JC8t6 || $_JCtOQ) {
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
       $_QLfol = "SELECT * FROM $_I6tLJ[MaillistTableName] WHERE id=".$_I6tLJ["RecipientId"];
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_JCl0i = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);

       //remove or send confirmation mail when required
       $_IfLJj = $_I6tLJ["RecipientId"];
       if(_J0O61($_IfLJj, $_I80Jo, $_I6tLJ, $_Jj08l, $Action, $errors, $_I816i) === false) {
          _J06JO($_JCl0i, $_I80Jo["MaillistTableName"], "UnsubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
          exit;
       } else {
          if ($_I80Jo["UnsubscriptionType"] == 'SingleOptOut') {

               if($_Jj08l["SendOptOutConfirmedMail"] ) {
                  _J0LAA("unsubscribeconfirmed", $_JCl0i, $_I80Jo, $_Jj08l, $errors, $_I816i);
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

  // no subscribe || unsubscribe => error
  if(! ($Action == "subscribe" || $Action == "unsubscribe") && ($Action != "") ) {
     $errors[] = $_Jj08l["ErrorText"]."<br />".$resourcestrings[$INTERFACE_LANGUAGE]["unknown_Action"]."<br />";
     _J06JO(0, $_I8I6o, "SubscribeErrorPage", "ErrorText", $_Jj08l, $errors);
  }


  // ******** check the actions END

  if($HTMLForm == "editform" && $Action == "" && $_JC6Jf != "") {
    $_I6tLJ["RecipientGroups"] = array();
  }

  // XSS leak, user can deactivate it
  if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){
    foreach($_I6tLJ as $key => $_QltJO){
      if(is_string($_QltJO)) {
        $_I6tLJ[$key] = htmlspecialchars(_LA8F6($_QltJO), ENT_COMPAT, $_QLo06);
      }
    }
  }

  // Get HTML form
  if(!empty($_JC6Jf))
    $_I6tLJ["key"] = $_JC6Jf;
  $_QLJfI = _J0Q81($_I6tLJ, $_Jj08l, $HTMLForm);

  // footer
  _JJCCF($_QLJfI);
  // footer END

  if(!isset($_I6tLJ["Action"]) || $_I6tLJ["Action"] == "")
     if($HTMLForm == "editform")
       $_I6tLJ["Action"] = "edit";
       else
       if($HTMLForm == "unsubform")
         $_I6tLJ["Action"] = "unsubscribe";
         else
         $_I6tLJ["Action"] = "subscribe";

  if($HTMLForm == "editform" && $Action == "" && (!empty($_JC6Jf) || !empty($_I6tLJ["u_EMail"])) ) {

    if(!empty($_JC6Jf))
      $_QLfol = "SELECT * FROM $_I8I6o WHERE `IdentString`="._LRAFO($_JC6Jf);
      else
      if(!empty($_I6tLJ["u_EMail"]))
         $_QLfol = "SELECT * FROM $_I8I6o WHERE `u_EMail`="._LRAFO($_I6tLJ["u_EMail"]);
         else
         $_QLfol = "SELECT * FROM $_I8I6o WHERE `u_EMail`="._LRAFO(chr(255)."Invalid recipient email address.");

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1) {
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D("Invalid recipient.", $_JCIO0);
    }

    if($_QL8i1 && mysql_num_rows($_QL8i1) == 0) {
      $_I816i[] = $_Jj08l["EMailAddressNotInList"];
      _J06JO(0, $_I8I6o, "UnsubscribeErrorPage", "ErrorText", $_Jj08l, $_I816i);
    }

    if($_QL8i1 && mysql_num_rows($_QL8i1)) {
      $_JClJJ = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      // Bool fields
      $_I016j = array('IsActive', 'PrivacyPolicyAccepted'); _L8FCO($_I8I6o, $_Iflj0, $_I016j);
      foreach ($_Iflj0 as $key => $_QltJO){
        if(isset($_JClJJ[$_QltJO]) && !$_JClJJ[$_QltJO])
          unset($_JClJJ[$_QltJO]);
      }
      // Bool fields /
      $_I6tLJ["key"] = $_JClJJ["IdentString"];
      $_JClJJ["u_Birthday"] = _L8BCR($_JClJJ["u_Birthday"], $INTERFACE_LANGUAGE);
      $_QLfol = "SELECT groups_id FROM $_IfJ66 WHERE Member_id=$_JClJJ[id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_I6tLJ["RecipientGroups"] = array();
      while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
        $_I6tLJ["RecipientGroups"][] = $_QLO0f["groups_id"];
      }
      mysql_free_result($_QL8i1);
      reset($_IOJoI);
      foreach($_IOJoI as $key => $_QltJO) {
        if(isset($_JClJJ[$key]))
           $_I6tLJ[$key] = $_JClJJ[$key];
      }
    }
  }

  // change this because MarkFields must find it
  if( isset($_I6tLJ["RecipientGroups"]) && !$_Jj08l["GroupsAsCheckBoxes"] ) {
    $_QlJ8C = $_I6tLJ["RecipientGroups"];
    unset($_I6tLJ["RecipientGroups"]);
    $_I6tLJ["RecipientGroups"] = array();
    reset($_QlJ8C);
    foreach($_QlJ8C as $_IOLil => $_IOCjL) {
      $_I6tLJ["RecipientGroups"][] = $_IOCjL;
    }
  }

  if( isset($_I6tLJ["RecipientGroups"]) && $_Jj08l["GroupsAsCheckBoxes"] ) {
    $_QlJ8C = $_I6tLJ["RecipientGroups"];
    unset($_I6tLJ["RecipientGroups"]);
    reset($_QlJ8C);
    foreach($_QlJ8C as $_IOLil => $_IOCjL) {
      $_QLJfI = str_replace('name="RecipientGroups[]" value="'.$_IOCjL.'"', 'name="RecipientGroups[]" value="'.$_IOCjL.'" checked="checked"', $_QLJfI);
    }
  }

  $_QLJfI = _L8AOB($errors, $_I6tLJ, $_QLJfI);

  if(count($errors) == 0)
    $_QLJfI = _L80DF($_QLJfI, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    else {
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", join("", $_I816i));
      $_QLJfI = _L81BJ($_QLJfI, '<SHOWHIDE:ERRORTOPIC>', '<SHOWHIDE:ERRORTOPIC>', "");
      $_QLJfI = _L81BJ($_QLJfI, '</SHOWHIDE:ERRORTOPIC>', '</SHOWHIDE:ERRORTOPIC>', "");
    }

  SetHTMLHeaders($_QLo06, false);

  print $_QLJfI;

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
