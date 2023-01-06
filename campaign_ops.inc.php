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

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  include_once("removena.inc.php");
  include_once("campaignstools.inc.php");

  if(isset($_J11tt))
     unset($_J11tt);
  $_J11tt = array();
  if ( isset($_POST["OneCampaignListId"]) && $_POST["OneCampaignListId"] > 0 )
      $_J11tt[] = intval($_POST["OneCampaignListId"]);
      else
      if ( isset($_POST["CampaignIDs"]) ) {
        foreach($_POST["CampaignIDs"] as $key)
          $_J11tt[] = intval($key);
      }


  if( isset($_POST["CampaignListActions"]) && $_POST["CampaignListActions"] == "DuplicateCampaigns")
    _LOAB8($_J11tt);

  if(isset($_POST["CampaignListActions"]) && $_POST["CampaignListActions"] == "RemoveCampaigns" || isset($_POST["OneCampaignListAction"]) && $_POST["OneCampaignListAction"] == "DeleteCampaign" ) {
    $_IQ0Cj = array();
    $_J11iO = array();
    for($_Qli6J=0; $_Qli6J<count($_J11tt); $_Qli6J++){
      if(!_LO8EB($_J11tt[$_Qli6J]) && !_LOP86($_J11tt[$_Qli6J])){
        $_J11iO[] = $_J11tt[$_Qli6J];
      } else {
        $_IQ0Cj[] = $_J11tt[$_Qli6J]." is referenced by Split test or Follow Up Responder.";
      }
    }
    _LOB0R($_J11iO, $_IQ0Cj);
  }

  // we don't check for errors here
  function _LOAB8($_J11tt) {
    global $_QLi60, $_QLttI;
    global $_jC80J, $_jC8Li, $_jCtCI, $_jCOO1, $_jCOlI, $_jCo0Q,
    $_jCooQ, $_jCC16, $_jCC1i, $_jCi01, $_jCi8J,
    $_jCiL1, $_jCLC8, $_jClC0;

    _LRP11();

#    $_J1QfQ[]="CurrentSendTableName";
#    $_J1QfQ[]="ArchiveTableName";
    $_J1QfQ[]="GroupsTableName";
    $_J1QfQ[]="NotInGroupsTableName";
    $_J1QfQ[]="MTAsTableName";
#    $_J1QfQ[]="RStatisticsTableName";
    $_J1QfQ[]="LinksTableName";
#    $_J1QfQ[]="TrackingOpeningsTableName";
#    $_J1QfQ[]="TrackingOpeningsByRecipientTableName";
#    $_J1QfQ[]="TrackingLinksTableName";
#    $_J1QfQ[]="TrackingLinksByRecipientTableName";
#    $_J1QfQ[]="TrackingUserAgentsTableName";
#    $_J1QfQ[]="TrackingOSsTableName";

    $_ji160 = 0;
    for($_Qli6J=0; $_Qli6J<count($_J11tt); $_Qli6J++) {
      $_QLfol = "SELECT * FROM $_QLi60 WHERE id=".intval($_J11tt[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      unset($_QLO0f["id"]);
      $_QLO0f["CreateDate"] = "NOW()";
      $_J1I16 = $_QLO0f["CurrentSendTableName"] == $_jC80J;

      $_jC6ot = "";
      for($_QliOt=1; $_QliOt<200000; $_QliOt++) {
        $_QLfol = "SELECT id FROM $_QLi60 WHERE Name="._LRAFO($_QLO0f["Name"].sprintf(" (%d)", $_QliOt));
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
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

      if(!$_J1I16){
        $_Ii01J = TablePrefix._L8A8P($_QLO0f["Name"]);
        $_jClC1 = _L8D00($_Ii01J."_sendstate");
        $_ji0I0 = _L8D00($_Ii01J."_currentusedmtas");
        $_jfJJ1 = _L8D00($_Ii01J."_archive");
        $_ji080 = _L8D00($_Ii01J."_statistics");
        $_QljJi = _L8D00($_Ii01J."_groups");
        $_ji0oi = _L8D00($_Ii01J."_nogroups");
        $_ji10i = _L8D00($_Ii01J."_mtas");
        $_Ii01O = _L8D00($_Ii01J."_links");
        $_Ii0jf = _L8D00($_Ii01J."_topenings");
        $_Ii0lf = _L8D00($_Ii01J."_tropenings");
        $_Ii1i8 = _L8D00($_Ii01J."_tlinks");
        $_IiQjL = _L8D00($_Ii01J."_trlinks");
        $_IiQJi = _L8D00($_Ii01J."_useragents");
        $_IiIQ6 = _L8D00($_Ii01J."_oss");
      } else{
        $_jClC1 = $_jC80J;
        $_ji0I0 = $_jC8Li;
        $_jfJJ1 = $_jCtCI;
        $_ji080 = $_jCooQ;
        $_QljJi = $_jCOO1;
        $_ji0oi = $_jCOlI;
        $_ji10i = $_jCo0Q;
        $_Ii01O = $_jCC16;
        $_Ii0jf = $_jCC1i;
        $_Ii0lf = $_jCi01;
        $_Ii1i8 = $_jCi8J;
        $_IiQjL = $_jCiL1;
        $_IiQJi = $_jCLC8;
        $_IiIQ6 = $_jClC0;
      }

      $_J1j1Q = $_QLO0f;
      $_J1j1Q["id"] = $_J11tt[$_Qli6J];
      $_QLO0f["CurrentSendTableName"] = $_jClC1;
      $_QLO0f["CurrentUsedMTAsTableName"] = $_ji0I0;
      $_QLO0f["ArchiveTableName"] = $_jfJJ1;
      $_QLO0f["RStatisticsTableName"] = $_ji080;
      $_QLO0f["GroupsTableName"]= $_QljJi;
      $_QLO0f["NotInGroupsTableName"]= $_ji0oi;
      $_QLO0f["MTAsTableName"]= $_ji10i;
      $_QLO0f["LinksTableName"]= $_Ii01O;
      $_QLO0f["TrackingOpeningsTableName"]= $_Ii0jf;
      $_QLO0f["TrackingOpeningsByRecipientTableName"]= $_Ii0lf;
      $_QLO0f["TrackingLinksTableName"]= $_Ii1i8;
      $_QLO0f["TrackingLinksByRecipientTableName"]= $_IiQjL;
      $_QLO0f["TrackingUserAgentsTableName"]= $_IiQJi;
      $_QLO0f["TrackingOSsTableName"]= $_IiIQ6;

      $_QLfol = "";
      foreach($_QLO0f as $key => $_QltJO) {
        if($_QLfol != "")
          $_QLfol .= ", ";
        if($key != "CreateDate")
          $_QLfol .= "`$key`="._LRAFO($_QltJO);
          else
          $_QLfol .= "`$key`=".$_QltJO;
      }

      $_QLfol = "INSERT INTO `$_QLi60` SET ".$_QLfol;
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);

      // for one campaign only!!!
      $_jjJfo = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      $_ji160 = $_jj6L6[0];
      mysql_free_result($_jjJfo);

      if(!$_J1I16){
        $_IiIlQ = join("", file(_LOCFC()."campaign.sql"));
        $_IiIlQ = str_replace('`TABLE_CURRENT_SENDTABLE`', $_jClC1, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CURRENT_USED_MTAS`', $_ji0I0, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_ARCHIVETABLE`', $_jfJJ1, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_C_STATISTICS`', $_ji080, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_GROUPS`', $_QljJi, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_NOTINGROUPS`', $_ji0oi, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_MTAS`', $_ji10i, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CAMPAIGNLINKS`', $_Ii01O, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGS`', $_Ii0jf, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGSBYRECIPIENT`', $_Ii0lf, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGLINKS`', $_Ii1i8, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGLINKSBYRECIPIENT`', $_IiQjL, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGUSERAGENTS`', $_IiQJi, $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOSS`', $_IiIQ6, $_IiIlQ);

        $_IijLl = explode(";", $_IiIlQ);

        for($_QliOt=0; $_QliOt<count($_IijLl); $_QliOt++) {
          if(trim($_IijLl[$_QliOt]) == "") continue;
          $_QL8i1 = mysql_query($_IijLl[$_QliOt]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
          if(!$_QL8i1)
            $_QL8i1 = mysql_query($_IijLl[$_QliOt], $_QLttI);
          _L8D88($_IijLl[$_QliOt]);
        }

      }

      for($_QliOt=0;$_QliOt<count($_J1QfQ); $_QliOt++) {
          $key = $_J1QfQ[$_QliOt];

          if($key == "GroupsTableName" || $key == "NotInGroupsTableName")
             $_QLfol = "INSERT INTO `$_QLO0f[$key]` (`Campaigns_id`, `ml_groups_id`) SELECT $_ji160, `ml_groups_id` FROM `$_J1j1Q[$key]` WHERE `Campaigns_id`=$_J1j1Q[id]";
             else
             if($key == "MTAsTableName")
               $_QLfol = "INSERT INTO `$_QLO0f[$key]` (`Campaigns_id`, `mtas_id`, `sortorder`) SELECT $_ji160, `mtas_id`, `sortorder` FROM `$_J1j1Q[$key]` WHERE `Campaigns_id`=$_J1j1Q[id]";
               else
               if($key == "LinksTableName")
                $_QLfol = "INSERT INTO `$_QLO0f[$key]` (`Campaigns_id`, `IsActive`, `Link`, `Description`) SELECT $_ji160, `IsActive`, `Link`, `Description` FROM `$_J1j1Q[$key]` WHERE `Campaigns_id`=$_J1j1Q[id]";

          mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
      }


    }

    return $_ji160;
  }

  // we don't check for errors here
  function _LOB0R($_J11tt, &$_IQ0Cj) {
    global $_QLi60, $resourcestrings, $INTERFACE_LANGUAGE, $_j6JfL, $_QLttI;
    global $_jC80J;

    _LRP11();

    $_J11iO = array();
    for($_Qli6J=0; $_Qli6J<count($_J11tt); $_Qli6J++) {
      $_J11tt[$_Qli6J] = intval($_J11tt[$_Qli6J]);
      $_QLfol = "SELECT * FROM $_QLi60 WHERE id=".$_J11tt[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        $_J1I16 = $_QLO0f["CurrentSendTableName"] == $_jC80J;

        $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE `Campaigns_id`=$_QLO0f[id] AND SendState='Sending' LIMIT 0,1";
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
          mysql_free_result($_I1O6j);
          $_IQ0Cj[] = $_QLO0f["Name"].": ".$resourcestrings[$INTERFACE_LANGUAGE]["000675"];
          continue;
        }
        if($_I1O6j)
          mysql_free_result($_I1O6j);


        $_IIQIL = array();
        if($_J1I16){
          $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE `Campaigns_id`=$_QLO0f[id]";
          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
           $_IIQIL[] = $_I1OfI["id"];
          }
          mysql_free_result($_I1O6j);
          $_QLlO6 = "";
          for($_QliOt=0; $_QliOt<count($_IIQIL); $_QliOt++)
             if($_QLlO6 == "")
               $_QLlO6 = "`SendStat_id`=$_IIQIL[$_QliOt]";
               else
               $_QLlO6 .= " OR `SendStat_id`=$_IIQIL[$_QliOt]";
        }

        reset($_QLO0f);
        foreach($_QLO0f as $key => $_QltJO) {
          if (strpos($key, "TableName") !== false) {

            if(!$_J1I16)
              $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
              else{
                if(_LP0PL($_QltJO, "Campaigns_id"))
                  $_QLfol = "DELETE FROM `$_QltJO` WHERE `Campaigns_id`=$_QLO0f[id]";
                  else{
                    if(count($_IIQIL) == 0) continue;
                    $_QLfol = "DELETE FROM `$_QltJO` WHERE " . $_QLlO6;
                  }
              }

            mysql_query($_QLfol, $_QLttI);
            if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
          }
        }
      }
      mysql_free_result($_QL8i1);

      // and now from campaigns table
      $_QLfol = "DELETE FROM $_QLi60 WHERE id=".$_J11tt[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      $_J11iO[] = $_J11tt[$_Qli6J];

    }

    // newsletter archive
    _J1DOP($_J11iO);
  }


?>
