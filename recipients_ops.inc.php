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
  // used from browserecpts.php, browsmailinglists.php, nl.php, defaultnewsletter.php, cron_subunsubcheck.inc.php, cron_bounces.inc.php

  include_once("responders_cleanup.inc.php");
  include_once("maillogger.php");

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  $_I8oIJ = array();
  $_I8I6o = "";
  $_I8jjj = "";
  $_IfJ66 = "";
  $MailingListId = 0;
  $_I8jLt = "";
  $_I8Jti = "";
  $_jJi11 = array();
  $_IQ0Cj = array();
  // cron_subunsubcheck.inc.php, cron_bounces.inc.php will set the parameters itself

  if(!defined("API") && !defined("EditRecipient"))
    _J1QBE();

  function _J1QBE() {
    global $_QL88I, $_QLttI;
    global $_I8oIJ, $MailingListId;
    global $_I8I6o, $_I8jjj, $_IfJ66, $_I8jLt, $_I8Jti, $_QljJi;
    global $_jJi11, $_IQ0Cj;

    $_I8oIJ = array();
    if ( isset($_POST["OneRecipientId"]) && $_POST["OneRecipientId"] != "" )
        $_I8oIJ[] = $_POST["OneRecipientId"];
        else
        if ( isset($_POST["RecipientsIDs"]) )
          $_I8oIJ = array_merge($_I8oIJ, $_POST["RecipientsIDs"]);


    // get the table
    if ( isset($_POST["OneMailingListId"]) && intval($_POST["OneMailingListId"]) != 0) {
      $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);

      if(!defined("CRONS_PHP") && !_LAEJL($_POST["OneMailingListId"])){
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $_QLJfI;
        exit;
      }

      $_QLfol = "SELECT id, MaillistTableName, StatisticsTableName, MailListToGroupsTableName, MailLogTableName, EditTableName, GroupsTableName FROM $_QL88I WHERE id=".$_POST["OneMailingListId"];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      $_I8I6o = $_QLO0f["MaillistTableName"];
      $_I8jjj = $_QLO0f["StatisticsTableName"];
      $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
      $MailingListId  = $_QLO0f["id"];
      $_I8jLt = $_QLO0f["MailLogTableName"];
      $_I8Jti = $_QLO0f["EditTableName"];
      $_QljJi = $_QLO0f["GroupsTableName"];

      mysql_free_result($_QL8i1);
    }
  }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "RemoveRecipients" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "DeleteRecipient" )
     ) {
          if(isset($_IQ0Cj))
            unset($_IQ0Cj);
          $_IQ0Cj = array();
          _J1OQP($_I8oIJ, $_IQ0Cj);
    }


  if  ( isset($_POST["RecipientsActions"]) && ( $_POST["RecipientsActions"] == "AssignToGroups" || $_POST["RecipientsActions"] == "AssignToGroupsAdditionally" )   ) {
     if(isset($_POST["Groups"]))
        _J1J0O($_I8oIJ, $_IfJ66, $_POST["Groups"], $_POST["RecipientsActions"] == "AssignToGroupsAdditionally");
        else
        if(isset($_POST["RecipientGroups"]))
         _J1J0O($_I8oIJ, $_IfJ66, $_POST["RecipientGroups"], $_POST["RecipientsActions"] == "AssignToGroupsAdditionally");
        else
         _J1J0O($_I8oIJ, $_IfJ66, array(), $_POST["RecipientsActions"] == "AssignToGroupsAdditionally");
  }


  if  ( isset($_POST["RecipientsActions"]) && ($_POST["RecipientsActions"] == "RemoveFromGroups")   ) {
     if(isset($_POST["Groups"]))
        _J1JJL($_I8oIJ, $MailingListId, $_IfJ66, $_POST["Groups"]);
        else
        if(isset($_POST["RecipientGroups"]))
          _J1JJL($_I8oIJ, $MailingListId, $_IfJ66, $_POST["RecipientGroups"]);
          else
          _J1J0O($_I8oIJ, $_IfJ66, array());
  }

  if  ( isset($_POST["RecipientsActions"]) && ( ($_POST["RecipientsActions"] == "MoveRecipients") || ($_POST["RecipientsActions"] == "CopyRecipients") ) ) {

     if(!defined("CRONS_PHP") && !_LAEJL($_POST["DestMailingList"])){
       $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
       $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
       print $_QLJfI;
       exit;
     }

     // get the table
     $_QLfol = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName, EditTableName, MailListToGroupsTableName, GroupsTableName FROM $_QL88I WHERE id=".intval($_POST["DestMailingList"]);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_ff1CQ = $_QLO0f[0];
     $_ffQOo = $_QLO0f[1];
     $_ffQo8 = $_QLO0f[2];
     $_ffIf6 = $_QLO0f[3];
     $_fi8Ql = $_QLO0f[4];
     $_fi8tj = $_QLO0f[5];
     mysql_free_result($_QL8i1);
     $_jJi11 = array();
      if($_ff1CQ == $_I8I6o) {
         $_jJi11[] = $resourcestrings[$INTERFACE_LANGUAGE]["000043"];
      }
      else
       _J1O6L($_I8oIJ, $_jJi11, $_ff1CQ, $_ffQOo, $_ffQo8, $_ffIf6, ($_POST["RecipientsActions"] == "MoveRecipients"), "", isset($_POST["CopyMoveGroupsAssignment"]) && $_POST["CopyMoveGroupsAssignment"] == 1, $_fi8tj, $_fi8Ql);
  }

  if  ( isset($_POST["RecipientsActions"]) && ( ($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") || ($_POST["RecipientsActions"] == "AddRecipientToGlobalBlacklist") ) ) {
    if($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") {
      $_QLfol = "SELECT LocalBlocklistTableName FROM $_QL88I WHERE id=".$_POST["OneMailingListId"];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QLO0f = mysql_fetch_row($_QL8i1);
      $_jjj8f = $_QLO0f[0];
      _J1LOQ($_I8oIJ, $_jjj8f, $_I8I6o, $_I8jjj);
    } else {
      _J1LOQ($_I8oIJ, $_I8tfQ, $_I8I6o, $_I8jjj);
    }
  }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "ResetBounceState" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "ResetBounceState" )
     ) {
          if(isset($_IQ0Cj))
            unset($_IQ0Cj);
          $_IQ0Cj = array();
          _J16F0($_I8oIJ, $_IQ0Cj);
    }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "ActivateRecipients" ) ||
        ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "ActivateRecipient" )
        ||
        ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "ResetInactiveState" ) ||
        ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "ResetInactiveState" )
     ) {
          if(isset($_IQ0Cj))
            unset($_IQ0Cj);
          $_IQ0Cj = array();
          _J1RD0(true, $_I8oIJ, $_IQ0Cj);
    }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "DeactivateRecipients" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "DeactivateRecipient" )

        ||
        ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "SetInactiveState" ) ||
        ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "SetInactiveState" )

     ) {
          if(isset($_IQ0Cj))
            unset($_IQ0Cj);
          $_IQ0Cj = array();
          _J1RD0(false, $_I8oIJ, $_IQ0Cj);
    }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "SetSubscribedState" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "SetSubscribedState" )
     ) {
          if(isset($_IQ0Cj))
            unset($_IQ0Cj);
          $_IQ0Cj = array();
          _J1RER($_I8oIJ, $_IQ0Cj);
    }

  // we don't check for errors here
 function _J1OQP($_I8oIJ, &$_IQ0Cj, $_fiOI0 = true) {
    global $_I8I6o, $_I8jjj, $_IfJ66, $_I8jLt, $_I8Jti, $MailingListId, $_QLttI;

    reset($_I8oIJ);
    foreach($_I8oIJ as $_Qli6J => $_QltJO) {
      $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);

      mysql_query("BEGIN", $_QLttI);

      // Delete recipient
      $_QLfol = "DELETE FROM `$_I8I6o` WHERE id=".$_I8oIJ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      // Delete maillog, members edit, groups assignment, statistics
      $_J1QfQ = array("`$_I8jLt`", "`$_I8Jti`", "`$_IfJ66`");
      if($_fiOI0)
         $_J1QfQ[] = "`$_I8jjj`";

      for($_QliOt=0; $_QliOt<count($_J1QfQ); $_QliOt++){
        $_QLfol = "DELETE FROM $_J1QfQ[$_QliOt] WHERE `Member_id`=$_I8oIJ[$_Qli6J]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      }

      mysql_query("COMMIT", $_QLttI);
    }

    if($MailingListId != 0) {
      _JQJCP($MailingListId, $_I8oIJ);
    }

  }

 function _J1O6L($_I8oIJ, &$_jJi11, $_ff1CQ, $_ffQOo, $_ffQo8, $_ffIf6, $_ffj1J, $_fiOoQ = "", $_fiOLo = false, $_fi8tj = "", $_fi8Ql = "") {
  global $_I8I6o, $_I8jjj, $_IfJ66, $MailingListId, $_I8jLt, $_I8Jti, $_QljJi, $_QLttI;

  $_Iflj0 = array();
  _L8EOB($_I8I6o, $_Iflj0, array("id"));
  $_IOJoI = join(", ", $_Iflj0);

  unset($_Iflj0);
  $_Iflj0 = array();
  _L8EOB($_I8jjj, $_Iflj0, array("Member_id"));
  $_fioJL = join(", ", $_Iflj0);

  $_Iflj0 = array();
  _L8EOB($_I8jLt, $_Iflj0, array("Member_id"));
  $_fio8O = join(", ", $_Iflj0);

  $_Iflj0 = array();
  _L8EOB($_I8Jti, $_Iflj0, array("Member_id"));
  $_fioO6 = join(", ", $_Iflj0);


  if(!empty($_fiOoQ)){
    $_fOolj = new _LFBOB();
  }

  $_fiooJ = array();
  if($_fiOLo && !empty($_fi8tj) && !empty($_fi8Ql) && !empty($_QljJi) && !empty($_IfJ66)){
    // Src ==> Dest List groups name = IDs
    $_QLfol = "SELECT $_QljJi.id AS g1_id, $_fi8tj.id AS g2_id
           FROM $_QljJi
           LEFT JOIN $_fi8tj ON LOWER($_QljJi.Name)=LOWER($_fi8tj.Name)
           WHERE $_QljJi.id IS NOT NULL AND $_fi8tj.id IS NOT NULL";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
      $_fiooJ[$_QLO0f['g1_id']] = $_QLO0f['g2_id'];
    }
    mysql_free_result($_QL8i1);
  }

  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {

     mysql_query("BEGIN", $_QLttI);

     $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
     // der Empfaenger selbst
     if($_ffj1J)
        $_QLfol = "INSERT INTO `$_ff1CQ` (".$_IOJoI.") ";
        else
        $_QLfol = "INSERT IGNORE INTO `$_ff1CQ` (".$_IOJoI.") ";
     $_QLfol .= "SELECT $_IOJoI FROM `$_I8I6o` WHERE id=$_I8oIJ[$_Qli6J]";
     mysql_query($_QLfol, $_QLttI);
     if (mysql_error($_QLttI) != "") {
          $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
          unset($_I8oIJ[$_Qli6J]);
        }
        else {
         $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
         if (mysql_error($_QLttI) != "") { # not inserted
           $_jJi11[] = "Duplicate entry for id $_I8oIJ[$_Qli6J]";
           unset($_I8oIJ[$_Qli6J]);
           if($_QL8i1)
             mysql_free_result($_QL8i1);
           mysql_query("ROLLBACK", $_QLttI);
           continue;
         }
         $_QLO0f=mysql_fetch_array($_QL8i1);
         $ID = $_QLO0f[0];
         if (mysql_error($_QLttI) != "" || $ID == 0) { # not inserted
           $_jJi11[] = "Duplicate entry for id $_I8oIJ[$_Qli6J]";
           unset($_I8oIJ[$_Qli6J]);
           if($_QL8i1)
             mysql_free_result($_QL8i1);
           mysql_query("ROLLBACK", $_QLttI);
           continue;
         }
         mysql_free_result($_QL8i1);

         // Statistik
         $_QLfol = "SELECT $_fioJL FROM `$_I8jjj` WHERE Member_id=$_I8oIJ[$_Qli6J]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
           while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             $_QLfol = "INSERT INTO `$_ffQOo` (".$_fioJL.", Member_id) VALUES (";
             if (isset($_IOCjL)) unset($_IOCjL);
             $_IOCjL = array();
             foreach ($_QLO0f as $key => $_QltJO) {
                $_IOCjL[] = _LRAFO($_QltJO);
             }
             $_QLfol .= join(", ", $_IOCjL);
             $_QLfol .= ", $ID)";
             mysql_query($_QLfol, $_QLttI);
             if (mysql_error($_QLttI) != "")
                $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
           }
         }

         // maillog
         $_QLfol = "SELECT $_fio8O FROM `$_I8jLt` WHERE Member_id=$_I8oIJ[$_Qli6J]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
           while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             $_QLfol = "INSERT INTO `$_ffQo8` (".$_fio8O.", Member_id) VALUES (";
             if (isset($_IOCjL)) unset($_IOCjL);
             $_IOCjL = array();
             foreach ($_QLO0f as $key => $_QltJO) {
                $_IOCjL[] = _LRAFO($_QltJO);
             }
             $_QLfol .= join(", ", $_IOCjL);
             $_QLfol .= ", $ID)";
             mysql_query($_QLfol, $_QLttI);
             if (mysql_error($_QLttI) != "")
                $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
           }
         }

         // edit log
         $_QLfol = "SELECT $_fioO6 FROM `$_I8Jti` WHERE Member_id=$_I8oIJ[$_Qli6J]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
           while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             $_QLfol = "INSERT INTO `$_ffIf6` (".$_fioO6.", Member_id) VALUES (";
             if (isset($_IOCjL)) unset($_IOCjL);
             $_IOCjL = array();
             foreach ($_QLO0f as $key => $_QltJO) {
                $_IOCjL[] = _LRAFO($_QltJO);
             }
             $_QLfol .= join(", ", $_IOCjL);
             $_QLfol .= ", $ID)";
             mysql_query($_QLfol, $_QLttI);
             if (mysql_error($_QLttI) != "")
                $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
           }
         }

         // groups
         if($_fiOLo && count($_fiooJ)){
           $_QLfol = "SELECT `groups_id` FROM `$_IfJ66` WHERE `Member_id`=$_I8oIJ[$_Qli6J]";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             if(isset($_fiooJ[$_QLO0f["groups_id"]])){
                $_QLfol = "INSERT INTO `$_fi8Ql` (`groups_id`, `Member_id`) VALUES(".$_fiooJ[$_QLO0f["groups_id"]].", $ID)";
                mysql_query($_QLfol, $_QLttI);
                if (mysql_error($_QLttI) != "")
                  $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
             }
           }
           mysql_free_result($_QL8i1);
         }

         if(!empty($_fiOoQ)){
           $_fOolj->_LF0QR($_ffQo8, $ID, $_fiOoQ);
         }

         // loeschen
         if($_ffj1J) {
            // Empfaenger loeschen
            $_QLfol = "DELETE FROM `$_I8I6o` WHERE `id`=$_I8oIJ[$_Qli6J]";
            mysql_query($_QLfol, $_QLttI);
            if (mysql_error($_QLttI) != "")
               $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

            // Delete maillog, members edit, groups assignment, statistics
            $_fiOI0 = true;
            $_J1QfQ = array("`$_I8jLt`", "`$_I8Jti`", "`$_IfJ66`");
            if($_fiOI0)
               $_J1QfQ[] = "`$_I8jjj`";

            for($_QliOt=0; $_QliOt<count($_J1QfQ); $_QliOt++){
              $_QLfol = "DELETE FROM $_J1QfQ[$_QliOt] WHERE `Member_id`=$_I8oIJ[$_Qli6J]";
              $_QL8i1 = mysql_query($_QLfol, $_QLttI);
              if (mysql_error($_QLttI) != "") $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
            }

         }

        }

        mysql_query("COMMIT", $_QLttI);

  }

  if($_ffj1J && $MailingListId != 0) {
    _JQJCP($MailingListId, $_I8oIJ);
  }

  $_fOolj = null;

 }

 function _J1LOQ($_I8oIJ, $_f8fQ8, $_I8I6o, $_I8jjj) {
  global $_QLttI;
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);
  reset($_I8oIJ);
  $_I1o8o = 0;
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
     $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
     $_QLfol = "SELECT u_EMail FROM `$_I8I6o` WHERE id=$_I8oIJ[$_Qli6J]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
       mysql_free_result($_QL8i1);
       $_QLfol = "INSERT IGNORE INTO `$_f8fQ8` SET u_EMail="._LRAFO($_QLO0f[0]);
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       if (mysql_affected_rows($_QLttI) > 0) {
         $_QLfol = "INSERT INTO `$_I8jjj` SET `Action`='BlackListed', `ActionDate`=NOW(), `Member_id`=$_I8oIJ[$_Qli6J]";
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
         $_I1o8o++;
       }
     }
  }
  return $_I1o8o = count($_I8oIJ);
 }

 function _J1LEC($_I8oIJ, $_fioii, $_I8I6o, $_I8jjj) {
  global $_QLttI;
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);
  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
     $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
     $_QLfol = "SELECT u_EMail FROM `$_I8I6o` WHERE id=$_I8oIJ[$_Qli6J]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
       mysql_free_result($_QL8i1);
       $_QLO0f[0] = substr($_QLO0f[0], strpos($_QLO0f[0], '@') + 1);
       $_QLfol = "INSERT IGNORE INTO `$_fioii` SET `Domainname`="._LRAFO($_QLO0f[0]);
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       if (mysql_affected_rows($_QLttI) > 0) {
         $_QLfol = "INSERT INTO `$_I8jjj` SET `Action`='BlackListed', `ActionDate`=NOW(), `Member_id`=$_I8oIJ[$_Qli6J]";
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
       }
     }
  }
 }

 function _J1J0O($_I8oIJ, $_IfJ66, $_jt0QC, $_fiCCQ=false) {
  global $_QLttI;
  if(!is_array($_jt0QC))
    $_jt0QC = explode(",", $_jt0QC);
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);
  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
    $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);

    mysql_query("BEGIN", $_QLttI);

    if(!$_fiCCQ) {
      $_QLfol = "DELETE FROM `$_IfJ66` WHERE Member_id=".$_I8oIJ[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

    reset($_jt0QC);
    foreach($_jt0QC as $_QliOt => $_QltJO) {

      if($_fiCCQ) {
        $_QLfol = "SELECT groups_id FROM `$_IfJ66` WHERE Member_id=$_I8oIJ[$_Qli6J] AND groups_id=".intval(trim($_jt0QC[$_QliOt]));
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1) {
          if(mysql_num_rows($_QL8i1) > 0) {
            mysql_free_result($_QL8i1);
            continue;
          }
          mysql_free_result($_QL8i1);
        }
      }

      $_QLfol = "INSERT INTO `$_IfJ66` SET `Member_id`=$_I8oIJ[$_Qli6J], `groups_id`=".intval(trim($_jt0QC[$_QliOt]));
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

    mysql_query("COMMIT", $_QLttI);

  }
 }

 function _J1JJL($_I8oIJ, $MailingListId, $_IfJ66, $_jt0QC) {
  global $_QLttI;
  if(!is_array($_jt0QC))
    $_jt0QC = explode(",", $_jt0QC);
  $_fJl0t = 0;
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);
  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
    $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
    reset($_jt0QC);
    mysql_query("BEGIN", $_QLttI);
    foreach($_jt0QC as $_QliOt => $_QltJO) {
      $_QLfol = "DELETE FROM `$_IfJ66` WHERE Member_id=".$_I8oIJ[$_Qli6J]. " AND groups_id=".intval(trim($_jt0QC[$_QliOt]));
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_fJl0t += mysql_affected_rows($_QLttI);
    }
    mysql_query("COMMIT", $_QLttI);
  }
  return $_fJl0t;
 }

 function _J1JFE($_I8oIJ, $MailingListId, $_IfJ66) {
  global $_QLttI;
  $_fJl0t = 0;
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);
  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
      $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
      mysql_query("BEGIN", $_QLttI);
      $_QLfol = "DELETE FROM `$_IfJ66` WHERE Member_id=".$_I8oIJ[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_fJl0t += mysql_affected_rows($_QLttI);
      mysql_query("COMMIT", $_QLttI);
  }
  return $_fJl0t;
 }


 function _J1608($_fii81, $MailingListId, $_IfJ66, $_jt0QC) {
  global $_QLttI;
  if(!is_array($_jt0QC))
    $_jt0QC = explode(",", $_jt0QC);
  $_fJl0t = 0;
  if(!is_array($_fii81)) $_fii81 = array($_fii81);
  reset($_fii81);
  foreach($_fii81 as $_Qli6J => $_QltJO) {
    $_fii81[$_Qli6J] = intval($_fii81[$_Qli6J]);
    reset($_jt0QC);
    foreach($_jt0QC as $_QliOt => $_QltJO) {
      $_QLfol = "SELECT COUNT(*) FROM `$_IfJ66` WHERE Member_id=".$_fii81[$_Qli6J]. " AND groups_id=".intval(trim($_jt0QC[$_QliOt]));
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1))
        $_fJl0t += $_QLO0f[0];
      if($_QL8i1)
        mysql_free_result($_QL8i1);
    }
  }
  return $_fJl0t;
 }

 function _J16F0($_I8oIJ, &$_IQ0Cj) {
  global $_I8I6o, $_I8jjj, $_QLttI;
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);
  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
      $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);

      mysql_query("BEGIN", $_QLttI);

      $_QLfol = "UPDATE `$_I8I6o` SET BounceStatus='', SoftbounceCount=0, HardbounceCount=0 WHERE id=".$_I8oIJ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      $_QLfol = "DELETE FROM `$_I8jjj` WHERE Action='Bounced' AND Member_id=".$_I8oIJ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      mysql_query("COMMIT", $_QLttI);

  }
 }

 function _J1RD0($_IjIfQ, $_I8oIJ, &$_IQ0Cj, $_fiOoQ = "") {
  global $_I8I6o, $_I8jjj, $_QLttI, $_I8jLt;
  $_IOCjL=0;
  if($_IjIfQ)
    $_IOCjL=1;
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);

  if(!empty($_fiOoQ)){
    $_fOolj = new _LFBOB();
  }

  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
      $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);

      mysql_query("BEGIN", $_QLttI);

      $_QLfol = "UPDATE `$_I8I6o` SET IsActive=$_IOCjL WHERE id=".$_I8oIJ[$_Qli6J]." AND IsActive<>$_IOCjL";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      if(mysql_affected_rows($_QLttI) > 0) {
         if(!empty($_fiOoQ)){
           $_fOolj->_LF0QR($_I8jLt, $_I8oIJ[$_Qli6J], $_fiOoQ);
         }
         if($_IOCjL)
           $_QLfol = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Activated', Member_id=".$_I8oIJ[$_Qli6J];
           else
           $_QLfol = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Deactivated', Member_id=".$_I8oIJ[$_Qli6J];
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      }

      mysql_query("COMMIT", $_QLttI);

  }
  return true;
 }

 function _J1RER($_I8oIJ, &$_IQ0Cj) {
  global $_I8I6o, $_I8jjj, $_QLttI, $INTERFACE_LANGUAGE, $resourcestrings;
  if(!is_array($_I8oIJ)) $_I8oIJ = array($_I8oIJ);
  reset($_I8oIJ);
  foreach($_I8oIJ as $_Qli6J => $_QltJO) {
      $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);

      mysql_query("BEGIN", $_QLttI);

      $_QLfol = "UPDATE `$_I8I6o` SET SubscriptionStatus='Subscribed', IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."', DateOfOptInConfirmation=NOW() WHERE id=".$_I8oIJ[$_Qli6J]." AND SubscriptionStatus<>'Subscribed'";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      if(mysql_affected_rows($_QLttI) > 0) {
        $_QLfol = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Subscribed', Member_id=".$_I8oIJ[$_Qli6J];
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
        if( mysql_error($_QLttI) == "" ) {
          $_QLfol = "DELETE FROM `$_I8jjj` WHERE Member_id=".$_I8oIJ[$_Qli6J]." AND Action='OptInConfirmationPending'";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        }
      }

      mysql_query("COMMIT", $_QLttI);
  }
 }

 function _J18DA($EMail) {
  global $_I8tfQ, $_QLttI;

  $_QLfol = "SELECT COUNT(*) FROM $_I8tfQ WHERE u_EMail="._LRAFO(trim($EMail));
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    return $_QLO0f[0] > 0;
  }

  return false;
 }

 function _J18FQ($EMail, $_fiioj, $_jjj8f="") {
  global $_QL88I, $_QLttI;

  if(!$_jjj8f) {
    $_QLfol = "SELECT LocalBlocklistTableName FROM $_QL88I WHERE id=".intval($_fiioj);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      $_jjj8f = $_QLO0f[0];
    } else
      return false;
  }

  $_QLfol = "SELECT COUNT(*) FROM `$_jjj8f` WHERE u_EMail="._LRAFO(trim($EMail));
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    return $_QLO0f[0] > 0;
  }

  return false;
 }

 function _J1PQO($EMail) {
  global $_I8OoJ, $_QLttI;

  $EMail = trim($EMail);
  $_QlOjt = strpos($EMail, '@');
  if($_QlOjt !== false)
     $EMail = substr($EMail, $_QlOjt + 1);
  $_QLfol = "SELECT COUNT(*) FROM `$_I8OoJ` WHERE `Domainname`="._LRAFO($EMail);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    return $_QLO0f[0] > 0;
  }

  return false;
 }

 function _J1P6D($EMail, $_fiioj, $_Jj6f0="") {
  global $_QL88I, $_QLttI;

  if(!$_Jj6f0) {
    $_QLfol = "SELECT LocalDomainBlocklistTableName FROM $_QL88I WHERE id=".intval($_fiioj);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj6f0 = $_QLO0f[0];
    } else
      return false;
  }

  $EMail = trim($EMail);
  $_QlOjt = strpos($EMail, '@');
  if($_QlOjt !== false)
     $EMail = substr($EMail, $_QlOjt + 1);

  $_QLfol = "SELECT COUNT(*) FROM `$_Jj6f0` WHERE Domainname="._LRAFO($EMail);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);
    return $_QLO0f[0] > 0;
  }

  return false;
 }

?>
