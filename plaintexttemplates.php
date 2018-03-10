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
  include_once("defaulttexts.inc.php");

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
  _O8QO8();

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];

  if(isset($_GET["IsFCKEditor"]))
    $_POST["_IsFCKEditor"] = $_GET["IsFCKEditor"];

  $_I0600 = "";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000810"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'DISABLED', 'browse_plaintexttemplates.htm');

  if($OwnerUserId == 0)
    $_QJlJ0 = "SELECT {} FROM $_Q66li WHERE MailFormat = 'PlainText'";
    else
    $_QJlJ0 = "SELECT {} FROM $_Q66li LEFT JOIN $_Q6ftI ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND MailFormat = 'PlainText'";

  $_QJCJi = _OD08E($_QJlJ0, $_QJCJi);

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

  print $_QJCJi;


  function _OD08E($_QJlJ0, $_Q6ICj) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_Q66li, $_Q6ftI, $OwnerUserId, $UserId;
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
    if ( (!isset($_POST['PageSelected'])) || (intval($_POST['PageSelected']) == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', 'COUNT(id)', $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneTemplateId"] ) && ($_POST["OneTemplateId"] == "End") )
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

    $_QJlJ0 = str_replace('{}', 'id, Name, MailPlainText, IsDefault, UsersOption', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {

      if($OwnerUserId != 0 && $_Q6Q1C["UsersOption"] > 0){
        $_QJlJ0 = "SELECT users_id FROM $_Q6ftI WHERE templates_id=$_Q6Q1C[id] AND users_id=$UserId";
        $_Q8COf = mysql_query($_QJlJ0);
        if(mysql_num_rows($_Q8COf) == 0) {@mysql_free_result($_Q8COf); continue;}
        mysql_free_result($_Q8COf);
      }


      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);

      $_Q6ClO = $_Q6Q1C["MailPlainText"];
      $_Q6ClO = str_replace('"', '0x022', $_Q6ClO);
      $_Q6ClO = str_replace(chr(10), '0x10', $_Q6ClO);
      $_Q6ClO = str_replace(chr(13), '0x13', $_Q6ClO);
      $_Q66jQ = str_replace ('name="ApplyTemplate"', 'name="ApplyTemplate" value="'.$_Q6ClO.'"', $_Q66jQ);

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



?>
