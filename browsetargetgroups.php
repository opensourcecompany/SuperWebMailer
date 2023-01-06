<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTargetGroupsBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";
  if (count($_POST) > 1) {


    $_Ilt8t = !isset($_POST["TargetGroupActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneTargetGroupAction"]) && $_POST["OneTargetGroupAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneTargetGroupId"]) && $_POST["OneTargetGroupId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["TargetGroupActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["TargetGroupActions"] == "RemoveTargetGroups") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeTargetGroupsRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _LQCJQ($_POST["TargetGroupsIDs"], $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001512"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001511"];
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
            if(!$_QLJJ6["PrivilegeTargetGroupsRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _LQCJQ(intval($_POST["OneTargetGroupId"]), $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001512"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001511"];
      }
    }


  }

  // default SQL query
  $_QLfol = "SELECT {} FROM `$_QlfCL`";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001510"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'targetgroupsedit', 'browse_targetgroups.htm');


  $_QLJfI = _LQCQL($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0 && strpos($_QLJfI, '<div class="PageContainer">') !== false ) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">') );
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeTargetGroupsCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "newtargetgroup.php");
    }
    if(!$_QLJJ6["PrivilegeTargetGroupsEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditTargetGroupProperties");
    }

    if(!$_QLJJ6["PrivilegeTargetGroupsRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteTargetGroup");
      $_QLoli = _JJCRD($_QLoli, "RemoveTargetGroups");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  } else {

    $_QLoli = $_QLJfI;
    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeTargetGroupsCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "newtargetgroup.php");
    }
    if(!$_QLJJ6["PrivilegeTargetGroupsEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditTargetGroupProperties");
    }

    if(!$_QLJJ6["PrivilegeTargetGroupsRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteTargetGroup");
      $_QLoli = _JJCRD($_QLoli, "RemoveTargetGroups");
    }

    $_QLJfI = $_QLoli;
  }

  print $_QLJfI;
  exit;


  function _LQCQL($_QLfol, $_QLoli) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_QLttI;
    $_Il0o6 = array();

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['TargetGroupsPageSelected'])) || ($_POST['TargetGroupsPageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['TargetGroupsPageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT(id)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneTargetGroupId"] ) && ($_POST["OneTargetGroupId"] == "End") )
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
      $_Iljoj .= "  DisableItem('PageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    // Sort
    $_IlJj8 = " ORDER BY Name ASC";

    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', 'id, `Name`, IsDefault', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      if(!$_QLO0f["IsDefault"])
        $_Ql0fO = str_replace ('name="TargetGroupsIDs[]"', 'name="TargetGroupsIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
        else
        $_Ql0fO = _JJC1E($_Ql0fO, "TargetGroupsIDs[]");
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      $_Ql0fO = str_replace ('name="EditTargetGroupProperties"', 'name="EditTargetGroupProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteTargetGroup"', 'name="DeleteTargetGroup" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      if($_QLO0f["IsDefault"]) {
        $_Ql0fO = _L81BJ($_Ql0fO, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000031"]);
      }

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:DELETE>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:DELETE>", "", $_QLoli);

    return $_QLoli;
  }

  function _LQCJQ($_jo6I6, &$_IQ0Cj) {
    global $_QlfCL, $_QLttI;
    $_I0lji = array();
    if(is_array($_jo6I6))
      $_I0lji = array_merge($_I0lji, $_jo6I6);
      else
      $_I0lji[] = $_jo6I6;
    for($_Qli6J=0; $_Qli6J<count($_I0lji); $_Qli6J++) {
      $_QLfol = "DELETE FROM `$_QlfCL` WHERE id=".intval($_I0lji[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "")
         $_IQ0Cj[] = mysql_error($_QLttI);
    }
  }

?>
