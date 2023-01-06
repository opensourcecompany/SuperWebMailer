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

  if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "HEAD"){
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D($_J0COJ, $_JCIO0);
  }

  if( !isset($_POST["MailingListId"]) || !isset($_POST["FormId"]) || intval($_POST["MailingListId"]) == 0 || intval($_POST["FormId"]) == 0 ){
        $_JCIO0 = $commonmsgNoParameters;
        _LJD1D($_J0COJ, $_JCIO0);
  }

  $MailingListId = intval($_POST["MailingListId"]);
  $FormId = intval($_POST["FormId"]);

  $_QLfol = "SELECT `users_id`, `MaillistTableName`, `FormsTableName`, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName` FROM `$_QL88I` WHERE `id`=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_JCIO0 = $commonmsgMailListNotFound;
    _LJD1D($_J0COJ, $_JCIO0);
  }

  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_jQIIl = $_QLO0f["ReasonsForUnsubscripeTableName"];
  $_jQIt6 = $_QLO0f["ReasonsForUnsubscripeStatisticsTableName"];

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
  _JQRLR($INTERFACE_LANGUAGE);
  _JOLFC();
  // ***

  $_QLfol = "SELECT $_IfJoo.*, $_IfJoo.id AS FormId, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=".intval($FormId);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_JCIO0 = $commonmsgHTMLFormNotFound;
    _LJD1D($_J0COJ, $_JCIO0);
  }

  $_Jj08l = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  // we need this for confirmation string
  $_Jj08l["MailingListId"] = $MailingListId;
  $_Jj08l["FormId"] = $FormId;
  $_Jj08l["ReasonsForUnsubscripeTableName"] = $_jQIIl;
  $_Jj08l["ReasonsForUnsubscripeStatisticsTableName"] = $_jQIt6;


  # set theme and language for correct template
  $INTERFACE_STYLE = $_Jj08l["Theme"];
  $INTERFACE_LANGUAGE = $_Jj08l["Language"];

  _JQRLR($INTERFACE_LANGUAGE);

   // set the user paths for images, attachments....
  _LRRFJ($_QLO0f["users_id"]);

  $_fi1it = array();

  $_fiQi0 = _J11L6(intval($_POST["ReasonsForUnsubscripe_id"]));
  if(isset($_POST["ReasonsForUnsubscripe_id"]) && intval($_POST["ReasonsForUnsubscripe_id"]) != 0 &&  $_fiQi0 != "" ){
    $_QLfol = "INSERT INTO `$_jQIt6` SET `ReasonsForUnsubscripe_id`=".intval($_POST["ReasonsForUnsubscripe_id"]);
    $_QLfol .= ", `VoteDate`=NOW()";
    if(isset($_POST["ReasonText"]) && trim($_POST["ReasonText"]) != ""){
       if(!IsUtf8String($_POST["ReasonText"]))
          $_POST["ReasonText"] = ConvertString("iso-8859-1", $_QLo06, $_POST["ReasonText"], false);
       $_QLfol .= ", `ReasonText`="._LRAFO(trim(_LC6CP(_LBDA8(CleanUpHTML($_POST["ReasonText"])))));
    }
    mysql_query($_QLfol, $_QLttI);
    //_L8D88($_QLfol);

    if($_Jj08l["ExternalReasonForUnsubscriptionScript"] != ""){
      $_fi1it["MailingListId"] = $MailingListId;
      $_fi1it["FormId"] = $FormId;
      $_fi1it["Reason"] = $_fiQi0;
      if(isset($_POST["ReasonText"]) && trim($_POST["ReasonText"]) != "")
         $_fi1it["ReasonText"] = trim($_POST["ReasonText"]);
      foreach($_POST as $key => $_QltJO){
        $_QlOjt = strpos($key, "u_");
        if($_QlOjt !== false && $_QlOjt == 0)
           $_fi1it[$key] = $_QltJO;
      }
    }

  }

  if(count($_fi1it))
    _J0R06(0, array(), $_fi1it, "ReasonForUnsubscriptionVote", $_Jj08l);

  $_I816i = array();
  _J06JO(0, "", "RFUSurveyConfirmationPage", "", $_Jj08l, $_I816i);


  function _J11L6($_fiIJ0){
    global $_jQIIl, $_QLttI;
    $_QLfol = "SELECT Reason FROM $_jQIIl WHERE id=$_fiIJ0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QLO0f = mysql_fetch_row($_QL8i1)){
      return $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }

    return "";
  }

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
