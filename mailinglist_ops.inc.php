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

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  if(defined("SWM"))
    include_once("responders_cleanup.inc.php");

  if(!defined("API")) {
    if (count($_POST) <= 1) {
      include_once("browsemailinglists.php");
      exit;
    }

    if(isset($_jOCOQ))
       unset($_jOCOQ);

    $_jOCOQ = array();
    if ( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" )
        $_jOCOQ[] = intval($_POST["OneMailingListId"]);
        else
        if ( isset($_POST["MailingListIDs"]) )
          $_jOCOQ = array_merge($_jOCOQ, $_POST["MailingListIDs"]);


    foreach($_jOCOQ as $key) {
      if(!_LAEJL($key)){
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $_QLJfI;
        exit;
      }
    }

    if  ( ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "DeleteMailLists" ) ||
       ( isset($_POST["OneMailingListAction"]) && $_POST["OneMailingListAction"] == "DeleteList" )
       ) {
          $_IQ0Cj = array();
          _LF8LB($_jOCOQ, $_IQ0Cj);
         }


    if  ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "RemoveRecipients" ) {
          $_IQ0Cj = array();
          _LF881($_jOCOQ, $_IQ0Cj);
    }

    if  ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "DeleteGroups" ) {
          $_IQ0Cj = array();
          _LFP0D($_jOCOQ, $_IQ0Cj);
    }

    if  ( isset($_POST["MailingListActions"]) && $_POST["MailingListActions"] == "DuplicateMailLists" ) {
          $_IQ0Cj = array();
          _LFPER($_jOCOQ, $_IQ0Cj);
    }

    if  ( isset($_POST["MailingListActions"]) && ( ($_POST["MailingListActions"] == "MoveRecipients") || ($_POST["MailingListActions"] == "CopyRecipients") ) ) {

       if(!_LAEJL($_POST["DestMailingList"])){
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
         $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
         print $_QLJfI;
         exit;
       }

       // get the table
       $_QLfol = "SELECT MaillistTableName, StatisticsTableName, MailLogTableName, `EditTableName` FROM $_QL88I WHERE id=".intval($_POST["DestMailingList"]);
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_QLO0f = mysql_fetch_row($_QL8i1);
       $_ff1CQ = $_QLO0f[0];
       $_ffQOo = $_QLO0f[1];
       $_ffQo8 = $_QLO0f[2];
       $_ffIf6 = $_QLO0f[3];
       mysql_free_result($_QL8i1);
       $_jJi11 = array();
       _LFRJC($_jOCOQ, $_jJi11, $_ff1CQ, $_ffQOo, $_ffQo8, $_ffIf6, ($_POST["MailingListActions"] == "MoveRecipients"), intval($_POST["DestMailingList"]));
    }
  } // if(!defined("API")) {

  function _LFRJC($_jOCOQ, &$_jJi11, $_ff1CQ, $_ffQOo, $_ffQo8, $_ffIf6, $_ffj1J, $_ffj18) {
    global $_QL88I, $_QlQot;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    $_ffj18 = intval($_ffj18);
    $_Iflj0 = array();
    _L8EOB($_ff1CQ, $_Iflj0, array("id"));
    $_IOJoI = join(", ", $_Iflj0);

    reset($_jOCOQ);
    foreach($_jOCOQ as $_Qli6J => $_QltJO) {
      if($_ffj18 == intval($_jOCOQ[$_Qli6J])) {
         $_jJi11[] = $resourcestrings[$INTERFACE_LANGUAGE]["000043"];
         continue;
      }
      $_jOCOQ[$_Qli6J] = intval($_jOCOQ[$_Qli6J]);
      $_QLfol = "SELECT MaillistTableName, StatisticsTableName, MailListToGroupsTableName, MailLogTableName, `EditTableName` FROM $_QL88I WHERE id=".$_jOCOQ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1) continue;
      if (mysql_error($_QLttI) != "") $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_ffjlI = $_QLO0f["MaillistTableName"];
      $_ffJo6 = $_QLO0f["StatisticsTableName"];
      $_ff6j8 = $_QLO0f["MailListToGroupsTableName"];
      $_ff6Jj = $_QLO0f["MailLogTableName"];
      $_fff00 = $_QLO0f["EditTableName"];

      mysql_query("BEGIN", $_QLttI);

      if($_ffj1J)
        $_QLfol = "INSERT INTO `$_ff1CQ` (".$_IOJoI.") ";
        else
        $_QLfol = "INSERT IGNORE INTO `$_ff1CQ` (".$_IOJoI.") ";
      $_QLfol .= "SELECT $_IOJoI FROM $_ffjlI";
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") {
          $_jJi11[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
        }
        else {
                // loeschen
                if($_ffj1J) {
                 $_QLfol = "DELETE FROM `$_ffjlI`";
                 mysql_query($_QLfol, $_QLttI);
                 $_QLfol = "DELETE FROM `$_ffJo6`";
                 mysql_query($_QLfol, $_QLttI);
                 $_QLfol = "DELETE FROM `$_ff6j8`";
                 mysql_query($_QLfol, $_QLttI);
                 $_QLfol = "DELETE FROM `$_ff6Jj`";
                 mysql_query($_QLfol, $_QLttI);
                 $_QLfol = "DELETE FROM `$_fff00`";
                 mysql_query($_QLfol, $_QLttI);
                }
            }

      mysql_query("COMMIT", $_QLttI);

    }

    if($_ffj1J && defined("SWM")) {
      _JQ6FB($_jOCOQ, $_jJi11);
    }
  }

  // we don't check for errors here
  function _LF8LB($_jOCOQ, &$_IQ0Cj) {
    global $_QL88I, $_QlQot, $_IoCo0, $_I616t, $_ICo0J, $_jJLQo, $_j6Ql8, $_QLi60, $_ItfiJ, $_jJL88, $_jJLLf, $_IjC0Q;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;


    $_jJltj = array();
    if(_L8B1P($_IoCo0)) {
      $_jJltj[] = array(
                                   "TableName" => $_IoCo0
                                 );
    }

    if(_L8B1P($_I616t)) {
      $_jJltj[] = array(
                                   "TableName" => $_I616t
                                 );
    }

    if(_L8B1P($_ICo0J)) {
      $_jJltj[] = array(
                                   "TableName" => $_ICo0J
                                 );
    }

    if(_L8B1P($_jJLQo)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLQo
                                 );
    }

    if(_L8B1P($_j6Ql8)) {
      $_jJltj[] = array(
                                   "TableName" => $_j6Ql8
                                 );
    }
    if(_L8B1P($_QLi60)) {
      $_jJltj[] = array(
                                   "TableName" => $_QLi60
                                 );
    }
    if(_L8B1P($_ItfiJ)) {
      $_jJltj[] = array(
                                   "TableName" => $_ItfiJ
                                 );
    }

    if(_L8B1P($_jJL88)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJL88
                                 );
    }

    if(_L8B1P($_jJLLf)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLLf
                                 );
    }

    if(_L8B1P($_IjC0Q)) {
      $_jJltj[] = array(
                                   "TableName" => $_IjC0Q
                                 );
    }

    reset($_jOCOQ);
    foreach($_jOCOQ as $_Qli6J => $_Ql0fO) {
      $_jOCOQ[$_Qli6J] = intval($_jOCOQ[$_Qli6J]);

      // referenzen vorhanden?
      $_j608C = 0;
      for($_QliOt=0; $_QliOt<count($_jJltj); $_QliOt++) {
        $_QLfol = "SELECT COUNT(*) FROM `".$_jJltj[$_QliOt]["TableName"]."` WHERE `maillists_id`=".$_jOCOQ[$_Qli6J];
        $_jjJfo = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_jj6L6 = mysql_fetch_row($_jjJfo);
        $_j608C += $_jj6L6[0];
        mysql_free_result($_jjJfo);
        if($_j608C > 0) break;

        if($_jJltj[$_QliOt]["TableName"] == $_I616t) {
           $_QLfol = "SELECT COUNT(*) FROM `".$_jJltj[$_QliOt]["TableName"]."` WHERE (`OnFollowUpDoneAction`=2 AND `OnFollowUpDoneCopyToMailList`=$_jOCOQ[$_Qli6J]) OR (`OnFollowUpDoneAction`=3 AND `OnFollowUpDoneMoveToMailList`=$_jOCOQ[$_Qli6J]) ";
           $_jjJfo = mysql_query($_QLfol, $_QLttI);
           _L8D88($_QLfol);
           $_jj6L6 = mysql_fetch_row($_jjJfo);
           $_j608C += $_jj6L6[0];
        }

        if($_j608C > 0) break;

      }
      $_QLfol = "SELECT * FROM $_QL88I WHERE id=".$_jOCOQ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      if(!$_QL8i1 || !($_QLO0f = mysql_fetch_assoc($_QL8i1))) continue;
      mysql_free_result($_QL8i1);

      if($_j608C > 0) {
        $_IQ0Cj[] = $_QLO0f["Name"]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000033"];
        continue;
      }

      // drop tables
      foreach($_QLO0f as $key => $_QltJO) {
        if(strpos($key, "TableName") === false) continue;
        $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      }

      // Delete mailistusers reference
      $_QLfol = "DELETE FROM $_QlQot WHERE maillists_id=".$_jOCOQ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      // and now mailists table
      $_QLfol = "DELETE FROM $_QL88I WHERE id=".$_jOCOQ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }
  }

  function _LF881($_jOCOQ, &$_IQ0Cj) {
    global $_QL88I, $_QlQot, $_QLttI;

    reset($_jOCOQ);
    foreach($_jOCOQ as $_Qli6J => $_Ql0fO) {
      $_jOCOQ[$_Qli6J] = intval($_jOCOQ[$_Qli6J]);
      $_QLfol = "SELECT * FROM $_QL88I WHERE id=".$_jOCOQ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1) continue;
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      $_QLO0f = mysql_fetch_array($_QL8i1);
      mysql_free_result($_QL8i1);

      $_fffL6 = array();
      $_fffL6[] = "MaillistTableName";
      $_fffL6[] = "MailListToGroupsTableName";
      $_fffL6[] = "StatisticsTableName";
      $_fffL6[] = "MailLogTableName";
      $_fffL6[] = "EditTableName";

      mysql_query("BEGIN", $_QLttI);

      // remove all entries
      for($_Qli6J=0; $_Qli6J<count($_fffL6); $_Qli6J++) {
        $_QLfol = "DELETE FROM `".$_QLO0f[$_fffL6[$_Qli6J]]."`";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      }

      mysql_query("COMMIT", $_QLttI);

    }
    if(defined("SWM"))
      _JQ6FB($_jOCOQ, $_IQ0Cj);
  }

  // we don't check for errors here
  function _LFP0D($_jOCOQ, &$_IQ0Cj) {
    global $_QL88I, $_QLi60, $_jJLLf, $_IjC0Q, $_I616t, $_jJLQo, $_QLttI;

    reset($_jOCOQ);
    foreach($_jOCOQ as $_Qli6J => $_Ql0fO) {
      $_jOCOQ[$_Qli6J] = intval($_jOCOQ[$_Qli6J]);
      $_QLfol = "SELECT `GroupsTableName`, `MailListToGroupsTableName` FROM `$_QL88I` WHERE id=".$_jOCOQ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1) continue;
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      $_QLO0f = mysql_fetch_array($_QL8i1);
      mysql_free_result($_QL8i1);

      mysql_query("BEGIN", $_QLttI);

      // GroupsTableName
      $_QLfol = "DELETE FROM `$_QLO0f[GroupsTableName]`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      // MailListToGroupsTableName
      $_QLfol = "DELETE FROM `$_QLO0f[MailListToGroupsTableName]`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;


        // campaigns, sms campaigns
        $_ff8oi = array($_QLi60, $_jJLLf, $_IjC0Q, $_I616t, $_jJLQo);
        reset($_ff8oi);
        foreach($_ff8oi as $key) {
          if(defined("SML") && $key != $_IjC0Q) continue;

          $_QLfol = "SELECT `id`, `GroupsTableName`, `NotInGroupsTableName` FROM `$key` WHERE maillists_id=".$_jOCOQ[$_Qli6J];
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
          While($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
            $_QLfol = "DELETE FROM `$_QLO0f[GroupsTableName]`";
            if($key == $_QLi60)
               $_QLfol .= " WHERE `Campaigns_id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_QLfol = "DELETE FROM `$_QLO0f[NotInGroupsTableName]`";
            if($key == $_QLi60)
               $_QLfol .= " WHERE `Campaigns_id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
          }
          mysql_free_result($_QL8i1);
        }

      mysql_query("COMMIT", $_QLttI);

    }
  }


  function _LFPER($_jOCOQ, &$_IQ0Cj){
    global $_QL88I, $_QlQot, $UserId, $OwnerUserId, $_QLttI;

    if($OwnerUserId == 0) // ist es ein Admin?
       $_fjL01 = $UserId;
       else
       $_fjL01 = $OwnerUserId;

    reset($_jOCOQ);
    foreach($_jOCOQ as $_Qli6J => $_Ql0fO) {
      $_jOCOQ[$_Qli6J] = intval($_jOCOQ[$_Qli6J]);
      $_QLfol = "SELECT * FROM `$_QL88I` WHERE id=".$_jOCOQ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1) continue;
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      $_Qli6J = 1;
      $_fft6i = $_QLO0f["Name"]." (%d)";
      while (true) {
        $_QLfol = "SELECT id FROM $_QL88I WHERE Name="._LRAFO(sprintf($_fft6i, $_Qli6J))." AND users_id=".$_QLO0f["users_id"];
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(mysql_num_rows($_QL8i1) > 0) {
          mysql_free_result($_QL8i1);
          $_Qli6J++;
          continue;
        }
        if($_QL8i1)
          mysql_free_result($_QL8i1);
        break;
      }
      $_fft6i = sprintf($_fft6i, $_Qli6J);

      $_fftt0 = $_QLO0f;
      $_fftt0["Name"] = $_fft6i;

      $_J0Lo8 = TablePrefix._L8A8P($_fftt0["Name"]);
      $_I8I6o = _L8D00($_J0Lo8."_members");
      $_jjj8f = _L8D00($_J0Lo8."_localblocklist");
      $_Jj6f0 = _L8D00($_J0Lo8."_localdomainblocklist");
      $_I8jjj = _L8D00($_J0Lo8."_statistics");
      $_IfJoo = _L8D00($_J0Lo8."_forms");
      $_ji10i = _L8D00($_J0Lo8."_mtas");
      $_JJffC = _L8D00($_J0Lo8."_inboxes");
      $_QljJi = _L8D00($_J0Lo8."_groups");
      $_IfJ66 = _L8D00($_J0Lo8."_maillisttogroups");
      $_I8jLt = _L8D00($_J0Lo8."_maillog");
      $_I8Jti = _L8D00($_J0Lo8."_edit");
      $_jQIIl = _L8D00($_J0Lo8."_reasons");
      $_jQIt6 = _L8D00($_J0Lo8."_reasonsstat");

      $_ffO1f = $_fftt0;

      $_QLfol = "INSERT INTO `$_QL88I` (CreateDate, users_id, MaillistTableName, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, LocalDomainBlocklistTableName, StatisticsTableName, FormsTableName, MTAsTableName, InboxesTableName, MailLogTableName, EditTableName, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName`, Name) ";
      $_QLfol .= "VALUES(NOW(), $_fjL01, '$_I8I6o', '$_QljJi', '$_IfJ66', '$_jjj8f', '$_Jj6f0', '$_I8jjj', '$_IfJoo', '$_ji10i', '$_JJffC', '$_I8jLt', '$_I8Jti', '$_jQIIl', '$_jQIt6', "._LRAFO($_fftt0["Name"])." )";

      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1)
        _L8D88($_QLfol);
        else {
         $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
         $_QLO0f = mysql_fetch_array($_QL8i1);
         $_fjLIf = $_QLO0f[0];
         $_ffO1f["id"] = $_QLO0f[0];
         mysql_free_result($_QL8i1);
        }

      $_IiIlQ = join("", file(_LOCFC()."newmailinglist.sql"));
      $_IiIlQ = str_replace('`TABLE_MAILLIST`', $_I8I6o, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_GROUPS`', $_QljJi, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_MAILLISTTOGROUP`', $_IfJ66, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_LOCALBLOCKLIST`', $_jjj8f, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_LOCALDOMAINBLOCKLIST`', $_Jj6f0, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_STATISTICS`', $_I8jjj, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_FORMS`', $_IfJoo, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_MTAS`', $_ji10i, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_INBOXES`', $_JJffC, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_MAILLOG`', $_I8jLt, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_EDIT`', $_I8Jti, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_REASONSFORUNSUBSCRIPE`', $_jQIIl, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_REASONSFORUNSUBSCRIPESTATISTICS`', $_jQIt6, $_IiIlQ);

      $_IijLl = explode(";", $_IiIlQ);

      for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
        if(trim($_IijLl[$_Qli6J]) == "") continue;
        $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
        if(!$_QL8i1)
          $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
        if(!$_QL8i1)
          _L8D88($_IijLl[$_Qli6J]);
      }

      // user
      // user hinzufuegen
      $_QLfol = "INSERT INTO `$_QlQot` (`users_id`, maillists_id) SELECT `users_id`, $_fjLIf FROM `$_QlQot` WHERE `maillists_id`=$_fftt0[id]";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);

      if($OwnerUserId != 0) { // ist es Kein Admin?
        $_QLfol = "SELECT `users_id` FROM `$_QlQot` WHERE `users_id`=$UserId AND, `maillists_id`=$UserId";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
          $_QLfol = "INSERT INTO `$_QlQot` (`users_id`, `maillists_id`) VALUES($UserId, $_fjLIf)";
          mysql_query($_QLfol, $_QLttI);
        }
        if($_QL8i1)
         mysql_free_result($_QL8i1);
      }

      // tables to copy
      $_J1QfQ = array();
      $_J1QfQ[] = "GroupsTableName";
      $_J1QfQ[] = "LocalBlocklistTableName";
      $_J1QfQ[] = "LocalDomainBlocklistTableName";
      $_J1QfQ[] = "FormsTableName";
      $_J1QfQ[] = "MTAsTableName";
      $_J1QfQ[] = "InboxesTableName";
      //$_J1QfQ[] = "MaillistTableName"];
      //$_J1QfQ[] = "MailListToGroupsTableName";
      //$_J1QfQ[] = "MailLogTableName";
      //$_J1QfQ[] = "EditTableName";
      $_J1QfQ[] = "ReasonsForUnsubscripeTableName";
      //$_J1QfQ[] = "ReasonsForUnsubscripeStatisticsTableName";

      $_ffO1f["GroupsTableName"] = $_QljJi;
      $_ffO1f["LocalBlocklistTableName"] = $_jjj8f;
      $_ffO1f["LocalDomainBlocklistTableName"] = $_Jj6f0;
      $_ffO1f["FormsTableName"] = $_IfJoo;
      $_ffO1f["MTAsTableName"] = $_ji10i;
      $_ffO1f["InboxesTableName"] = $_JJffC;
      //$_ffO1f["MailListToGroupsTableName"] = $_IfJ66;
      //$_ffO1f["MaillistTableName"] = $_I8I6o;
      //$_ffO1f["MailLogTableName"] = $_I8jLt;
      //$_ffO1f["EditTableName"] = $_I8Jti;
      $_ffO1f["ReasonsForUnsubscripeTableName"] = $_jQIIl;
      //$_ffO1f["ReasonsForUnsubscripeStatisticsTableName"] = $_jQIt6;

      // copy src row to dest row
      reset($_fftt0);
      $_ffo00 = false;
      $_IOJoI = array();
      foreach($_fftt0 as $key => $_QltJO){
        if($key == "forms_id") $_ffo00 = true;
        if(!$_ffo00) continue;
        if($key == "Name") continue;
        $_IOJoI[] = "`$key`="._LRAFO($_QltJO);
      }
      $_QLfol = "UPDATE `$_QL88I` SET ".join(", ", $_IOJoI)." WHERE id=$_ffO1f[id]";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);

      // copy src tables to dest tables
      for($_QliOt=0;$_QliOt<count($_J1QfQ); $_QliOt++) {
        $key = $_J1QfQ[$_QliOt];
        $_QLfol = "INSERT INTO `$_ffO1f[$key]` SELECT * FROM `$_fftt0[$key]`";
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }


    }

  }

  function _LFADO($MailingListId, $_fjlOl, $_QljJi = "", $_IfJ66="", $_IfJoo = ""){
    global $_QL88I, $_QLi60, $_jJLLf, $_I616t, $_IjC0Q, $_jJLQo, $_QLttI;
    $MailingListId = intval($MailingListId);
    if($MailingListId == 0) return false;
    if(!is_array($_fjlOl))
      $_fjlOl = array($_fjlOl);
    if(empty($_QljJi) || empty($_IfJ66) || empty($_IfJoo)){
       $_QlI6f = "SELECT FormsTableName, GroupsTableName, MailListToGroupsTableName FROM $_QL88I WHERE id=$MailingListId";
       $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
       _L8D88($_QlI6f);
       if($_QL8i1 && $_QLO0f=mysql_fetch_assoc($_QL8i1)) {
          $_IfJoo = $_QLO0f["FormsTableName"];
          $_QljJi = $_QLO0f["GroupsTableName"];
          $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
          mysql_free_result($_QL8i1);
       } else
         return false;
    }

    mysql_query("BEGIN", $_QLttI);

    for($_Qli6J=0; $_Qli6J<count($_fjlOl); $_Qli6J++) {
       $_fjlOl[$_Qli6J] = intval($_fjlOl[$_Qli6J]);
       if($_fjlOl[$_Qli6J] == 0) continue;
       $_QLfol = "DELETE FROM `$_QljJi` WHERE id=".$_fjlOl[$_Qli6J];
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       // maillist to group
       $_QLfol = "DELETE FROM `$_IfJ66` WHERE groups_id=".$_fjlOl[$_Qli6J];
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);

       // forms table
       $_QLfol = "UPDATE `$_IfJoo` SET GroupsOption=1 WHERE groups_id=".$_fjlOl[$_Qli6J];
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);

         // campaigns, sms campaigns
         $_ff8oi = array($_QLi60, $_jJLLf, $_I616t, $_IjC0Q, $_jJLQo);
         reset($_ff8oi);
         foreach($_ff8oi as $key) {
           if(defined("SML") && $key != $_IjC0Q) continue;

           $_QLfol = "SELECT `id`, `GroupsTableName`, `NotInGroupsTableName` FROM `$key` WHERE maillists_id=$MailingListId";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           _L8D88($_QLfol);
           While($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             $_QLlO6 = "";
             if($key == $_QLi60)
               $_QLlO6 = "a.`Campaigns_id`=$_QLO0f[id] AND";
             $_QLfol = "DELETE a, b FROM `$_QLO0f[GroupsTableName]` a, `$_QLO0f[NotInGroupsTableName]` b WHERE $_QLlO6 a.`ml_groups_id`=b.`ml_groups_id` AND a.`ml_groups_id`=".$_fjlOl[$_Qli6J];
             mysql_query($_QLfol, $_QLttI);
           }
           mysql_free_result($_QL8i1);
         }
    }

    mysql_query("COMMIT", $_QLttI);

    return true;
  }


?>
