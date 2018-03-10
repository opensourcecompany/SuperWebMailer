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
  include_once("templates.inc.php");


  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeViewProcessLog"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }


  if(isset($_GET["Result_id"])) {
    $_GET["Result_id"] = intval($_GET["Result_id"]);
    $_QJCJi = join("", file(_O68QF()."processlog_view_result.htm"));

    $_QJlJ0 = "SELECT $_jJ6Qf.id, $_jJJtf.JobType, Result, ResultText, DATE_FORMAT(StartDateTime, $_Q6QiO) AS StartDate, DATE_FORMAT(EndDateTime, $_Q6QiO) AS EndDate FROM $_jJ6Qf LEFT JOIN $_jJJtf ON $_jJJtf.id=cronoptions_id WHERE $_jJ6Qf.id=".$_GET["Result_id"];

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1 && $_Q6Q1C = mysql_fetch_array($_Q60l1)) {
     $_QJCJi = _OPR6L($_QJCJi, "<RESULTTEXT>", "</RESULTTEXT>", $_Q6Q1C["ResultText"]);

     if($_Q6Q1C["Result"] > 0)
       $_6itQQ = '<img src="images/check16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTED"].'" />&nbsp;';
       else
       if($_Q6Q1C["Result"] == 0)
          $_6itQQ = '<img src="images/cross16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["FAILED"].'" />&nbsp;';
          else
         if($_Q6Q1C["Result"] == -1)
           $_6itQQ = '<img src="images/minus16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["NOT_EXECUTED"].'" />&nbsp;';
           else
             $_6itQQ = '<img src="images/hourglass.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTING"].'" />&nbsp;';

     $_QJCJi = _OPR6L($_QJCJi, "<LIST:PROCESSNAME>", "</LIST:PROCESSNAME>", $_6itQQ.$resourcestrings[$INTERFACE_LANGUAGE]["Cron".$_Q6Q1C["JobType"]]);
     $_QJCJi = _OPR6L($_QJCJi, "<LIST:START>", "</LIST:START>", $_Q6Q1C["StartDate"]);
     $_QJCJi = _OPR6L($_QJCJi, "<LIST:END>", "</LIST:END>", $_Q6Q1C["EndDate"]);
    }

    SetHTMLHeaders($_Q6QQL);

    print $_QJCJi;
    exit;
  }

  $_I0600 = "";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000340"], $_I0600, 'stat_processlog', 'browse_processlog_snipped.htm');

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);


  $_jC1lo = "";
  $_jCQ0I = "";

  if(! ( isset($_POST["startdate"]) && isset($_POST["enddate"])) ) {

    $_QJlJ0 = "SELECT DATE_FORMAT(NOW(), $_If0Ql) AS ENDDATE, DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 10 DAY), $_If0Ql) AS STARTDATE ";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    $_jCQ0I = $_Q6Q1C[0];
    $_jC1lo = $_Q6Q1C[1];
    $_POST["startdate"] = $_jC1lo;
    $_POST["enddate"] = $_jCQ0I;
    mysql_free_result($_Q60l1);
  }

  if( isset($_POST["startdate"]) && isset($_POST["enddate"]) ) {
    if($INTERFACE_LANGUAGE != "de") {
      $_jC1lo = $_POST["startdate"];
      $_jCQ0I = $_POST["enddate"];
    } else {
      $_Q8otJ = explode('.', $_POST["startdate"]);
      $_jC1lo = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
      $_Q8otJ = explode('.', $_POST["enddate"]);
      $_jCQ0I = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
    }
  }


  $_jC1lo .= " 00:00:00";
  $_jCQ0I .= " 23:59:59";

  $_QJlJ0 = "AND $_jJJtf.JobType <> 'EventResponderChecking'";
  if(defined("SWM") && $OwnerOwnerUserId == 90){
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

  if(empty($_POST["ShowTasks"]))
    $_POST["ShowTasks"] = "ALL";

  if($_POST["ShowTasks"] == "EXECUTED")
    $_QJlJ0 .= " AND `$_jJ6Qf`.Result>0";
    else
  if($_POST["ShowTasks"] == "FAILED")
    $_QJlJ0 .= " AND `$_jJ6Qf`.Result=0";
    else
  if($_POST["ShowTasks"] == "NOT_EXECUTED")
    $_QJlJ0 .= " AND `$_jJ6Qf`.Result=-1";
    else
  if($_POST["ShowTasks"] == "EXECUTING")
    $_QJlJ0 .= " AND `$_jJ6Qf`.Result<-1";

  $_QJlJ0 = "SELECT {} FROM `$_jJ6Qf` LEFT JOIN `$_jJJtf` ON `$_jJJtf`.id=cronoptions_id WHERE StartDateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I)." $_QJlJ0 ORDER BY StartDateTime DESC";

  $_QJCJi = _LLBE8($_QJlJ0, $_QJCJi);

  $_QJCJi = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QJCJi);
  $_QJCJi = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QJCJi);
  $_QJCJi = str_replace('<option value="'.$_POST["ShowTasks"].'">', '<option value="'.$_POST["ShowTasks"].'" selected="selected">', $_QJCJi);

  if($INTERFACE_LANGUAGE != "de")
    $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);

  print $_QJCJi;

  function _LLBE8($_QJlJ0, $_Q6ICj) {
    global $UserId, $_SESSION, $INTERFACE_LANGUAGE, $resourcestrings, $_jJJtf, $_jJ6Qf, $_jC1lo, $_jCQ0I, $_Q6QiO, $_Q61I1;
    $_I61Cl = array();

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_QllO8 = intval($_POST["ItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["ItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', "COUNT($_jJ6Qf.id)", $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    _OAL8F($_QtjtL);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneProcessLogId"] ) && ($_POST["OneProcessLogId"] == "End") )
       $_I6Q6O = $_I6IJ8;

    if ( ($_I6Q6O > $_I6IJ8) || ($_I6Q6O <= 0) )
       $_I6Q6O = 1;

    $_IJQQI = ($_I6Q6O - 1) * $_I6Q68;

    $_Q6i6i = "";
    for($_Q6llo=1; $_Q6llo<=$_I6IJ8; $_Q6llo++)
      if($_Q6llo != $_I6Q6O)
       $_Q6i6i .= "<option>$_Q6llo</option>";
       else
       $_Q6i6i .= '<option selected="selected">'.$_Q6llo.'</option>';

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:PAGES>", "</OPTION:PAGES>", $_Q6i6i);

    // Nav-Buttons
    $_I6ICC = "";
    if($_I6Q6O == 1) {
      $_I6ICC .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_I6Q6O == $_I6IJ8) || ($_I6Qfj == 0) ) {
      $_I6ICC .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_I6Qfj == 0)
      $_I6ICC .= "  DisableItem('PageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_Iijl0 = "$_jJ6Qf.id, $_jJJtf.JobType, Result, ResultText, DATE_FORMAT(StartDateTime, $_Q6QiO) AS StartDate, DATE_FORMAT(EndDateTime, $_Q6QiO) AS EndDate";
    $_QJlJ0 = str_replace('{}', $_Iijl0, $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      if($_Q6Q1C["Result"] > 0)
        $_6itQQ = '<img src="images/check16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTED"].'" />&nbsp;';
        else
        if($_Q6Q1C["Result"] == 0)
           $_6itQQ = '<img src="images/cross16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["FAILED"].'" />&nbsp;';
           else
        if($_Q6Q1C["Result"] == -1)
           $_6itQQ = '<img src="images/minus16.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["NOT_EXECUTED"].'" />&nbsp;';
           else
           $_6itQQ = '<img src="images/hourglass.gif" width="16" height="16" alt="'.$resourcestrings[$INTERFACE_LANGUAGE]["EXECUTING"].'" />&nbsp;';
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:PROCESSNAME>", "</LIST:PROCESSNAME>", $_6itQQ.$resourcestrings[$INTERFACE_LANGUAGE]["Cron".$_Q6Q1C["JobType"]]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:START>", "</LIST:START>", $_Q6Q1C["StartDate"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:END>", "</LIST:END>", $_Q6Q1C["EndDate"]);
      $_Q66jQ = str_replace("Result_id=", "Result_id=".$_Q6Q1C["id"], $_Q66jQ);

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    return $_Q6ICj;
  }

?>
