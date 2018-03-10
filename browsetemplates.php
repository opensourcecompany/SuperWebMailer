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
    if(!$_QJojf["PrivilegeTemplateBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  // import sample newsletter templates
  if(count($_POST) == 0){
   include_once("defaulttexts.inc.php");
   _O8QO8();
  }

  $_I0600 = "";
  if (count($_POST) != 0) {

      if( isset($_POST["FilterApplyBtn"]) ) {
        // Filter
      }

    $_I680t = !isset($_POST["TemplatelistActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneTemplateListAction"]) && $_POST["OneTemplateListAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneTemplateListId"]) && $_POST["OneTemplateListId"] != "")  )
           $_I680t = false;
      }
    }


    if(  !$_I680t && isset($_POST["TemplatelistActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["TemplatelistActions"] == "RemoveTemplates") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeTemplateRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_QtIiC = array();
          for($_Q6llo=0; $_Q6llo<count($_POST["TemplateIDs"]); $_Q6llo++) {
            $_QJlJ0 = "DELETE FROM $_Q66li WHERE id=".intval($_POST["TemplateIDs"][$_Q6llo]);
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_error($_Q61I1) != "")
              $_QtIiC[] = mysql_error($_Q61I1);
            $_QJlJ0 = "DELETE FROM $_Q6ftI WHERE templates_id=".intval($_POST["TemplateIDs"][$_Q6llo]);
            mysql_query($_QJlJ0, $_Q61I1);
          }

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000801"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000800"];
        }
        if($_POST["TemplatelistActions"] == "DuplicateTemplates") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeTemplateCreate"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          for($_Q6llo=0; $_Q6llo<count($_POST["TemplateIDs"]); $_Q6llo++) {
            $_QJlJ0 = "SELECT * FROM $_Q66li WHERE id=".intval($_POST["TemplateIDs"][$_Q6llo]);
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
            mysql_free_result($_Q60l1);
            unset($_Q6Q1C["id"]);
            $_QJlJ0 = "INSERT INTO $_Q66li SET ";
            $_QtjtL = array();
            foreach($_Q6Q1C as $key => $_Q6ClO)
               $_QtjtL[] = "$key="._OPQLR($_Q6Q1C[$key]);
            $_QJlJ0 .= join(",", $_QtjtL);
            mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
            $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
            $_Q8OiJ=mysql_fetch_row($_Q60l1);
            $_Ifto1 = $_Q8OiJ[0];
            mysql_free_result($_Q60l1);
            $_QJlJ0 = "UPDATE $_Q66li SET Name="._OPQLR($_Q6Q1C["Name"].sprintf(" (%d)", $_Ifto1))." WHERE id=$_Ifto1";
            mysql_query($_QJlJ0, $_Q61I1);

            $_QJlJ0 = "INSERT INTO $_Q6ftI (`templates_id`, `users_id`) SELECT $_Ifto1, `users_id` FROM $_Q6ftI WHERE `templates_id`=".intval($_POST["TemplateIDs"][$_Q6llo]);
            mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
          }

        }
    }

    if( $_I680t && isset($_POST["OneTemplateListAction"]) && isset($_POST["OneTemplateListId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneTemplateListAction"] == "EditTemplateProperties") {
        include_once("templateedit.php");
        exit;
      }

      if($_POST["OneTemplateListAction"] == "DeleteTemplate") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeTemplateRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

        $_QJlJ0 = "DELETE FROM $_Q66li WHERE id=".intval($_POST["OneTemplateListId"]);
        mysql_query($_QJlJ0, $_Q61I1);
        $_QtIiC = array();
        if(mysql_error($_Q61I1) != "")
          $_QtIiC[] = mysql_error($_Q61I1);

        $_QJlJ0 = "DELETE FROM $_Q6ftI WHERE templates_id=".intval($_POST["OneTemplateListId"]);
        mysql_query($_QJlJ0, $_Q61I1);

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000801"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000800"];
      }
    }

  }

  // set saved values
  if (count($_POST) == 0 || isset($_POST["EditPage"]) ) {
    include_once("savedoptions.inc.php");
    $_j0166 = _LQB6D("BrowseTemplatesFilter");
    $_QllO8 = @unserialize($_j0166);
    if( $_j0166 != "" && $_QllO8 !== false ) {
      $_POST = array_merge($_POST, $_QllO8);
    }
  }

  // default SQL query
  if($OwnerUserId == 0)
    $_QJlJ0 = "SELECT DISTINCT {} FROM $_Q66li WHERE (1=1)";
  else
    $_QJlJ0 = "SELECT DISTINCT {} FROM $_Q66li LEFT JOIN $_Q6ftI ON templates_id=id WHERE (`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000802"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browsetemplates', 'browse_templates_snipped.htm');

  $_QJCJi = _OJAE6($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeTemplateCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "templateedit.php");
    }
    if(!$_QJojf["PrivilegeTemplateEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditTemplateProperties");
      $_Q6ICj = _LJRLJ($_Q6ICj, "DuplicateTemplates");
    }

    if(!$_QJojf["PrivilegeTemplateRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteTemplate");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveTemplates");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  print $_QJCJi;



  function _OJAE6($_QJlJ0, $_Q6ICj) {
    global $_Q66li, $UserId, $OwnerUserId, $INTERFACE_LANGUAGE, $resourcestrings, $_Q61I1;
    $_I61Cl = array();

    // Searchstring
    if( isset( $_POST["SearchFor"] ) && ($_POST["SearchFor"] != "") ) {
    $_I61Cl["SearchFor"] = $_POST["SearchFor"];
    $_I6oQj = $_Q66li.".`Name`";
      if( isset( $_POST["fieldname"] ) && ($_POST["fieldname"] != "") ) {
        $_I61Cl["fieldname"] = $_POST["fieldname"];
        if ($_POST["fieldname"] == "SearchForid")
          $_I6oQj = $_Q66li.".id";
        if ($_POST["fieldname"] == "SearchForName")
          $_I6oQj = $_Q66li.".`Name`";
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
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "End") )
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
         $_I6jfj = " ORDER BY `id`";
      if($_POST["sortfieldname"] == "SortFormat")
         $_I6jfj = " ORDER BY `MailFormat`";
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

    $_QJlJ0 = str_replace('{}', 'id, Name, IsDefault, MailFormat', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:TYPE>", "</LIST:TYPE>", $resourcestrings[$INTERFACE_LANGUAGE]["MailFormat".$_Q6Q1C["MailFormat"]] );

      $_Q66jQ = str_replace ('name="EditTemplateProperties"', 'name="EditTemplateProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteTemplate"', 'name="DeleteTemplate" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="TemplateIDs[]"', 'name="TemplateIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      if($_Q6Q1C["IsDefault"]) {
        $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000803"]);
      }

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_I61Cl["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseTemplatesFilter", serialize($_I61Cl) );
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    $_Q6ICj = str_replace ("<CAN:DELETE>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</CAN:DELETE>", "", $_Q6ICj);

    return $_Q6ICj;
  }

?>
