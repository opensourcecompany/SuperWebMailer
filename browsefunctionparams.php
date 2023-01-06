<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeFunctionBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_POST["BackBtn"])) {
    include_once("browsefunctions.php");
    exit;
  }

  if(isset($_POST["OneFunctionId"]))
    $_jQjLl = intval($_POST["OneFunctionId"]);
    else
    if(isset($_GET["OneFunctionId"]))
      $_jQjLl = intval($_GET["OneFunctionId"]);



  $_Itfj8 = "";
  $errors = array();

  if(isset($_GET["Action"]) && $_GET["Action"] == "new") {
    include_once("functionedit.php");
    exit;
  }


  if(isset($_POST["CreateFunction"])) {
    if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "") {
       $errors[] = "Name";
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000116"];
    } if(strpos(trim($_POST["Name"]), "]") !== false || strpos(trim($_POST["Name"]), "[") !== false) {
        $errors[] = "Name";
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000117"];
      } else {
        $_QLJfI = trim($_POST["Name"]);
        for($_Qli6J=0; $_Qli6J<strlen($_QLJfI); $_Qli6J++)
          if( !preg_match("/^[a-zA-Z0-9_]{0,255}$/", $_QLJfI[$_Qli6J]) ) {
            $errors[] = "Name";
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000118"];
            break;
          }
      }

    if (count($errors) == 0) {
       $_QLfol = "SELECT COUNT(*) FROM $_jQ68I WHERE Name="._LRAFO(trim($_POST["Name"]));
       if(isset($_jQjLl) && $_jQjLl > 0) {
         $_QLfol .= " AND id<>".$_jQjLl;
       }
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_row($_QL8i1);
       mysql_free_result($_QL8i1);
       if($_QLO0f[0] > 0) {
         $errors[] = "Name";
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000116"];
       }

       if(defined("SWM")) {
         $_QLfol = "SELECT COUNT(*) FROM $_jQf81 WHERE Name="._LRAFO(trim($_POST["Name"]));
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_QLO0f = mysql_fetch_row($_QL8i1);
         mysql_free_result($_QL8i1);
         if($_QLO0f[0] > 0) {
           $errors[] = "Name";
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001420"];
         }
       }
     }
    if (count($errors) == 0) {
      $_QLfol = "INSERT INTO $_jQ68I SET CreateDate=NOW(), Name="._LRAFO(trim($_POST["Name"]));
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_jQjLl = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }
  } // if(isset($_POST["CreateFunction"]))

  // get function
  if(isset($_jQjLl) && $_jQjLl > 0) {
    $_QLfol = "SELECT * FROM $_jQ68I WHERE id=".$_jQjLl;
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_jQ8J6 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if( isset($_jQ8J6["functiontext"]) && $_jQ8J6["functiontext"] != "") {
        $_jQtIJ = @unserialize( $_jQ8J6["functiontext"] );
        if($_jQtIJ === false)
           $_jQtIJ = array();
      }
      else
      $_jQtIJ = array();
  } else
    $_jQtIJ = array(); // new
  //

  if (count($_POST) > 1 && isset($_POST["OneParamAction"]) ) {
     if($_POST["OneParamAction"] == "EditFunctionParamsProperties") {
      include_once("functionedit.php");
      exit;
     }

     if($_POST["OneParamAction"] == "DeleteParamOfFunction") {
      $_I016j = array();
      for($_Qli6J=0; $_Qli6J<count($_jQtIJ); $_Qli6J++) {
        if($_Qli6J != intval($_POST["OneParamId"]))
          $_I016j[] = $_jQtIJ[$_Qli6J];
      }
      unset($_jQtIJ);
      $_jQtIJ = $_I016j;
      $_QLfol = "UPDATE $_jQ68I SET `functiontext`="._LRAFO( serialize($_jQtIJ) )." WHERE id=".$_jQjLl;
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
     }
  }

  // Template
  if (isset($_jQjLl))
    $_POST["OneFunctionId"] = $_jQjLl;
  $functionName = "";
  if(isset($_jQ8J6["Name"])) {
     $functionName = $_jQ8J6["Name"];
     $_POST["Name"] = $functionName;
  }
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000115"], $functionName), $_Itfj8, 'functionedit', 'browse_function_params.htm');

  $_QLoli = "";
  $_IC1C6 = _L81DB($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");
  for($_Qli6J=0; $_Qli6J<count($_jQtIJ); $_Qli6J++) {
    $_Ql0fO = $_IC1C6;

    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:CONDITION>", "</LIST:CONDITION>", _L1A8P($_jQtIJ[$_Qli6J], $_Qli6J==0));
    $_Ql0fO = str_replace ('name="EditFunctionParamsProperties"', 'name="EditFunctionParamsProperties" value="'.$_Qli6J.'"', $_Ql0fO);
    $_Ql0fO = str_replace ('name="DeleteParamOfFunction"', 'name="DeleteParamOfFunction" value="'.$_Qli6J.'"', $_Ql0fO);

    $_QLoli .= $_Ql0fO;
  }

  if($_QLoli == "")
    $_QLoli = '<tr><td colspan="3">'.$resourcestrings[$INTERFACE_LANGUAGE]["000119"].'</td></tr>';
  $_QLJfI = _L81BJ($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QLoli);


  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  if(!isset($_jQjLl) || $_jQjLl == "")
     $_QLJfI = _L80DF($_QLJfI, "<ISNOT:NEWFUNCTION>", "</ISNOT:NEWFUNCTION>");
     else {
       $_QLJfI = str_replace("<ISNOT:NEWFUNCTION>", "", $_QLJfI);
       $_QLJfI = str_replace("</ISNOT:NEWFUNCTION>", "", $_QLJfI);
     }

  print $_QLJfI;
  exit;

  function _L1A8P($_jQoQi, $_jQolf) {
    global $resourcestrings, $INTERFACE_LANGUAGE;

    if($_jQolf)
      $_QLJfI = $resourcestrings[$INTERFACE_LANGUAGE]["IF"];
      else
      $_QLJfI = $resourcestrings[$INTERFACE_LANGUAGE]["ELSEIF"];

    if(!is_array($_jQoQi["fieldname"])) {
      $_jQoQi["logicalop"] = array("--");
      $_jQoQi["fieldname"] = array($_jQoQi["fieldname"]);
      $_jQoQi["operator"] = array($_jQoQi["operator"]);
      $_jQoQi["comparestring"] = array($_jQoQi["comparestring"]);
    }

    # we show only first condition, no more space left
    $_QLJfI .= "&nbsp;";
    $_QLJfI .= "[".$_jQoQi["fieldname"][0]."]";

    $_QLJfI .= "&nbsp;";

    switch($_jQoQi["operator"][0]) {
      case "eq":
           $_QLJfI .= "="."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
      case "neq":
           $_QLJfI .= "&lt;&gt;"."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
      case "lt":
           $_QLJfI .= "&lt;"."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
      case "gt":
           $_QLJfI .= "&gt;"."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
      case "eq_num":
           $_QLJfI .= "="."&nbsp;".$_jQoQi["comparestring"][0]."";
           break;
      case "neq_num":
           $_QLJfI .= "&lt;&gt;"."&nbsp;".$_jQoQi["comparestring"][0]."";
           break;
      case "lt_num":
           $_QLJfI .= "&lt;"."&nbsp;".$_jQoQi["comparestring"][0]."";
           break;
      case "gt_num":
           $_QLJfI .= "&gt;"."&nbsp;".$_jQoQi["comparestring"][0]."";
           break;
      case "contains":
           $_QLJfI .= $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi["operator"][0]]."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
      case "contains_not":
           $_QLJfI .= $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi["operator"][0]]."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
      case "starts_with":
           $_QLJfI .= $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi["operator"][0]]."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
      case "REGEXP":
           $_QLJfI .= $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi["operator"][0]]."&nbsp;&quot;".$_jQoQi["comparestring"][0]."&quot;";
           break;
    }

    if(count($_jQoQi["fieldname"]) > 1){
      $_QLJfI .= "&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi["logicalop"][1]]."&nbsp;"."...";
    }

    $_QLJfI .= "&nbsp;".$resourcestrings[$INTERFACE_LANGUAGE]["GIVEOUT"]."&nbsp;";
    $_It18j = 30;
    $_jQCjt = "";
    $_jQCfL = htmlentities( $_jQoQi["outputtext"], ENT_COMPAT, "UTF-8" );
    if (strlen($_QLJfI.$_jQCfL) > 30)
      $_jQCjt = "...";
    $_QLJfI .= substr($_jQCfL, 0, $_It18j).$_jQCjt;


    return $_QLJfI;
  }

?>
