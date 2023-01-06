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

  if(isset($_J11tt))
     unset($_J11tt);
  $_J11tt = array();
  if ( isset($_POST["OneCampaignListId"]) && $_POST["OneCampaignListId"] != "" )
      $_J11tt[] = $_POST["OneCampaignListId"];
      else
      if ( isset($_POST["CampaignIDs"]) )
        $_J11tt = array_merge($_J11tt, $_POST["CampaignIDs"]);


  if( isset($_POST["CampaignListActions"]) && $_POST["CampaignListActions"] == "DuplicateCampaigns")
    _LOAB8($_J11tt);

  if(isset($_POST["CampaignListActions"]) && $_POST["CampaignListActions"] == "RemoveCampaigns" || isset($_POST["OneCampaignListAction"]) && $_POST["OneCampaignListAction"] == "DeleteCampaign" ) {
    $_IQ0Cj = array();
    _LOB0R($_J11tt, $_IQ0Cj);
  }

  // we don't check for errors here
  function _LOAB8($_J11tt) {
    global $_jJLLf;

#    $_J1QfQ[]="CurrentSendTableName";
#    $_J1QfQ[]="ArchiveTableName";
    $_J1QfQ[]="GroupsTableName";
    $_J1QfQ[]="NotInGroupsTableName";
#    $_J1QfQ[]="MTAsTableName";
#    $_J1QfQ[]="RStatisticsTableName";
#    $_J1QfQ[]="LinksTableName";
#    $_J1QfQ[]="TrackingOpeningsTableName";
#    $_J1QfQ[]="TrackingOpeningsByRecipientTableName";
#    $_J1QfQ[]="TrackingLinksTableName";
#    $_J1QfQ[]="TrackingLinksByRecipientTableName";
#    $_J1QfQ[]="TrackingUserAgentsTableName";
#    $_J1QfQ[]="TrackingOSsTableName";

    for($_Qli6J=0; $_Qli6J<count($_J11tt); $_Qli6J++) {
      $_J11tt[$_Qli6J] = intval($_J11tt[$_Qli6J]);
      $_QLfol = "SELECT * FROM `$_jJLLf` WHERE id=".$_J11tt[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      unset($_QLO0f["id"]);
      $_QLO0f["CreateDate"] = "NOW()";

      $_jC6ot = "";
      for($_QliOt=1; $_QliOt<200000; $_QliOt++) {
        $_QLfol = "SELECT id FROM `$_jJLLf` WHERE `Name`="._LRAFO($_QLO0f["Name"].sprintf(" (%d)", $_QliOt));
        $_QL8i1 = mysql_query($_QLfol);
        if($_QL8i1 && mysql_num_rows($_QL8i1) == 0) {
          mysql_free_result($_QL8i1);
          $_jC6ot = $_QLO0f["Name"].sprintf(" (%d)", $_QliOt);
          break;
        }
        if($_QL8i1)
          mysql_free_result($_QL8i1);
      }

      if($_jC6ot == "") {
        print "can't find unique name, this error should never occur"; // will never occur
        exit;
      }

      $_QLO0f["Name"] = $_jC6ot;
      if($_QLO0f["SetupLevel"] == 99)
        $_QLO0f["SetupLevel"] = 1; // new campaign, user must do setup

      $_Ii01J = TablePrefix._L8A8P($_QLO0f["Name"]);
      $_jClC1 = _L8D00($_Ii01J."_sendstate");
      $_ji080 = _L8D00($_Ii01J."_statistics");
      $_QljJi = _L8D00($_Ii01J."_groups");
      $_ji0oi = _L8D00($_Ii01J."_nogroups");

      $_J1j1Q = $_QLO0f;
      $_QLO0f["CurrentSendTableName"] = $_jClC1;
      $_QLO0f["RStatisticsTableName"] = $_ji080;
      $_QLO0f["GroupsTableName"]= $_QljJi;
      $_QLO0f["NotInGroupsTableName"]= $_ji0oi;

      $_QLfol = "";
      foreach($_QLO0f as $key => $_QltJO) {
        if($_QLfol != "")
          $_QLfol .= ", ";
        if($key != "CreateDate")
          $_QLfol .= "`$key`="._LRAFO($_QltJO);
          else
          $_QLfol .= "`$key`=".$_QltJO;
      }
      $_QLfol = "INSERT INTO `$_jJLLf` SET ".$_QLfol;
      mysql_query($_QLfol);
      _L8D88($_QLfol);

      $_IiIlQ = join("", file(_LOCFC()."smscampaign.sql"));
      $_IiIlQ = str_replace('`TABLE_CURRENT_SENDTABLE`', $_jClC1, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_C_STATISTICS`', $_ji080, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_GROUPS`', $_QljJi, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_NOTINGROUPS`', $_ji0oi, $_IiIlQ);

      $_IijLl = explode(";", $_IiIlQ);

      for($_QliOt=0; $_QliOt<count($_IijLl); $_QliOt++) {
        if(trim($_IijLl[$_QliOt]) == "") continue;
        $_QL8i1 = mysql_query($_IijLl[$_QliOt]." CHARSET=" . DefaultMySQLEncoding);
        if(!$_QL8i1)
          $_QL8i1 = mysql_query($_IijLl[$_QliOt]);
        _L8D88($_IijLl[$_QliOt]);
      }

      for($_QliOt=0;$_QliOt<count($_J1QfQ); $_QliOt++) {
        $key = $_J1QfQ[$_QliOt];
        $_QLfol = "INSERT INTO `$_QLO0f[$key]` SELECT * FROM `$_J1j1Q[$key]`";
        mysql_query($_QLfol);
        _L8D88($_QLfol);
      }

    }
  }

  // we don't check for errors here
  function _LOB0R($_J11tt, &$_IQ0Cj) {
    global $_jJLLf, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_J11tt); $_Qli6J++) {
      $_J11tt[$_Qli6J] = intval($_J11tt[$_Qli6J]);
      $_QLfol = "SELECT * FROM `$_jJLLf` WHERE id=".$_J11tt[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $_QLO0f = mysql_fetch_assoc($_QL8i1);

        $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE SendState='Sending' LIMIT 0,1";
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
          mysql_free_result($_I1O6j);
          $_IQ0Cj[] = $_QLO0f["Name"].": ".$resourcestrings[$INTERFACE_LANGUAGE]["001675"];
          continue;
        }
        if($_I1O6j)
          mysql_free_result($_I1O6j);

        reset($_QLO0f);
        foreach($_QLO0f as $key => $_QltJO) {
          if (strpos($key, "TableName") !== false) {
            $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
            mysql_query($_QLfol, $_QLttI);
            if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
          }
        }
      }
      mysql_free_result($_QL8i1);

      // and now from campaigns table
      $_QLfol = "DELETE FROM `$_jJLLf` WHERE id=".$_J11tt[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }

  }


?>
