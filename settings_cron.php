<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeCron"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  $errors = array();

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }


  // save
  if(isset($_POST["SaveBtn"])) {
    if (!isset($_POST["JobWorkingInterval"]) || !isset($_POST["JobWorkingIntervalType"]) ) {
      $errors[] = "JobWorkingInterval";
      $errors[] = "JobWorkingIntervalType";
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000346"];
    }

    if(count($errors) == 0 && count($_POST["JobWorkingInterval"]) != count($_POST["JobWorkingIntervalType"]) ) {
      $errors[] = "JobWorkingInterval";
      $errors[] = "JobWorkingIntervalType";
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000346"];
    }

    if(count($errors) == 0) {
      foreach($_POST["JobWorkingInterval"] as $key => $_Q6ClO) {
        if(intval($_Q6ClO) <= 0)
          $_POST["JobWorkingInterval"][$key] = 1;
          else
          $_POST["JobWorkingInterval"][$key] = intval($_Q6ClO);
      }
    }

    // save
    if(count($errors) == 0) {
      foreach($_POST["JobWorkingInterval"] as $key => $_Q6ClO) {
        $_QJlJ0 = "UPDATE $_jJJtf SET JobWorkingInterval="._OPQLR($_Q6ClO).", JobWorkingIntervalType="._OPQLR($_POST["JobWorkingIntervalType"][$key])." WHERE id=".intval($key);
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
    }
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000345"], $_I0600, 'settings_cron', 'browse_cron_snipped.htm');


  $_Q6tjl = "";
  $_IIJi1 = _OP81D($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
  $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

  $_QJlJ0 = "WHERE $_jJJtf.JobType <> 'EventResponderChecking'";
  if(!defined("SML") && $OwnerOwnerUserId == 90){
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'ResponderStatCleanUp'";
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'AutoresponderChecking'";
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'FollowUpResponderChecking'";
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'BirthdayResponderChecking'";
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'RSS2EMailResponderChecking'";
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'DistribListChecking'";
  }
  if(defined("SML") && $OwnerOwnerUserId == 0x41){
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'ResponderStatCleanUp'";
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'DistribListChecking'";
    $_QJlJ0 .= " AND $_jJJtf.JobType <> 'SendEngineChecking'";
  }

  $_QJlJ0 = "SELECT *, DATE_FORMAT(LastExecution, $_Q6QiO) AS LastExec FROM $_jJJtf $_QJlJ0 ORDER BY id";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {

    if($_Q6Q1C["JobType"] == 'EventResponderChecking') continue; // skip at this time

    // compatibily
    if($_Q6Q1C["JobWorkingIntervalType"] == "") {
       $_Q6Q1C["JobWorkingIntervalType"] = "Day";
       mysql_query("UPDATE $_jJJtf SET JobWorkingIntervalType='$_Q6Q1C[JobWorkingIntervalType]' WHERE id=$_Q6Q1C[id] ");
    }

    $_Q66jQ = $_IIJi1;
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:JOBNAME>", "</LIST:JOBNAME>", $resourcestrings[$INTERFACE_LANGUAGE]["Cron".$_Q6Q1C["JobType"]]);
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LASTEXECUTION>", "</LIST:LASTEXECUTION>", $_Q6Q1C["LastExec"]);

    $_6tifO = _OP81D($_Q66jQ, "<LIST:JOBINTERVAL>", "</LIST:JOBINTERVAL>");
    $_6tifO = str_replace ('<LIST:JOBINTERVAL>', '', $_6tifO);
    $_6tifO = str_replace ('</LIST:JOBINTERVAL>', '', $_6tifO);

    $_6tifO = _OPFJA(array(), $_Q6Q1C, $_6tifO);
    $_6tifO = str_replace ('name="JobWorkingInterval"', 'name="JobWorkingInterval['.$_Q6Q1C["id"].']"', $_6tifO);
    $_6tifO = str_replace ('name="JobWorkingIntervalType"', 'name="JobWorkingIntervalType['.$_Q6Q1C["id"].']"', $_6tifO);

    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:JOBINTERVAL>", "</LIST:JOBINTERVAL>", $_6tifO);

    $_Q6tjl .= $_Q66jQ;

  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);


  $_QJCJi = str_replace ('?language=', '?language='.$INTERFACE_LANGUAGE, $_QJCJi);
  $_QJCJi = str_replace ('<crons_php>', ScriptBaseURL.'crons.php', $_QJCJi);
  $_QJCJi = str_replace ('&lt;crons_php&gt;', ScriptBaseURL.'crons.php', $_QJCJi);


  if($INTERFACE_LANGUAGE != "de")
    $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);

  $_QJCJi = _OPFJA($errors, array(), $_QJCJi);

  print $_QJCJi;

?>
