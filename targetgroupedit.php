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

  if( isset($_POST["CancelBtn"]) ) {
    include_once("browsetargetgroups.php");
    exit;
  }

  $_Itfj8 = "";

  if(isset($_POST["OneTargetGroupId"]))
    $_jojt6 = intval($_POST["OneTargetGroupId"]);
    else
    if(isset($_GET["OneTargetGroupId"]))
      $_jojt6 = intval($_GET["OneTargetGroupId"]);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if( (!isset($_jojt6) || $_jojt6 == 0) && !$_QLJJ6["PrivilegeTargetGroupsCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if( (isset($_jojt6)) && !$_QLJJ6["PrivilegeTargetGroupsEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $errors = array();
  if(isset($_POST["SaveBtn"])) {
    if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "") {
       $errors[] = "Name";
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001516"];
    } else if(strpos(trim($_POST["Name"]), "]") !== false || strpos(trim($_POST["Name"]), "[") !== false) {
        $errors[] = "Name";
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001517"];
      } else {
        $_QLJfI = trim($_POST["Name"]);
        for($_Qli6J=0; $_Qli6J<strlen($_QLJfI); $_Qli6J++)
          if( !preg_match("/^[a-zA-Z0-9_]{0,255}$/", $_QLJfI[$_Qli6J]) ) {
            $errors[] = "Name";
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001518"];
            break;
          }
      }

    if(count($errors) > 0 && $_Itfj8 == "")
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001519"];

    if (count($errors) == 0) {
       $_QLfol = "SELECT COUNT(*) FROM $_QlfCL WHERE Name="._LRAFO(trim($_POST["Name"]));
       if(isset($_jojt6) && $_jojt6 > 0) {
         $_QLfol .= " AND id<>".$_jojt6;
       }
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_row($_QL8i1);
       mysql_free_result($_QL8i1);
       if($_QLO0f[0] > 0) {
         $errors[] = "Name";
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001516"];
       }
     }

    if (count($errors) == 0) {
       $_8C0jo = array();

       if(   (!isset($_POST["logicalop"][1]) || $_POST["logicalop"][1] == "--") &&
            (!isset($_POST["logicalop"][2]) || $_POST["logicalop"][2] == "--")
          ) {

          $_8C0jo["fieldname"][0] = $_POST["fieldname"][0];
          $_8C0jo["operator"][0] = $_POST["operator"][0];
          $_8C0jo["comparestring"][0] = trim($_POST["comparestring"][0]);

          $_8C0Ol[] = $_8C0jo;
       } else {

           # skip not filled conditions
           $_Qli6J = 0;
           $_8C0jo["fieldname"][$_Qli6J] = $_POST["fieldname"][0];
           $_8C0jo["operator"][$_Qli6J] = $_POST["operator"][0];
           $_8C0jo["comparestring"][$_Qli6J] = trim($_POST["comparestring"][0]);

           if( isset($_POST["logicalop"][1]) && $_POST["logicalop"][1] != "--" ) {
             $_Qli6J++;
             $_8C0jo["logicalop"][$_Qli6J] = $_POST["logicalop"][1];
             $_8C0jo["fieldname"][$_Qli6J] = $_POST["fieldname"][1];
             $_8C0jo["operator"][$_Qli6J] = $_POST["operator"][1];
             $_8C0jo["comparestring"][$_Qli6J] = trim($_POST["comparestring"][1]);
           }

           if( isset($_POST["logicalop"][2]) && $_POST["logicalop"][2] != "--" ) {
             $_Qli6J++;
             $_8C0jo["logicalop"][$_Qli6J] = $_POST["logicalop"][2];
             $_8C0jo["fieldname"][$_Qli6J] = $_POST["fieldname"][2];
             $_8C0jo["operator"][$_Qli6J] = $_POST["operator"][2];
             $_8C0jo["comparestring"][$_Qli6J] = trim($_POST["comparestring"][2]);
           }

           $_8C0Ol[] = $_8C0jo;
       }

       if(isset($_jojt6) && $_jojt6 > 0)
          $_QLfol = "UPDATE `$_QlfCL` SET `Name`="._LRAFO($_POST["Name"]).", `functiontext`="._LRAFO( serialize($_8C0jo) )." WHERE id=".$_jojt6;
          else
          $_QLfol = "INSERT INTO `$_QlfCL` SET `Name`="._LRAFO($_POST["Name"]).", `CreateDate`=NOW(), `functiontext`="._LRAFO( serialize($_8C0jo) );

       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);

       if(!isset($_jojt6)){
          $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
          $_QLO0f=mysql_fetch_array($_QL8i1);
          $_jojt6 = $_QLO0f[0];
          mysql_free_result($_QL8i1);
       }

       include_once("browsetargetgroups.php");
       exit;

    }

  }

  // get targetgroups
  if(isset($_jojt6) && $_jojt6 > 0) {
    $_QLfol = "SELECT * FROM $_QlfCL WHERE id=".intval($_jojt6);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_8C161 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if($_8C161["functiontext"] != "") {
        $_8C0Ol = @unserialize( $_8C161["functiontext"] );
        if($_8C0Ol === false)
          $_8C0Ol = array();
      }
      else
      $_8C0Ol = array();
  } else
    $_8C0Ol = array(); // new

  // Template
  if (isset($_jojt6))
    $_POST["OneTargetGroupId"] = intval($_jojt6);
  $_8C1Lt = "";
  if(isset($_8C161["Name"])) {
     $_8C1Lt = $_8C161["Name"];
     $_POST["Name"] = $_8C1Lt;
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001515"], $_8C1Lt), $_Itfj8, 'targetgroupsedit', 'targetgroupedit.htm');
  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

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
  #### special newsletter unsubscribe placeholders
  reset($_IolCJ);
  foreach ($_IolCJ as $key => $_QltJO)
    $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
  $_QLJfI = _L81BJ($_QLJfI, "<fieldnames>", "</fieldnames>", join("\r\n", $_jl0Ii));
  mysql_free_result($_QL8i1);
  #

  if(isset($_8C0Ol) && is_array($_8C0Ol) && count($_8C0Ol) > 0) {
    $_8C0jo = $_8C0Ol;
  } else
    $_8C0jo = array();

  $_8CQo6 = array();
  if(count($_8C0jo) > 0) {
    # format for MarkFields

      $_Qli6J=0;
      foreach($_8C0jo["fieldname"] as $key => $_QltJO) {
          if(isset($_8C0jo["logicalop"][$key]))
            $_8CQo6["logicalop[$_Qli6J]"] = $_8C0jo["logicalop"][$key];
            else
            $_8CQo6["logicalop[$_Qli6J]"] = "";
          $_8CQo6["fieldname[$_Qli6J]"] = $_8C0jo["fieldname"][$key];
          $_8CQo6["operator[$_Qli6J]"] = $_8C0jo["operator"][$key];
          $_8CQo6["comparestring[$_Qli6J]"] = htmlspecialchars( $_8C0jo["comparestring"][$key], ENT_COMPAT, $_QLo06 );
          $_Qli6J++;
      }
  }

  $_POST = array_merge($_POST, $_8CQo6);
  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
  $_QLJfI = _L81BJ($_QLJfI, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
  $_QLJfI = _L81BJ($_QLJfI, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
  $_QLJfI = _L81BJ($_QLJfI, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

  print $_QLJfI;
?>
