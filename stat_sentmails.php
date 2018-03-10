<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMailsSentStatBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_I0600 = "";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000730"], $_I0600, 'stat_sentmails', 'mailssentstat_snipped.htm');

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);
  // use ever yyyy-mm-dd
  $_If0Ql = "'%d.%m.%Y'";
  $_Jlj0J = "'%Y-%m-%d'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);
     $_If0Ql = "'%Y-%m-%d'";
  }

  if( !isset($_POST["startdate"]) || !isset($_POST["enddate"]) ) {
    $_QJlJ0 = "SELECT DATE_FORMAT(CONCAT(YEAR(NOW()), '-', MONTH(NOW()), '-', '1'), $_If0Ql) ";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_jC18j = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);

    if ( !isset($_POST["startdate"]) )
       $_POST["startdate"] = $_jC18j[0];


    for($_Q6llo=31; $_Q6llo>27; $_Q6llo--) { #31, 30, 29, 28
      $_QJlJ0 = "SELECT DATE_FORMAT(CONCAT(YEAR(NOW()), '-', MONTH(NOW()), '-', '$_Q6llo'), $_If0Ql) ";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_jC18j = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      if($_jC18j[0] != NULL) break;
    }

    if ( !isset($_POST["enddate"]) )
       $_POST["enddate"] = $_jC18j[0];
  }

  $_QJCJi = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QJCJi);
  $_QJCJi = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QJCJi);

  // *********** Period statistics
  $_jC1lo = "";
  $_jCQ0I = "";

  if($INTERFACE_LANGUAGE != "de") {
    $_jC1lo = $_POST["startdate"];
    $_jCQ0I = $_POST["enddate"];
  } else {
    $_Q8otJ = explode('.', $_POST["startdate"]);
    $_jC1lo = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
    $_Q8otJ = explode('.', $_POST["enddate"]);
    $_jCQ0I = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
  }

  $_QLLjo = array();
  _OAJL1($_jJ6f0, $_QLLjo, array("id", "MailDate"));

  $_I16jJ = array();
  for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
    if($_QLLjo[$_Q6llo] != "EventResponderEMailCount" && $_QLLjo[$_Q6llo] != "EditConfirmedMailCount")
       $_I16jJ[] = "SUM($_QLLjo[$_Q6llo]) AS SUM". strtoupper( $_QLLjo[$_Q6llo] );
  }
  $_I16jJ = join(", ", $_I16jJ);

  $_QJlJ0 = "SELECT $_I16jJ FROM $_jJ6f0 WHERE (MailDate >= "._OPQLR($_jC1lo).") AND (MailDate <= "._OPQLR($_jCQ0I).")";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_6lClf = 0;
  foreach($_QL8Q8 as $key => $_Q6ClO){
     if($_Q6ClO == NULL) {
       $_Q6ClO = 0;
       $_QL8Q8[$key] = $_Q6ClO;
     }
     $_6lClf += $_Q6ClO;
     $_QJCJi = _OPR6L($_QJCJi, '<LIST:'.$key.'>', '</LIST:'.$key.'>', $_Q6ClO);
  }
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:SUMMAILSSENT>', '</LIST:SUMMAILSSENT>', $_6lClf);


  //

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QJCJi = addCultureInfo($_QJCJi);
  // addCultureInfo /

  # Set chart attributes
  $_QJCJi = str_replace("SENTMAILSTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000731"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("SENTMAILSAXISXTITLE", "", $_QJCJi);
  $_QJCJi = str_replace("SENTMAILSAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_Q6QQL), $_QJCJi);

  $_jCfit = array();
  reset($_QL8Q8);
  $_jC6QL = 0;
  foreach($_QL8Q8 as $key => $_Q6ClO){
    if(!defined("SWM")) {
      if($key == strtoupper("SUMTestEMailCount") || $key == strtoupper("SUMAdminNotifyMailCount") || $key == strtoupper("SUMOptInConfirmationMailCount") || $key == strtoupper("SUMOptInConfirmedMailCount")
         || $key == strtoupper("SUMOptOutConfirmationMailCount") || $key == strtoupper("SUMOptOutConfirmedMailCount") || $key == strtoupper("SUMEditConfirmationMailCount") || $key == strtoupper("SUMEditConfirmedMailCount") ) {
           $_jC6IQ = array("label" => $resourcestrings[$INTERFACE_LANGUAGE][ strtoupper($key) ], "y" => $_Q6ClO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
           $_jCfit[] = $_jC6IQ;
           if($_Q6ClO > $_jC6QL)
             $_jC6QL = $_Q6ClO;
         }
    }
    if(defined("SWM")) {
      $_jC6IQ = array("label" => $resourcestrings[$INTERFACE_LANGUAGE][ strtoupper($key) ], "y" => $_Q6ClO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_jCfit[] = $_jC6IQ;
      if($_Q6ClO > $_jC6QL)
         $_jC6QL = $_Q6ClO;
    }
    if(!defined("SWM") && $key == "SUMDISTRIBLISTEMAILCOUNT") {
      $_jC6IQ = array("label" => $resourcestrings[$INTERFACE_LANGUAGE][ strtoupper($key) ], "y" => $_Q6ClO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
      $_jCfit[] = $_jC6IQ;
      if($_Q6ClO > $_jC6QL)
         $_jC6QL = $_Q6ClO;
    }
  }

  $_QJCJi = str_replace("/* SENTMAILS_DATA */", _OCR88($_jCfit, JSON_NUMERIC_CHECK), $_QJCJi);

  if($_jC6QL < 10){
     // set interval 1
     $_QJCJi = str_replace("/*SENTMAILSCHARTINTERVAL", "", $_QJCJi);
     $_QJCJi = str_replace("SENTMAILSCHARTINTERVAL*/", "", $_QJCJi);
  }

  print $_QJCJi;

?>
