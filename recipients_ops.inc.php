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
  // used from browserecpts.php, browsmailinglists.php, nl.php, defaultnewsletter.php, cron_subunsubcheck.inc.php, cron_bounces.inc.php

  include_once("responders_cleanup.inc.php");
  include_once("maillogger.php");

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  $_QltCO = array();
  $_QlQC8 = "";
  $_QlIf6 = "";
  $_QLI68 = "";
  $MailingListId = 0;
  $_QljIQ = "";
  $_Qljli = "";
  $_IoO1t = array();
  $_QtIiC = array();
  // cron_subunsubcheck.inc.php, cron_bounces.inc.php will set the parameters itself

  if(!defined("API"))
    _L10PF();

  function _L10PF() {
    global $_Q60QL, $_Q61I1;
    global $_QltCO, $MailingListId;
    global $_QlQC8, $_QlIf6, $_QLI68, $_QljIQ, $_Qljli;
    global $_IoO1t, $_QtIiC;

    $_QltCO = array();
    if ( isset($_POST["OneRecipientId"]) && $_POST["OneRecipientId"] != "" )
        $_QltCO[] = $_POST["OneRecipientId"];
        else
        if ( isset($_POST["RecipientsIDs"]) )
          $_QltCO = array_merge($_QltCO, $_POST["RecipientsIDs"]);


    // get the table
    if ( isset($_POST["OneMailingListId"]) && intval($_POST["OneMailingListId"]) != 0) {
      $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);

      if(!defined("CRONS_PHP") && !_OCJCC($_POST["OneMailingListId"])){
        $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $_QJCJi;
        exit;
      }

      $_QJlJ0 = "SELECT id, MaillistTableName, StatisticsTableName, MailListToGroupsTableName, MailLogTableName, EditTableName FROM $_Q60QL WHERE id=".$_POST["OneMailingListId"];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      $_QlQC8 = $_Q6Q1C["MaillistTableName"];
      $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
      $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
      $MailingListId  = $_Q6Q1C["id"];
      $_QljIQ = $_Q6Q1C["MailLogTableName"];
      $_Qljli = $_Q6Q1C["EditTableName"];

      mysql_free_result($_Q60l1);
    }
  }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "RemoveRecipients" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "DeleteRecipient" )
     ) {
          if(isset($_QtIiC))
            unset($_QtIiC);
          $_QtIiC = array();
          _L10CL($_QltCO, $_QtIiC);
    }


  if  ( isset($_POST["RecipientsActions"]) && ( $_POST["RecipientsActions"] == "AssignToGroups" || $_POST["RecipientsActions"] == "AssignToGroupsAdditionally" )   ) {
     if(isset($_POST["Groups"]))
        _L1QPQ($_QltCO, $_QLI68, $_POST["Groups"], $_POST["RecipientsActions"] == "AssignToGroupsAdditionally");
        else
        if(isset($_POST["RecipientGroups"]))
         _L1QPQ($_QltCO, $_QLI68, $_POST["RecipientGroups"], $_POST["RecipientsActions"] == "AssignToGroupsAdditionally");
        else
         _L1QPQ($_QltCO, $_QLI68, array(), $_POST["RecipientsActions"] == "AssignToGroupsAdditionally");
  }


  if  ( isset($_POST["RecipientsActions"]) && ($_POST["RecipientsActions"] == "RemoveFromGroups")   ) {
     if(isset($_POST["Groups"]))
        _L1OR1($_QltCO, $MailingListId, $_QLI68, $_POST["Groups"]);
        else
        if(isset($_POST["RecipientGroups"]))
          _L1OR1($_QltCO, $MailingListId, $_QLI68, $_POST["RecipientGroups"]);
          else
          _L1QPQ($_QltCO, $_QLI68, array());
  }

  if  ( isset($_POST["RecipientsActions"]) && ( ($_POST["RecipientsActions"] == "MoveRecipients") || ($_POST["RecipientsActions"] == "CopyRecipients") ) ) {

     if(!defined("CRONS_PHP") && !_OCJCC($_POST["DestMailingList"])){
       $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
       $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
       print $_QJCJi;
       exit;
     }

     // get the table
     $_QJlJ0 = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName, EditTableName FROM $_Q60QL WHERE id=".intval($_POST["DestMailingList"]);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_60i0C = $_Q6Q1C[0];
     $_60iLC = $_Q6Q1C[1];
     $_60Li0 = $_Q6Q1C[2];
     $_60l1L = $_Q6Q1C[3];
     mysql_free_result($_Q60l1);
     $_IoO1t = array();
      if($_60i0C == $_QlQC8) {
         $_IoO1t[] = $resourcestrings[$INTERFACE_LANGUAGE]["000043"];
      }
      else
       _L11LC($_QltCO, $_IoO1t, $_60i0C, $_60iLC, $_60Li0, $_60l1L, ($_POST["RecipientsActions"] == "MoveRecipients"));
  }

  if  ( isset($_POST["RecipientsActions"]) && ( ($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") || ($_POST["RecipientsActions"] == "AddRecipientToGlobalBlacklist") ) ) {
    if($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") {
      $_QJlJ0 = "SELECT LocalBlocklistTableName FROM $_Q60QL WHERE id=".$_POST["OneMailingListId"];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      $_ItCCo = $_Q6Q1C[0];
      _L11PQ($_QltCO, $_ItCCo, $_QlQC8, $_QlIf6);
    } else {
      _L11PQ($_QltCO, $_Ql8C0, $_QlQC8, $_QlIf6);
    }
  }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "ResetBounceState" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "ResetBounceState" )
     ) {
          if(isset($_QtIiC))
            unset($_QtIiC);
          $_QtIiC = array();
          _L1J0J($_QltCO, $_QtIiC);
    }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "ActivateRecipients" ) ||
        ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "ActivateRecipient" )
        ||
        ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "ResetInactiveState" ) ||
        ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "ResetInactiveState" )
     ) {
          if(isset($_QtIiC))
            unset($_QtIiC);
          $_QtIiC = array();
          _L1J66(true, $_QltCO, $_QtIiC);
    }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "DeactivateRecipients" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "DeactivateRecipient" )

        ||
        ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "SetInactiveState" ) ||
        ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "SetInactiveState" )

     ) {
          if(isset($_QtIiC))
            unset($_QtIiC);
          $_QtIiC = array();
          _L1J66(false, $_QltCO, $_QtIiC);
    }

  if  ( ( isset($_POST["RecipientsActions"]) && $_POST["RecipientsActions"] == "SetSubscribedState" ) ||
     ( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] == "SetSubscribedState" )
     ) {
          if(isset($_QtIiC))
            unset($_QtIiC);
          $_QtIiC = array();
          _L16JC($_QltCO, $_QtIiC);
    }

  // we don't check for errors here
 function _L10CL($_QltCO, &$_QtIiC, $_66086 = true) {
    global $_QlQC8, $_QlIf6, $_QLI68, $_QljIQ, $_Qljli, $MailingListId, $_Q61I1;

    reset($_QltCO);
    foreach($_QltCO as $_Q6llo => $_Q6ClO) {
      $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);

      mysql_query("BEGIN", $_Q61I1);

      // Delete recipient
      $_QJlJ0 = "DELETE FROM `$_QlQC8` WHERE id=".$_QltCO[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      // Delete maillog, members edit, groups assignment, statistics
      $_jj6l0 = array("`$_QljIQ`", "`$_Qljli`", "`$_QLI68`");
      if($_66086)
         $_jj6l0[] = "`$_QlIf6`";

      for($_Qf0Ct=0; $_Qf0Ct<count($_jj6l0); $_Qf0Ct++){
        $_QJlJ0 = "DELETE FROM $_jj6l0[$_Qf0Ct] WHERE `Member_id`=$_QltCO[$_Q6llo]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      }

      mysql_query("COMMIT", $_Q61I1);
    }

    if($MailingListId != 0) {
      _LQQJO($MailingListId, $_QltCO);
    }

  }

 function _L11LC($_QltCO, &$_IoO1t, $_60i0C, $_60iLC, $_60Li0, $_60l1L, $_60lCJ, $_6616L = "") {
  global $_QlQC8, $_QlIf6, $_QLI68, $MailingListId, $_QljIQ, $_Qljli, $_Q61I1;

  $_QLLjo = array();
  _OAJL1($_QlQC8, $_QLLjo, array("id"));
  $_I16jJ = join(", ", $_QLLjo);

  unset($_QLLjo);
  $_QLLjo = array();
  _OAJL1($_QlIf6, $_QLLjo, array("Member_id"));
  $_66QQ0 = join(", ", $_QLLjo);

  $_QLLjo = array();
  _OAJL1($_QljIQ, $_QLLjo, array("Member_id"));
  $_66Qof = join(", ", $_QLLjo);

  $_QLLjo = array();
  _OAJL1($_Qljli, $_QLLjo, array("Member_id"));
  $_66QiL = join(", ", $_QLLjo);


  if(!empty($_6616L)){
    $_6jIQO = new _OFBEA();
  }

  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {

     mysql_query("BEGIN", $_Q61I1);

     $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
     // der Empfaenger selbst
     if($_60lCJ)
        $_QJlJ0 = "INSERT INTO `$_60i0C` (".$_I16jJ.") ";
        else
        $_QJlJ0 = "INSERT IGNORE INTO `$_60i0C` (".$_I16jJ.") ";
     $_QJlJ0 .= "SELECT $_I16jJ FROM `$_QlQC8` WHERE id=$_QltCO[$_Q6llo]";
     mysql_query($_QJlJ0, $_Q61I1);
     if (mysql_error($_Q61I1) != "") {
          $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
          unset($_QltCO[$_Q6llo]);
        }
        else {
         $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
         if (mysql_error($_Q61I1) != "") { # not inserted
           $_IoO1t[] = "Duplicate entry for id $_QltCO[$_Q6llo]";
           unset($_QltCO[$_Q6llo]);
           if($_Q60l1)
             mysql_free_result($_Q60l1);
           mysql_query("ROLLBACK", $_Q61I1);
           continue;
         }
         $_Q6Q1C=mysql_fetch_array($_Q60l1);
         $ID = $_Q6Q1C[0];
         if (mysql_error($_Q61I1) != "" || $ID == 0) { # not inserted
           $_IoO1t[] = "Duplicate entry for id $_QltCO[$_Q6llo]";
           unset($_QltCO[$_Q6llo]);
           if($_Q60l1)
             mysql_free_result($_Q60l1);
           mysql_query("ROLLBACK", $_Q61I1);
           continue;
         }
         mysql_free_result($_Q60l1);

         // Statistik
         $_QJlJ0 = "SELECT $_66QQ0 FROM `$_QlIf6` WHERE Member_id=$_QltCO[$_Q6llo]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
           while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
             $_QJlJ0 = "INSERT INTO `$_60iLC` (".$_66QQ0.", Member_id) VALUES (";
             if (isset($_I1L81)) unset($_I1L81);
             $_I1L81 = array();
             foreach ($_Q6Q1C as $key => $_Q6ClO) {
                $_I1L81[] = _OPQLR($_Q6ClO);
             }
             $_QJlJ0 .= join(", ", $_I1L81);
             $_QJlJ0 .= ", $ID)";
             mysql_query($_QJlJ0, $_Q61I1);
             if (mysql_error($_Q61I1) != "")
                $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
           }
         }

         // maillog
         $_QJlJ0 = "SELECT $_66Qof FROM `$_QljIQ` WHERE Member_id=$_QltCO[$_Q6llo]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
           while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
             $_QJlJ0 = "INSERT INTO `$_60Li0` (".$_66Qof.", Member_id) VALUES (";
             if (isset($_I1L81)) unset($_I1L81);
             $_I1L81 = array();
             foreach ($_Q6Q1C as $key => $_Q6ClO) {
                $_I1L81[] = _OPQLR($_Q6ClO);
             }
             $_QJlJ0 .= join(", ", $_I1L81);
             $_QJlJ0 .= ", $ID)";
             mysql_query($_QJlJ0, $_Q61I1);
             if (mysql_error($_Q61I1) != "")
                $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
           }
         }

         // edit log
         $_QJlJ0 = "SELECT $_66QiL FROM `$_Qljli` WHERE Member_id=$_QltCO[$_Q6llo]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
           while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
             $_QJlJ0 = "INSERT INTO `$_60l1L` (".$_66QiL.", Member_id) VALUES (";
             if (isset($_I1L81)) unset($_I1L81);
             $_I1L81 = array();
             foreach ($_Q6Q1C as $key => $_Q6ClO) {
                $_I1L81[] = _OPQLR($_Q6ClO);
             }
             $_QJlJ0 .= join(", ", $_I1L81);
             $_QJlJ0 .= ", $ID)";
             mysql_query($_QJlJ0, $_Q61I1);
             if (mysql_error($_Q61I1) != "")
                $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
           }
         }

         if(!empty($_6616L)){
           $_6jIQO->_OF0FL($_60Li0, $ID, $_6616L);
         }

         // loeschen
         if($_60lCJ) {
            // Empfaenger loeschen
            $_QJlJ0 = "DELETE FROM `$_QlQC8` WHERE `id`=$_QltCO[$_Q6llo]";
            mysql_query($_QJlJ0, $_Q61I1);
            if (mysql_error($_Q61I1) != "")
               $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

            // Delete maillog, members edit, groups assignment, statistics
            $_66086 = true;
            $_jj6l0 = array("`$_QljIQ`", "`$_Qljli`", "`$_QLI68`");
            if($_66086)
               $_jj6l0[] = "`$_QlIf6`";

            for($_Qf0Ct=0; $_Qf0Ct<count($_jj6l0); $_Qf0Ct++){
              $_QJlJ0 = "DELETE FROM $_jj6l0[$_Qf0Ct] WHERE `Member_id`=$_QltCO[$_Q6llo]";
              $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
              if (mysql_error($_Q61I1) != "") $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
            }

         }

        }

        mysql_query("COMMIT", $_Q61I1);

  }

  if($_60lCJ && $MailingListId != 0) {
    _LQQJO($MailingListId, $_QltCO);
  }

  $_6jIQO = null;

 }

 function _L11PQ($_QltCO, $_61lij, $_QlQC8, $_QlIf6) {
  global $_Q61I1;
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);
  reset($_QltCO);
  $_Q8COf = 0;
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
     $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
     $_QJlJ0 = "SELECT u_EMail FROM `$_QlQC8` WHERE id=$_QltCO[$_Q6llo]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
       mysql_free_result($_Q60l1);
       $_QJlJ0 = "INSERT IGNORE INTO `$_61lij` SET u_EMail="._OPQLR($_Q6Q1C[0]);
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       if (mysql_affected_rows($_Q61I1) > 0) {
         $_QJlJ0 = "INSERT INTO `$_QlIf6` SET `Action`='BlackListed', `ActionDate`=NOW(), `Member_id`=$_QltCO[$_Q6llo]";
         mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);
         $_Q8COf++;
       }
     }
  }
  return $_Q8COf = count($_QltCO);
 }

 function _L1QOB($_QltCO, $_66IjL, $_QlQC8, $_QlIf6) {
  global $_Q61I1;
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);
  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
     $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
     $_QJlJ0 = "SELECT u_EMail FROM `$_QlQC8` WHERE id=$_QltCO[$_Q6llo]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
       mysql_free_result($_Q60l1);
       $_Q6Q1C[0] = substr($_Q6Q1C[0], strpos($_Q6Q1C[0], '@') + 1);
       $_QJlJ0 = "INSERT IGNORE INTO `$_66IjL` SET `Domainname`="._OPQLR($_Q6Q1C[0]);
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       if (mysql_affected_rows($_Q61I1) > 0) {
         $_QJlJ0 = "INSERT INTO `$_QlIf6` SET `Action`='BlackListed', `ActionDate`=NOW(), `Member_id`=$_QltCO[$_Q6llo]";
         mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);
       }
     }
  }
 }

 function _L1QPQ($_QltCO, $_QLI68, $_IitLf, $_66Iio=false) {
  global $_Q61I1;
  if(!is_array($_IitLf))
    $_IitLf = explode(",", $_IitLf);
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);
  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
    $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);

    mysql_query("BEGIN", $_Q61I1);

    if(!$_66Iio) {
      $_QJlJ0 = "DELETE FROM `$_QLI68` WHERE Member_id=".$_QltCO[$_Q6llo];
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

    reset($_IitLf);
    foreach($_IitLf as $_Qf0Ct => $_Q6ClO) {

      if($_66Iio) {
        $_QJlJ0 = "SELECT groups_id FROM `$_QLI68` WHERE Member_id=$_QltCO[$_Q6llo] AND groups_id=".intval(trim($_IitLf[$_Qf0Ct]));
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q60l1) {
          if(mysql_num_rows($_Q60l1) > 0) {
            mysql_free_result($_Q60l1);
            continue;
          }
          mysql_free_result($_Q60l1);
        }
      }

      $_QJlJ0 = "INSERT INTO `$_QLI68` SET `Member_id`=$_QltCO[$_Q6llo], `groups_id`=".intval(trim($_IitLf[$_Qf0Ct]));
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

    mysql_query("COMMIT", $_Q61I1);

  }
 }

 function _L1OR1($_QltCO, $MailingListId, $_QLI68, $_IitLf) {
  global $_Q61I1, $_QCLCI;
  if(!is_array($_IitLf))
    $_IitLf = explode(",", $_IitLf);
  $_Jl811 = 0;
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);
  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
    $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
    reset($_IitLf);
    mysql_query("BEGIN", $_Q61I1);
    foreach($_IitLf as $_Qf0Ct => $_Q6ClO) {
      $_QJlJ0 = "DELETE FROM `$_QLI68` WHERE Member_id=".$_QltCO[$_Q6llo]. " AND groups_id=".intval(trim($_IitLf[$_Qf0Ct]));
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Jl811 += mysql_affected_rows($_Q61I1);
    }
    mysql_query("COMMIT", $_Q61I1);
  }
  return $_Jl811;
 }

 function _L1LLB($_QltCO, $MailingListId, $_QLI68) {
  global $_Q61I1;
  $_Jl811 = 0;
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);
  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
      $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
      mysql_query("BEGIN", $_Q61I1);
      $_QJlJ0 = "DELETE FROM `$_QLI68` WHERE Member_id=".$_QltCO[$_Q6llo];
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Jl811 += mysql_affected_rows($_Q61I1);
      mysql_query("COMMIT", $_Q61I1);
  }
  return $_Jl811;
 }

 function _L1J0J($_QltCO, &$_QtIiC) {
  global $_QlQC8, $_QlIf6, $_Q61I1;
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);
  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
      $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);

      mysql_query("BEGIN", $_Q61I1);

      $_QJlJ0 = "UPDATE `$_QlQC8` SET BounceStatus='', SoftbounceCount=0, HardbounceCount=0 WHERE id=".$_QltCO[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE Action='Bounced' AND Member_id=".$_QltCO[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      mysql_query("COMMIT", $_Q61I1);

  }
 }

 function _L1J66($_Qo0oi, $_QltCO, &$_QtIiC, $_6616L = "") {
  global $_QlQC8, $_QlIf6, $_Q61I1, $_QljIQ;
  $_I1L81=0;
  if($_Qo0oi)
    $_I1L81=1;
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);

  if(!empty($_6616L)){
    $_6jIQO = new _OFBEA();
  }

  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
      $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);

      mysql_query("BEGIN", $_Q61I1);

      $_QJlJ0 = "UPDATE `$_QlQC8` SET IsActive=$_I1L81 WHERE id=".$_QltCO[$_Q6llo]." AND IsActive<>$_I1L81";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      if(mysql_affected_rows($_Q61I1) > 0) {
         if(!empty($_6616L)){
           $_6jIQO->_OF0FL($_QljIQ, $_QltCO[$_Q6llo], $_6616L);
         }
         if($_I1L81)
           $_QJlJ0 = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Activated', Member_id=".$_QltCO[$_Q6llo];
           else
           $_QJlJ0 = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Deactivated', Member_id=".$_QltCO[$_Q6llo];
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      }

      mysql_query("COMMIT", $_Q61I1);

  }
  return true;
 }

 function _L16JC($_QltCO, &$_QtIiC) {
  global $_QlQC8, $_QlIf6, $_Q61I1, $INTERFACE_LANGUAGE, $resourcestrings;
  if(!is_array($_QltCO)) $_QltCO = array($_QltCO);
  reset($_QltCO);
  foreach($_QltCO as $_Q6llo => $_Q6ClO) {
      $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);

      mysql_query("BEGIN", $_Q61I1);

      $_QJlJ0 = "UPDATE `$_QlQC8` SET SubscriptionStatus='Subscribed', IPOnSubscription='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."', DateOfOptInConfirmation=NOW() WHERE id=".$_QltCO[$_Q6llo]." AND SubscriptionStatus<>'Subscribed'";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      if(mysql_affected_rows($_Q61I1) > 0) {
        $_QJlJ0 = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Subscribed', Member_id=".$_QltCO[$_Q6llo];
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
        if( mysql_error($_Q61I1) == "" ) {
          $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE Member_id=".$_QltCO[$_Q6llo]." AND Action='OptInConfirmationPending'";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        }
      }

      mysql_query("COMMIT", $_Q61I1);
  }
 }

 function _L0FRD($EMail) {
  global $_Ql8C0, $_Q61I1;

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Ql8C0 WHERE u_EMail="._OPQLR(trim($EMail));
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    return $_Q6Q1C[0] > 0;
  }

  return false;
 }

 function _L101P($EMail, $_6Jli1, $_ItCCo="") {
  global $_Q60QL, $_Q61I1;

  if(!$_ItCCo) {
    $_QJlJ0 = "SELECT LocalBlocklistTableName FROM $_Q60QL WHERE id=".intval($_6Jli1);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      $_ItCCo = $_Q6Q1C[0];
    } else
      return false;
  }

  $_QJlJ0 = "SELECT COUNT(*) FROM `$_ItCCo` WHERE u_EMail="._OPQLR(trim($EMail));
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    return $_Q6Q1C[0] > 0;
  }

  return false;
 }

 function _L1ROL($EMail) {
  global $_Qlt66, $_Q61I1;

  $EMail = trim($EMail);
  $_Q6i6i = strpos($EMail, '@');
  if($_Q6i6i !== false)
     $EMail = substr($EMail, $_Q6i6i + 1);
  $_QJlJ0 = "SELECT COUNT(*) FROM `$_Qlt66` WHERE `Domainname`="._OPQLR($EMail);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    return $_Q6Q1C[0] > 0;
  }

  return false;
 }

 function _L1RDP($EMail, $_6Jli1, $_jf1J1="") {
  global $_Q60QL, $_Q61I1;

  if(!$_jf1J1) {
    $_QJlJ0 = "SELECT LocalDomainBlocklistTableName FROM $_Q60QL WHERE id=".intval($_6Jli1);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      $_jf1J1 = $_Q6Q1C[0];
    } else
      return false;
  }

  $EMail = trim($EMail);
  $_Q6i6i = strpos($EMail, '@');
  if($_Q6i6i !== false)
     $EMail = substr($EMail, $_Q6i6i + 1);

  $_QJlJ0 = "SELECT COUNT(*) FROM `$_jf1J1` WHERE Domainname="._OPQLR($EMail);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    return $_Q6Q1C[0] > 0;
  }

  return false;
 }

?>
