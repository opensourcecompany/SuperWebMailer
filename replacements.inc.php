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
  include_once("functions.inc.php");
  include_once("targetgroups.inc.php");

  $_fLfJI = array('[Year]', '[Month]', '[Day]', '[WeekNumber]', '[Hour]', '[Minute]', '[Second]', '[LastEMailSent]', '[DayName]', '[MonthName]');
  
  // SpecialPlaceholders = array ([key] => value )
  function _J1EBE($_jf8if, $_fLfOf, $_IO08l, $charset, $_6JC6j, $_fILtI) {
    global $_Iol8t, $_QLo06, $resourcestrings, $INTERFACE_LANGUAGE, $_QLl1Q, $_fLfJI;
    global $ShortDateFormat, $LongDateFormat, $DayNumToDayName, $MonthNumToMonthName;

    if(isset($_jf8if) && is_array($_jf8if) ) {
      if(!isset($_jf8if["PersonalizeEMails"]))
         $_jf8if["PersonalizeEMails"] = 1;
    }

    // query functions
    $_IO08l = _J1FRF($_jf8if, $_IO08l, $charset, $_6JC6j);

    // text blocks
    $_IO08l = _J1FC6($_jf8if, $_IO08l, $charset, $_6JC6j);

    // target groups
    if($_6JC6j){
      $_IO08l = _JQ1R6($_jf8if, $_IO08l, $charset, $_6JC6j);
    }

    // default placeholders
    reset($_Iol8t);
    foreach($_Iol8t as $_I016j => $key) {
     if(stripos($_IO08l, $key) === false) continue;
     $_QltJO = "";
     switch ($key) {
       case '[Date_short]':
          $_QltJO = date($ShortDateFormat);  
          break;
       case '[Date_long]':
          $_QltJO = date($LongDateFormat);
          break;
       case '[Time]':
          $_QltJO = date("H:i:s");
          break;
       case '[RecipientId]':
          if(isset($_jf8if["id"]))
             $_QltJO = $_jf8if["id"];
          break;
       case '[MailingListId]':
          $_QltJO = $_fLfOf;
          break;
       case '[SubscriptionStatus]':
          if(isset($_jf8if["SubscriptionStatus"])) {
            if($_jf8if["SubscriptionStatus"] == 'OptInConfirmationPending')
               $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["000048"];
            if($_jf8if["SubscriptionStatus"] == 'Subscribed')
               $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["000049"];
            if($_jf8if["SubscriptionStatus"] == 'OptOutConfirmationPending')
               $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["000050"];
          }
          break;
       case '[IPOnSubscription]':
          if(isset($_jf8if["IPOnSubscription"])) {
            $_QltJO = $_jf8if["IPOnSubscription"];
            if($_QltJO == "manual" || $_QltJO == $resourcestrings[$INTERFACE_LANGUAGE]["000047"]) {
               $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["000047"];
            }
          }
          break;
       case '[DateOfSubscription]':
          if(isset($_jf8if["DateOfSubscription"])) {
            $_QltJO = _L8BCR($_jf8if["DateOfSubscription"], $INTERFACE_LANGUAGE);
          }
          break;
       case '[DateOfOptInConfirmation]':
          if(isset($_jf8if["DateOfOptInConfirmation"])) {
            $_QltJO = _L8BCR($_jf8if["DateOfOptInConfirmation"], $INTERFACE_LANGUAGE);
          }
          break;
       case '[EMail_LocalPart]':
          if(!$_jf8if["PersonalizeEMails"] || !isset($_jf8if["u_EMail"])){
            $_QltJO = "";
            break;
          }
          $_I016j = explode('@', $_jf8if["u_EMail"], 2); $_QltJO = $_I016j[0]; // PHP 5.3 doesn't support explode()[0]
          break;
       case '[EMail_DomainPart]':
          if(!$_jf8if["PersonalizeEMails"] || !isset($_jf8if["u_EMail"])){
            $_QltJO = "";
            break;
          }
          $_I016j = explode('@', $_jf8if["u_EMail"], 2); $_QltJO = $_I016j[1]; // PHP 5.3 doesn't support explode()[1]
          break;   
     }
     $_IO08l = str_ireplace("$key", $_QltJO, $_IO08l);
    }

    reset($_fLfJI);
    foreach($_fLfJI as $_I016j => $key) {
     if(stripos($_IO08l, $key) === false) continue;
     $_QltJO = "";
     switch ($key) {
       case '[Year]':
           $_QltJO = date("Y");
          break;
       case '[Month]':
           $_QltJO = date("m");
          break;
       case '[Day]':
           $_QltJO = date("d");
          break;
       case '[WeekNumber]':
           $_QltJO = date("W");
          break;
       case '[Hour]':
          $_QltJO = date("H");
          break;
       case '[Minute]':
          $_QltJO = date("i");
          break;
       case '[Second]':
          $_QltJO = date("s");
          break;
       case '[LastEMailSent]':
          if(isset($_jf8if["LastEMailSent"])) {
            $_QltJO = _L8BCR($_jf8if["LastEMailSent"], $INTERFACE_LANGUAGE);
          }
          break;
       case '[DayName]':                                                
         $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE][$DayNumToDayName[intval(date("N")) - 1]];
         break;
       case '[MonthName]':
         $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE][$MonthNumToMonthName[intval(date("m"))]];
         break;
     }
     $_IO08l = str_ireplace("$key", $_QltJO, $_IO08l);
    }

    // SpecialPlaceholders
    if(isset($_fILtI) && is_array($_fILtI) ) {
      reset($_fILtI);
      foreach($_fILtI as $key => $_QltJO) {
        if($_QltJO == "NULL" || !isset($_QltJO) || $_QltJO == NULL)
          $_QltJO = "";
        $_IO08l = str_ireplace($key, $_QltJO, $_IO08l);
      }
    }

    if(isset($_jf8if) && is_array($_jf8if) ) {
      reset($_jf8if);
      if($_jf8if["PersonalizeEMails"]) {
        foreach($_jf8if as $key => $_QltJO) {
         if($_QltJO == "NULL" || !isset($_QltJO) || $_QltJO == NULL)
           $_QltJO = "";

         if($key == "u_Gender"){
           if($_QltJO == "undefined")
             $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["undefined"];
           else
           if($_QltJO == "m")
             $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["man"];
           else
           if($_QltJO == "w")
             $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["woman"];
           else
           if($_QltJO == "d")
             $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["diverse"];
         }

         if($key == "SubscriptionStatus"){
           if(isset($resourcestrings[$INTERFACE_LANGUAGE]["Member$_QltJO"]))
              $_QltJO = $resourcestrings[$INTERFACE_LANGUAGE]["Member$_QltJO"];
              else
              $_QltJO = "";
         }

         if($key == "u_Birthday"){
            $_QltJO = _L8BCR($_jf8if["u_Birthday"], $INTERFACE_LANGUAGE);
         }

         $_IO08l = str_ireplace("[$key]", $_QltJO, $_IO08l);
        }
      }
    }
    $_IO08l = trim($_IO08l);

    // query functions and text blocks again
    if(strpos($_IO08l, "[") !== false) {
       $_IO08l = _J1FRF($_jf8if, $_IO08l, $charset, $_6JC6j);
       //
       $_IO08l = _J1FC6($_jf8if, $_IO08l, $charset, $_6JC6j);
    }

    // *******************************************************
    
    // not utf-8?
    if( strtoupper($charset) != "UTF-8" ) {
       $_6JiJ6 = ConvertString("UTF-8", $charset, $_IO08l, $_6JC6j);
       if($_6JiJ6 != "")
         $_IO08l = $_6JiJ6;
    }

    // remove entities from PlainText AFTER converting FROM UTF-8 to charset
    if(!$_6JC6j) {
      $_6JiJ6 = unhtmlentities($_IO08l, $charset);
      if($_6JiJ6 != $_IO08l) {
        $_IO08l = $_6JiJ6;
      }
    }
    // remove entities from PlainText /
    
    /* lower email size without decoding emojis or e.g. cyrillic texts
    if($_6JC6j && strtoupper($charset) == "UTF-8"){
      $_IO08l = _LCRC8($_IO08l);
    }
    */
    

    // execute internal functions 
    $internalFunctions = new TinternalFunctions();
    $_IO08l = $internalFunctions->_JQQE8($_IO08l, $charset, $_6JC6j);
    $internalFunctions = null;

    if($_6JC6j) {
       $_IO08l = SetHTMLCharSet($_IO08l, $charset);

       // Spam protection
       $_IO08l = str_ireplace("<tbody>", "", $_IO08l);
       $_IO08l = str_ireplace("</tbody>", "", $_IO08l);
       $_IO08l = str_ireplace("<thead>", "", $_IO08l);
       $_IO08l = str_ireplace("</thead>", "", $_IO08l);
       $_IO08l = str_ireplace("<tfoot>", "", $_IO08l);
       $_IO08l = str_ireplace("</tfoot>", "", $_IO08l);
       $_IO08l = str_ireplace("FrontPage.Editor.Document", "", $_IO08l);
       $_IO08l = str_ireplace("Microsoft FrontPage 4.0", "", $_IO08l);
       $_IO08l = str_ireplace("Microsoft FrontPage 12.0", "", $_IO08l);
    }
    if(!$_6JC6j) {
      $_IO08l = str_ireplace("<br>", $_QLl1Q, $_IO08l);
      $_IO08l = str_ireplace("<br/>", $_QLl1Q, $_IO08l);
      $_IO08l = str_ireplace("<br />", $_QLl1Q, $_IO08l);
    }

    return $_IO08l;
  }

  function _J1FRF($_jf8if, $_IO08l, $charset, $_6JC6j) {
    global $_jQ68I, $_QLttI;

    if( !isset($_jQ68I) || $_jQ68I == "") return $_IO08l;
    if(!$_jf8if["PersonalizeEMails"]) return $_IO08l;

    if(!isset($_jf8if["id"]) && isset($_jf8if["RecipientId"]))
      $_jf8if["id"] = $_jf8if["RecipientId"];
      else
      if(!isset($_jf8if["id"]))
        $_jf8if["id"] = 0;

    $_QLfol = "SELECT `Name`, `functiontext` FROM `$_jQ68I`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
       if($_QLO0f["functiontext"] == "") continue;
       if(stripos($_IO08l, "[".$_QLO0f["Name"]."]") !== false) {
         $_jQ8J6 = @unserialize($_QLO0f["functiontext"]);
         if($_jQ8J6 === false)
           $_jQ8J6 = array();
         $_jQoQi = false;
         $_fLtJ0 = "";
         for($_Qli6J=0; $_Qli6J<count($_jQ8J6); $_Qli6J++) {
           $_jQoQi = _JQ06L($_jf8if, $_jQ8J6[$_Qli6J], $_fLtJ0, $_6JC6j);
           if($_jQoQi) break;
         }

         if($_jQoQi) {
           $_IO08l = str_ireplace("[".$_QLO0f["Name"]."]", $_fLtJ0, $_IO08l);
         }
           else
             $_IO08l = str_ireplace("[".$_QLO0f["Name"]."]", "", $_IO08l); // simple replace it with nothing
       }
    }
    mysql_free_result($_QL8i1);

    return $_IO08l;
  }

  function _J1FC6($_jf8if, $_IO08l, $charset, $_6JC6j) {
    global $_jQf81, $_QLttI;

    if(!isset($_jQf81) || $_jQf81 == "") return $_IO08l;
    if(!$_jf8if["PersonalizeEMails"]) return $_IO08l;

    if(!isset($_jf8if["id"]) && isset($_jf8if["RecipientId"]))
      $_jf8if["id"] = $_jf8if["RecipientId"];
      else
      if(!isset($_jf8if["id"]))
        $_jf8if["id"] = 0;

    $_QLfol = "SELECT `Name`, `textblocktext` FROM `$_jQf81`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
       if($_QLO0f["textblocktext"] == "") continue;
       if(stripos($_IO08l, "[".$_QLO0f["Name"]."]") !== false) {
         $_fLOjO = @unserialize( base64_decode($_QLO0f["textblocktext"]) );
         if($_fLOjO === false)
           $_fLOjO = array();
         $_jQoQi = false;
         $_fLtJ0 = "";
         if( isset($_fLOjO["fieldname"]) )
            $_jQoQi = _JQ06L($_jf8if, $_fLOjO, $_fLtJ0, $_6JC6j);
         if( !isset($_fLOjO["fieldname"]) ) {
           $_jQoQi = true;
           if($_6JC6j)
             $_fLtJ0 = $_fLOjO["outputtext"];
             else
             $_fLtJ0 = $_fLOjO["outputtextplain"];
         }

         if($_jQoQi) {
           $_IO08l = str_ireplace("[".$_QLO0f["Name"]."]", $_fLtJ0, $_IO08l);
         }
           else
             $_IO08l = str_ireplace("[".$_QLO0f["Name"]."]", "", $_IO08l); // simple replace it with nothing
       }
    }
    mysql_free_result($_QL8i1);

    return $_IO08l;
  }

  function _JQ06L($_jf8if, $_jQoQi, &$_fLtJ0, $_6JC6j = true) {

    if(!is_array($_jQoQi["fieldname"])) {
      $_jQoQi["logicalop"] = array("--");
      $_jQoQi["fieldname"] = array($_jQoQi["fieldname"]);
      $_jQoQi["operator"] = array($_jQoQi["operator"]);
      $_jQoQi["comparestring"] = array($_jQoQi["comparestring"]);
    }

    if(isset($_jQoQi["outputtext"]))
      $_fLtJ0 = $_jQoQi["outputtext"];
      else
      $_fLtJ0 = "";

    if(!$_6JC6j && isset($_jQoQi["outputtextplain"])) # text blocks
       $_fLtJ0 = $_jQoQi["outputtextplain"];

    # works others as in delphi
    $_fLOO1 = _JQ0F8($_jf8if, $_jQoQi, 0);
    for($_Qli6J=1; $_Qli6J<count($_jQoQi["fieldname"]); $_Qli6J++) {
      if( $_jQoQi["logicalop"][$_Qli6J] == "AND" ) {
           if(!$_fLOO1) continue; // AND is ever false if one is false
           $_I016j = _JQ0F8($_jf8if, $_jQoQi, $_Qli6J);
           if( !($_fLOO1 == true && $_I016j == true) )
             $_fLOO1 = false;
         }
         else {
           if($_fLOO1) continue; // OR is ever true if one is true
           $_I016j = _JQ0F8($_jf8if, $_jQoQi, $_Qli6J);
           if($_fLOO1 == true || $_I016j == true)
              $_fLOO1 = true;
         }
    }

    if($_fLOO1)
      return true;

    $_fLtJ0 = "";
    return false;
  }

  function _JQ0F8($_jf8if, $_jQoQi, $_Jto6L) {

    if(strpos($_jQoQi["operator"][$_Jto6L], "_num") === false)
      $_QltJO = $_jf8if[$_jQoQi["fieldname"][$_Jto6L]];
      else
      $_QltJO = $_jf8if[$_jQoQi["fieldname"][$_Jto6L]];
    if(!isset($_QltJO))  
      $_QltJO = "";
    $_QltJO = strtolower($_QltJO);
    $_fLOC8 = intval($_QltJO);

    switch($_jQoQi["operator"][$_Jto6L]) {
      case "eq":
           return strtolower( $_jQoQi["comparestring"][$_Jto6L] ) == $_QltJO;
           break;
      case "neq":
           return strtolower( $_jQoQi["comparestring"][$_Jto6L] ) != $_QltJO;
           break;
      case "lt":
           return strtolower( $_jQoQi["comparestring"][$_Jto6L] ) < $_QltJO;
           break;
      case "gt":
           return strtolower( $_jQoQi["comparestring"][$_Jto6L] ) > $_QltJO;
           break;
      case "eq_num":
           return $_fLOC8 == intval($_jQoQi["comparestring"][$_Jto6L]);
           break;
      case "neq_num":
           return $_fLOC8 != intval($_jQoQi["comparestring"][$_Jto6L]);
           break;
      case "lt_num":
           return $_fLOC8 < intval($_jQoQi["comparestring"][$_Jto6L]);
           break;
      case "gt_num":
           return $_fLOC8 > intval($_jQoQi["comparestring"][$_Jto6L]);
           break;
      case "contains":
           return stripos($_QltJO, $_jQoQi["comparestring"][$_Jto6L]) !== false;
           break;
      case "contains_not":
           return stripos($_QltJO, $_jQoQi["comparestring"][$_Jto6L]) === false;
           break;
      case "starts_with":
           $_QlOjt = stripos($_QltJO, $_jQoQi["comparestring"][$_Jto6L]);
           if($_QlOjt !== false) {
             return $_QlOjt == 0;
           }
           break;
      case "REGEXP":
           return preg_match($_jQoQi["comparestring"][$_Jto6L], $_QltJO);
           break;
    }

    return false;
  }

  function _JQ1R6($_jf8if, $_IO08l, $charset, $_6JC6j) {
    global $_QlfCL, $_QLttI;

    if(!isset($_QlfCL) || $_QlfCL == "") return $_IO08l;
    if(!$_jf8if["PersonalizeEMails"]) return $_IO08l;

    $_jLtli = array();
    _JJREP($_IO08l, $_jLtli);
    if(!count($_jLtli)) return $_IO08l;

    if(!isset($_jf8if["id"]) && isset($_jf8if["RecipientId"]))
      $_jf8if["id"] = $_jf8if["RecipientId"];
      else
      if(!isset($_jf8if["id"]))
        $_jf8if["id"] = 0;

    $_QLfol = "SELECT `Name`, `functiontext` FROM `$_QlfCL`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
       if($_QLO0f["functiontext"] == "") continue;

       $_QlOjt = array_searchi($_QLO0f["Name"], $_jLtli);
       if($_QlOjt !== false) {
         $_jQ8J6 = @unserialize( $_QLO0f["functiontext"] );
         if($_jQ8J6 === false)
           $_jQ8J6 = array();
         $_jQoQi = false;
         $_fLtJ0 = "";
         $_jQoQi = _JQ06L($_jf8if, $_jQ8J6, $_fLtJ0, $_6JC6j);

         if(!$_jQoQi) {
           unset($_jLtli[$_QlOjt]);
         }
       }
    }
    mysql_free_result($_QL8i1);

    $_Qli6J = 0;
    $_IO08l = _JJPA1($_IO08l, $_jLtli, false, $_Qli6J);

    return $_IO08l;
  }

  if(!function_exists("array_searchi")) {
    function array_searchi($_jOjt8, $_jOjjt) {
      return array_search(strtolower($_jOjt8),array_map('strtolower',$_jOjjt));
    }
  }

