<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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


  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];

  if(isset($_GET["IsFCKEditor"]))
    $_POST["_IsFCKEditor"] = $_GET["IsFCKEditor"];

  if( isset($_POST["CancelBtn"]) ) {
    include_once("browsefunctionparams.php");
    exit;
  }

  $_Itfj8 = "";

  if(isset($_POST["OneFunctionId"]))
    $_jQjLl = intval($_POST["OneFunctionId"]);
    else
    if(isset($_GET["OneFunctionId"]))
      $_jQjLl = intval($_GET["OneFunctionId"]);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!isset($_jQjLl) && !$_QLJJ6["PrivilegeFunctionCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if(isset($_jQjLl) && !$_QLJJ6["PrivilegeFunctionEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // get function
  if(isset($_jQjLl) && intval($_jQjLl)) {
    $_jQjLl = intval($_jQjLl);
    $_QLfol = "SELECT * FROM $_jQ68I WHERE id=".$_jQjLl;
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_jQ8J6 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if(isset($_jQ8J6["functiontext"]) && $_jQ8J6["functiontext"] != "") {
        $_jQtIJ = @unserialize( $_jQ8J6["functiontext"] );
        if($_jQtIJ === false)
          $_jQtIJ = array();
      }
      else
      $_jQtIJ = array();
  } else
    $_jQ8J6 = array(); // new

  // Template
  if (isset($_jQjLl))
    $_POST["OneFunctionId"] = $_jQjLl;
  $functionName = "";
  if(isset($_jQ8J6["Name"])) {
     $functionName = $_jQ8J6["Name"];
     $_POST["Name"] = $functionName;
  }
  if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] <> "")
     $_POST["OneParamId"] = intval($_POST["OneParamId"]);
     else
     if(isset($_POST["OneParamId"]))
       unset($_POST["OneParamId"]);

  if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] >= 0) {
    $_6QOIC = $_jQtIJ[$_POST["OneParamId"]];
  } else
    $_6QOIC = array();

  if(isset($_POST["SaveBtn"])) {
    if(  (!isset($_POST["logicalop"][1]) || $_POST["logicalop"][1] == "--") &&
         (!isset($_POST["logicalop"][2]) || $_POST["logicalop"][2] == "--")
       ) {
    if(isset($_6QOIC))
      unset($_6QOIC);
    $_6QOIC = array();
    $_6QOIC["fieldname"] = $_POST["fieldname"][0];
    $_6QOIC["operator"] = $_POST["operator"][0];
    $_6QOIC["comparestring"] = trim($_POST["comparestring"][0]);

    $_6QOIC["outputtext"] = $_POST["outputtext"];
    if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] >= 0)
       $_jQtIJ[$_POST["OneParamId"]] = $_6QOIC;
       else
       $_jQtIJ[] = $_6QOIC;
    } else {

      if(isset($_6QOIC))
        unset($_6QOIC);
      $_6QOIC = array();

      # skip not filled conditions
      $_Qli6J = 0;
      $_6QOIC["fieldname"][$_Qli6J] = $_POST["fieldname"][0];
      $_6QOIC["operator"][$_Qli6J] = $_POST["operator"][0];
      $_6QOIC["comparestring"][$_Qli6J] = trim($_POST["comparestring"][0]);

      if( isset($_POST["logicalop"][1]) && $_POST["logicalop"][1] != "--" ) {
        $_Qli6J++;
        $_6QOIC["logicalop"][$_Qli6J] = $_POST["logicalop"][1];
        $_6QOIC["fieldname"][$_Qli6J] = $_POST["fieldname"][1];
        $_6QOIC["operator"][$_Qli6J] = $_POST["operator"][1];
        $_6QOIC["comparestring"][$_Qli6J] = trim($_POST["comparestring"][1]);
      }

      if( isset($_POST["logicalop"][2]) && $_POST["logicalop"][2] != "--" ) {
        $_Qli6J++;
        $_6QOIC["logicalop"][$_Qli6J] = $_POST["logicalop"][2];
        $_6QOIC["fieldname"][$_Qli6J] = $_POST["fieldname"][2];
        $_6QOIC["operator"][$_Qli6J] = $_POST["operator"][2];
        $_6QOIC["comparestring"][$_Qli6J] = trim($_POST["comparestring"][2]);
      }

      $_6QOIC["outputtext"] = $_POST["outputtext"];
      if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] >= 0)
         $_jQtIJ[$_POST["OneParamId"]] = $_6QOIC;
         else
         $_jQtIJ[] = $_6QOIC;

    }
    $_QLfol = "UPDATE $_jQ68I SET `functiontext`="._LRAFO( serialize($_jQtIJ) )." WHERE id=".$_jQjLl;
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    include_once("browsefunctionparams.php");
    exit;
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000120"], $functionName), $_Itfj8, 'functionedit', 'functionedit.htm');

  #### normal placeholders
  $_QLfol = "SELECT `text`, `fieldname` FROM $_Ij8oL WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_I1OoI = array();
  $_jl0Ii = array();
  $_jl0Ii[] = '<option value="id">id</option>';
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
   $_I1OoI[] =  sprintf("new Array('[%s]', '%s')", $_QLO0f["fieldname"], $_QLO0f["text"]);
   $_jl0Ii[] =  '<option value="'.$_QLO0f["fieldname"].'">'.$_QLO0f["text"].'</option>';
  }
  # defaults
  foreach ($_Iol8t as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
  $_QLJfI = _L81BJ($_QLJfI, "<fieldnames>", "</fieldnames>", join("\r\n", $_jl0Ii));
  mysql_free_result($_QL8i1);
  #

  if(count($_6QOIC) > 0 && !is_array($_6QOIC["fieldname"])) {
    $_6QOIC["logicalop"] = array("--");
    $_6QOIC["fieldname"] = array($_6QOIC["fieldname"]);
    $_6QOIC["operator"] = array($_6QOIC["operator"]);
    $_6QOIC["comparestring"] = array($_6QOIC["comparestring"]);
  }

  if(count($_6QOIC) > 0) {
    # format for MarkFields
    $_6QoLo["outputtext"] = $_6QOIC["outputtext"];
    $_Qli6J=0;
    foreach($_6QOIC["fieldname"] as $key => $_QltJO) {
        if(isset($_6QOIC["logicalop"][$key]))
          $_6QoLo["logicalop[$_Qli6J]"] = $_6QOIC["logicalop"][$key];
          else
          $_6QoLo["logicalop[$_Qli6J]"] = "";
        $_6QoLo["fieldname[$_Qli6J]"] = $_6QOIC["fieldname"][$key];
        $_6QoLo["operator[$_Qli6J]"] = $_6QOIC["operator"][$key];
        $_6QoLo["comparestring[$_Qli6J]"] = htmlspecialchars( $_6QOIC["comparestring"][$key], ENT_COMPAT, $_QLo06 );
        $_Qli6J++;
    }
  } else{
    $_6QoLo = array();
  }

  $_POST = array_merge($_POST, $_6QoLo);
  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
  $_QLJfI = _L81BJ($_QLJfI, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
  $_QLJfI = _L81BJ($_QLJfI, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
  $_QLJfI = _L81BJ($_QLJfI, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

  print $_QLJfI;
?>
