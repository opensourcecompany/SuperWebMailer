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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTemplateBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // import sample newsletter templates
  if(count($_POST) <= 1){
   include_once("defaulttexts.inc.php");
   _L61BJ();
  }

  $_Itfj8 = "";
  if (count($_POST) > 1) {

      if( isset($_POST["FilterApplyBtn"]) ) {
        // Filter
      }

    $_Ilt8t = !isset($_POST["TemplatelistActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneTemplateListAction"]) && $_POST["OneTemplateListAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneTemplateListId"]) && $_POST["OneTemplateListId"] > 0)  )
           $_Ilt8t = false;
      }
    }


    if(  !$_Ilt8t && isset($_POST["TemplatelistActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["TemplatelistActions"] == "RemoveTemplates") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeTemplateRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          for($_Qli6J=0; $_Qli6J<count($_POST["TemplateIDs"]); $_Qli6J++) {
            $_QLfol = "DELETE FROM $_Ql10t WHERE id=".intval($_POST["TemplateIDs"][$_Qli6J]);
            mysql_query($_QLfol, $_QLttI);
            if(mysql_error($_QLttI) != "")
              $_IQ0Cj[] = mysql_error($_QLttI);
            $_QLfol = "DELETE FROM $_Ql18I WHERE templates_id=".intval($_POST["TemplateIDs"][$_Qli6J]);
            mysql_query($_QLfol, $_QLttI);
          }

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000801"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000800"];
        }
        if($_POST["TemplatelistActions"] == "DuplicateTemplates") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeTemplateCreate"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          for($_Qli6J=0; $_Qli6J<count($_POST["TemplateIDs"]); $_Qli6J++) {
            $_QLfol = "SELECT * FROM $_Ql10t WHERE id=".intval($_POST["TemplateIDs"][$_Qli6J]);
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            $_QLO0f = mysql_fetch_assoc($_QL8i1);
            mysql_free_result($_QL8i1);
            unset($_QLO0f["id"]);
            $_QLfol = "INSERT INTO $_Ql10t SET ";
            $_QLlO6 = array();
            foreach($_QLO0f as $key => $_QltJO)
               $_QLlO6[] = "$key="._LRAFO($_QLO0f[$key]);
            $_QLfol .= join(",", $_QLlO6);
            mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
            $_I1OfI=mysql_fetch_row($_QL8i1);
            $_j11Lo = $_I1OfI[0];
            mysql_free_result($_QL8i1);
            $_QLfol = "UPDATE $_Ql10t SET Name="._LRAFO($_QLO0f["Name"].sprintf(" (%d)", $_j11Lo))." WHERE id=$_j11Lo";
            mysql_query($_QLfol, $_QLttI);

            $_QLfol = "INSERT INTO $_Ql18I (`templates_id`, `users_id`) SELECT $_j11Lo, `users_id` FROM $_Ql18I WHERE `templates_id`=".intval($_POST["TemplateIDs"][$_Qli6J]);
            mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
          }

        }
    }

    if( $_Ilt8t && isset($_POST["OneTemplateListAction"]) && isset($_POST["OneTemplateListId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneTemplateListAction"] == "EditTemplateProperties") {
        include_once("templateedit.php");
        exit;
      }

      if($_POST["OneTemplateListAction"] == "DeleteTemplate") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeTemplateRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

        $_QLfol = "DELETE FROM $_Ql10t WHERE id=".intval($_POST["OneTemplateListId"]);
        mysql_query($_QLfol, $_QLttI);
        $_IQ0Cj = array();
        if(mysql_error($_QLttI) != "")
          $_IQ0Cj[] = mysql_error($_QLttI);

        $_QLfol = "DELETE FROM $_Ql18I WHERE templates_id=".intval($_POST["OneTemplateListId"]);
        mysql_query($_QLfol, $_QLttI);

        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000801"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000800"];
      }
    }

  }

  // set saved values
  if (count($_POST) <= 1 || isset($_POST["EditPage"]) ) {
    include_once("savedoptions.inc.php");
    $_jo8ji = _JOO1L("BrowseTemplatesFilter");
    $_I016j = @unserialize($_jo8ji);
    if( $_jo8ji != "" && $_I016j !== false ) {
      $_POST = array_merge($_POST, $_I016j);
    }
  }

  // default SQL query
  if($OwnerUserId == 0)
    $_QLfol = "SELECT DISTINCT {} FROM $_Ql10t WHERE (1=1)";
  else
    $_QLfol = "SELECT DISTINCT {} FROM $_Ql10t LEFT JOIN $_Ql18I ON templates_id=id WHERE (`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000802"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browsetemplates', 'browse_templates_snipped.htm');

  $_QLJfI = _LQDOA($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeTemplateCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "templateedit.php");
    }
    if(!$_QLJJ6["PrivilegeTemplateEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditTemplateProperties");
      $_QLoli = _JJCRD($_QLoli, "DuplicateTemplates");
    }

    if(!$_QLJJ6["PrivilegeTemplateRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteTemplate");
      $_QLoli = _JJCRD($_QLoli, "RemoveTemplates");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  print $_QLJfI;



  function _LQDOA($_QLfol, $_QLoli) {
    global $_Ql10t, $UserId, $OwnerUserId, $INTERFACE_LANGUAGE, $resourcestrings, $_QLttI;
    $_Il0o6 = array();

    // Searchstring
    if( isset( $_POST["SearchFor"] ) && ($_POST["SearchFor"] != "") ) {
    $_Il0o6["SearchFor"] = $_POST["SearchFor"];
    $_IliOC = $_Ql10t.".`Name`";
      if( isset( $_POST["fieldname"] ) && ($_POST["fieldname"] != "") ) {
        $_Il0o6["fieldname"] = $_POST["fieldname"];
        if ($_POST["fieldname"] == "SearchForid")
          $_IliOC = $_Ql10t.".id";
        if ($_POST["fieldname"] == "SearchForName")
          $_IliOC = $_Ql10t.".`Name`";
      }

      $_QLfol .= " AND ($_IliOC LIKE "._LRAFO("%".trim($_POST["SearchFor"])."%").")";
    } else {
      $_Il0o6["SearchFor"] = "";
      $_Il0o6["fieldname"] = "SearchForName";
    }

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['PageSelected']);

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

    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneTemplateListId"] ) && ($_POST["OneTemplateListId"] == "End") )
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
    if( isset( $_POST["sortfieldname"] ) && ($_POST["sortfieldname"] != "") ) {
      $_Il0o6["sortfieldname"] = $_POST["sortfieldname"];
      if($_POST["sortfieldname"] == "SortName")
         $_IlJj8 = " ORDER BY `Name`";
      if($_POST["sortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY `id`";
      if($_POST["sortfieldname"] == "SortFormat")
         $_IlJj8 = " ORDER BY `MailFormat`";
      if (isset($_POST["sortorder"]) ) {
         $_Il0o6["sortorder"] = $_POST["sortorder"];
         if($_POST["sortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["sortfieldname"] = "SortName";
      $_Il0o6["sortorder"] = "ascending";
    }
    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', 'id, Name, IsDefault, MailFormat', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:TYPE>", "</LIST:TYPE>", $resourcestrings[$INTERFACE_LANGUAGE]["MailFormat".$_QLO0f["MailFormat"]] );

      $_Ql0fO = str_replace ('name="EditTemplateProperties"', 'name="EditTemplateProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteTemplate"', 'name="DeleteTemplate" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="TemplateIDs[]"', 'name="TemplateIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      if($_QLO0f["IsDefault"]) {
        $_Ql0fO = _L81BJ($_Ql0fO, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000803"]);
      }

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_Il0o6["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseTemplatesFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:DELETE>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:DELETE>", "", $_QLoli);

    return $_QLoli;
  }

?>
