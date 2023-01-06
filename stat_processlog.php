<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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
    if(!$_QLJJ6["PrivilegeViewProcessLog"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }


  if(isset($_GET["Result_id"])) {
    $_GET["Result_id"] = intval($_GET["Result_id"]);
    $_QLJfI = _JJAQE("processlog_view_result.htm");

    $_QLfol = "SELECT $_JQQoC.id, $_JQQI1.JobType, Result, ResultText, DATE_FORMAT(StartDateTime, $_QLo60) AS StartDate, DATE_FORMAT(EndDateTime, $_QLo60) AS EndDate FROM $_JQQoC LEFT JOIN $_JQQI1 ON $_JQQI1.id=cronoptions_id WHERE $_JQQoC.id=".$_GET["Result_id"];

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && $_QLO0f = mysql_fetch_array($_QL8i1)) {
     $_QLJfI = _L81BJ($_QLJfI, "<RESULTTEXT>", "</RESULTTEXT>", $_QLO0f["ResultText"]);

     if($_QLO0f["Result"] > 0)
       $_8fQ0J = '<img src="images/check16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTED"].'" />&nbsp;';
       else
       if($_QLO0f["Result"] == 0)
          $_8fQ0J = '<img src="images/cross16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["FAILED"].'" />&nbsp;';
          else
         if($_QLO0f["Result"] == -1)
           $_8fQ0J = '<img src="images/minus16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["NOT_EXECUTED"].'" />&nbsp;';
           else
             $_8fQ0J = '<img src="images/hourglass.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTING"].'" />&nbsp;';

     $_QLJfI = _L81BJ($_QLJfI, "<LIST:PROCESSNAME>", "</LIST:PROCESSNAME>", $_8fQ0J.$resourcestrings[$INTERFACE_LANGUAGE]["Cron".$_QLO0f["JobType"]]);
     $_QLJfI = _L81BJ($_QLJfI, "<LIST:START>", "</LIST:START>", $_QLO0f["StartDate"]);
     $_QLJfI = _L81BJ($_QLJfI, "<LIST:END>", "</LIST:END>", $_QLO0f["EndDate"]);
    }

    SetHTMLHeaders($_QLo06);

    print $_QLJfI;
    exit;
  }

  $_Itfj8 = "";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000340"], $_Itfj8, 'stat_processlog', 'browse_processlog_snipped.htm');

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);
  $_QLJfI = str_replace('dateFormat = "de"', 'dateFormat = "'.$INTERFACE_LANGUAGE.'"', $_QLJfI);


  $_JoiCQ = "";
  $_JoL0L = "";

  if(! ( isset($_POST["startdate"]) && isset($_POST["enddate"])) ) {

    $_QLfol = "SELECT DATE_FORMAT(NOW(), $_j01CJ) AS ENDDATE, DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 10 DAY), $_j01CJ) AS STARTDATE ";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_JoL0L = $_QLO0f[0];
    $_JoiCQ = $_QLO0f[1];
    $_POST["startdate"] = $_JoiCQ;
    $_POST["enddate"] = $_JoL0L;
    mysql_free_result($_QL8i1);
  }

  if( isset($_POST["startdate"]) && isset($_POST["enddate"]) ) {
    if($INTERFACE_LANGUAGE != "de") {
      $_JoiCQ = $_POST["startdate"];
      $_JoL0L = $_POST["enddate"];
    } else {
      $_I1OoI = explode('.', $_POST["startdate"]);
      $_JoiCQ = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
      $_I1OoI = explode('.', $_POST["enddate"]);
      $_JoL0L = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
    }
  }


  $_JoiCQ .= " 00:00:00";
  $_JoL0L .= " 23:59:59";

  $_QLfol = "AND $_JQQI1.JobType <> 'EventResponderChecking'";
  if(defined("SWM") && $OwnerOwnerUserId == 90){
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

  if(empty($_POST["ShowTasks"]))
    $_POST["ShowTasks"] = "ALL";

  if($_POST["ShowTasks"] == "EXECUTED")
    $_QLfol .= " AND `$_JQQoC`.Result>0";
    else
  if($_POST["ShowTasks"] == "FAILED")
    $_QLfol .= " AND `$_JQQoC`.Result=0";
    else
  if($_POST["ShowTasks"] == "NOT_EXECUTED")
    $_QLfol .= " AND `$_JQQoC`.Result=-1";
    else
  if($_POST["ShowTasks"] == "EXECUTING")
    $_QLfol .= " AND `$_JQQoC`.Result<-1";

  $_QLfol = "SELECT {} FROM `$_JQQoC` LEFT JOIN `$_JQQI1` ON `$_JQQI1`.id=cronoptions_id WHERE StartDateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L)." $_QLfol ORDER BY StartDateTime DESC";

  $_QLJfI = _JLFDC($_QLfol, $_QLJfI);

  $_QLJfI = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QLJfI);
  $_QLJfI = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QLJfI);
  $_QLJfI = str_replace('<option value="'.$_POST["ShowTasks"].'">', '<option value="'.$_POST["ShowTasks"].'" selected="selected">', $_QLJfI);

  if($INTERFACE_LANGUAGE != "de")
    $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);

  print $_QLJfI;

  function _JLFDC($_QLfol, $_QLoli) {
    global $UserId, $_SESSION, $INTERFACE_LANGUAGE, $resourcestrings, $_JQQI1, $_JQQoC, $_JoiCQ, $_JoL0L, $_QLo60, $_QLttI;
    $_Il0o6 = array();

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', "COUNT($_JQQoC.id)", $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    _L8D88($_QLlO6);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "End") )
       $_IlQQ6 = $_IlILC;

    if ( ($_IlQQ6 > $_IlILC) || ($_IlQQ6 <= 0) )
       $_IlQQ6 = 1;

    $_Iil6i = ($_IlQQ6 - 1) * $_Il1jO;

    $_QlOjt = "";
    for($_Qli6J=1; $_Qli6J<=$_IlILC; $_Qli6J++)
      if($_Qli6J != $_IlQQ6)
       $_QlOjt .= "<option>$_Qli6J</option>";
       else
       $_QlOjt .= '<option selected="selected">'.$_Qli6J.'</option>';

    $_QLoli = _L81BJ($_QLoli, "<OPTION:PAGES>", "</OPTION:PAGES>", $_QlOjt);

    // Nav-Buttons
    $_Iljoj = "";
    if($_IlQQ6 == 1) {
      $_Iljoj .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_IlQQ6 == $_IlILC) || ($_IlQll == 0) ) {
      $_Iljoj .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_IlQll == 0)
      $_Iljoj .= "  DisableItem('PageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_jf8JI = "$_JQQoC.id, $_JQQI1.JobType, Result, ResultText, DATE_FORMAT(StartDateTime, $_QLo60) AS StartDate, DATE_FORMAT(EndDateTime, $_QLo60) AS EndDate";
    $_QLfol = str_replace('{}', $_jf8JI, $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      if($_QLO0f["Result"] > 0)
        $_8fQ0J = '<img src="images/check16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTED"].'" />&nbsp;';
        else
        if($_QLO0f["Result"] == 0)
           $_8fQ0J = '<img src="images/cross16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["FAILED"].'" />&nbsp;';
           else
        if($_QLO0f["Result"] == -1)
           $_8fQ0J = '<img src="images/minus16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["NOT_EXECUTED"].'" />&nbsp;';
           else
           $_8fQ0J = '<img src="images/hourglass.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTING"].'" />&nbsp;';
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:PROCESSNAME>", "</LIST:PROCESSNAME>", $_8fQ0J.$resourcestrings[$INTERFACE_LANGUAGE]["Cron".$_QLO0f["JobType"]]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:START>", "</LIST:START>", $_QLO0f["StartDate"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:END>", "</LIST:END>", $_QLO0f["EndDate"]);
      $_Ql0fO = str_replace("Result_id=", "Result_id=".$_QLO0f["id"], $_Ql0fO);

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    return $_QLoli;
  }

?>
