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
  include_once("defaulttexts.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeFunctionBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];

  if(isset($_GET["IsFCKEditor"]))
    $_POST["_IsFCKEditor"] = $_GET["IsFCKEditor"];

  if (count($_POST) == 0)
    _O81DC();

  $_I0600 = "";
  if (count($_POST) != 0) {


    $_I680t = !isset($_POST["FunctionActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneFunctionAction"]) && $_POST["OneFunctionAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneFunctionId"]) && $_POST["OneFunctionId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["FunctionActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["FunctionActions"] == "RemoveFunctions") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeFunctionRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_QtIiC = array();
          _OLBDQ($_POST["FunctionIDs"], $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000112"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000111"];
        }

    }


    if( isset($_POST["OneFunctionAction"]) && isset($_POST["OneFunctionId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneFunctionAction"] == "EditFunctionProperties") {
        include_once("browsefunctionparams.php");
        exit;
      }

      if($_POST["OneFunctionAction"] == "DeleteFunction") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeFunctionRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_QtIiC = array();
          _OLBDQ($_POST["OneFunctionId"], $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000112"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000111"];
      }
    }


  }

  // default SQL query
  $_QJlJ0 = "SELECT {} FROM $_I88i8";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000110"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'functionedit', 'browse_functions.htm');


  $_QJCJi = _OLBPQ($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0 && strpos($_QJCJi, '<div class="PageContainer">') !== false ) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">') );
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeFunctionCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browsefunctionparams.php");
    }
    if(!$_QJojf["PrivilegeFunctionEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditFunctionProperties");
    }

    if(!$_QJojf["PrivilegeFunctionRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteFunction");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveFunctions");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  } else {

    $_Q6ICj = $_QJCJi;
    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeFunctionCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browsefunctionparams.php");
    }
    if(!$_QJojf["PrivilegeFunctionEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditFunctionProperties");
    }

    if(!$_QJojf["PrivilegeFunctionRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteFunction");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveFunctions");
    }

    $_QJCJi = $_Q6ICj;
  }

  if(isset($_POST["_FORMNAME"]) && $_POST["_FORMNAME"] != "null" && $_POST["_FORMNAME"] != "")
    $_QJCJi = str_replace("'FORMNAME'", "'".$_POST["_FORMNAME"]."'", $_QJCJi);
    else
      $_QJCJi = _OP6PQ($_QJCJi, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if(isset($_POST["_FORMFIELD"]) && $_POST["_FORMFIELD"] != "null" && $_POST["_FORMFIELD"] != "") {
      $_QJCJi = str_replace('.FORMFIELD', ".".$_POST["_FORMFIELD"], $_QJCJi);
      $_QJCJi = str_replace('<HAS_SOURCEELEMENT>', '', $_QJCJi);
      $_QJCJi = str_replace('</HAS_SOURCEELEMENT>', '', $_QJCJi);
    }
    else
    $_QJCJi = _OP6PQ($_QJCJi, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if (!isset($_POST["_IsFCKEditor"]) || $_POST["_IsFCKEditor"] == "false" ) {
     $_QJCJi = str_replace('<ISNOTFCKEDITOR>', '', $_QJCJi);
     $_QJCJi = str_replace('</ISNOTFCKEDITOR>', '', $_QJCJi);
     $_QJCJi = _OP6PQ($_QJCJi, "<ISFCKEDITOR>", "</ISFCKEDITOR>");
   }
    else {
      $_QJCJi = _OP6PQ($_QJCJi, "<ISNOTFCKEDITOR>", "</ISNOTFCKEDITOR>");
      $_QJCJi = str_replace('<ISFCKEDITOR>', '', $_QJCJi);
      $_QJCJi = str_replace('</ISFCKEDITOR>', '', $_QJCJi);
    }

  if(isset($_POST["_FORMFIELD"])) {
    $_QJCJi = str_replace('"SourceCKEditor"', '"'.$_POST["_FORMFIELD"].'"', $_QJCJi);
  }

  print $_QJCJi;
  exit;


  function _OLBPQ($_QJlJ0, $_Q6ICj) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_Q61I1;
    $_I61Cl = array();

    if(isset($_POST["_FORMNAME"]))
      $_I61Cl["_FORMNAME"] = $_POST["_FORMNAME"];

    if(isset($_POST["_FORMFIELD"]))
      $_I61Cl["_FORMFIELD"] = $_POST["_FORMFIELD"];

    if(isset($_POST["_IsFCKEditor"]))
      $_I61Cl["_IsFCKEditor"] = $_POST["_IsFCKEditor"];

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_QllO8 = intval($_POST["ItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["ItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['FunctionsPageSelected'])) || (intval($_POST['FunctionsPageSelected']) == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['FunctionsPageSelected']);

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

    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "End") )
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

    $_QJlJ0 = str_replace('{}', 'id, Name, IsDefault', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      if(!$_Q6Q1C["IsDefault"])
        $_Q66jQ = str_replace ('name="FunctionIDs[]"', 'name="FunctionIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
        else
        $_Q66jQ = _LJ6B1($_Q66jQ, "FunctionIDs[]");
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);

      $_Q66jQ = str_replace ('name="EditFunctionProperties"', 'name="EditFunctionProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteFunction"', 'name="DeleteFunction" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="ApplyFunction"', 'name="ApplyFunction" value="['.$_Q6Q1C["Name"].']"', $_Q66jQ);

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

  function _OLBDQ($_ItQ00, &$_QtIiC) {
    global $_I88i8, $_Q61I1;
    $_QfC8t = array();
    if(is_array($_ItQ00))
      $_QfC8t = array_merge($_QfC8t, $_ItQ00);
      else
      $_QfC8t[] = $_ItQ00;
    for($_Q6llo=0; $_Q6llo<count($_QfC8t); $_Q6llo++) {
      $_QJlJ0 = "DELETE FROM $_I88i8 WHERE id=".intval($_QfC8t[$_Q6llo]);
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "")
         $_QtIiC[] = mysql_error($_Q61I1);
    }
  }

?>
