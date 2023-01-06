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
  include_once("templates.inc.php");
  @include_once("php_register_globals_off.inc.php");
  include_once("htmltools.inc.php");
  include_once("newslettersubunsubcheck.inc.php");

  $_J0COJ = $commonmsgAnErrorOccured;

  if(isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == "HEAD" || $_SERVER['REQUEST_METHOD'] == "OPTIONS")){
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D($_J0COJ, $_JCIO0);
  }

  if(empty($_GET["key"]) && empty($_POST["key"])){
     $_JCIO0 = $commonmsgNoParameters;
     _LJD1D($_J0COJ, $_JCIO0);
  }

  $Action = "";

  if( !empty($_POST["Action"]) )
    $Action = $_POST["Action"];

  if( !empty($_POST["PDFDownloadBtn"]) )
    $Action = "PDFDownloadBtn";

  $_JC6Jf = !empty($_GET["key"]) ? $_GET["key"] : "";
  if( !empty($_POST["key"]) )
     $_JC6Jf = $_POST["key"];

  $key = $_JC6Jf;

  $_IfLJj = 0;
  $MailingListId = 0;
  $FormId = 0;
  $rid = 0;

  if(!empty($key)){
    $_JC6oo = _LPQEP($key, $_IfLJj, $MailingListId, $FormId, $rid);
    if(!$_JC6oo || ($_JC6oo && !_J01BO($key, $MailingListId)) ) {
       $key = "";
       $_JC6Jf = "";
       $_IfLJj = 0;
       _LJD1D($_J0COJ, $commonmsgParamKeyInvalid);
    }
  }

  $_QLfol = "SELECT `users_id`, `SubscriptionUnsubscription`, `MaillistTableName`, `FormsTableName` FROM `$_QL88I` WHERE `id`=".intval($MailingListId);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_JCIO0 = $commonmsgMailListNotFound;
    _LJD1D($_J0COJ, $_JCIO0);
  }
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_j6lIj = $_QLO0f["users_id"];
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_I8I6o = $_QLO0f["MaillistTableName"];
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

  $_QLfol = "SELECT $_IfJoo.*, $_IfJoo.id AS FormId, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=".intval($FormId);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_JCIO0 = $commonmsgHTMLFormNotFound;
    _LJD1D($_J0COJ, $_JCIO0);
  }

  $_Jj08l = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  # set theme and language for correct template
  $INTERFACE_STYLE = $_Jj08l["Theme"];
  $INTERFACE_LANGUAGE = $_Jj08l["Language"];

  _JQRLR($INTERFACE_LANGUAGE);

   // set the user paths for images, attachments....
  _LRRFJ($_QLO0f["users_id"]);


  //if(empty($_Jj08l["UserDefinedFormsFolder"]))
     $_QLJfI = join("", file(_LOC8P()."default_show_saved_data_page.htm"));
  //   else
  //   $_QLJfI = join("", file( _LPC1C(InstallPath.$_Jj08l["UserDefinedFormsFolder"])."default_show_saved_data_page.htm"));


  // no PDF support for PHP < 5.4
  if( version_compare(PHP_VERSION, "5.4.0", "<") ) {
    $_QLJfI = _L80DF($_QLJfI, '<!--PHPGT54-->', '<!--/PHPGT54-->');
  }

  //
  $_QLJfI = str_replace("var UnsubscribeConfirmationText;", "var UnsubscribeConfirmationText='".$resourcestrings[$INTERFACE_LANGUAGE]["ConfirmUnsubscribeFromAllMailLists"]."';", $_QLJfI);
  $_QLJfI = str_replace("var UnsubscribeConfirmationTitle;", "var UnsubscribeConfirmationTitle='".$resourcestrings[$INTERFACE_LANGUAGE]["ConfirmUnsubscribeFromAllMailListsTitle"]."';", $_QLJfI);

  if($_Jj08l["PrivacyPolicyURL"] == ""){
    $_QLJfI = _L80DF($_QLJfI, '<if:PrivacyPolicyURL>', '</if:PrivacyPolicyURL>');
  } else{
    $_QLJfI = _L81BJ($_QLJfI, '<PrivacyPolicyURL>', '</PrivacyPolicyURL>', $_Jj08l["PrivacyPolicyURL"]);
    $_QLJfI = _L8OF8($_QLJfI, '<if:PrivacyPolicyURL>');
  }

  if(!empty($_JCC81["Firm"]))
    $_JCC81["Firm_OR_Name"] = $_JCC81["Firm"];
    else
    $_JCC81["Firm_OR_Name"] = $_JCC81["FirstName"]." ".$_JCC81["LastName"];

  $_JCC81["CurrentDate"] = date($ShortDateFormat);

  foreach($_JCC81 as $key => $_QltJO)
    if(isset($_QltJO))
      $_QLJfI = str_replace("[$key]", $_QltJO, $_QLJfI);

  $_QLfol = "SELECT `u_EMail` FROM `$_I8I6o` WHERE `IdentString`="._LRAFO($_JC6Jf);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0){
     _LJD1D($commonmsgAnErrorOccured, $_Jj08l["EMailAddressNotInList"]);
  }
  $_8JQQ6 = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);


  $_8JQJf = true;
  if(! ($_JCC1C == "Allowed" || $_JCC1C == "UnsubscribeOnly"))
    $_8JQJf = false;

  $_8JI1L = _L81DB($_QLJfI, "<MailingList:Row_Separator>", "</MailingList:Row_Separator>");
  $_QLJfI = _L80DF($_QLJfI, "<MailingList:Row_Separator>", "</MailingList:Row_Separator>");
  $_I80Jo = _L81DB($_QLJfI, "<MailingList:Row>", "</MailingList:Row>");
  $_QLoli = "";

  // use ever yyyy-mm-dd
  $_j01CJ = "'%d.%m.%Y'";
  $_fiJIt = "'%d.%m.%Y %H:%i:%s'";
  if($INTERFACE_LANGUAGE != "de") {
     $_j01CJ = "'%Y-%m-%d'";
     $_fiJIt = "'%Y-%m-%d %H:%i:%s'";
  }

  //
  $_8JIiC = array();
  $_QLfol = "SELECT `text`, `fieldname` FROM `$_Ij8oL` WHERE `language`="._LRAFO($INTERFACE_LANGUAGE);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_fifQL = mysql_fetch_assoc($_QL8i1)) {
    if($_fifQL["fieldname"] == "u_EMail") continue;
    if(strpos($_fifQL["fieldname"], "u_Business") !== false && strpos($_fifQL["text"], " ") !== false) {
      $_fifQL["text"] = substr($_fifQL["text"], 0, strpos($_fifQL["text"], " "));
    }
    if($_fifQL["fieldname"] == "u_Birthday")
      $_fifQL["fieldname"] = "u_BirthdayFormatted";
    $_8JIiC[$_fifQL["fieldname"]] = $_fifQL["text"];
  }
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `SubscriptionUnsubscription`, `Name`, `MaillistTableName`, `MailListToGroupsTableName`, `FormsTableName`, `GroupsTableName` FROM `$_QL88I` WHERE `users_id`=$_j6lIj";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)){

    $_QLlO6 = "DATE_FORMAT(DateOfSubscription, $_fiJIt) AS DateOfSubscriptionFormatted";
    $_QLlO6 .= ", DATE_FORMAT(DateOfOptInConfirmation, $_fiJIt) AS DateOfOptInConfirmationFormatted";
    $_QLlO6 .= ", DATE_FORMAT(LastEMailSent, $_fiJIt) AS LastEMailSentFormatted";
    $_QLlO6 .= ", DATE_FORMAT(LastChangeDate, $_fiJIt) AS LastChangeDateFormatted";
    $_QLlO6 .= ", IF(u_Birthday <> '0000-00-00', DATE_FORMAT(u_Birthday, $_j01CJ), '') AS u_BirthdayFormatted";


    $_QLfol = "SELECT *, $_QLlO6 FROM `$_QLO0f[MaillistTableName]` WHERE `u_EMail`="._LRAFO($_8JQQ6["u_EMail"]);
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
      if( !($_QLO0f["SubscriptionUnsubscription"] == "Allowed" || $_QLO0f["SubscriptionUnsubscription"] == "UnsubscribeOnly"))
        $_8JQJf = false;

      if(empty($_QLoli))
        $_QLoli .= $_I80Jo;
        else
        $_QLoli .= $_8JI1L. $_I80Jo;

      $_QLoli = str_ireplace("[MailListsName]", $_QLO0f["Name"], $_QLoli);
      if($_I1OfI["IsActive"])
        $_I1OfI["IsMemberActive"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
        else
        $_I1OfI["IsMemberActive"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

      if($_I1OfI["PrivacyPolicyAccepted"])
        $_I1OfI["PrivacyPolicyAccepted"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
        else
        $_I1OfI["PrivacyPolicyAccepted"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

      if($_I1OfI["LastEMailSentFormatted"] == "")
         $_I1OfI["LastEMailSentFormatted"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];

      $_QLfol = "SELECT $_QLO0f[GroupsTableName].Name FROM $_QLO0f[MailListToGroupsTableName] LEFT JOIN $_QLO0f[GroupsTableName] ON groups_id=id WHERE Member_id=$_I1OfI[id]";
      $_8JjfQ = mysql_query($_QLfol, $_QLttI);
      $_I1OfI["Groups"] = array();
      while($_8JjLI = mysql_fetch_row($_8JjfQ)) {
        $_I1OfI["Groups"] [] = $_8JjLI[0];
      }
      mysql_free_result($_8JjfQ);
      if(count($_I1OfI["Groups"]) == 0)
        $_I1OfI["Groups"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];
        else
        $_I1OfI["Groups"] = join(" ,", $_I1OfI["Groups"]);
      foreach($_I1OfI as $key => $_QltJO)
         if(isset($_QltJO))
           $_QLoli = str_replace("[$key]", $_QltJO, $_QLoli);

      $_8JJJI = "";
      $_8JJiJ = _L81DB($_QLoli, "<MailingList:Row_Fields>", "</MailingList:Row_Fields>");
      foreach($_8JIiC as $key => $_IOCjL){
        if(!isset($_I1OfI[$key])) continue;
        $_QltJO = $_I1OfI[$key];
        if(strpos($key, "u_UserFieldInt") !== false && !$_QltJO) continue;
        if(strpos($key, "u_UserFieldB") !== false && !$_QltJO) continue;
        if(trim($_QltJO) != "" && $_QltJO != "undefined" && isset($_8JIiC[$key])){
          if($key == "u_Comments")
            $_QltJO = preg_replace("/\r\n|\r|\n/", "<br />", $_QltJO);
          if(strpos($key, "u_UserFieldB") !== false || $key == "u_PersonalizedTracking"){
            if($_QltJO) $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
            if(!$_QltJO) $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
          }
          $_8JJJI .= $_8JJiJ;
          $_8JJJI = str_replace("[FieldName]", $_8JIiC[$key], $_8JJJI);
          $_8JJJI = str_replace("[FieldValue]", $_QltJO, $_8JJJI);
        }
      }
      $_QLoli = _L81BJ($_QLoli, "<MailingList:Row_Fields>", "</MailingList:Row_Fields>", $_8JJJI);
    }
    mysql_free_result($_I1O6j);
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<MailingList:Row>", "</MailingList:Row>", $_QLoli);

  if($_8JQJf){
     $_QLJfI = _L8OF8($_QLJfI, "<IF:UNSUBSCRIBE_ALLOWED>");
     }else{
       $_QLJfI = _L80DF($_QLJfI, '<IF:UNSUBSCRIBE_ALLOWED>', '</IF:UNSUBSCRIBE_ALLOWED>');
   }

  _JJCCF($_QLJfI);

  if($Action == "PDFDownloadBtn"){

     $_QLJfI = _L80DF($_QLJfI, "<EXCLUDE_FROM_PDF>", "</EXCLUDE_FROM_PDF>");

     require_once("pdf.inc.php");

     _J0BCL($_QLJfI, 'information.pdf', strtolower($INTERFACE_LANGUAGE), html_entity_decode(_LPFR0("<title>", "</title>", $_QLJfI), ENT_COMPAT, $_QLo06), html_entity_decode(_LPFR0("<title>", "</title>", $_QLJfI), ENT_COMPAT, $_QLo06), $_JCC81["Firm_OR_Name"], $AppName." ".$_foo18);

     exit;

  } else{
     $_QLJfI = _L8OF8($_QLJfI, "<EXCLUDE_FROM_PDF>");
  }

  SetHTMLHeaders($_QLo06);
  print $_QLJfI;

  function _LJD1D($_J0COJ, $_JCIO0) {
    global $AppName, $_QLo06, $_Jj08l;

    SetHTMLHeaders($_QLo06);

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
