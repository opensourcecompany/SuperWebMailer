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

 include_once("htmltools.inc.php");
 include_once("jsonencode.inc.php");
 if(defined("API"))
   include_once(InstallPath."api/api_base.php");

 if(!defined("PHP_VERSION"))
   define("PHP_VERSION", phpversion());

 function _OP0AF($_J1ooj) {
   global $_jjC06, $_jjCtI, $_I0lQJ, $_jji0C, $_QOCJo, $_QCo6j, $_jji0i;
   $_Qt6oI = $_J1ooj."/";
   $_jjC06 = InstallPath."userfiles/".$_Qt6oI;
   $_jjCtI = ScriptBaseURL."userfiles/".$_Qt6oI;
   $_I0lQJ = $_jjC06."import/";
   $_jji0C = $_jjC06."export/";
   $_QOCJo = $_jjC06."file/";
   $_QCo6j = $_jjC06."image/";
   $_jji0i = $_jjC06."media/";
 }

 function _OP0D0($_ICQQo){
   global $_QolLi, $_Qofoi, $_ICljl, $_QLo0Q, $_I0f8O, $_Ql8C0, $_Qlt66, $_I88i8, $_I8tjl, $_Q66li, $_Q6ftI, $_IC1lt;
   global $_Q6jOo, $_IIl8O, $_IjQIf, $_IQL81, $_II8J0, $_QCLCI, $_IC0oQ, $_ICjQ6;
   global $_jJ6f0;
   global $_IoOLJ;
   global $_ICjCO;
   global $_IooOQ;
   global $_IoCtL;
   global $_QoOft, $_Qoo8o;
   global $_Q6C0i;

   $_QolLi = $_ICQQo["InboxesTableName"];
   $_Qofoi = $_ICQQo["MTAsTableName"];
   $_ICljl = $_ICQQo["PagesTableName"];
   $_QLo0Q = $_ICQQo["MessagesTableName"];
   $_Ql8C0 = $_ICQQo["GlobalBlockListTableName"];
   $_Qlt66 = $_ICQQo["GlobalDomainBlockListTableName"];
   $_I88i8 = $_ICQQo["FunctionsTableName"];
   $_jJ6f0 = $_ICQQo["MailsSentTableName"];
   $_I0f8O = $_ICQQo["AutoImportsTableName"];

   $_QoOft = $_ICQQo["DistributionListsTableName"];
   $_Qoo8o = $_ICQQo["DistributionListsEntriesTableName"];

   $_Q6C0i = $_ICQQo["TargetGroupsTableName"];

   // SWM
   if( defined("SWM") ) {
     $_I8tjl = $_ICQQo["TextBlocksTableName"];
     $_Q66li = $_ICQQo["TemplatesTableName"];
     $_Q6ftI = $_ICQQo["TemplatesToUsersTableName"];
     $_IC1lt = $_ICQQo["NewsletterArchivesTableName"];
     $_Q6jOo = $_ICQQo["CampaignsTableName"];
     $_IIl8O = $_ICQQo["BirthdayMailsTableName"];
     $_IjQIf = $_ICQQo["BirthdayMailsStatisticsTableName"];
     $_IQL81 = $_ICQQo["AutoresponderMailsTableName"];
     $_II8J0 = $_ICQQo["AutoresponderStatisticsTableName"];
     $_QCLCI = $_ICQQo["FollowUpMailsTableName"];
     $_IC0oQ = $_ICQQo["EventMailsTableName"];
     $_ICjQ6 = $_ICQQo["EventMailsStatisticsTableName"];
     $_IoOLJ = $_ICQQo["RSS2EMailMailsTableName"];
     $_ICjCO = $_ICQQo["RSS2EMailMailsStatisticsTableName"];
     $_IooOQ = $_ICQQo["SplitTestsTableName"];
     $_IoCtL = $_ICQQo["SMSCampaignsTableName"];
   } else {
     $_I8tjl = "";
     $_Q66li = "";
     $_Q6ftI = "";
     $_IC1lt = "";
     $_Q6jOo = "";
     $_IIl8O = "";
     $_IjQIf = "";
     $_IQL81 = "";
     $_II8J0 = "";
     $_QCLCI = "";
     $_IC0oQ = "";
     $_ICjQ6 = "";
     $_IoOLJ = "";
     $_ICjCO = "";
     $_IooOQ = "";
     $_IoCtL = "";
   }
 }

 function _OP10J($lang){
   global $INTERFACE_LANGUAGE, $_Q61I1;
   if(empty($lang)) $lang = $INTERFACE_LANGUAGE;
   if($lang == "de") {
      @setlocale (LC_ALL, 'de_DE');
      @setlocale (LC_TIME, 'de_DE');
      if(function_exists("date_default_timezone_set"))
        @date_default_timezone_set("Europe/Berlin");
      @mysql_query('SET time_zone = "Europe/Berlin"', $_Q61I1);
   } else{
      @setlocale (LC_ALL, 'en_US');
      @setlocale (LC_TIME, 'en_US');
      if(function_exists("date_default_timezone_set"))
        @date_default_timezone_set("Europe/London");
      @mysql_query('SET time_zone = "Europe/London"', $_Q61I1);
   }
 }

 function SetHTMLHeaders($_Q6QQL) {
   // Prevent the browser from caching the result.
   // Date in the past
   @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
   // always modified
   @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
   // HTTP/1.1
   @header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0') ;
   @header('Cache-Control: post-check=0, pre-check=0', false) ;
   // HTTP/1.0
   @header('Pragma: no-cache') ;

   // Set the response format.
   @header( 'Content-Type: text/html; charset='.$_Q6QQL ) ;
 }

 function _OPQ1F($_J1Cif) {
     if(is_array($_J1Cif))
       if( version_compare(PHP_VERSION, "5.0.0", "<") )
         return array_map(__FUNCTION__, $_J1Cif);
         else
         return array_map(__METHOD__, $_J1Cif);

     if(!empty($_J1Cif) && is_string($_J1Cif)) {
         return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $_J1Cif);
     }

     return $_J1Cif;
 }

 function _OPQLR($_QJCJi) {
   global $_Q61I1;

   if(function_exists("mysql_real_escape_string") && isset($_Q61I1) && $_Q61I1)
     $_QJCJi = mysql_real_escape_string($_QJCJi, $_Q61I1);
     else {
       $_QJCJi = _OPQ1F($_QJCJi);
     }

   $_QJCJi = "'".$_QJCJi."'";

   return $_QJCJi;
 }

 function _OPQJR($_QJCJi) {
   $_QJCJi = str_replace ('"', '\"', $_QJCJi);
   $_QJCJi = str_replace ("'", "\\'", $_QJCJi);
   $_QJCJi = '"'.$_QJCJi.'"';
   return $_QJCJi;
 }

 function _OPQ6J($_J1iJQ = 300) {
   @ignore_user_abort(true);
   @set_time_limit($_J1iJQ);
 }

 // \\ problem
 if ( (version_compare(PHP_VERSION, "5.4") < 0) && get_magic_quotes_gpc() && !function_exists("stripslashes_deep") ) {
    function stripslashes_deep($_Q6ClO) {
        $_Q6ClO = is_array($_Q6ClO) ? array_map('stripslashes_deep', $_Q6ClO) : (isset($_Q6ClO) ? stripslashes($_Q6ClO) : null);
        # urldecode wegen ctek server
#        if(!is_array($_Q6ClO)) {
#          $_QllO8 = stripos($_Q6ClO, "http:");
#          if($_QllO8 !== false && $_QllO8 == 0)
#             $_Q6ClO = urldecode($_Q6ClO);
#        }
        return $_Q6ClO;
    }
 }

# // \\ problem
# if ( !get_magic_quotes_gpc() ) {
#    function addslashes_deep($_Q6ClO) {
#        $_Q6ClO = is_array($_Q6ClO) ? array_map('addslashes_deep', $_Q6ClO) : (isset($_Q6ClO) ? addslashes($_Q6ClO) : null);
#        return $_Q6ClO;
#    }
# }

 function _OPOAR($_I1L81) {
   if ( (version_compare(PHP_VERSION, "5.4") < 0) && get_magic_quotes_gpc() )
      return stripslashes_deep($_I1L81);
      else
      return $_I1L81;
 }

# function AddSlashs($_I1L81) {
#   if ( !get_magic_quotes_gpc() )
#      return addslashes_deep($_I1L81);
#      else
#      return $_I1L81;
# }

 function _OPLPC($_J1ilj, $_J1Lil, $_J1lIJ) {
   return str_replace ($_J1ilj, $_J1Lil, $_J1lIJ);
 }

 function _OPLFQ($_J1ltO, $_J1lio) { # return <> 0 then dec 1 !!!!!!!!!!
   $_I1t0l = strpos ($_J1lio, $_J1ltO);
   if ($_I1t0l !== false)
     return $_I1t0l + 1;
     else
     return 0;
 }

 function strpos_reverse($_J1lio, $_JQ0OO, $_JQ0ll = -1) {
     // from http://de.php.net/manual/en/function.strpos.php
     // modified
     // $_JQ0ll = strpos($_J1lio,$relativeChar);
     if($_JQ0ll < 0)
       $_JQ0ll = strlen($_J1lio);
     $_JQ101 = $_JQ0ll;
     $_JQ1CI = '';
     //
     while ($_JQ1CI != $_JQ0OO) {
         $_JQQ00 = $_JQ101-1;
         $_JQ1CI = substr($_J1lio,$_JQQ00,strlen($_JQ0OO));
         if($_JQ1CI === false) return FALSE;
         $_JQ101 = $_JQQ00;
     }
     //
     if (!empty($_JQ1CI)) {
         //
         return $_JQ101;
         return TRUE;
     }
     else {
         return FALSE;
     }
     //
 }

 function _OP6PQ($_Q6ICj, $_JQQI6, $_JQQOi) {
    if (strpos($_Q6ICj, $_JQQI6) === false || strpos($_Q6ICj, $_JQQOi) === false )
      return $_Q6ICj;
    $_JQI0f = substr($_Q6ICj, 0, strpos($_Q6ICj, $_JQQI6));
    $_JQII8 = substr($_Q6ICj, strpos($_Q6ICj, $_JQQOi) + strlen($_JQQOi));
    return _OP6PQ($_JQI0f.$_JQII8, $_JQQI6, $_JQQOi);

 }

 function _OPR6L($_Q6ICj, $_JQQI6, $_JQQOi, $_Q6ClO) {
    if (strpos($_Q6ICj, $_JQQI6) === false || strpos($_Q6ICj, $_JQQOi) === false )
      return $_Q6ICj;
    $_JQI0f = substr($_Q6ICj, 0, strpos($_Q6ICj, $_JQQI6));
    $_JQII8 = substr($_Q6ICj, strpos($_Q6ICj, $_JQQOi) + strlen($_JQQOi));
    return _OPR6L($_JQI0f.$_Q6ClO.$_JQII8, $_JQQI6, $_JQQOi, $_Q6ClO);

 }

 function _OP81D($_Q6ICj, $_JQQI6, $_JQQOi) {
    if (strpos($_Q6ICj, $_JQQI6) === false || strpos($_Q6ICj, $_JQQOi) === false )
      return "";
    $_Q6ICj = substr($_Q6ICj, strpos($_Q6ICj, $_JQQI6) + strlen($_JQQI6));
    $_Q6ICj = substr($_Q6ICj, 0, strpos($_Q6ICj, $_JQQOi) - 1);
    return $_Q6ICj;
 }

 function _OP8CP($_Q6ICj, $_JQQI6, $_JQQOi, $_Q6ClO, $_JQILl = false) {
   $_Q6ICj = _OB8O0($_JQQI6, $_JQQOi, $_Q6ICj, $_Q6ClO);
   if($_JQILl)
      $_Q6ICj = _OP8CP($_Q6ICj, $_JQQI6, $_JQQOi, $_Q6ClO, $_JQILl);
   return $_Q6ICj;
 }

 function _OPPRR($_Q6ICj, $_JQjJQ){
   $_Q6ICj = str_ireplace($_JQjJQ, "", $_Q6ICj);
   $_Q6ICj = str_ireplace(substr_replace($_JQjJQ, "/", 1, 0), "", $_Q6ICj);
   return $_Q6ICj;
 }

 function _OPAOJ($_IJlJ6) {
   if ( preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $_IJlJ6) ){
        return 1;
   }else {
        return 0;
   }
 }

 function _OPB1E($_IJlJ6) {
   if(strpos($_IJlJ6, '[') !== false && strpos($_IJlJ6, ']') !== false && strpos($_IJlJ6, '[') < strpos($_IJlJ6, ']'))
      return 1;
   if ( preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $_IJlJ6) ){
        return 1;
   }else {
        return 0;
   }
 }

 function _OPC0F($_IJlJ6, $_JQjf6 = true) {  // with IDN support
   if ( preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $_IJlJ6) ){
        return 1;
   }else {
        if($_JQjf6) {
          if(!@include_once("./PEAR/IDNA.php"))
            include_once(InstallPath."PEAR/"."IDNA.php");

          $_JQjot = new Net_IDNA();
          $_JQJJ0 = $_JQjot->singleton();
          $_JQJJf = $_JQJJ0->encode($_IJlJ6);
          if(isset($_JQJJf) && $_IJlJ6 != $_JQJJf)
            return _OPC0F($_JQJJf, false);
        }
        return 0;
   }
 }

 function _OPCCE($_jlj0f) {
    $_I1t0l = strpos($_jlj0f, 'http://');
    $_JQJJt = strpos($_jlj0f, 'https://');
    if($_I1t0l === false && $_JQJJt === false)
      return false;
    if($_I1t0l !== false && $_I1t0l > 0)
       return false;
    if($_JQJJt !== false && $_JQJJt > 0)
       return false;
    if($_I1t0l !== false)
       $_jlj0f = trim(substr($_jlj0f, 7));
       else
       $_jlj0f = trim(substr($_jlj0f, 8));
    if($_jlj0f == "")
      return false;

    $_I1t0l = strpos($_jlj0f, 'www.');
    if($_I1t0l !== false )
       $_jlj0f = trim(substr($_jlj0f, 4));
    if($_jlj0f == "")
      return false;

    $_I1t0l = strpos($_jlj0f, '/');
    if($_I1t0l !== false )
       $_jlj0f = substr($_jlj0f, 0, $_I1t0l);
    if($_jlj0f == "localhost") return true; // for local testing
    return _OPC0F("info@".$_jlj0f);
 }

 function _OPDRB(&$_JQJi8) {
    if (!(strpos($_JQJi8, '@aol.de') === False)) {
      $_JQJi8=_OPLPC('@aol.de', "@aol.com", $_JQJi8);
    }
    if (!(strpos($_JQJi8, '@tonline.de') === False)) {
      $_JQJi8=_OPLPC('@tonline.de', "@t-online.de", $_JQJi8);
    }
    if (!(strpos($_JQJi8, '@-tonline.de') === False)) {
      $_JQJi8=_OPLPC('@-tonline.de', "@t-online.de", $_JQJi8);
    }
    if ((strpos("a".$_JQJi8, "www.") > 0)) {
       $_JQJi8 = substr($_JQJi8, 4, strlen($_JQJi8));
    }
 }

 function _OPEOJ($_QlQC8, $EMail) {
   global $_Q61I1;
   $_QJlJ0 = "SELECT `id` FROM `$_QlQC8` WHERE `u_EMail`="._OPQLR($EMail);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
     if($_Q60l1)
       mysql_free_result($_Q60l1);
     return false;
   } else {
     if($_Q60l1)
       mysql_free_result($_Q60l1);
     return true;
   }
 }

 function _OPEDJ($_JQ60C, $_JQ6oi, $_I6016, $_JQfjf, $_JQ8IQ = False) {
   global $AppName, $php_errormsg;

  if($_JQ60C == "")
    $_QiOo1 = "From: webmaster@{$_SERVER['SERVER_NAME']}";
    else
    $_QiOo1 = "From: $_JQ60C";

  if($_JQ8IQ)
   $_Q8Q1Q = "-fwebmaster@{$_SERVER['SERVER_NAME']}";
   else
   $_Q8Q1Q = "";

   $_JQ8ij = @ini_set('track_errors', 1);
   if($_Q8Q1Q != "")
     $_Q8COf = mail($_JQ6oi, $_I6016, $_JQfjf, $_QiOo1, $_Q8Q1Q);
     else
     $_Q8COf = mail($_JQ6oi, $_I6016, $_JQfjf, $_QiOo1);

   if(!$_Q8COf){
     if(isset($php_errormsg) && !defined("CRONS_PHP"))
       print $_JQtI1;
   }
   @ini_set('track_errors', $_JQ8ij);
   return $_Q8COf;
 }

