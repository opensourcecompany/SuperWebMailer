<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

  if(isset($_jjJCt))
     unset($_jjJCt);
  $_jjJCt = array();
  if ( isset($_POST["OneCampaignListId"]) && $_POST["OneCampaignListId"] != "" )
      $_jjJCt[] = $_POST["OneCampaignListId"];
      else
      if ( isset($_POST["CampaignIDs"]) )
        $_jjJCt = array_merge($_jjJCt, $_POST["CampaignIDs"]);


  if( isset($_POST["CampaignListActions"]) && $_POST["CampaignListActions"] == "DuplicateCampaigns")
    _O66DB($_jjJCt);

  if(isset($_POST["CampaignListActions"]) && $_POST["CampaignListActions"] == "RemoveCampaigns" || isset($_POST["OneCampaignListAction"]) && $_POST["OneCampaignListAction"] == "DeleteCampaign" ) {
    $_QtIiC = array();
    _O6R86($_jjJCt, $_QtIiC);
  }

  // we don't check for errors here
  function _O66DB($_jjJCt) {
    global $_IoCtL;

#    $_jj6l0[]="CurrentSendTableName";
#    $_jj6l0[]="ArchiveTableName";
    $_jj6l0[]="GroupsTableName";
    $_jj6l0[]="NotInGroupsTableName";
#    $_jj6l0[]="MTAsTableName";
#    $_jj6l0[]="RStatisticsTableName";
#    $_jj6l0[]="LinksTableName";
#    $_jj6l0[]="TrackingOpeningsTableName";
#    $_jj6l0[]="TrackingOpeningsByRecipientTableName";
#    $_jj6l0[]="TrackingLinksTableName";
#    $_jj6l0[]="TrackingLinksByRecipientTableName";
#    $_jj6l0[]="TrackingUserAgentsTableName";
#    $_jj6l0[]="TrackingOSsTableName";

    for($_Q6llo=0; $_Q6llo<count($_jjJCt); $_Q6llo++) {
      $_jjJCt[$_Q6llo] = intval($_jjJCt[$_Q6llo]);
      $_QJlJ0 = "SELECT * FROM `$_IoCtL` WHERE id=".$_jjJCt[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      unset($_Q6Q1C["id"]);
      $_Q6Q1C["CreateDate"] = "NOW()";

      $_j06O8 = "";
      for($_Qf0Ct=1; $_Qf0Ct<200000; $_Qf0Ct++) {
        $_QJlJ0 = "SELECT id FROM `$_IoCtL` WHERE `Name`="._OPQLR($_Q6Q1C["Name"].sprintf(" (%d)", $_Qf0Ct));
        $_Q60l1 = mysql_query($_QJlJ0);
        if($_Q60l1 && mysql_num_rows($_Q60l1) == 0) {
          mysql_free_result($_Q60l1);
          $_j06O8 = $_Q6Q1C["Name"].sprintf(" (%d)", $_Qf0Ct);
          break;
        }
        if($_Q60l1)
          mysql_free_result($_Q60l1);
      }

      if($_j06O8 == "") {
        print "can't find unique name, this error should never occur"; // will never occur
        exit;
      }

      $_Q6Q1C["Name"] = $_j06O8;
      if($_Q6Q1C["SetupLevel"] == 99)
        $_Q6Q1C["SetupLevel"] = 1; // new campaign, user must do setup

      $_IjI0O = TablePrefix._OA0LA($_Q6Q1C["Name"]);
      $_j0fti = _OALO0($_IjI0O."_sendstate");
      $_j08fl = _OALO0($_IjI0O."_statistics");
      $_Q6t6j = _OALO0($_IjI0O."_groups");
      $_j0t0o = _OALO0($_IjI0O."_nogroups");

      $_jjfJ0 = $_Q6Q1C;
      $_Q6Q1C["CurrentSendTableName"] = $_j0fti;
      $_Q6Q1C["RStatisticsTableName"] = $_j08fl;
      $_Q6Q1C["GroupsTableName"]= $_Q6t6j;
      $_Q6Q1C["NotInGroupsTableName"]= $_j0t0o;

      $_QJlJ0 = "";
      foreach($_Q6Q1C as $key => $_Q6ClO) {
        if($_QJlJ0 != "")
          $_QJlJ0 .= ", ";
        if($key != "CreateDate")
          $_QJlJ0 .= "`$key`="._OPQLR($_Q6ClO);
          else
          $_QJlJ0 .= "`$key`=".$_Q6ClO;
      }
      $_QJlJ0 = "INSERT INTO `$_IoCtL` SET ".$_QJlJ0;
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);

      $_Ij6Io = join("", file(_O68A8()."smscampaign.sql"));
      $_Ij6Io = str_replace('`TABLE_CURRENT_SENDTABLE`', $_j0fti, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_C_STATISTICS`', $_j08fl, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_GROUPS`', $_Q6t6j, $_Ij6Io);
      $_Ij6Io = str_replace('`TABLE_NOTINGROUPS`', $_j0t0o, $_Ij6Io);

      $_Ij6il = explode(";", $_Ij6Io);

      for($_Qf0Ct=0; $_Qf0Ct<count($_Ij6il); $_Qf0Ct++) {
        if(trim($_Ij6il[$_Qf0Ct]) == "") continue;
        $_Q60l1 = mysql_query($_Ij6il[$_Qf0Ct]." CHARSET=utf8");
        if(!$_Q60l1)
          $_Q60l1 = mysql_query($_Ij6il[$_Qf0Ct]);
        _OAL8F($_Ij6il[$_Qf0Ct]);
      }

      for($_Qf0Ct=0;$_Qf0Ct<count($_jj6l0); $_Qf0Ct++) {
        $key = $_jj6l0[$_Qf0Ct];
        $_QJlJ0 = "INSERT INTO `$_Q6Q1C[$key]` SELECT * FROM `$_jjfJ0[$key]`";
        mysql_query($_QJlJ0);
        _OAL8F($_QJlJ0);
      }

    }
  }

  // we don't check for errors here
  function _O6R86($_jjJCt, &$_QtIiC) {
    global $_IoCtL, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_jjJCt); $_Q6llo++) {
      $_jjJCt[$_Q6llo] = intval($_jjJCt[$_Q6llo]);
      $_QJlJ0 = "SELECT * FROM `$_IoCtL` WHERE id=".$_jjJCt[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);

        $_QJlJ0 = "SELECT id FROM `$_Q6Q1C[CurrentSendTableName]` WHERE SendState='Sending' LIMIT 0,1";
        $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q8Oj8 && mysql_num_rows($_Q8Oj8) > 0) {
          mysql_free_result($_Q8Oj8);
          $_QtIiC[] = $_Q6Q1C["Name"].": ".$resourcestrings[$INTERFACE_LANGUAGE]["001675"];
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

      // and now from campaigns table
      $_QJlJ0 = "DELETE FROM `$_IoCtL` WHERE id=".$_jjJCt[$_Q6llo];
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
    }

  }


?>
