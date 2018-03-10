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
  include_once("sessioncheck.inc.php");
  include_once("mailinglistq.inc.php");
  include_once("onlineupdate.inc.php");
  include_once("templates.inc.php");
  include_once("localmessages_ops.inc.php");


  $_QJlJ0 = "SELECT DISTINCT `Name`, `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_Q60QL`";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QJlJ0 .= " WHERE (`users_id`=$UserId)";
     else {
      $_QJlJ0 .= " LEFT JOIN `$_Q6fio` ON `$_Q60QL`.`id`=`$_Q6fio`.`maillists_id` WHERE (`$_Q6fio`.`users_id`=$UserId) AND (`$_Q60QL`.`users_id`=$OwnerUserId)";
     }
  $_QJlJ0 .= " ORDER BY `Name` ASC";

  $_jol0I = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  // *********** Total statistics
  $_jol0f = mysql_num_rows($_jol0I);
  $_QLJfj = 0;
  $_QLJtj = 0;
  $_QL61I = 0;
  $_QL6Lj = 0;
  $_QLfjO = 0;
  while( $_Q6Q1C=mysql_fetch_assoc($_jol0I) ) {
    $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QLJfj += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(id) AS Total FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptInConfirmationPending'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QL6Lj += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(id) AS Total FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptOutConfirmationPending'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QLfjO += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QLJtj += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6Q1C[LocalBlocklistTableName]`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QL61I += $_QL8Q8[0];
  }

  $_QJlJ0 = "SELECT COUNT(id) FROM `$_Ql8C0`";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_QL8Q8=mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QL61I += $_QL8Q8[0];

  $_I0600 = "";
  $_jolLf = "";
  $_jC0t8 = 0;
  if( _L0P8D($_jolLf, $_jC0t8, $id, $OwnerUserId == 0) ) {
    if ( $_jolLf != "" && $_jC0t8 != 0 && $OwnerUserId == 0 ) {
       if ( version_compare($_QoJ8j, $_jolLf, "<") ) {
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["UpdateAvailable"];
         $_I0600 = _LJ81E($_I0600);
         $_I0600 = str_replace("%NEWVERSION%", $_jolLf, $_I0600);
         $_I0600 = str_replace("%NEWVERSIONDATE%", strftime("%x", $_jC0t8), $_I0600);
         _OELQQ(0, $UserId, $resourcestrings[$INTERFACE_LANGUAGE]["UpdateAvailableSubject"], '<p>'.$_I0600.'</p>', array());
       }
    }
  } else {
    mysql_query("DELETE FROM `$_Q88iO` WHERE `id`=$id", $_Q61I1);
  }

  if ( ($UserType == "SuperAdmin") ) {
    include_once("browseusers.php");
    exit;
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000007"], $_I0600, 'interface', 'dashboard_snipped.htm');

  $_QJCJi = _OPR6L($_QJCJi, '<MAILINGLIST:TOTAL>', '</MAILINGLIST:TOTAL>', $_jol0f);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:TOTALACTIVE>', '</LIST:TOTALACTIVE>', $_QLJfj);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:TOTALINACTIVE>', '</LIST:TOTALINACTIVE>', $_QLJtj);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_QL61I);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:TOTALOPTINUNCONFIRMED>', '</LIST:TOTALOPTINUNCONFIRMED>', $_QL6Lj);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:TOTALOPTOUTUNCONFIRMED>', '</LIST:TOTALOPTOUTUNCONFIRMED>', $_QLfjO);

  // *********** Total statistics END

  //

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_QJlJ0 = "SELECT DATE_FORMAT(NOW(), $_If0Ql), DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 5 DAY), $_If0Ql)";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_jC18j = mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);

  // *********** Period statistics
  $_jC1lo = $_jC18j[1];
  $_jCQ0I = $_jC18j[0];

  if($INTERFACE_LANGUAGE == "de") {
    $_Q8otJ = explode('.', $_jC1lo);
    $_jC1lo = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
    $_Q8otJ = explode('.', $_jCQ0I);
    $_jCQ0I = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
  }

  // CHART
  $_II1Ot = array();

  // set 5 days to 0
  for($_Q6llo=0; $_Q6llo<=5; $_Q6llo++) {
    $_QJlJ0 = "SELECT DATE_FORMAT(DATE_ADD('$_jC1lo', INTERVAL $_Q6llo DAY), '%Y-%m-%d')";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_jtO66 = mysql_fetch_row($_Q60l1);
    if (! isset($_II1Ot[$_jtO66[0]]) )
       $_II1Ot[$_jtO66[0]] = array (0, 0, 0, 0, 0);
    mysql_free_result($_Q60l1);
  }

  // $resourcestrings[$INTERFACE_LANGUAGE]["ChartNoDataText"]
  /// $resourcestrings[$INTERFACE_LANGUAGE]["PBarLoadingText"]

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QJCJi = addCultureInfo($_QJCJi);
  // addCultureInfo /

  # Set chart attributes
  $_QJCJi = str_replace("SUBUNSUBCHARTTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000027"]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000371"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("SUBUNSUBCHARTAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("SUBUNSUBCHARTAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_Q6QQL), $_QJCJi);

  $_jC1lo .= " 00:00:00";
  $_jCQ0I .= " 23:59:59";

  if( mysql_num_rows($_jol0I) > 0)
     mysql_data_seek($_jol0I, 0);

  while( $_Q6Q1C=mysql_fetch_array($_jol0I) ) {
    $_QJlJ0 = "SELECT COUNT(ActionDate) AS Counter, DATE_FORMAT(ActionDate, $_If0Ql) AS ADate, DATE_FORMAT(ActionDate, '%Y-%m-%d') AS ADateEng, Action FROM $_Q6Q1C[StatisticsTableName] WHERE (Action='OptInConfirmationPending' OR Action='Subscribed' OR Action='Unsubscribed' OR Action='OptOutConfirmationPending' OR Action='Bounced') AND (ActionDate >= '$_jC1lo') AND (ActionDate <= '$_jCQ0I') GROUP BY Action, ADateEng ORDER BY ADate ASC, Action";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    if($_Q60l1 && mysql_num_rows($_Q60l1) > 0 ) {
      while ($_I1COO = mysql_fetch_array($_Q60l1) ) {
        if (! isset($_II1Ot[$_I1COO["ADateEng"]]) )
           $_II1Ot[$_I1COO["ADateEng"]] = array (0, 0, 0, 0, 0);
      }

      mysql_data_seek($_Q60l1, 0);
      while ($_I1COO = mysql_fetch_array($_Q60l1) ) {
        if($_I1COO["Action"] == 'OptInConfirmationPending')
           $_II1Ot[$_I1COO["ADateEng"]][0] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'Subscribed')
           $_II1Ot[$_I1COO["ADateEng"]][1] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'OptOutConfirmationPending')
           $_II1Ot[$_I1COO["ADateEng"]][2] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'Unsubscribed')
           $_II1Ot[$_I1COO["ADateEng"]][3] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'Bounced')
           $_II1Ot[$_I1COO["ADateEng"]][4] += $_I1COO["Counter"];
      }
    }
    mysql_free_result($_Q60l1);
  }

  ksort ($_II1Ot, SORT_STRING);


  $_jCQOI = array();
  $_jCI6f = array();
  $_jCIi0 = array();
  $_jCjOI = array();
  $_jCJj8 = array();

  $_jC6QL = 0;
  reset($_II1Ot);
  foreach ($_II1Ot as $key => $_Q6ClO) {

    if($INTERFACE_LANGUAGE == "de") {
      $key = substr($key, 8).".".substr($key, 5, 2).".".substr($key, 0, 4);
    }

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[0]);
    $_jCQOI[] = $_jC6IQ;
    if($_Q6ClO[0] > $_jC6QL)
      $_jC6QL = $_Q6ClO[0];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[1]);
    $_jCI6f[] = $_jC6IQ;
    if($_Q6ClO[1] > $_jC6QL)
      $_jC6QL = $_Q6ClO[1];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[2]);
    $_jCIi0[] = $_jC6IQ;
    if($_Q6ClO[2] > $_jC6QL)
      $_jC6QL = $_Q6ClO[2];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[3]);
    $_jCjOI[] = $_jC6IQ;
    if($_Q6ClO[3] > $_jC6QL)
      $_jC6QL = $_Q6ClO[3];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[4]);
    $_jCJj8[] = $_jC6IQ;
    if($_Q6ClO[4] > $_jC6QL)
      $_jC6QL = $_Q6ClO[4];
  }

  $_Qf1i1 = array();

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000180"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCQOI);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000025"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCI6f);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000181"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCIi0);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000026"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCjOI);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000169"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCJj8);
  $_Qf1i1[] = $entry;

  $_QJCJi = str_replace("/* SUBUNSUBCHART_DATA */", _OCR88($_Qf1i1, JSON_NUMERIC_CHECK), $_QJCJi);

  if($_jC6QL < 10){
     // set interval 1
     $_QJCJi = str_replace("/*SUBUNSUBCHARTINTERVAL", "", $_QJCJi);
     $_QJCJi = str_replace("SUBUNSUBCHARTINTERVAL*/", "", $_QJCJi);
  }

  /////////

  $_QJlJ0 = "SELECT `id`, `Name`, `CurrentSendTableName` FROM `$_Q6jOo`";
  if($OwnerUserId != 0) {
     $_QJlJ0 .= " LEFT JOIN `$_Q6fio` ON `$_Q6fio`.`maillists_id`=`$_Q6jOo`.`maillists_id`";
  }
  $_QJlJ0 .= " WHERE `SetupLevel`=99 AND `SendScheduler` <> 'SaveOnly'";

  if($OwnerUserId != 0) {
   $_QJlJ0 .= " AND `$_Q6fio`.`users_id`=$UserId";
  }

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_jCf0Q = array();
  while($_Q60l1 && $_Q6J0Q = mysql_fetch_assoc($_Q60l1)) {
    $_j0fti = $_Q6J0Q["CurrentSendTableName"];
    $_j06O8 = $_Q6J0Q["Name"];
    $_QJlJ0 = "SELECT *, DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS EndSendDateTimeFormated FROM `$_j0fti` WHERE `SendState`='DONE' ORDER BY `EndSendDateTime` DESC LIMIT 0, 1";
    $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
    if($_ItlJl && mysql_num_rows($_ItlJl) > 0){
       $_Q6Q1C = mysql_fetch_assoc($_ItlJl);
       $_Q6Q1C["Name"] = $_Q6J0Q["Name"];
       $_Q6Q1C["CampaignId"] = $_Q6J0Q["id"];
       $_jCf0Q[$_Q6J0Q["id"]] = $_Q6Q1C;
    }
    mysql_free_result($_ItlJl);
  }
  if($_Q60l1)
    mysql_free_result($_Q60l1);

  usort ($_jCf0Q, "CompareByEndSendDateTime");

  $_jCfjJ = false;
  if(count($_jCf0Q) == 0){
    $_Q6J0Q["Name"] = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
    $_Q6J0Q["SentCountSucc"] = 0;
    $_Q6J0Q["SentCountFailed"] = 0;
    $_Q6J0Q["HardBouncesCount"] = 0;
    $_Q6J0Q["SoftBouncesCount"] = 0;
    $_Q6J0Q["EndSendDateTimeFormated"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];
    $_jCfjJ = true;
  }
  else
    $_Q6J0Q = $_jCf0Q[0];

  if($_Q6J0Q["Name"] == $resourcestrings[$INTERFACE_LANGUAGE]["NA"])
     $_QJCJi = _OPR6L($_QJCJi, "<MAILING:NAME>", "</MAILING:NAME>", $_Q6J0Q["Name"]);
     else
     $_QJCJi = _OPR6L($_QJCJi, "<MAILING:NAME>", "</MAILING:NAME>", '<a href="./stat_campaignlog.php?CampaignId='."$_Q6J0Q[CampaignId]&SendStatId=$_Q6J0Q[id]".'" style="font-size: 10pt">'.$_Q6J0Q["Name"].'</a>');
  $_QJCJi = _OPR6L($_QJCJi, "<MAILING:SENTSUCC>", "</MAILING:SENTSUCC>", $_Q6J0Q["SentCountSucc"]);
  $_QJCJi = _OPR6L($_QJCJi, "<MAILING:SENTFAILED>", "</MAILING:SENTFAILED>", $_Q6J0Q["SentCountFailed"]);
  $_QJCJi = _OPR6L($_QJCJi, "<MAILING:HARDBOUNCES>", "</MAILING:HARDBOUNCES>", $_Q6J0Q["HardBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<MAILING:SOFTBOUNCES>", "</MAILING:SOFTBOUNCES>", $_Q6J0Q["SoftBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<MAILING:ENDSENTDATETIME>", "<MAILING:ENDSENTDATETIME>", $_Q6J0Q["EndSendDateTimeFormated"]);



  # Set chart attributes
  $_QJCJi = str_replace("LASTMAILINGCHARTTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000688"], $_Q6QQL), $_QJCJi);

  $_jCfit = array();
  $_jC6IQ = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000686"].", {y}", "y" => $_Q6J0Q["SentCountSucc"]);
  $_jCfit[] = $_jC6IQ;
  $_jC6IQ = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000687"].", {y}", "y" => $_Q6J0Q["SentCountFailed"]);
  if(!$_jCfjJ) $_jCfit[] = $_jC6IQ;
  $_jC6IQ = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000683"].", {y}", "y" => $_Q6J0Q["HardBouncesCount"]);
  if(!$_jCfjJ) $_jCfit[] = $_jC6IQ;

  $_QJCJi = str_replace("/* MAILINGCHART_DATA */", _OCR88($_jCfit, JSON_NUMERIC_CHECK), $_QJCJi);


  print $_QJCJi;


  function CompareByEndSendDateTime($_Q8otJ, $_jQjOO) {
      return strcmp($_Q8otJ["EndSendDateTime"], $_jQjOO["EndSendDateTime"]) * -1;
  }

?>
