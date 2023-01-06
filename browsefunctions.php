<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeFunctionBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];

  if(isset($_GET["IsFCKEditor"]))
    $_POST["_IsFCKEditor"] = $_GET["IsFCKEditor"];

  if (count($_POST) <= 1)
    _L61QE();

  $_Itfj8 = "";
  if (count($_POST) > 1) {


    $_Ilt8t = !isset($_POST["FunctionActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneFunctionAction"]) && $_POST["OneFunctionAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneFunctionId"]) && $_POST["OneFunctionId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["FunctionActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["FunctionActions"] == "RemoveFunctions") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeFunctionRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _L1BDE($_POST["FunctionIDs"], $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000112"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000111"];
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
            if(!$_QLJJ6["PrivilegeFunctionRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _L1BDE($_POST["OneFunctionId"], $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000112"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000111"];
      }
    }


  }

  // set saved values
  if ( (count($_POST) <= 1) || !isset($_POST["FilterApplyBtn"]) ) {
    include_once("savedoptions.inc.php");
    $_jQLCt = _JOO1L("BrowseFunctionsFilter");

    if( $_jQLCt != "") {
      $_I016j = @unserialize($_jQLCt);
      if($_I016j !== false)
        $_POST = array_merge($_POST, $_I016j);
    }
  }

  // default SQL query
  $_QLfol = "SELECT {} FROM `$_jQ68I`";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000110"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'functionedit', 'browse_functions.htm');


  $_QLJfI = _L1BJP($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0 && strpos($_QLJfI, '<div class="PageContainer">') !== false ) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">') );
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeFunctionCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "browsefunctionparams.php");
    }
    if(!$_QLJJ6["PrivilegeFunctionEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditFunctionProperties");
    }

    if(!$_QLJJ6["PrivilegeFunctionRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteFunction");
      $_QLoli = _JJCRD($_QLoli, "RemoveFunctions");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  } else {

    $_QLoli = $_QLJfI;
    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeFunctionCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "browsefunctionparams.php");
    }
    if(!$_QLJJ6["PrivilegeFunctionEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditFunctionProperties");
    }

    if(!$_QLJJ6["PrivilegeFunctionRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteFunction");
      $_QLoli = _JJCRD($_QLoli, "RemoveFunctions");
    }

    $_QLJfI = $_QLoli;
  }

  if(isset($_POST["_FORMNAME"]) && $_POST["_FORMNAME"] != "null" && $_POST["_FORMNAME"] != "")
    $_QLJfI = str_replace("'FORMNAME'", "'".$_POST["_FORMNAME"]."'", $_QLJfI);
    else
      $_QLJfI = _L80DF($_QLJfI, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if(isset($_POST["_FORMFIELD"]) && $_POST["_FORMFIELD"] != "null" && $_POST["_FORMFIELD"] != "") {
      $_QLJfI = str_replace('.FORMFIELD', ".".$_POST["_FORMFIELD"], $_QLJfI);
      $_QLJfI = str_replace('<HAS_SOURCEELEMENT>', '', $_QLJfI);
      $_QLJfI = str_replace('</HAS_SOURCEELEMENT>', '', $_QLJfI);
    }
    else
    $_QLJfI = _L80DF($_QLJfI, "<HAS_SOURCEELEMENT>", "</HAS_SOURCEELEMENT>");

  if (!isset($_POST["_IsFCKEditor"]) || $_POST["_IsFCKEditor"] == "false" ) {
     $_QLJfI = str_replace('<ISNOTFCKEDITOR>', '', $_QLJfI);
     $_QLJfI = str_replace('</ISNOTFCKEDITOR>', '', $_QLJfI);
     $_QLJfI = _L80DF($_QLJfI, "<ISFCKEDITOR>", "</ISFCKEDITOR>");
   }
    else {
      $_QLJfI = _L80DF($_QLJfI, "<ISNOTFCKEDITOR>", "</ISNOTFCKEDITOR>");
      $_QLJfI = str_replace('<ISFCKEDITOR>', '', $_QLJfI);
      $_QLJfI = str_replace('</ISFCKEDITOR>', '', $_QLJfI);
    }

  if(isset($_POST["_FORMFIELD"])) {
    $_QLJfI = str_replace('"SourceCKEditor"', '"'.$_POST["_FORMFIELD"].'"', $_QLJfI);
  }

  print $_QLJfI;
  exit;


  function _L1BJP($_QLfol, $_QLoli) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_QLttI, $_jQ68I;
    $_Il0o6 = array();

    if( isset($_POST["FunctionsSaveFilter"]) )
      $_Il0o6["FunctionsSaveFilter"] = $_POST["FunctionsSaveFilter"];

    if(isset($_POST["_FORMNAME"]))
      $_Il0o6["_FORMNAME"] = $_POST["_FORMNAME"];

    if(isset($_POST["_FORMFIELD"]))
      $_Il0o6["_FORMFIELD"] = $_POST["_FORMFIELD"];

    if(isset($_POST["_IsFCKEditor"]))
      $_Il0o6["_IsFCKEditor"] = $_POST["_IsFCKEditor"];

    // Searchstring
    if( isset( $_POST["FunctionSearchFor"] ) && ($_POST["FunctionSearchFor"] != "") ) {
      $_Il0o6["FunctionSearchFor"] = $_POST["FunctionSearchFor"];
      $_IliOC = "Name";

      if( isset( $_POST["Functionfieldname"] ) && ($_POST["Functionfieldname"] != "") ) {
        $_Il0o6["Functionfieldname"] = $_POST["Functionfieldname"];
        $_I016j = substr($_POST["Functionfieldname"], 10);
        if($_I016j != "All")
          $_IliOC = $_I016j;
          else {
            $_IliOC = "";
            $_Iflj0 = array();
            $_QLlO6 = array();
            _L8EOB($_jQ68I, $_Iflj0);
            for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
              $_QLlO6[] = "("."`$_Iflj0[$_Qli6J]` LIKE '%".trim($_POST["FunctionSearchFor"])."%')";
            }
          }

      }

      if($_IliOC != "")
        $_QLfol .= " WHERE (`$_IliOC` LIKE '%".trim($_POST["FunctionSearchFor"])."%')";
        else
        if(count($_QLlO6) > 0)
          $_QLfol .= " WHERE (".join(" OR ", $_QLlO6).")";


    } else {
      $_Il0o6["FunctionSearchFor"] = "";
      $_Il0o6["Functionfieldname"] = "SearchFor_Name";
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
    if ( (!isset($_POST['FunctionsPageSelected'])) || (intval($_POST['FunctionsPageSelected']) == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['FunctionsPageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT(id)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneFunctionId"] ) && ($_POST["OneFunctionId"] == "End") )
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
    if( isset( $_POST["Functionssortfieldname"] ) && ($_POST["Functionssortfieldname"] != "") ) {
      $_Il0o6["Functionssortfieldname"] = $_POST["Functionssortfieldname"];
      if($_POST["Functionssortfieldname"] == "SortName")
         $_IlJj8 = " ORDER BY Name";
      if($_POST["Functionssortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY id";
      if (isset($_POST["Functionssortorder"]) ) {
         $_Il0o6["Functionssortorder"] = $_POST["Functionssortorder"];
         if($_POST["Functionssortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["Functionssortfieldname"] = "SortName";
      $_Il0o6["Functionssortorder"] = "ascending";
    }

    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', 'id, Name, IsDefault', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      if(!$_QLO0f["IsDefault"])
        $_Ql0fO = str_replace ('name="FunctionIDs[]"', 'name="FunctionIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
        else
        $_Ql0fO = _JJC1E($_Ql0fO, "FunctionIDs[]");
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      $_Ql0fO = str_replace ('name="EditFunctionProperties"', 'name="EditFunctionProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteFunction"', 'name="DeleteFunction" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="ApplyFunction"', 'name="ApplyFunction" value="['.$_QLO0f["Name"].']"', $_Ql0fO);

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


    // save the filter for later use
    if( isset($_POST["FunctionsSaveFilter"]) ) {

       if(isset($_Il0o6["_FORMNAME"])) unset($_Il0o6["_FORMNAME"]);
       if(isset($_Il0o6["_FORMFIELD"])) unset($_Il0o6["_FORMFIELD"]);
       if(isset($_Il0o6["_IsFCKEditor"])) unset($_Il0o6["_IsFCKEditor"]);

       $_Il0o6["FunctionsSaveFilter"] = $_POST["FunctionsSaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseFunctionsFilter", serialize($_Il0o6) );
    }

    return $_QLoli;
  }

  function _L1BDE($_jIQLC, &$_IQ0Cj) {
    global $_jQ68I, $_QLttI;
    $_I0lji = array();
    if(is_array($_jIQLC))
      $_I0lji = array_merge($_I0lji, $_jIQLC);
      else
      $_I0lji[] = $_jIQLC;
    for($_Qli6J=0; $_Qli6J<count($_I0lji); $_Qli6J++) {
      $_QLfol = "DELETE FROM $_jQ68I WHERE id=".intval($_I0lji[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "")
         $_IQ0Cj[] = mysql_error($_QLttI);
    }
  }

?>
