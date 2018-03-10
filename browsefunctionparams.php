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
  include_once("functions.inc.php");
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

  if(isset($_POST["BackBtn"])) {
    include_once("browsefunctions.php");
    exit;
  }

  if(isset($_POST["OneFunctionId"]))
    $_I8f80 = intval($_POST["OneFunctionId"]);
    else
    if(isset($_GET["OneFunctionId"]))
      $_I8f80 = intval($_GET["OneFunctionId"]);



  $_I0600 = "";
  $errors = array();

  if(isset($_GET["Action"]) && $_GET["Action"] == "new") {
    include_once("functionedit.php");
    exit;
  }


  if(isset($_POST["CreateFunction"])) {
    if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "") {
       $errors[] = "Name";
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000116"];
    } if(strpos(trim($_POST["Name"]), "]") !== false || strpos(trim($_POST["Name"]), "[") !== false) {
        $errors[] = "Name";
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000117"];
      } else {
        $_QJCJi = trim($_POST["Name"]);
        for($_Q6llo=0; $_Q6llo<strlen($_QJCJi); $_Q6llo++)
          if( !preg_match("/^[a-zA-Z0-9_]{0,255}$/", $_QJCJi{$_Q6llo}) ) {
            $errors[] = "Name";
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000118"];
            break;
          }
      }

    if (count($errors) == 0) {
       $_QJlJ0 = "SELECT COUNT(*) FROM $_I88i8 WHERE Name="._OPQLR(trim($_POST["Name"]));
       if(isset($_I8f80) && $_I8f80 != "") {
         $_QJlJ0 .= " AND id<>".$_I8f80;
       }
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       mysql_free_result($_Q60l1);
       if($_Q6Q1C[0] > 0) {
         $errors[] = "Name";
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000116"];
       }

       if(defined("SWM")) {
         $_QJlJ0 = "SELECT COUNT(*) FROM $_I8tjl WHERE Name="._OPQLR(trim($_POST["Name"]));
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q6Q1C = mysql_fetch_row($_Q60l1);
         mysql_free_result($_Q60l1);
         if($_Q6Q1C[0] > 0) {
           $errors[] = "Name";
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001420"];
         }
       }
     }
    if (count($errors) == 0) {
      $_QJlJ0 = "INSERT INTO $_I88i8 SET CreateDate=NOW(), Name="._OPQLR(trim($_POST["Name"]));
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_I8f80 = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }
  } // if(isset($_POST["CreateFunction"]))

  // get function
  if(isset($_I8f80) && $_I8f80 != "") {
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
    $_I8OQt = array(); // new
  //

  if (count($_POST) != 0 && isset($_POST["OneParamAction"]) ) {
     if($_POST["OneParamAction"] == "EditFunctionParamsProperties") {
      include_once("functionedit.php");
      exit;
     }

     if($_POST["OneParamAction"] == "DeleteParamOfFunction") {
      $_QllO8 = array();
      for($_Q6llo=0; $_Q6llo<count($_I8OQt); $_Q6llo++) {
        if($_Q6llo != intval($_POST["OneParamId"]))
          $_QllO8[] = $_I8OQt[$_Q6llo];
      }
      unset($_I8OQt);
      $_I8OQt = $_QllO8;
      $_QJlJ0 = "UPDATE $_I88i8 SET `functiontext`="._OPQLR( serialize($_I8OQt) )." WHERE id=".$_I8f80;
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
     }
  }

  // Template
  if (isset($_I8f80))
    $_POST["OneFunctionId"] = $_I8f80;
  $_I8oQI = "";
  if(isset($_I8tii["Name"])) {
     $_I8oQI = $_I8tii["Name"];
     $_POST["Name"] = $_I8oQI;
  }
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000115"], $_I8oQI), $_I0600, 'functionedit', 'browse_function_params.htm');

  $_Q6ICj = "";
  $_IIJi1 = _OP81D($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");
  for($_Q6llo=0; $_Q6llo<count($_I8OQt); $_Q6llo++) {
    $_Q66jQ = $_IIJi1;

    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:CONDITION>", "</LIST:CONDITION>", _OLB66($_I8OQt[$_Q6llo], $_Q6llo==0));
    $_Q66jQ = str_replace ('name="EditFunctionParamsProperties"', 'name="EditFunctionParamsProperties" value="'.$_Q6llo.'"', $_Q66jQ);
    $_Q66jQ = str_replace ('name="DeleteParamOfFunction"', 'name="DeleteParamOfFunction" value="'.$_Q6llo.'"', $_Q66jQ);

    $_Q6ICj .= $_Q66jQ;
  }

  if($_Q6ICj == "")
    $_Q6ICj = '<tr><td colspan="3">'.$resourcestrings[$INTERFACE_LANGUAGE]["000119"].'</td></tr>';
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6ICj);


  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  if(!isset($_I8f80) || $_I8f80 == "")
     $_QJCJi = _OP6PQ($_QJCJi, "<ISNOT:NEWFUNCTION>", "</ISNOT:NEWFUNCTION>");
     else {
       $_QJCJi = str_replace("<ISNOT:NEWFUNCTION>", "", $_QJCJi);
       $_QJCJi = str_replace("</ISNOT:NEWFUNCTION>", "", $_QJCJi);
     }

  print $_QJCJi;
  exit;

  function _OLB66($_I8C10, $_I8C8J) {
    global $resourcestrings, $INTERFACE_LANGUAGE;

    if($_I8C8J)
      $_QJCJi = $resourcestrings[$INTERFACE_LANGUAGE]["IF"];
      else
      $_QJCJi = $resourcestrings[$INTERFACE_LANGUAGE]["ELSEIF"];

    if(!is_array($_I8C10["fieldname"])) {
      $_I8C10["logicalop"] = array("--");
      $_I8C10["fieldname"] = array($_I8C10["fieldname"]);
      $_I8C10["operator"] = array($_I8C10["operator"]);
      $_I8C10["comparestring"] = array($_I8C10["comparestring"]);
    }

    # we show only first condition, no more space left
    $_QJCJi .= "&nbsp;";
    $_QJCJi .= "[".$_I8C10["fieldname"][0]."]";

    $_QJCJi .= "&nbsp;";

    switch($_I8C10["operator"][0]) {
      case "eq":
           $_QJCJi .= "="."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
      case "neq":
           $_QJCJi .= "&lt;&gt;"."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
      case "lt":
           $_QJCJi .= "&lt;"."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
      case "gt":
           $_QJCJi .= "&gt;"."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
      case "eq_num":
           $_QJCJi .= "="."&nbsp;".$_I8C10["comparestring"][0]."";
           break;
      case "neq_num":
           $_QJCJi .= "&lt;&gt;"."&nbsp;".$_I8C10["comparestring"][0]."";
           break;
      case "lt_num":
           $_QJCJi .= "&lt;"."&nbsp;".$_I8C10["comparestring"][0]."";
           break;
      case "gt_num":
           $_QJCJi .= "&gt;"."&nbsp;".$_I8C10["comparestring"][0]."";
           break;
      case "contains":
           $_QJCJi .= $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10["operator"][0]]."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
      case "contains_not":
           $_QJCJi .= $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10["operator"][0]]."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
      case "starts_with":
           $_QJCJi .= $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10["operator"][0]]."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
      case "REGEXP":
           $_QJCJi .= $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10["operator"][0]]."&nbsp;&quot;".$_I8C10["comparestring"][0]."&quot;";
           break;
    }

    if(count($_I8C10["fieldname"]) > 1){
      $_QJCJi .= "&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE][$_I8C10["logicalop"][1]]."&nbsp;"."...";
    }

    $_QJCJi .= "&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE]["GIVEOUT"]."&nbsp;";
    $_I00tC = 30;
    $_I8i66 = "";
    $_I8iCo = htmlentities( $_I8C10["outputtext"], ENT_COMPAT, "UTF-8" );
    if (strlen($_QJCJi.$_I8iCo) > 30)
      $_I8i66 = "...";
    $_QJCJi .= substr($_I8iCo, 0, $_I00tC).$_I8i66;


    return $_QJCJi;
  }

?>
