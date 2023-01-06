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
    if(!$_QLJJ6["PrivilegeTemplateBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // import sample newsletter templates
  if(count($_POST) <= 1){
   _L61BJ();
  }

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];

  if(isset($_GET["IsFCKEditor"]))
    $_POST["_IsFCKEditor"] = $_GET["IsFCKEditor"];

  if(isset($_GET["formIframeName"]))
    $_POST["formIframeName"] = $_GET["formIframeName"];


  $_Itfj8 = "";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000810"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'DISABLED', 'browse_htmltemplates.htm');

  if($OwnerUserId == 0)
    $_QLfol = "SELECT {} FROM $_Ql10t WHERE MailFormat <> 'PlainText'";
    else
    $_QLfol = "SELECT {} FROM $_Ql10t LEFT JOIN $_Ql18I ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND MailFormat <> 'PlainText'";

  if( (!empty($_GET["wizardableonly"]) && $_GET["wizardableonly"] == "true") || (!empty($_POST["wizardableonly"]) && $_POST["wizardableonly"] == "true") )
     $_QLfol .= " AND `IsWizardable`>0";

  $_QLJfI = _LBC6F($_QLfol, $_QLJfI);

  if(!empty($_POST["formIframeName"])) {
    $_QLJfI = _L80DF($_QLJfI, "<ISNOTFCKEDITOR>", "</ISNOTFCKEDITOR>");
    $_QLJfI = _L80DF($_QLJfI, "<ISFCKEDITOR>", "</ISFCKEDITOR>");
    $_QLJfI = str_replace('"IFRAMENAME"', '"'.$_POST["formIframeName"].'"', $_QLJfI);
    $_QLJfI = str_replace('"ScriptBaseURL"', '"'.ScriptBaseURL.'"', $_QLJfI);
    $_QLJfI = str_replace('name="formIframeName"', 'name="formIframeName" value='.'"'.$_POST["formIframeName"].'"', $_QLJfI);
    if( !empty($_GET["wizardableonly"]) && $_GET["wizardableonly"] == "true" )
      $_QLJfI = str_replace('"wizardableonly"', '"wizardableonly" value='.'"'.$_GET["wizardableonly"].'"', $_QLJfI);
    if( !empty($_POST["wizardableonly"]) && $_POST["wizardableonly"] == "true" )
      $_QLJfI = str_replace('"wizardableonly"', '"wizardableonly" value='.'"'.$_POST["wizardableonly"].'"', $_QLJfI);


  } else {

     $_QLJfI = _L80DF($_QLJfI, "<ISIFRAME>", "</ISIFRAME>");

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
  }

  print $_QLJfI;


  function _LBC6F($_QLfol, $_QLoli) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_Ql10t, $_Ql18I, $OwnerUserId, $UserId;
    $_Il0o6 = array();

    if(isset($_POST["_FORMNAME"]))
      $_Il0o6["_FORMNAME"] = $_POST["_FORMNAME"];

    if(isset($_POST["_FORMFIELD"]))
      $_Il0o6["_FORMFIELD"] = $_POST["_FORMFIELD"];

    if(isset($_POST["_IsFCKEditor"]))
      $_Il0o6["_IsFCKEditor"] = $_POST["_IsFCKEditor"];

    // wie viele pro Seite?
    $_Il1jO = 10;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 10;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['PageSelected'])) || (intval($_POST['PageSelected']) == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT(id)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "End") )
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

    $_QLfol = str_replace('{}', 'id, Name, MailHTMLText, IsDefault, UsersOption', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {

      if($OwnerUserId != 0 && $_QLO0f["UsersOption"] > 0){
        $_QLfol = "SELECT users_id FROM $_Ql18I WHERE templates_id=$_QLO0f[id] AND users_id=$UserId";
        $_I1o8o = mysql_query($_QLfol);
        if(mysql_num_rows($_I1o8o) == 0) {@mysql_free_result($_I1o8o); continue;}
        mysql_free_result($_I1o8o);
      }


      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      $_QltJO = $_QLO0f["MailHTMLText"];
      $_QltJO = str_replace('"', '0x022', $_QltJO);
      $_QltJO = str_replace(chr(10), '0x10', $_QltJO);
      $_QltJO = str_replace(chr(13), '0x13', $_QltJO);
      if(empty($_POST["formIframeName"]))
        $_Ql0fO = str_replace ('name="ApplyTemplate"', 'name="ApplyTemplate" value="'.$_QltJO.'"', $_Ql0fO);
        else
        $_Ql0fO = str_replace ('name="ApplyTemplate"', 'name="ApplyTemplate" value="'.$_QLO0f["id"].'"', $_Ql0fO);

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



?>