# PHP 5 includes this functions
 if (!function_exists ('stripos') ) {
   function stripos ( $_ILo0C, $_ILooj, $_ILCIC=NULL ) {
   if (isset($_ILCIC) && $_ILCIC != NULL)
     return strpos( strtolower($_ILo0C), strtolower($_ILooj), $_ILCIC);
     else
     return strpos(strtolower($_ILo0C), strtolower($_ILooj), $_ILooj);
   }
 }

 if (!function_exists ('str_ireplace') ) {
   function str_ireplace($_ILCOl,$_ILi66,$_I6016){
       $_ILL10 = chr(1);
       $_ILo0C = strtolower($_I6016);
       $_ILooj = strtolower($_ILCOl);
       while (($_I1t0l=strpos($_ILo0C,$_ILooj))!==FALSE){
         $_I6016 = substr_replace($_I6016,$_ILL10,$_I1t0l,strlen($_ILCOl));
         $_ILo0C = substr_replace($_ILo0C,$_ILL10,$_I1t0l,strlen($_ILCOl));
       }
       $_I6016 = str_replace($_ILL10,$_ILi66,$_I6016);
       return $_I6016;
     }
 }

 if (!function_exists('file_get_contents')) {
      function file_get_contents($_jt8LJ, $_JQtjO = false, $_JQtO8 = null)
      {
          if (false === $_JQtLL = fopen($_jt8LJ, 'rb', $_JQtjO)) {
              trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
              return false;
          }

          clearstatcache();
          if ($_JQOOo = @filesize($_jt8LJ)) {
              $_Qf1i1 = fread($_JQtLL, $_JQOOo);
          } else {
              $_Qf1i1 = '';
              while (!feof($_JQtLL)) {
                  $_Qf1i1 .= fread($_JQtLL, 8192);
              }
          }

          fclose($_JQtLL);
          return $_Qf1i1;
      }
  }
