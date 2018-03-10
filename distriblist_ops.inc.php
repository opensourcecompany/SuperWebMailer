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

  if(isset($_jLtj8))
     unset($_jLtj8);
  $_jLtj8 = array();
  if ( isset($_POST["OneDistribListId"]) && $_POST["OneDistribListId"] != "" )
      $_jLtj8[] = intval($_POST["OneDistribListId"]);
      else
      if ( isset($_POST["DistribListIDs"]) ) {
        foreach($_POST["DistribListIDs"] as $key)
          $_jLtj8[] = intval($key);
      }


  if( isset($_POST["DistribListActions"]) && $_POST["DistribListActions"] == "DuplicateDistribLists")
    _O88F8($_jLtj8);

  if(isset($_POST["DistribListActions"]) && $_POST["DistribListActions"] == "RemoveDistribLists" || isset($_POST["OneDistribListAction"]) && $_POST["OneDistribListAction"] == "DeleteDistribList" ) {
    $_QtIiC = array();
    _O8PP1($_jLtj8, $_QtIiC);
  }

  // we don't check for errors here
  function _O88F8($_jLtj8) {
    global $_QoOft, $_Q61I1;

#    $_jj6l0[]="CurrentSendTableName";
#    $_jj6l0[]="CurrentUsedMTAsTableName";
    $_jj6l0[]="GroupsTableName";
    $_jj6l0[]="NotInGroupsTableName";
    $_jj6l0[]="MTAsTableName";
#    $_jj6l0[]="RStatisticsTableName";
    $_jj6l0[]="LinksTableName";
#    $_jj6l0[]="TrackingOpeningsTableName";
#    $_jj6l0[]="TrackingOpeningsByRecipientTableName";
#    $_jj6l0[]="TrackingLinksTableName";
#    $_jj6l0[]="TrackingLinksByRecipientTableName";
#    $_jj6l0[]="TrackingUserAgentsTableName";
#    $_jj6l0[]="TrackingOSsTableName";

    $_j0O01 = 0;
    for($_Q6llo=0; $_Q6llo<count($_jLtj8); $_Q6llo++) {
      $_QJlJ0 = "SELECT * FROM $_QoOft WHERE id=".intval($_jLtj8[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      unset($_Q6Q1C["id"]);
      $_Q6Q1C["CreateDate"] = "NOW()";

      $_j06O8 = "";
      for($_Qf0Ct=1; $_Qf0Ct<200000; $_Qf0Ct++) {
        $_QJlJ0 = "SELECT id FROM $_QoOft WHERE Name="._OPQLR($_Q6Q1C["Name"].sprintf(" (%d)", $_Qf0Ct));
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q60l1 && mysql_num_rows($_Q60l1) == 0) {
          mysql_free_result($_Q60l1);
          $_jiiOJ = $_Q6Q1C["Name"].sprintf(" (%d)", $_Qf0Ct);
          break;
        }
        if($_Q60l1)
          mysql_free_result($_Q60l1);
      }

      if($_jiiOJ == "") {
        print "can't find unique name, this error should never occur"; // will never occur
        exit;
      }

      $_Q6Q1C["Name"] = $_jiiOJ;

      $_jiLi6 = TablePrefix._OA0LA($_Q6Q1C["Name"]);
      $_j0fti = _OALO0($_jiLi6."_sendstate");
      $_j080i = _OALO0($_jiLi6."_currentusedmtas");
      $_j08fl = _OALO0($_jiLi6."_statistics");
      $_Q6t6j = _OALO0($_jiLi6."_groups");
      $_j0t0o = _OALO0($_jiLi6."_nogroups");
      $_j0tio = _OALO0($_jiLi6."_mtas");
      $_IjILj = _OALO0($_jiLi6."_links");
      $_IjjJC = _OALO0($_jiLi6."_topenings");
      $_IjjJi = _OALO0($_jiLi6."_tropenings");
      $_Ijj6J = _OALO0($_jiLi6."_tlinks");
      $_IjJ0J = _OALO0($_jiLi6."_trlinks");
      $_IjJQO = _OALO0($_jiLi6."_useragents");
      $_Ij61o = _OALO0($_jiLi6."_oss");

      $_jjfJ0 = $_Q6Q1C;
      $_Q6Q1C["CurrentSendTableName"] = $_j0fti;
      $_Q6Q1C["CurrentUsedMTAsTableName"] = $_j080i;
      $_Q6Q1C["RStatisticsTableName"] = $_j08fl;
      $_Q6Q1C["GroupsTableName"]= $_Q6t6j;
      $_Q6Q1C["NotInGroupsTableName"]= $_j0t0o;
      $_Q6Q1C["MTAsTableName"]= $_j0tio;
      $_Q6Q1C["LinksTableName"]= $_IjILj;
      $_Q6Q1C["TrackingOpeningsTableName"]= $_IjjJC;
      $_Q6Q1C["TrackingOpeningsByRecipientTableName"]= $_IjjJi;
      $_Q6Q1C["TrackingLinksTableName"]= $_Ijj6J;
      $_Q6Q1C["TrackingLinksByRecipientTableName"]= $_IjJ0J;
      $_Q6Q1C["TrackingUserAgentsTableName"]= $_IjJQO;
      $_Q6Q1C["TrackingOSsTableName"]= $_Ij61o;

      $_QJlJ0 = "";
      foreach($_Q6Q1C as $key => $_Q6ClO) {
        if($_QJlJ0 != "")
          $_QJlJ0 .= ", ";
        if($key != "CreateDate")
          $_QJlJ0 .= "`$key`="._OPQLR($_Q6ClO);
          else
          $_QJlJ0 .= "`$key`=".$_Q6ClO;
      }
      $_QJlJ0 = "INSERT INTO `$_QoOft` SET ".$_QJlJ0;
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);

      // for one campaign only!!!
      $_ItlJl= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
      $_IO08Q=mysql_fetch_row($_ItlJl);
      $_jiLLL = $_IO08Q[0];
      mysql_free_result($_ItlJl);

      $_Ij6Io = join("", file(_O68A8()."distriblist.sql"));
      $_Ij6Io = str_replace('`TABLE_CURRENT_SENDTABLE`', $_j0fti, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_CURRENT_USED_MTAS`', $_j080i, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_C_STATISTICS`', $_j08fl, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_GROUPS`', $_Q6t6j, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_NOTINGROUPS`', $_j0t0o, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_MTAS`', $_j0tio, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_DISTRIBLISTLINKS`', $_IjILj, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGS`', $_IjjJC, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGSBYRECIPIENT`', $_IjjJi, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKS`', $_Ijj6J, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKSBYRECIPIENT`', $_IjJ0J, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGUSERAGENTS`', $_IjJQO, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGOSS`', $_Ij61o, $_Ij6Io);

      $_Ij6il = explode(";", $_Ij6Io);

      for($_Qf0Ct=0; $_Qf0Ct<count($_Ij6il); $_Qf0Ct++) {
        if(trim($_Ij6il[$_Qf0Ct]) == "") continue;
        $_Q60l1 = mysql_query($_Ij6il[$_Qf0Ct]." CHARSET=utf8", $_Q61I1);
        if(!$_Q60l1)
          $_Q60l1 = mysql_query($_Ij6il[$_Qf0Ct], $_Q61I1);
        _OAL8F($_Ij6il[$_Qf0Ct]);
      }

      for($_Qf0Ct=0;$_Qf0Ct<count($_jj6l0); $_Qf0Ct++) {
        $key = $_jj6l0[$_Qf0Ct];
        $_QJlJ0 = "INSERT INTO `$_Q6Q1C[$key]` SELECT * FROM `$_jjfJ0[$key]`";
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }

    }

    return $_jiLLL;
  }

  // we don't check for errors here
  function _O8PP1($_jLtj8, &$_QtIiC) {
    global $_QoOft, $_Qoo8o, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_jLtj8); $_Q6llo++) {
      $_QJlJ0 = "SELECT * FROM `$_QoOft` WHERE id=".intval($_jLtj8[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);

        $_QJlJ0 = "SELECT id FROM `$_Q6Q1C[CurrentSendTableName]` WHERE SendState='Sending' OR SendState='ReSending' LIMIT 0,1";
        $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q8Oj8 && mysql_num_rows($_Q8Oj8) > 0) {
          mysql_free_result($_Q8Oj8);
          $_QtIiC[] = $_Q6Q1C["Name"].": ".$resourcestrings[$INTERFACE_LANGUAGE]["002675"];
          continue;
        }
        if($_Q8Oj8)
          mysql_free_result($_Q8Oj8);

        reset($_Q6Q1C);
        foreach($_Q6Q1C as $key => $_Q6ClO) {
          if (strpos($key, "TableName") !== false) {
            $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6ClO`";
            mysql_query($_QJlJ0, $_Q61I1);
            if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
          }
        }
      }
      mysql_free_result($_Q60l1);

      $_QJlJ0 = "DELETE FROM `$_Qoo8o` WHERE `DistribList_id`=".intval($_jLtj8[$_Q6llo]);
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      // and now from distriblist table
      $_QJlJ0 = "DELETE FROM `$_QoOft` WHERE id=".intval($_jLtj8[$_Q6llo]);
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
    }

  }


?>
