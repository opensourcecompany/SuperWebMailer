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

  $_616LJ = 50;

  if (isset($_POST["OneMailingListId"]) )
     $OneMailingListId = intval($_POST["OneMailingListId"]);
     else {
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000250"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = intval($_POST["OneMailingListId"]);
     }

  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  if(!isset($_POST["MailingListName"])) {
    $_QJlJ0 = "SELECT Name FROM $_Q60QL WHERE id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_POST["MailingListName"] = $_Q6Q1C[0];
  }

  if(isset($_POST["NextBtn"]) && isset($_POST["step"]))
     $_POST["step"]++;

  if(isset($_POST["PrevBtn"]) && isset($_POST["step"]))
     $_POST["step"]--;
  if(isset($_POST["step"]) && $_POST["step"] < 0) $_POST["step"] = 1;

  if(!isset($_POST["step"]) || $_POST["step"] == "1" ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists1_snipped.htm');
    $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
    $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);

    print $_QJCJi;
    exit;
  }

  if($_POST["step"] == "2"){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists2_snipped.htm');
    $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
    $_QJCJi = str_replace('name="MailingListName"', 'name="MailingListName" value="'.$_POST["MailingListName"].'"', $_QJCJi);

    print $_QJCJi;
    exit;
  } elseif($_POST["step"] == "3" && !isset($_POST["DoBtn"])){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists3_snipped.htm');
    unset($_POST["step"]);

    $_QJCJi = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QJCJi);
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  if(isset($_POST["DoBtn"])) {
    if($_POST["step"] == "3") $_POST["step"]++;

    $_POST["RemovedRecipientsByLocalBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByGlobalBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByLocalDomainBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByGlobalDomainBlocklistCount"] = 0;
    $_POST["RemovedRecipientsByECGlistCount"] = 0;

    $_QJlJ0 = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_Q60QL WHERE id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_61Ot0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT COUNT(id) FROM $_61Ot0[MaillistTableName]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_IO08Q = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    $_POST["MailingListRecipientsCount"] = $_IO08Q[0];

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
                }
  }

  unset($_POST["step"]);

  if($_POST["CurrentList"] == "ALL_DONE") {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists5_snipped.htm');

    $_QJlJ0 = "SELECT `MaillistTableName` FROM $_Q60QL WHERE id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_61Ot0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT COUNT(id) FROM $_61Ot0[MaillistTableName]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_IO08Q = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_POST["MailingListRecipientsCount"]);
    $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNTAFTER>", "</RECIPIENTSCOUNTAFTER>", $_IO08Q[0]);
    $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByLocalBlocklistCount>", "</RemovedRecipientsByLocalBlocklistCount>", $_POST["RemovedRecipientsByLocalBlocklistCount"]);
    $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByGlobalBlocklistCount>", "</RemovedRecipientsByGlobalBlocklistCount>", $_POST["RemovedRecipientsByGlobalBlocklistCount"]);
    $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByLocalDomainBlocklistCount>", "</RemovedRecipientsByLocalDomainBlocklistCount>", $_POST["RemovedRecipientsByLocalDomainBlocklistCount"]);
    $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByGlobalDomainBlocklistCount>", "</RemovedRecipientsByGlobalDomainBlocklistCount>", $_POST["RemovedRecipientsByGlobalDomainBlocklistCount"]);
    $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByECGlistCount>", "</RemovedRecipientsByECGlistCount>", $_POST["RemovedRecipientsByECGlistCount"]);
    $_QJCJi = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QJCJi);

    print $_QJCJi;
    exit;
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_POST["MailingListName"]." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000250"], "", 'ml_remove_recipients_by_blocklists', 'ml_remove_recipients_by_blocklists4_snipped.htm');

  $_QJCJi = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QJCJi);
  if($_POST["CurrentList"] == "local")
     $_QJCJi = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000251"], $_QJCJi);
     else
     if($_POST["CurrentList"] == "global")
       $_QJCJi = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000252"], $_QJCJi);
       else
       if($_POST["CurrentList"] == "localdomain")
          $_QJCJi = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000254"], $_QJCJi);
          else
          if($_POST["CurrentList"] == "globaldomain")
            $_QJCJi = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000255"], $_QJCJi);
            else
            if($_POST["CurrentList"] == "ECG")
              $_QJCJi = str_replace('%MLREMOVEPAGETITLE%', $resourcestrings[$INTERFACE_LANGUAGE]["000253"], $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", $_POST["MailingListRecipientsCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByLocalBlocklistCount>", "</RemovedRecipientsByLocalBlocklistCount>", $_POST["RemovedRecipientsByLocalBlocklistCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByGlobalBlocklistCount>", "</RemovedRecipientsByGlobalBlocklistCount>", $_POST["RemovedRecipientsByGlobalBlocklistCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByLocalDomainBlocklistCount>", "</RemovedRecipientsByLocalDomainBlocklistCount>", $_POST["RemovedRecipientsByLocalDomainBlocklistCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByGlobalDomainBlocklistCount>", "</RemovedRecipientsByGlobalDomainBlocklistCount>", $_POST["RemovedRecipientsByGlobalDomainBlocklistCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<RemovedRecipientsByECGlistCount>", "</RemovedRecipientsByECGlistCount>", $_POST["RemovedRecipientsByECGlistCount"]);
  $_QJCJi = str_replace('%MAILINGLISTNAME%', $_POST["MailingListName"], $_QJCJi);

  $_QllO8 = explode("<!--SPACER//-->", $_QJCJi);
  $_QllO8[0] = _OPFJA(array(), $_POST, $_QllO8[0]);
  print $_QllO8[0];
  flush();
  $_QJCJi = $_QllO8[1];
  $_IIJi1 = _OP81D($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QJCJi = _OP6PQ($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");

  $_61LOI = 0;
  if(isset($_POST["ListDone"]) && $_POST["ListDone"] > 0) {
   unset($_POST["ListDone"]);
  } else
    if(isset($_POST["CurrentListID"]))
      $_61LOI = $_POST["CurrentListID"][count($_POST["CurrentListID"]) - 1];

  $_61lII = 0;

  if($_POST["CurrentList"] == "local" || $_POST["CurrentList"] == "global") {
    if(!isset($_61Ot0)) {
      $_QJlJ0 = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_Q60QL WHERE id=$OneMailingListId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_61Ot0 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }
    if($_POST["CurrentList"] == "local") {
      $_61lij = $_61Ot0["LocalBlocklistTableName"];
      $_6Q0oC = "RemovedRecipientsByLocalBlocklistCount";
      }
      else
       if($_POST["CurrentList"] == "global") {
         $_61lij = $_Ql8C0;
         $_6Q0oC = "RemovedRecipientsByGlobalBlocklistCount";
       }

    $_QJlJ0 = "SELECT `$_61Ot0[MaillistTableName]`.id, `$_61Ot0[MaillistTableName]`.u_EMail FROM `$_61Ot0[MaillistTableName]` LEFT JOIN `$_61lij`".$_Q6JJJ;
    $_QJlJ0 .= " ON `$_61lij`.`u_EMail` = `$_61Ot0[MaillistTableName]`.`u_EMail`".$_Q6JJJ;
    $_QJlJ0 .= " WHERE `$_61Ot0[MaillistTableName]`.id>$_61LOI AND `$_61lij`.`u_EMail` IS NOT NULL".$_Q6JJJ;
    $_QJlJ0 .= " ORDER BY `$_61Ot0[MaillistTableName]`.id ".$_Q6JJJ;
    $_QJlJ0 .= " LIMIT 0, $_616LJ";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_61lII++;
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = str_replace('name="CurrentListID[]"', 'name="CurrentListID[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:EMAIL>", "</LIST:EMAIL>", $_Q6Q1C["u_EMail"]);

      include_once("recipients_ops.inc.php");

      $_QlQC8 = $_61Ot0["MaillistTableName"];
      $_QlIf6 = $_61Ot0["StatisticsTableName"];
      $_QLI68 = $_61Ot0["MailListToGroupsTableName"];
      $MailingListId = $OneMailingListId;
      $_QljIQ = $_61Ot0["MailLogTableName"];

      $_QltCO = array();

      $_QltCO[] = $_Q6Q1C["id"];
      $_QtIiC = array();
      _L10CL($_QltCO, $_QtIiC);

      $_j6O8O = $resourcestrings[$INTERFACE_LANGUAGE]["000260"];
      if(count($_QtIiC) == 0)
        $_POST[$_6Q0oC] += count($_QltCO);
        else {
          $_j6O8O = join("<br />", $_QtIiC);
        }

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", $_j6O8O);

      print $_Q6JJJ.$_Q66jQ;
      flush();

      _OPQ6J();

    }
    mysql_free_result($_Q60l1);
  }

  if($_POST["CurrentList"] == "globaldomain" || $_POST["CurrentList"] == "localdomain") {

    if(!isset($_61Ot0)) {
      $_QJlJ0 = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_Q60QL WHERE id=$OneMailingListId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_61Ot0 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      if($_POST["CurrentList"] == "localdomain")
        $_QJlJ0 = "SELECT id FROM `$_61Ot0[LocalDomainBlocklistTableName]` LIMIT 0, 1";
        else
        $_QJlJ0 = "SELECT id FROM `$_Qlt66` LIMIT 0, 1";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_6Q1jj = mysql_num_rows($_Q60l1);
      mysql_free_result($_Q60l1);
    }

    if($_POST["CurrentList"] == "localdomain") {
      $_6Q0oC = "RemovedRecipientsByLocalDomainBlocklistCount";
    }
    if($_POST["CurrentList"] == "globaldomain") {
      $_6Q0oC = "RemovedRecipientsByGlobalDomainBlocklistCount";
    }

    $_QJlJ0 = "SELECT `$_61Ot0[MaillistTableName]`.id, `$_61Ot0[MaillistTableName]`.u_EMail FROM `$_61Ot0[MaillistTableName]` ".$_Q6JJJ;
    $_QJlJ0 .= " WHERE `$_61Ot0[MaillistTableName]`.id>$_61LOI AND $_6Q1jj > 0".$_Q6JJJ;
    $_QJlJ0 .= " ORDER BY `$_61Ot0[MaillistTableName]`.id ".$_Q6JJJ;
    $_QJlJ0 .= " LIMIT 0, $_616LJ";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_61lII++;
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = str_replace('name="CurrentListID[]"', 'name="CurrentListID[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:EMAIL>", "</LIST:EMAIL>", $_Q6Q1C["u_EMail"]);

      include_once("recipients_ops.inc.php");
      if($_POST["CurrentList"] == "localdomain") {
         $_6QQIQ = _L1RDP($_Q6Q1C["u_EMail"], $OneMailingListId, $_61Ot0["LocalDomainBlocklistTableName"]);
      } else
         $_6QQIQ = _L1ROL($_Q6Q1C["u_EMail"]);

      if($_6QQIQ) {

        $_QlQC8 = $_61Ot0["MaillistTableName"];
        $_QlIf6 = $_61Ot0["StatisticsTableName"];
        $_QLI68 = $_61Ot0["MailListToGroupsTableName"];
        $MailingListId = $OneMailingListId;
        $_QljIQ = $_61Ot0["MailLogTableName"];

        $_QltCO = array();

        $_QltCO[] = $_Q6Q1C["id"];
        $_QtIiC = array();
        _L10CL($_QltCO, $_QtIiC);

        $_j6O8O = $resourcestrings[$INTERFACE_LANGUAGE]["000260"];
        if(count($_QtIiC) == 0)
          $_POST[$_6Q0oC] += count($_QltCO);
          else {
            $_j6O8O = join("<br />", $_QtIiC);
          }
      } else
        $_j6O8O = $resourcestrings[$INTERFACE_LANGUAGE]["000262"];

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", $_j6O8O);

      print $_Q6JJJ.$_Q66jQ;
      flush();

      _OPQ6J();

    }
    mysql_free_result($_Q60l1);

  }


  if($_POST["CurrentList"] == "ECG") {
    $_6Q0oC="RemovedRecipientsByECGlistCount";
    if(!isset($_61Ot0)) {
      $_QJlJ0 = "SELECT `MaillistTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName`, `LocalDomainBlocklistTableName`, `StatisticsTableName`, `MailLogTableName` FROM $_Q60QL WHERE id=$OneMailingListId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_61Ot0 = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }

    $_QJlJ0 = "SELECT `$_61Ot0[MaillistTableName]`.id, `$_61Ot0[MaillistTableName]`.u_EMail FROM `$_61Ot0[MaillistTableName]` ".$_Q6JJJ;
    $_QJlJ0 .= " WHERE `$_61Ot0[MaillistTableName]`.id>$_61LOI ".$_Q6JJJ;
    $_QJlJ0 .= " ORDER BY `$_61Ot0[MaillistTableName]`.id ".$_Q6JJJ;
    $_QJlJ0 .= " LIMIT 0, $_616LJ";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_61lII++;
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = str_replace('name="CurrentListID[]"', 'name="CurrentListID[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:EMAIL>", "</LIST:EMAIL>", $_Q6Q1C["u_EMail"]);

      if(_OC0DR($_Q6Q1C["u_EMail"])) {
        include_once("recipients_ops.inc.php");

        $_QlQC8 = $_61Ot0["MaillistTableName"];
        $_QlIf6 = $_61Ot0["StatisticsTableName"];
        $_QLI68 = $_61Ot0["MailListToGroupsTableName"];
        $MailingListId = $OneMailingListId;
        $_QljIQ = $_61Ot0["MailLogTableName"];

        $_QltCO = array();

        $_QltCO[] = $_Q6Q1C["id"];
        $_QtIiC = array();
        _L10CL($_QltCO, $_QtIiC);

        $_j6O8O = $resourcestrings[$INTERFACE_LANGUAGE]["000260"];
        if(count($_QtIiC) == 0)
          $_POST[$_6Q0oC] += count($_QltCO);
          else {
            $_j6O8O = join("<br />", $_QtIiC);
          }
      } else
        $_j6O8O = $resourcestrings[$INTERFACE_LANGUAGE]["000261"];

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", $_j6O8O);

      print $_Q6JJJ.$_Q66jQ;
      flush();

      _OPQ6J();

    }
    mysql_free_result($_Q60l1);

  }

  if($_61lII == 0) {
    $_Q66jQ = $_IIJi1;
    $_Q66jQ = str_replace('name="CurrentListID[]"', 'name="_CurrentListID" value=""', $_Q66jQ);
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:EMAIL>", "</LIST:EMAIL>", "&nbsp;");
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:STATUS>", "</LIST:STATUS>", "&nbsp;");
    print $_Q6JJJ.$_Q66jQ;
    flush();
  }

  if($_61lII < $_616LJ) {

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

  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
  print $_QJCJi;
?>
