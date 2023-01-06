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

  if(isset($_GET["form"]))
    $_POST["_FORMNAME"] = $_GET["form"];

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];

  if(isset($_GET["IsFCKEditor"]))
    $_POST["_IsFCKEditor"] = $_GET["IsFCKEditor"];

  if( isset($_POST["CancelBtn"]) ) {
    include_once("browsetextblocks.php");
    exit;
  }

  $_Itfj8 = "";

  if(isset($_POST["OneTextBlockId"]))
    $_joOfL = intval($_POST["OneTextBlockId"]);
    else
    if(isset($_GET["OneTextBlockId"]))
      $_joOfL = intval($_GET["OneTextBlockId"]);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if( (!isset($_joOfL) || $_joOfL == 0) && !$_QLJJ6["PrivilegeTextBlockCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if( (isset($_joOfL)) && !$_QLJJ6["PrivilegeTextBlockEdit"]) {
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
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001416"];
    } else if(strpos(trim($_POST["Name"]), "]") !== false || strpos(trim($_POST["Name"]), "[") !== false) {
        $errors[] = "Name";
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001417"];
      } else {
        $_QLJfI = trim($_POST["Name"]);
        for($_Qli6J=0; $_Qli6J<strlen($_QLJfI); $_Qli6J++)
          if( !preg_match("/^[a-zA-Z0-9_]{0,255}$/", $_QLJfI[$_Qli6J]) ) {
            $errors[] = "Name";
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001418"];
            break;
          }
      }

    if( isset($_POST["outputtext"]) ) {
       $_POST["outputtext"] = CleanUpHTML( $_POST["outputtext"] );
       // fix www to http://wwww.
       $_POST["outputtext"] = str_replace('href="www.', 'href="http://www.', $_POST["outputtext"]);

    } else
      $errors[] = "outputtext";

    if( isset($_POST["outputtextplain"]) ) {
       $_POST["outputtextplain"] = CleanUpHTML( $_POST["outputtextplain"] );
       // fix www to http://wwww.
       $_POST["outputtextplain"] = str_replace('href="www.', 'href="http://www.', $_POST["outputtextplain"]);

    } else
      $errors[] = "outputtextplain";

    if(count($errors) > 0 && $_Itfj8 == "")
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001419"];

    if (count($errors) == 0) {
       $_QLfol = "SELECT COUNT(*) FROM $_jQf81 WHERE Name="._LRAFO(trim($_POST["Name"]));
       if(isset($_joOfL) && $_joOfL > 0) {
         $_QLfol .= " AND id<>".$_joOfL;
       }
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_row($_QL8i1);
       mysql_free_result($_QL8i1);
       if($_QLO0f[0] > 0) {
         $errors[] = "Name";
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001416"];
       }

       $_QLfol = "SELECT COUNT(*) FROM $_jQ68I WHERE Name="._LRAFO(trim($_POST["Name"]));
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_row($_QL8i1);
       mysql_free_result($_QL8i1);
       if($_QLO0f[0] > 0) {
         $errors[] = "Name";
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001420"];
       }

     }

    if (count($errors) == 0) {
       $_8ifQ1 = array();

       if(   (!isset($_POST["logicalop"][1]) || $_POST["logicalop"][1] == "--") &&
            (!isset($_POST["logicalop"][2]) || $_POST["logicalop"][2] == "--")
          ) {

        if(isset($_POST["WithCondition"])) {
          $_8ifQ1["fieldname"][0] = $_POST["fieldname"][0];
          $_8ifQ1["operator"][0] = $_POST["operator"][0];
          $_8ifQ1["comparestring"][0] = trim($_POST["comparestring"][0]);
         }

         $_8ifQ1["outputtext"] = $_POST["outputtext"];
         $_8ifQ1["outputtextplain"] = $_POST["outputtextplain"];
         $_8i8fj[] = $_8ifQ1;
       } else {

         if(isset($_POST["WithCondition"])) {
           # skip not filled conditions
           $_Qli6J = 0;
           $_8ifQ1["fieldname"][$_Qli6J] = $_POST["fieldname"][0];
           $_8ifQ1["operator"][$_Qli6J] = $_POST["operator"][0];
           $_8ifQ1["comparestring"][$_Qli6J] = trim($_POST["comparestring"][0]);

           if( isset($_POST["logicalop"][1]) && $_POST["logicalop"][1] != "--" ) {
             $_Qli6J++;
             $_8ifQ1["logicalop"][$_Qli6J] = $_POST["logicalop"][1];
             $_8ifQ1["fieldname"][$_Qli6J] = $_POST["fieldname"][1];
             $_8ifQ1["operator"][$_Qli6J] = $_POST["operator"][1];
             $_8ifQ1["comparestring"][$_Qli6J] = trim($_POST["comparestring"][1]);
           }

           if( isset($_POST["logicalop"][2]) && $_POST["logicalop"][2] != "--" ) {
             $_Qli6J++;
             $_8ifQ1["logicalop"][$_Qli6J] = $_POST["logicalop"][2];
             $_8ifQ1["fieldname"][$_Qli6J] = $_POST["fieldname"][2];
             $_8ifQ1["operator"][$_Qli6J] = $_POST["operator"][2];
             $_8ifQ1["comparestring"][$_Qli6J] = trim($_POST["comparestring"][2]);
           }
         }

         $_8ifQ1["outputtext"] = $_POST["outputtext"];
         $_8ifQ1["outputtextplain"] = $_POST["outputtextplain"];
         $_8i8fj[] = $_8ifQ1;

       }

       if(isset($_joOfL) && $_joOfL <> 0)
          $_QLfol = "UPDATE `$_jQf81` SET `Name`="._LRAFO($_POST["Name"]).", `textblocktext`="._LRAFO( base64_encode(serialize($_8ifQ1)) )." WHERE id=".$_joOfL;
          else
          $_QLfol = "INSERT INTO `$_jQf81` SET `Name`="._LRAFO($_POST["Name"]).", `CreateDate`=NOW(), `textblocktext`="._LRAFO( base64_encode(serialize($_8ifQ1)) );

       mysql_query($_QLfol);
       _L8D88($_QLfol);

       if(!isset($_joOfL)){
          $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()");
          $_QLO0f=mysql_fetch_array($_QL8i1);
          $_joOfL = $_QLO0f[0];
          mysql_free_result($_QL8i1);
       }

       include_once("browsetextblocks.php");
       exit;

    }

  }

  // get textblock
  if(isset($_joOfL) && $_joOfL > 0) {
    $_QLfol = "SELECT * FROM $_jQf81 WHERE id=".intval($_joOfL);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_fLOjO = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if( isset($_fLOjO["textblocktext"]) && $_fLOjO["textblocktext"] != "") {
        $_8i8fj = @unserialize( base64_decode($_fLOjO["textblocktext"]) );
        if($_8i8fj === false)
          $_8i8fj = array();
      }
      else
      $_8i8fj = array();
  } else
    $_8i8fj = array(); // new

  // Template
  if (isset($_joOfL))
    $_POST["OneTextBlockId"] = intval($_joOfL);
  $_8i88C = "";
  if(isset($_fLOjO["Name"])) {
     $_8i88C = $_fLOjO["Name"];
     $_POST["Name"] = $_8i88C;
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001415"], $_8i88C), $_Itfj8, 'textblocksedit', 'textblockedit.htm');
  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

  #### normal placeholders
  $_QLfol = "SELECT `text`, `fieldname` FROM $_Ij8oL WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
  $_QL8i1 = mysql_query($_QLfol);
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

  if(isset($_8i8fj) && is_array($_8i8fj) && count($_8i8fj) > 0) {
    $_8ifQ1 = $_8i8fj;
  } else
    $_8ifQ1 = array();

  $_8i8Oj = array();
  if(count($_8ifQ1) > 0) {
    # format for MarkFields
    $_8i8Oj["outputtext"] = $_8ifQ1["outputtext"];
    $_8i8Oj["outputtextplain"] = $_8ifQ1["outputtextplain"];
    $_8i8Oj["outputtext"] = FixCKEditorStyleProtectionForCSS($_8i8Oj["outputtext"]);
    if(isset($_8ifQ1["fieldname"]))
       $_POST["WithCondition"] = 1;
       else
        if(isset($_POST["WithCondition"]))
          unset($_POST["WithCondition"]);

    if(isset($_POST["WithCondition"])) {
      $_Qli6J=0;
      foreach($_8ifQ1["fieldname"] as $key => $_QltJO) {
          if(isset($_8ifQ1["logicalop"][$key]))
            $_8i8Oj["logicalop[$_Qli6J]"] = $_8ifQ1["logicalop"][$key];
            else
            $_8i8Oj["logicalop[$_Qli6J]"] = "";
          $_8i8Oj["fieldname[$_Qli6J]"] = $_8ifQ1["fieldname"][$key];
          $_8i8Oj["operator[$_Qli6J]"] = $_8ifQ1["operator"][$key];
          $_8i8Oj["comparestring[$_Qli6J]"] = htmlspecialchars( $_8ifQ1["comparestring"][$key], ENT_COMPAT, $_QLo06 );
          $_Qli6J++;
      }
    }
  }

  $_POST = array_merge($_POST, $_8i8Oj);
  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

  $_QLJfI = _L81BJ($_QLJfI, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
  $_QLJfI = _L81BJ($_QLJfI, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
  $_QLJfI = _L81BJ($_QLJfI, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
  $_QLJfI = _L81BJ($_QLJfI, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

  print $_QLJfI;
?>
