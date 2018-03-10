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
        $_jCO1J = $commonmsgNoParameters;
        _ORECR($_jj0JO, $_jCO1J);
  }

  if( !isset($_POST["MailingListId"]) || !isset($_POST["FormId"]) || intval($_POST["MailingListId"]) == 0 || intval($_POST["FormId"]) == 0 ){
        $_jCO1J = $commonmsgNoParameters;
        _ORECR($_jj0JO, $_jCO1J);
  }

  $MailingListId = intval($_POST["MailingListId"]);
  $FormId = intval($_POST["FormId"]);

  $_QJlJ0 = "SELECT `users_id`, `MaillistTableName`, `FormsTableName`, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName` FROM `$_Q60QL` WHERE `id`=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    $_jCO1J = $commonmsgMailListNotFound;
    _ORECR($_jj0JO, $_jCO1J);
  }

  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_I8Jtl = $_Q6Q1C["ReasonsForUnsubscripeTableName"];
  $_I86jt = $_Q6Q1C["ReasonsForUnsubscripeStatisticsTableName"];

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
  _LQLRQ($INTERFACE_LANGUAGE);
  _LQF1R();
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
  $_j6ioL["ReasonsForUnsubscripeTableName"] = $_I8Jtl;
  $_j6ioL["ReasonsForUnsubscripeStatisticsTableName"] = $_I86jt;


  # set theme and language for correct template
  $INTERFACE_STYLE = $_j6ioL["Theme"];
  $INTERFACE_LANGUAGE = $_j6ioL["Language"];

  _LQLRQ($INTERFACE_LANGUAGE);

   // set the user paths for images, attachments....
  _OP0AF($_Q6Q1C["users_id"]);

  $_6Jojj = array();

  $_6JC0o = _L0E8E(intval($_POST["ReasonsForUnsubscripe_id"]));
  if(isset($_POST["ReasonsForUnsubscripe_id"]) && intval($_POST["ReasonsForUnsubscripe_id"]) != 0 &&  $_6JC0o != "" ){
    $_QJlJ0 = "INSERT INTO `$_I86jt` SET `ReasonsForUnsubscripe_id`=".intval($_POST["ReasonsForUnsubscripe_id"]);
    $_QJlJ0 .= ", `VoteDate`=NOW()";
    if(isset($_POST["ReasonText"]) && trim($_POST["ReasonText"]) != ""){
       if(!IsUtf8String($_POST["ReasonText"]))
          $_POST["ReasonText"] = ConvertString("iso-8859-1", $_Q6QQL, $_POST["ReasonText"], false);
       $_QJlJ0 .= ", `ReasonText`="._OPQLR(trim(_ODQAB(CleanUpHTML($_POST["ReasonText"]))));
    }
    mysql_query($_QJlJ0, $_Q61I1);
    //_OAL8F($_QJlJ0);

    if($_j6ioL["ExternalReasonForUnsubscriptionScript"] != ""){
      $_6Jojj["MailingListId"] = $MailingListId;
      $_6Jojj["FormId"] = $FormId;
      $_6Jojj["Reason"] = $_6JC0o;
      if(isset($_POST["ReasonText"]) && trim($_POST["ReasonText"]) != "")
         $_6Jojj["ReasonText"] = trim($_POST["ReasonText"]);
      foreach($_POST as $key => $_Q6ClO){
        $_Q6i6i = strpos($key, "u_");
        if($_Q6i6i !== false && $_Q6i6i == 0)
           $_6Jojj[$key] = $_Q6ClO;
      }
    }

  }

  if(count($_6Jojj))
    _L08DC(0, array(), $_6Jojj, "ReasonForUnsubscriptionVote", $_j6ioL);

  $_Ql1O8 = array();
  _L08LL(0, "", "RFUSurveyConfirmationPage", "", $_j6ioL, $_Ql1O8);


  function _L0E8E($_6JC1j){
    global $_I8Jtl, $_Q61I1;
    $_QJlJ0 = "SELECT Reason FROM $_I8Jtl WHERE id=$_6JC1j";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q6Q1C = mysql_fetch_row($_Q60l1)){
      return $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }

    return "";
  }

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
