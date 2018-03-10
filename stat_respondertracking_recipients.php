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

  if(isset($_POST['ResponderId']))
    $ResponderId = intval($_POST['ResponderId']);
  else
    if(isset($_GET['ResponderId']))
      $ResponderId = intval($_GET['ResponderId']);
      else
      if ( isset($_POST['OneCampaignListId']) )
         $ResponderId = intval($_POST['OneCampaignListId']);


  if(isset($_POST['TrackingType']))
    $_6Loof = $_POST['TrackingType'];
  else
    if ( isset($_GET['TrackingType']) )
       $_6Loof = $_GET['TrackingType'];

  if(isset($_POST['LinkId']))
    $_jjJoO = intval($_POST['LinkId']);
  else
    if ( isset($_GET['LinkId']) )
       $_jjJoO = intval($_GET['LinkId']);

  if(isset($_POST['FUMailId']))
    $_J16Jf = intval($_POST['FUMailId']);
  else
    if ( isset($_GET['FUMailId']) )
       $_J16Jf = intval($_GET['FUMailId']);

  if(isset($_POST['DistribListEntryId']))
    $_6lQ61 = intval($_POST['DistribListEntryId']);
  else
    if ( isset($_GET['DistribListEntryId']) )
       $_6lQ61 = intval($_GET['DistribListEntryId']);

  if(isset($_POST['OneDLEId']))
    $_6lQ61 = intval($_POST['OneDLEId']);
  else
    if ( isset($_GET['OneDLEId']) )
       $_6lQ61 = intval($_GET['OneDLEId']);

  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);
  if(isset($SendStatId) && empty($SendStatId))
    unset($SendStatId);

  if(isset($_POST['ResponderType']))
    $ResponderType = $_POST['ResponderType'];
  else
    if ( isset($_GET['ResponderType']) )
       $ResponderType = $_GET['ResponderType'];

  if(!isset($_6Loof) || !isset($ResponderId) || !isset($ResponderType) )
    exit;

  if(isset($_jjJoO) && $_jjJoO == "")
    unset($_jjJoO);


  if(isset($_J16Jf) && $_J16Jf == "")
    unset($_J16Jf);
  if(isset($_6lQ61) && $_6lQ61 == "")
    unset($_6lQ61);

  if($_6Loof == "Openers")
    $_6LCIj = "TrackingOpeningsByRecipientTableName";
    else
    $_6LCIj = "TrackingLinksByRecipientTableName";

  $_jj1tl = _OAP0L($ResponderType);
  if($_jj1tl)
    $_6LCl6 = _OABJE($_jj1tl);

  if($ResponderType == "FollowUpResponder") {
    if(!isset($_J16Jf)) {
      include_once("fumailselect.inc.php");
      if(!isset($_POST["FUMailId"]))
         exit;
         else
         $_J16Jf = intval($_POST["FUMailId"]);
    }
  }

  if($ResponderType == "DistributionList") {
    if(!isset($_6lQ61)) {
      include_once("distriblistentryselect.inc.php");
      if(!isset($_POST["OneDLEId"]))
         exit;
         else
         $_6lQ61 = intval($_POST["OneDLEId"]);
    }
  }

  if(empty($_6LCl6)) return false;

  // set saved values
  if (count($_POST) == 0  ) {
    include_once("savedoptions.inc.php");
    $_6lOQj = _LQB6D("BrowseRespondersTrackingFilter");
    if( $_6lOQj != "" ) {
      $_QllO8 = @unserialize($_6lOQj);
      if($_QllO8 !== false)
        $_POST = array_merge($_POST, $_QllO8);
    }
  }

  if( isset($_GET["startdate"]) && isset($_GET["enddate"]) ) {
     $_POST["startdate"] = $_GET["startdate"];
     $_POST["enddate"] = $_GET["enddate"];
  }
  if( isset($_POST["startdate"]) && isset($_POST["enddate"]) ) {
    if($INTERFACE_LANGUAGE != "de") {
      $_jC1lo = $_POST["startdate"];
      $_jCQ0I = $_POST["enddate"];
    } else {
      $_Q8otJ = explode('.', $_POST["startdate"]);
      $_jC1lo = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
      $_Q8otJ = explode('.', $_POST["enddate"]);
      $_jCQ0I = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
    }
  }

  if($OwnerOwnerUserId == 0x5A) exit;

  $_jC1lo .= " 00:00:00";
  $_jCQ0I .= " 23:59:59";

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  $_I0600 = "";

  if($ResponderType == "FollowUpResponder") {
     $_QJlJ0 = "SELECT $_6LCl6.FUMailsTableName, $_6LCl6.maillists_id, $_6LCl6.Name, $_Q60QL.MaillistTableName, $_Q60QL.GroupsTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.users_id FROM $_6LCl6 LEFT JOIN $_Q60QL ON $_Q60QL.id=$_6LCl6.maillists_id WHERE $_6LCl6.id=$ResponderId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     $_QJlJ0 = "SELECT $_6LCIj, LinksTableName FROM $_Q6Q1C[FUMailsTableName] WHERE id=$_J16Jf";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_6tLoi = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
     $_Q6Q1C = array_merge($_Q6Q1C, $_6tLoi);

  } else{
    $_QllO8 = "";
    if($ResponderType == "DistributionList"){
      $_QllO8="`$_6LCl6`.`CurrentSendTableName`, ";
    }

    $_QJlJ0 = "SELECT $_QllO8 $_6LCIj, $_6LCl6.maillists_id, $_6LCl6.LinksTableName, $_6LCl6.Name, $_Q60QL.MaillistTableName, $_Q60QL.GroupsTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.users_id FROM $_6LCl6 LEFT JOIN $_Q60QL ON $_Q60QL.id=$_6LCl6.maillists_id WHERE $_6LCl6.id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  }
  $_QlQC8 = $_Q6Q1C["MaillistTableName"];
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
  $OneMailingListId = $_Q6Q1C["maillists_id"];
  $_6LCIj = $_Q6Q1C[$_6LCIj];
  $_IjILj = $_Q6Q1C["LinksTableName"];
  $_6Liio = $_Q6Q1C["users_id"];
  if($_QlQC8 == "") exit; // hack

  if($ResponderType == "DistributionList" && isset($SendStatId)){
      $_QJlJ0 = "SELECT `id` FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `distriblistentry_id`=$_6lQ61";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q8OiJ = mysql_fetch_assoc($_Q60l1)){
        mysql_free_result($_Q60l1);
        $SendStatId = $_Q8OiJ["id"];
      } else
        exit; // hack
  }

  // ********************* Actions

  if (count($_POST) != 0) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }

    $_I680t = !isset($_POST["RecipientsActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneRecipientId"]) && $_POST["OneRecipientId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["RecipientsActions"]) ) {

        // nur hier die Listenaktionen machen

        if($_POST["RecipientsActions"] == "AssignToGroups" ) {
          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "AssignToGroupsAdditionally" ) {
          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "RemoveRecipients") {
          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
        }

        if($_POST["RecipientsActions"] == "MoveRecipients") {
          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_IoO1t) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000038"].join("<br />", $_IoO1t);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000037"];
        }

        if($_POST["RecipientsActions"] == "CopyRecipients") {
          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_IoO1t) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000040"].join("<br />", $_IoO1t);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000039"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") {
          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToGlobalBlacklist") {
          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "ResetInactiveState") {
          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000168"];
        }
        if($_POST["RecipientsActions"] == "ResetBounceState") {
          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000166"];
        }

    }

    if( isset($_POST["OneRecipientAction"]) && isset($_POST["OneRecipientId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneRecipientAction"] == "EditRecipientProperties") {
        include_once("recipientedit.php");
        exit;
      }

      if($_POST["OneRecipientAction"] == "DeleteRecipient") {
        include_once("recipients_ops.inc.php");

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
      }
    }

  }
  // ********************* Actions /

  if(isset($_jjJoO)) {
    $_QJlJ0 = "SELECT `Link` FROM `$_IjILj` WHERE `id`=$_jjJoO";
    if(isset($SendStatId))
      $_QJlJ0 .= " AND `distriblistentry_id`=$_6lQ61";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      if(strlen($_Q6Q1C["Link"]) > 40)
        $_Q6Q1C["Link"] = substr($_Q6Q1C["Link"], 0, 40)."...";
    } else
      exit; // hack
  }

  // Template
  if($_6Loof == "Openers")
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000702"], $_POST["startdate"], $_POST["enddate"]), $_I0600, 'stat_respondertracking_recipients', 'stat_campaigntracking_recipients_snipped.htm');
    else
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000703"], $_Q6Q1C["Link"], $_POST["startdate"], $_POST["enddate"]), $_I0600, 'stat_respondertracking_recipients', 'stat_campaigntracking_recipients_snipped.htm');

  $_QJCJi = str_replace('name="ResponderId"', 'name="ResponderId" value="'.$ResponderId.'"', $_QJCJi);
  $_QJCJi = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QJCJi);
  $_QJCJi = str_replace('name="TrackingType"', 'name="TrackingType" value="'.$_6Loof.'"', $_QJCJi);
  $_QJCJi = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QJCJi);
  $_QJCJi = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QJCJi);
  if(isset($_J16Jf))
     $_QJCJi = str_replace('name="FUMailId"', 'name="FUMailId" value="'.$_J16Jf.'"', $_QJCJi);
  if(isset($_6lQ61))
     $_QJCJi = str_replace('name="DistribListEntryId"', 'name="DistribListEntryId" value="'.$_6lQ61.'"', $_QJCJi);
  if(isset($SendStatId))
     $_QJCJi = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QJCJi);
  if(isset($_jjJoO)){
    $_QJCJi = str_replace('name="LinkId"', 'name="LinkId" value="'.$_jjJoO.'"', $_QJCJi);
    $_QJCJi = _OP6PQ($_QJCJi, "<if:notlinktracking>", "</if:notlinktracking>");
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:FirstAccess>", "</LABEL:FirstAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000696"]);
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LastAccess>", "</LABEL:LastAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000697"]);
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ClickCount>", "</LABEL:ClickCount>", $resourcestrings[$INTERFACE_LANGUAGE]["000698"]);
  } else{
    $_QJCJi = str_replace("<if:notlinktracking>", "", $_QJCJi);
    $_QJCJi = str_replace("</if:notlinktracking>", "", $_QJCJi);

    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:FirstAccess>", "</LABEL:FirstAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000693"]);
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LastAccess>", "</LABEL:LastAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000694"]);
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ClickCount>", "</LABEL:ClickCount>", $resourcestrings[$INTERFACE_LANGUAGE]["000695"]);
  }
  $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);

  // security check, because of GET
  if($OwnerUserId != 0) { // ist es kein Admin?
    $_QJlJ0 = "SELECT COUNT(users_id) FROM $_Q6fio WHERE users_id=$UserId AND maillists_id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_IO08Q = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
    } else
      $_IO08Q[0] = 0;
    if($_IO08Q[0] == 0) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["MailingListPermissionsError"]);
      print $_QJCJi;
      exit;
    }
  } else if($_6Liio != $UserId){
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["MailingListPermissionsError"]);
      print $_QJCJi;
      exit;
  }

  // default SQL query to get recipients
  $_QJlJ0 = "SELECT  {} FROM $_QlQC8 RIGHT JOIN $_6LCIj ON $_QlQC8.id=$_6LCIj.Member_id WHERE ($_6LCIj.ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).") {WHERE}";

  if(isset($_jjJoO))
    $_QJlJ0 = str_replace("{WHERE}", "AND $_6LCIj.Links_id=$_jjJoO {WHERE} ", $_QJlJ0);

  if(isset($SendStatId))
    $_QJlJ0 = str_replace("{WHERE}", "AND $_6LCIj.SendStat_Id=$SendStatId {WHERE} ", $_QJlJ0);

  // List of MailingLists SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_Q68ff .= " WHERE (users_id=$UserId)";
     else {
      $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_Q68ff .= " AND $_Q60QL.id<>$OneMailingListId ORDER BY Name ASC";

  // List of Groups
  $_Q6tC6 = "SELECT DISTINCT id, Name FROM $_Q6t6j ORDER BY Name ASC";

  $_QJCJi = _OLDOF($_QlQC8, $_QJlJ0, $_Q68ff, $_Q6tC6, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeRecipientEdit"]) {
      $_Q6ICj = _LJRLJ($_Q6ICj, "CopyRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "MoveRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AddRecipientToLocalBlacklist");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AddRecipientToGlobalBlacklist");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AssignToGroups");
    }

    if(!$_QJojf["PrivilegeRecipientRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteRecipient");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "MoveRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AssignToGroups");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  print $_QJCJi;

  function _OLDOF($_QlQC8, $_QJlJ0, $_Q68ff, $_Q6tC6, $_Q6ICj) {
    global $UserId, $_SESSION, $_Q8f1L, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1, $_QlQC8, $_QLI68, $_Q6t6j;
    global $_6LCIj, $_jC1lo, $_jCQ0I, $_Q6QiO, $_jjJoO, $SendStatId, $_Qofjo, $_Q6JJJ;

    $_I61Cl = array();

    // Searchstring
    if( isset( $_POST["RcptsSearchFor"] ) && ($_POST["RcptsSearchFor"] != "") ) {
      $_I61Cl["RcptsSearchFor"] = $_POST["RcptsSearchFor"];
      $_I6oQj = $_QlQC8."u_LastName";

      if( isset( $_POST["Rcptsfieldname"] ) && ($_POST["Rcptsfieldname"] != "") ) {
        $_I61Cl["Rcptsfieldname"] = $_POST["Rcptsfieldname"];
        $_QllO8 = substr($_POST["Rcptsfieldname"], 9);
        if($_QllO8 != "All") {
             $_I6oQj = "`$_QlQC8`."."`$_QllO8`";
          }
          else {
            $_I6oQj = "";
            $_QLLjo = array();
            $_QtjtL = array();
            _OAJL1($_QlQC8, $_QLLjo);
            for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
              if( _OPLFQ("u_", $_QLLjo[$_Q6llo]) != 1 ) continue;
              $_QtjtL[] = "("."`$_QlQC8`.`$_QLLjo[$_Q6llo]` LIKE '%".trim($_POST["RcptsSearchFor"])."%')";
            }
          }

      }

      if($_I6oQj != "")
        $_QJlJ0 .= " AND ($_I6oQj LIKE '%".trim($_POST["RcptsSearchFor"])."%')";
        else
        if(count($_QtjtL) > 0)
          $_QJlJ0 .= " AND (".join(" OR ", $_QtjtL).")";


    } else {
      $_I61Cl["RcptsSearchFor"] = "";
      $_I61Cl["Rcptsfieldname"] = "SearchForu_LastName";
    }

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["RcptsItemsPerPage"])) {
       $_QllO8 = intval($_POST["RcptsItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["RcptsItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['RcptsPageSelected'])) || ($_POST['RcptsPageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['RcptsPageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;

    $_QtjtL = str_replace('{WHERE}', '', $_QtjtL);
    if(!isset($_jjJoO))
      $_QtjtL = str_replace('{}', "COUNT(DISTINCT $_6LCIj.Member_id)", $_QtjtL);
      else
      $_QtjtL = str_replace('{}', "COUNT($_6LCIj.Member_id)", $_QtjtL);

    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    _OAL8F($_QtjtL);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "End") )
       $_I6Q6O = $_I6IJ8;

    if ( ($_I6Q6O > $_I6IJ8) || ($_I6Q6O <= 0) )
       $_I6Q6O = 1;

    $_IJQQI = ($_I6Q6O - 1) * $_I6Q68;

    $_Q6i6i = "";
    for($_Q6llo=1; $_Q6llo<=$_I6IJ8; $_Q6llo++)
      if($_Q6llo != $_I6Q6O)
       $_Q6i6i .= "<option>$_Q6llo</option>";
       else
       $_Q6i6i .= '<option selected="selected">'.$_Q6llo.'</option>';

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:PAGES>", "</OPTION:PAGES>", $_Q6i6i);

    // Nav-Buttons
    $_I6ICC = "";
    if($_I6Q6O == 1) {
      $_I6ICC .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_I6Q6O == $_I6IJ8) || ($_I6Qfj == 0) ) {
      $_I6ICC .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_I6Qfj == 0)
      $_I6ICC .= "  DisableItem('RcptsPageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    // Sort
    $_I6jfj = " ORDER BY u_LastName ASC";
    if( isset( $_POST["Rcptssortfieldname"] ) && ($_POST["Rcptssortfieldname"] != "") ) {
      $_I61Cl["Rcptssortfieldname"] = $_POST["Rcptssortfieldname"];
      if($_POST["Rcptssortfieldname"] == "SortName")
         $_I6jfj = " ORDER BY u_LastName";
      if($_POST["Rcptssortfieldname"] == "SortFirstName")
         $_I6jfj = " ORDER BY u_FirstName";
      if($_POST["Rcptssortfieldname"] == "SortEMail")
         $_I6jfj = " ORDER BY u_EMail";
      if($_POST["Rcptssortfieldname"] == "SortSalutation")
         $_I6jfj = " ORDER BY u_Salutation";
      if($_POST["Rcptssortfieldname"] == "Sortid")
         $_I6jfj = " ORDER BY $_6LCIj.Member_id";
      if (isset($_POST["Rcptssortorder"]) ) {
         $_I61Cl["Rcptssortorder"] = $_POST["Rcptssortorder"];
         if($_POST["Rcptssortorder"] == "ascending")
           $_I6jfj .= " ASC";
           else
           $_I6jfj .= " DESC";
         }
    } else {
      $_I61Cl["Rcptssortfieldname"] = "SortName";
      $_I61Cl["Rcptssortorder"] = "ascending";
    }
    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";


    $_QJlJ0 = str_replace('{WHERE}', '', $_QJlJ0);

    if(!isset($_jjJoO))
      $_QJlJ0 = str_replace('{}', " DISTINCT $_QlQC8.id, $_QlQC8.*, $_6LCIj.Member_id", $_QJlJ0);
      else
      $_QJlJ0 = str_replace('{}', " $_QlQC8.*, $_6LCIj.Member_id", $_QJlJ0);

    // Columns
    $_IL1fi = _LQB6D("PersTrackingRcptsListColumns");
    if( $_IL1fi != "") {
      $_QllO8 = @unserialize($_IL1fi);
      if($_QllO8 !== false)
        $_IL1fi = $_QllO8;
        else
        $_IL1fi = array();
    } else
      $_IL1fi = array();

    if(count($_IL1fi) <= 1) {
       $_IL1fi[] = "u_EMail";
       $_IL1fi[] = "ActionsColumn;right";
    }
    $_ILQj1 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE'";
    $_Q60l1 = mysql_query($_ILQj1, $_Q61I1);
    $_I16jJ = array();
    while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
      $_I16jJ[$_Q6Q1C["fieldname"]] = $_Q6Q1C["text"];
    }
    mysql_free_result($_Q60l1);

    $_ILIiJ = array();
    $_ILjQI = array();
    for($_Q6llo=0; $_Q6llo<count($_IL1fi); $_Q6llo++){
      $key = $_IL1fi[$_Q6llo];
      if(!isset($_I16jJ[$key])) continue;
      $_ILIiJ[] = $_I16jJ[$key];
      $_ILjQI[] = $key;
    }

    // actions column
    $_ILjtO = _OP81D($_Q6ICj, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>");
    $_ILJ1Q = _OP81D($_ILjtO, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>");
    $_ILjtO = _OPR6L($_ILjtO, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>", "");
    if(in_array("ActionsColumn;right", $_IL1fi)) {
      $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>", "");
    } else {
      $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>", "");
    }

    $_ILJlo = _OP81D($_Q6ICj, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>");
    $_Q6tjl = "";
    for($_Q6llo=0; $_Q6llo<count($_ILIiJ); $_Q6llo++){
      $_Q6tjl .= _OPR6L($_ILJlo, "<HEAD:COLUMNNAME>", "</HEAD:COLUMNNAME>", $_ILIiJ[$_Q6llo]);
    }
    if(in_array("ActionsColumn;right", $_IL1fi)) {
      $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:ACTIONS_RIGHT>", "</HEAD:ACTIONS_RIGHT>", $_ILJ1Q.$_Q6JJJ.$_ILjtO);
    } else{
      $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:ACTIONS_LEFT>", "</HEAD:ACTIONS_LEFT>", $_ILjtO.$_ILJ1Q.$_Q6JJJ);
    }

    $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>", $_Q6tjl);

    $_Q6ICj = str_replace ("<HEAD:ACTIONS_SPACER>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</HEAD:ACTIONS_SPACER>", "", $_Q6ICj);
    $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:ACTIONS_RIGHT>", "</HEAD:ACTIONS_RIGHT>", "");
    $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:ACTIONS_LEFT>", "</HEAD:ACTIONS_LEFT>", "");

    // Columns /

    $_IL66t = _OP81D($_Q6ICj, "<BODY:ACTIONS>", "</BODY:ACTIONS>");
    $_ILf1t = _OP81D($_IL66t, "<BODY:ACTIONS_SPACER>", "</BODY:ACTIONS_SPACER>");
    $_IL66t = _OPR6L($_IL66t, "<BODY:ACTIONS_SPACER>", "</BODY:ACTIONS_SPACER>", "");
    $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ACTIONS>", "</BODY:ACTIONS>", "");

    $_ILfo1 = _OP81D($_Q6ICj, "<BODY:EMAILFIELD>", "</BODY:EMAILFIELD>");
    $_IL8Jl = _OP81D($_Q6ICj, "<BODY:FIELD>", "</BODY:FIELD>");
    $_Q6tjl = "";
    for($_Q6llo=0; $_Q6llo<count($_ILjQI); $_Q6llo++){
      if($_ILjQI[$_Q6llo] != "u_EMail")
        $_Q6tjl .= _OPR6L($_IL8Jl, "<LIST:FIELDNAME>", "</LIST:FIELDNAME>", "<LIST:".strtoupper($_ILjQI[$_Q6llo])."></LIST:".strtoupper($_ILjQI[$_Q6llo]).">");
        else
        $_Q6tjl .= $_ILfo1.$_Q6JJJ;
    }

    $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ENTRIES>", "</BODY:ENTRIES>", $_Q6tjl);

    if(in_array("ActionsColumn;right", $_IL1fi)) {
      $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ACTIONS_RIGHT>", "</BODY:ACTIONS_RIGHT>", $_ILf1t.$_Q6JJJ.$_IL66t);
    } else{
      $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ACTIONS_LEFT>", "</BODY:ACTIONS_LEFT>", $_IL66t.$_ILf1t.$_Q6JJJ);
    }

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    // sql query
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["Member_id"]);
      if($_Q6Q1C["u_EMail"] == "")
        $_Q6Q1C["u_EMail"] = $resourcestrings[$INTERFACE_LANGUAGE]["000692"];

      reset($_Q6Q1C);
      foreach($_Q6Q1C as $key => $_Q6ClO) {
         switch ($key) {
           case 'u_EMailFormat':
              $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["$_Q6ClO"];
              break;
           case 'u_Gender':
              if($_Q6ClO == "undefined")
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["undefined"];
              else
              if($_Q6ClO == "m")
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["man"];
              else
              if($_Q6ClO == "w")
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["woman"];
              break;
           case 'u_Birthday':
             if($_Q6ClO != '0000-00-00')
                $_Q6ClO = _OAQOB($_Q6ClO, $INTERFACE_LANGUAGE);
                else
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["undefined"];
             break;
           case 'u_UserFieldBool1':
             if($_Q6ClO <= 0)
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["FALSE"];
                else
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["TRUE"];
             break;
           case 'u_UserFieldBool2':
             if($_Q6ClO <= 0)
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["FALSE"];
                else
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["TRUE"];
             break;
           case 'u_UserFieldBool3':
             if($_Q6ClO <= 0)
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["FALSE"];
                else
                $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["TRUE"];
             break;
         }
         $key = strtoupper($key);
         if($_Q6ClO == "") $_Q6ClO = "&nbsp;";
         $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:$key>", "</LIST:$key>", $_Q6ClO);
      }

      if(!isset($_jjJoO))
        $_QtjtL = "SELECT COUNT(Clicks) FROM $_6LCIj WHERE ($_6LCIj.ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).") AND Member_id=$_Q6Q1C[Member_id]";
        else
        $_QtjtL = "SELECT SUM(Clicks) FROM $_6LCIj WHERE ($_6LCIj.ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).") AND Member_id=$_Q6Q1C[Member_id] AND Links_id=$_jjJoO";
      if(isset($SendStatId))
        $_QtjtL .= " AND `SendStat_id`=$SendStatId";
      $_6lOQO = mysql_query($_QtjtL, $_Q61I1);
      $_6tLoi = mysql_fetch_row($_6lOQO);
      mysql_free_result($_6lOQO);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ACCESSCOUNT>", "</LIST:ACCESSCOUNT>", $_6tLoi[0]);

      $_QtjtL = "SELECT DATE_FORMAT(ADateTime, $_Q6QiO) FROM $_6LCIj WHERE ($_6LCIj.ADateTime BETWEEN "._OPQLR($_jC1lo)." AND "._OPQLR($_jCQ0I).") AND Member_id=$_Q6Q1C[Member_id]";
      if(isset($_jjJoO))
        $_QtjtL .= " AND Links_id=$_jjJoO";
      if(isset($SendStatId))
        $_QtjtL .= " AND `SendStat_id`=$SendStatId";
      $_QtjtL .= " ORDER BY ADateTime ASC";
      $_6lOQO = mysql_query($_QtjtL, $_Q61I1);
      $_6tLoi = mysql_fetch_row($_6lOQO);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:FIRSTACCESS>", "</LIST:FIRSTACCESS>", $_6tLoi[0]);

      if(mysql_num_rows($_6lOQO) > 1) { // more than one?
        mysql_data_seek($_6lOQO, mysql_num_rows($_6lOQO)-1);
        $_6tLoi = mysql_fetch_row($_6lOQO);
      }
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LASTACCESS>", "</LIST:LASTACCESS>", $_6tLoi[0]);
      mysql_free_result($_6lOQO);

      $_IOOit = "";
      $_Qf186 = "";
      if($_Q6Q1C["IsActive"] <= 0) {
         $_IOOit = "images/user_deactivated16.gif";
         $_Qf186 = $resourcestrings[$INTERFACE_LANGUAGE]["MemberDeactivated"];
        }
      if($_Q6Q1C["IsActive"] > 0) {
         $_IOOit = "images/user_activated16.gif";
         $_Qf186 = $resourcestrings[$INTERFACE_LANGUAGE]["MemberActivated"];
        }
      if($_Q6Q1C["SubscriptionStatus"] == 'OptInConfirmationPending') {
         $_IOOit = "images/user_unconfirmed_sub16.gif";
         $_Qf186 = $resourcestrings[$INTERFACE_LANGUAGE]["MemberOptInConfirmationPending"];
        }
      if($_Q6Q1C["SubscriptionStatus"] == 'OptOutConfirmationPending') {
         $_IOOit = "images/user_unconfirmed_unsub16.gif";
         $_Qf186 = $resourcestrings[$INTERFACE_LANGUAGE]["MemberOptOutConfirmationPending"];
        }
      $_Qf186 = 'onmouseover="showTooltip(event, \''.$_Qf186.'\');return false" onmouseout="hideTooltip()"';

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:MEMBERIMAGE>", "</LIST:MEMBERIMAGE>", '<img src="'.$_IOOit.'" alt="" width="16" height="16" '.$_Qf186.' />');

      if(isset($_Q6Q1C["id"])) {
        $_Q66jQ = str_replace ('name="DeleteRecipient"', 'name="DeleteRecipient" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
        $_Q66jQ = str_replace ('name="RecipientsIDs[]"', 'name="RecipientsIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      } else {
        $_Q66jQ = _LJ6B1($_Q66jQ, "DeleteRecipient");
        $_Q66jQ = _LJ6B1($_Q66jQ, "RecipientsIDs[]");
      }
      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);
    $_Q6ICj = str_replace ("<BODY:ACTIONS_SPACER>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</BODY:ACTIONS_SPACER>", "", $_Q6ICj);
    $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ACTIONS_RIGHT>", "</BODY:ACTIONS_RIGHT>", "");
    $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ACTIONS_LEFT>", "</BODY:ACTIONS_LEFT>", "");

    // Groups
    $_Q6Oto = "";
    $_Q60l1 = mysql_query($_Q6tC6, $_Q61I1);
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q6Oto .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>';
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_Q6Oto);
    // Groups /


    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_I61Cl["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseRespondersTrackingFilter", serialize($_I61Cl) );
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    // Mailinglisten Liste
    $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
    $_Q6tjl = "";
    if($_Q60l1) {
      while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
        $_Q6tjl .= sprintf('<option value="%d">%s</option>'."\r\n", $_Q6Q1C["id"], $_Q6Q1C["Name"]);
      }
      mysql_free_result($_Q60l1);
    }
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:MAILINGLISTS>", "</OPTION:MAILINGLISTS>", $_Q6tjl);


    return $_Q6ICj;
  }

?>
