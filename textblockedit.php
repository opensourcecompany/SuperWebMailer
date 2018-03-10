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

  $_I0600 = "";

  if(isset($_POST["OneTextBlockId"]))
    $_j0Q1i = intval($_POST["OneTextBlockId"]);
    else
    if(isset($_GET["OneTextBlockId"]))
      $_j0Q1i = intval($_GET["OneTextBlockId"]);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if( (!isset($_j0Q1i) || $_j0Q1i == 0) && !$_QJojf["PrivilegeTextBlockCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if( (isset($_j0Q1i)) && !$_QJojf["PrivilegeTextBlockEdit"]) {
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
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001416"];
    } else if(strpos(trim($_POST["Name"]), "]") !== false || strpos(trim($_POST["Name"]), "[") !== false) {
        $errors[] = "Name";
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001417"];
      } else {
        $_QJCJi = trim($_POST["Name"]);
        for($_Q6llo=0; $_Q6llo<strlen($_QJCJi); $_Q6llo++)
          if( !preg_match("/^[a-zA-Z0-9_]{0,255}$/", $_QJCJi{$_Q6llo}) ) {
            $errors[] = "Name";
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001418"];
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

    if(count($errors) > 0 && $_I0600 == "")
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001419"];

    if (count($errors) == 0) {
       $_QJlJ0 = "SELECT COUNT(*) FROM $_I8tjl WHERE Name="._OPQLR(trim($_POST["Name"]));
       if(isset($_j0Q1i) && $_j0Q1i != "") {
         $_QJlJ0 .= " AND id<>".$_j0Q1i;
       }
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       mysql_free_result($_Q60l1);
       if($_Q6Q1C[0] > 0) {
         $errors[] = "Name";
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001416"];
       }

       $_QJlJ0 = "SELECT COUNT(*) FROM $_I88i8 WHERE Name="._OPQLR(trim($_POST["Name"]));
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       mysql_free_result($_Q60l1);
       if($_Q6Q1C[0] > 0) {
         $errors[] = "Name";
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001420"];
       }

     }

    if (count($errors) == 0) {
       $_fI6ji = array();

       if(   (!isset($_POST["logicalop"][1]) || $_POST["logicalop"][1] == "--") &&
            (!isset($_POST["logicalop"][2]) || $_POST["logicalop"][2] == "--")
          ) {

        if(isset($_POST["WithCondition"])) {
          $_fI6ji["fieldname"][0] = $_POST["fieldname"][0];
          $_fI6ji["operator"][0] = $_POST["operator"][0];
          $_fI6ji["comparestring"][0] = trim($_POST["comparestring"][0]);
         }

         $_fI6ji["outputtext"] = $_POST["outputtext"];
         $_fI6ji["outputtextplain"] = $_POST["outputtextplain"];
         $_fIfQi[] = $_fI6ji;
       } else {

         if(isset($_POST["WithCondition"])) {
           # skip not filled conditions
           $_Q6llo = 0;
           $_fI6ji["fieldname"][$_Q6llo] = $_POST["fieldname"][0];
           $_fI6ji["operator"][$_Q6llo] = $_POST["operator"][0];
           $_fI6ji["comparestring"][$_Q6llo] = trim($_POST["comparestring"][0]);

           if( isset($_POST["logicalop"][1]) && $_POST["logicalop"][1] != "--" ) {
             $_Q6llo++;
             $_fI6ji["logicalop"][$_Q6llo] = $_POST["logicalop"][1];
             $_fI6ji["fieldname"][$_Q6llo] = $_POST["fieldname"][1];
             $_fI6ji["operator"][$_Q6llo] = $_POST["operator"][1];
             $_fI6ji["comparestring"][$_Q6llo] = trim($_POST["comparestring"][1]);
           }

           if( isset($_POST["logicalop"][2]) && $_POST["logicalop"][2] != "--" ) {
             $_Q6llo++;
             $_fI6ji["logicalop"][$_Q6llo] = $_POST["logicalop"][2];
             $_fI6ji["fieldname"][$_Q6llo] = $_POST["fieldname"][2];
             $_fI6ji["operator"][$_Q6llo] = $_POST["operator"][2];
             $_fI6ji["comparestring"][$_Q6llo] = trim($_POST["comparestring"][2]);
           }
         }

         $_fI6ji["outputtext"] = $_POST["outputtext"];
         $_fI6ji["outputtextplain"] = $_POST["outputtextplain"];
         $_fIfQi[] = $_fI6ji;

       }

       if(isset($_j0Q1i) && $_j0Q1i != "")
          $_QJlJ0 = "UPDATE `$_I8tjl` SET `Name`="._OPQLR($_POST["Name"]).", `textblocktext`="._OPQLR( base64_encode(serialize($_fI6ji)) )." WHERE id=".$_j0Q1i;
          else
          $_QJlJ0 = "INSERT INTO `$_I8tjl` SET `Name`="._OPQLR($_POST["Name"]).", `CreateDate`=NOW(), `textblocktext`="._OPQLR( base64_encode(serialize($_fI6ji)) );

       mysql_query($_QJlJ0);
       _OAL8F($_QJlJ0);

       if(!isset($_j0Q1i)){
          $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
          $_Q6Q1C=mysql_fetch_array($_Q60l1);
          $_j0Q1i = $_Q6Q1C[0];
          mysql_free_result($_Q60l1);
       }

       include_once("browsetextblocks.php");
       exit;

    }

  }

  // get textblock
  if(isset($_j0Q1i) && $_j0Q1i != "") {
    $_QJlJ0 = "SELECT * FROM $_I8tjl WHERE id=".intval($_j0Q1i);
    $_Q60l1 = mysql_query($_QJlJ0);
    $_6f0oC = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if($_6f0oC["textblocktext"] != "") {
        $_fIfQi = @unserialize( base64_decode($_6f0oC["textblocktext"]) );
        if($_fIfQi === false)
          $_fIfQi = array();
      }
      else
      $_fIfQi = array();
  } else
    $_fIfQi = array(); // new

  // Template
  if (isset($_j0Q1i))
    $_POST["OneTextBlockId"] = intval($_j0Q1i);
  $_fIfCO = "";
  if(isset($_6f0oC["Name"])) {
     $_fIfCO = $_6f0oC["Name"];
     $_POST["Name"] = $_fIfCO;
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001415"], $_fIfCO), $_I0600, 'textblocksedit', 'textblockedit.htm');
  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

  #### normal placeholders
  $_QJlJ0 = "SELECT `text`, `fieldname` FROM $_Qofjo WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
  $_Q60l1 = mysql_query($_QJlJ0);
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

  if(isset($_fIfQi) && is_array($_fIfQi) && count($_fIfQi) > 0) {
    $_fI6ji = $_fIfQi;
  } else
    $_fI6ji = array();

  $_fIfiI = array();
  if(count($_fI6ji) > 0) {
    # format for MarkFields
    $_fIfiI["outputtext"] = $_fI6ji["outputtext"];
    $_fIfiI["outputtextplain"] = $_fI6ji["outputtextplain"];
    $_fIfiI["outputtext"] = FixCKEditorStyleProtectionForCSS($_fIfiI["outputtext"]);
    if(isset($_fI6ji["fieldname"]))
       $_POST["WithCondition"] = 1;
       else
        if(isset($_POST["WithCondition"]))
          unset($_POST["WithCondition"]);

    if(isset($_POST["WithCondition"])) {
      $_Q6llo=0;
      foreach($_fI6ji["fieldname"] as $key => $_Q6ClO) {
          if(isset($_fI6ji["logicalop"][$key]))
            $_fIfiI["logicalop[$_Q6llo]"] = $_fI6ji["logicalop"][$key];
            else
            $_fIfiI["logicalop[$_Q6llo]"] = "";
          $_fIfiI["fieldname[$_Q6llo]"] = $_fI6ji["fieldname"][$key];
          $_fIfiI["operator[$_Q6llo]"] = $_fI6ji["operator"][$key];
          $_fIfiI["comparestring[$_Q6llo]"] = htmlspecialchars( $_fI6ji["comparestring"][$key], ENT_COMPAT, "UTF-8" );
          $_Q6llo++;
      }
    }
  }

  $_POST = array_merge($_POST, $_fIfiI);
  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
  $_QJCJi = _OPR6L($_QJCJi, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
  $_QJCJi = _OPR6L($_QJCJi, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
  $_QJCJi = _OPR6L($_QJCJi, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

  print $_QJCJi;
?>
