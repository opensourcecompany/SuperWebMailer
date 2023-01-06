<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  if($UserType != "SuperAdmin") {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  $_Itfj8 = "";

  $_QLfol = "SELECT CronCleanUpDays FROM $_I1O0i";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM `$_jCJ6O`";
  $_jCJoL = " LEFT JOIN `$_I18lo` ON `$_I18lo`.`id`=`$_jCJ6O`.`users_id`";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["003000"], $_QLO0f[0]).$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browseusers_loginhistory', 'browseusers_loginhistory_snipped.htm');

  $_QLJfI = _LQF80($_QLfol, $_QLJfI, $_jCJoL);


  print $_QLJfI;



  function _LQF80($_QLfol, $_QLoli, $_jCJoL) {
    global $_I18lo, $_jCJ6O, $_QLttI, $INTERFACE_LANGUAGE, $resourcestrings;
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
    $_QLlO6 = str_replace('{}', 'COUNT(id)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneLoginHistoryListId"] ) && ($_POST["OneLoginHistoryListId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneLoginHistoryListId"] ) && ($_POST["OneLoginHistoryListId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneLoginHistoryListId"] ) && ($_POST["OneLoginHistoryListId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneLoginHistoryListId"] ) && ($_POST["OneLoginHistoryListId"] == "End") )
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

    $_QLfol .= $_jCJoL;
    
    // Sort
    $_IlJj8 = " ORDER BY $_jCJ6O.`LastLogin` DESC";   // ???

    $_QLfol .= $_IlJj8;
    
    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
    if($INTERFACE_LANGUAGE != "de") {
       $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
    }

    $_QLfol = str_replace('{}', "$_jCJ6O.`users_id`, DATE_FORMAT($_jCJ6O.`LastLogin`, $_QLo60) As LastLogin, DATE_FORMAT($_jCJ6O.`Logout`, $_QLo60) As Logout, $_jCJ6O.`IP`, $_I18lo.`Username` ", $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["users_id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:USERNAME>", "</LIST:USERNAME>", $_QLO0f["Username"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LOGINDATE>", "</LIST:LOGINDATE>", $_QLO0f["LastLogin"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LOGOUTDATE>", "</LIST:LOGOUTDATE>", $_QLO0f["Logout"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:IPADDRESS>", "</LIST:IPADDRESS>", $_QLO0f["IP"]);
      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    return $_QLoli;
  }

?>
