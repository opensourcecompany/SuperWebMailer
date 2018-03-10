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

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMailingListBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  if (count($_POST) != 0) {
      if( isset($_POST["FilterApplyBtn"]) ) {
        // Filter
      }

    $_I680t = !isset($_POST["MailingListActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneMailingListAction"]) && $_POST["OneMailingListAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["MailingListActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["MailingListActions"] == "DeleteMailLists") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeMailingListRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000023"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000022"];
        }

        if($_POST["MailingListActions"] == "RemoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
        }

        if($_POST["MailingListActions"] == "MoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"] || !$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_IoO1t) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000038"].join("<br />", $_IoO1t);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000037"];
        }

        if($_POST["MailingListActions"] == "CopyRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_IoO1t) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000040"].join("<br />", $_IoO1t);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000039"];
        }

        if($_POST["MailingListActions"] == "DeleteGroups") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientEdit"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000093"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000092"];
        }

        if($_POST["MailingListActions"] == "DuplicateMailLists") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeMailingListCreate"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000099"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000098"];
        }
    }

    if( isset($_POST["OneMailingListAction"]) && isset($_POST["OneMailingListId"]) ) {
       // hier die Einzelaktionen
       if($_POST["OneMailingListAction"] == "EditListProperties") {
         include_once("mailinglistedit.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "ListReport") {
         include_once("mailinglistsubunsubstat.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "BrowseRecipients") {
         include_once("browsercpts.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "ShowListForms") {
         include_once("browseforms.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "DeleteList") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeMailingListRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

         include_once("mailinglist_ops.inc.php");

         // show now the list
         if(count($_QtIiC) > 0)
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000023"].join("<br />", $_QtIiC);
         else
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000022"];
       }
     }
  }

  // set saved values
  if (count($_POST) == 0 || isset($_POST["EditPage"]) ) {
    include_once("savedoptions.inc.php");
    $_IoOtl = _LQB6D("BrowseMailinglistFilter");
    $_QllO8 = @unserialize($_IoOtl);
    if( $_IoOtl != "" && $_QllO8 !== false ) {
      $_POST = array_merge($_POST, $_QllO8);
    }
  }

  // default SQL query
  $_QJlJ0 = "SELECT DISTINCT {} FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QJlJ0 .= " WHERE (users_id=$UserId)";
     else {
      $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }

  // List of MailingLists SQL query
  $_Q68ff = str_replace("{}", "id, Name", $_QJlJ0);
  $_Q68ff .= " ORDER BY Name ASC";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000016"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browsemailinglists', 'browse_mallists_snipped.htm');

  $_QJCJi = _OJ1JB($_QJlJ0, $_Q68ff, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeMailingListCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "mailinglistcreate.php");
      $_Q6ICj = _LJRLJ($_Q6ICj, "DuplicateMailLists");
    }
    if(!$_QJojf["PrivilegeMailingListEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditListProperties");
      $_Q6ICj = _LJRLJ($_Q6ICj, "DuplicateMailLists");
    }

    if(!$_QJojf["PrivilegeMailingListRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteList");
      $_Q6ICj = _LJRLJ($_Q6ICj, "DeleteMailLists");
    }
    if(!$_QJojf["PrivilegeRecipientBrowse"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "BrowseRecipients");
    }

    if(!$_QJojf["PrivilegeRecipientRemove"]) {
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "MoveRecipients");
    }
    if(!$_QJojf["PrivilegeRecipientEdit"]) {
      $_Q6ICj = _LJRLJ($_Q6ICj, "CopyRecipients");
    }
    if(!$_QJojf["PrivilegeRecipientCreate"]) {
      $_Q6ICj = _LJRLJ($_Q6ICj, "CopyRecipients");
      $_Q6ICj = _LJRLJ($_Q6ICj, "MoveRecipients");
    }
    if(!$_QJojf["PrivilegeMLSubUnsubStatBrowse"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "ListReport");
    }
    if(!$_QJojf["PrivilegeFormBrowse"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "ShowListForms");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }


  print $_QJCJi;



  function _OJ1JB($_QJlJ0, $_Q68ff, $_Q6ICj) {
    global $UserId, $_Q60QL, $_Q8f1L, $_Q6jOo, $_IQL81, $_QCLCI, $_IIl8O,
           $_IoOLJ, $_I0f8O, $_IooOQ, $_IoCtL, $_QoOft, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;
    $_I61Cl = array();

    // Searchstring
    if( isset( $_POST["SearchFor"] ) && ($_POST["SearchFor"] != "") ) {
    $_I61Cl["SearchFor"] = $_POST["SearchFor"];
    $_I6oQj = TablePrefix."maillists.`Name`";
      if( isset( $_POST["fieldname"] ) && ($_POST["fieldname"] != "") ) {
        $_I61Cl["fieldname"] = $_POST["fieldname"];
        if ($_POST["fieldname"] == "SearchForid")
          $_I6oQj = TablePrefix."maillists.id";
        if ($_POST["fieldname"] == "SearchForName")
          $_I6oQj = TablePrefix."maillists.`Name`";
        if ($_POST["fieldname"] == "SearchForDescription")
          $_I6oQj = TablePrefix."maillists.`Description`";
      }

      $_QJlJ0 .= " AND ($_I6oQj LIKE "._OPQLR("%".trim($_POST["SearchFor"])."%").")";
    } else {
      $_I61Cl["SearchFor"] = "";
      $_I61Cl["fieldname"] = "SearchForName";
    }

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_QllO8 = intval($_POST["ItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["ItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', 'COUNT(id)', $_QtjtL);
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

    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "End") )
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
      $_I6ICC .= "  DisableItem('PageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    // Sort
    $_I6jfj = " ORDER BY Name ASC";
    if( isset( $_POST["sortfieldname"] ) && ($_POST["sortfieldname"] != "") ) {
      $_I61Cl["sortfieldname"] = $_POST["sortfieldname"];
      if($_POST["sortfieldname"] == "SortName")
         $_I6jfj = " ORDER BY `Name`";
      if($_POST["sortfieldname"] == "Sortid")
         $_I6jfj = " ORDER BY id";
      if (isset($_POST["sortorder"]) ) {
         $_I61Cl["sortorder"] = $_POST["sortorder"];
         if($_POST["sortorder"] == "ascending")
           $_I6jfj .= " ASC";
           else
           $_I6jfj .= " DESC";
         }
    } else {
      $_I61Cl["sortfieldname"] = "SortName";
      $_I61Cl["sortorder"] = "ascending";
    }
    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', 'id, Name, MaillistTableName', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    $_Ioi61 = array();

    $_Ioi61[] = array(
                                   "TableName" => $_Q60QL
                                 );

    if(_OA1LL($_IQL81)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IQL81
                                 );
    }

    if(_OA1LL($_QCLCI)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QCLCI
                                 );
    }

    if(_OA1LL($_IIl8O)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IIl8O
                                 );
    }

    if(_OA1LL($_IoOLJ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoOLJ
                                 );
    }

    if(_OA1LL($_Q6jOo)) {
      $_Ioi61[] = array(
                                   "TableName" => $_Q6jOo
                                 );
    }

    if(_OA1LL($_I0f8O)) {
      $_Ioi61[] = array(
                                   "TableName" => $_I0f8O
                                 );
    }

    if(_OA1LL($_IooOQ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IooOQ
                                 );
    }

    if(_OA1LL($_IoCtL)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoCtL
                                 );
    }

    if(_OA1LL($_QoOft)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QoOft
                                 );
    }

    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);

      // RecipientsCount
      $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[MaillistTableName]";
      $_IoioQ = mysql_query($_QJlJ0, $_Q61I1);
      $_IflL6 = mysql_fetch_row($_IoioQ);
      mysql_free_result($_IoioQ);

      // referenzen vorhanden?
      $_IoL1l = 0;
      for($_Q6llo=0; $_Q6llo<count($_Ioi61); $_Q6llo++) {

        if($_Ioi61[$_Q6llo]["TableName"] == $_Q60QL){
          $_QJlJ0 = "SELECT COUNT(*) FROM `".$_Ioi61[$_Q6llo]["TableName"]."` WHERE ";
          $_QJlJ0 .= "`OnSubscribeAlsoAddToMailList`=$_Q6Q1C[id]";
          $_QJlJ0 .= " OR ";
          $_QJlJ0 .= "`OnSubscribeAlsoRemoveFromMailList`=$_Q6Q1C[id]";
          $_QJlJ0 .= " OR ";
          $_QJlJ0 .= "`OnUnsubscribeAlsoAddToMailList`=$_Q6Q1C[id]";
          $_QJlJ0 .= " OR ";
          $_QJlJ0 .= "`OnUnsubscribeAlsoRemoveFromMailList`=$_Q6Q1C[id]";
          $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          $_IO08Q = mysql_fetch_row($_ItlJl);
          $_IoL1l += $_IO08Q[0];
          mysql_free_result($_ItlJl);
          continue;
        }

        $_QJlJ0 = "SELECT COUNT(*) FROM ".$_Ioi61[$_Q6llo]["TableName"]." WHERE `maillists_id`=$_Q6Q1C[id]";
        $_ItlJl = mysql_query($_QJlJ0);
        _OAL8F($_QJlJ0);
        $_IO08Q = mysql_fetch_row($_ItlJl);
        $_IoL1l += $_IO08Q[0];
        mysql_free_result($_ItlJl);
        if($_IoL1l > 0) break;
        if($_Ioi61[$_Q6llo]["TableName"] == $_QCLCI) {
           $_QJlJ0 = "SELECT COUNT(*) FROM `".$_Ioi61[$_Q6llo]["TableName"]."` WHERE (`OnFollowUpDoneAction`=2 AND `OnFollowUpDoneCopyToMailList`=$_Q6Q1C[id]) OR (`OnFollowUpDoneAction`=3 AND `OnFollowUpDoneMoveToMailList`=$_Q6Q1C[id] ) ";
           $_ItlJl = mysql_query($_QJlJ0);
           _OAL8F($_QJlJ0);
           $_IO08Q = mysql_fetch_row($_ItlJl);
           $_IoL1l += $_IO08Q[0];
        }
        if($_IoL1l > 0) break;
      }

      // RecipientsCount active
      $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[MaillistTableName] WHERE IsActive=1 AND (SubscriptionStatus='Subscribed' OR SubscriptionStatus='OptOutConfirmationPending')";
      $_IoioQ = mysql_query($_QJlJ0, $_Q61I1);
      $_IoLQC = mysql_fetch_row($_IoioQ);
      mysql_free_result($_IoioQ);

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:TOTAL>", "</LIST:TOTAL>", $_IflL6[0]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ACTIVE>", "</LIST:ACTIVE>", $_IoLQC[0]);
      $_Q66jQ = str_replace ('name="EditListProperties"', 'name="EditListProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="ListReport"', 'name="ListReport" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="BrowseRecipients"', 'name="BrowseRecipients" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      if($_IoL1l == 0)
        $_Q66jQ = str_replace ('name="DeleteList"', 'name="DeleteList" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
        else {
         $_Q66jQ = str_replace ('src="images/delete.gif"', 'src="images/delete_disabled.gif"', $_Q66jQ);
         $_Q66jQ = str_replace ('name="DeleteList"', 'name="DeleteList" disabled="disabled"', $_Q66jQ);
        }
      $_Q66jQ = str_replace ('name="ShowListForms"', 'name="ShowListForms" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="MailingListIDs[]"', 'name="MailingListIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_I61Cl["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseMailinglistFilter", serialize($_I61Cl) );
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

/*    // Mailinglisten Liste
    $_Q60l1 = mysql_query($_Q68ff);
    $_Q6tjl = "";
    if($_Q60l1) {
      while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
        $_Q6tjl .= sprintf('<option value="%d">%s</option>'."\r\n", $_Q6Q1C["id"], $_Q6Q1C["Name"]);
      }
      mysql_free_result($_Q60l1);
    }
    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:MAILINGLISTS>", "</OPTION:MAILINGLISTS>", $_Q6tjl);*/


    return $_Q6ICj;
  }

?>