# PHP 5 includes this functions END

 function _OPFJA($_JQOoI, $_JQoQO, $_Q6ICj) {
  for($_Q6llo=0; $_Q6llo< count($_JQOoI); $_Q6llo++) {
    $_Q6ICj = str_replace ('name="'.$_JQOoI[$_Q6llo].'"', 'name="'.$_JQOoI[$_Q6llo].'" class="missingvaluebackground"', $_Q6ICj);
    $_Q6ICj = str_replace ('name="'.$_JQOoI[$_Q6llo].'[]"', 'name="'.$_JQOoI[$_Q6llo].'[]" class="missingvaluebackground"', $_Q6ICj);
  }

  // Slash wegmachen
  foreach ($_JQoQO as $key => $_Q6ClO){
     $_JQoQO[$key] = _OPOAR($_Q6ClO);
     if(strpos($key, "Subject") !== false || strpos($key, "MailPreHeaderText") !== false){
        $_JQoQO[$key] = str_replace('"', '&quot;', $_Q6ClO);
     }

  }

  reset($_JQoQO);
  foreach($_JQoQO as $_I1i8O => $_I1L81) {
    $_JQojl = false;
    $_QllO8 = strpos ($_Q6ICj, 'name="'.$_I1i8O.'"');
    if($_QllO8 === false) {
       $_QllO8 = strpos ($_Q6ICj, 'name="'.$_I1i8O.'[]"');
       if($_QllO8 === false) continue;
       $_JQojl = is_array($_I1L81);
    }

    if($_QllO8 === false) continue;
    $_Io0l8 = strpos_reverse($_Q6ICj, "<", $_QllO8);
    if($_Io0l8 == false)
      $_Q6ICj = str_replace ('name="'.$_I1i8O.'"', 'name="'.$_I1i8O.'" value="'.$_I1L81.'"', $_Q6ICj);
      else {
       $_Q66jQ = substr($_Q6ICj, $_Io0l8);
       $_JQotj = trim(substr($_Q66jQ, 1, strpos($_Q66jQ, " ") - 1));

       if($_JQotj == "textarea") {
        $_Q66jQ = substr($_Q66jQ, 0, strpos($_Q66jQ, ">") + 1);
        $_JQCIj = $_Q66jQ;
        $_Q6ICj = str_replace ($_Q66jQ, $_JQCIj.$_I1L81, $_Q6ICj);
       }
       elseif($_JQotj == "select") {
         $_Q66jQ = substr($_Q66jQ, 0, strpos($_Q66jQ, "</select>") + strlen("</select>"));
         $_JQCIj = $_Q66jQ;
         if(!$_JQojl)
            $_JQCIj = str_replace ('value="'.$_I1L81.'"', 'value="'.$_I1L81.'" selected="selected"', $_JQCIj);
            else {
              for($_Q6llo=0; $_Q6llo<count($_I1L81); $_Q6llo++) {
                $_JQCIj = str_replace ('value="'.$_I1L81[$_Q6llo].'"', 'value="'.$_I1L81[$_Q6llo].'" selected="selected"', $_JQCIj);
              }
            }
         $_Q6ICj = str_replace ($_Q66jQ, $_JQCIj, $_Q6ICj);
       }
       else {// normal <input
          $_Q66jQ = substr($_Q66jQ, 0, strpos($_Q66jQ, ">"));
          if( !(stripos($_Q66jQ, 'type="submit"') === false) ) continue;
          if( !(stripos($_Q66jQ, 'type="reset"') === false) ) continue;
          if( !(stripos($_Q66jQ, 'type="button"') === false) ) continue;
          if( !(stripos($_Q66jQ, 'type="image"') === false) ) continue;

          if( !(stripos($_Q66jQ, 'type="radio"') === false) ) { // wichtig immer name="" value="" sonst klappt es nicht
            $_Q6ICj = str_replace ('name="'.$_I1i8O.'" value="'.$_I1L81.'"', 'name="'.$_I1i8O.'" value="'.$_I1L81.'" checked="checked"', $_Q6ICj);
          } elseif( !(stripos($_Q66jQ, 'type="checkbox"') === false) ) {
            if(!$_JQojl) {
              if($_I1L81 != "")
                $_Q6ICj = str_replace ('name="'.$_I1i8O.'"', 'name="'.$_I1i8O.'" checked="checked" value="1"', $_Q6ICj);
              } else {
                for($_Q6llo=0; $_Q6llo<count($_I1L81); $_Q6llo++)
                   $_Q6ICj = str_replace ('name="'.$_I1i8O.'[]"', 'name="'.$_I1i8O.'[]" checked="checked" value="1"', $_Q6ICj);
              }

          } else
             $_Q6ICj = str_replace ('name="'.$_I1i8O.'"', 'name="'.$_I1i8O.'" value="'.$_I1L81.'"', $_Q6ICj);
         }
      }
  }
  return $_Q6ICj;
 }

 function _OA0LA($_I1L81) {
   $_I1L81 = trim($_I1L81);
   $_JQi1J = preg_quote('\/:;*?"<>|=@[]^`´-', '/');
   $_I1L81 = preg_replace("/([\\x00-\\x2f\\x7b-\\xff{$_JQi1J}])/", "", $_I1L81);
   if($_I1L81 == "")
     $_I1L81 = "Table_";

   if(ord($_I1L81{0}) >= 0x30 && ord($_I1L81{0}) <= 0x39){
     $_I1L81 = 'x'.$_I1L81;
   }

   if (strlen($_I1L81) > 63)
      $_I1L81 = substr($_I1L81, 40);

   return strtolower($_I1L81);
 }

 function _OA1LL($_JQiof) {
   global $_Q61I1;
   if($_JQiof == "") return false;
   $_Q60l1 = mysql_query("SHOW TABLES LIKE "._OPQLR($_JQiof), $_Q61I1);
   if( mysql_num_rows( $_Q60l1 ) == 0 )
      return false;
   while($_Q6Q1C = mysql_fetch_row($_Q60l1)) {
     if($_Q6Q1C[0] == $_JQiof) {
       mysql_free_result($_Q60l1);
       return true;
     }
   }
   mysql_free_result($_Q60l1);
   return false;
 }

 function _OAQOB($_JQLfo, $INTERFACE_LANGUAGE) {
   if($_JQLfo == "") return $_JQLfo;
   if(strpos($_JQLfo, " ") !== false)
     $_JQLfo = substr($_JQLfo, 0, strpos($_JQLfo, " ") );
   if($INTERFACE_LANGUAGE == "de") // 2008-09-18
     return substr($_JQLfo, 8, 2).".".substr($_JQLfo, 5, 2).".".substr($_JQLfo, 0, 4);
     else
     return $_JQLfo;
 }

 function _OAO0D($_JQLfo, $INTERFACE_LANGUAGE) {
   if($_JQLfo == "") return $_JQLfo;
   if(strpos($_JQLfo, " ") !== false)
     $_JQLfo = substr($_JQLfo, 0, strpos($_JQLfo, " ") );
   if($INTERFACE_LANGUAGE == "de") // 2008-09-18
     return substr($_JQLfo, 6, 4)."-".substr($_JQLfo, 3, 2)."-".substr($_JQLfo, 0, 2);
     else
     return $_JQLfo;
 }

 function _OAOD0($_JQLfo, $INTERFACE_LANGUAGE) {
   if($_JQLfo == "") return $_JQLfo;
   if(strpos($_JQLfo, " ") === false)
     return _OAO0D($_JQLfo, $INTERFACE_LANGUAGE);
   if($INTERFACE_LANGUAGE == "de") // 2008-09-18 00:00:00
     return substr($_JQLfo, 6, 4)."-".substr($_JQLfo, 3, 2)."-".substr($_JQLfo, 0, 2).substr($_JQLfo, strpos($_JQLfo, " "));
     else
     return $_JQLfo;
 }

 function _OALO0($_JQiof) {
  $_QJCJi = _OA0LA(strtolower($_JQiof));
  if(TablePrefix != "" && strpos($_QJCJi, TablePrefix) === false)
     $_QJCJi = TablePrefix.$_QJCJi;

  $_QJCJi = str_replace("-", "", $_QJCJi);
  $_QJCJi = str_replace("`", "", $_QJCJi);
  $_QJCJi = str_replace("´", "", $_QJCJi);
  $_QJCJi = str_replace("'", "", $_QJCJi);
  $_QJCJi = str_replace("\"", "", $_QJCJi);
  $_QJCJi = str_replace(" ", "_", $_QJCJi);
  $_JQiof = $_QJCJi;

  if(strlen($_JQiof) >= 64) {
    $_JQiof = TablePrefix.substr($_JQiof, 40);
    $_QJCJi = $_JQiof;
  }

  $_Q6llo = 0;
  while ( _OA1LL($_QJCJi) ) {
   $_Q6llo++;
   $_QJCJi = $_JQiof."_".$_Q6llo;
   if(strlen($_QJCJi) > 64) {
     $_JQiof = TablePrefix.substr($_JQiof, 40);
     $_QJCJi = $_JQiof;
   }
  }
  return $_QJCJi;
 }

 function _OAL8F($_JQLCL, $_JQl0I = null, $_JQljl = 0) {
   global $UserType, $Username, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;
   if($_JQljl == 0) {
       $_QJCJi = mysql_error($_Q61I1);
       $_j88of = mysql_errno($_Q61I1);
     }
     else{
       $_QJCJi = mysql_error($_JQljl);
       $_j88of = mysql_errno($_JQljl);
     }
   if($_QJCJi == "") return;

   if(defined("API")){
     if($_JQl0I != null) {
        return $_JQl0I->api_ShowSQLError($_JQLCL);
       }
       else {
         print $_QJCJi." ".$_JQLCL;
         exit;
       }
   }

   $_Q6ICj = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000017"], $resourcestrings[$INTERFACE_LANGUAGE]["000017"], 'DISABLED', 'sql_error_snipped.htm');
   $_Q6ICj = _OPR6L($_Q6ICj, "<SQL:ERROR>", "</SQL:ERROR>", $_QJCJi." ".$_j88of);
   $_Q6ICj = _OPR6L($_Q6ICj, "<SQL:QUERY>", "</SQL:QUERY>", $_JQLCL);
   print $_Q6ICj;
   exit; // cancel script
 }

 function _OALDL($_jj0JO, $_JQl0I = null) {
   global $UserType, $Username, $resourcestrings, $INTERFACE_LANGUAGE;

   if(defined("API")){
     if($_JQl0I != null) {
        return $_JQl0I->api_ShowSQLError($_jj0JO);
       }
       else {
         print $_jj0JO;
         exit;
       }
   }

   $_Q6ICj = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["ERROR"], $resourcestrings[$INTERFACE_LANGUAGE]["ERROR"], 'DISABLED', 'error_snipped.htm');
   $_Q6ICj = _OPR6L($_Q6ICj, "<ERROR>", "</ERROR>", $_jj0JO);
   print $_Q6ICj;
   exit; // cancel script
 }

 function _OAJL1($_ICLO8, &$_QLLjo, $_JI010 = array()) {
   global $_Q61I1;
   if(!isset($_QLLjo))
      $_QLLjo = array();
      else
      array_splice($_QLLjo, 0, count($_QLLjo) );

   $_QJlJ0 = "SHOW COLUMNS FROM `$_ICLO8`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if (!$_Q60l1) {
       _OAL8F($_QJlJ0);
       exit;
   }
   if (mysql_num_rows($_Q60l1) > 0) {
       while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          foreach ($_Q6Q1C as $key => $_Q6ClO) {
             if ($key == "Field")  {
                if(isset($_JI010))
                  if ( in_array($_Q6ClO, $_JI010) ) break; // ignore
                $_QLLjo[] = $_Q6ClO;
                break;
             }
          }
       }
       mysql_free_result($_Q60l1);
   }
 }

 function _OAJCF($_JI0jt, $_ICLO8, &$_QLLjo, $_JI010 = array()) {
   if(!isset($_QLLjo))
      $_QLLjo = array();
      else
      array_splice($_QLLjo, 0, count($_QLLjo) );

   $_QJlJ0 = "SHOW COLUMNS FROM `$_ICLO8`";
   $_Q60l1 = mysql_query($_QJlJ0, $_JI0jt);
   if (!$_Q60l1) {
       _OAL8F($_QJlJ0, null, $_JI0jt);
       exit;
   }
   if (mysql_num_rows($_Q60l1) > 0) {
       while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          foreach ($_Q6Q1C as $key => $_Q6ClO) {
             if ($key == "Field")  {
                if(isset($_JI010))
                  if ( in_array($_Q6ClO, $_JI010) ) break; // ignore
                $_QLLjo[] = $_Q6ClO;
                break;
             }
          }
       }
       mysql_free_result($_Q60l1);
   }
 }

 function _OA60P($_ICLO8, &$_QLLjo, $_JI010 = array()) {
   global $_Q61I1;
   if(!isset($_QLLjo))
      $_QLLjo = array();
      else
      array_splice($_QLLjo, 0, count($_QLLjo) );

   $_QJlJ0 = "SHOW COLUMNS FROM `$_ICLO8`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if (!$_Q60l1) {
       _OAL8F($_QJlJ0);
       exit;
   }
   if (mysql_num_rows($_Q60l1) > 0) {
       while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          foreach ($_Q6Q1C as $key => $_Q6ClO) {
             if ($key == "Field")  {
                if(isset($_JI010))
                  if ( in_array($_Q6ClO, $_JI010) ) break; // ignore
                $_QLLjo[$_Q6ClO] = $_Q6ClO;
                break;
             }
          }
       }
       mysql_free_result($_Q60l1);
   }
 }

 function _OA61D($_ICLO8, &$_QLLjo, $_JI010 = array()) {
   global $_Q61I1;
   if(!isset($_QLLjo))
      $_QLLjo = array();
      else
      array_splice($_QLLjo, 0, count($_QLLjo) );

   $_QJlJ0 = "SHOW COLUMNS FROM `$_ICLO8`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if (!$_Q60l1) {
       _OAL8F($_QJlJ0);
       exit;
   }
   if (mysql_num_rows($_Q60l1) > 0) {
       while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          // Fieldname
          foreach ($_Q6Q1C as $key => $_Q6ClO) {
             if ($key == "Field")  {
                if(isset($_JI010))
                  if ( in_array($_Q6ClO, $_JI010) ) {
                     break; // ignore
                  }
                $_I6oQj = $_Q6ClO;
             }

             if ($key == "Type")  {
                if(strpos($_Q6ClO, "tinyint") === false) {
                  $_I6oQj = "";
                }
                break;
             }
          }
          if($_I6oQj != "")
            $_QLLjo[] = $_I6oQj;
       }
       mysql_free_result($_Q60l1);
   }
 }

 function _OA68J($_ICLO8, $_I6oQj) {
   global $_Q61I1;
   $_QJlJ0 = "SHOW COLUMNS FROM `$_ICLO8`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if (!$_Q60l1) {
       _OAL8F($_QJlJ0);
       exit;
   }
   $_Qo1oC = false;
   $_JI0JO = "";
   if (mysql_num_rows($_Q60l1) > 0) {
       while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          foreach ($_Q6Q1C as $key => $_Q6ClO) {
             if ($key == "Field" && $_I6oQj == $_Q6ClO)  {
                $_Qo1oC = true;
             }
             if($_Qo1oC && $key == "Type") {
               $_JI0JO=$_Q6ClO;
               break;
             }

          }
          if($_Qo1oC && $_JI0JO != "")
            break;
       }
       mysql_free_result($_Q60l1);
   }
   return $_JI0JO;
 }

 function _OA6DQ($_ICLO8, $_I6oQj) {
   global $_Q61I1;
   $_QJlJ0 = "SHOW COLUMNS FROM `$_ICLO8` WHERE Field="._OPQLR($_I6oQj);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_error($_Q61I1) != "")
     return false;
   if (mysql_num_rows($_Q60l1) > 0) {
     mysql_free_result($_Q60l1);
     return true;
   }
   mysql_free_result($_Q60l1);
   return false;
 }

 function _OARCF($_QLitI, $MailingListId, $FormId) {
     // create confirmation IdentString
     $_JI1IJ = sprintf("%02X", $_QLitI);
     $_JI1iC = sprintf("%02X", $MailingListId);
     $_JIQi0 = sprintf("%02X", $FormId);

     if(empty($_SERVER["REMOTE_ADDR"])) // PHP error undefined index
       $_SERVER["REMOTE_ADDR"] = date("U");
     $_JIIQj = strtoupper( $_JI1IJ."-".$_JI1iC."-".$_JIQi0."-".md5($_SERVER["REMOTE_ADDR"])."-".md5(date("r"))."-".md5($_JI1IJ.$_JI1iC.$_JIQi0) );
     if(strlen($_JIIQj) > 64)
        $_JIIQj = substr($_JIIQj, 0, 63);
     return $_JIIQj;
 }

 function _OA81R($_JIIif, $_QLitI, $MailingListId, $FormId, $_QlQC8="") {
    global $_Q60QL, $_Q61I1;
    $_QLitI = intval($_QLitI);
    $MailingListId = intval($MailingListId);
    $FormId = intval($FormId);
    $_JIIQj = $_JIIif;
    $_JIjQ8 = true;
    if($_JIIif != "") {
      $_Q8otJ = explode("-", $_JIIif);
      if( count($_Q8otJ)> 3 && hexdec($_Q8otJ[0]) == $_QLitI && hexdec($_Q8otJ[1]) == $MailingListId && hexdec($_Q8otJ[2]) == $FormId )
        $_JIjQ8 = false;
    }

    if($_JIjQ8) {
      $_JIIQj = _OARCF($_QLitI, $MailingListId, $FormId);
      if($_QlQC8 == "") {
        $_QJlJ0 = "SELECT `MaillistTableName` FROM `$_Q60QL` WHERE id=$MailingListId";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
        $_QlQC8 = $_Q6Q1C["MaillistTableName"];
      }
      $_QJlJ0 = "UPDATE `$_QlQC8` SET `IdentString`="._OPQLR($_JIIQj)." WHERE `id`=$_QLitI";
      mysql_query($_QJlJ0, $_Q61I1);
    }
    return $_JIIQj;
 }

 function _OA8LE($key, &$_QLitI, &$MailingListId, &$FormId, $rid = "") {
   $_QLitI = 0;
   $MailingListId = 0;
   $FormId = 0;
   $_Q8otJ = explode("-", $key);
   if(count($_Q8otJ) < 3) return false;
   $_JI1IJ = $_Q8otJ[0];
   $_JI1iC = $_Q8otJ[1];
   $_JIQi0 = $_Q8otJ[2];
   $_QLitI = hexdec($_JI1IJ);
   $MailingListId = hexdec($_JI1iC);
   $FormId = hexdec($_JIQi0);

   // rid can override form_id to get correct form
   if(!empty($rid)){
      $_Q8otJ = explode("_", $rid);
      if(count($_Q8otJ) > 4){
        if($_Q8otJ[4]{0} == "x" && hexdec(substr($_Q8otJ[4], 1)))
          $FormId = hexdec(substr($_Q8otJ[4], 1));
      }
   }

   return true;
 }

 function _OAP0L($_JIjtL){
    if($_JIjtL == "BirthdayResponder")
      return 1;
    if($_JIjtL == "EventResponder")
      return 2;
    if($_JIjtL == "FollowUpResponder")
      return 3;
    if($_JIjtL == "Campaign")
      return 4;
    if($_JIjtL == "RSS2EMailResponder")
      return 5;
    if($_JIjtL == "DistributionList")
      return 6;
    return 0;
 }

 function _OAPCO($_JIJQ1){
  switch ($_JIJQ1) {
    case 1:
    return "BirthdayResponder";
    break;
    case 2:
    return "EventResponder";
    break;
    case 3:
    return "FollowUpResponder";
    break;
    case 4:
    return "Campaign";
    break;
    case 5:
    return "RSS2EMailResponder";
    case 6:
    return "DistributionList";
    break;
  }
  return "";
 }


 /**
  * Gets table name field in users table
  *
  */

 function _OAAP1($_JIJQ1){
   $_IiQl1 = "";
   switch ($_JIJQ1) {
     case 1:
     $_IiQl1 = "BirthdayMailsTableName";
     break;
     case 2:
     $_IiQl1 = "EventMailsTableName";
     break;
     case 3:
     $_IiQl1 = "FollowUpMailsTableName";
     break;
     case 4:
     $_IiQl1 = "CampaignsTableName";
     break;
     case 5:
     $_IiQl1 = "RSS2EMailMailsTableName";
     break;
     case 6:
     $_IiQl1 = "DistributionListsTableName";
     break;
   }
  return $_IiQl1;
 }

 /**
  * Gets table name for responder
  *
  */

 function _OABJE($_JIJQ1){
   global $_IIl8O, $_IC0oQ, $_QCLCI, $_Q6jOo, $_IoOLJ, $_QoOft;
   $_IiQl1 = "";
   switch ($_JIJQ1) {
     case 1:
     $_IiQl1 = $_IIl8O;
     break;
     case 2:
     $_IiQl1 = $_IC0oQ;
     break;
     case 3:
     $_IiQl1 = $_QCLCI;
     break;
     case 4:
     $_IiQl1 = $_Q6jOo;
     break;
     case 5:
     $_IiQl1 = $_IoOLJ;
     break;
     case 6:
     $_IiQl1 = $_QoOft;
     break;
   }
  return $_IiQl1;
 }

 function _OAC1D($rid, $_JIJfO) {
   global $_Q8f1L, $_Q6JJJ, $_Q61I1;

   $_Q8otJ = explode("_", $rid);
   if(count($_Q8otJ) < 4) {
     return false;
   }
   $_ICltC = hexdec($_Q8otJ[0]);
   $_Ii016 = hexdec($_Q8otJ[1]);
   $ResponderType = hexdec($_Q8otJ[2]);
   $ResponderId = hexdec($_Q8otJ[3]);

   if(!defined("SWM") && $ResponderType != 6) { // 6=DistribList, SML can't do others
    return false;
   }

   $_IiQl1 = _OAAP1($ResponderType);

   if($_IiQl1 == "") {
     return false;
   }

   $_QJlJ0 = "SELECT `$_IiQl1` FROM `$_Q8f1L` WHERE `id`=".intval($_Ii016);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
     return false;
   } else {
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_IiQl1 = $_Q6Q1C[0];
     mysql_free_result($_Q60l1);
   }

   if($ResponderType == 4 || $ResponderType == 6) { // Campaign, DistributionList
     $_QJlJ0 = "SELECT `CurrentSendTableName` FROM `$_IiQl1` WHERE `id`=".intval($ResponderId);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
       return false;
     } else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
     }

     if($_JIJfO)
       $_QJlJ0 = "UPDATE `$_Q6Q1C[CurrentSendTableName]` SET `HardBouncesCount`=`HardBouncesCount`+1 WHERE `id`=".intval($_ICltC);
       else
       $_QJlJ0 = "UPDATE `$_Q6Q1C[CurrentSendTableName]` SET `SoftBouncesCount`=`SoftBouncesCount`+1 WHERE `id`=".intval($_ICltC);
   } else {
     if($_JIJfO)
       $_QJlJ0 = "UPDATE `$_IiQl1` SET `HardBouncesCount`=`HardBouncesCount`+1 WHERE `id`=".intval($ResponderId);
       else
       $_QJlJ0 = "UPDATE `$_IiQl1` SET `SoftBouncesCount`=`SoftBouncesCount`+1 WHERE `id`=".intval($ResponderId);
   }

   mysql_query($_QJlJ0, $_Q61I1);

   return true;
 }

 function _OAD1J($rid) {
   global $_Q8f1L, $_Q6JJJ, $_Q61I1;

   $_Q8otJ = explode("_", $rid);
   if(count($_Q8otJ) < 4) {
     return false;
   }
   $_ICltC = hexdec($_Q8otJ[0]);
   $_Ii016 = hexdec($_Q8otJ[1]);
   $ResponderType = hexdec($_Q8otJ[2]);
   $ResponderId = hexdec($_Q8otJ[3]);

   if(!defined("SWM") && $ResponderType != 6) { // 6=DistribList, SML can't do others
    return false;
   }

   $_IiQl1 = _OAAP1($ResponderType);

   if($_IiQl1 == "") {
     return false;
   }

   $_QJlJ0 = "SELECT `$_IiQl1` FROM `$_Q8f1L` WHERE `id`=".intval($_Ii016);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
     return false;
   } else {
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_IiQl1 = $_Q6Q1C[0];
     mysql_free_result($_Q60l1);
   }

   if($ResponderType == 4 || $ResponderType == 6) { // Campaign, DistributionList
     $_QJlJ0 = "SELECT `CurrentSendTableName` FROM `$_IiQl1` WHERE id=".intval($ResponderId);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if( !$_Q60l1 || mysql_num_rows($_Q60l1) == 0 ) {
       return false;
     } else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
     }

     $_QJlJ0 = "UPDATE `$_Q6Q1C[CurrentSendTableName]` SET `UnsubscribesCount`=`UnsubscribesCount`+1 WHERE id=".intval($_ICltC);
   } else {
     $_QJlJ0 = "UPDATE `$_IiQl1` SET `UnsubscribesCount`=`UnsubscribesCount`+1 WHERE id=".intval($ResponderId);
   }
   mysql_query($_QJlJ0, $_Q61I1);

   return true;
 }

 function _OADJO($MailingListId, $_ILLiJ){
   global $_Q61I1, $_Q60QL;
   global $_Q6jOo, $_IIl8O, $_IQL81, $_QCLCI, $_IC0oQ;
   global $_IoOLJ;
   global $_IooOQ;
   global $_IoCtL;
   global $_QoOft;

   $_QJlJ0 = "SELECT `forms_id` FROM `$_Q60QL` WHERE `id`=$MailingListId AND `forms_id`=$_ILLiJ";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0){
     mysql_free_result($_Q60l1);
     return true;
   } else
     if($_Q60l1)
       mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `forms_id` FROM `$_QoOft` WHERE `maillists_id`=$MailingListId AND `forms_id`=$_ILLiJ";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0){
     mysql_free_result($_Q60l1);
     return true;
   } else
     if($_Q60l1)
       mysql_free_result($_Q60l1);


   if( defined("SWM") ) {
     $_jj6l0 = array($_IQL81, $_IIl8O, $_Q6jOo, $_QCLCI, $_IC0oQ, $_IoOLJ, $_IooOQ, $_IoCtL);

     reset($_jj6l0);
     foreach($_jj6l0 as $key => $_Q6ClO) {
       if(!_OA1LL($_Q6ClO)) continue;
       $_QJlJ0 = "SELECT `forms_id` FROM `$_Q6ClO` WHERE `maillists_id`=$MailingListId AND `forms_id`=$_ILLiJ";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_num_rows($_Q60l1) > 0){
         mysql_free_result($_Q60l1);
         return true;
       } else
         if($_Q60l1)
           mysql_free_result($_Q60l1);
     }
   }

   return false;
 }

