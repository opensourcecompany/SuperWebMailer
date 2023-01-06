<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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

  if(isset($_JLCf1))
     unset($_JLCf1);
  $_JLCf1 = array();
  if ( isset($_POST["OneDistribListId"]) && $_POST["OneDistribListId"] > 0 )
      $_JLCf1[] = intval($_POST["OneDistribListId"]);
      else
      if ( isset($_POST["DistribListIDs"]) ) {
        foreach($_POST["DistribListIDs"] as $key)
          $_JLCf1[] = intval($key);
      }


  if( isset($_POST["DistribListActions"]) && $_POST["DistribListActions"] == "DuplicateDistribLists")
    _L6RRB($_JLCf1);

  if(isset($_POST["DistribListActions"]) && $_POST["DistribListActions"] == "RemoveDistribLists" || isset($_POST["OneDistribListAction"]) && $_POST["OneDistribListAction"] == "DeleteDistribList" ) {
    $_IQ0Cj = array();
    _L68QD($_JLCf1, $_IQ0Cj);
  }

  // we don't check for errors here
  function _L6RRB($_JLCf1) {
    global $_IjC0Q, $_QLttI;

#    $_J1QfQ[]="CurrentSendTableName";
#    $_J1QfQ[]="CurrentUsedMTAsTableName";
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
    for($_Qli6J=0; $_Qli6J<count($_JLCf1); $_Qli6J++) {
      $_QLfol = "SELECT * FROM $_IjC0Q WHERE id=".intval($_JLCf1[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      unset($_QLO0f["id"]);
      unset($_QLO0f["EMailsProcessed"]);
      unset($_QLO0f["EMailsSent"]);
      $_QLO0f["CreateDate"] = "NOW()";

      $_jC6ot = "";
      for($_QliOt=1; $_QliOt<200000; $_QliOt++) {
        $_QLfol = "SELECT id FROM $_IjC0Q WHERE Name="._LRAFO($_QLO0f["Name"].sprintf(" (%d)", $_QliOt));
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1 && mysql_num_rows($_QL8i1) == 0) {
          mysql_free_result($_QL8i1);
          $_Ji8J1 = $_QLO0f["Name"].sprintf(" (%d)", $_QliOt);
          break;
        }
        if($_QL8i1)
          mysql_free_result($_QL8i1);
      }

      if($_Ji8J1 == "") {
        print "can't find unique name, this error should never occur"; // will never occur
        exit;
      }

      $_QLO0f["Name"] = $_Ji8J1;

      $_JiOQ0 = TablePrefix._L8A8P($_QLO0f["Name"]);
      $_jClC1 = _L8D00($_JiOQ0."_sendstate");
      $_ji0I0 = _L8D00($_JiOQ0."_currentusedmtas");
      $_ji080 = _L8D00($_JiOQ0."_statistics");
      $_QljJi = _L8D00($_JiOQ0."_groups");
      $_ji0oi = _L8D00($_JiOQ0."_nogroups");
      $_ji10i = _L8D00($_JiOQ0."_mtas");
      $_Ii01O = _L8D00($_JiOQ0."_links");
      $_Ii0jf = _L8D00($_JiOQ0."_topenings");
      $_Ii0lf = _L8D00($_JiOQ0."_tropenings");
      $_Ii1i8 = _L8D00($_JiOQ0."_tlinks");
      $_IiQjL = _L8D00($_JiOQ0."_trlinks");
      $_IiQJi = _L8D00($_JiOQ0."_useragents");
      $_IiIQ6 = _L8D00($_JiOQ0."_oss");

      $_J1j1Q = $_QLO0f;
      $_QLO0f["CurrentSendTableName"] = $_jClC1;
      $_QLO0f["CurrentUsedMTAsTableName"] = $_ji0I0;
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
      $_QLfol = "INSERT INTO `$_IjC0Q` SET ".$_QLfol;
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);

      // for one campaign only!!!
      $_jjJfo= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_jj6L6=mysql_fetch_row($_jjJfo);
      $_Jio1C = $_jj6L6[0];
      mysql_free_result($_jjJfo);

      $_IiIlQ = join("", file(_LOCFC()."distriblist.sql"));
      $_IiIlQ = str_replace('`TABLE_CURRENT_SENDTABLE`', $_jClC1, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_CURRENT_USED_MTAS`', $_ji0I0, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_C_STATISTICS`', $_ji080, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_GROUPS`', $_QljJi, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_NOTINGROUPS`', $_ji0oi, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_MTAS`', $_ji10i, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_DISTRIBLISTLINKS`', $_Ii01O, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGS`', $_Ii0jf, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGSBYRECIPIENT`', $_Ii0lf, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKS`', $_Ii1i8, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKSBYRECIPIENT`', $_IiQjL, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGUSERAGENTS`', $_IiQJi, $_IiIlQ);
      $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGOSS`', $_IiIQ6, $_IiIlQ);

      $_IijLl = explode(";", $_IiIlQ);

      for($_QliOt=0; $_QliOt<count($_IijLl); $_QliOt++) {
        if(trim($_IijLl[$_QliOt]) == "") continue;
        $_QL8i1 = mysql_query($_IijLl[$_QliOt]." CHARSET="  . DefaultMySQLEncoding, $_QLttI);
        if(!$_QL8i1)
          $_QL8i1 = mysql_query($_IijLl[$_QliOt], $_QLttI);
        _L8D88($_IijLl[$_QliOt]);
      }

      for($_QliOt=0;$_QliOt<count($_J1QfQ); $_QliOt++) {
        $key = $_J1QfQ[$_QliOt];
        $_QLfol = "INSERT INTO `$_QLO0f[$key]` SELECT * FROM `$_J1j1Q[$key]`";
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }

    }

    return $_Jio1C;
  }

  // we don't check for errors here
  function _L68QD($_JLCf1, &$_IQ0Cj, $_j0CO6 = true) {
    global $_IjC0Q, $_IjCfJ, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    if($_j0CO6)
      include_once("distribliststools.inc.php");
    
    for($_Qli6J=0; $_Qli6J<count($_JLCf1); $_Qli6J++) {
      $_QLfol = "SELECT * FROM `$_IjC0Q` WHERE id=".intval($_JLCf1[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $_QLO0f = mysql_fetch_assoc($_QL8i1);

        $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE SendState='Sending' OR SendState='ReSending' LIMIT 0,1";
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
          mysql_free_result($_I1O6j);
          $_IQ0Cj[] = $_QLO0f["Name"].": ".$resourcestrings[$INTERFACE_LANGUAGE]["002675"];
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

      if($_j0CO6){
        $_QLfol = "SELECT `MailHTMLText`, `Attachments` FROM `$_IjCfJ` WHERE `DistribList_id`=".intval($_JLCf1[$_Qli6J]);
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        
        while( $_I1OfI = mysql_fetch_assoc($_I1O6j) ){
          _L6ROQ($_I1OfI);
        }
        
        mysql_free_result($_I1O6j);
      }
      
      
      
      $_QLfol = "DELETE FROM `$_IjCfJ` WHERE `DistribList_id`=".intval($_JLCf1[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      // and now from distriblist table
      $_QLfol = "DELETE FROM `$_IjC0Q` WHERE id=".intval($_JLCf1[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }

  }


?>