class TInternalFunction{
  var $functionName;
  var $HoleMatch;
  var $OpValue;
  var $Error;

  // constructor
  function __construct($HoleMatch, $functionName, $OpValue) {
     $this->HoleMatch = $HoleMatch;
     $this->functionName = $functionName;
     $this->OpValue = str_replace('&nbsp;', '', trim($OpValue));
     $this->OpValue = str_replace('%20', '', trim($OpValue));
     $this->Error = "";
  }

  function TInternalFunction($HoleMatch, $functionName, $OpValue) {
    self::__construct($HoleMatch, $functionName, $OpValue);
  }
 }

 class TinternalFunctions{

  // @private 
  var $_fLC8t = array
    ('base64_encode', 'base64_decode', 'url_encode', 'url_decode', 'htmlentities', 'unhtmlentities',
     'sha1', 'md5', 'sha2_256', 'sha2_384', 'sha2_512', 'domain_part', 'local_part', 'tld', 'lowercase',
     'uppercase', 'lcfirst', 'ucfirst', 'trim', 'ltrim', 'rtrim', 'nl2br', 'hex', 'rand', 'add', 'sub', 'mul', 'div', 
     'muldiv', 'divmul',  'abs', 'toint', 'tofloat',
     'rand_string_mixed', 'rand_string_uppercase', 'rand_string_lowercase', 'rand_string_from_array', 'substring', 'pos',
     'today', 'tomorrow', 'yesterday', 'incdays', 'incweeks', 'incyears', 'format');

  // @private 
  var $_fLCii = 'sf_';

  // @public
  var $internalFunctions = array();

  // constructor
  function __construct() {
     foreach($this->_fLC8t as $key){
       $this->internalFunctions[] = $this->_fLCii . $key; 
     }
  }

  function TinternalFunctions() {
    self::__construct();
  }

  // destructor
  function __destruct() {
  }
  
  function _JQQP8(&$_IoLOO){
   for($_Qli6J=0; $_Qli6J<count($_IoLOO); $_Qli6J++) 
     $_IoLOO[$_Qli6J] = trim($_IoLOO[$_Qli6J], " \n\r\t\v\0" . chr(160));
  }
  
  // @public
  function _JQQE8($_fLClO, $_JLO0O, $_6JC6j){

    // PHP reg expressions are very slow
   $_fLi0f = strpos($_fLClO, $this->_fLCii) === false; 
   if($_fLi0f)
      return $_fLClO; 
    
   $_fLiOi = $this->_JQLJ0($_fLClO);

   for($_Qli6J=0; $_Qli6J<count($_fLiOi); $_Qli6J++){
       $_fLClO = $this->_JQL8B($_fLClO, $_fLiOi[$_Qli6J], $_JLO0O, $_6JC6j);
       $_fLiOi[$_Qli6J] = null;
   } 
   return $_fLClO; 
  }

  // @private
  function _JQOA6($_6j11L){
   if (strlen($_6j11L) > 1){
     $_6j11L = substr($_6j11L, 1);
     $_6j11L = substr($_6j11L, 0, strlen($_6j11L) - 1);
   }
   return $_6j11L;
  }
  
  // @private
  function _JQLJ0($_fLLJQ){
    $_fLClO = $_fLLJQ;

    $_ILJjL = array();

    // sample sf_substring([EMail_DomainPart], 1, sf_pos([EMail_DomainPart], . , 1 ))
    
    $_6iifC = 0;

    while (true) {
      // find first sf_*()
      
      $_fLl1j = array();
      
      /*
       [a|b|abc] looks for a, b, a, b, c
       (a|b|abc) looks for a, b, abc
       $_fLlJf = preg_match( '/(' . 'sf_.*?' . ')' . '([ |\t|\n|\r|\&nbsp\;]*)\(.*?\)/i', $_fLClO, $_fLl1j, PREG_OFFSET_CAPTURE, $_6iifC);
       \u00A0 => 160 = &nbsp;
      */ 

      $_fLlJf = preg_match( '/(' . 'sf_.*?' . ')' . '(( |\t|\n|\r|' . chr(160) . '|\&nbsp\;)*)\(.*?\)/i', $_fLClO, $_fLl1j, PREG_OFFSET_CAPTURE, $_6iifC);

      if(!$_fLlJf) break;

      if (count($_fLl1j) >= 3){ // M.Groups.Count >= 3
       $_fLlt1 = $_fLl1j[0][0];

       $functionName = $_fLl1j[1][0]; //sf_substring
       
       if(!in_arrayi($functionName, $this->internalFunctions)){
        $_6iifC = $_fLl1j[0][1] + 1;
        continue; 
       }
       
       $_fLlol = $_fLl1j[2][0]; // filler between sf_substring and (

       $_fl018 = $_fLl1j[0][1];

       $_fLlJf = preg_match( '/\([^)(]*+(?:(?R)[^)(]*)*+\)/i', $_fLClO, $_fLl1j, PREG_OFFSET_CAPTURE, $_fl018);

       if ($_fLlJf && count($_fLl1j) == 1)
       {
         $_QltJO = $_fLl1j[0][0]; // ([EMail_DomainPart], 1, sf_pos([EMail_DomainPart], . , 1 ))

         $_fl06O = new TInternalFunction($functionName . $_fLlol . $_QltJO, $functionName, $this->_JQOA6($_QltJO));
         $_ILJjL[] = $_fl06O;

         $_fj6CI = false;
         if (strpos($_fl06O->functionName, 'sf_rand') === false)
           $_fj6CI = true;

         if ( strpos($_fLClO, $_fl06O->HoleMatch) === false){
           $_fLClO = _LRFCO($functionName, '!!ERROR IN FUNCTION PARAMETERS!!  missing ")"', $_fLClO);
           $_fl06O->Error = '!!ERROR IN FUNCTION PARAMETERS!!  missing ")"';
           continue;
         }

         if($_fj6CI)
           $_fLClO = str_replace($_fl06O->HoleMatch, '', $_fLClO);
           else
           $_fLClO = _LRFCO($_fl06O->HoleMatch, '', $_fLClO);
       }
       else
         $_6iifC = $_fl018 + 1;
      }
      else
       $_6iifC++;
    }
    
    return $_ILJjL;
  }

  // @private
  function _JQL8B($_fLClO, $_fl06O, $_JLO0O, $_6JC6j){
    global $ShortDateFormat, $LongDateFormat;

    $_fl0lJ = $this->_JQLJ0($_fl06O->OpValue);
    for($_Qli6J=0; $_Qli6J<count($_fl0lJ); $_Qli6J++){
        $_fl06O->OpValue = $this->_JQL8B($_fl06O->OpValue, $_fl0lJ[$_Qli6J], $_JLO0O, false);
        $_fl0lJ[$_Qli6J] = null;
    }
    $_fl1II = '';
    $_fl06O->OpValue = unhtmlentities($_fl06O->OpValue, $_JLO0O);
    
    if($_fl06O->Error != ""){
      $_fl1II = $_fl06O->Error;
      return _LRFCO($_fl06O->functionName, $_fl1II, $_fLClO);
    }  
    
    switch($_fl06O->functionName){
      case $this->_fLCii . 'base64_encode':
        $_fl1II = base64_encode($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'base64_decode':
        $_fl1II = base64_decode($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'url_encode':
        $_fl1II = urlencode($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'url_decode':
        $_fl1II = urldecode($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'htmlentities':
        $_fl1II = htmlentities($_fl06O->OpValue, ENT_COMPAT, $_JLO0O);
        break;
      case $this->_fLCii . 'unhtmlentities':
        $_fl1II = /*unhtmlentities*/($_fl06O->OpValue/*, $_JLO0O*/);
        break;
      case $this->_fLCii . 'sha1':
        $_fl1II = sha1($_fl06O->OpValue, false);
        break;
      case $this->_fLCii . 'md5':
        $_fl1II = md5($_fl06O->OpValue, false);
        break;
      case $this->_fLCii . 'sha2_256':
        $_fl1II = hash("sha256", $_fl06O->OpValue, false);
        break;
      case $this->_fLCii . 'sha2_384':
        $_fl1II = hash("sha384", $_fl06O->OpValue, false);
        break;
      case $this->_fLCii . 'sha2_512':
        $_fl1II = hash("sha512", $_fl06O->OpValue, false);
        break;
      case $this->_fLCii . 'domain_part':
        $_I016j = explode('@', $_fl06O->OpValue);
        if(count($_I016j) > 1)
           $_fl1II = $_I016j[1];
        break;
      case $this->_fLCii . 'local_part':
        $_I016j = explode('@', $_fl06O->OpValue);
        if(count($_I016j) > 0)
           $_fl1II = $_I016j[0];
        break;
      case $this->_fLCii . 'tld':
        $_I016j = explode('@', $_fl06O->OpValue);
        if(count($_I016j) > 1){
           $_fl1II = $_I016j[1];
           $_fl1II = substr($_fl1II, strpos_reverse($_fl1II, '.') + 1);
        }   
        break;
      case $this->_fLCii . 'lowercase':
        if(function_exists("mb_strtolower"))
          $_fl1II = mb_strtolower($_fl06O->OpValue, $_JLO0O);
          else
          $_fl1II = strtolower($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'uppercase':
        if(function_exists("mb_strtoupper"))
          $_fl1II = mb_strtoupper($_fl06O->OpValue, $_JLO0O);
          else
          $_fl1II = strtoupper($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'lcfirst':
        $_fl1II = lcfirst($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'ucfirst':
        $_fl1II = ucfirst($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'trim':
        $_fl1II = trim($_fl06O->OpValue, " \n\r\t\v\0" . chr(160));
        break;
      case $this->_fLCii . 'ltrim':
        $_fl1II = ltrim($_fl06O->OpValue, " \n\r\t\v\0" . chr(160));
        break;
      case $this->_fLCii . 'rtrim':
        $_fl1II = rtrim($_fl06O->OpValue, " \n\r\t\v\0" . chr(160));
        break;
      case $this->_fLCii . 'nl2br':
        $_fl1II = nl2br($_fl06O->OpValue);
        break;
      case $this->_fLCii . 'hex':
        $_fl1II = dechex (intval($_fl06O->OpValue));
        break;
      case $this->_fLCii . 'rand':
        $_fl1II = rand(0, intval($_fl06O->OpValue));
        break;
      case $this->_fLCii . 'add':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        $this->_JQQP8($_IoLOO);
        $_fl1OQ = 0;
        $_flQjj = floatval( $_IoLOO[$_fl1OQ] );
        for ($_fl1OQ=1; $_fl1OQ<count($_IoLOO); $_fl1OQ++)
           $_flQjj = $_flQjj + floatval( $_IoLOO[$_fl1OQ] );
        if(intval($_flQjj) == $_flQjj)
          $_fl1II = sprintf("%.0f", $_flQjj);
          else
          $_fl1II = sprintf("%f", $_flQjj);
        break;
      case $this->_fLCii . 'sub':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        $this->_JQQP8($_IoLOO);
        $_fl1OQ = 0;
        $_flQjj = floatval( $_IoLOO[$_fl1OQ] );
        for ($_fl1OQ=1; $_fl1OQ<count($_IoLOO); $_fl1OQ++)
           $_flQjj = $_flQjj - floatval( $_IoLOO[$_fl1OQ] );
        if(intval($_flQjj) == $_flQjj)
          $_fl1II = sprintf("%.0f", $_flQjj);
          else
          $_fl1II = sprintf("%f", $_flQjj);
        break;
      case $this->_fLCii . 'mul':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        $this->_JQQP8($_IoLOO);
        $_fl1OQ = 0;
        $_flQjj = floatval( $_IoLOO[$_fl1OQ] );
        for ($_fl1OQ=1; $_fl1OQ<count($_IoLOO); $_fl1OQ++)
           $_flQjj = $_flQjj * floatval( $_IoLOO[$_fl1OQ] );
        if(intval($_flQjj) == $_flQjj)
          $_fl1II = sprintf("%.0f", $_flQjj);
          else
          $_fl1II = sprintf("%f", $_flQjj);
        break;
      case $this->_fLCii . 'div':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        $this->_JQQP8($_IoLOO);
        $_fl1OQ = 0;
        $_flQjj = floatval( $_IoLOO[$_fl1OQ] );
        for ($_fl1OQ=1; $_fl1OQ<count($_IoLOO); $_fl1OQ++)
           $_flQjj = $_flQjj / floatval( $_IoLOO[$_fl1OQ] );
        if(intval($_flQjj) == $_flQjj)
          $_fl1II = sprintf("%.0f", $_flQjj);
          else
          $_fl1II = sprintf("%f", $_flQjj);
        break;
      case $this->_fLCii . 'muldiv':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        $this->_JQQP8($_IoLOO);
        if(count($_IoLOO) < 3){
          $_fl1II = "**ERROR** Too few arguments for muldiv";
          break;
        }
        $_flQjj = floatval( $_IoLOO[0] ) * floatval( $_IoLOO[1] ) / floatval( $_IoLOO[2] );
        if(intval($_flQjj) == $_flQjj)
          $_fl1II = sprintf("%.0f", $_flQjj);
          else
          $_fl1II = sprintf("%f", $_flQjj);
        break;
      case $this->_fLCii . 'divmul':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        $this->_JQQP8($_IoLOO);
        if(count($_IoLOO) < 3){
          $_fl1II = "**ERROR** Too few arguments for muldiv";
          break;
        }
        $_flQjj = floatval( $_IoLOO[0] ) / floatval( $_IoLOO[1] ) * floatval( $_IoLOO[2] );
        if(intval($_flQjj) == $_flQjj)
          $_fl1II = sprintf("%.0f", $_flQjj);
          else
          $_fl1II = sprintf("%f", $_flQjj);
        break;
      case $this->_fLCii . 'abs':
        $_flQjj = floatval( $_fl06O->OpValue );
        if(intval($_flQjj) == $_flQjj)
          $_fl1II = sprintf("%.0f", abs($_flQjj));
          else
          $_fl1II = sprintf("%f", abs($_flQjj));
        break;
      case $this->_fLCii . 'toint':
        $_fl1II = intval( $_fl06O->OpValue );
        break;
      case $this->_fLCii . 'tofloat':
        $_fl1II = sprintf("%f", floatval( $_fl06O->OpValue ));
        break;
      case $this->_fLCii . 'rand_string_mixed':
      case $this->_fLCii . 'rand_string_uppercase':
      case $this->_fLCii . 'rand_string_lowercase':
        $_fl1II = intval( $_fl06O->OpValue );
        if($_fl1II < 1) $_fl1II = 8;
        if($_fl1II > 2147483647) $_fl1II = 2147483647; // MaxInt
        
        $_fl1II = _LBQB1($_fl1II);
        
        if($_fl06O->functionName == $this->_fLCii . 'rand_string_uppercase') 
          $_fl1II = strtoupper($_fl1II);
        if($_fl06O->functionName == $this->_fLCii . 'rand_string_lowercase') 
          $_fl1II = strtolower($_fl1II);
        
        break;
      case $this->_fLCii . 'rand_string_from_array':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        $_fl1II = $_IoLOO[rand(0, count($_IoLOO) - 1)];
        break;
      case $this->_fLCii . 'substring': 
      case $this->_fLCii . 'substr': 
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        $this->_JQQP8($_IoLOO);
        
        if(count($_IoLOO) < 2)
          $_fl1II = "";
          else{
            $_IoLOO[1] = intval($_IoLOO[1]);
            if($_IoLOO[1] == 0)
              $_IoLOO[1] = 1;
              
            if(count($_IoLOO) > 2 && $_IoLOO[2] == "")
              array_pop($_IoLOO);
              
            if(count($_IoLOO) > 2)
              $_fl1II = substr($_IoLOO[0], $_IoLOO[1] - 1, $_IoLOO[2]);  
              else
              $_fl1II = substr($_IoLOO[0], $_IoLOO[1] - 1);  
          }
        break;
      case $this->_fLCii . 'pos': 
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        $this->_JQQP8($_IoLOO);
        
        if(count($_IoLOO) < 2)
          $_fl1II = 0;
          else{
            $_fl1II = stripos($_IoLOO[0], $_IoLOO[1]);  
            if($_fl1II === false)
              $_fl1II = 0;
              else
              $_fl1II++;
            if($_fl1II && count($_IoLOO) > 2)  
              $_fl1II -= intval($_IoLOO[2]);
            
          }
        break;

      case $this->_fLCii . 'today':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        $_jQCfL = new DateTime('now');
        
        if (!count($_IoLOO) || trim($_IoLOO[0]) == "")
           $_fl1II = $_jQCfL->format($ShortDateFormat);
          else
           $_fl1II = $_jQCfL->format(trim($_IoLOO[0]));

        unset($_jQCfL);    

        break;

      case $this->_fLCii . 'tomorrow':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        $_jQCfL = new DateTime('tomorrow');
        
        if (!count($_IoLOO) || trim($_IoLOO[0]) == "")
           $_fl1II = $_jQCfL->format($ShortDateFormat);
          else
           $_fl1II = $_jQCfL->format(trim($_IoLOO[0]));

        unset($_jQCfL);    

        break;
    
      case $this->_fLCii . 'yesterday':
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        $_jQCfL = new DateTime('yesterday');
        
        if (!count($_IoLOO) || trim($_IoLOO[0]) == "")
           $_fl1II = $_jQCfL->format($ShortDateFormat);
          else
           $_fl1II = $_jQCfL->format(trim($_IoLOO[0]));

        unset($_jQCfL);    

        break;

      case $this->_fLCii . 'incdays': // sf_incdays(date, days, destformat = '', srcformat = '') }
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        if(count($_IoLOO) < 2){
          $_fl1II = "**ERROR** Too few arguments for incdays";
          break;
        }
        
        $_flQij = $ShortDateFormat;
        if(count($_IoLOO) >= 4 && trim($_IoLOO[3]) !== "")
          $_flQij = trim($_IoLOO[3]);
        
        $_flI8Q = $ShortDateFormat;
        if(count($_IoLOO) >= 3 && trim($_IoLOO[2]) !== "")
          $_flI8Q = trim($_IoLOO[2]);

        $_jQCfL = DateTime::createFromFormat($_flQij, $_IoLOO[0]);
        if($_jQCfL === false){
          $_fl1II = "**ERROR** invalid date for incdays";
          break;
        }
        
        $_jQCfL->modify('+' . intval(trim($_IoLOO[1])) . ' day'); // PHP 5.2 compatible
          
        $_fl1II = $_jQCfL->format($_flI8Q);

        unset($_jQCfL);    

        break;

      case $this->_fLCii . 'incweeks': // sf_incweeks(date, weeks, destformat = '', srcformat = '') }
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        if(count($_IoLOO) < 2){
          $_fl1II = "**ERROR** Too few arguments for incweeks";
          break;
        }
        
        $_flQij = $ShortDateFormat;
        if(count($_IoLOO) >= 4 && trim($_IoLOO[3]) !== "")
          $_flQij = trim($_IoLOO[3]);
        
        $_flI8Q = $ShortDateFormat;
        if(count($_IoLOO) >= 3 && trim($_IoLOO[2]) !== "")
          $_flI8Q = trim($_IoLOO[2]);

        $_jQCfL = DateTime::createFromFormat($_flQij, $_IoLOO[0]);
        if($_jQCfL === false){
          $_fl1II = "**ERROR** invalid date for incweeks";
          break;
        }
        
        $_jQCfL->modify('+' . intval(trim($_IoLOO[1])) . ' week'); // PHP 5.2 compatible
          
        $_fl1II = $_jQCfL->format($_flI8Q);

        unset($_jQCfL);    

        break;

      case $this->_fLCii . 'incyears': // sf_incyears(date, years, destformat = '', srcformat = '') }
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        if(count($_IoLOO) < 2){
          $_fl1II = "**ERROR** Too few arguments for incyears";
          break;
        }
        
        $_flQij = $ShortDateFormat;
        if(count($_IoLOO) >= 4 && trim($_IoLOO[3]) !== "")
          $_flQij = trim($_IoLOO[3]);
        
        $_flI8Q = $ShortDateFormat;
        if(count($_IoLOO) >= 3 && trim($_IoLOO[2]) !== "")
          $_flI8Q = trim($_IoLOO[2]);

        $_jQCfL = DateTime::createFromFormat($_flQij, $_IoLOO[0]);
        if($_jQCfL === false){
          $_fl1II = "**ERROR** invalid date for incyears";
          break;
        }
        
        $_jQCfL->modify('+' . intval(trim($_IoLOO[1])) . ' year'); // PHP 5.2 compatible
          
        $_fl1II = $_jQCfL->format($_flI8Q);

        unset($_jQCfL);    

        break;

      case $this->_fLCii . 'format': // format(format, args0, args1...) https://www.php.net/manual/de/function.sprintf.php
        $_IoLOO = explode( strpos($_fl06O->OpValue, ",") !== false ? ',' : ';', $_fl06O->OpValue);
        
        if(count($_IoLOO) < 2){
          $_fl1II = "**ERROR** Too few arguments for format";
          break;
        }
        
        $format = $_IoLOO[0];
        array_shift($_IoLOO);
        
        for($_Qli6J=0; $_Qli6J<count($_IoLOO); $_Qli6J++){
          $_IoLOO[$_Qli6J] = trim($_IoLOO[$_Qli6J]);
          if(is_numeric($_IoLOO[$_Qli6J]))
            $_IoLOO[$_Qli6J] = $_IoLOO[$_Qli6J] + 0;
        }
        
        $_fl1II = vsprintf($format, $_IoLOO);
        
        break;

    }
    
    if(strpos($_fl06O->functionName, "rand") === false){ // all rand() functions only once!
      if(!$_6JC6j)
        return str_replace($_fl06O->HoleMatch, $_fl1II, $_fLClO);
        else
        return str_replace($_fl06O->HoleMatch, htmlentities($_fl1II, ENT_COMPAT, $_JLO0O), $_fLClO);
    }else{
      if(!$_6JC6j)
        return _LRFCO($_fl06O->HoleMatch, $_fl1II, $_fLClO);
        else
        return _LRFCO($_fl06O->HoleMatch, htmlentities($_fl1II, ENT_COMPAT, $_JLO0O), $_fLClO);
    }  
  } 

}  
?>
