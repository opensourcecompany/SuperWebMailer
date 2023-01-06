<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

  $_ffCj8 = 50;

  if (isset($_POST["OneMailingListId"]) )
     $OneMailingListId = intval($_POST["OneMailingListId"]);
     else {
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000250"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = intval($_POST["OneMailingListId"]);
     }

  if(!_LAEJL($OneMailingListId)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  if(!isset($_POST["MailingListName"])) {
    $_QLfol = "SELECT Name FROM $_QL88I WHERE id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_POST["MailingListName"] = $_QLO0f[0];
  }

  if(isset($_POST["NextBtn"]) && isset($_POST["step"]))
     $_POST["step"]++;

  if(isset($_POST["PrevBtn"]) && isset($_POST["step"]))
     $_POST["step"]--;
  if(isset($_POST["step"]) && $_POST["step"] < 0) $_POST["step"] = 1;

  if(!isset($_POST["step"]) || $_POST["step"] == "1" ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists1_snipped.htm');
    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
    $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);

    print $_QLJfI;
    exit;
  }

  if($_POST["step"] == "2"){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists2_snipped.htm');
    $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
    $_QLJfI = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QLJfI);
    if(!defined("ECG_APIKEY") || ECG_APIKEY == ""){
      $_QLJfI = _JJC1E($_QLJfI, "RemoveRecipientsByECGlist");
      $_QLJfI = _JJDJC($_QLJfI, "RemoveRecipientsByECGlist");
    }  

    print $_QLJfI;
    exit;
  } elseif($_POST["step"] == "3" && !isset($_POST["DoBtn"])){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists3_snipped.htm');
    unset($_POST["step"]);

    $_QLJfI = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QLJfI);
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  if(isset($_POST["DoBtn"])) {
    if($_POST["step"] == "3") $_POST["step"]++;

    $_POST["RemovedRecipientsByLocalBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByGlobalBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByLocalDomainBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByGlobalDomainBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByECGlistCount"] = 0;

    $_QLfol = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_QL88I WHERE id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_f80to = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT COUNT(id) FROM $_f80to[MaillistTableName]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_jj6L6 = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    $_POST["MailingListRecipientsCount"] = $_jj6L6[0];

    $_POST["CurrentList"] = "ALL_DONE";

    if(!empty($_POST["RemoveRecipientsByLocalBlocklist"])){
      $_POST["CurrentList"] = "local";
    } else
      if(!empty($_POST["RemoveRecipientsByGlobalBlocklist"])){
        $_POST["CurrentList"] = "global";
        } else
         if(!empty($_POST["RemoveRecipientsByLocalDomainBlocklist"])){
           $_POST["CurrentList"] = "localdomain";
         } else
           if(!empty($_POST["RemoveRecipientsByGlobalDomainBlocklist"])){
             $_POST["CurrentList"] = "globaldomain";
             } else
                if( !empty($_POST["RemoveRecipientsByECGlist"]) ) {
                  $_POST["CurrentList"] = "ECG";
                  $_f8jtC = true;
                }
  }

  unset($_POST["step"]);
  
  if($_POST["CurrentList"] == "ALL_DONE") {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists5_snipped.htm');

    $_QLfol = "SELECT `MaillistTableName` FROM $_QL88I WHERE id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_f80to = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT COUNT(id) FROM $_f80to[MaillistTableName]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_jj6L6 = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_POST["MailingListRecipientsCount"]);
    $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNTAFTER>", "</RECIPIENTSCOUNTAFTER>", $_jj6L6[0]);
    $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByLocalBlocklistCount>", "</RemovedRecipientsByLocalBlocklistCount>", $_POST["RemovedRecipientsByLocalBlocklistCount"]);
    $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByGlobalBlocklistCount>", "</RemovedRecipientsByGlobalBlocklistCount>", $_POST["RemovedRecipientsByGlobalBlocklistCount"]);
    $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByLocalDomainBlocklistCount>", "</RemovedRecipientsByLocalDomainBlocklistCount>", $_POST["RemovedRecipientsByLocalDomainBlocklistCount"]);
    $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByGlobalDomainBlocklistCount>", "</RemovedRecipientsByGlobalDomainBlocklistCount>", $_POST["RemovedRecipientsByGlobalDomainBlocklistCount"]);
    $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByECGlistCount>", "</RemovedRecipientsByECGlistCount>", $_POST["RemovedRecipientsByECGlistCount"]);
    $_QLJfI = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QLJfI);

    print $_QLJfI;
    exit;
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists4_snipped.htm');

  $_QLJfI = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QLJfI);
  if($_POST["CurrentList"] == "local")
     $_QLJfI = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000251"], $_QLJfI);
     else
     if($_POST["CurrentList"] == "global")
       $_QLJfI = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000252"], $_QLJfI);
       else
       if($_POST["CurrentList"] == "localdomain")
          $_QLJfI = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000254"], $_QLJfI);
          else
          if($_POST["CurrentList"] == "globaldomain")
            $_QLJfI = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000255"], $_QLJfI);
            else
            if($_POST["CurrentList"] == "ECG")
              $_QLJfI = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000253"], $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_POST["MailingListRecipientsCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByLocalBlocklistCount>", "</RemovedRecipientsByLocalBlocklistCount>", $_POST["RemovedRecipientsByLocalBlocklistCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByGlobalBlocklistCount>", "</RemovedRecipientsByGlobalBlocklistCount>", $_POST["RemovedRecipientsByGlobalBlocklistCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByLocalDomainBlocklistCount>", "</RemovedRecipientsByLocalDomainBlocklistCount>", $_POST["RemovedRecipientsByLocalDomainBlocklistCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByGlobalDomainBlocklistCount>", "</RemovedRecipientsByGlobalDomainBlocklistCount>", $_POST["RemovedRecipientsByGlobalDomainBlocklistCount"]);
  $_QLJfI = _L81BJ($_QLJfI, "<RemovedRecipientsByECGlistCount>", "</RemovedRecipientsByECGlistCount>", $_POST["RemovedRecipientsByECGlistCount"]);
  $_QLJfI = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QLJfI);

  $_I016j = explode("<!--SPACER//-->", $_QLJfI);
  $_I016j[0] = _L8AOB(array(), $_POST, $_I016j[0]);
  print $_I016j[0];
  flush();
  $_QLJfI = $_I016j[1];
  $_IC1C6 = _L81DB($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QLJfI = _L80DF($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");

  $_f8J16 = 0;
  if(isset($_POST["ListDone"]) && $_POST["ListDone"] > 0) {
   unset($_POST["ListDone"]);
  } else
    if(isset($_POST["CurrentListID"]))
      $_f8J16 = $_POST["CurrentListID"][count($_POST["CurrentListID"]) - 1];

  $_f86fC = 0;

  if($_POST["CurrentList"] == "local" || $_POST["CurrentList"] == "global") {
    if(!isset($_f80to)) {
      $_QLfol = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_QL88I WHERE id=$OneMailingListId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_f80to = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }
    if($_POST["CurrentList"] == "local") {
      $_f8fQ8 = $_f80to["LocalBlocklistTableName"];
      $_f881t = "RemovedRecipientsByLocalBlocklistCount";
      }
      else
       if($_POST["CurrentList"] == "global") {
         $_f8fQ8 = $_I8tfQ;
         $_f881t = "RemovedRecipientsByGlobalBlocklistCount";
       }

    $_QLfol = "SELECT `$_f80to[MaillistTableName]`.id, `$_f80to[MaillistTableName]`.u_EMail FROM `$_f80to[MaillistTableName]` LEFT JOIN `$_f8fQ8`".$_QLl1Q;
    $_QLfol .= " ON `$_f8fQ8`.`u_EMail` = `$_f80to[MaillistTableName]`.`u_EMail`".$_QLl1Q;
    $_QLfol .= " WHERE `$_f80to[MaillistTableName]`.id>$_f8J16 AND `$_f8fQ8`.`u_EMail` IS NOT NULL".$_QLl1Q;
    $_QLfol .= " ORDER BY `$_f80to[MaillistTableName]`.id ".$_QLl1Q;
    $_QLfol .= " LIMIT 0, $_ffCj8";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_f86fC++;
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = str_replace('name="CurrentListID[]"', 'name="CurrentListID[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", $_QLO0f["u_EMail"]);

      include_once("recipients_ops.inc.php");

      $_I8I6o = $_f80to["MaillistTableName"];
      $_I8jjj = $_f80to["StatisticsTableName"];
      $_IfJ66 = $_f80to["MailListToGroupsTableName"];
      $MailingListId = $OneMailingListId;
      $_I8jLt = $_f80to["MailLogTableName"];

      $_I8oIJ = array();

      $_I8oIJ[] = $_QLO0f["id"];
      $_IQ0Cj = array();
      _J1OQP($_I8oIJ, $_IQ0Cj);

      $_JIfo0 = $resourcestrings[$INTERFACE_LANGUAGE]["000260"];
      if(count($_IQ0Cj) == 0)
        $_POST[$_f881t] += count($_I8oIJ);
        else {
          $_JIfo0 = join("<br />", $_IQ0Cj);
        }

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $_JIfo0);

      print $_QLl1Q.$_Ql0fO;
      flush();

      _LRCOC();

    }
    mysql_free_result($_QL8i1);
  }

  if($_POST["CurrentList"] == "globaldomain" || $_POST["CurrentList"] == "localdomain") {

    if(!isset($_f80to)) {
      $_QLfol = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_QL88I WHERE id=$OneMailingListId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_f80to = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      if($_POST["CurrentList"] == "localdomain")
        $_QLfol = "SELECT id FROM `$_f80to[LocalDomainBlocklistTableName]` LIMIT 0, 1";
        else
        $_QLfol = "SELECT id FROM `$_I8OoJ` LIMIT 0, 1";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_f88Qt = mysql_num_rows($_QL8i1);
      mysql_free_result($_QL8i1);
    }

    if($_POST["CurrentList"] == "localdomain") {
      $_f881t = "RemovedRecipientsByLocalDomainBlocklistCount";
    }
    if($_POST["CurrentList"] == "globaldomain") {
      $_f881t = "RemovedRecipientsByGlobalDomainBlocklistCount";
    }

    $_QLfol = "SELECT `$_f80to[MaillistTableName]`.id, `$_f80to[MaillistTableName]`.u_EMail FROM `$_f80to[MaillistTableName]` ".$_QLl1Q;
    $_QLfol .= " WHERE `$_f80to[MaillistTableName]`.id>$_f8J16 AND $_f88Qt > 0".$_QLl1Q;
    $_QLfol .= " ORDER BY `$_f80to[MaillistTableName]`.id ".$_QLl1Q;
    $_QLfol .= " LIMIT 0, $_ffCj8";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_f86fC++;
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = str_replace('name="CurrentListID[]"', 'name="CurrentListID[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", $_QLO0f["u_EMail"]);

      include_once("recipients_ops.inc.php");
      if($_POST["CurrentList"] == "localdomain") {
         $_f8t18 = _J1P6D($_QLO0f["u_EMail"], $OneMailingListId, $_f80to["LocalDomainBlocklistTableName"]);
      } else
         $_f8t18 = _J1PQO($_QLO0f["u_EMail"]);

      if($_f8t18) {

        $_I8I6o = $_f80to["MaillistTableName"];
        $_I8jjj = $_f80to["StatisticsTableName"];
        $_IfJ66 = $_f80to["MailListToGroupsTableName"];
        $MailingListId = $OneMailingListId;
        $_I8jLt = $_f80to["MailLogTableName"];

        $_I8oIJ = array();

        $_I8oIJ[] = $_QLO0f["id"];
        $_IQ0Cj = array();
        _J1OQP($_I8oIJ, $_IQ0Cj);

        $_JIfo0 = $resourcestrings[$INTERFACE_LANGUAGE]["000260"];
        if(count($_IQ0Cj) == 0)
          $_POST[$_f881t] += count($_I8oIJ);
          else {
            $_JIfo0 = join("<br />", $_IQ0Cj);
          }
      } else
        $_JIfo0 = $resourcestrings[$INTERFACE_LANGUAGE]["000262"];

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $_JIfo0);

      print $_QLl1Q.$_Ql0fO;
      flush();

      _LRCOC();

    }
    mysql_free_result($_QL8i1);

  }


  if($_POST["CurrentList"] == "ECG") {
    $_f881t="RemovedRecipientsByECGlistCount";
    if(!isset($_f80to)) {
      $_QLfol = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_QL88I WHERE id=$OneMailingListId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_f80to = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }

    $_QLfol = "SELECT `$_f80to[MaillistTableName]`.id, `$_f80to[MaillistTableName]`.`u_EMail` FROM `$_f80to[MaillistTableName]` ".$_QLl1Q;
    $_QLfol .= " WHERE `$_f80to[MaillistTableName]`.id>$_f8J16 ".$_QLl1Q;
    $_QLfol .= " ORDER BY `$_f80to[MaillistTableName]`.id ".$_QLl1Q;
    $_QLfol .= " LIMIT 0, $_ffCj8";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_J06Ji = array();
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_J06Ji[] = array("email" => $_QLO0f["u_EMail"]/*, "id" => $_QLO0f["id"]*/);
    }
    
    $_J0fIj = array();
    $_J08Q1 = "";
    $_J0t0L = _L6AF6($_J06Ji, $_J0fIj, $_J08Q1);    
    unset($_J06Ji); 
    
    mysql_data_seek($_QL8i1, 0);
    
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_f86fC++;
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = str_replace('name="CurrentListID[]"', 'name="CurrentListID[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", $_QLO0f["u_EMail"]);

      $_I016j = $_J0t0L && array_search($_QLO0f["u_EMail"], array_column($_J0fIj, 'email')) !== false;
      
      if( $_I016j ) {
        include_once("recipients_ops.inc.php");

        $_I8I6o = $_f80to["MaillistTableName"];
        $_I8jjj = $_f80to["StatisticsTableName"];
        $_IfJ66 = $_f80to["MailListToGroupsTableName"];
        $MailingListId = $OneMailingListId;
        $_I8jLt = $_f80to["MailLogTableName"];

        $_I8oIJ = array();

        $_I8oIJ[] = $_QLO0f["id"];
        $_IQ0Cj = array();
        _J1OQP($_I8oIJ, $_IQ0Cj);

        $_JIfo0 = $resourcestrings[$INTERFACE_LANGUAGE]["000260"];
        if(count($_IQ0Cj) == 0)
          $_POST[$_f881t] += count($_I8oIJ);
          else {
            $_JIfo0 = join("<br />", $_IQ0Cj);
          }
      } else
        $_JIfo0 = $resourcestrings[$INTERFACE_LANGUAGE]["000261"];

      if($_J0t0L)
        $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $_JIfo0);
        else
        $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", $_J08Q1);

      print $_QLl1Q.$_Ql0fO;
      flush();

      _LRCOC();

    }
    mysql_free_result($_QL8i1);

    if($_f86fC > 0 && !isset($_f8jtC)) {
       sleep(10);
    }  
  }

  if($_f86fC == 0) {
    $_Ql0fO = $_IC1C6;
    $_Ql0fO = str_replace('name="CurrentListID[]"', 'name="_CurrentListID" value=""', $_Ql0fO);
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:EMAIL>", "</LIST:EMAIL>", "&nbsp;");
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:STATUS>", "</LIST:STATUS>", "&nbsp;");
    print $_QLl1Q.$_Ql0fO;
    flush();
  }

  if($_f86fC < $_ffCj8) {

    $_POST["ListDone"] = 1; # start again at 0

    switch ($_POST["CurrentList"]) {
      case 'local':
          $_POST["CurrentList"] = "global";
          if(!empty($_POST["RemoveRecipientsByGlobalBlocklist"]))
             break;
      case 'global':
          $_POST["CurrentList"] = "localdomain";
          if(!empty($_POST["RemoveRecipientsByLocalDomainBlocklist"]))
             break;
      case 'localdomain':
          $_POST["CurrentList"] = "globaldomain";
          if(!empty($_POST["RemoveRecipientsByGlobalDomainBlocklist"]))
             break;
      case 'globaldomain':
          $_POST["CurrentList"] = "ECG";
          if( !empty($_POST["RemoveRecipientsByECGlist"]) )
              break;
      case 'ECG':
          $_POST["CurrentList"] = "ALL_DONE";
          break;
      default:
         $_POST["CurrentList"] = "ALL_DONE";
    }

  }

  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
  print $_QLJfI;
?>
