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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("searchrecipients_ops.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeRecipientBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if (! isset($_POST["OneMailingListId"]) ) {
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000036"];
    include_once("mailinglistselect.inc.php");
    if (!isset($_POST["OneMailingListId"]) )
       exit;
       else {
         $_Itfj8 = "";
         $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
       }
  }

  if(!_LAEJL($_POST["OneMailingListId"])){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  if(!isset($_Itfj8))
     $_Itfj8 = "";
  if (count($_POST) > 1) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }

    $_Ilt8t = !isset($_POST["RecipientsActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneRecipientAction"]) && $_POST["OneRecipientAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneRecipientId"]) && $_POST["OneRecipientId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["RecipientsActions"]) ) {

        // nur hier die Listenaktionen machen

        if($_POST["RecipientsActions"] == "AssignToGroups" ) {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "AssignToGroupsAdditionally" ) {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "RemoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
        }

        if($_POST["RecipientsActions"] == "MoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"] || !$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_jJi11) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000038"].join("<br />", $_jJi11);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000037"];
        }

        if($_POST["RecipientsActions"] == "CopyRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_jJi11) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000040"].join("<br />", $_jJi11);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000039"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"] || !$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToGlobalBlacklist") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"] || !$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "ResetInactiveState") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000168"];
        }
        if($_POST["RecipientsActions"] == "SetInactiveState") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000171"];
        }
        if($_POST["RecipientsActions"] == "ResetBounceState") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000166"];
        }
        if($_POST["RecipientsActions"] == "SetSubscribedState") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000170"];
        }

    }

    if( isset($_POST["OneRecipientAction"]) && isset($_POST["OneRecipientId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneRecipientAction"] == "EditRecipientProperties") {
        include_once("recipientedit.php");
        exit;
      }

      if($_POST["OneRecipientAction"] == "DeleteRecipient") {

        if($OwnerUserId != 0) {
          if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
            $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QLJfI;
            exit;
          }
        }

        include_once("recipients_ops.inc.php");

        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
      }
    }

  }

  $_jt118 = false;
  // set saved values
  if ( (count($_POST) <= 1) || (isset($_POST["MailingListSelectForm"])) || (isset($_POST["ShowMailingList"])) || ( isset($_POST["OneMailingListAction"]) && $_POST["OneMailingListAction"] == "BrowseRecipients") || isset($_POST["EditPage"])  ) {
    include_once("savedoptions.inc.php");
    $_jt1Cj = _JOO1L("BrowseRecipientsFilter");

    if( $_jt1Cj != "") {
      $_I016j = @unserialize($_jt1Cj);
      if($_I016j !== false) {
        $_POST = array_merge($_POST, $_I016j);
        $_jt118 = count($_I016j);
      }
    }
  }

  if(!$_jt118){
    if(isset($_POST["RcptsSaveFilter"]) || !empty($_POST["RcptsSearchFor"]) || (isset($_POST["ShowOnlyRecipientsGroups"]) && count($_POST["ShowOnlyRecipientsGroups"])) )
      $_jt118 = true;
  }

  // get the table
  $_QLfol = "SELECT MaillistTableName, Name, GroupsTableName, MailListToGroupsTableName, EditTableName FROM $_QL88I WHERE id=".intval($_POST["OneMailingListId"]);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  $_I8I6o = $_QLO0f[0];
  $_jtICQ = $_QLO0f[1];
  $_QljJi = $_QLO0f[2];
  $_IfJ66 = $_QLO0f[3];
  $_I8Jti = $_QLO0f[4];

  // default SQL query to get recipients
  $_QLfol = "SELECT {} FROM $_I8I6o {WHERE} ";
  $_jtj1o = "";

  if(isset($_POST["searchoptions"])){
     if(!empty($_POST["searchoptions"])){
       $_jtjl0 = @unserialize( base64_decode($_POST["searchoptions"]) );
       $_jtj1o = _JO668($_jtjl0);
     } else
       unset($_POST["searchoptions"]);
  }


  // List of MailingLists SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QlI6f .= " WHERE (users_id=$UserId)";
     else {
      $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }
  $_QlI6f .= " AND $_QL88I.id<>$_POST[OneMailingListId] ORDER BY Name ASC";

  // List of Groups
  $_QljLL = "SELECT DISTINCT id, Name FROM $_QljJi ORDER BY Name ASC";

  $_jtJtC = false;
  $_QL8i1 = mysql_query("SELECT id FROM $_QljJi LIMIT 0, 1", $_QLttI);
  $_jtJtC = ($_QL8i1 && mysql_num_rows($_QL8i1));
  if($_QL8i1)
    mysql_free_result($_QL8i1);

  // Template
  $_jt6tQ = $resourcestrings[$INTERFACE_LANGUAGE]["000036"];
  if(isset($_POST["searchoptions"]) && !empty($_POST["searchoptions"]))
    $_jt6tQ = $resourcestrings[$INTERFACE_LANGUAGE]["002002"];
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_jtICQ." - ".$_jt6tQ.$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browsercpts', 'browse_rcpts_snipped.htm');

  // hold hidden the listname
  $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QLJfI);

  // groups
  $_QLJfI = str_replace('name="GroupsDefined"', 'name="GroupsDefined" value="'.(int)$_jtJtC.'"', $_QLJfI);

  if($_jt118) {
    $_QLJfI = _L81BJ($_QLJfI, "<IF:FILTER_INACTIVE>", "</IF:FILTER_INACTIVE>", "");
    $_QLJfI = str_replace("<IF:FILTER_ACTIVE>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:FILTER_ACTIVE>", "", $_QLJfI);
  } else{
    $_QLJfI = _L81BJ($_QLJfI, "<IF:FILTER_ACTIVE>", "</IF:FILTER_ACTIVE>", "");
    $_QLJfI = str_replace("<IF:FILTER_INACTIVE>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:FILTER_INACTIVE>", "", $_QLJfI);
  }


  //
  $_jtf1L = "SELECT text, fieldname FROM $_Ij8oL WHERE language="._LRAFO($INTERFACE_LANGUAGE);
  $_QL8i1 = mysql_query($_jtf1L, $_QLttI);
  $_IOJoI = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    if($_QLO0f["fieldname"] == "u_Comments") continue; // no comments
    $_IOJoI[$_QLO0f["fieldname"]] = $_QLO0f["text"];
  }
  mysql_free_result($_QL8i1);

  // searchfor
  $_jtfLI = "";
  reset($_IOJoI);
  foreach($_IOJoI as $key => $_QltJO){
    $_jtfLI .= '<option value="SearchFor'.$key.'">'.$_QltJO.'</option>';
  }
  $_QLJfI = _L81BJ($_QLJfI, "<searchforfieldnames>", "</searchforfieldnames>", $_jtfLI);

  // sort
  $_jtfLI = "";
  reset($_IOJoI);
  foreach($_IOJoI as $key => $_QltJO){
    $_jtfLI .= '<option value="Sort'.$key.'">'.$_QltJO.'</option>';
  }
  $_QLJfI = _L81BJ($_QLJfI, "<sortforfieldnames>", "</sortforfieldnames>", $_jtfLI);

  // Groups

  $_ItlLC = "";
  $_ICQjo = 0;
  if(!isset($_POST["searchoptions"])){ // not while searching

    // unset groups when it is another mailinglist
    if(isset($_POST["ShowOnlyRecipientsInGroups"]) && isset($_POST["ShowOnlyRecipientsGroupsMailingListId"]) && $_POST["ShowOnlyRecipientsGroupsMailingListId"] != $_POST["OneMailingListId"]) {
       unset($_POST["ShowOnlyRecipientsInGroups"]);
       unset($_POST["ShowOnlyRecipientsGroups"]);
    }

    $_QL8i1 = mysql_query($_QljLL, $_QLttI);
    _L8D88($_QljLL);
    $_IC1C6 = _L81DB($_QLJfI, "<SHOW:SHOWONLYRECIPIENTSINGROUPS>", "</SHOW:SHOWONLYRECIPIENTSINGROUPS>");
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_ItlLC .= $_IC1C6;

      $_ItlLC = _L81BJ($_ItlLC, "<GroupsId>", "</GroupsId>", $_QLO0f["id"]);
      $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_QLO0f["id"]);
      $_ItlLC = _L81BJ($_ItlLC, "<GroupsName>", "</GroupsName>", $_QLO0f["Name"]);
      $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_QLO0f["Name"]);
      $_ICQjo++;
      $_ItlLC = str_replace("GroupsLabelId", 'groupchkbox_'.$_ICQjo, $_ItlLC);

      if(isset($_POST["ShowOnlyRecipientsInGroups"]) && isset($_POST["ShowOnlyRecipientsGroups"])){
         if(in_array($_QLO0f["id"], $_POST["ShowOnlyRecipientsGroups"])){
            $_ItlLC = str_replace('name="ShowOnlyRecipientsGroups[]" value="'.$_QLO0f["id"].'"', 'name="ShowOnlyRecipientsGroups[]" value="'.$_QLO0f["id"].'" checked="checked"', $_ItlLC);
         }
      }

    }

    mysql_free_result($_QL8i1);
  }

  $_Iljoj = "";
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:SHOWONLYRECIPIENTSINGROUPS>", "</SHOW:SHOWONLYRECIPIENTSINGROUPS>", $_ItlLC);
  if($_ICQjo == 0){
     $_Iljoj = "document.getElementById('ShowOnlyRecipientsInGroups').disabled = true;".$_QLl1Q;
     if(isset($_POST["ShowOnlyRecipientsInGroups"]))
       unset($_POST["ShowOnlyRecipientsInGroups"]);
  }
  // Groups /

  $_jtt1C = false;
  register_shutdown_function('shutdownShowRecipientsDone');

  $_QLJfI = _L1D0L($_I8I6o, $_QLfol, $_QlI6f, $_QljLL, $_QLJfI, $_Iljoj, $_jtj1o);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeRecipientCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "recipientedit.php");
    }
    if(!$_QLJJ6["PrivilegeImportBrowse"]) {
      $_QLoli = _JJC0E($_QLoli, "importrecipients.php");
    }
    if(!$_QLJJ6["PrivilegeExportBrowse"]) {
      $_QLoli = _JJC0E($_QLoli, "exportrecipients.php");
    }

    if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditRecipientProperties");
      $_QLoli = _JJCRD($_QLoli, "CopyRecipients");
      $_QLoli = _JJCRD($_QLoli, "MoveRecipients");
      $_QLoli = _JJCRD($_QLoli, "AddRecipientToLocalBlacklist");
      $_QLoli = _JJCRD($_QLoli, "AddRecipientToGlobalBlacklist");
      $_QLoli = _JJCRD($_QLoli, "AssignToGroups");
      $_QLoli = _JJCRD($_QLoli, "AssignToGroupsAdditionally");
      $_QLoli = _JJCRD($_QLoli, "SetSubscribedState");
      $_QLoli = _JJCRD($_QLoli, "ResetBounceState");
      $_QLoli = _JJCRD($_QLoli, "SetInactiveState");
      $_QLoli = _JJCRD($_QLoli, "ResetInactiveState");
    }

    if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteRecipient");
      $_QLoli = _JJCRD($_QLoli, "RemoveRecipients");
      $_QLoli = _JJCRD($_QLoli, "MoveRecipients");
      $_QLoli = _JJCRD($_QLoli, "AddRecipientToLocalBlacklist");
      $_QLoli = _JJCRD($_QLoli, "AddRecipientToGlobalBlacklist");
      $_QLoli = _JJCRD($_QLoli, "AssignToGroups");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  // search for recipients than we must deactivate some things
  if(isset($_POST["searchoptions"])){
     $_jttCC = array("ShowOnlyRecipientsInGroups", "ShowOnlyRecipientsGroups[]", "RcptsSearchFor", "Rcptsfieldname", "RcptsSaveFilter");
     foreach($_jttCC as $key){
       $_Iljoj .= "\r\nDisableItem('$key', false);\r\n";
     }
     $_QLJfI = _JJC0E($_QLJfI, "recipientedit.php");
     $_QLJfI = _JJC0E($_QLJfI, "importrecipients.php");
     $_QLJfI = _JJC0E($_QLJfI, "exportrecipients.php");

  } else {
   $_QLJfI = _JJC0E($_QLJfI, "./searchrecipients.php?ModifySearchParams=1");
   $_QLJfI = _JJC0E($_QLJfI, "./browsesearchrecipients_results.php");
  }

  if(!$_jtJtC){
    $_QLJfI = _JJCRD($_QLJfI, "AssignToGroups");
    $_QLJfI = _JJCRD($_QLJfI, "AssignToGroupsAdditionally");
  }

  $_QLJfI = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLJfI);

  print $_QLJfI;

  $_jtt1C = true;


  function _L1D0L($_I8I6o, $_QLfol, $_QlI6f, $_QljLL, $_QLoli, &$_Iljoj, $_jtj1o) {
    global $UserId, $_QLttI, $_I18lo, $resourcestrings, $INTERFACE_LANGUAGE, $_IfJ66, $_QljJi, $_Ij8oL, $_QLl1Q;

    $_Il0o6 = array();
    $_jtOC6 = false;
    $_jto68 = false;

    if(isset($_POST["searchoptions"]) && isset($_POST["ShowOnlyRecipientsInGroups"]))
     unset($_POST["ShowOnlyRecipientsInGroups"]);

    if(isset($_POST["searchoptions"]))
      $_Il0o6["searchoptions"] = $_POST["searchoptions"];

    $_Il0o6["MemberInGroupExistsNotExists"] = "";

    if(isset($_POST["ShowOnlyRecipientsInGroups"]) && isset($_POST["ShowOnlyRecipientsGroups"])){
      $_Il0o6["ShowOnlyRecipientsInGroups"] = $_POST["ShowOnlyRecipientsInGroups"];
      $_Il0o6["ShowOnlyRecipientsGroups"] = $_POST["ShowOnlyRecipientsGroups"];
      $_Il0o6["ShowOnlyRecipientsGroupsMailingListId"] = $_POST["OneMailingListId"];
      if(!isset($_POST["MemberInGroupExistsNotExists"]))
         $_POST["MemberInGroupExistsNotExists"] = "MemberInGroupExists";
      $_Il0o6["MemberInGroupExistsNotExists"] = $_POST["MemberInGroupExistsNotExists"];

      unset($_POST["ShowOnlyRecipientsInGroups"]);
      unset($_POST["ShowOnlyRecipientsGroups"]);
      unset($_POST["MemberInGroupExistsNotExists"]);
    }

    // Searchstring
    if( !isset($_POST["searchoptions"]) )
       $_jtj1o = "";
    if( !isset($_POST["searchoptions"]) && isset( $_POST["RcptsSearchFor"] ) && ($_POST["RcptsSearchFor"] != "") ) {
      $_Il0o6["RcptsSearchFor"] = $_POST["RcptsSearchFor"];
      $_IliOC = $_I8I6o."u_LastName";

      if( isset( $_POST["Rcptsfieldname"] ) && ($_POST["Rcptsfieldname"] != "") ) {
        $_Il0o6["Rcptsfieldname"] = $_POST["Rcptsfieldname"];
        $_I016j = substr($_POST["Rcptsfieldname"], 9);
        if($_I016j != "All") {
           if($_I016j != "x_GroupName")
             $_IliOC = $_I8I6o."."."`".$_I016j."`";
             else {
               $_IliOC = $_QljJi."."."`Name`";
               $_jtOC6 = true;
               $_jto68 = true;
             }
          }
          else {
            $_IliOC = "";
            $_Iflj0 = array();
            $_QLlO6 = array();
            _L8EOB($_I8I6o, $_Iflj0);
            for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
              if( _LRDB8("u_", $_Iflj0[$_Qli6J]) != 1 && $_Iflj0[$_Qli6J] != "id" ) continue;
              $_QLlO6[] = "("."`$_I8I6o`.`$_Iflj0[$_Qli6J]` LIKE "._LRAFO("%".trim($_POST["RcptsSearchFor"])."%").")";
            }
            $_QLlO6[] = "(".$_QljJi."."."`Name` LIKE "._LRAFO("%".trim($_POST["RcptsSearchFor"])."%").")";
            $_jtOC6 = true;
            $_jto68 = true;
          }

      }

      if($_IliOC != "")
        $_jtj1o = " ($_IliOC LIKE "._LRAFO("%".trim($_POST["RcptsSearchFor"])."%").")";
        else
        if(count($_QLlO6) > 0)
          $_jtj1o = " (".join(" OR ", $_QLlO6).")";


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
    $_jj8Ci = "";
    $_jtC6C = "";
    $_jtC81 = false;

    if( !isset($_POST["searchoptions"]) && ($_jtOC6 || isset($_Il0o6["ShowOnlyRecipientsInGroups"])) ) {
        if($_Il0o6["MemberInGroupExistsNotExists"] != "MemberInGroupNotExists"){
            $_jj8Ci = " LEFT JOIN $_IfJ66 ON $_IfJ66.Member_id=$_I8I6o.id ";
            if($_jto68)
              $_jj8Ci .= " LEFT JOIN $_QljJi ON $_QljJi.id=$_IfJ66.groups_id ";
          }
          else{
            if($_jto68)
              $_jj8Ci .= " LEFT JOIN $_IfJ66 ON $_IfJ66.Member_id=$_I8I6o.id LEFT JOIN $_QljJi ON $_QljJi.id=$_IfJ66.groups_id ";
            $_jtC6C = " (NOT EXISTS (SELECT $_IfJ66.groups_id FROM $_IfJ66 WHERE $_IfJ66.Member_id=$_I8I6o.id /**/)) ";
          }


        //$_QLlO6 = str_replace('{}', "DISTINCT {}", $_QLlO6);
        $_jtC81 = true;
      }

    if( !isset($_POST["searchoptions"]) && isset($_Il0o6["ShowOnlyRecipientsInGroups"]) ){
      $_Ql0fO = array();
      foreach($_Il0o6["ShowOnlyRecipientsGroups"] as $key => $_QltJO)
         $_Ql0fO[] = "$_IfJ66.groups_id = ".intval($_QltJO);
      $_Ql0fO = "(".join(" OR ", $_Ql0fO).")";

      if($_Il0o6["MemberInGroupExistsNotExists"] != "MemberInGroupNotExists"){
        if(!empty($_jtC6C))
          $_jtC6C = $_Ql0fO . " AND " . $_jtC6C;
          else
          $_jtC6C = $_Ql0fO;
      } else{
        $_Ql0fO = " AND " . $_Ql0fO;
        $_jtC6C = str_replace('/**/', $_Ql0fO, $_jtC6C);
      }
    }

    if(!empty($_jj8Ci))
     $_QLlO6 = str_replace('{WHERE}', $_jj8Ci." ".'{WHERE}', $_QLlO6);
    if(!empty($_jtC6C))
     $_QLlO6 = str_replace('{WHERE}', " WHERE ".$_jtC6C, $_QLlO6);
    if(!empty($_jtj1o)){
      if(strpos($_QLlO6, '{WHERE}') !== false)
        $_QLlO6 = str_replace('{WHERE}', " WHERE ".$_jtj1o, $_QLlO6);
        else
        $_QLlO6 .= " AND ".$_jtj1o;
    }

    $_QLlO6 = str_replace('{WHERE}', "", $_QLlO6);

    $_QLlO6 = str_replace('{}', "COUNT(" . ($_jtC81 ? "DISTINCT " : "") . " `$_I8I6o`.`id`)", $_QLlO6);

//print "sql1: ".$_QLlO6."<br<br>";

    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    _L8D88($_QLlO6);
    $_QLO0f = mysql_fetch_array($_QL8i1);
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

    //

    // Sort
    $_IlJj8 = " ORDER BY u_EMail";
    if( isset( $_POST["Rcptssortfieldname"] ) && ($_POST["Rcptssortfieldname"] != "") ) {
      $_Il0o6["Rcptssortfieldname"] = $_POST["Rcptssortfieldname"];
      if(strpos($_POST["Rcptssortfieldname"], "Sortu_") !== false){

         $_Iflj0 = array();
         _L8EOB($_I8I6o, $_Iflj0);
         if(in_array(substr($_POST["Rcptssortfieldname"], 4), $_Iflj0) !== false) // fieldcheck leak
           $_IlJj8 = " ORDER BY $_I8I6o.`".substr($_POST["Rcptssortfieldname"], 4) ."`";
           else
           $_IlJj8 = " ORDER BY u_EMail";
      }
      if($_POST["Rcptssortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY $_I8I6o.`id`";
      if($_POST["Rcptssortfieldname"] == "Sortbouncestatus") {
         $_IlJj8 = " ORDER BY $_I8I6o.`BounceStatus`";
         if(isset($_POST["Rcptssortorder"]) && $_POST["Rcptssortorder"] == "ascending")
           $_IlJj8 .= " ASC, ";
           else
           if(isset($_POST["Rcptssortorder"]))
              $_IlJj8 .= " DESC, ";
              else
              $_IlJj8 .= " ASC, ";
         $_IlJj8 .= " $_I8I6o.`HardbounceCount`";
      }
      if($_POST["Rcptssortfieldname"] == "SortActiveStatus")
         $_IlJj8 = " ORDER BY $_I8I6o.`IsActive`";
      if($_POST["Rcptssortfieldname"] == "SortSubscriptionStatus")
         $_IlJj8 = " ORDER BY $_I8I6o.`SubscriptionStatus`";

      if (isset($_POST["Rcptssortorder"]) ) {
         $_Il0o6["Rcptssortorder"] = $_POST["Rcptssortorder"];
         if($_POST["Rcptssortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["Rcptssortfieldname"] = "Sortu_EMail";
      $_Il0o6["Rcptssortorder"] = "ascending";
    }


    if(!empty($_jj8Ci))
     $_QLfol = str_replace('{WHERE}', $_jj8Ci." ".'{WHERE}', $_QLfol);
    if(!empty($_jtC6C))
     $_QLfol = str_replace('{WHERE}', " WHERE ".$_jtC6C, $_QLfol);
    if(!empty($_jtj1o)){
      if(strpos($_QLfol, '{WHERE}') !== false)
        $_QLfol = str_replace('{WHERE}', " WHERE ".$_jtj1o, $_QLfol);
        else
        $_QLfol .= " AND ".$_jtj1o;
    }

    $_QLfol = str_replace('{WHERE}', "", $_QLfol);

    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    // Columns
    $_jtCOQ = _JOO1L("RcptsListColumns");
    if( $_jtCOQ != "") {
      $_I016j = @unserialize($_jtCOQ);
      if($_I016j !== false)
        $_jtCOQ = $_I016j;
        else
        $_jtCOQ = array();
    } else
      $_jtCOQ = array();

    if(count($_jtCOQ) <= 1) {
       $_jtCOQ[] = "u_LastName";
       $_jtCOQ[] = "u_FirstName";
       $_jtCOQ[] = "u_EMail";
       $_jtCOQ[] = "u_Salutation";
       $_jtCOQ[] = "ActionsColumn;right";
    }
    $_jti0i = "SELECT text, fieldname FROM $_Ij8oL WHERE language="._LRAFO($INTERFACE_LANGUAGE);
    $_QL8i1 = mysql_query($_jti0i, $_QLttI);
    $_IOJoI = array();
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_IOJoI[$_QLO0f["fieldname"]] = $_QLO0f["text"];
    }
    mysql_free_result($_QL8i1);

    $_jtiJJ = array();
    $_jtL0i = array();
    $_jtLjt = array();
    for($_Qli6J=0; $_Qli6J<count($_jtCOQ); $_Qli6J++){
      $key = $_jtCOQ[$_Qli6J];
      if(!isset($_IOJoI[$key])) continue;
      $_jtiJJ[] = $key;
      $_jtL0i[] = $_IOJoI[$key];
      $_jtLjt[] = $key;
    }

    // actions column
    if(in_array("ActionsColumn;right", $_jtCOQ)) {
      $_jtl0C = _L81DB($_QLoli, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>");
      $_jtlt6 = _L81DB($_jtl0C, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>");
      $_jtl0C = _L81BJ($_jtl0C, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>", "");
      $_QLoli = _L81BJ($_QLoli, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>", "");
    } else {
       $_QLoli = str_replace ("<HEAD:ACTIONS>", "", $_QLoli);
       $_QLoli = str_replace ("</HEAD:ACTIONS>", "", $_QLoli);
    }

    $_jO06f = _L81DB($_QLoli, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>");
    $_QlIf1 = "";
    for($_Qli6J=0; $_Qli6J<count($_jtL0i); $_Qli6J++){
      $_QlIf1 .= _L81BJ($_jO06f, "<HEAD:COLUMNNAME>", "</HEAD:COLUMNNAME>", $_jtL0i[$_Qli6J]);
      $_QlIf1 = str_replace("<sortforfieldname>", $_jtLjt[$_Qli6J], $_QlIf1);
    }
    if(in_array("ActionsColumn;right", $_jtCOQ)) {
      $_QLoli = _L81BJ($_QLoli, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>", $_QlIf1.$_QLl1Q.$_jtlt6.$_QLl1Q.$_jtl0C);
    } else {
      $_QLoli = _L81BJ($_QLoli, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>", $_QlIf1);
    }
    $_QLoli = str_replace ("<HEAD:ACTIONS_SPACER>", "", $_QLoli);
    $_QLoli = str_replace ("</HEAD:ACTIONS_SPACER>", "", $_QLoli);

    // Columns /

    $_QLfol = str_replace('{}', ($_jtC81 ? "DISTINCT " : "") . "$_I8I6o.id, $_I8I6o.IsActive, $_I8I6o.SubscriptionStatus,$_I8I6o.BounceStatus, $_I8I6o.HardbounceCount, $_I8I6o.SoftbounceCount, $_I8I6o.".join(", $_I8I6o.", $_jtiJJ), $_QLfol);

//print "<br><br>sql: ".$_QLfol."<br<br>";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

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
    if(in_array("ActionsColumn;right", $_jtCOQ)) {
      $_QLoli = _L81BJ($_QLoli, "<BODY:ENTRIES>", "</BODY:ENTRIES>", $_QlIf1.$_QLl1Q.$_jO0CJ.$_QLl1Q.$_jO0O8);
    } else {
      $_QLoli = _L81BJ($_QLoli, "<BODY:ENTRIES>", "</BODY:ENTRIES>", $_jO0O8.$_QLl1Q.$_jO0CJ.$_QLl1Q.$_QlIf1);
    }


    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");

    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;

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
      $_QlLoL = 'onmouseover="showTooltip(event, \''.$_QlLoL.'\');return false;" onmouseout="hideTooltip()"';

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:MEMBERIMAGE>", "</LIST:MEMBERIMAGE>", '<img src="'.$_jjllL.'" alt="" width="16" height="16" '.$_QlLoL.' />');

      if($_QLO0f["HardbounceCount"] > 0 || $_QLO0f["BounceStatus"] == 'PermanentlyBounced') {
        $_jjllL = "images/user_bounced.gif";
        $_QlLoL = $resourcestrings[$INTERFACE_LANGUAGE]["000051"].", ".$_QLO0f["HardbounceCount"]."x";
        $_QlLoL = 'onmouseover="showTooltip(event, \''.$_QlLoL.'\');return false;" onmouseout="hideTooltip()"';
        $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:BOUNCEIMAGE>", "</LIST:BOUNCEIMAGE>", '<img src="'.$_jjllL.'" alt="" width="16" height="16" '.$_QlLoL.' />&nbsp;');
      } else
        $_Ql0fO = _L80DF($_Ql0fO, "<LIST:BOUNCEIMAGE>", "</LIST:BOUNCEIMAGE>");

      $_Ql0fO = str_replace ('name="EditRecipientProperties"', 'name="EditRecipientProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteRecipient"', 'name="DeleteRecipient" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="RecipientsIDs[]"', 'name="RecipientsIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);
    $_QLoli = str_replace ("<BODY:ACTIONS_SPACER>", "", $_QLoli);
    $_QLoli = str_replace ("</BODY:ACTIONS_SPACER>", "", $_QLoli);

    // save the filter for later use
    if( isset($_POST["RcptsSaveFilter"]) && !isset($_POST["searchoptions"]) ) {
       $_Il0o6["RcptsSaveFilter"] = $_POST["RcptsSaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseRecipientsFilter", serialize($_Il0o6) );
    }

    if(isset($_Il0o6["ShowOnlyRecipientsGroups"])){
       unset($_Il0o6["ShowOnlyRecipientsGroups"]);
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);



    return $_QLoli;
  }

  function shutdownShowRecipientsDone(){
   global $_jtt1C;

   if(!$_jtt1C && isset($_POST["RcptsSaveFilter"]) ){
      include_once("savedoptions.inc.php");
      _JOOFF("BrowseRecipientsFilter", serialize(array()) );
   }

  }

?>
