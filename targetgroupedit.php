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

  if( isset($_POST["CancelBtn"]) ) {
    include_once("browsetargetgroups.php");
    exit;
  }

  $_I0600 = "";

  if(isset($_POST["OneTargetGroupId"]))
    $_IlifI = intval($_POST["OneTargetGroupId"]);
    else
    if(isset($_GET["OneTargetGroupId"]))
      $_IlifI = intval($_GET["OneTargetGroupId"]);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if( (!isset($_IlifI) || $_IlifI == 0) && !$_QJojf["PrivilegeTargetGroupsCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if( (isset($_IlifI)) && !$_QJojf["PrivilegeTargetGroupsEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $errors = array();
  if(isset($_POST["SaveBtn"])) {
    if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "") {
       $errors[] = "Name";
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001516"];
    } else if(strpos(trim($_POST["Name"]), "]") !== false || strpos(trim($_POST["Name"]), "[") !== false) {
        $errors[] = "Name";
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001517"];
      } else {
        $_QJCJi = trim($_POST["Name"]);
        for($_Q6llo=0; $_Q6llo<strlen($_QJCJi); $_Q6llo++)
          if( !preg_match("/^[a-zA-Z0-9_]{0,255}$/", $_QJCJi{$_Q6llo}) ) {
            $errors[] = "Name";
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001518"];
            break;
          }
      }

    if(count($errors) > 0 && $_I0600 == "")
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001519"];

    if (count($errors) == 0) {
       $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6C0i WHERE Name="._OPQLR(trim($_POST["Name"]));
       if(isset($_IlifI) && $_IlifI != "") {
         $_QJlJ0 .= " AND id<>".$_IlifI;
       }
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       mysql_free_result($_Q60l1);
       if($_Q6Q1C[0] > 0) {
         $errors[] = "Name";
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001516"];
       }
     }

    if (count($errors) == 0) {
       $_f1LCl = array();

       if(   (!isset($_POST["logicalop"][1]) || $_POST["logicalop"][1] == "--") &&
            (!isset($_POST["logicalop"][2]) || $_POST["logicalop"][2] == "--")
          ) {

          $_f1LCl["fieldname"][0] = $_POST["fieldname"][0];
          $_f1LCl["operator"][0] = $_POST["operator"][0];
          $_f1LCl["comparestring"][0] = trim($_POST["comparestring"][0]);

          $_f1LiC[] = $_f1LCl;
       } else {

           # skip not filled conditions
           $_Q6llo = 0;
           $_f1LCl["fieldname"][$_Q6llo] = $_POST["fieldname"][0];
           $_f1LCl["operator"][$_Q6llo] = $_POST["operator"][0];
           $_f1LCl["comparestring"][$_Q6llo] = trim($_POST["comparestring"][0]);

           if( isset($_POST["logicalop"][1]) && $_POST["logicalop"][1] != "--" ) {
             $_Q6llo++;
             $_f1LCl["logicalop"][$_Q6llo] = $_POST["logicalop"][1];
             $_f1LCl["fieldname"][$_Q6llo] = $_POST["fieldname"][1];
             $_f1LCl["operator"][$_Q6llo] = $_POST["operator"][1];
             $_f1LCl["comparestring"][$_Q6llo] = trim($_POST["comparestring"][1]);
           }

           if( isset($_POST["logicalop"][2]) && $_POST["logicalop"][2] != "--" ) {
             $_Q6llo++;
             $_f1LCl["logicalop"][$_Q6llo] = $_POST["logicalop"][2];
             $_f1LCl["fieldname"][$_Q6llo] = $_POST["fieldname"][2];
             $_f1LCl["operator"][$_Q6llo] = $_POST["operator"][2];
             $_f1LCl["comparestring"][$_Q6llo] = trim($_POST["comparestring"][2]);
           }

           $_f1LiC[] = $_f1LCl;
       }

       if(isset($_IlifI) && $_IlifI != "")
          $_QJlJ0 = "UPDATE `$_Q6C0i` SET `Name`="._OPQLR($_POST["Name"]).", `functiontext`="._OPQLR( serialize($_f1LCl) )." WHERE id=".$_IlifI;
          else
          $_QJlJ0 = "INSERT INTO `$_Q6C0i` SET `Name`="._OPQLR($_POST["Name"]).", `CreateDate`=NOW(), `functiontext`="._OPQLR( serialize($_f1LCl) );

       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);

       if(!isset($_IlifI)){
          $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
          $_Q6Q1C=mysql_fetch_array($_Q60l1);
          $_IlifI = $_Q6Q1C[0];
          mysql_free_result($_Q60l1);
       }

       include_once("browsetargetgroups.php");
       exit;

    }

  }

  // get targetgroups
  if(isset($_IlifI) && $_IlifI != "") {
    $_QJlJ0 = "SELECT * FROM $_Q6C0i WHERE id=".intval($_IlifI);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_f1l88 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if($_f1l88["functiontext"] != "") {
        $_f1LiC = @unserialize( $_f1l88["functiontext"] );
        if($_f1LiC === false)
          $_f1LiC = array();
      }
      else
      $_f1LiC = array();
  } else
    $_f1LiC = array(); // new

  // Template
  if (isset($_IlifI))
    $_POST["OneTargetGroupId"] = intval($_IlifI);
  $_f1lot = "";
  if(isset($_f1l88["Name"])) {
     $_f1lot = $_f1l88["Name"];
     $_POST["Name"] = $_f1lot;
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001515"], $_f1lot), $_I0600, 'targetgroupsedit', 'targetgroupedit.htm');
  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

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
  #### special newsletter unsubscribe placeholders
  reset($_III0L);
  foreach ($_III0L as $key => $_Q6ClO)
    $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QJCJi = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
  $_QJCJi = _OPR6L($_QJCJi, "<fieldnames>", "</fieldnames>", join("\r\n", $_jQjOO));
  mysql_free_result($_Q60l1);
  #

  if(isset($_f1LiC) && is_array($_f1LiC) && count($_f1LiC) > 0) {
    $_f1LCl = $_f1LiC;
  } else
    $_f1LCl = array();

  $_f1lCj = array();
  if(count($_f1LCl) > 0) {
    # format for MarkFields

      $_Q6llo=0;
      foreach($_f1LCl["fieldname"] as $key => $_Q6ClO) {
          if(isset($_f1LCl["logicalop"][$key]))
            $_f1lCj["logicalop[$_Q6llo]"] = $_f1LCl["logicalop"][$key];
            else
            $_f1lCj["logicalop[$_Q6llo]"] = "";
          $_f1lCj["fieldname[$_Q6llo]"] = $_f1LCl["fieldname"][$key];
          $_f1lCj["operator[$_Q6llo]"] = $_f1LCl["operator"][$key];
          $_f1lCj["comparestring[$_Q6llo]"] = htmlspecialchars( $_f1LCl["comparestring"][$key], ENT_COMPAT, "UTF-8" );
          $_Q6llo++;
      }
  }

  $_POST = array_merge($_POST, $_f1lCj);
  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
  $_QJCJi = _OPR6L($_QJCJi, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
  $_QJCJi = _OPR6L($_QJCJi, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
  $_QJCJi = _OPR6L($_QJCJi, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

  print $_QJCJi;
?>
