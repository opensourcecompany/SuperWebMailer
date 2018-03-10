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

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  if(defined("SWM"))
    include_once("responders_cleanup.inc.php");

  if(!defined("API")) {
    if (count($_POST) == 0) {
      include_once("browsemailinglists.php");
      exit;
    }

    if(isset($_IlJIC))
       unset($_IlJIC);

    $_IlJIC = array();
    if ( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" )
        $_IlJIC[] = intval($_POST["OneMailingListId"]);
        else
        if ( isset($_POST["MailingListIDs"]) )
          $_IlJIC = array_merge($_IlJIC, $_POST["MailingListIDs"]);


    foreach($_IlJIC as $key) {
      if(!_OCJCC($key)){
        $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $_QJCJi;
        exit;
      }
    }

    if  ( ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "DeleteMailLists" ) ||
       ( isset($_POST["OneMailingListAction"]) && $_POST["OneMailingListAction"] == "DeleteList" )
       ) {
          $_QtIiC = array();
          _OFPOA($_IlJIC, $_QtIiC);
         }


    if  ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "RemoveRecipients" ) {
          $_QtIiC = array();
          _OFPLA($_IlJIC, $_QtIiC);
    }

    if  ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "DeleteGroups" ) {
          $_QtIiC = array();
          _OFPD8($_IlJIC, $_QtIiC);
    }

    if  ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "DuplicateMailLists" ) {
          $_QtIiC = array();
          _OFAOQ($_IlJIC, $_QtIiC);
    }

    if  ( isset($_POST["MailingListActions"]) && ( ($_POST["MailingListActions"] == "MoveRecipients") || ($_POST["MailingListActions"] == "CopyRecipients") ) ) {

       if(!_OCJCC($_POST["DestMailingList"])){
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
         $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
         print $_QJCJi;
         exit;
       }

       // get the table
       $_QJlJ0 = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName, `EditTableName` FROM $_Q60QL WHERE id=".intval($_POST["DestMailingList"]);
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       $_60i0C = $_Q6Q1C[0];
       $_60iLC = $_Q6Q1C[1];
       $_60Li0 = $_Q6Q1C[2];
       $_60l1L = $_Q6Q1C[3];
       mysql_free_result($_Q60l1);
       $_IoO1t = array();
       _OFPOP($_IlJIC, $_IoO1t, $_60i0C, $_60iLC, $_60Li0, $_60l1L, ($_POST["MailingListActions"] == "MoveRecipients"), intval($_POST["DestMailingList"]));
    }
  } // if(!defined("API")) {

  function _OFPOP($_IlJIC, &$_IoO1t, $_60i0C, $_60iLC, $_60Li0, $_60l1L, $_60lCJ, $_6100t) {
    global $_Q60QL, $_Q6fio;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    $_6100t = intval($_6100t);
    $_QLLjo = array();
    _OAJL1($_60i0C, $_QLLjo, array("id"));
    $_I16jJ = join(", ", $_QLLjo);

    reset($_IlJIC);
    foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
      if($_6100t == intval($_IlJIC[$_Q6llo])) {
         $_IoO1t[] = $resourcestrings[$INTERFACE_LANGUAGE]["000043"];
         continue;
      }
      $_IlJIC[$_Q6llo] = intval($_IlJIC[$_Q6llo]);
      $_QJlJ0 = "SELECT MaillistTableName, StatisticsTableName, MailListToGroupsTableName, MailLogTableName, `EditTableName` FROM $_Q60QL WHERE id=".$_IlJIC[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1) continue;
      if (mysql_error($_Q61I1) != "") $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_61106 = $_Q6Q1C["MaillistTableName"];
      $_611Lj = $_Q6Q1C["StatisticsTableName"];
      $_61Q0J = $_Q6Q1C["MailListToGroupsTableName"];
      $_61QJC = $_Q6Q1C["MailLogTableName"];
      $_61Q66 = $_Q6Q1C["EditTableName"];

      mysql_query("BEGIN", $_Q61I1);

      if($_60lCJ)
        $_QJlJ0 = "INSERT INTO `$_60i0C` (".$_I16jJ.") ";
        else
        $_QJlJ0 = "INSERT IGNORE INTO `$_60i0C` (".$_I16jJ.") ";
      $_QJlJ0 .= "SELECT $_I16jJ FROM $_61106";
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") {
          $_IoO1t[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
        }
        else {
                // loeschen
                if($_60lCJ) {
                 $_QJlJ0 = "DELETE FROM `$_61106`";
                 mysql_query($_QJlJ0, $_Q61I1);
                 $_QJlJ0 = "DELETE FROM `$_611Lj`";
                 mysql_query($_QJlJ0, $_Q61I1);
                 $_QJlJ0 = "DELETE FROM `$_61Q0J`";
                 mysql_query($_QJlJ0, $_Q61I1);
                 $_QJlJ0 = "DELETE FROM `$_61QJC`";
                 mysql_query($_QJlJ0, $_Q61I1);
                 $_QJlJ0 = "DELETE FROM `$_61Q66`";
                 mysql_query($_QJlJ0, $_Q61I1);
                }
            }

      mysql_query("COMMIT", $_Q61I1);

    }

    if($_60lCJ && defined("SWM")) {
      _LQOEJ($_IlJIC, $_IoO1t);
    }
  }

  // we don't check for errors here
  function _OFPOA($_IlJIC, &$_QtIiC) {
    global $_Q60QL, $_Q6fio, $_IQL81, $_QCLCI, $_IIl8O, $_IoOLJ, $_IC0oQ, $_Q6jOo, $_I0f8O, $_IooOQ, $_IoCtL, $_QoOft;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;


    $_Ioi61 = array();
    if(_OA1LL($_IQL81)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IQL81
                                 );
    }

    if(_OA1LL($_QCLCI)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QCLCI
                                 );
    }

    if(_OA1LL($_IIl8O)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IIl8O
                                 );
    }

    if(_OA1LL($_IoOLJ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoOLJ
                                 );
    }

    if(_OA1LL($_IC0oQ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IC0oQ
                                 );
    }
    if(_OA1LL($_Q6jOo)) {
      $_Ioi61[] = array(
                                   "TableName" => $_Q6jOo
                                 );
    }
    if(_OA1LL($_I0f8O)) {
      $_Ioi61[] = array(
                                   "TableName" => $_I0f8O
                                 );
    }

    if(_OA1LL($_IooOQ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IooOQ
                                 );
    }

    if(_OA1LL($_IoCtL)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoCtL
                                 );
    }

    if(_OA1LL($_QoOft)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QoOft
                                 );
    }

    reset($_IlJIC);
    foreach($_IlJIC as $_Q6llo => $_Q66jQ) {
      $_IlJIC[$_Q6llo] = intval($_IlJIC[$_Q6llo]);

      // referenzen vorhanden?
      $_IoL1l = 0;
      for($_Qf0Ct=0; $_Qf0Ct<count($_Ioi61); $_Qf0Ct++) {
        $_QJlJ0 = "SELECT COUNT(*) FROM `".$_Ioi61[$_Qf0Ct]["TableName"]."` WHERE `maillists_id`=".$_IlJIC[$_Q6llo];
        $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        $_IO08Q = mysql_fetch_row($_ItlJl);
        $_IoL1l += $_IO08Q[0];
        mysql_free_result($_ItlJl);
        if($_IoL1l > 0) break;

        if($_Ioi61[$_Qf0Ct]["TableName"] == $_QCLCI) {
           $_QJlJ0 = "SELECT COUNT(*) FROM `".$_Ioi61[$_Qf0Ct]["TableName"]."` WHERE (`OnFollowUpDoneAction`=2 AND `OnFollowUpDoneCopyToMailList`=$_IlJIC[$_Q6llo]) OR (`OnFollowUpDoneAction`=3 AND `OnFollowUpDoneMoveToMailList`=$_IlJIC[$_Q6llo]) ";
           $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);
           $_IO08Q = mysql_fetch_row($_ItlJl);
           $_IoL1l += $_IO08Q[0];
        }

        if($_IoL1l > 0) break;

      }
      $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=".$_IlJIC[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      if(!$_Q60l1 || !($_Q6Q1C = mysql_fetch_assoc($_Q60l1))) continue;
      mysql_free_result($_Q60l1);

      if($_IoL1l > 0) {
        $_QtIiC[] = $_Q6Q1C["Name"]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000033"];
        continue;
      }

      // drop tables
      foreach($_Q6Q1C as $key => $_Q6ClO) {
        if(strpos($key, "TableName") === false) continue;
        $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6ClO`";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      }

      // Delete mailistusers reference
      $_QJlJ0 = "DELETE FROM $_Q6fio WHERE maillists_id=".$_IlJIC[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      // and now mailists table
      $_QJlJ0 = "DELETE FROM $_Q60QL WHERE id=".$_IlJIC[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
    }
  }

  function _OFPLA($_IlJIC, &$_QtIiC) {
    global $_Q60QL, $_Q6fio, $_Q61I1;

    reset($_IlJIC);
    foreach($_IlJIC as $_Q6llo => $_Q66jQ) {
      $_IlJIC[$_Q6llo] = intval($_IlJIC[$_Q6llo]);
      $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=".$_IlJIC[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1) continue;
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      $_Q6Q1C = mysql_fetch_array($_Q60l1);
      mysql_free_result($_Q60l1);

      $_61I6J = array();
      $_61I6J[] = "MaillistTableName";
      $_61I6J[] = "MailListToGroupsTableName";
      $_61I6J[] = "StatisticsTableName";
      $_61I6J[] = "MailLogTableName";
      $_61I6J[] = "EditTableName";

      mysql_query("BEGIN", $_Q61I1);

      // remove all entries
      for($_Q6llo=0; $_Q6llo<count($_61I6J); $_Q6llo++) {
        $_QJlJ0 = "DELETE FROM `".$_Q6Q1C[$_61I6J[$_Q6llo]]."`";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      }

      mysql_query("COMMIT", $_Q61I1);

    }
    if(defined("SWM"))
      _LQOEJ($_IlJIC, $_QtIiC);
  }

  // we don't check for errors here
  function _OFPD8($_IlJIC, &$_QtIiC) {
    global $_Q60QL, $_Q6jOo, $_IoCtL, $_QoOft, $_QCLCI, $_Q61I1;

    reset($_IlJIC);
    foreach($_IlJIC as $_Q6llo => $_Q66jQ) {
      $_IlJIC[$_Q6llo] = intval($_IlJIC[$_Q6llo]);
      $_QJlJ0 = "SELECT `GroupsTableName`, `MailListToGroupsTableName` FROM `$_Q60QL` WHERE id=".$_IlJIC[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1) continue;
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      $_Q6Q1C = mysql_fetch_array($_Q60l1);
      mysql_free_result($_Q60l1);

      mysql_query("BEGIN", $_Q61I1);

      // GroupsTableName
      $_QJlJ0 = "DELETE FROM `$_Q6Q1C[GroupsTableName]`";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      // MailListToGroupsTableName
      $_QJlJ0 = "DELETE FROM `$_Q6Q1C[MailListToGroupsTableName]`";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;


        // campaigns, sms campaigns
        $_61jjf = array($_Q6jOo, $_IoCtL, $_QoOft, $_QCLCI);
        reset($_61jjf);
        foreach($_61jjf as $key) {
          if(defined("SML") && $key != $_QoOft) continue;

          $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$key` WHERE maillists_id=".$_IlJIC[$_Q6llo];
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
          While($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
            $_QJlJ0 = "DELETE FROM `$_Q6Q1C[GroupsTableName]`";
            mysql_query($_QJlJ0, $_Q61I1);
            $_QJlJ0 = "DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`";
            mysql_query($_QJlJ0, $_Q61I1);
          }
          mysql_free_result($_Q60l1);
        }

      mysql_query("COMMIT", $_Q61I1);

    }
  }


  function _OFAOQ($_IlJIC, &$_QtIiC){
    global $_Q60QL, $_Q6fio, $UserId, $OwnerUserId, $_Q61I1;

    if($OwnerUserId == 0) // ist es ein Admin?
       $_JLfCJ = $UserId;
       else
       $_JLfCJ = $OwnerUserId;

    reset($_IlJIC);
    foreach($_IlJIC as $_Q6llo => $_Q66jQ) {
      $_IlJIC[$_Q6llo] = intval($_IlJIC[$_Q6llo]);
      $_QJlJ0 = "SELECT * FROM `$_Q60QL` WHERE id=".$_IlJIC[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1) continue;
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      $_Q6llo = 1;
      $_61jlo = $_Q6Q1C["Name"]." (%d)";
      while (true) {
        $_QJlJ0 = "SELECT id FROM $_Q60QL WHERE Name="._OPQLR(sprintf($_61jlo, $_Q6llo))." AND users_id=".$_Q6Q1C["users_id"];
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(mysql_num_rows($_Q60l1) > 0) {
          mysql_free_result($_Q60l1);
          $_Q6llo++;
          continue;
        }
        if($_Q60l1)
          mysql_free_result($_Q60l1);
        break;
      }
      $_61jlo = sprintf($_61jlo, $_Q6llo);

      $_61J06 = $_Q6Q1C;
      $_61J06["Name"] = $_61jlo;

      $_jjjjC = TablePrefix._OA0LA($_61J06["Name"]);
      $_QlQC8 = _OALO0($_jjjjC."_members");
      $_ItCCo = _OALO0($_jjjjC."_localblocklist");
      $_jf1J1 = _OALO0($_jjjjC."_localdomainblocklist");
      $_QlIf6 = _OALO0($_jjjjC."_statistics");
      $_QLI8o = _OALO0($_jjjjC."_forms");
      $_j0tio = _OALO0($_jjjjC."_mtas");
      $_j8QJ8 = _OALO0($_jjjjC."_inboxes");
      $_Q6t6j = _OALO0($_jjjjC."_groups");
      $_QLI68 = _OALO0($_jjjjC."_maillisttogroups");
      $_QljIQ = _OALO0($_jjjjC."_maillog");
      $_Qljli = _OALO0($_jjjjC."_edit");
      $_I8Jtl = _OALO0($_jjjjC."_reasons");
      $_I86jt = _OALO0($_jjjjC."_reasonsstat");

      $_61J6o = $_61J06;

      $_QJlJ0 = "INSERT INTO `$_Q60QL` (CreateDate, users_id, MaillistTableName, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, LocalDomainBlocklistTableName, StatisticsTableName, FormsTableName, MTAsTableName, InboxesTableName, MailLogTableName, EditTableName, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName`, Name) ";
      $_QJlJ0 .= "VALUES(NOW(), $_JLfCJ, '$_QlQC8', '$_Q6t6j', '$_QLI68', '$_ItCCo', '$_jf1J1', '$_QlIf6', '$_QLI8o', '$_j0tio', '$_j8QJ8', '$_QljIQ', '$_Qljli', '$_I8Jtl', '$_I86jt', "._OPQLR($_61J06["Name"])." )";

      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1)
        _OAL8F($_QJlJ0);
        else {
         $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
         $_Q6Q1C = mysql_fetch_array($_Q60l1);
         $_JL8I8 = $_Q6Q1C[0];
         $_61J6o["id"] = $_Q6Q1C[0];
         mysql_free_result($_Q60l1);
        }

      $_Ij6Io = join("", file(_O68A8()."newmailinglist.sql"));
      $_Ij6Io = str_replace('`TABLE_MAILLIST`', $_QlQC8, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_GROUPS`', $_Q6t6j, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_MAILLISTTOGROUP`', $_QLI68, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_LOCALBLOCKLIST`', $_ItCCo, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_LOCALDOMAINBLOCKLIST`', $_jf1J1, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_STATISTICS`', $_QlIf6, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_FORMS`', $_QLI8o, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_MTAS`', $_j0tio, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_INBOXES`', $_j8QJ8, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_MAILLOG`', $_QljIQ, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_EDIT`', $_Qljli, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_REASONSFORUNSUBSCRIPE`', $_I8Jtl, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_REASONSFORUNSUBSCRIPESTATISTICS`', $_I86jt, $_Ij6Io);

      $_Ij6il = explode(";", $_Ij6Io);

      for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
        if(trim($_Ij6il[$_Q6llo]) == "") continue;
        $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
        if(!$_Q60l1)
          $_Q60l1 = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
        if(!$_Q60l1)
          _OAL8F($_Ij6il[$_Q6llo]);
      }

      // user
      // user hinzufuegen
      $_QJlJ0 = "INSERT INTO `$_Q6fio` (`users_id`, maillists_id) SELECT `users_id`, $_JL8I8 FROM `$_Q6fio` WHERE `maillists_id`=$_61J06[id]";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);

      if($OwnerUserId != 0) { // ist es Kein Admin?
        $_QJlJ0 = "SELECT `users_id` FROM `$_Q6fio` WHERE `users_id`=$UserId AND, `maillists_id`=$UserId";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
          $_QJlJ0 = "INSERT INTO `$_Q6fio` (`users_id`, `maillists_id`) VALUES($UserId, $_JL8I8)";
          mysql_query($_QJlJ0, $_Q61I1);
        }
        if($_Q60l1)
         mysql_free_result($_Q60l1);
      }

      // tables to copy
      $_jj6l0 = array();
      $_jj6l0[] = "GroupsTableName";
      $_jj6l0[] = "LocalBlocklistTableName";
      $_jj6l0[] = "LocalDomainBlocklistTableName";
      $_jj6l0[] = "FormsTableName";
      $_jj6l0[] = "MTAsTableName";
      $_jj6l0[] = "InboxesTableName";
      //$_jj6l0[] = "MaillistTableName"];
      //$_jj6l0[] = "MailListToGroupsTableName";
      //$_jj6l0[] = "MailLogTableName";
      //$_jj6l0[] = "EditTableName";
      $_jj6l0[] = "ReasonsForUnsubscripeTableName";
      //$_jj6l0[] = "ReasonsForUnsubscripeStatisticsTableName";

      $_61J6o["GroupsTableName"] = $_Q6t6j;
      $_61J6o["LocalBlocklistTableName"] = $_ItCCo;
      $_61J6o["LocalDomainBlocklistTableName"] = $_jf1J1;
      $_61J6o["FormsTableName"] = $_QLI8o;
      $_61J6o["MTAsTableName"] = $_j0tio;
      $_61J6o["InboxesTableName"] = $_j8QJ8;
      //$_61J6o["MailListToGroupsTableName"] = $_QLI68;
      //$_61J6o["MaillistTableName"] = $_QlQC8;
      //$_61J6o["MailLogTableName"] = $_QljIQ;
      //$_61J6o["EditTableName"] = $_Qljli;
      $_61J6o["ReasonsForUnsubscripeTableName"] = $_I8Jtl;
      //$_61J6o["ReasonsForUnsubscripeStatisticsTableName"] = $_I86jt;

      // copy src row to dest row
      reset($_61J06);
      $_61JL0 = false;
      $_I16jJ = array();
      foreach($_61J06 as $key => $_Q6ClO){
        if($key == "forms_id") $_61JL0 = true;
        if(!$_61JL0) continue;
        if($key == "Name") continue;
        $_I16jJ[] = "`$key`="._OPQLR($_Q6ClO);
      }
      $_QJlJ0 = "UPDATE `$_Q60QL` SET ".join(", ", $_I16jJ)." WHERE id=$_61J6o[id]";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);

      // copy src tables to dest tables
      for($_Qf0Ct=0;$_Qf0Ct<count($_jj6l0); $_Qf0Ct++) {
        $key = $_jj6l0[$_Qf0Ct];
        $_QJlJ0 = "INSERT INTO `$_61J6o[$key]` SELECT * FROM `$_61J06[$key]`";
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }


    }

  }

  function _OFAF1($MailingListId, $_JLtIL, $_Q6t6j = "", $_QLI68="", $_QLI8o = ""){
    global $_Q60QL, $_Q6jOo, $_IoCtL, $_QCLCI, $_QoOft, $_Q61I1;
    $MailingListId = intval($MailingListId);
    if($MailingListId == 0) return false;
    if(!is_array($_JLtIL))
      $_JLtIL = array($_JLtIL);
    if(empty($_Q6t6j) || empty($_QLI68) || empty($_QLI8o)){
       $_Q68ff = "SELECT FormsTableName, GroupsTableName, MailListToGroupsTableName FROM $_Q60QL WHERE id=$MailingListId";
       $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
       _OAL8F($_Q68ff);
       if($_Q60l1 && $_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
          $_QLI8o = $_Q6Q1C["FormsTableName"];
          $_Q6t6j = $_Q6Q1C["GroupsTableName"];
          $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
          mysql_free_result($_Q60l1);
       } else
         return false;
    }

    mysql_query("BEGIN", $_Q61I1);

    for($_Q6llo=0; $_Q6llo<count($_JLtIL); $_Q6llo++) {
       $_JLtIL[$_Q6llo] = intval($_JLtIL[$_Q6llo]);
       if($_JLtIL[$_Q6llo] == 0) continue;
       $_QJlJ0 = "DELETE FROM `$_Q6t6j` WHERE id=".$_JLtIL[$_Q6llo];
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       // maillist to group
       $_QJlJ0 = "DELETE FROM `$_QLI68` WHERE groups_id=".$_JLtIL[$_Q6llo];
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);

       // forms table
       $_QJlJ0 = "UPDATE `$_QLI8o` SET GroupsOption=1 WHERE groups_id=".$_JLtIL[$_Q6llo];
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);

         // campaigns, sms campaigns
         $_61jjf = array($_Q6jOo, $_IoCtL, $_QCLCI, $_QoOft);
         reset($_61jjf);
         foreach($_61jjf as $key) {
           if(defined("SML") && $key != $_QoOft) continue;

           $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$key` WHERE maillists_id=$MailingListId";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);
           While($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
             $_QJlJ0 = "DELETE a, b FROM `$_Q6Q1C[GroupsTableName]` a, `$_Q6Q1C[NotInGroupsTableName]` b WHERE a.`ml_groups_id`=b.`ml_groups_id` AND a.`ml_groups_id`=".$_JLtIL[$_Q6llo];
             mysql_query($_QJlJ0, $_Q61I1);
           }
           mysql_free_result($_Q60l1);
         }
    }

    mysql_query("COMMIT", $_Q61I1);

    return true;
  }


?>
