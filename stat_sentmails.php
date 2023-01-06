<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
 include_once("sessioncheck.inc.php");
 include_once("templates.inc.php");
 include_once("functions.inc.php");
 include_once("mailer.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMailsSentStatBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_Itfj8 = "";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000730"], $_Itfj8, 'stat_sentmails', 'mailssentstat_snipped.htm');

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);
  // use ever yyyy-mm-dd
  $_j01CJ = "'%d.%m.%Y'";
  $_fJtjj = "'%Y-%m-%d'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);
     $_j01CJ = "'%Y-%m-%d'";
  }

  if( !isset($_POST["startdate"]) || !isset($_POST["enddate"]) ) {
    $_QLfol = "SELECT DATE_FORMAT(CONCAT(YEAR(NOW()), '-', MONTH(NOW()), '-', '1'), $_j01CJ) ";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Joi6C = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);

    if ( !isset($_POST["startdate"]) )
       $_POST["startdate"] = $_Joi6C[0];


    for($_Qli6J=31; $_Qli6J>27; $_Qli6J--) { #31, 30, 29, 28
      $_QLfol = "SELECT DATE_FORMAT(CONCAT(YEAR(NOW()), '-', MONTH(NOW()), '-', '$_Qli6J'), $_j01CJ) ";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_Joi6C = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      if($_Joi6C[0] != NULL) break;
    }

    if ( !isset($_POST["enddate"]) )
       $_POST["enddate"] = $_Joi6C[0];
  }

  $_QLJfI = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QLJfI);
  $_QLJfI = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QLJfI);

  // *********** Period statistics
  $_JoiCQ = "";
  $_JoL0L = "";

  if($INTERFACE_LANGUAGE != "de") {
    $_JoiCQ = $_POST["startdate"];
    $_JoL0L = $_POST["enddate"];
  } else {
    $_I1OoI = explode('.', $_POST["startdate"]);
    $_JoiCQ = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
    $_I1OoI = explode('.', $_POST["enddate"]);
    $_JoL0L = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
  }

  $_Iflj0 = array();
  _L8EOB($_JQQiJ, $_Iflj0, array("id", "MailDate"));

  $_IOJoI = array();
  for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
    if($_Iflj0[$_Qli6J] != "EventResponderEMailCount" && $_Iflj0[$_Qli6J] != "EditConfirmedMailCount")
       $_IOJoI[] = "SUM($_Iflj0[$_Qli6J]) AS SUM". strtoupper( $_Iflj0[$_Qli6J] );
  }
  $_IOJoI = join(", ", $_IOJoI);

  $_QLfol = "SELECT $_IOJoI FROM $_JQQiJ WHERE (MailDate >= "._LRAFO($_JoiCQ).") AND (MailDate <= "._LRAFO($_JoL0L).")";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_8tfO1 = 0;
  foreach($_Ift08 as $key => $_QltJO){
     if($_QltJO == NULL) {
       $_QltJO = 0;
       $_Ift08[$key] = $_QltJO;
     }
     $_8tfO1 += $_QltJO;
     $_QLJfI = _L81BJ($_QLJfI, '<LIST:'.$key.'>', '</LIST:'.$key.'>', $_QltJO);
  }
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:SUMMAILSSENT>', '</LIST:SUMMAILSSENT>', $_8tfO1);


  //

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QLJfI = addCultureInfo($_QLJfI);
  // addCultureInfo /

  # Set chart attributes
  $_QLJfI = str_replace("SENTMAILSTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000731"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("SENTMAILSAXISXTITLE", "", $_QLJfI);
  $_QLJfI = str_replace("SENTMAILSAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_QLo06), $_QLJfI);

  $_JCQoQ = array();
  reset($_Ift08);
  $_JolCi = 0;
  foreach($_Ift08 as $key => $_QltJO){
    if(!defined("SWM")) {
      if($key == strtoupper("SUMTestEMailCount") || $key == strtoupper("SUMAdminNotifyMailCount") || $key == strtoupper("SUMOptInConfirmationMailCount") || $key == strtoupper("SUMOptInConfirmedMailCount")
         || $key == strtoupper("SUMOptOutConfirmationMailCount") || $key == strtoupper("SUMOptOutConfirmedMailCount") || $key == strtoupper("SUMEditConfirmationMailCount") || $key == strtoupper("SUMEditConfirmedMailCount") ) {
           $_JC0jO = array("label" => $resourcestrings[$INTERFACE_LANGUAGE][ strtoupper($key) ], "y" => $_QltJO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
           $_JCQoQ[] = $_JC0jO;
           if($_QltJO > $_JolCi)
             $_JolCi = $_QltJO;
         }
    }
    if(defined("SWM")) {
      $_JC0jO = array("label" => $resourcestrings[$INTERFACE_LANGUAGE][ strtoupper($key) ], "y" => $_QltJO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_JCQoQ[] = $_JC0jO;
      if($_QltJO > $_JolCi)
         $_JolCi = $_QltJO;
    }
    if(!defined("SWM") && $key == "SUMDISTRIBLISTEMAILCOUNT") {
      $_JC0jO = array("label" => $resourcestrings[$INTERFACE_LANGUAGE][ strtoupper($key) ], "y" => $_QltJO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_JCQoQ[] = $_JC0jO;
      if($_QltJO > $_JolCi)
         $_JolCi = $_QltJO;
    }
  }

  $_QLJfI = str_replace("/* SENTMAILS_DATA */", _LAFFB($_JCQoQ, JSON_NUMERIC_CHECK), $_QLJfI);

  if($_JolCi < 10){
     // set interval 1
     $_QLJfI = str_replace("/*SENTMAILSCHARTINTERVAL", "", $_QLJfI);
     $_QLJfI = str_replace("SENTMAILSCHARTINTERVAL*/", "", $_QLJfI);
  }

  print $_QLJfI;

?>
