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
    $_888J0 = $_POST['TrackingType'];
  else
    if ( isset($_GET['TrackingType']) )
       $_888J0 = $_GET['TrackingType'];

  if(isset($_POST['LinkId']))
    $_J10lj = intval($_POST['LinkId']);
  else
    if ( isset($_GET['LinkId']) )
       $_J10lj = intval($_GET['LinkId']);

  if(isset($_POST['FUMailId']))
    $_6Q60L = intval($_POST['FUMailId']);
  else
    if ( isset($_GET['FUMailId']) )
       $_6Q60L = intval($_GET['FUMailId']);

  if(isset($_POST['DistribListEntryId']))
    $_88LLI = intval($_POST['DistribListEntryId']);
  else
    if ( isset($_GET['DistribListEntryId']) )
       $_88LLI = intval($_GET['DistribListEntryId']);

  if(isset($_POST['OneDLEId']))
    $_88LLI = intval($_POST['OneDLEId']);
  else
    if ( isset($_GET['OneDLEId']) )
       $_88LLI = intval($_GET['OneDLEId']);

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

  if(!isset($_888J0) || !isset($ResponderId) || !isset($ResponderType) )
    exit;

  if(isset($_J10lj) && $_J10lj == "")
    unset($_J10lj);


  if(isset($_6Q60L) && $_6Q60L == "")
    unset($_6Q60L);
  if(isset($_88LLI) && $_88LLI == "")
    unset($_88LLI);

  if($_888J0 == "Openers")
    $_88tj6 = "TrackingOpeningsByRecipientTableName";
    else
    $_88tj6 = "TrackingLinksByRecipientTableName";

  $_J0ifL = _LPO6C($ResponderType);
  if($_J0ifL)
    $_88tfJ = _LPLBQ($_J0ifL);

  if($ResponderType == "FollowUpResponder") {
    if(!isset($_6Q60L)) {
      include_once("fumailselect.inc.php");
      if(!isset($_POST["FUMailId"]))
         exit;
         else
         $_6Q60L = intval($_POST["FUMailId"]);
    }
  }

  if($ResponderType == "DistributionList") {
    if(!isset($_88LLI)) {
      include_once("distriblistentryselect.inc.php");
      if(!isset($_POST["OneDLEId"]))
         exit;
         else
         $_88LLI = intval($_POST["OneDLEId"]);
    }
  }

  if(empty($_88tfJ)) return false;

  // set saved values
  if (count($_POST) <= 1  ) {
    include_once("savedoptions.inc.php");
    $_8tJIi = _JOO1L("BrowseRespondersTrackingFilter");
    if( $_8tJIi != "" ) {
      $_I016j = @unserialize($_8tJIi);
      if($_I016j !== false)
        $_POST = array_merge($_POST, $_I016j);
    }
  }

  if( isset($_GET["startdate"]) && isset($_GET["enddate"]) ) {
     $_POST["startdate"] = $_GET["startdate"];
     $_POST["enddate"] = $_GET["enddate"];
  }
  if( isset($_POST["startdate"]) && isset($_POST["enddate"]) ) {
    if($INTERFACE_LANGUAGE != "de") {
      $_JoiCQ = $_POST["startdate"];
      $_JoL0L = $_POST["enddate"];
    } else {
      $_I1OoI = explode('.', $_POST["startdate"]);
      $_JoiCQ = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
      $_I1OoI = explode('.', $_POST["enddate"]);
      $_JoL0L = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
    }
  }

  if($OwnerOwnerUserId == 0x5A) exit;

  $_JoiCQ .= " 00:00:00";
  $_JoL0L .= " 23:59:59";

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  $_Itfj8 = "";

  if($ResponderType == "FollowUpResponder") {
     $_QLfol = "SELECT $_88tfJ.FUMailsTableName, $_88tfJ.maillists_id, $_88tfJ.Name, $_QL88I.MaillistTableName, $_QL88I.GroupsTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.users_id FROM $_88tfJ LEFT JOIN $_QL88I ON $_QL88I.id=$_88tfJ.maillists_id WHERE $_88tfJ.id=$ResponderId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);

     $_QLfol = "SELECT $_88tj6, LinksTableName FROM $_QLO0f[FUMailsTableName] WHERE id=$_6Q60L";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_8QiiO = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
     $_QLO0f = array_merge($_QLO0f, $_8QiiO);

  } else{
    $_I016j = "";
    if($ResponderType == "DistributionList"){
      $_I016j="`$_88tfJ`.`CurrentSendTableName`, ";
    }

    $_QLfol = "SELECT $_I016j $_88tj6, $_88tfJ.maillists_id, $_88tfJ.LinksTableName, $_88tfJ.Name, $_QL88I.MaillistTableName, $_QL88I.GroupsTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.users_id FROM $_88tfJ LEFT JOIN $_QL88I ON $_QL88I.id=$_88tfJ.maillists_id WHERE $_88tfJ.id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  }
  $_I8I6o = $_QLO0f["MaillistTableName"];
  $_QljJi = $_QLO0f["GroupsTableName"];
  $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
  $OneMailingListId = $_QLO0f["maillists_id"];
  $_88tj6 = $_QLO0f[$_88tj6];
  $_Ii01O = $_QLO0f["LinksTableName"];
  $_88OLf = $_QLO0f["users_id"];
  if($_I8I6o == "") exit; // hack

  if($ResponderType == "DistributionList" && isset($SendStatId)){
      $_QLfol = "SELECT `id` FROM `$_QLO0f[CurrentSendTableName]` WHERE `distriblistentry_id`=$_88LLI";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_I1OfI = mysql_fetch_assoc($_QL8i1)){
        mysql_free_result($_QL8i1);
        $SendStatId = $_I1OfI["id"];
      } else
        exit; // hack
  }

  // ********************* Actions

  if (count($_POST) > 1) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }

    $_Ilt8t = !isset($_POST["RecipientsActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneRecipientId"]) && $_POST["OneRecipientId"] != "")  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["RecipientsActions"]) ) {

        // nur hier die Listenaktionen machen

        if($_POST["RecipientsActions"] == "AssignToGroups" ) {
          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "AssignToGroupsAdditionally" ) {
          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "RemoveRecipients") {
          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
        }

        if($_POST["RecipientsActions"] == "MoveRecipients") {
          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_jJi11) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000038"].join("<br />", $_jJi11);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000037"];
        }

        if($_POST["RecipientsActions"] == "CopyRecipients") {
          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_jJi11) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000040"].join("<br />", $_jJi11);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000039"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") {
          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToGlobalBlacklist") {
          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "ResetInactiveState") {
          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000168"];
        }
        if($_POST["RecipientsActions"] == "ResetBounceState") {
          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000166"];
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
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
      }
    }

  }
  // ********************* Actions /

  if(isset($_J10lj)) {
    $_QLfol = "SELECT `Link` FROM `$_Ii01O` WHERE `id`=$_J10lj";
    if(isset($SendStatId))
      $_QLfol .= " AND `distriblistentry_id`=$_88LLI";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      if(strlen($_QLO0f["Link"]) > 40)
        $_QLO0f["Link"] = substr($_QLO0f["Link"], 0, 40)."...";
    } else
      exit; // hack
  }

  // Template
  if($_888J0 == "Openers")
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000702"], $_POST["startdate"], $_POST["enddate"]), $_Itfj8, 'stat_respondertracking_recipients', 'stat_campaigntracking_recipients_snipped.htm');
    else
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000703"], $_QLO0f["Link"], $_POST["startdate"], $_POST["enddate"]), $_Itfj8, 'stat_respondertracking_recipients', 'stat_campaigntracking_recipients_snipped.htm');

  $_QLJfI = _L80DF($_QLJfI, "<if:LinkTracking>");
  $_QLJfI = _L80DF($_QLJfI, "<if:OpeningTracking>");
  $_QLJfI = str_replace('name="ResponderId"', 'name="ResponderId" value="'.$ResponderId.'"', $_QLJfI);
  $_QLJfI = str_replace('name="ResponderType"', 'name="ResponderType" value="'.$ResponderType.'"', $_QLJfI);
  $_QLJfI = str_replace('name="TrackingType"', 'name="TrackingType" value="'.$_888J0.'"', $_QLJfI);
  $_QLJfI = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QLJfI);
  $_QLJfI = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QLJfI);
  if(isset($_6Q60L))
     $_QLJfI = str_replace('name="FUMailId"', 'name="FUMailId" value="'.$_6Q60L.'"', $_QLJfI);
  if(isset($_88LLI))
     $_QLJfI = str_replace('name="DistribListEntryId"', 'name="DistribListEntryId" value="'.$_88LLI.'"', $_QLJfI);
  if(isset($SendStatId))
     $_QLJfI = str_replace('name="SendStatId"', 'name="SendStatId" value="'.$SendStatId.'"', $_QLJfI);
  if(isset($_J10lj)){
    $_QLJfI = str_replace('name="LinkId"', 'name="LinkId" value="'.$_J10lj.'"', $_QLJfI);
    $_QLJfI = _L80DF($_QLJfI, "<if:notlinktracking>", "</if:notlinktracking>");
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:FirstAccess>", "</LABEL:FirstAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000696"]);
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LastAccess>", "</LABEL:LastAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000697"]);
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ClickCount>", "</LABEL:ClickCount>", $resourcestrings[$INTERFACE_LANGUAGE]["000698"]);
  } else{
    $_QLJfI = str_replace("<if:notlinktracking>", "", $_QLJfI);
    $_QLJfI = str_replace("</if:notlinktracking>", "", $_QLJfI);

    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:FirstAccess>", "</LABEL:FirstAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000693"]);
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LastAccess>", "</LABEL:LastAccess>", $resourcestrings[$INTERFACE_LANGUAGE]["000694"]);
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ClickCount>", "</LABEL:ClickCount>", $resourcestrings[$INTERFACE_LANGUAGE]["000695"]);
  }
  $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);

  // security check, because of GET
  if($OwnerUserId != 0) { // ist es kein Admin?
    $_QLfol = "SELECT COUNT(users_id) FROM $_QlQot WHERE users_id=$UserId AND maillists_id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      $_jj6L6 = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
    } else
      $_jj6L6[0] = 0;
    if($_jj6L6[0] == 0) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["MailingListPermissionsError"]);
      print $_QLJfI;
      exit;
    }
  } else if($_88OLf != $UserId){
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["MailingListPermissionsError"]);
      print $_QLJfI;
      exit;
  }

  // default SQL query to get recipients
  $_QLfol = "SELECT  {} FROM $_I8I6o RIGHT JOIN $_88tj6 ON $_I8I6o.id=$_88tj6.Member_id WHERE ($_88tj6.ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).") {WHERE}";

  if(isset($_J10lj))
    $_QLfol = str_replace("{WHERE}", "AND $_88tj6.Links_id=$_J10lj {WHERE} ", $_QLfol);

  if(isset($SendStatId))
    $_QLfol = str_replace("{WHERE}", "AND $_88tj6.SendStat_Id=$SendStatId {WHERE} ", $_QLfol);

  // List of MailingLists SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QlI6f .= " WHERE (users_id=$UserId)";
     else {
      $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }
  $_QlI6f .= " AND $_QL88I.id<>$OneMailingListId ORDER BY Name ASC";

  // List of Groups
  $_QljLL = "SELECT DISTINCT id, Name FROM $_QljJi ORDER BY Name ASC";

  $_QLJfI = _L1D0L($_I8I6o, $_QLfol, $_QlI6f, $_QljLL, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
      $_QLoli = _JJCRD($_QLoli, "CopyRecipients");
      $_QLoli = _JJCRD($_QLoli, "MoveRecipients");
      $_QLoli = _JJCRD($_QLoli, "AddRecipientToLocalBlacklist");
      $_QLoli = _JJCRD($_QLoli, "AddRecipientToGlobalBlacklist");
      $_QLoli = _JJCRD($_QLoli, "AssignToGroups");
    }

    if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteRecipient");
      $_QLoli = _JJCRD($_QLoli, "RemoveRecipients");
      $_QLoli = _JJCRD($_QLoli, "MoveRecipients");
      $_QLoli = _JJCRD($_QLoli, "AssignToGroups");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  print $_QLJfI;

  function _L1D0L($_I8I6o, $_QLfol, $_QlI6f, $_QljLL, $_QLoli) {
    global $UserId, $_SESSION, $_I18lo, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI, $_I8I6o, $_IfJ66, $_QljJi;
    global $_88tj6, $_JoiCQ, $_JoL0L, $_QLo60, $_J10lj, $SendStatId, $_Ij8oL, $_QLl1Q;

    $_Il0o6 = array();

    // Searchstring
    if( isset( $_POST["RcptsSearchFor"] ) && ($_POST["RcptsSearchFor"] != "") ) {
      $_Il0o6["RcptsSearchFor"] = $_POST["RcptsSearchFor"];
      $_IliOC = $_I8I6o."u_LastName";

      if( isset( $_POST["Rcptsfieldname"] ) && ($_POST["Rcptsfieldname"] != "") ) {
        $_Il0o6["Rcptsfieldname"] = $_POST["Rcptsfieldname"];
        $_I016j = substr($_POST["Rcptsfieldname"], 9);
        if($_I016j != "All") {
             $_IliOC = "`$_I8I6o`."."`$_I016j`";
          }
          else {
            $_IliOC = "";
            $_Iflj0 = array();
            $_QLlO6 = array();
            _L8EOB($_I8I6o, $_Iflj0);
            for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
              if( _LRDB8("u_", $_Iflj0[$_Qli6J]) != 1 ) continue;
              $_QLlO6[] = "("."`$_I8I6o`.`$_Iflj0[$_Qli6J]` LIKE '%".trim($_POST["RcptsSearchFor"])."%')";
            }
          }

      }

      if($_IliOC != "")
        $_QLfol .= " AND ($_IliOC LIKE '%".trim($_POST["RcptsSearchFor"])."%')";
        else
        if(count($_QLlO6) > 0)
          $_QLfol .= " AND (".join(" OR ", $_QLlO6).")";


    } else {
      $_Il0o6["RcptsSearchFor"] = "";
      $_Il0o6["Rcptsfieldname"] = "SearchForu_LastName";
    }

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["RcptsItemsPerPage"])) {
       $_I016j = intval($_POST["RcptsItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["RcptsItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['RcptsPageSelected'])) || ($_POST['RcptsPageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['RcptsPageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;

    $_QLlO6 = str_replace('{WHERE}', '', $_QLlO6);
    if(!isset($_J10lj))
      $_QLlO6 = str_replace('{}', "COUNT(DISTINCT $_88tj6.Member_id)", $_QLlO6);
      else
      $_QLlO6 = str_replace('{}', "COUNT($_88tj6.Member_id)", $_QLlO6);

    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    _L8D88($_QLlO6);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneRecipientId"] ) && ($_POST["OneRecipientId"] == "End") )
       $_IlQQ6 = $_IlILC;

    if ( ($_IlQQ6 > $_IlILC) || ($_IlQQ6 <= 0) )
       $_IlQQ6 = 1;

    $_Iil6i = ($_IlQQ6 - 1) * $_Il1jO;

    $_QlOjt = "";
    for($_Qli6J=1; $_Qli6J<=$_IlILC; $_Qli6J++)
      if($_Qli6J != $_IlQQ6)
       $_QlOjt .= "<option>$_Qli6J</option>";
       else
       $_QlOjt .= '<option selected="selected">'.$_Qli6J.'</option>';

    $_QLoli = _L81BJ($_QLoli, "<OPTION:PAGES>", "</OPTION:PAGES>", $_QlOjt);

    // Nav-Buttons
    $_Iljoj = "";
    if($_IlQQ6 == 1) {
      $_Iljoj .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_IlQQ6 == $_IlILC) || ($_IlQll == 0) ) {
      $_Iljoj .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_IlQll == 0)
      $_Iljoj .= "  DisableItem('RcptsPageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    // Sort
    $_IlJj8 = " ORDER BY u_LastName ASC";
    if( isset( $_POST["Rcptssortfieldname"] ) && ($_POST["Rcptssortfieldname"] != "") ) {
      $_Il0o6["Rcptssortfieldname"] = $_POST["Rcptssortfieldname"];
      if($_POST["Rcptssortfieldname"] == "SortName")
         $_IlJj8 = " ORDER BY u_LastName";
      if($_POST["Rcptssortfieldname"] == "SortFirstName")
         $_IlJj8 = " ORDER BY u_FirstName";
      if($_POST["Rcptssortfieldname"] == "SortEMail")
         $_IlJj8 = " ORDER BY u_EMail";
      if($_POST["Rcptssortfieldname"] == "SortSalutation")
         $_IlJj8 = " ORDER BY u_Salutation";
      if($_POST["Rcptssortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY $_88tj6.Member_id";
      if (isset($_POST["Rcptssortorder"]) ) {
         $_Il0o6["Rcptssortorder"] = $_POST["Rcptssortorder"];
         if($_POST["Rcptssortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["Rcptssortfieldname"] = "SortName";
      $_Il0o6["Rcptssortorder"] = "ascending";
    }
    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";


    $_QLfol = str_replace('{WHERE}', '', $_QLfol);

    if(!isset($_J10lj))
      $_QLfol = str_replace('{}', " DISTINCT $_I8I6o.id, $_I8I6o.*, $_88tj6.Member_id", $_QLfol);
      else
      $_QLfol = str_replace('{}', " $_I8I6o.*, $_88tj6.Member_id", $_QLfol);

    // Columns
    $_jtCOQ = _JOO1L("PersTrackingRcptsListColumns");
    if( $_jtCOQ != "") {
      $_I016j = @unserialize($_jtCOQ);
      if($_I016j !== false)
        $_jtCOQ = $_I016j;
        else
        $_jtCOQ = array();
    } else
      $_jtCOQ = array();

    if(count($_jtCOQ) <= 1) {
       $_jtCOQ[] = "u_EMail";
       $_jtCOQ[] = "ActionsColumn;right";
    }
    $_jti0i = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE'";
    $_QL8i1 = mysql_query($_jti0i, $_QLttI);
    $_IOJoI = array();
    while($_QLO0f = mysql_fetch_array($_QL8i1)) {
      $_IOJoI[$_QLO0f["fieldname"]] = $_QLO0f["text"];
    }
    mysql_free_result($_QL8i1);

    $_jtL0i = array();
    $_jtLjt = array();
    for($_Qli6J=0; $_Qli6J<count($_jtCOQ); $_Qli6J++){
      $key = $_jtCOQ[$_Qli6J];
      if(!isset($_IOJoI[$key])) continue;
      $_jtL0i[] = $_IOJoI[$key];
      $_jtLjt[] = $key;
    }

    // actions column
    $_jtl0C = _L81DB($_QLoli, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>");
    $_jtlt6 = _L81DB($_jtl0C, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>");
    $_jtl0C = _L81BJ($_jtl0C, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>", "");
    if(in_array("ActionsColumn;right", $_jtCOQ)) {
      $_QLoli = _L81BJ($_QLoli, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>", "");
    } else {
      $_QLoli = _L81BJ($_QLoli, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>", "");
    }

    $_jO06f = _L81DB($_QLoli, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>");
    $_QlIf1 = "";
    for($_Qli6J=0; $_Qli6J<count($_jtL0i); $_Qli6J++){
      $_QlIf1 .= _L81BJ($_jO06f, "<HEAD:COLUMNNAME>", "</HEAD:COLUMNNAME>", $_jtL0i[$_Qli6J]);
    }
    if(in_array("ActionsColumn;right", $_jtCOQ)) {
      $_QLoli = _L81BJ($_QLoli, "<HEAD:ACTIONS_RIGHT>", "</HEAD:ACTIONS_RIGHT>", $_jtlt6.$_QLl1Q.$_jtl0C);
    } else{
      $_QLoli = _L81BJ($_QLoli, "<HEAD:ACTIONS_LEFT>", "</HEAD:ACTIONS_LEFT>", $_jtl0C.$_jtlt6.$_QLl1Q);
    }

    $_QLoli = _L81BJ($_QLoli, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>", $_QlIf1);

    $_QLoli = str_replace ("<HEAD:ACTIONS_SPACER>", "", $_QLoli);
    $_QLoli = str_replace ("</HEAD:ACTIONS_SPACER>", "", $_QLoli);
    $_QLoli = _L81BJ($_QLoli, "<HEAD:ACTIONS_RIGHT>", "</HEAD:ACTIONS_RIGHT>", "");
    $_QLoli = _L81BJ($_QLoli, "<HEAD:ACTIONS_LEFT>", "</HEAD:ACTIONS_LEFT>", "");

    // Columns /

    $_jO0O8 = _L81DB($_QLoli, "<BODY:ACTIONS>", "</BODY:ACTIONS>");
    $_jO0CJ = _L81DB($_jO0O8, "<BODY:ACTIONS_SPACER>", "</BODY:ACTIONS_SPACER>");
    $_jO0O8 = _L81BJ($_jO0O8, "<BODY:ACTIONS_SPACER>", "</BODY:ACTIONS_SPACER>", "");
    $_QLoli = _L81BJ($_QLoli, "<BODY:ACTIONS>", "</BODY:ACTIONS>", "");

    $_jO16Q = _L81DB($_QLoli, "<BODY:EMAILFIELD>", "</BODY:EMAILFIELD>");
    $_jO1fO = _L81DB($_QLoli, "<BODY:FIELD>", "</BODY:FIELD>");
    $_QlIf1 = "";
    for($_Qli6J=0; $_Qli6J<count($_jtLjt); $_Qli6J++){
      if($_jtLjt[$_Qli6J] != "u_EMail")
        $_QlIf1 .= _L81BJ($_jO1fO, "<LIST:FIELDNAME>", "</LIST:FIELDNAME>", "<LIST:".strtoupper($_jtLjt[$_Qli6J])."></LIST:".strtoupper($_jtLjt[$_Qli6J]).">");
        else
        $_QlIf1 .= $_jO16Q.$_QLl1Q;
    }

    $_QLoli = _L81BJ($_QLoli, "<BODY:ENTRIES>", "</BODY:ENTRIES>", $_QlIf1);

    if(in_array("ActionsColumn;right", $_jtCOQ)) {
      $_QLoli = _L81BJ($_QLoli, "<BODY:ACTIONS_RIGHT>", "</BODY:ACTIONS_RIGHT>", $_jO0CJ.$_QLl1Q.$_jO0O8);
    } else{
      $_QLoli = _L81BJ($_QLoli, "<BODY:ACTIONS_LEFT>", "</BODY:ACTIONS_LEFT>", $_jO0O8.$_jO0CJ.$_QLl1Q);
    }

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    // sql query
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["Member_id"]);
      if($_QLO0f["u_EMail"] == "")
        $_QLO0f["u_EMail"] = $resourcestrings[$INTERFACE_LANGUAGE]["000692"];

      reset($_QLO0f);
      foreach($_QLO0f as $key => $_QltJO) {
         switch ($key) {
           case 'u_EMailFormat':
              $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["$_QltJO"];
              break;
           case 'u_Gender':
              if($_QltJO == "undefined")
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["undefined"];
              else
              if($_QltJO == "m")
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["man"];
              else
              if($_QltJO == "w")
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["woman"];
              else
              if($_QltJO == "d")
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["diverse"];
              break;
           case 'u_Birthday':
             if($_QltJO != '0000-00-00')
                $_QltJO = _L8BCR($_QltJO, $INTERFACE_LANGUAGE);
                else
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["undefined"];
             break;
           case 'u_UserFieldBool1':
           case 'u_UserFieldBool2':
           case 'u_UserFieldBool3':
             if($_QltJO <= 0)
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["FALSE"];
                else
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["TRUE"];
             break;
           case 'u_PersonalizedTracking':
             if($_QltJO <= 0)
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
                else
                $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
             break;
         }
         $key = strtoupper($key);
         if($_QltJO == "") $_QltJO = "&nbsp;";
         $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:$key>", "</LIST:$key>", $_QltJO);
      }

      if(!isset($_J10lj))
        $_QLlO6 = "SELECT COUNT(Clicks) FROM $_88tj6 WHERE ($_88tj6.ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).") AND Member_id=$_QLO0f[Member_id]";
        else
        $_QLlO6 = "SELECT SUM(Clicks) FROM $_88tj6 WHERE ($_88tj6.ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).") AND Member_id=$_QLO0f[Member_id] AND Links_id=$_J10lj";
      if(isset($SendStatId))
        $_QLlO6 .= " AND `SendStat_id`=$SendStatId";
      $_8tJfC = mysql_query($_QLlO6, $_QLttI);
      $_8QiiO = mysql_fetch_row($_8tJfC);
      mysql_free_result($_8tJfC);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ACCESSCOUNT>", "</LIST:ACCESSCOUNT>", $_8QiiO[0]);

      $_QLlO6 = "SELECT DATE_FORMAT(ADateTime, $_QLo60) FROM $_88tj6 WHERE ($_88tj6.ADateTime BETWEEN "._LRAFO($_JoiCQ)." AND "._LRAFO($_JoL0L).") AND Member_id=$_QLO0f[Member_id]";
      if(isset($_J10lj))
        $_QLlO6 .= " AND Links_id=$_J10lj";
      if(isset($SendStatId))
        $_QLlO6 .= " AND `SendStat_id`=$SendStatId";
      $_QLlO6 .= " ORDER BY ADateTime ASC";
      $_8tJfC = mysql_query($_QLlO6, $_QLttI);
      $_8QiiO = mysql_fetch_row($_8tJfC);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:FIRSTACCESS>", "</LIST:FIRSTACCESS>", $_8QiiO[0]);

      if(mysql_num_rows($_8tJfC) > 1) { // more than one?
        mysql_data_seek($_8tJfC, mysql_num_rows($_8tJfC)-1);
        $_8QiiO = mysql_fetch_row($_8tJfC);
      }
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LASTACCESS>", "</LIST:LASTACCESS>", $_8QiiO[0]);
      mysql_free_result($_8tJfC);

      $_jjllL = "";
      $_QlLoL = "";
      if($_QLO0f["IsActive"] <= 0) {
         $_jjllL = "images/user_deactivated16.gif";
         $_QlLoL = $resourcestrings[$INTERFACE_LANGUAGE]["MemberDeactivated"];
        }
      if($_QLO0f["IsActive"] > 0) {
         $_jjllL = "images/user_activated16.gif";
         $_QlLoL = $resourcestrings[$INTERFACE_LANGUAGE]["MemberActivated"];
        }
      if($_QLO0f["SubscriptionStatus"] == 'OptInConfirmationPending') {
         $_jjllL = "images/user_unconfirmed_sub16.gif";
         $_QlLoL = $resourcestrings[$INTERFACE_LANGUAGE]["MemberOptInConfirmationPending"];
        }
      if($_QLO0f["SubscriptionStatus"] == 'OptOutConfirmationPending') {
         $_jjllL = "images/user_unconfirmed_unsub16.gif";
         $_QlLoL = $resourcestrings[$INTERFACE_LANGUAGE]["MemberOptOutConfirmationPending"];
        }
      $_QlLoL = 'onmouseover="showTooltip(event, \''.$_QlLoL.'\');return false" onmouseout="hideTooltip()"';

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:MEMBERIMAGE>", "</LIST:MEMBERIMAGE>", '<img src="'.$_jjllL.'" alt="" width="16" height="16" '.$_QlLoL.' />');

      if(isset($_QLO0f["id"])) {
        $_Ql0fO = str_replace ('name="DeleteRecipient"', 'name="DeleteRecipient" value="'.$_QLO0f["id"].'"', $_Ql0fO);
        $_Ql0fO = str_replace ('name="RecipientsIDs[]"', 'name="RecipientsIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      } else {
        $_Ql0fO = _JJC1E($_Ql0fO, "DeleteRecipient");
        $_Ql0fO = _JJC1E($_Ql0fO, "RecipientsIDs[]");
      }
      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);
    $_QLoli = str_replace ("<BODY:ACTIONS_SPACER>", "", $_QLoli);
    $_QLoli = str_replace ("</BODY:ACTIONS_SPACER>", "", $_QLoli);
    $_QLoli = _L81BJ($_QLoli, "<BODY:ACTIONS_RIGHT>", "</BODY:ACTIONS_RIGHT>", "");
    $_QLoli = _L81BJ($_QLoli, "<BODY:ACTIONS_LEFT>", "</BODY:ACTIONS_LEFT>", "");

    // Groups
    $_QlJ8C = "";
    $_QL8i1 = mysql_query($_QljLL, $_QLttI);
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_QlJ8C .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>';
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_QlJ8C);
    // Groups /


    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_Il0o6["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseRespondersTrackingFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    // Mailinglisten Liste
    $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
    $_QlIf1 = "";
    if($_QL8i1) {
      while($_QLO0f=mysql_fetch_array($_QL8i1)) {
        $_QlIf1 .= sprintf('<option value="%d">%s</option>'."\r\n", $_QLO0f["id"], $_QLO0f["Name"]);
      }
      mysql_free_result($_QL8i1);
    }
    $_QLoli = _L81BJ($_QLoli, "<OPTION:MAILINGLISTS>", "</OPTION:MAILINGLISTS>", $_QlIf1);


    return $_QLoli;
  }

?>
