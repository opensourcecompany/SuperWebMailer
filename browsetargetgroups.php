<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
    if(!$_QJojf["PrivilegeTargetGroupsBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  if (count($_POST) != 0) {


    $_I680t = !isset($_POST["TargetGroupActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneTargetGroupAction"]) && $_POST["OneTargetGroupAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneTargetGroupId"]) && $_POST["OneTargetGroupId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["TargetGroupActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["TargetGroupActions"] == "RemoveTargetGroups") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeTargetGroupsRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_QtIiC = array();
          _OJAR0($_POST["TargetGroupsIDs"], $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001512"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001511"];
        }

    }


    if( isset($_POST["OneTargetGroupAction"]) && isset($_POST["OneTargetGroupId"]) ) {
      // hier die Einzelaktionen

      if($_POST["OneTargetGroupAction"] == "NewTargetGroup") {
        include_once("targetgroupedit.php");
        exit;
      }

      if($_POST["OneTargetGroupAction"] == "EditTargetGroupProperties") {
        include_once("targetgroupedit.php");
        exit;
      }

      if($_POST["OneTargetGroupAction"] == "DeleteTargetGroup") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeTargetGroupsRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_QtIiC = array();
          _OJAR0(intval($_POST["OneTargetGroupId"]), $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001512"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001511"];
      }
    }


  }

  // default SQL query
  $_QJlJ0 = "SELECT {} FROM `$_Q6C0i`";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001510"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'targetgroupsedit', 'browse_targetgroups.htm');


  $_QJCJi = _OJALL($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0 && strpos($_QJCJi, '<div class="PageContainer">') !== false ) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">') );
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeTargetGroupsCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "newtargetgroup.php");
    }
    if(!$_QJojf["PrivilegeTargetGroupsEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditTargetGroupProperties");
    }

    if(!$_QJojf["PrivilegeTargetGroupsRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteTargetGroup");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveTargetGroups");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  } else {

    $_Q6ICj = $_QJCJi;
    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeTargetGroupsCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "newtargetgroup.php");
    }
    if(!$_QJojf["PrivilegeTargetGroupsEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditTargetGroupProperties");
    }

    if(!$_QJojf["PrivilegeTargetGroupsRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteTargetGroup");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveTargetGroups");
    }

    $_QJCJi = $_Q6ICj;
  }

  print $_QJCJi;
  exit;


  function _OJALL($_QJlJ0, $_Q6ICj) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_Q61I1;
    $_I61Cl = array();

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_QllO8 = intval($_POST["ItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["ItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['TargetGroupsPageSelected'])) || ($_POST['TargetGroupsPageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['TargetGroupsPageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', 'COUNT(id)', $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "End") )
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

    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', 'id, `Name`, IsDefault', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      if(!$_Q6Q1C["IsDefault"])
        $_Q66jQ = str_replace ('name="TargetGroupsIDs[]"', 'name="TargetGroupsIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
        else
        $_Q66jQ = _LJ6B1($_Q66jQ, "TargetGroupsIDs[]");
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);

      $_Q66jQ = str_replace ('name="EditTargetGroupProperties"', 'name="EditTargetGroupProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteTargetGroup"', 'name="DeleteTargetGroup" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      if($_Q6Q1C["IsDefault"]) {
        $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000031"]);
      }

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    $_Q6ICj = str_replace ("<CAN:DELETE>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</CAN:DELETE>", "", $_Q6ICj);

    return $_Q6ICj;
  }

  function _OJAR0($_IlltC, &$_QtIiC) {
    global $_Q6C0i, $_Q61I1;
    $_QfC8t = array();
    if(is_array($_IlltC))
      $_QfC8t = array_merge($_QfC8t, $_IlltC);
      else
      $_QfC8t[] = $_IlltC;
    for($_Q6llo=0; $_Q6llo<count($_QfC8t); $_Q6llo++) {
      $_QJlJ0 = "DELETE FROM `$_Q6C0i` WHERE id=".intval($_QfC8t[$_Q6llo]);
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "")
         $_QtIiC[] = mysql_error($_Q61I1);
    }
  }

?>
