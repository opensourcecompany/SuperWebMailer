<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright � 2007 - 2014 Mirko Boeer                         #
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
    if(!$_QJojf["PrivilegeAutoImportBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  if (count($_POST) != 0) {
    if( isset($_POST["OneAutoImportListAction"]) && isset($_POST["OneAutoImportListId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneAutoImportListAction"] == "EditAutoImportProperties") {
        include_once("autoimportedit.php");
        exit;
      }

      if($_POST["OneAutoImportListAction"] == "DeleteAutoImport") {

        if($OwnerUserId != 0) {
          if(!$_QJojf["PrivilegeAutoImportRemove"]) {
            $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QJCJi;
            exit;
          }
        }

        include_once("removeautoimport.inc.php");

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000062"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001207"];
      }
    }

  }

  // default SQL query
  $_QJlJ0 = "SELECT DISTINCT {} FROM `$_I0f8O`";
  if($OwnerUserId != 0) {
     $_QJlJ0 .= " LEFT JOIN `$_Q6fio` ON `$_Q6fio`.`maillists_id`=`$_I0f8O`.`maillists_id`";
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001200"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browseautoimports', 'browse_autoimports_snipped.htm');

  $_QJCJi = _OLJLL($_QJlJ0, $_QJCJi);


  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeAutoImportCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "autoimportedit.php");
    }
    if(!$_QJojf["PrivilegeAutoImportEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditAutoImportProperties");
    }

    if(!$_QJojf["PrivilegeAutoImportRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteAutoImport");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveAutoImports");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  print $_QJCJi;



  function _OLJLL($_QJlJ0, $_Q6ICj) {
    global $_I0f8O, $UserId, $OwnerUserId, $_Q61I1, $INTERFACE_LANGUAGE, $resourcestrings, $_Q60QL, $_Q6fio;
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
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    if($OwnerUserId != 0){
      if(strpos($_QJlJ0, " WHERE ") === false)
        $_QJlJ0 .= " WHERE ";
      $_QJlJ0 .= "`$_Q6fio`.`users_id`=$UserId";
    }
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

    if( isset( $_POST["OneAutoImportListId"] ) && ($_POST["OneAutoImportListId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneAutoImportListId"] ) && ($_POST["OneAutoImportListId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneAutoImportListId"] ) && ($_POST["OneAutoImportListId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneAutoImportListId"] ) && ($_POST["OneAutoImportListId"] == "End") )
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

    $_QJlJ0 = str_replace('{}', "`id`, `IsActive`, `Name`, `ImportOption`, `LastImportDone`, `$_I0f8O`.maillists_id", $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    // get all table FormsTable
    $_I6jtI = array();
    $_I6jtf = "SELECT `FormsTableName` FROM `$_Q60QL`";

    $_Q8Oj8 = mysql_query($_I6jtf, $_Q61I1);
    while ($_Q8OiJ = mysql_fetch_row($_Q8Oj8)) {
      $_I6jtI[] = $_Q8OiJ[0];
    }
    mysql_free_result($_Q8Oj8);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);
      $_QJCJi = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
      if(!$_Q6Q1C["IsActive"])
        $_QJCJi = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ACTIVE>", "</LIST:ACTIVE>", $_QJCJi);
      $_I0ojO = "";
      if($_Q6Q1C["ImportOption"] == 'ImportCSV')
         $_I0ojO = $resourcestrings[$INTERFACE_LANGUAGE]["001202"];
         else
         if($_Q6Q1C["ImportOption"] == 'ImportDB')
            $_I0ojO = $resourcestrings[$INTERFACE_LANGUAGE]["001203"];
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:IMPORTOPTION>", "</LIST:IMPORTOPTION>", $_I0ojO);

      $_Q66jQ = str_replace ('name="EditAutoImportProperties"', 'name="EditAutoImportProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteAutoImport"', 'name="DeleteAutoImport" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      if(!$_Q6Q1C["LastImportDone"]) {
        $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["001201"]);
      }

      // not an admin, check rights for mailinglist
      if($OwnerUserId != 0) {
        if($_Q6Q1C["maillists_id"] != 0) {
          $_QJlJ0 = "SELECT COUNT(*) FROM `$_Q6fio` WHERE `maillists_id`=$_Q6Q1C[maillists_id] AND `users_id`=$UserId";
          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          $_I6JII = mysql_fetch_row($_Q8Oj8);
          if($_I6JII[0] == 0) {
              continue;
              $_Q66jQ = _LJ6B1($_Q66jQ, "EditAutoImportProperties");
              $_Q66jQ = _LJ6B1($_Q66jQ, "DeleteAutoImport");
              $_Q66jQ = _LJRLJ($_Q66jQ, "RemoveAutoImport");
          }
          mysql_free_result($_Q8Oj8);
        }
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

?>
