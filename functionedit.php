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

  $_I0600 = "";

  if(isset($_POST["OneFunctionId"]))
    $_I8f80 = intval($_POST["OneFunctionId"]);
    else
    if(isset($_GET["OneFunctionId"]))
      $_I8f80 = intval($_GET["OneFunctionId"]);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!isset($_I8f80) && !$_QJojf["PrivilegeFunctionCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if(isset($_I8f80) && !$_QJojf["PrivilegeFunctionEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  // get function
  if(isset($_I8f80) && intval($_I8f80)) {
    $_I8f80 = intval($_I8f80);
    $_QJlJ0 = "SELECT * FROM $_I88i8 WHERE id=".$_I8f80;
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_I8tii = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if($_I8tii["functiontext"] != "") {
        $_I8OQt = @unserialize( $_I8tii["functiontext"] );
        if($_I8OQt === false)
          $_I8OQt = array();
      }
      else
      $_I8OQt = array();
  } else
    $_I8tii = array(); // new

  // Template
  if (isset($_I8f80))
    $_POST["OneFunctionId"] = $_I8f80;
  $_I8oQI = "";
  if(isset($_I8tii["Name"])) {
     $_I8oQI = $_I8tii["Name"];
     $_POST["Name"] = $_I8oQI;
  }
  if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] != "")
     $_POST["OneParamId"] = intval($_POST["OneParamId"]);
     else
     if(isset($_POST["OneParamId"]))
       unset($_POST["OneParamId"]);

  if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] >= 0) {
    $_J1tol = $_I8OQt[$_POST["OneParamId"]];
  } else
    $_J1tol = array();

  if(isset($_POST["SaveBtn"])) {
    if(  (!isset($_POST["logicalop"][1]) || $_POST["logicalop"][1] == "--") &&
         (!isset($_POST["logicalop"][2]) || $_POST["logicalop"][2] == "--")
       ) {
    if(isset($_J1tol))
      unset($_J1tol);
    $_J1tol = array();
    $_J1tol["fieldname"] = $_POST["fieldname"][0];
    $_J1tol["operator"] = $_POST["operator"][0];
    $_J1tol["comparestring"] = trim($_POST["comparestring"][0]);

    $_J1tol["outputtext"] = $_POST["outputtext"];
    if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] >= 0)
       $_I8OQt[$_POST["OneParamId"]] = $_J1tol;
       else
       $_I8OQt[] = $_J1tol;
    } else {

      if(isset($_J1tol))
        unset($_J1tol);
      $_J1tol = array();

      # skip not filled conditions
      $_Q6llo = 0;
      $_J1tol["fieldname"][$_Q6llo] = $_POST["fieldname"][0];
      $_J1tol["operator"][$_Q6llo] = $_POST["operator"][0];
      $_J1tol["comparestring"][$_Q6llo] = trim($_POST["comparestring"][0]);

      if( isset($_POST["logicalop"][1]) && $_POST["logicalop"][1] != "--" ) {
        $_Q6llo++;
        $_J1tol["logicalop"][$_Q6llo] = $_POST["logicalop"][1];
        $_J1tol["fieldname"][$_Q6llo] = $_POST["fieldname"][1];
        $_J1tol["operator"][$_Q6llo] = $_POST["operator"][1];
        $_J1tol["comparestring"][$_Q6llo] = trim($_POST["comparestring"][1]);
      }

      if( isset($_POST["logicalop"][2]) && $_POST["logicalop"][2] != "--" ) {
        $_Q6llo++;
        $_J1tol["logicalop"][$_Q6llo] = $_POST["logicalop"][2];
        $_J1tol["fieldname"][$_Q6llo] = $_POST["fieldname"][2];
        $_J1tol["operator"][$_Q6llo] = $_POST["operator"][2];
        $_J1tol["comparestring"][$_Q6llo] = trim($_POST["comparestring"][2]);
      }

      $_J1tol["outputtext"] = $_POST["outputtext"];
      if(isset($_POST["OneParamId"]) && $_POST["OneParamId"] >= 0)
         $_I8OQt[$_POST["OneParamId"]] = $_J1tol;
         else
         $_I8OQt[] = $_J1tol;

    }
    $_QJlJ0 = "UPDATE $_I88i8 SET `functiontext`="._OPQLR( serialize($_I8OQt) )." WHERE id=".$_I8f80;
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    include_once("browsefunctionparams.php");
    exit;
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000120"], $_I8oQI), $_I0600, 'functionedit', 'functionedit.htm');

  #### normal placeholders
  $_QJlJ0 = "SELECT `text`, `fieldname` FROM $_Qofjo WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q8otJ = array();
  $_jQjOO = array();
  $_jQjOO[] = '<option value="id">id</option>';
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[] =  sprintf("new Array('[%s]', '%s')", $_Q6Q1C["fieldname"], $_Q6Q1C["text"]);
   $_jQjOO[] =  '<option value="'.$_Q6Q1C["fieldname"].'">'.$_Q6Q1C["text"].'</option>';
  }
  # defaults
  foreach ($_IIQI8 as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QJCJi = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
  $_QJCJi = _OPR6L($_QJCJi, "<fieldnames>", "</fieldnames>", join("\r\n", $_jQjOO));
  mysql_free_result($_Q60l1);
  #

  if(count($_J1tol) > 0 && !is_array($_J1tol["fieldname"])) {
    $_J1tol["logicalop"] = array("--");
    $_J1tol["fieldname"] = array($_J1tol["fieldname"]);
    $_J1tol["operator"] = array($_J1tol["operator"]);
    $_J1tol["comparestring"] = array($_J1tol["comparestring"]);
  }

  if(count($_J1tol) > 0) {
    # format for MarkFields
    $_J1o6l["outputtext"] = $_J1tol["outputtext"];
    $_Q6llo=0;
    foreach($_J1tol["fieldname"] as $key => $_Q6ClO) {
        if(isset($_J1tol["logicalop"][$key]))
          $_J1o6l["logicalop[$_Q6llo]"] = $_J1tol["logicalop"][$key];
          else
          $_J1o6l["logicalop[$_Q6llo]"] = "";
        $_J1o6l["fieldname[$_Q6llo]"] = $_J1tol["fieldname"][$key];
        $_J1o6l["operator[$_Q6llo]"] = $_J1tol["operator"][$key];
        $_J1o6l["comparestring[$_Q6llo]"] = htmlspecialchars( $_J1tol["comparestring"][$key], ENT_COMPAT, "UTF-8" );
        $_Q6llo++;
    }
  } else{
    $_J1o6l = array();
  }

  $_POST = array_merge($_POST, $_J1o6l);
  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
  $_QJCJi = _OPR6L($_QJCJi, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
  $_QJCJi = _OPR6L($_QJCJi, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
  $_QJCJi = _OPR6L($_QJCJi, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

  print $_QJCJi;
?>
