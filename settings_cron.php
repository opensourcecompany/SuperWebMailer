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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeCron"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";
  $errors = array();

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_8QoCl = false;

  // save
  if(isset($_POST["SaveBtn"])) {
    if (!isset($_POST["JobWorkingInterval"]) || !isset($_POST["JobWorkingIntervalType"]) ) {
      $errors[] = "JobWorkingInterval";
      $errors[] = "JobWorkingIntervalType";
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000346"];
    }

    if(count($errors) == 0 && count($_POST["JobWorkingInterval"]) != count($_POST["JobWorkingIntervalType"]) ) {
      $errors[] = "JobWorkingInterval";
      $errors[] = "JobWorkingIntervalType";
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000346"];
    }

    if(count($errors) == 0) {
      foreach($_POST["JobWorkingInterval"] as $key => $_QltJO) {
        if(intval($_QltJO) <= 0)
          $_POST["JobWorkingInterval"][$key] = 1;
          else
          $_POST["JobWorkingInterval"][$key] = intval($_QltJO);
        if(strpos($_POST["JobWorkingIntervalType"][$key], "Second") !== false)
          $_8QoCl = true;
      }
    }

    // save
    if(count($errors) == 0) {
      foreach($_POST["JobWorkingInterval"] as $key => $_QltJO) {
        $_QLfol = "UPDATE $_JQQI1 SET JobWorkingInterval="._LRAFO($_QltJO).", JobWorkingIntervalType="._LRAFO($_POST["JobWorkingIntervalType"][$key])." WHERE id=".intval($key);
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
      if($_8QoCl)
        $_Itfj8 .= "<br /><br />" . $resourcestrings[$INTERFACE_LANGUAGE]["WarningCronTasksInSecondsInterval"];
    }
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000345"], $_Itfj8, 'settings_cron', 'browse_cron_snipped.htm');


  $_QlIf1 = "";
  $_IC1C6 = _L81DB($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
  $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

  $_QLfol = "WHERE $_JQQI1.JobType <> 'EventResponderChecking'";
  if(!defined("SML") && $OwnerOwnerUserId == 90){
    $_QLfol .= " AND $_JQQI1.JobType <> 'ResponderStatCleanUp'";
    $_QLfol .= " AND $_JQQI1.JobType <> 'AutoresponderChecking'";
    $_QLfol .= " AND $_JQQI1.JobType <> 'FollowUpResponderChecking'";
    $_QLfol .= " AND $_JQQI1.JobType <> 'BirthdayResponderChecking'";
    $_QLfol .= " AND $_JQQI1.JobType <> 'RSS2EMailResponderChecking'";
    $_QLfol .= " AND $_JQQI1.JobType <> 'DistribListChecking'";
  }
  if(defined("SML") && $OwnerOwnerUserId == 0x41){
    $_QLfol .= " AND $_JQQI1.JobType <> 'ResponderStatCleanUp'";
    $_QLfol .= " AND $_JQQI1.JobType <> 'DistribListChecking'";
    $_QLfol .= " AND $_JQQI1.JobType <> 'SendEngineChecking'";
  }

  $_QLfol = "SELECT *, DATE_FORMAT(LastExecution, $_QLo60) AS LastExec FROM $_JQQI1 $_QLfol ORDER BY id";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {

    if($_QLO0f["JobType"] == 'EventResponderChecking') continue; // skip at this time

    // compatibily
    if($_QLO0f["JobWorkingIntervalType"] == "") {
       $_QLO0f["JobWorkingIntervalType"] = "Day";
       mysql_query("UPDATE $_JQQI1 SET JobWorkingIntervalType='$_QLO0f[JobWorkingIntervalType]' WHERE id=$_QLO0f[id] ");
    }

    $_Ql0fO = $_IC1C6;
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:JOBNAME>", "</LIST:JOBNAME>", $resourcestrings[$INTERFACE_LANGUAGE]["Cron".$_QLO0f["JobType"]]);
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LASTEXECUTION>", "</LIST:LASTEXECUTION>", $_QLO0f["LastExec"]);

    $_8QC8l = _L81DB($_Ql0fO, "<LIST:JOBINTERVAL>", "</LIST:JOBINTERVAL>");
    $_8QC8l = str_replace ('<LIST:JOBINTERVAL>', '', $_8QC8l);
    $_8QC8l = str_replace ('</LIST:JOBINTERVAL>', '', $_8QC8l);

    $_8QC8l = _L8AOB(array(), $_QLO0f, $_8QC8l);
    $_8QC8l = str_replace ('name="JobWorkingInterval"', 'name="JobWorkingInterval['.$_QLO0f["id"].']"', $_8QC8l);
    $_8QC8l = str_replace ('name="JobWorkingIntervalType"', 'name="JobWorkingIntervalType['.$_QLO0f["id"].']"', $_8QC8l);

    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:JOBINTERVAL>", "</LIST:JOBINTERVAL>", $_8QC8l);

    $_QlIf1 .= $_Ql0fO;

  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);


  $_QLJfI = str_replace ('?language=', '?language='.$INTERFACE_LANGUAGE, $_QLJfI);
  $_QLJfI = str_replace ('<crons_php>', ScriptBaseURL.'crons.php', $_QLJfI);
  $_QLJfI = str_replace ('&lt;crons_php&gt;', ScriptBaseURL.'crons.php', $_QLJfI);


  if($INTERFACE_LANGUAGE != "de")
    $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);

  $_QLJfI = _L8AOB($errors, array(), $_QLJfI);

  print $_QLJfI;

?>