// function IsUTF8string($_J1lio)
// {
//   return (utf8_encode(utf8_decode($_J1lio)) == $_J1lio);
//   return (utf8_decode($_J1lio) != $_J1lio) && (utf8_encode($_J1lio) != $_J1lio);
// }

 function _OAELF($_J1lIJ) {
     $_JI6Ij = strlen($_J1lIJ);
     for($_Q6llo = 0; $_Q6llo < $_JI6Ij; $_Q6llo++){
         $_QL8Q8 = ord($_J1lIJ[$_Q6llo]);
         if ($_QL8Q8 > 128) {
             if (($_QL8Q8 > 247)) return false;
             elseif ($_QL8Q8 > 239) $_JI6f0 = 4;
             elseif ($_QL8Q8 > 223) $_JI6f0 = 3;
             elseif ($_QL8Q8 > 191) $_JI6f0 = 2;
             else return false;
             if (($_Q6llo + $_JI6f0) > $_JI6Ij) return false;
             while ($_JI6f0 > 1) {
                 $_Q6llo++;
                 $_jQjOO = ord($_J1lIJ[$_Q6llo]);
                 if ($_jQjOO < 128 || $_jQjOO > 191) return false;
                 $_JI6f0--;
             }
         }
     }
     return true;
 } // end of check_utf8

 function IsUtf8String( $_QJCJi ) {
     return _OAELF($_QJCJi);

     # problem segmentation fault one some apache webserver because of bug in libs for text > 10KB
     $_JIfII  = '[\x00-\x7F]';
     $_JIfl8 = '[\xC2-\xDF][\x80-\xBF]';
     $_JI8oj = '[\xE0-\xEF][\x80-\xBF]{2}';
     $_JI8o6 = '[\xF0-\xF4][\x80-\xBF]{3}';
     $_JI8ot = '[\xF8-\xFB][\x80-\xBF]{4}';
     $_JItJ1 = '[\xFC-\xFD][\x80-\xBF]{5}';
     $_Q60l1 = preg_match("/^($_JIfII|$_JIfl8|$_JI8oj|$_JI8o6|$_JI8ot|$_JItJ1)*$/s", $_QJCJi);

     if($_Q60l1)
       $_Q60l1 = (utf8_decode($_QJCJi) != "");

     return $_Q60l1;
 }


 function _OAFJ6 ($_J1lio) {
       /* Only do the slow convert if there are 8-bit characters */
     /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
    // if (! ereg("[\200-\237]", $_J1lio) and ! ereg("[\241-\377]", $_J1lio))
     if (! preg_match("/[\200-\237]/", $_J1lio) and ! preg_match("/[\241-\377]/", $_J1lio))
         return $_J1lio;

     // decode three byte unicode characters
     /*$_J1lio = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
     "'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",
     $_J1lio);*/

     $_J1lio = preg_replace_callback("/([\340-\357])([\200-\277])([\200-\277])/",
     'charset_decode_utf_8_preg_replace_callback1',
     $_J1lio);

     // decode two byte unicode characters
     /*$_J1lio = preg_replace("/([\300-\337])([\200-\277])/e",
     "'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'",
     $_J1lio);*/

     $_J1lio = preg_replace_callback("/([\300-\337])([\200-\277])/",
     'charset_decode_utf_8_preg_replace_callback2',
     $_J1lio);

     return $_J1lio;
 }

 function charset_decode_utf_8_preg_replace_callback1($_JItfQ){
   return '&#'.((ord($_JItfQ[1])-224)*4096 + (ord($_JItfQ[2])-128)*64 + (ord($_JItfQ[3])-128)).';';
 }

 function charset_decode_utf_8_preg_replace_callback2($_JItfQ){
   return '&#'.((ord($_JItfQ[1])-192)*64+(ord($_JItfQ[2])-128)).';';
 }

 function _OB16R($_JIt81, $_JItOI, $_I11oJ) {
   $_JIt81 = strtoupper($_JIt81);
   $_JItOI = strtoupper($_JItOI);
   if ( ($_JIt81 == "UTF-8") && ( ($_JItOI == "ISO-8859-1") || ($_JItOI == "ISO-8859-15") ) ) {
     $_I11oJ = _OAFJ6($_I11oJ);
     if(strpos($_I11oJ, "&#") === false) return true;
     $_QllO8 = explode("&#", $_I11oJ);
     for($_Q6llo=0; $_Q6llo<count($_QllO8); $_Q6llo++) {
        if(strpos($_QllO8[$_Q6llo], ";") === false) continue;
        while( strpos($_QllO8[$_Q6llo], ";") !== false ){
          $_QllO8[$_Q6llo] = substr($_QllO8[$_Q6llo], 0, strpos($_QllO8[$_Q6llo], ";"));
        }
        if(strlen($_QllO8[$_Q6llo]) > 2 && intval($_QllO8[$_Q6llo]) > 255 && $_QllO8[$_Q6llo] == (string)intval($_QllO8[$_Q6llo]) ) {
          return false;
        }
     }
     return true;
   } else
     return true;
 }

 include_once("utf8converter.inc.php");

 function ConvertString($_JIt81, $_JItOI, $_I11oJ, $_JIO08) {
   $_JIt81 = strtoupper($_JIt81);
   $_JItOI = strtoupper($_JItOI);
   if($_JIt81 != $_JItOI) {
     if($_JItOI ==  "WINDOWS-1255" && $_JIt81 == "UTF-8")
        $_I11oJ = utf8_to_windows1255($_I11oJ);
        else
        if ( function_exists('iconv') ) {
            $_I11oJ = rtrim(@iconv($_JIt81, $_JItOI, $_I11oJ));
        }
        else
        if( function_exists('mb_convert_encoding') ) {
          $_JIO8t = mb_convert_encoding($_I11oJ, $_JItOI, $_JIt81);
          if($_JIO8t != "")
            $_I11oJ = $_JIO8t;
        }
        else {
          if ( ($_JIt81 == "UTF-8") && ( ($_JItOI == "ISO-8859-1") || ($_JItOI == "ISO-8859-15") ) )
             $_I11oJ = utf8_decode($_I11oJ);
          if ( ($_JItOI == "UTF-8") && ( ($_JIt81 == "ISO-8859-1") || ($_JIt81 == "ISO-8859-15") ) )
             $_I11oJ = utf8_encode($_I11oJ);
        }
   }

   if($_JIO08 && !(stripos($_I11oJ, "charset=$_JIt81") === false)) {
      $_I11oJ=str_ireplace("charset=$_JIt81", "charset=$_JItOI", $_I11oJ);
   }
   return $_I11oJ;
 }

 function _OBQEP($_JIo1Q, $_I11oJ) {
   global $AppName;
   if(strpos($_JIo1Q, "[") !== false) // no placeholders!
     return;
   @mail($_JIo1Q, $AppName." - problems while email creating / sending", $_I11oJ, "From: ".$_JIo1Q);
 }

 function _OBOOC($UserId) {
   global $_Q8f1L, $_Q61I1;
   $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE `id`=".intval($UserId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   // SuperAdmin can only create admin users
   if($_Q6Q1C["UserType"] == "SuperAdmin") {
      $_I01C0 = Array ();
      _OA61D($_Q8f1L, $_I01C0);
      for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++) {
        if(strpos($_I01C0[$_Q6llo], "Privilege") !== false &&
           strpos($_I01C0[$_Q6llo], "PrivilegeUser") === false &&
           strpos($_I01C0[$_Q6llo], "PrivilegeBrandingEdit") === false &&
           strpos($_I01C0[$_Q6llo], "PrivilegeOptionsEdit") === false &&
           strpos($_I01C0[$_Q6llo], "PrivilegeDbRepair") === false &&
           strpos($_I01C0[$_Q6llo], "PrivilegeSystemTest") === false &&
           strpos($_I01C0[$_Q6llo], "PrivilegeViewProcessLog") === false &&
           strpos($_I01C0[$_Q6llo], "PrivilegeCron") === false
          )
           $_Q6Q1C[$_I01C0[$_Q6llo]] = 0;
      }

   }
   return $_Q6Q1C;
 }

 function _OBOAB($_ICLO8, $_JIoJO = array(), $_JICQ1 = array()){
   global $_Q61I1;
   if(!_OA1LL($_ICLO8)) {return;}
   $_QJlJ0 = "SELECT * FROM `$_ICLO8`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     foreach($_Q6Q1C as $key => $_Q6ClO) {
       if($_Q6ClO == "" || strpos($key, "TableName") === false || in_array($key, $_JIoJO) || in_array($_Q6ClO, $_JICQ1)) continue; // only Tables!!
       _OBOAB($_Q6ClO, $_JIoJO, $_JICQ1);
       mysql_query("DROP TABLE IF EXISTS `$_Q6ClO`", $_Q61I1);
     }
   }
   mysql_free_result($_Q60l1);
 }

 function _OBL6Q($_JICij, $_JIiJ1)
 {
     if($_JICij == "") return false; // empty string can force overflow, why?
     is_dir(dirname($_JICij)) || _OBL6Q(dirname($_JICij), $_JIiJ1);
     return is_dir($_JICij) || @mkdir($_JICij, $_JIiJ1);
 }

 function _OBLAR($_jlj0f){
   if(stripos($_jlj0f, "http://") !== false)
     return substr($_jlj0f, 7);
   if(stripos($_jlj0f, "https://") !== false)
     return substr($_jlj0f, 8);
   return $_jlj0f;
 }

 function _OBLCO($_JICij) {
   if(substr($_JICij, strlen($_JICij) - 1) == "/")
      return substr($_JICij, 0, strlen($_JICij) - 1);
      else
      return $_JICij;
 }

 function _OBLDR($_JICij) {
   if(substr($_JICij, strlen($_JICij) - 1) == "/")
      return $_JICij;
      else
      return $_JICij."/";
 }

 function _OBJAL($_jt8LJ){
   $_jt8LJ = basename($_jt8LJ);
   if(strrpos($_jt8LJ, '.') !== false)
      $_JIL0t = substr($_jt8LJ, strrpos($_jt8LJ, '.'));
      else
      $_JIL0t = "";
   return $_JIL0t;
 }

 function _OB68A($_J1lio, $_JILlj = array()) {
   $_JIlo1 = null;

   if (!empty($_JILlj)) {
     foreach ($_JILlj as $_Q6ClO) {
       $_JIlo1 .= "\\$value";
     }
   }

   $_Jj0IQ = $_J1lio;
   if(IsUTF8string($_Jj0IQ))
     $_Jj0IQ =  utf8_decode($_Jj0IQ);
   $_QJCJi = "";
   $_Jj0IQ = str_replace(" ", "_", $_Jj0IQ);
   $_Jj0IQ = str_replace("-", "_", $_Jj0IQ);
   for($_Q6llo=0; $_Q6llo<strlen($_Jj0IQ); $_Q6llo++) {
      if (
          (ord($_Jj0IQ{$_Q6llo}) >= 0x30 && ord($_Jj0IQ{$_Q6llo}) <= 0x39) ||
          (ord($_Jj0IQ{$_Q6llo}) >= 0x41 && ord($_Jj0IQ{$_Q6llo}) <= 0x5A) ||
          (ord($_Jj0IQ{$_Q6llo}) >= 0x61 && ord($_Jj0IQ{$_Q6llo}) <= 0x7A) ||
          (ord($_Jj0IQ{$_Q6llo}) == 0x5F) ||
          ($_Jj0IQ{$_Q6llo} == '.')
         ) {
           $_QJCJi = $_QJCJi . $_Jj0IQ{$_Q6llo};
         } else {
           switch(ord($_Jj0IQ{$_Q6llo})) {
             case 0xC4: $_QJCJi = $_QJCJi . "Ae";
                   break;
             case 0xDC: $_QJCJi = $_QJCJi . "Ue";
                   break;
             case 0xD6: $_QJCJi = $_QJCJi . "Oe";
                   break;
             case 0xE4: $_QJCJi = $_QJCJi . "ae";
                   break;
             case 0xFC: $_QJCJi = $_QJCJi . "ue";
                   break;
             case 0xF6: $_QJCJi = $_QJCJi . "oe";
                   break;
             case 0xDF: $_QJCJi = $_QJCJi . "ss";
                   break;
           }
         }
   }
   $_J1lio = $_QJCJi;

   include_once("functions_charmapping.inc.php");


   $_Jj0l0 = _OCP0R();

   if (is_array($_J1lio)) {

     $_Jj1tt = array();

     foreach ($_J1lio as $key => $_JjQ1C) {
       $_JjQ1C = strtr($_JjQ1C, $_Jj0l0);
       $_JjQ1C = preg_replace("/[^{$_JIlo1}_a-zA-Z0-9]/", '', $_JjQ1C);
       $_Jj1tt[$key] = preg_replace('/[_]+/', '_', $_JjQ1C); // remove double underscore
     }
   } else {
     $_J1lio = strtr($_J1lio, $_Jj0l0);
     $_J1lio = preg_replace("/[^{$_JIlo1}_a-zA-Z0-9]/", '', $_J1lio);
     $_Jj1tt = preg_replace('/[_]+/', '_', $_J1lio); // remove double underscore
   }
   return $_Jj1tt;
 }

 function _OBRJD($_QCoLj, $_jt8LJ){
   $_jt8LJ = basename($_jt8LJ);

   if(strrpos($_jt8LJ, '.') !== false) {
     $_JIL0t = substr($_jt8LJ, strrpos($_jt8LJ, '.'));
     $_jt8LJ = substr($_jt8LJ, 0, strrpos($_jt8LJ, '.'));
   } else
     $_JIL0t = "";

   if($_jt8LJ == "")
     $_jt8LJ = "file";

   $_jt8LJ = _OB68A($_jt8LJ);
   if($_jt8LJ == "")
     $_jt8LJ = "file";
   if($_JIL0t != "")
     $_jt8LJ .= $_JIL0t;

   if(!file_exists(_OBLDR($_QCoLj) . $_jt8LJ)) {
     return $_jt8LJ;
   }

   if(strrpos($_jt8LJ, '.') !== false) {
     $_JIL0t = substr($_jt8LJ, strrpos($_jt8LJ, '.'));
     $_jt8LJ = substr($_jt8LJ, 0, strrpos($_jt8LJ, '.'));
   } else
     $_JIL0t = "";
   if($_jt8LJ == "")
     $_jt8LJ = "file";

   $_Q6llo=0;
   $_JjQj8=2147483647;
   for($_Q6llo=0; $_Q6llo<$_JjQj8; $_Q6llo++){
     $_JjQio = $_jt8LJ.$_Q6llo.$_JIL0t;
     if(!file_exists(_OBLDR($_QCoLj) . $_JjQio))
       return $_JjQio;
   }
   // can't do anything
   return $_jt8LJ.$_JIL0t;
 }

 function _OB8O0($_JjIJI, $_Jjj0L, $_Q6ICj, $_I11oJ) {
   if(strpos($_Q6ICj, $_JjIJI) === false || strpos($_Q6ICj, $_Jjj0L) === false )
     return $_Q6ICj;
   $_Q66jQ = substr($_Q6ICj, 0, strpos($_Q6ICj, $_JjIJI) + strlen($_JjIJI));
   $_JQCIj = substr($_Q6ICj, strpos($_Q6ICj, $_Jjj0L));
   return $_Q66jQ.$_I11oJ.$_JQCIj;
 }

 function GetHTMLCharSet($_Q6ICj) {
   if(stripos($_Q6ICj, '</head>') === false)
     return "iso-8859-1";
   $_Q6ICj = substr($_Q6ICj, 0, stripos($_Q6ICj, '</head>') - 1);
   $_QllO8 = stripos ($_Q6ICj, 'content="text/html;');
   if($_QllO8 !== false) {
     $_Io0l8 = strpos_reverse($_Q6ICj, "<", $_QllO8);
     $_Q6ICj = substr($_Q6ICj, $_Io0l8);
     $_JjjC6 = strpos($_Q6ICj, ">");
     $_Q6ICj = substr($_Q6ICj, 0, $_JjjC6);
     $_Q6ICj = substr($_Q6ICj, stripos($_Q6ICj, "charset=") + strlen("charset="));
     if(strpos($_Q6ICj, '"') !== false)
        $_Q6ICj = substr($_Q6ICj, 0, strpos($_Q6ICj, '"'));
     $_Q6ICj = str_replace('"', '', $_Q6ICj);
     $_Q6ICj = str_replace(' ', '', $_Q6ICj);
     $_Q6ICj = str_replace('/', '', $_Q6ICj);
     return strtolower( $_Q6ICj );
   } else {
      // <meta charset="UTF-8">
      if(stripos ($_Q6ICj, 'charset=') === false)
        return "iso-8859-1";
      $_Q8otJ = explode("<meta", $_Q6ICj);
      for($_Q6llo=0; $_Q6llo < count($_Q8otJ); $_Q6llo++){
        $_Io0l8=stripos($_Q8otJ[$_Q6llo], "charset=");
        if($_Io0l8 !== false){
          $_Q6ICj = $_Q8otJ[$_Q6llo];
          $_JjjC6 = strpos($_Q6ICj, "<");
          if($_JjjC6 !== false)
            $_Q6ICj = substr($_Q6ICj, 0, $_JjjC6);
          if(count(explode("=", $_Q6ICj)) > 2)
           continue;

          $_JjjC6 = strpos($_Q6ICj, ">");
          $_Q6ICj = substr($_Q6ICj, 0, $_JjjC6);
          $_Q6ICj = substr($_Q6ICj, stripos($_Q6ICj, "charset=") + strlen("charset="));
          $_Q6ICj = str_replace('"', '', $_Q6ICj);
          $_Q6ICj = str_replace(' ', '', $_Q6ICj);
          $_Q6ICj = str_replace('/', '', $_Q6ICj);
          if($_Q6ICj != "")
            return strtolower( $_Q6ICj );
        }
      }
   }
   return "iso-8859-1";
 }

 function SetHTMLCharSet($_Q6ICj, $charset, $_JjJQQ = false) {
   if(stripos($_Q6ICj, "<head>") === false) {
     return $_Q6ICj;
   }
   $_JjJLo = '<meta http-equiv="content-type" content="text/html; charset='.strtolower($charset);

   if(!$_JjJQQ)
     $_JjJLo .= '">';
     else
     $_JjJLo .= '" />';

   $_QllO8 = stripos ($_Q6ICj, 'content="text/html;');
   $_Jj6C8=true;

   if($_QllO8 === false) {
      $_JjfJo = false;
      $_Iji86=substr($_Q6ICj, 0, stripos($_Q6ICj, '</head>') - 1);
      $_JjfJo=stripos ($_Iji86, 'charset=');
      if($_JjfJo !== false){
        $_QllO8=$_JjfJo;
        $_JjfJo=true;
        $_Jj6C8=false;
      } else
       $_JjfJo=false;

      if(!$_JjfJo) {
        $_Q6ICj = str_ireplace("<head>", "<head>$_JjJLo", $_Q6ICj);
        return $_Q6ICj;
      }
   }

   while($_QllO8 !== false) {
     // search vor <meta
     $_Io0l8 = strpos_reverse($_Q6ICj, "<", $_QllO8);
     $_Q66jQ = substr($_Q6ICj, 0, $_Io0l8);
     $_JQCIj = substr($_Q6ICj, $_Io0l8 + 1);
     $_JjjC6 = strpos($_JQCIj, ">");
     $_JQCIj = substr($_JQCIj, $_JjjC6 + 1);

     $_Q6ICj = $_Q66jQ.$_JQCIj;
     if($_Jj6C8)
       $_QllO8 = stripos ($_Q6ICj, 'content="text/html;');
       else
       $_QllO8 = stripos ($_Q6ICj, 'charset=');
   }
   $_Q6ICj = str_ireplace("<head>", "<head>$_JjJLo", $_Q6ICj);
   return $_Q6ICj;
 }

 function GetInlineFiles($_JjflQ, &$_jitLI, $_Jj8fl = true) {
   global $_IifjO;

   while(count($_jitLI) > 0)
     unset($_jitLI[0]);

   $_Jjtfi = array();
   // href
   preg_match_all('/[ \r\n]href\=([\"\']*)(.*?)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( !preg_match("/http:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/https:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/mailto:/i", $_JjOII[$_Q6llo][2]) && !preg_match("/\#/i", $_JjOII[$_Q6llo][2]) && !preg_match("/chrome:/i", $_JjOII[$_Q6llo][2]) && !preg_match("/javascript:/i", $_JjOII[$_Q6llo][2]) && !preg_match("/file:\/\//i", $_JjOII[$_Q6llo][2]) )
       if( preg_match("/\.css/i", $_JjOII[$_Q6llo][2]) || preg_match("/\.js/i", $_JjOII[$_Q6llo][2]) ) { // only css and js, ignore other files

          $_JjOC0 = false;
          reset($_IifjO);
          foreach($_IifjO as $_QllO8 => $key) {
            if( preg_match("/".$key."/i", $_JjOII[$_Q6llo][2]) ) {
              $_JjOC0 = true;
              break;
            }
          }

          if(!$_JjOC0)
            $_Jjtfi[] = $_JjOII[$_Q6llo][2];
       }
   }

   // src
   preg_match_all('/[ \r\n]src\=([\"\']*)(.*?)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( $_JjOII[$_Q6llo][1] == '""' || $_JjOII[$_Q6llo][1] == "''") continue;
     if( !preg_match("/http:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/https:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/chrome:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/javascript:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/file:\/\//i", $_JjOII[$_Q6llo][2]) )
       $_Jjtfi[] = $_JjOII[$_Q6llo][2];
   }

   // background
   preg_match_all('/[ \r\n]background\=([\"\']*)(.*?)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( $_JjOII[$_Q6llo][1] == '""' || $_JjOII[$_Q6llo][1] == "''") continue;
     if( !preg_match("/http:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/https:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/chrome:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/javascript:\/\//i", $_JjOII[$_Q6llo][2]) && !preg_match("/file:\/\//i", $_JjOII[$_Q6llo][2]) )
       $_Jjtfi[] = $_JjOII[$_Q6llo][2];
   }

   // background-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n]background-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( !preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/chrome:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/javascript:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/file:\/\//i", $_JjOII[$_Q6llo][1]) ){
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   // background: // immer auf ' " pruefen, die muessen weg
   //preg_match_all('/background:[\s*|a-z|0-9|\#|\:|\-]{0,}url\s*\(([\"\']*)(.*?)\)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   preg_match_all('/[ \r\n]background\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( !preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/chrome:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/javascript:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/file:\/\//i", $_JjOII[$_Q6llo][1]) ){
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   // list-style-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n]list-style-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( !preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/chrome:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/javascript:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/file:\/\//i", $_JjOII[$_Q6llo][1]) ){
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   // content: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n]content\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( !preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/chrome:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/javascript:\/\//i", $_JjOII[$_Q6llo][1]) && !preg_match("/file:\/\//i", $_JjOII[$_Q6llo][1]) ){
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   for($_Q6llo=0; $_Q6llo<count($_Jjtfi); $_Q6llo++) {
     $_Jjtfi[$_Q6llo] = str_replace("'", "", $_Jjtfi[$_Q6llo]);
     $_Jjtfi[$_Q6llo] = str_replace('"', "", $_Jjtfi[$_Q6llo]);
     $_Jjtfi[$_Q6llo] = str_replace('&quot;', "", $_Jjtfi[$_Q6llo]);
   }

   // only unique files
   $_QllO8 = array_unique($_Jjtfi);
   foreach ($_QllO8 as $key => $_Q6ClO)
     if( !preg_match("/\.ph.*?/i", $_Q6ClO) && !preg_match("/\.pl.*?/i", $_Q6ClO) && !preg_match("/\.cg.*?/i", $_Q6ClO) ){ # no php, pl, cgi files
        if($_Jj8fl && !(stripos($_Q6ClO, "data:image") === false)) continue;
        $_jitLI[] = $_Q6ClO;
     }

 }

 function _OBAQ8($_JjflQ, &$_QOLIl, $_JjOC1 = false, $_JjoOi = false) {
   while(count($_QOLIl) > 0)
     unset($_QOLIl[0]);
   $_JjoOL = array();

   // href
   preg_match_all('/<a.*?href\=([\"\']*)(.*?)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( $_JjOII[$_Q6llo][1] == '""' || $_JjOII[$_Q6llo][1] == "''") continue;

     if( preg_match("/^http:\/\//i", $_JjOII[$_Q6llo][2]) || preg_match("/^https:\/\//i", $_JjOII[$_Q6llo][2]) ){
          $_QJCJi = $_JjOII[$_Q6llo][2];
          $_QJCJi = _OBLAR($_QJCJi);
          if(strpos(_OBLCO($_QJCJi), "/") === false) // normal URL with / than remove trailing Slash
             $_JjoOL[] = _OBLCO( $_JjOII[$_Q6llo][2] );
             else
             $_JjoOL[] = $_JjOII[$_Q6llo][2];
     } else {
       if($_JjoOi)
          $_JjoOL[] = $_JjOII[$_Q6llo][2];
     }
   }

   // only unique links
   if(!$_JjOC1){
     $_QllO8 = array_unique($_JjoOL);
     foreach ($_QllO8 as $key => $_Q6ClO)
       $_QOLIl[] = $_Q6ClO;
   } else {
     $_QOLIl = array_merge($_QOLIl, $_JjoOL);
   }
 }

 function _OBAF1($_JjflQ, &$_QOLIl, $_JjOC1 = false) {
   while(count($_QOLIl) > 0)
     unset($_QOLIl[0]);
   $_JjoOL = array();

   // href
   preg_match_all('/<a.*?href\=([\"\']*)(.*?)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( $_JjOII[$_Q6llo][1] == '""' || $_JjOII[$_Q6llo][1] == "''") continue;

     if( preg_match("/^mailto:/i", $_JjOII[$_Q6llo][2]) )
          $_JjoOL[] = $_JjOII[$_Q6llo][2];
   }

   // only unique links
   if(!$_JjOC1){
     $_QllO8 = array_unique($_JjoOL);
     foreach ($_QllO8 as $key => $_Q6ClO)
       $_QOLIl[] = $_Q6ClO;
   } else {
     $_QOLIl = array_merge($_QOLIl, $_JjoOL);
   }
 }

 function _OBBPD($_JjflQ, &$_QOLIl, &$_QOLCo, $_JjOC1 = false, $_JjoOi = false) {
   global $_Q6QQL;

   _OBAQ8($_JjflQ, $_QOLIl, $_JjOC1, $_JjoOi);

   while(count($_QOLCo) > 0)
     unset($_QOLCo[0]);

   for($_Q6llo=0;$_Q6llo<count($_QOLIl);$_Q6llo++) {
     $_QJCJi = $_QOLIl[$_Q6llo];
     $_QJCJi = preg_quote($_QJCJi, "/");

     if ( preg_match('/[ \r\n]href\=[\"\']'.$_QJCJi.'[\"\'].*?>(.*?)<\/a>/is', $_JjflQ, $_JjOII) || preg_match('/[ \r\n]href\=[\"\']'.$_QJCJi."\/".'[\"\'].*?>(.*?)<\/a>/is', $_JjflQ, $_JjOII) ) {

          $_QllO8 = trim(_ODQAB($_JjOII[1], $_Q6QQL));
          if(!isset($_QllO8) || $_QllO8 == "" || strpos($_QllO8, "http:/") !== false || strpos($_QllO8, "https:/") !== false) {
               if(isset($_JjOII[1]) && preg_match_all('/<img.*?alt\=([\"\']*)(.*?)\1[\s\/>]/i', $_JjOII[1], $_JjC66, PREG_SET_ORDER) && isset($_JjC66[0][2]) ){ # linked images alt tag
                   $_QllO8 = trim(_ODQAB($_JjC66[0][2], $_Q6QQL));
                }

             if(!isset($_QllO8) || $_QllO8 == "")
               $_QllO8 = trim($_QOLIl[$_Q6llo]);

             }

          if(!$_JjOC1 && !$_JjoOi){
             // for tracking
             $_QllO8 = str_replace("&", " ", $_QllO8);
             $_QllO8 = str_replace("\r\n", " ", $_QllO8);
             $_QllO8 = str_replace("\r", " ", $_QllO8);
             $_QllO8 = str_replace("\n", " ", $_QllO8);
          }

          $_QOLCo[] = trim($_QllO8);
          if(!$_JjOC1) continue;

          $_JjflQ = preg_replace("/".preg_quote($_JjOII[0], "/")."/", "dummy />", $_JjflQ, 1);
      }
      else
      $_QOLCo[] = trim($_QOLIl[$_Q6llo]);
   }
 }

 function _OBBEA($_JjflQ, &$_jQtjQ, $_Jji60 = false) {

   while(count($_jQtjQ) > 0)
     unset($_jQtjQ[0]);

   $_Jjtfi = array();

   // src
   preg_match_all('/[ \r\n]src\=([\"\']*)(.*?)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( (preg_match("/http:\/\//i", $_JjOII[$_Q6llo][2]) || $_Jji60 && preg_match("/https:\/\//i", $_JjOII[$_Q6llo][2])) && !( preg_match("/\.css/i", $_JjOII[$_Q6llo][2]) || preg_match("/\.js/i", $_JjOII[$_Q6llo][2]) || preg_match("/\.ph.*?/i", $_JjOII[$_Q6llo][2]) || preg_match("/\.pl.*?/i", $_JjOII[$_Q6llo][2]) || preg_match("/\.cg.*?/i", $_JjOII[$_Q6llo][2]) ) )
       $_Jjtfi[] = $_JjOII[$_Q6llo][2];
   }

   // background
   preg_match_all('/[ \r\n]background\=([\"\']*)(.*?)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( $_JjOII[$_Q6llo][1] == '""' || $_JjOII[$_Q6llo][1] == "''") continue;
     if( preg_match("/http:\/\//i", $_JjOII[$_Q6llo][2]) || $_Jji60 && preg_match("/https:\/\//i", $_JjOII[$_Q6llo][2]) )
       $_Jjtfi[] = $_JjOII[$_Q6llo][2];
   }

   // background-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n]background-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) || $_Jji60 && preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) ) {
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   // background: // immer auf ' " pruefen, die muessen weg
   //preg_match_all('/background:[\s*|a-z|0-9|\#|\:|\-]{0,}url\s*\(([\"\']*)(.*?)\)\1[\s\/>]/is', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   preg_match_all('/[ \r\n]background\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) || $_Jji60 && preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) ) {
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   // list-style-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n]list-style-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) || $_Jji60 && preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) ) {
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   // content: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n]content\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_JjflQ, $_JjOII, PREG_SET_ORDER);
   for($_Q6llo=0;$_Q6llo<count($_JjOII);$_Q6llo++) {
     if( preg_match("/http:\/\//i", $_JjOII[$_Q6llo][1]) || $_Jji60 && preg_match("/https:\/\//i", $_JjOII[$_Q6llo][1]) ) {
       $_QJCJi = $_JjOII[$_Q6llo][1];
       if(strpos($_QJCJi, ");") !== false)
         $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ");"));
       $_Jjtfi[] = $_QJCJi;
     }
   }

   for($_Q6llo=0; $_Q6llo<count($_Jjtfi); $_Q6llo++) {
     $_Jjtfi[$_Q6llo] = str_replace("'", "", $_Jjtfi[$_Q6llo]);
     $_Jjtfi[$_Q6llo] = str_replace('"', "", $_Jjtfi[$_Q6llo]);
     $_Jjtfi[$_Q6llo] = str_replace('&quot;', "", $_Jjtfi[$_Q6llo]);
   }

   // only unique files
   $_QllO8 = array_unique($_Jjtfi);
   foreach ($_QllO8 as $key => $_Q6ClO)
     $_jQtjQ[] = $_Q6ClO;

 }

 function _OBCE8($_jt8LJ) {
   global $Mimetypes;

   if(strrpos($_jt8LJ, '.') !== false)
      $_JIL0t = substr($_jt8LJ, strrpos($_jt8LJ, '.') + 1);
      else
      $_JIL0t = "";
   $_I1t0l = stripos($Mimetypes, $_JIL0t."=");
   if($_I1t0l === false)
     return "application/octet-stream";
     else {
       $_QJCJi = substr($Mimetypes, $_I1t0l + strlen($_JIL0t."="));
       if(strpos($_QJCJi, ",") !== false)
          $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, ","));
       return $_QJCJi;
     }

 }

 function CheckFileNameForUTF8($_JjlIt) {
    if((utf8_decode($_JjlIt) != $_JjlIt) && (utf8_encode($_JjlIt) != $_JjlIt))
      return utf8_decode($_JjlIt);
      else
      return $_JjlIt;

 }

 function _OBDF6($_JjlJl, $_JjlOl = 2, $_JJ0t1 = false, $_JJ106 = false) {
  $_JJ1IL = $_JjlJl;

  if ($_JJ0t1)
    $_JJ1Ji = 'Byte';
    else
    if ($_JJ106)
       $_JJ1Ji = 'MB';
   else
    if ($_JJ1IL < 100)
        $_JJ1Ji = 'Byte';
        else
        if ($_JJ1IL >= 100 && $_JJ1IL < 1024 * 1024) {
         $_JJ1Ji = 'KB';
         $_JJ1IL = $_JJ1IL / 1024;
        }
        else
         if ($_JJ1IL < 1024 * 1024 * 1024) {
          $_JJ1Ji = 'MB';
          $_JJ1IL = $_JJ1IL / (1024 * 1024);
          if ($_JJ1IL == 0)
            $_JJ1Ji = 'Byte';
         }
         else
         {
          $_JJ1Ji = 'GB';
          $_JJ1IL = $_JJ1IL / (1024 * 1024 * 1024);
          if ($_JJ1IL == 0)
            $_JJ1Ji = 'Byte';
         }

  if(!$_JJ106) {
    if ($_JJ1IL > 0) {
      if ($_JJ1Ji != 'Byte')
        return trim(sprintf('%16.'.$_JjlOl.'f %s', $_JJ1IL, $_JJ1Ji));
        else
        return trim(sprintf('%16.0f %s', $_JJ1IL, $_JJ1Ji));
     }else
      return '0' . ' ' . $_JJ1Ji;
  }
  else
    return trim(sprinf('%16.'.$_JjlOl.'f %s', $_JJ1IL / (1024 * 1024), $_JJ1Ji));

 }

 function _OBEPD($_Q6ICj, &$errors) {
   global $resourcestrings, $INTERFACE_LANGUAGE;

    while(count($errors) > 0)
      unset($errors[0]);

    $_jitLI = array();
    GetInlineFiles($_Q6ICj, $_jitLI);
    $_jt8IL = InstallPath;

    for($_Q6llo=0; $_Q6llo< count($_jitLI); $_Q6llo++) {
      if(!@file_exists($_jitLI[$_Q6llo])) {
        $_QJCJi = _OBEDB($_jitLI[$_Q6llo]);
        $_jitLI[$_Q6llo] = $_QJCJi;
      }
      if(!file_exists($_jitLI[$_Q6llo])) {
        $errors[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImageOrFileNotFound"], $_jitLI[$_Q6llo]);
      }
    }
 }

 function _OBEDB($_QCttf) {

   if(strpos($_QCttf, BasePath) === false)
     return $_QCttf;

   if(strpos(InstallPath, BasePath) !== false) {
      if(BasePath != "/") { # not installed in root?
        $_jt8IL = substr(InstallPath, 0, strpos(InstallPath, BasePath));
        $_jt8IL .= $_QCttf;
        return $_jt8IL;
      } else{
        return InstallPath.$_QCttf;
      }
   }

   return $_QCttf;

 }

 function getOwnIP() {

    if (defined('AF_INET6') && isset($_SERVER['REMOTE_ADDR']) && function_exists("filter_var")) {
     if(filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
       return $_SERVER['REMOTE_ADDR'];
    }

    if(!isset($_SERVER['REMOTE_ADDR']))
      $_SERVER['REMOTE_ADDR'] = "127.0.0.1";

    /* php-magazin 05-07 (de) S. 38, by C.Fraunholz */
    /* modified version from internet */
    if (!(isset($_SERVER['HTTP_VIA']) || isset($_SERVER['HTTP_CLIENT_IP']))) {
        $_JJQJ1 = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
    } else {
       $_JJI1J = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
       $_JJI80 = "0.0.0.0";
       if (isset($_SERVER['HTTP_CLIENT_IP'])) {
          $_JJI80 = substr($_SERVER['HTTP_CLIENT_IP'], 0, strpos($_SERVER['HTTP_CLIENT_IP'],".")) * 1;
       } else {
           if (isset($_SERVER['HTTP_VIA'])) {
              $_JJI80 = substr($_SERVER['HTTP_VIA'], 0, strpos($_SERVER['HTTP_VIA'], ".")) * 1;
           }
       }
       if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
         $_JJjfl = substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ".")) * 1;
         else
         $_JJjfl = "";
       if ($_JJjfl != "" && !($_JJjfl == 10 || $_JJjfl == 192 || $_JJjfl == 127 || $_JJjfl == 224)) { // Proxy
         $_JJQJ1 = long2ip(ip2long($_SERVER['HTTP_X_FORWARDED_FOR']));
       } elseif(isset($_SERVER['HTTP_CLIENT_IP']) && !($_JJI80 == 10 || $_JJI80 == 192 || $_JJI80 == 127 || $_JJI80 == 224)) {
         $_JJQJ1 = long2ip(ip2long($_SERVER['HTTP_CLIENT_IP']));
       } else {
         $_JJQJ1 = $_JJI1J;
       }
    }
    $_QL8Q8 = strpos($_JJQJ1, ",");
    if ($_QL8Q8 !== false) $_JJQJ1 = substr($_JJQJ1, 0, $_QL8Q8);
    if(strpos($_JJQJ1, "'") !== false)
       $_JJQJ1 = str_replace("'", "", $_JJQJ1);

    if(defined("ip_address_mask_length") && $_JJQJ1 != "" && ip_address_mask_length > 0) {
       $_JJJI0 = ip2long($_JJQJ1);
       if($_JJJI0 > 0) {
         switch (ip_address_mask_length) {
           case 1:
           $_JJJI0 = $_JJJI0 & 0xFFFFFF00;
           break;
           case 2:
           $_JJJI0 = $_JJJI0 & 0xFFFF0000;
           break;
           case 3:
           $_JJJI0 = $_JJJI0 & 0xFF000000;
           break;
           case 4:
           $_JJJI0 = $_JJJI0 & 0x00000000;
           break;
         }
         $_JJQJ1 = long2ip($_JJJI0);
       }
    }
    return $_JJQJ1;
 }

 function CleanUpHTML($_Q6ICj, $_JJJfl = true){
   $_Q6ICj = str_replace('ï»¿', '', $_Q6ICj); # remove BOM
   $_Q6ICj = stripslashes($_Q6ICj);
   $_Q6ICj = str_replace('"file:///', '"_DO_NOT_USE_FILE_PROTOCOL_', $_Q6ICj);
   $_Q6ICj = str_replace('"file://', '"_DO_NOT_USE_FILE_PROTOCOL_', $_Q6ICj);
   $_Q6ICj = str_replace("?".ini_get("session.name")."=".session_id(), "", $_Q6ICj);
   $_Q6ICj = str_replace("&".ini_get("session.name")."=".session_id(), "", $_Q6ICj);
   $_Q6ICj = str_replace("&amp;".ini_get("session.name")."=".session_id(), "", $_Q6ICj);
   $_Q6ICj = str_replace('<script src="chrome://skype_ff_toolbar_win/content/injection_graph_func.js" id="injection_graph_func" charset="utf-8" type="text/javascript"></script>', "", $_Q6ICj);
   $_JJ66i = $_Q6ICj;

   // remove all scripts
   if($_JJJfl){
     $_JJ66i = preg_replace('/<script(.*?)>(.*?)<\/script>/is', '', $_JJ66i);
     $_JJ66i = preg_replace('/<object(.*?)>(.*?)<\/object>/is', '', $_JJ66i);
     $_JJ66i = preg_replace('/<embed(.*?)>(.*?)<\/embed>/is', '', $_JJ66i);
   }

   // FF bug url(&quot; &quot;);!!
   if(strpos($_JJ66i, " url(&quot;") !== false) {
     $_JJ66i = str_replace(" url(&quot;", " url('", $_JJ66i);
     $_JJ66i = str_replace("&quot;);", "');", $_JJ66i);
   }

   if($_JJJfl){
     $_JJ6L1 = array('onabort', 'onblur', 'onchange', 'onclick', 'ondblclick', 'onerror', 'onfocus', 'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup',  'onreset', 'onselect', 'onsubmit', 'onunload');

     reset($_JJ6L1);
     foreach($_JJ6L1 as $key => $_Q6ClO)
       $_JJ66i = preg_replace('/'.$_Q6ClO.'\=([\"\']*)(.*?)\1[\s\/>]/is', '', $_JJ66i);
   }

   $_Q6ICj = $_JJ66i;
   $_Q6ICj = FixCKEditorStyleProtectionForCSS($_Q6ICj);
   return $_Q6ICj;
 }

 function FixCKEditorStyleProtectionForCSS($_Q6ICj){
  $_Q6ICj = str_ireplace("<style", "<style", $_Q6ICj);
  $_Q6ICj = str_ireplace("</style>", "</style>", $_Q6ICj);
  $_QllO8 = explode("<style", $_Q6ICj);
  for($_Q6llo=0; $_Q6llo<count($_QllO8); $_Q6llo++) {
     if(strpos($_QllO8[$_Q6llo], "</style>") === false) continue;
     $_JjjC6 = explode("</style>", $_QllO8[$_Q6llo]);
     $_JjjC6[0] = str_replace("<!--", "", $_JjjC6[0]);
     $_JjjC6[0] = str_replace("//-->", "", $_JjjC6[0]);
     $_JjjC6[0] = str_replace("-->", "", $_JjjC6[0]);
     $_QllO8[$_Q6llo] = implode("</style>", $_JjjC6);
  }
  $_Q6ICj = implode("<style", $_QllO8);
  return $_Q6ICj;
 }

 if(!function_exists("sha1"))
   require_once("sha1.php");

 function _OC0DR($EMail){
   $EMail = strtolower($EMail);
   $_JJfjO = substr($EMail, strpos($EMail, "@"));
   $_QCioi = fopen(InstallPath."ECG/ecg-liste.hash", "rb");
   if($_QCioi === false)
     return false;
   if(version_compare(phpversion(), "5.0.0", "<") && !function_exists("sha1_str2blks_SHA1") ) { //sha1_str2blks_SHA1 is defined in sha1.php
     $EMail = sha1($EMail);
     $_JJfjO = sha1($_JJfjO);
   } else {
     $EMail = sha1($EMail, true);
     $_JJfjO = sha1($_JJfjO, true);
   }
   if(version_compare(phpversion(), "5.0.0", "<") && !function_exists("sha1_str2blks_SHA1") ) { //sha1_str2blks_SHA1 is defined in sha1.php
     $_Q66jQ = "";
     for($_Q6llo=0; $_Q6llo<strlen($EMail); $_Q6llo=$_Q6llo+2)
        $_Q66jQ .= chr(hexdec(substr($EMail, $_Q6llo, 2)));
     $EMail = $_Q66jQ;
     $_Q66jQ = "";
     for($_Q6llo=0; $_Q6llo<strlen($_JJfjO); $_Q6llo=$_Q6llo+2)
        $_Q66jQ .= chr(hexdec(substr($_JJfjO, $_Q6llo, 2)));
     $_JJfjO = $_Q66jQ;
   }
   while(!feof($_QCioi)) {
     $_QfoQo = fread($_QCioi, 20);
     if($EMail == $_QfoQo || $_JJfjO == $_QfoQo){
       fclose($_QCioi);
       return true;
     }
   }
   fclose($_QCioi);
   return false;
 }

 function _OC1CF(){
   $_JJftl = "";
   mt_srand((double)microtime()*1000000);
   $_IflL6 = mt_rand(32, 64);

   for ($_Q6llo = 0; $_Q6llo < $_IflL6; $_Q6llo++) {
     do {
      $_QL8Q8 = chr(mt_rand(48, 122));
     } while ( ($_QL8Q8 == '`') || ($_QL8Q8 == "'") || ($_QL8Q8 == "+") || ($_QL8Q8 == '"') || ($_QL8Q8 == "%") || ($_QL8Q8 == "&") || ($_QL8Q8 == "*") || ($_QL8Q8 == "?") || ($_QL8Q8 == "\\") || ($_QL8Q8 == '/') || ($_QL8Q8 == '"') || ($_QL8Q8 == '~') || ($_QL8Q8 == '{') || ($_QL8Q8 == '}') || ($_QL8Q8 == '[') || ($_QL8Q8 == ']') );
     $_JJftl .= $_QL8Q8;
   }

   if(version_compare(phpversion(), "5.0.0", "<") && !function_exists("sha1_str2blks_SHA1") ) { //sha1_str2blks_SHA1 is defined in sha1.php
     $_JJftl = sha1($_JJftl);
   } else {
     $_JJftl = sha1($_JJftl, false);
   }

   if(mt_rand(1, 1024) % 2 == 0)
     $_QJCJi = $_JJftl.substr($_JJftl, 0, 20).substr($_JJftl, 0, 20);
     else
     $_QJCJi = $_JJftl.substr($_JJftl, 20).substr($_JJftl, 20);
   while(strlen($_QJCJi) < 80)
     $_QJCJi = '0'.$_QJCJi;
   if(strlen($_QJCJi) > 80)
     $_QJCJi = substr($_QJCJi, 0, 80);
   return $_QJCJi;
 }

 function _OCQ6E($_j8O8t,$_QfJI8,$_QCoLj,$_Qf1i1,$_ILOjO=0, $_j8O60=80, $_JJfCl=false, $_JJfL8="", $_JJ8jj="", &$_j88of, &$_j8t8L) {
     global $_jJtt0;
     $_JJt0j = false;

     while (true){
       $_JJtoO = "";
       if (empty($_QfJI8))
           $_QfJI8 = 'GET';
       $_QfJI8 = strtoupper($_QfJI8);

       $_JJOQJ = $_j8O8t;
       if($_j8O60 == 443 && strpos($_JJOQJ, 'ssl://') === false)
          $_JJOQJ = 'ssl://'.$_JJOQJ;

       $_QCioi = fsockopen($_JJOQJ, $_j8O60, $_j88of, $_j8t8L, 30);
       if(!$_QCioi) {
         $_j88of = 600;
         $_j8t8L = "Can't connect to server.";
         return false;
       }

       if ($_QfJI8 == 'GET')
           $_QCoLj .= '?' . $_Qf1i1;
       $_JJOLI = "";
       $_JJOLI .= "$_QfJI8 $_QCoLj HTTP/1.0\r\n"; # for 1.1 we must http_chunked_decode() => wordpress plugin
       $_JJOLI .= "Host: $_j8O8t\r\n";

       if($_JJfCl && $_JJt0j){
         $_JJOLI .= "Authorization: Basic ".base64_encode($_JJfL8 . ":" . $_JJ8jj)."\r\n";
       }

       if ($_QfJI8 == 'POST'){
         $_JJOLI .= "Content-type: application/x-www-form-urlencoded\r\n";
         $_JJOLI .= "Content-length: " . strlen($_Qf1i1) . "\r\n";
       }
       if ($_ILOjO)
           $_JJOLI .= "User-Agent: MSIE\r\n";
           else
           $_JJOLI .= "User-Agent: $_jJtt0\r\n";

       $_JJOLI .= "Connection: close\r\n\r\n";
       if ($_QfJI8 == 'POST')
          $_JJOLI .= $_Qf1i1 . "\r\n\r\n";

       fputs($_QCioi, $_JJOLI);

       if(function_exists("stream_set_timeout") && function_exists("stream_set_blocking") && function_exists("stream_get_meta_data") )  {
          stream_set_blocking($_QCioi, TRUE);
          stream_set_timeout($_QCioi, 20);
          $_JJo0J = stream_get_meta_data($_QCioi);
          while ((!feof($_QCioi)) && (!$_JJo0J['timed_out'])) {
           $_JJtoO .= fgets($_QCioi, 128);
           $_JJo0J = stream_get_meta_data($_QCioi);
          }
        } else {
          sleep(2);
          while (!feof($_QCioi)) {
           $_JJtoO .= fgets($_QCioi, 128);
          }
        }
       fclose($_QCioi);
       $_JJolf = trim(substr($_JJtoO, 0, strpos($_JJtoO, " ")));
       $_JJCQ0 = trim(substr($_JJtoO, strpos($_JJtoO, " ")));
       $_JJCQ0 = trim(substr($_JJCQ0, 0, strpos($_JJCQ0, " ")));

       if($_JJCQ0 == 401 && $_JJfCl && !$_JJt0j){
         $_JJt0j = true;
         continue;
       } else
         return $_JJtoO;
     }
 }

 function _OCQDE($_j8O8t,$_QfJI8,$_QCoLj,$_Qf1i1,$_ILOjO=0, $_j8O60=80, $_JJfCl=false, $_JJfL8="", $_JJ8jj="", &$_j88of, &$_j8t8L) {
      global $_jJtt0;

   // don't wait for an result
       if (empty($_QfJI8))
           $_QfJI8 = 'GET';
       $_QfJI8 = strtoupper($_QfJI8);

       $_JJOQJ = $_j8O8t;
       if($_j8O60 == 443 && strpos($_JJOQJ, 'ssl://') === false)
          $_JJOQJ = 'ssl://'.$_JJOQJ;

       $_QCioi = fsockopen($_JJOQJ, $_j8O60, $_j88of, $_j8t8L, 30);
       if(!$_QCioi) {
         $_j88of = 600;
         $_j8t8L = "Can't connect to server.";
         return false;
       }

       if ($_QfJI8 == 'GET')
           $_QCoLj .= '?' . $_Qf1i1;
       $_JJOLI = "";
       $_JJOLI .= "$_QfJI8 $_QCoLj HTTP/1.0\r\n"; # for 1.1 we must http_chunked_decode() => wordpress plugin
       $_JJOLI .= "Host: $_j8O8t\r\n";

       if($_JJfCl && $_JJt0j){
         $_JJOLI .= "Authorization: Basic ".base64_encode($_JJfL8 . ":" . $_JJ8jj)."\r\n";
       }

       if ($_QfJI8 == 'POST'){
         $_JJOLI .= "Content-type: application/x-www-form-urlencoded\r\n";
         $_JJOLI .= "Content-length: " . strlen($_Qf1i1) . "\r\n";
       }
       if ($_ILOjO)
           $_JJOLI .= "User-Agent: MSIE\r\n";
           else
           $_JJOLI .= "User-Agent: $_jJtt0\r\n";

       $_JJOLI .= "Connection: close\r\n\r\n";
       if ($_QfJI8 == 'POST')
          $_JJOLI .= $_Qf1i1 . "\r\n\r\n";

       fputs($_QCioi, $_JJOLI);

       fclose($_QCioi);

       return false;
 }

 if(function_exists("stream_context_create")){

   function DoHTTPPOSTRequest($_j8O8t, $_QCoLj, $_Qf1i1, $_j8O60=80, $_JJCLi = array()){
     $_JJi0f = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';

     $_JJiiJ="";
     foreach($_JJCLi as $key => $_Q6ClO)
       $_JJiiJ .= "$key: $_Q6ClO\r\n";

     if(is_array($_Qf1i1))
       $_Qf1i1 = http_build_query($_Qf1i1);

     $_I16oJ = array(
             'http' => array(
                 'header' => "Content-type: application/x-www-form-urlencoded\r\n".$_JJiiJ,
                 'method' => "POST",
                 'content' => $_Qf1i1,
                 'Connection: close',
                 // Force the peer to validate (not needed in 5.6.0+, but still works
                 'verify_peer' => true,
                 'timeout' => 30,
                 $_JJi0f => $_j8O8t,
             ),
         );

     $_QllO8 = strpos($_QCoLj, "/");
     if($_QllO8 !== false && $_QllO8 == 0)
       $_JJLf0 = _OBLCO($_j8O8t).$_QCoLj;
       else
       $_JJLf0 = _OBLDR($_j8O8t).$_QCoLj;

     if($_j8O60 != 443)
       $_JJLf0 = "http://".$_JJLf0;
       else
       $_JJLf0 = "https://".$_JJLf0;


     $_JJLL6 = stream_context_create($_I16oJ);
     $_QoQOL = file_get_contents($_JJLf0, false, $_JJLL6);
     if($_QoQOL === false)
       return false;
     else
       return $_QoQOL;
   }

 }

 function _OCL0A($_JJl61, &$_II08o, $_JJl81 = ""){
    global $_QOCJo;

    $_IQC1o = $_JJl61[$_JJl81."MailFormat"];
    $_II0lj = strlen($_JJl61[$_JJl81."MailSubject"]) + 150; //150 for date:, subject:, from:, to:....
    if ($_IQC1o == "Multipart") {
      $_II0lj += strlen($_JJl61[$_JJl81."MailHTMLText"]) + strlen($_JJl61[$_JJl81."MailHTMLText"]) * 15 / 100;
      $_II0lj += strlen($_JJl61[$_JJl81."MailPlainText"]) + strlen($_JJl61[$_JJl81."MailPlainText"]) * 15 / 100;
    }
    if ($_IQC1o == "PlainText") {
      $_II0lj += strlen($_JJl61[$_JJl81."MailPlainText"]) + strlen($_JJl61[$_JJl81."MailPlainText"]) * 15 / 100;
    }
    if ($_IQC1o == "HTML") {
      $_II0lj += strlen($_JJl61[$_JJl81."MailHTMLText"]) + strlen($_JJl61[$_JJl81."MailHTMLText"]) * 15 / 100;
    }

    if ($_IQC1o == "Multipart" || $_IQC1o == "HTML" ) {

       $_jitLI = array();
       GetInlineFiles($_JJl61[$_JJl81."MailHTMLText"], $_jitLI);
       $_jt8IL = InstallPath;

       for($_Q6llo=0; $_Q6llo< count($_jitLI); $_Q6llo++) {
         $_II0lj += strlen( basename( $_jitLI[$_Q6llo] ) );
         if(!@file_exists($_jitLI[$_Q6llo])) {
           $_QJCJi = _OBEDB($_jitLI[$_Q6llo]);
           $_jitLI[$_Q6llo] = $_QJCJi;
         }
         $_I00tC = filesize($_jitLI[$_Q6llo]);
         $_II0lj += $_I00tC + $_I00tC * 30 / 100;
       }
    }


    if(isset($_JJl61[$_JJl81."Attachments"]))
       for($_Q6llo=0; $_Q6llo<count($_JJl61[$_JJl81."Attachments"]); $_Q6llo++) {
          $_II0lj += strlen( $_JJl61[$_JJl81."Attachments"][$_Q6llo] );
          $_I00tC = filesize($_QOCJo.$_JJl61[$_JJl81."Attachments"][$_Q6llo]);
          $_II0lj += $_I00tC + $_I00tC * 30 / 100;
       }

    $_II08o = ini_get("memory_limit");
    if(empty($_II08o))
      if( version_compare(phpversion(), "5.2.0", "<") )
         $_II08o = "8M";
         else
         $_II08o = "16M";
    if($_II08o == -1)
       $_II08o = "2GB"; # 32bit integer

    if(!(strpos($_II08o, "G") === false))
       $_II08o = $_II08o * 1024 * 1024 * 1024;
       else
       if(!(strpos($_II08o, "M") === false))
          $_II08o = $_II08o * 1024 * 1024;
          else
          if(!(strpos($_II08o, "K") === false))
             $_II08o = $_II08o * 1024;
             else
             $_II08o = $_II08o * 1;
    if($_II08o == 0)
      if( version_compare(phpversion(), "5.2.0", "<") )
         $_II08o = 8 * 1024 * 1024;
         else
         $_II08o = 16 * 1024 * 1024;
    return $_II0lj;
 }

 function _OCLD8($_Q8otJ, $_jQjOO){
   if( !is_array($_Q8otJ) || !is_array($_jQjOO) || count($_Q8otJ) != count($_jQjOO) )
     return false;
   $_Q60l1 = array_diff($_Q8otJ, $_jQjOO);
   if(count($_Q60l1) == 0)
      $_Q60l1 = array_diff($_jQjOO, $_Q8otJ);
   if(count($_Q60l1) == 0)
     return true;
     else
     return false;
 }

 function _OCJCC($MailingListId){
   global $_Q61I1, $_Q60QL, $_Q6fio, $OwnerUserId, $UserId;
   if($UserId == 0 || defined("CRONS_PHP") || defined("UserNewsletterPHP") || defined("DefaultNewsletterPHP")) return true;
   if(!isset($MailingListId) || empty($MailingListId) || is_array($MailingListId)) return true;
   $MailingListId = intval($MailingListId);
   $_QJlJ0 = "SELECT `$_Q60QL`.`id` FROM `$_Q60QL`";
   if($OwnerUserId == 0) // ist es ein Admin?
      $_QJlJ0 .= " WHERE (`users_id`=$UserId)";
      else {
       $_QJlJ0 .= " LEFT JOIN `$_Q6fio` ON `$_Q60QL`.`id`=`$_Q6fio`.`maillists_id` WHERE (`$_Q6fio`.`users_id`=$UserId) AND (`$_Q60QL`.`users_id`=$OwnerUserId)";
      }
   $_QJlJ0 .= " AND (`$_Q60QL`.`id` = $MailingListId)";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && ($_Q6Q1C = mysql_fetch_assoc($_Q60l1))) {
     mysql_free_result($_Q60l1);
     return $_Q6Q1C["id"] == $MailingListId;
   } else
     return false;
 }

 function _OC60P($_I1L81){
   if( strtolower($_I1L81) == "true" )
     return 1;
   if( strtolower($_I1L81) == "false" )
     return 0;
   $_I1L81 = intval($_I1L81);
   if($_I1L81<0) $_I1L81 = 0;
   if($_I1L81>1) $_I1L81 = 1;

   return $_I1L81;
 }

 function _OC6FL($_ICQQo){
   global $_Q61I1;
   $IP = getOwnIP();
   if($_ICQQo["LimitSubUnsubScripts"] == "Unlimited" || $IP == "") return false;
   // remove old records
   $_QJlJ0 = "DELETE FROM $_ICQQo[LimitSubUnsubScriptsTableName] WHERE NOW() >= DATE_ADD(`CreateDate`, INTERVAL 1 $_ICQQo[LimitSubUnsubScriptsLimitedRequestsInterval])";
   mysql_query($_QJlJ0, $_Q61I1);
   //_OAL8F($_QJlJ0);

   $_QJlJ0 = "SELECT COUNT(*) FROM $_ICQQo[LimitSubUnsubScriptsTableName] WHERE `IP`=". _OPQLR($IP);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);

   $_Q60l1 = $_Q6Q1C[0] > $_ICQQo["LimitSubUnsubScriptsLimitedRequests"];

   if(!$_Q60l1){
     $_QJlJ0 = "INSERT INTO $_ICQQo[LimitSubUnsubScriptsTableName] SET `CreateDate`=NOW(), `IP`="._OPQLR($IP);
     mysql_query($_QJlJ0, $_Q61I1);
   }

   return $_Q60l1;
 }

 function _OCRRL($_I11oJ, $_JJliO, &$_jj0JO){
   global $_Q61I1, $_Q60QL, $_III0L, $resourcestrings, $INTERFACE_LANGUAGE;
   $_jj0JO = "";

   if(!is_array($_JJliO) && intval($_JJliO) > 0){
      $_QJlJ0 = "SELECT `SubscriptionUnsubscription` FROM `$_Q60QL` WHERE `$_Q60QL`.`id`=".intval($_JJliO);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && ($_JJliO = mysql_fetch_assoc($_Q60l1))) {
        mysql_free_result($_Q60l1);
      }
   }

   if(!is_array($_JJliO) || !isset($_JJliO["SubscriptionUnsubscription"]) || $_JJliO["SubscriptionUnsubscription"] == "Allowed")
     return true;

   if($_JJliO["SubscriptionUnsubscription"] == 'SubscribeOnly' || $_JJliO["SubscriptionUnsubscription"] == 'Denied'){
     if (stripos($_I11oJ, $_III0L["UnsubscribeLink"]) !== false){
       $_jj0JO = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["PlaceholderBecauseOfMailingListRuleNotAllowed"], $_III0L["UnsubscribeLink"]);
       return false;
     }
   }

   if($_JJliO["SubscriptionUnsubscription"] == 'UnsubscribeOnly' || $_JJliO["SubscriptionUnsubscription"] == 'Denied'){
     if (stripos($_I11oJ, $_III0L["EditLink"]) !== false){
       $_jj0JO = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["PlaceholderBecauseOfMailingListRuleNotAllowed"], $_III0L["EditLink"]);
       return false;
     }
   }

   return true;
 }

 function _OCR88($_Q80Qf, $_I16oJ = 0){
   if(version_compare(PHP_VERSION, "5.3") >= 0){
     return json_encode($_Q80Qf, $_I16oJ);
   }
   /*if(version_compare(PHP_VERSION, "5.2") >= 0){
     //return json_encode($_Q80Qf); // 5.2 doesn't support $_I16oJ
     return internal_json_encode($_Q80Qf, $_I16oJ);
   } */
   // older PHP versions
   // function in jsonencode.inc.php
   return internal_json_encode($_Q80Qf, $_I16oJ);
 }

 # PHP 5.4 and newer PEAR check
 if(!function_exists("IsPEARError")){
   function IsPEARError($_Qf1i1){
     return is_a($_Qf1i1, 'PEAR_Error');
   }
 }

 if(!function_exists("PEARraiseError")){
   function PEARraiseError($_J601L){
     // PEAR::raiseError() is not PHP 5.4 compatible
     return new PEAR_Error($_J601L);
   }
 }


?>
