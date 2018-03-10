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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("searchrecipients_ops.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeRecipientBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if (! isset($_POST["OneMailingListId"]) ) {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000036"];
    include_once("mailinglistselect.inc.php");
    if (!isset($_POST["OneMailingListId"]) )
       exit;
       else {
         $_I0600 = "";
         $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
       }
  }

  if(!_OCJCC($_POST["OneMailingListId"])){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  if(!isset($_I0600))
     $_I0600 = "";
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

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "AssignToGroupsAdditionally" ) {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          if(isset($_POST["Groups"]))
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000090"];
            else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000091"];
        }

        if($_POST["RecipientsActions"] == "RemoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
        }

        if($_POST["RecipientsActions"] == "MoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"] || !$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_IoO1t) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000038"].join("<br />", $_IoO1t);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000037"];
        }

        if($_POST["RecipientsActions"] == "CopyRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");

          // show now the list
          if(count($_IoO1t) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000040"].join("<br />", $_IoO1t);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000039"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToLocalBlacklist") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"] || !$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "AddRecipientToGlobalBlacklist") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"] || !$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000041"];
        }

        if($_POST["RecipientsActions"] == "ResetInactiveState") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000168"];
        }
        if($_POST["RecipientsActions"] == "SetInactiveState") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000171"];
        }
        if($_POST["RecipientsActions"] == "ResetBounceState") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000166"];
        }
        if($_POST["RecipientsActions"] == "SetSubscribedState") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("recipients_ops.inc.php");
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000170"];
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
          if(!$_QJojf["PrivilegeRecipientRemove"]) {
            $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QJCJi;
            exit;
          }
        }

        include_once("recipients_ops.inc.php");

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
      }
    }

  }

  $_IiO0I = false;
  // set saved values
  if ( (count($_POST) == 0) || (isset($_POST["MailingListSelectForm"])) || (isset($_POST["ShowMailingList"])) || ( isset($_POST["OneMailingListAction"]) && $_POST["OneMailingListAction"] == "BrowseRecipients") || isset($_POST["EditPage"])  ) {
    include_once("savedoptions.inc.php");
    $_IiOOI = _LQB6D("BrowseRecipientsFilter");

    if( $_IiOOI != "") {
      $_QllO8 = @unserialize($_IiOOI);
      if($_QllO8 !== false) {
        $_POST = array_merge($_POST, $_QllO8);
        $_IiO0I = count($_QllO8);
      }
    }
  }

  if(!$_IiO0I){
    if(isset($_POST["RcptsSaveFilter"]) || !empty($_POST["RcptsSearchFor"]) || (isset($_POST["ShowOnlyRecipientsGroups"]) && count($_POST["ShowOnlyRecipientsGroups"])) )
      $_IiO0I = true;
  }

  // get the table
  $_QJlJ0 = "SELECT MaillistTableName, Name, GroupsTableName, MailListToGroupsTableName, EditTableName FROM $_Q60QL WHERE id=".intval($_POST["OneMailingListId"]);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  $_QlQC8 = $_Q6Q1C[0];
  $_IiC6I = $_Q6Q1C[1];
  $_Q6t6j = $_Q6Q1C[2];
  $_QLI68 = $_Q6Q1C[3];
  $_Qljli = $_Q6Q1C[4];

  // default SQL query to get recipients
  $_QJlJ0 = "SELECT {} FROM $_QlQC8 {WHERE} ";
  $_IiC6j = "";

  if(isset($_POST["searchoptions"])){
     if(!empty($_POST["searchoptions"])){
       $_Iii1I = @unserialize( base64_decode($_POST["searchoptions"]) );
       $_IiC6j = _LO0QD($_Iii1I);
     } else
       unset($_POST["searchoptions"]);
  }


  // List of MailingLists SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_Q68ff .= " WHERE (users_id=$UserId)";
     else {
      $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_Q68ff .= " AND $_Q60QL.id<>$_POST[OneMailingListId] ORDER BY Name ASC";

  // List of Groups
  $_Q6tC6 = "SELECT DISTINCT id, Name FROM $_Q6t6j ORDER BY Name ASC";

  // Template
  $_IiiI0 = $resourcestrings[$INTERFACE_LANGUAGE]["000036"];
  if(isset($_POST["searchoptions"]) && !empty($_POST["searchoptions"]))
    $_IiiI0 = $resourcestrings[$INTERFACE_LANGUAGE]["002002"];
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_IiC6I." - ".$_IiiI0.$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browsercpts', 'browse_rcpts_snipped.htm');

  // hold hidden the listname
  $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QJCJi);

  if($_IiO0I) {
    $_QJCJi = _OPR6L($_QJCJi, "<IF:FILTER_INACTIVE>", "</IF:FILTER_INACTIVE>", "");
    $_QJCJi = str_replace("<IF:FILTER_ACTIVE>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:FILTER_ACTIVE>", "", $_QJCJi);
  } else{
    $_QJCJi = _OPR6L($_QJCJi, "<IF:FILTER_ACTIVE>", "</IF:FILTER_ACTIVE>", "");
    $_QJCJi = str_replace("<IF:FILTER_INACTIVE>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:FILTER_INACTIVE>", "", $_QJCJi);
  }


  //
  $_IiiJJ = "SELECT text, fieldname FROM $_Qofjo WHERE language="._OPQLR($INTERFACE_LANGUAGE);
  $_Q60l1 = mysql_query($_IiiJJ, $_Q61I1);
  $_I16jJ = array();
  while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
    if($_Q6Q1C["fieldname"] == "u_Comments") continue; // no comments
    $_I16jJ[$_Q6Q1C["fieldname"]] = $_Q6Q1C["text"];
  }
  mysql_free_result($_Q60l1);

  // searchfor
  $_IiiJO = "";
  reset($_I16jJ);
  foreach($_I16jJ as $key => $_Q6ClO){
    $_IiiJO .= '<option value="SearchFor'.$key.'">'.$_Q6ClO.'</option>';
  }
  $_QJCJi = _OPR6L($_QJCJi, "<searchforfieldnames>", "</searchforfieldnames>", $_IiiJO);

  // sort
  $_IiiJO = "";
  reset($_I16jJ);
  foreach($_I16jJ as $key => $_Q6ClO){
    $_IiiJO .= '<option value="Sort'.$key.'">'.$_Q6ClO.'</option>';
  }
  $_QJCJi = _OPR6L($_QJCJi, "<sortforfieldnames>", "</sortforfieldnames>", $_IiiJO);

  // Groups

  $_I10Cl = "";
  $_II6ft = 0;
  if(!isset($_POST["searchoptions"])){ // not while searching

    // unset groups when it is another mailinglist
    if(isset($_POST["ShowOnlyRecipientsInGroups"]) && isset($_POST["ShowOnlyRecipientsGroupsMailingListId"]) && $_POST["ShowOnlyRecipientsGroupsMailingListId"] != $_POST["OneMailingListId"]) {
       unset($_POST["ShowOnlyRecipientsInGroups"]);
       unset($_POST["ShowOnlyRecipientsGroups"]);
    }

    $_Q60l1 = mysql_query($_Q6tC6, $_Q61I1);
    _OAL8F($_Q6tC6);
    $_IIJi1 = _OP81D($_QJCJi, "<SHOW:SHOWONLYRECIPIENTSINGROUPS>", "</SHOW:SHOWONLYRECIPIENTSINGROUPS>");
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_I10Cl .= $_IIJi1;

      $_I10Cl = _OPR6L($_I10Cl, "<GroupsId>", "</GroupsId>", $_Q6Q1C["id"]);
      $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_Q6Q1C["id"]);
      $_I10Cl = _OPR6L($_I10Cl, "<GroupsName>", "</GroupsName>", $_Q6Q1C["Name"]);
      $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_Q6Q1C["Name"]);
      $_II6ft++;
      $_I10Cl = str_replace("GroupsLabelId", 'groupchkbox_'.$_II6ft, $_I10Cl);

      if(isset($_POST["ShowOnlyRecipientsInGroups"]) && isset($_POST["ShowOnlyRecipientsGroups"])){
         if(in_array($_Q6Q1C["id"], $_POST["ShowOnlyRecipientsGroups"])){
            $_I10Cl = str_replace('name="ShowOnlyRecipientsGroups[]" value="'.$_Q6Q1C["id"].'"', 'name="ShowOnlyRecipientsGroups[]" value="'.$_Q6Q1C["id"].'" checked="checked"', $_I10Cl);
         }
      }

    }

    mysql_free_result($_Q60l1);
  }

  $_I6ICC = "";
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:SHOWONLYRECIPIENTSINGROUPS>", "</SHOW:SHOWONLYRECIPIENTSINGROUPS>", $_I10Cl);
  if($_II6ft == 0){
     $_I6ICC = "document.getElementById('ShowOnlyRecipientsInGroups').disabled = true;".$_Q6JJJ;
     if(isset($_POST["ShowOnlyRecipientsInGroups"]))
       unset($_POST["ShowOnlyRecipientsInGroups"]);
  }
  // Groups /

  $_IiLl1 = false;
  register_shutdown_function('shutdownShowRecipientsDone');

  $_QJCJi = _OLDOF($_QlQC8, $_QJlJ0, $_Q68ff, $_Q6tC6, $_QJCJi, $_I6ICC, $_IiC6j);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeRecipientCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "recipientedit.php");
    }
    if(!$_QJojf["PrivilegeImportBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "importrecipients.php");
    }
    if(!$_QJojf["PrivilegeExportBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "exportrecipients.php");
    }

    if(!$_QJojf["PrivilegeRecipientEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditRecipientProperties");
      $_Q6ICj = _LJRLJ($_Q6ICj, "CopyRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "MoveRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AddRecipientToLocalBlacklist");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AddRecipientToGlobalBlacklist");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AssignToGroups");
      $_Q6ICj = _LJRLJ($_Q6ICj, "SetSubscribedState");
      $_Q6ICj = _LJRLJ($_Q6ICj, "ResetBounceState");
      $_Q6ICj = _LJRLJ($_Q6ICj, "SetInactiveState");
      $_Q6ICj = _LJRLJ($_Q6ICj, "ResetInactiveState");
    }

    if(!$_QJojf["PrivilegeRecipientRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteRecipient");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "MoveRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AddRecipientToLocalBlacklist");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AddRecipientToGlobalBlacklist");
      $_Q6ICj = _LJRLJ($_Q6ICj, "AssignToGroups");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  // search for recipients than we must deactivate some things
  if(isset($_POST["searchoptions"])){
     $_IilLt = array("ShowOnlyRecipientsInGroups", "ShowOnlyRecipientsGroups[]", "RcptsSearchFor", "Rcptsfieldname", "RcptsSaveFilter");
     foreach($_IilLt as $key){
       $_I6ICC .= "\r\nDisableItem('$key', false);\r\n";
     }
     $_QJCJi = _LJ6RJ($_QJCJi, "recipientedit.php");
     $_QJCJi = _LJ6RJ($_QJCJi, "importrecipients.php");
     $_QJCJi = _LJ6RJ($_QJCJi, "exportrecipients.php");

  } else {
   $_QJCJi = _LJ6RJ($_QJCJi, "./searchrecipients.php?ModifySearchParams=1");
   $_QJCJi = _LJ6RJ($_QJCJi, "./browsesearchrecipients_results.php");
  }

  $_QJCJi = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_QJCJi);

  print $_QJCJi;

  $_IiLl1 = true;


  function _OLDOF($_QlQC8, $_QJlJ0, $_Q68ff, $_Q6tC6, $_Q6ICj, &$_I6ICC, $_IiC6j) {
    global $UserId, $_Q61I1, $_Q8f1L, $resourcestrings, $INTERFACE_LANGUAGE, $_QLI68, $_Q6t6j, $_Qofjo, $_Q6JJJ;

    $_I61Cl = array();
    $_IL0I6 = false;
    $_IL0JJ = false;

    if(isset($_POST["searchoptions"]) && isset($_POST["ShowOnlyRecipientsInGroups"]))
     unset($_POST["ShowOnlyRecipientsInGroups"]);

    if(isset($_POST["searchoptions"]))
      $_I61Cl["searchoptions"] = $_POST["searchoptions"];

    $_I61Cl["MemberInGroupExistsNotExists"] = "";

    if(isset($_POST["ShowOnlyRecipientsInGroups"]) && isset($_POST["ShowOnlyRecipientsGroups"])){
      $_I61Cl["ShowOnlyRecipientsInGroups"] = $_POST["ShowOnlyRecipientsInGroups"];
      $_I61Cl["ShowOnlyRecipientsGroups"] = $_POST["ShowOnlyRecipientsGroups"];
      $_I61Cl["ShowOnlyRecipientsGroupsMailingListId"] = $_POST["OneMailingListId"];
      if(!isset($_POST["MemberInGroupExistsNotExists"]))
         $_POST["MemberInGroupExistsNotExists"] = "MemberInGroupExists";
      $_I61Cl["MemberInGroupExistsNotExists"] = $_POST["MemberInGroupExistsNotExists"];

      unset($_POST["ShowOnlyRecipientsInGroups"]);
      unset($_POST["ShowOnlyRecipientsGroups"]);
      unset($_POST["MemberInGroupExistsNotExists"]);
    }

    // Searchstring
    if( !isset($_POST["searchoptions"]) )
       $_IiC6j = "";
    if( !isset($_POST["searchoptions"]) && isset( $_POST["RcptsSearchFor"] ) && ($_POST["RcptsSearchFor"] != "") ) {
      $_I61Cl["RcptsSearchFor"] = $_POST["RcptsSearchFor"];
      $_I6oQj = $_QlQC8."u_LastName";

      if( isset( $_POST["Rcptsfieldname"] ) && ($_POST["Rcptsfieldname"] != "") ) {
        $_I61Cl["Rcptsfieldname"] = $_POST["Rcptsfieldname"];
        $_QllO8 = substr($_POST["Rcptsfieldname"], 9);
        if($_QllO8 != "All") {
           if($_QllO8 != "x_GroupName")
             $_I6oQj = $_QlQC8."."."`".$_QllO8."`";
             else {
               $_I6oQj = $_Q6t6j."."."`Name`";
               $_IL0I6 = true;
               $_IL0JJ = true;
             }
          }
          else {
            $_I6oQj = "";
            $_QLLjo = array();
            $_QtjtL = array();
            _OAJL1($_QlQC8, $_QLLjo);
            for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
              if( _OPLFQ("u_", $_QLLjo[$_Q6llo]) != 1 && $_QLLjo[$_Q6llo] != "id" ) continue;
              $_QtjtL[] = "("."`$_QlQC8`.`$_QLLjo[$_Q6llo]` LIKE "._OPQLR("%".trim($_POST["RcptsSearchFor"])."%").")";
            }
            $_QtjtL[] = "(".$_Q6t6j."."."`Name` LIKE "._OPQLR("%".trim($_POST["RcptsSearchFor"])."%").")";
            $_IL0I6 = true;
            $_IL0JJ = true;
          }

      }

      if($_I6oQj != "")
        $_IiC6j = " ($_I6oQj LIKE "._OPQLR("%".trim($_POST["RcptsSearchFor"])."%").")";
        else
        if(count($_QtjtL) > 0)
          $_IiC6j = " (".join(" OR ", $_QtjtL).")";


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
    $_IO1Oj = "";
    $_IL1Jo = "";

    if( !isset($_POST["searchoptions"]) && ($_IL0I6 || isset($_I61Cl["ShowOnlyRecipientsInGroups"])) ) {
        if($_I61Cl["MemberInGroupExistsNotExists"] != "MemberInGroupNotExists"){
            $_IO1Oj = " LEFT JOIN $_QLI68 ON $_QLI68.Member_id=$_QlQC8.id ";
            if($_IL0JJ)
              $_IO1Oj .= " LEFT JOIN $_Q6t6j ON $_Q6t6j.id=$_QLI68.groups_id ";
          }
          else{
            if($_IL0JJ)
              $_IO1Oj .= " LEFT JOIN $_QLI68 ON $_QLI68.Member_id=$_QlQC8.id LEFT JOIN $_Q6t6j ON $_Q6t6j.id=$_QLI68.groups_id ";
            $_IL1Jo = " (NOT EXISTS (SELECT $_QLI68.groups_id FROM $_QLI68 WHERE $_QLI68.Member_id=$_QlQC8.id /**/)) ";
          }


        $_QtjtL = str_replace('{}', "DISTINCT {}", $_QtjtL);
      }

    if( !isset($_POST["searchoptions"]) && isset($_I61Cl["ShowOnlyRecipientsInGroups"]) ){
      $_Q66jQ = array();
      foreach($_I61Cl["ShowOnlyRecipientsGroups"] as $key => $_Q6ClO)
         $_Q66jQ[] = "$_QLI68.groups_id = ".intval($_Q6ClO);
      $_Q66jQ = "(".join(" OR ", $_Q66jQ).")";

      if($_I61Cl["MemberInGroupExistsNotExists"] != "MemberInGroupNotExists"){
        if(!empty($_IL1Jo))
          $_IL1Jo = $_Q66jQ . " AND " . $_IL1Jo;
          else
          $_IL1Jo = $_Q66jQ;
      } else{
        $_Q66jQ = " AND " . $_Q66jQ;
        $_IL1Jo = str_replace('/**/', $_Q66jQ, $_IL1Jo);
      }
    }

    if(!empty($_IO1Oj))
     $_QtjtL = str_replace('{WHERE}', $_IO1Oj." ".'{WHERE}', $_QtjtL);
    if(!empty($_IL1Jo))
     $_QtjtL = str_replace('{WHERE}', " WHERE ".$_IL1Jo, $_QtjtL);
    if(!empty($_IiC6j)){
      if(strpos($_QtjtL, '{WHERE}') !== false)
        $_QtjtL = str_replace('{WHERE}', " WHERE ".$_IiC6j, $_QtjtL);
        else
        $_QtjtL .= " AND ".$_IiC6j;
    }

    $_QtjtL = str_replace('{WHERE}', "", $_QtjtL);

    $_QtjtL = str_replace('{}', "COUNT(`$_QlQC8`.`id`)", $_QtjtL);

//print "sql1: ".$_QtjtL."<br<br>";

    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    _OAL8F($_QtjtL);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
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

    //

    // Sort
    $_I6jfj = " ORDER BY u_EMail";
    if( isset( $_POST["Rcptssortfieldname"] ) && ($_POST["Rcptssortfieldname"] != "") ) {
      $_I61Cl["Rcptssortfieldname"] = $_POST["Rcptssortfieldname"];
      if(strpos($_POST["Rcptssortfieldname"], "Sortu_") !== false){

         $_QLLjo = array();
         _OAJL1($_QlQC8, $_QLLjo);
         if(in_array(substr($_POST["Rcptssortfieldname"], 4), $_QLLjo) !== false) // fieldcheck leak
           $_I6jfj = " ORDER BY $_QlQC8.`".substr($_POST["Rcptssortfieldname"], 4) ."`";
           else
           $_I6jfj = " ORDER BY u_EMail";
      }
      if($_POST["Rcptssortfieldname"] == "Sortid")
         $_I6jfj = " ORDER BY $_QlQC8.`id`";
      if($_POST["Rcptssortfieldname"] == "Sortbouncestatus") {
         $_I6jfj = " ORDER BY $_QlQC8.`BounceStatus`";
         if(isset($_POST["Rcptssortorder"]) && $_POST["Rcptssortorder"] == "ascending")
           $_I6jfj .= " ASC, ";
           else
           if(isset($_POST["Rcptssortorder"]))
              $_I6jfj .= " DESC, ";
              else
              $_I6jfj .= " ASC, ";
         $_I6jfj .= " $_QlQC8.`HardbounceCount`";
      }
      if($_POST["Rcptssortfieldname"] == "SortActiveStatus")
         $_I6jfj = " ORDER BY $_QlQC8.`IsActive`";
      if($_POST["Rcptssortfieldname"] == "SortSubscriptionStatus")
         $_I6jfj = " ORDER BY $_QlQC8.`SubscriptionStatus`";

      if (isset($_POST["Rcptssortorder"]) ) {
         $_I61Cl["Rcptssortorder"] = $_POST["Rcptssortorder"];
         if($_POST["Rcptssortorder"] == "ascending")
           $_I6jfj .= " ASC";
           else
           $_I6jfj .= " DESC";
         }
    } else {
      $_I61Cl["Rcptssortfieldname"] = "Sortu_EMail";
      $_I61Cl["Rcptssortorder"] = "ascending";
    }


    if(!empty($_IO1Oj))
     $_QJlJ0 = str_replace('{WHERE}', $_IO1Oj." ".'{WHERE}', $_QJlJ0);
    if(!empty($_IL1Jo))
     $_QJlJ0 = str_replace('{WHERE}', " WHERE ".$_IL1Jo, $_QJlJ0);
    if(!empty($_IiC6j)){
      if(strpos($_QJlJ0, '{WHERE}') !== false)
        $_QJlJ0 = str_replace('{WHERE}', " WHERE ".$_IiC6j, $_QJlJ0);
        else
        $_QJlJ0 .= " AND ".$_IiC6j;
    }

    $_QJlJ0 = str_replace('{WHERE}', "", $_QJlJ0);

    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    // Columns
    $_IL1fi = _LQB6D("RcptsListColumns");
    if( $_IL1fi != "") {
      $_QllO8 = @unserialize($_IL1fi);
      if($_QllO8 !== false)
        $_IL1fi = $_QllO8;
        else
        $_IL1fi = array();
    } else
      $_IL1fi = array();

    if(count($_IL1fi) <= 1) {
       $_IL1fi[] = "u_LastName";
       $_IL1fi[] = "u_FirstName";
       $_IL1fi[] = "u_EMail";
       $_IL1fi[] = "u_Salutation";
       $_IL1fi[] = "ActionsColumn;right";
    }
    $_ILQj1 = "SELECT text, fieldname FROM $_Qofjo WHERE language="._OPQLR($INTERFACE_LANGUAGE);
    $_Q60l1 = mysql_query($_ILQj1, $_Q61I1);
    $_I16jJ = array();
    while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
      $_I16jJ[$_Q6Q1C["fieldname"]] = $_Q6Q1C["text"];
    }
    mysql_free_result($_Q60l1);

    $_ILQL0 = array();
    $_ILIiJ = array();
    $_ILjQI = array();
    for($_Q6llo=0; $_Q6llo<count($_IL1fi); $_Q6llo++){
      $key = $_IL1fi[$_Q6llo];
      if(!isset($_I16jJ[$key])) continue;
      $_ILQL0[] = $key;
      $_ILIiJ[] = $_I16jJ[$key];
      $_ILjQI[] = $key;
    }

    // actions column
    if(in_array("ActionsColumn;right", $_IL1fi)) {
      $_ILjtO = _OP81D($_Q6ICj, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>");
      $_ILJ1Q = _OP81D($_ILjtO, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>");
      $_ILjtO = _OPR6L($_ILjtO, "<HEAD:ACTIONS_SPACER>", "</HEAD:ACTIONS_SPACER>", "");
      $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:ACTIONS>", "</HEAD:ACTIONS>", "");
    } else {
       $_Q6ICj = str_replace ("<HEAD:ACTIONS>", "", $_Q6ICj);
       $_Q6ICj = str_replace ("</HEAD:ACTIONS>", "", $_Q6ICj);
    }

    $_ILJlo = _OP81D($_Q6ICj, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>");
    $_Q6tjl = "";
    for($_Q6llo=0; $_Q6llo<count($_ILIiJ); $_Q6llo++){
      $_Q6tjl .= _OPR6L($_ILJlo, "<HEAD:COLUMNNAME>", "</HEAD:COLUMNNAME>", $_ILIiJ[$_Q6llo]);
      $_Q6tjl = str_replace("<sortforfieldname>", $_ILjQI[$_Q6llo], $_Q6tjl);
    }
    if(in_array("ActionsColumn;right", $_IL1fi)) {
      $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>", $_Q6tjl.$_Q6JJJ.$_ILJ1Q.$_Q6JJJ.$_ILjtO);
    } else {
      $_Q6ICj = _OPR6L($_Q6ICj, "<HEAD:COLUMNDESCRIPTION>", "</HEAD:COLUMNDESCRIPTION>", $_Q6tjl);
    }
    $_Q6ICj = str_replace ("<HEAD:ACTIONS_SPACER>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</HEAD:ACTIONS_SPACER>", "", $_Q6ICj);

    // Columns /

    $_QJlJ0 = str_replace('{}', "$_QlQC8.id, $_QlQC8.IsActive, $_QlQC8.SubscriptionStatus,$_QlQC8.BounceStatus, $_QlQC8.HardbounceCount, $_QlQC8.SoftbounceCount, $_QlQC8.".join(", $_QlQC8.", $_ILQL0), $_QJlJ0);

//print "<br><br>sql: ".$_QJlJ0."<br<br>";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

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
    if(in_array("ActionsColumn;right", $_IL1fi)) {
      $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ENTRIES>", "</BODY:ENTRIES>", $_Q6tjl.$_Q6JJJ.$_ILf1t.$_Q6JJJ.$_IL66t);
    } else {
      $_Q6ICj = _OPR6L($_Q6ICj, "<BODY:ENTRIES>", "</BODY:ENTRIES>", $_IL66t.$_Q6JJJ.$_ILf1t.$_Q6JJJ.$_Q6tjl);
    }


    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");

    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_Q66jQ = $_IIJi1;

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
      $_Qf186 = 'onmouseover="showTooltip(event, \''.$_Qf186.'\');return false;" onmouseout="hideTooltip()"';

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:MEMBERIMAGE>", "</LIST:MEMBERIMAGE>", '<img src="'.$_IOOit.'" alt="" width="16" height="16" '.$_Qf186.' />');

      if($_Q6Q1C["HardbounceCount"] > 0 || $_Q6Q1C["BounceStatus"] == 'PermanentlyBounced') {
        $_IOOit = "images/user_bounced.gif";
        $_Qf186 = $resourcestrings[$INTERFACE_LANGUAGE]["000051"].", ".$_Q6Q1C["HardbounceCount"]."x";
        $_Qf186 = 'onmouseover="showTooltip(event, \''.$_Qf186.'\');return false;" onmouseout="hideTooltip()"';
        $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:BOUNCEIMAGE>", "</LIST:BOUNCEIMAGE>", '<img src="'.$_IOOit.'" alt="" width="16" height="16" '.$_Qf186.' />&nbsp;');
      } else
        $_Q66jQ = _OP6PQ($_Q66jQ, "<LIST:BOUNCEIMAGE>", "</LIST:BOUNCEIMAGE>");

      $_Q66jQ = str_replace ('name="EditRecipientProperties"', 'name="EditRecipientProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteRecipient"', 'name="DeleteRecipient" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="RecipientsIDs[]"', 'name="RecipientsIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);
    $_Q6ICj = str_replace ("<BODY:ACTIONS_SPACER>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</BODY:ACTIONS_SPACER>", "", $_Q6ICj);

    // save the filter for later use
    if( isset($_POST["RcptsSaveFilter"]) && !isset($_POST["searchoptions"]) ) {
       $_I61Cl["RcptsSaveFilter"] = $_POST["RcptsSaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseRecipientsFilter", serialize($_I61Cl) );
    }

    if(isset($_I61Cl["ShowOnlyRecipientsGroups"])){
       unset($_I61Cl["ShowOnlyRecipientsGroups"]);
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);



    return $_Q6ICj;
  }

  function shutdownShowRecipientsDone(){
   global $_IiLl1;

   if(!$_IiLl1 && isset($_POST["RcptsSaveFilter"]) ){
      include_once("savedoptions.inc.php");
      _LQC66("BrowseRecipientsFilter", serialize(array()) );
   }

  }

?>
