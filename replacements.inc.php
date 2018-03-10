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
  include_once("functions.inc.php");
  include_once("targetgroups.inc.php");

  // SpecialPlaceholders = array ([key] => value )
  function _L1ERL($_IiJo8, $_66l1J, $_I11oJ, $charset, $_JIO08, $_JiiQJ) {
    global $_IIQI8, $_Q6QQL, $resourcestrings, $INTERFACE_LANGUAGE, $_Q6JJJ;

    if(isset($_IiJo8) && is_array($_IiJo8) ) {
      if(!isset($_IiJo8["PersonalizeEMails"]))
         $_IiJo8["PersonalizeEMails"] = 1;
    }

    // query functions
    $_I11oJ = _L1FJ0($_IiJo8, $_I11oJ, $charset, $_JIO08);

    // text blocks
    $_I11oJ = _LQ0LB($_IiJo8, $_I11oJ, $charset, $_JIO08);

    // target groups
    if($_JIO08){
      $_I11oJ = _LQ0EE($_IiJo8, $_I11oJ, $charset, $_JIO08);
    }

    // default placeholders
    reset($_IIQI8);
    foreach($_IIQI8 as $_QllO8 => $key) {
     if(stripos($_I11oJ, $key) === false) continue;
     $_Q6ClO = "";
     switch ($key) {
       case '[Date_short]':
          if($INTERFACE_LANGUAGE == "de")
           $_Q6ClO = strftime("%d.%m.%Y");
          else
           $_Q6ClO = strftime("%m/%d/%Y");
          break;
       case '[Date_long]':
          $_Q6ClO = strftime("%c");
          break;
       case '[Year]':
           $_Q6ClO = strftime("%Y");
          break;
       case '[Month]':
           $_Q6ClO = strftime("%m");
          break;
       case '[Day]':
           $_Q6ClO = strftime("%d");
          break;
       case '[WeekNumber]':
           $_Q6ClO = strftime("%W");
          break;
       case '[Time]':
          $_Q6ClO = strftime("%H:%M:%S");
          break;
       case '[Hour]':
          $_Q6ClO = strftime("%H");
          break;
       case '[Minute]':
          $_Q6ClO = strftime("%M");
          break;
       case '[Second]':
          $_Q6ClO = strftime("%S");
          break;
       case '[RecipientId]':
          if(isset($_IiJo8["id"]))
             $_Q6ClO = $_IiJo8["id"];
          break;
       case '[MailingListId]':
          $_Q6ClO = $_66l1J;
          break;
       case '[SubscriptionStatus]':
          if(isset($_IiJo8["SubscriptionStatus"])) {
            if($_IiJo8["SubscriptionStatus"] == 'OptInConfirmationPending')
               $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["000048"];
            if($_IiJo8["SubscriptionStatus"] == 'Subscribed')
               $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["000049"];
            if($_IiJo8["SubscriptionStatus"] == 'OptOutConfirmationPending')
               $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["000050"];
          }
          break;
       case '[IPOnSubscription]':
          if(isset($_IiJo8["IPOnSubscription"])) {
            $_Q6ClO = $_IiJo8["IPOnSubscription"];
            if($_Q6ClO == "manual" || $_Q6ClO == $resourcestrings[$INTERFACE_LANGUAGE]["000047"]) {
               $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["000047"];
            }
          }
          break;
       case '[DateOfSubscription]':
          if(isset($_IiJo8["DateOfSubscription"])) {
            $_Q6ClO = _OAQOB($_IiJo8["DateOfSubscription"], $INTERFACE_LANGUAGE);
          }
          break;
       case '[DateOfOptInConfirmation]':
          if(isset($_IiJo8["DateOfOptInConfirmation"])) {
            $_Q6ClO = _OAQOB($_IiJo8["DateOfOptInConfirmation"], $INTERFACE_LANGUAGE);
          }
          break;
       case '[LastEMailSent]':
          if(isset($_IiJo8["LastEMailSent"])) {
            $_Q6ClO = _OAQOB($_IiJo8["LastEMailSent"], $INTERFACE_LANGUAGE);
          }
          break;
     }
     $_I11oJ = str_ireplace("$key", $_Q6ClO, $_I11oJ);
    }

    // SpecialPlaceholders
    if(isset($_JiiQJ) && is_array($_JiiQJ) ) {
      reset($_JiiQJ);
      foreach($_JiiQJ as $key => $_Q6ClO) {
       $_I11oJ = str_ireplace($key, $_Q6ClO, $_I11oJ);
      }
    }

    if(isset($_IiJo8) && is_array($_IiJo8) ) {
      reset($_IiJo8);
      if($_IiJo8["PersonalizeEMails"]) {
        foreach($_IiJo8 as $key => $_Q6ClO) {
         if($_Q6ClO == "NULL")
           $_Q6ClO = "";

         if($key == "u_Gender"){
           if($_Q6ClO == "undefined")
             $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["undefined"];
           else
           if($_Q6ClO == "m")
             $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["man"];
           else
           if($_Q6ClO == "w")
             $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["woman"];
         }

         if($key == "SubscriptionStatus"){
           if(isset($resourcestrings[$INTERFACE_LANGUAGE]["Member$_Q6ClO"]))
              $_Q6ClO = $resourcestrings[$INTERFACE_LANGUAGE]["Member$_Q6ClO"];
              else
              $_Q6ClO = "";
         }

         $_I11oJ = str_ireplace("[$key]", $_Q6ClO, $_I11oJ);
        }
      }
    }
    $_I11oJ = trim($_I11oJ);

    // query functions and text blocks again
    if(strpos($_I11oJ, "[") !== false) {
       $_I11oJ = _L1FJ0($_IiJo8, $_I11oJ, $charset, $_JIO08);
       //
       $_I11oJ = _LQ0LB($_IiJo8, $_I11oJ, $charset, $_JIO08);
    }

    // *******************************************************

    // not utf-8?
    if( strtoupper($charset) != "UTF-8" ) {
       $_JIO8t = ConvertString("UTF-8", $charset, $_I11oJ, $_JIO08);
       if($_JIO8t != "")
         $_I11oJ = $_JIO8t;
    }

    // remove entities from PlainText AFTER converting FROM UTF-8 to charset
    if(!$_JIO08) {
      $_JIO8t = unhtmlentities($_I11oJ, $charset);
      if($_JIO8t != $_I11oJ) {
        $_I11oJ = $_JIO8t;
      }
    }
    // remove entities from PlainText /

    if($_JIO08) {
       $_I11oJ = SetHTMLCharSet($_I11oJ, $charset);

       // Spam protection
       $_I11oJ = str_ireplace("<tbody>", "", $_I11oJ);
       $_I11oJ = str_ireplace("</tbody>", "", $_I11oJ);
       $_I11oJ = str_ireplace("<thead>", "", $_I11oJ);
       $_I11oJ = str_ireplace("</thead>", "", $_I11oJ);
       $_I11oJ = str_ireplace("<tfoot>", "", $_I11oJ);
       $_I11oJ = str_ireplace("</tfoot>", "", $_I11oJ);
       $_I11oJ = str_ireplace("FrontPage.Editor.Document", "", $_I11oJ);
       $_I11oJ = str_ireplace("Microsoft FrontPage 4.0", "", $_I11oJ);
       $_I11oJ = str_ireplace("Microsoft FrontPage 12.0", "", $_I11oJ);
    }
    if(!$_JIO08) {
      $_I11oJ = str_ireplace("<br>", $_Q6JJJ, $_I11oJ);
      $_I11oJ = str_ireplace("<br/>", $_Q6JJJ, $_I11oJ);
      $_I11oJ = str_ireplace("<br />", $_Q6JJJ, $_I11oJ);
    }

    return $_I11oJ;
  }

  function _L1FJ0($_IiJo8, $_I11oJ, $charset, $_JIO08) {
    global $_I88i8, $_Q61I1;

    if($_I88i8 == "") return $_I11oJ;
    if(!$_IiJo8["PersonalizeEMails"]) return $_I11oJ;

    if(!isset($_IiJo8["id"]) && isset($_IiJo8["RecipientId"]))
      $_IiJo8["id"] = $_IiJo8["RecipientId"];
      else
      if(!isset($_IiJo8["id"]))
        $_IiJo8["id"] = 0;

    $_QJlJ0 = "SELECT `Name`, `functiontext` FROM `$_I88i8`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
       if($_Q6Q1C["functiontext"] == "") continue;
       if(stripos($_I11oJ, "[".$_Q6Q1C["Name"]."]") !== false) {
         $_I8tii = @unserialize($_Q6Q1C["functiontext"]);
         if($_I8tii === false)
           $_I8tii = array();
         $_I8C10 = false;
         $_66lLl = "";
         for($_Q6llo=0; $_Q6llo<count($_I8tii); $_Q6llo++) {
           $_I8C10 = _LQ0JQ($_IiJo8, $_I8tii[$_Q6llo], $_66lLl, $_JIO08);
           if($_I8C10) break;
         }

         if($_I8C10) {
           $_I11oJ = str_ireplace("[".$_Q6Q1C["Name"]."]", $_66lLl, $_I11oJ);
         }
           else
             $_I11oJ = str_ireplace("[".$_Q6Q1C["Name"]."]", "", $_I11oJ); // simple replace it with nothing
       }
    }
    mysql_free_result($_Q60l1);

    return $_I11oJ;
  }

  function _LQ0LB($_IiJo8, $_I11oJ, $charset, $_JIO08) {
    global $_I8tjl, $_Q61I1;

    if($_I8tjl == "") return $_I11oJ;
    if(!$_IiJo8["PersonalizeEMails"]) return $_I11oJ;

    if(!isset($_IiJo8["id"]) && isset($_IiJo8["RecipientId"]))
      $_IiJo8["id"] = $_IiJo8["RecipientId"];
      else
      if(!isset($_IiJo8["id"]))
        $_IiJo8["id"] = 0;

    $_QJlJ0 = "SELECT `Name`, `textblocktext` FROM `$_I8tjl`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
       if($_Q6Q1C["textblocktext"] == "") continue;
       if(stripos($_I11oJ, "[".$_Q6Q1C["Name"]."]") !== false) {
         $_6f0oC = @unserialize( base64_decode($_Q6Q1C["textblocktext"]) );
         if($_6f0oC === false)
           $_6f0oC = array();
         $_I8C10 = false;
         $_66lLl = "";
         if( isset($_6f0oC["fieldname"]) )
            $_I8C10 = _LQ0JQ($_IiJo8, $_6f0oC, $_66lLl, $_JIO08);
         if( !isset($_6f0oC["fieldname"]) ) {
           $_I8C10 = true;
           if($_JIO08)
             $_66lLl = $_6f0oC["outputtext"];
             else
             $_66lLl = $_6f0oC["outputtextplain"];
         }

         if($_I8C10) {
           $_I11oJ = str_ireplace("[".$_Q6Q1C["Name"]."]", $_66lLl, $_I11oJ);
         }
           else
             $_I11oJ = str_ireplace("[".$_Q6Q1C["Name"]."]", "", $_I11oJ); // simple replace it with nothing
       }
    }
    mysql_free_result($_Q60l1);

    return $_I11oJ;
  }

  function _LQ0JQ($_IiJo8, $_I8C10, &$_66lLl, $_JIO08 = true) {

    if(!is_array($_I8C10["fieldname"])) {
      $_I8C10["logicalop"] = array("--");
      $_I8C10["fieldname"] = array($_I8C10["fieldname"]);
      $_I8C10["operator"] = array($_I8C10["operator"]);
      $_I8C10["comparestring"] = array($_I8C10["comparestring"]);
    }

    if(isset($_I8C10["outputtext"]))
      $_66lLl = $_I8C10["outputtext"];
      else
      $_66lLl = "";

    if(!$_JIO08 && isset($_I8C10["outputtextplain"])) # text blocks
       $_66lLl = $_I8C10["outputtextplain"];

    # works others as in delphi
    $_6f1o0 = _LQ0CO($_IiJo8, $_I8C10, 0);
    for($_Q6llo=1; $_Q6llo<count($_I8C10["fieldname"]); $_Q6llo++) {
      if( $_I8C10["logicalop"][$_Q6llo] == "AND" ) {
           if(!$_6f1o0) continue; // AND is ever false if one is false
           $_QllO8 = _LQ0CO($_IiJo8, $_I8C10, $_Q6llo);
           if( !($_6f1o0 == true && $_QllO8 == true) )
             $_6f1o0 = false;
         }
         else {
           if($_6f1o0) continue; // OR is ever true if one is true
           $_QllO8 = _LQ0CO($_IiJo8, $_I8C10, $_Q6llo);
           if($_6f1o0 == true || $_QllO8 == true)
              $_6f1o0 = true;
         }
    }

    if($_6f1o0)
      return true;

    $_66lLl = "";
    return false;
  }

  function _LQ0CO($_IiJo8, $_I8C10, $_6fQ1O) {

    if(strpos($_I8C10["operator"][$_6fQ1O], "_num") === false)
      $_Q6ClO = $_IiJo8[$_I8C10["fieldname"][$_6fQ1O]];
      else
      $_Q6ClO = $_IiJo8[$_I8C10["fieldname"][$_6fQ1O]];
    $_Q6ClO = strtolower($_Q6ClO);
    $_6fQCL = intval($_Q6ClO);

    switch($_I8C10["operator"][$_6fQ1O]) {
      case "eq":
           return strtolower( $_I8C10["comparestring"][$_6fQ1O] ) == $_Q6ClO;
           break;
      case "neq":
           return strtolower( $_I8C10["comparestring"][$_6fQ1O] ) != $_Q6ClO;
           break;
      case "lt":
           return strtolower( $_I8C10["comparestring"][$_6fQ1O] ) < $_Q6ClO;
           break;
      case "gt":
           return strtolower( $_I8C10["comparestring"][$_6fQ1O] ) > $_Q6ClO;
           break;
      case "eq_num":
           return $_6fQCL == intval($_I8C10["comparestring"][$_6fQ1O]);
           break;
      case "neq_num":
           return $_6fQCL != intval($_I8C10["comparestring"][$_6fQ1O]);
           break;
      case "lt_num":
           return $_6fQCL < intval($_I8C10["comparestring"][$_6fQ1O]);
           break;
      case "gt_num":
           return $_6fQCL > intval($_I8C10["comparestring"][$_6fQ1O]);
           break;
      case "contains":
           return stripos($_Q6ClO, $_I8C10["comparestring"][$_6fQ1O]) !== false;
           break;
      case "contains_not":
           return stripos($_Q6ClO, $_I8C10["comparestring"][$_6fQ1O]) === false;
           break;
      case "starts_with":
           $_Q6i6i = stripos($_Q6ClO, $_I8C10["comparestring"][$_6fQ1O]);
           if($_Q6i6i !== false) {
             return $_Q6i6i == 0;
           }
           break;
      case "REGEXP":
           return preg_match($_I8C10["comparestring"][$_6fQ1O], $_Q6ClO);
           break;
    }

    return false;
  }

  function _LQ0EE($_IiJo8, $_I11oJ, $charset, $_JIO08) {
    global $_Q6C0i, $_Q61I1;

    if($_Q6C0i == "") return $_I11oJ;
    if(!$_IiJo8["PersonalizeEMails"]) return $_I11oJ;

    $_j1L1C = array();
    _LJO8O($_I11oJ, $_j1L1C);
    if(!count($_j1L1C)) return $_I11oJ;

    if(!isset($_IiJo8["id"]) && isset($_IiJo8["RecipientId"]))
      $_IiJo8["id"] = $_IiJo8["RecipientId"];
      else
      if(!isset($_IiJo8["id"]))
        $_IiJo8["id"] = 0;

    $_QJlJ0 = "SELECT `Name`, `functiontext` FROM `$_Q6C0i`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
       if($_Q6Q1C["functiontext"] == "") continue;

       $_Q6i6i = array_searchi($_Q6Q1C["Name"], $_j1L1C);
       if($_Q6i6i !== false) {
         $_I8tii = @unserialize( $_Q6Q1C["functiontext"] );
         if($_I8tii === false)
           $_I8tii = array();
         $_I8C10 = false;
         $_66lLl = "";
         $_I8C10 = _LQ0JQ($_IiJo8, $_I8tii, $_66lLl, $_JIO08);

         if(!$_I8C10) {
           unset($_j1L1C[$_Q6i6i]);
         }
       }
    }
    mysql_free_result($_Q60l1);

    $_Q6llo = 0;
    $_I11oJ = _LJLCA($_I11oJ, $_j1L1C, false, $_Q6llo);

    return $_I11oJ;
  }

  if(!function_exists("array_searchi")) {
    function array_searchi($_ILooj, $_ILo0C) {
      return array_search(strtolower($_ILooj),array_map('strtolower',$_ILo0C));
    }
  }

?>
