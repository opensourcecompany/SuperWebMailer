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
  include_once("defaulttexts.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTextBlockBrowse"]) {
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

  $_Itfj8 = "";
  if (count($_POST) > 1) {


    $_Ilt8t = !isset($_POST["TextBlockActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneTextBlockAction"]) && $_POST["OneTextBlockAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneTextBlockId"]) && $_POST["OneTextBlockId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["TextBlockActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["TextBlockActions"] == "RemoveTextBlocks") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeTextBlockRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _LQERE($_POST["TextBlockIDs"], $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001412"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001411"];
        }

    }


    if( isset($_POST["OneTextBlockAction"]) && isset($_POST["OneTextBlockId"]) ) {
      // hier die Einzelaktionen

      if($_POST["OneTextBlockAction"] == "NewTextBlock") {
        include_once("textblockedit.php");
        exit;
      }

      if($_POST["OneTextBlockAction"] == "EditTextBlockProperties") {
        include_once("textblockedit.php");
        exit;
      }

      if($_POST["OneTextBlockAction"] == "DeleteTextBlock") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeTextBlockRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _LQERE(intval($_POST["OneTextBlockId"]), $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001412"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001411"];
      }
    }


  }

  // default SQL query
  $_QLfol = "SELECT {} FROM `$_jQf81`";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001410"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'textblocksedit', 'browse_textblocks.htm');


  $_QLJfI = _LQDEE($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0 && strpos($_QLJfI, '<div class="PageContainer">') !== false ) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">') );
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeTextBlockCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "newtextblock.php");
    }
    if(!$_QLJJ6["PrivilegeTextBlockEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditTextBlockProperties");
    }

    if(!$_QLJJ6["PrivilegeTextBlockRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteTextBlock");
      $_QLoli = _JJCRD($_QLoli, "RemoveTextBlocks");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  } else {

    $_QLoli = $_QLJfI;
    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeTextBlockCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "newtextblock.php");
    }
    if(!$_QLJJ6["PrivilegeTextBlockEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditTextBlockProperties");
    }

    if(!$_QLJJ6["PrivilegeTextBlockRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteTextBlock");
      $_QLoli = _JJCRD($_QLoli, "RemoveTextBlocks");
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


  function _LQDEE($_QLfol, $_QLoli) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_QLttI;
    $_Il0o6 = array();

    if(isset($_POST["_FORMNAME"]))
      $_Il0o6["_FORMNAME"] = $_POST["_FORMNAME"];

    if(isset($_POST["_FORMFIELD"]))
      $_Il0o6["_FORMFIELD"] = $_POST["_FORMFIELD"];

    if(isset($_POST["_IsFCKEditor"]))
      $_Il0o6["_IsFCKEditor"] = $_POST["_IsFCKEditor"];

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['TextblocksPageSelected'])) || ($_POST['TextblocksPageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['TextblocksPageSelected']);

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

    if( isset( $_POST["OneTextBlockId"] ) && ($_POST["OneTextBlockId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneTextBlockId"] ) && ($_POST["OneTextBlockId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneTextBlockId"] ) && ($_POST["OneTextBlockId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneTextBlockId"] ) && ($_POST["OneTextBlockId"] == "End") )
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
        $_Ql0fO = str_replace ('name="TextBlockIDs[]"', 'name="TextBlockIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
        else
        $_Ql0fO = _JJC1E($_Ql0fO, "TextBlockIDs[]");
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      $_Ql0fO = str_replace ('name="EditTextBlockProperties"', 'name="EditTextBlockProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteTextBlock"', 'name="DeleteTextBlock" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="ApplyTextBlock"', 'name="ApplyTextBlock" value="['.$_QLO0f["Name"].']"', $_Ql0fO);

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

  function _LQERE($_joCjj, &$_IQ0Cj) {
    global $_jQf81, $_QLttI;
    $_I0lji = array();
    if(is_array($_joCjj))
      $_I0lji = array_merge($_I0lji, $_joCjj);
      else
      $_I0lji[] = $_joCjj;
    for($_Qli6J=0; $_Qli6J<count($_I0lji); $_Qli6J++) {
      $_QLfol = "DELETE FROM `$_jQf81` WHERE id=".intval($_I0lji[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "")
         $_IQ0Cj[] = mysql_error($_QLttI);
    }
  }

?>
