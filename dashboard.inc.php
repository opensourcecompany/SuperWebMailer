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
  include_once("sessioncheck.inc.php");
  include_once("mailinglistq.inc.php");
  include_once("onlineupdate.inc.php");
  include_once("templates.inc.php");
  include_once("localmessages_ops.inc.php");


  $_QLfol = "SELECT DISTINCT `Name`, `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_QL88I`";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QLfol .= " WHERE (`users_id`=$UserId)";
     else {
      $_QLfol .= " LEFT JOIN `$_QlQot` ON `$_QL88I`.`id`=`$_QlQot`.`maillists_id` WHERE (`$_QlQot`.`users_id`=$UserId) AND (`$_QL88I`.`users_id`=$OwnerUserId)";
     }
  $_QLfol .= " ORDER BY `Name` ASC";

  $_Jooll = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  // *********** Total statistics
  $_JoCoC = mysql_num_rows($_Jooll);
  $_If6if = 0;
  $_If6l1 = 0;
  $_IffCj = 0;
  $_If81o = 0;
  $_If8io = 0;
  while( $_QLO0f=mysql_fetch_assoc($_Jooll) ) {
    $_QLfol = "SELECT COUNT(id) FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_If6if += $_Ift08[0];

    $_QLfol = "SELECT COUNT(id) AS Total FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptInConfirmationPending'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_If81o += $_Ift08[0];

    $_QLfol = "SELECT COUNT(id) AS Total FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptOutConfirmationPending'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_If8io += $_Ift08[0];

    $_QLfol = "SELECT COUNT(id) FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_If6l1 += $_Ift08[0];

    $_QLfol = "SELECT COUNT(id) FROM `$_QLO0f[LocalBlocklistTableName]`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IffCj += $_Ift08[0];
  }

  $_QLfol = "SELECT COUNT(id) FROM `$_I8tfQ`";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_Ift08=mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);
  $_IffCj += $_Ift08[0];

  $_Itfj8 = "";
  $_JoiQi = "";
  $_JoiJL = 0;
  if( _J0RCP($_JoiQi, $_JoiJL, $id, $OwnerUserId == 0) ) {
    if ( $_JoiQi != "" && $_JoiJL != 0 && $OwnerUserId == 0 ) {
       if ( version_compare($_Ij6Lj, $_JoiQi, "<") ) {
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["UpdateAvailable"];
         $_Itfj8 = _JJCCF($_Itfj8);
         $_Itfj8 = str_replace("%NEWVERSION%", $_JoiQi, $_Itfj8);
         $_Itfj8 = str_replace("%NEWVERSIONDATE%", date($ShortDateFormat, $_JoiJL), $_Itfj8);
         _LDADP(0, $UserId, $resourcestrings[$INTERFACE_LANGUAGE]["UpdateAvailableSubject"], '<p>'.$_Itfj8.'</p>', array());
       }
    }
  } else {
    mysql_query("DELETE FROM `$_I1O0i` WHERE `id`=$id", $_QLttI);
  }

  if ( ($UserType == "SuperAdmin") ) {
    include_once("browseusers.php");
    exit;
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000007"], $_Itfj8, 'interface', 'dashboard_snipped.htm');

  $_QLJfI = _L81BJ($_QLJfI, '<MAILINGLIST:TOTAL>', '</MAILINGLIST:TOTAL>', $_JoCoC);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:TOTALACTIVE>', '</LIST:TOTALACTIVE>', $_If6if);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:TOTALINACTIVE>', '</LIST:TOTALINACTIVE>', $_If6l1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_IffCj);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:TOTALOPTINUNCONFIRMED>', '</LIST:TOTALOPTINUNCONFIRMED>', $_If81o);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:TOTALOPTOUTUNCONFIRMED>', '</LIST:TOTALOPTOUTUNCONFIRMED>', $_If8io);

  // *********** Total statistics END

  //

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_QLfol = "SELECT DATE_FORMAT(NOW(), $_j01CJ), DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 5 DAY), $_j01CJ)";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Joi6C = mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);

  // *********** Period statistics
  $_JoiCQ = $_Joi6C[1];
  $_JoL0L = $_Joi6C[0];

  if($INTERFACE_LANGUAGE == "de") {
    $_I1OoI = explode('.', $_JoiCQ);
    $_JoiCQ = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
    $_I1OoI = explode('.', $_JoL0L);
    $_JoL0L = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
  }

  // CHART
  $_IoLOO = array();

  // set 5 days to 0
  for($_Qli6J=0; $_Qli6J<=5; $_Qli6J++) {
    $_QLfol = "SELECT DATE_FORMAT(DATE_ADD('$_JoiCQ', INTERVAL $_Qli6J DAY), '%Y-%m-%d')";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_JfJ0J = mysql_fetch_row($_QL8i1);
    if (! isset($_IoLOO[$_JfJ0J[0]]) )
       $_IoLOO[$_JfJ0J[0]] = array (0, 0, 0, 0, 0);
    mysql_free_result($_QL8i1);
  }

  // $resourcestrings[$INTERFACE_LANGUAGE]["ChartNoDataText"]
  /// $resourcestrings[$INTERFACE_LANGUAGE]["PBarLoadingText"]

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QLJfI = addCultureInfo($_QLJfI);
  // addCultureInfo /

  # Set chart attributes
  $_QLJfI = str_replace("SUBUNSUBCHARTTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000027"]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000371"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("SUBUNSUBCHARTAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("SUBUNSUBCHARTAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_QLo06), $_QLJfI);

  $_JoiCQ .= " 00:00:00";
  $_JoL0L .= " 23:59:59";

  if( mysql_num_rows($_Jooll) > 0)
     mysql_data_seek($_Jooll, 0);

  while( $_QLO0f=mysql_fetch_array($_Jooll) ) {
    $_QLfol = "SELECT COUNT(ActionDate) AS Counter, DATE_FORMAT(ActionDate, $_j01CJ) AS ADate, DATE_FORMAT(ActionDate, '%Y-%m-%d') AS ADateEng, Action FROM $_QLO0f[StatisticsTableName] WHERE (Action='OptInConfirmationPending' OR Action='Subscribed' OR Action='Unsubscribed' OR Action='OptOutConfirmationPending' OR Action='Bounced') AND (ActionDate >= '$_JoiCQ') AND (ActionDate <= '$_JoL0L') GROUP BY Action, ADateEng ORDER BY ADate ASC, Action";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    if($_QL8i1 && mysql_num_rows($_QL8i1) > 0 ) {
      while ($_IOLJ1 = mysql_fetch_array($_QL8i1) ) {
        if (! isset($_IoLOO[$_IOLJ1["ADateEng"]]) )
           $_IoLOO[$_IOLJ1["ADateEng"]] = array (0, 0, 0, 0, 0);
      }

      mysql_data_seek($_QL8i1, 0);
      while ($_IOLJ1 = mysql_fetch_array($_QL8i1) ) {
        if($_IOLJ1["Action"] == 'OptInConfirmationPending')
           $_IoLOO[$_IOLJ1["ADateEng"]][0] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'Subscribed')
           $_IoLOO[$_IOLJ1["ADateEng"]][1] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'OptOutConfirmationPending')
           $_IoLOO[$_IOLJ1["ADateEng"]][2] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'Unsubscribed')
           $_IoLOO[$_IOLJ1["ADateEng"]][3] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'Bounced')
           $_IoLOO[$_IOLJ1["ADateEng"]][4] += $_IOLJ1["Counter"];
      }
    }
    mysql_free_result($_QL8i1);
  }

  ksort ($_IoLOO, SORT_STRING);


  $_JoL18 = array();
  $_JoL66 = array();
  $_JolJl = array();
  $_JoltO = array();
  $_JolOQ = array();

  $_JolCi = 0;
  reset($_IoLOO);
  foreach ($_IoLOO as $key => $_QltJO) {

    if($INTERFACE_LANGUAGE == "de") {
      $key = substr($key, 8).".".substr($key, 5, 2).".".substr($key, 0, 4);
    }

    $_JC0jO = array("label" => $key , "y" => $_QltJO[0]);
    $_JoL18[] = $_JC0jO;
    if($_QltJO[0] > $_JolCi)
      $_JolCi = $_QltJO[0];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[1]);
    $_JoL66[] = $_JC0jO;
    if($_QltJO[1] > $_JolCi)
      $_JolCi = $_QltJO[1];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[2]);
    $_JolJl[] = $_JC0jO;
    if($_QltJO[2] > $_JolCi)
      $_JolCi = $_QltJO[2];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[3]);
    $_JoltO[] = $_JC0jO;
    if($_QltJO[3] > $_JolCi)
      $_JolCi = $_QltJO[3];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[4]);
    $_JolOQ[] = $_JC0jO;
    if($_QltJO[4] > $_JolCi)
      $_JolCi = $_QltJO[4];
  }

  $_I0QjQ = array();

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000180"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JoL18);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000025"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JoL66);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000181"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JolJl);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000026"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JoltO);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000169"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JolOQ);
  $_I0QjQ[] = $entry;

  $_QLJfI = str_replace("/* SUBUNSUBCHART_DATA */", _LAFFB($_I0QjQ, JSON_NUMERIC_CHECK), $_QLJfI);

  if($_JolCi < 10){
     // set interval 1
     $_QLJfI = str_replace("/*SUBUNSUBCHARTINTERVAL", "", $_QLJfI);
     $_QLJfI = str_replace("SUBUNSUBCHARTINTERVAL*/", "", $_QLJfI);
  }

  /////////

  $_QLfol = "SELECT `id`, `Name`, `CurrentSendTableName` FROM `$_QLi60`";
  if($OwnerUserId != 0) {
     $_QLfol .= " LEFT JOIN `$_QlQot` ON `$_QlQot`.`maillists_id`=`$_QLi60`.`maillists_id`";
  }
  $_QLfol .= " WHERE `SetupLevel`=99 AND `SendScheduler` <> 'SaveOnly'";

  if($OwnerUserId != 0) {
   $_QLfol .= " AND `$_QlQot`.`users_id`=$UserId";
  }

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_JC1J6 = array();
  while($_QL8i1 && $_QLL16 = mysql_fetch_assoc($_QL8i1)) {
    $_jClC1 = $_QLL16["CurrentSendTableName"];
    $_jC6ot = $_QLL16["Name"];
    $_QLfol = "SELECT *, DATE_FORMAT(EndSendDateTime, $_QLo60) AS EndSendDateTimeFormated FROM `$_jClC1` WHERE `Campaigns_id`=$_QLL16[id] AND `SendState`='DONE' ORDER BY `EndSendDateTime` DESC LIMIT 0, 1";
    $_jjJfo = mysql_query($_QLfol, $_QLttI);
    if($_jjJfo && mysql_num_rows($_jjJfo) > 0){
       $_QLO0f = mysql_fetch_assoc($_jjJfo);
       $_QLO0f["Name"] = $_QLL16["Name"];
       $_QLO0f["CampaignId"] = $_QLL16["id"];
       $_JC1J6[$_QLL16["id"]] = $_QLO0f;
    }
    mysql_free_result($_jjJfo);
  }
  if($_QL8i1)
    mysql_free_result($_QL8i1);

  usort ($_JC1J6, "CompareByEndSendDateTime");

  $_JC1oO = false;
  if(count($_JC1J6) == 0){
    $_QLL16["Name"] = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
    $_QLL16["SentCountSucc"] = 0;
    $_QLL16["SentCountFailed"] = 0;
    $_QLL16["HardBouncesCount"] = 0;
    $_QLL16["SoftBouncesCount"] = 0;
    $_QLL16["EndSendDateTimeFormated"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];
    $_JC1oO = true;
  }
  else
    $_QLL16 = $_JC1J6[0];

  if($_QLL16["Name"] == $resourcestrings[$INTERFACE_LANGUAGE]["NA"])
     $_QLJfI = _L81BJ($_QLJfI, "<MAILING:NAME>", "</MAILING:NAME>", $_QLL16["Name"]);
     else
     $_QLJfI = _L81BJ($_QLJfI, "<MAILING:NAME>", "</MAILING:NAME>", '<a href="./stat_campaignlog.php?CampaignId='."$_QLL16[CampaignId]&SendStatId=$_QLL16[id]".'" style="font-size: 10pt">'.$_QLL16["Name"].'</a>');
  $_QLJfI = _L81BJ($_QLJfI, "<MAILING:SENTSUCC>", "</MAILING:SENTSUCC>", $_QLL16["SentCountSucc"]);
  $_QLJfI = _L81BJ($_QLJfI, "<MAILING:SENTFAILED>", "</MAILING:SENTFAILED>", $_QLL16["SentCountFailed"]);
  $_QLJfI = _L81BJ($_QLJfI, "<MAILING:HARDBOUNCES>", "</MAILING:HARDBOUNCES>", $_QLL16["HardBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<MAILING:SOFTBOUNCES>", "</MAILING:SOFTBOUNCES>", $_QLL16["SoftBouncesCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<MAILING:ENDSENTDATETIME>", "</MAILING:ENDSENTDATETIME>", $_QLL16["EndSendDateTimeFormated"]);



  # Set chart attributes
  $_QLJfI = str_replace("LASTMAILINGCHARTTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000688"], $_QLo06), $_QLJfI);

  $_JCQoQ = array();
  $_JC0jO = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000686"].", {y}", "y" => $_QLL16["SentCountSucc"]);
  $_JCQoQ[] = $_JC0jO;
  $_JC0jO = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000687"].", {y}", "y" => $_QLL16["SentCountFailed"]);
  if(!$_JC1oO) $_JCQoQ[] = $_JC0jO;
  $_JC0jO = array("indexLabel" => $resourcestrings[$INTERFACE_LANGUAGE]["000683"].", {y}", "y" => $_QLL16["HardBouncesCount"]);
  if(!$_JC1oO) $_JCQoQ[] = $_JC0jO;

  $_QLJfI = str_replace("/* MAILINGCHART_DATA */", _LAFFB($_JCQoQ, JSON_NUMERIC_CHECK), $_QLJfI);


  print $_QLJfI;


  function CompareByEndSendDateTime($_I1OoI, $_jl0Ii) {
      return strcmp($_I1OoI["EndSendDateTime"], $_jl0Ii["EndSendDateTime"]) * -1;
  }

?>
