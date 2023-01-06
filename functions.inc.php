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

 include_once("htmltools.inc.php");
 include_once("sanitize.inc.php");
 include_once("csrf.inc.php");
 include_once("jsonencode.inc.php");
 if(defined("API"))
   include_once(InstallPath."api/api_base.php");

 define("iconvExists", function_exists('iconv'));
 define("mbfunctionsExists", function_exists('mb_convert_encoding'));
   
 function _LRRFJ($_6QolO) {
   global $_J18oI, $_jfOJj, $_ItL8f, $_J1t6J, $_IIlfi, $_IJi8f, $_J1tfC, $_QlLlJ;
   $_Qll8O = $_6QolO."/";
   $_J18oI = InstallPath."userfiles/".$_Qll8O;
   $_jfOJj = ScriptBaseURL."userfiles/".$_Qll8O;
   $_ItL8f = $_J18oI."import/";
   $_J1t6J = $_J18oI."export/";
   $_IIlfi = $_J18oI."file/";
   $_IJi8f = $_J18oI."image/";
   $_J1tfC = $_J18oI."media/";
   $_QlLlJ = $_J18oI."templates/";
 }

 function _LR8AP($_j661I){
   global $_IjljI, $_Ijt0i, $_jfQtI, $_Ifi1J, $_ItfiJ, $_I8tfQ, $_I8OoJ, $_jQ68I, $_jQf81, $_Ql10t, $_Ql18I, $_j6JfL;
   global $_QLi60, $_ICo0J, $_ICl0j, $_IoCo0, $_ICIJo, $_I616t, $_j6Ql8, $_j68Q0;
   global $_JQQiJ;
   global $_jJLQo;
   global $_j68Co;
   global $_jJL88;
   global $_jJLLf;
   global $_IjC0Q, $_IjCfJ;
   global $_QlfCL;

   $_IjljI = $_j661I["InboxesTableName"];
   $_Ijt0i = $_j661I["MTAsTableName"];
   $_jfQtI = $_j661I["PagesTableName"];
   $_Ifi1J = $_j661I["MessagesTableName"];
   $_I8tfQ = $_j661I["GlobalBlockListTableName"];
   $_I8OoJ = $_j661I["GlobalDomainBlockListTableName"];
   $_jQ68I = $_j661I["FunctionsTableName"];
   $_JQQiJ = $_j661I["MailsSentTableName"];
   $_ItfiJ = $_j661I["AutoImportsTableName"];

   $_IjC0Q = $_j661I["DistributionListsTableName"];
   $_IjCfJ = $_j661I["DistributionListsEntriesTableName"];

   $_QlfCL = $_j661I["TargetGroupsTableName"];

   // SWM
   if( defined("SWM") ) {
     $_jQf81 = $_j661I["TextBlocksTableName"];
     $_Ql10t = $_j661I["TemplatesTableName"];
     $_Ql18I = $_j661I["TemplatesToUsersTableName"];
     $_j6JfL = $_j661I["NewsletterArchivesTableName"];
     $_QLi60 = $_j661I["CampaignsTableName"];
     $_ICo0J = $_j661I["BirthdayMailsTableName"];
     $_ICl0j = $_j661I["BirthdayMailsStatisticsTableName"];
     $_IoCo0 = $_j661I["AutoresponderMailsTableName"];
     $_ICIJo = $_j661I["AutoresponderStatisticsTableName"];
     $_I616t = $_j661I["FollowUpMailsTableName"];
     $_j6Ql8 = $_j661I["EventMailsTableName"];
     $_j68Q0 = $_j661I["EventMailsStatisticsTableName"];
     $_jJLQo = $_j661I["RSS2EMailMailsTableName"];
     $_j68Co = $_j661I["RSS2EMailMailsStatisticsTableName"];
     $_jJL88 = $_j661I["SplitTestsTableName"];
     $_jJLLf = $_j661I["SMSCampaignsTableName"];
   } else {
     $_jQf81 = "";
     $_Ql10t = "";
     $_Ql18I = "";
     $_j6JfL = "";
     $_QLi60 = "";
     $_ICo0J = "";
     $_ICl0j = "";
     $_IoCo0 = "";
     $_ICIJo = "";
     $_I616t = "";
     $_j6Ql8 = "";
     $_j68Q0 = "";
     $_jJLQo = "";
     $_j68Co = "";
     $_jJL88 = "";
     $_jJLLf = "";
   }
 }

 function _LRP11(){
   global $UserId, $OwnerUserId, $_QLttI, $_I18lo;
   global $_jC80J, $_jC8Li, $_jCtCI, $_jCOO1, $_jCOlI, $_jCo0Q;
   global $_jCooQ, $_jCC16, $_jCC1i, $_jCi01, $_jCi8J;
   global $_jCiL1, $_jCLC8, $_jClC0;

   if( !defined("SWM") ) return;

   $_j6lIj = $OwnerUserId;
   if($_j6lIj == 0)
     $_j6lIj = $UserId;
   $_QLfol = "SELECT * FROM `$_I18lo` WHERE `id`=$_j6lIj";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != ""){
     print "Load User Settings MySQL-ERROR ".mysql_error($_QLttI)."<br /><br />SQL: $_QLfol";
     die;
   }
   if(!($_j661I = mysql_fetch_assoc($_QL8i1))){
     print "Load User Settings MySQL-ERROR "."No result for SQL query"."<br /><br />SQL: $_QLfol";
     die;

   }
   mysql_free_result($_QL8i1);

   $_jC80J = $_j661I["CampaignsCurrentSendTableName"];
   $_jC8Li = $_j661I["CampaignsCurrentUsedMTAsTableName"];
   $_jCtCI = $_j661I["CampaignsArchiveTableName"];
   $_jCOO1 = $_j661I["CampaignsGroupsTableName"];
   $_jCOlI = $_j661I["CampaignsNotInGroupsTableName"];
   $_jCo0Q = $_j661I["CampaignsMTAsTableName"];
   $_jCooQ = $_j661I["CampaignsRStatisticsTableName"];
   $_jCC16 = $_j661I["CampaignsLinksTableName"];
   $_jCC1i = $_j661I["CampaignsTrackingOpeningsTableName"];
   $_jCi01 = $_j661I["CampaignsTrackingOpeningsByRecipientTableName"];
   $_jCi8J = $_j661I["CampaignsTrackingLinksTableName"];
   $_jCiL1 = $_j661I["CampaignsTrackingLinksByRecipientTableName"];
   $_jCLC8 = $_j661I["CampaignsTrackingUserAgentsTableName"];
   $_jClC0 = $_j661I["CampaignsTrackingOSsTableName"];
 }

 function _LRPQ6($lang){
   global $INTERFACE_LANGUAGE, $_QLttI, $ShortDateFormat, $LongDateFormat;
   if(empty($lang)) $lang = $INTERFACE_LANGUAGE;
   if($lang == "de") {
      @setlocale (LC_ALL, 'de_DE');
      @setlocale (LC_TIME, 'de_DE');
      $ShortDateFormat = 'd.m.Y';
      $LongDateFormat = 'd.m.Y H:i:s';
   } else{
      @setlocale (LC_ALL, 'en_US');
      @setlocale (LC_TIME, 'en_US');
      $ShortDateFormat = 'Y/m/d';
      $LongDateFormat = 'Y/m/d H:i:s';
   }

   if(function_exists("date_default_timezone_set"))
      @date_default_timezone_set(_LRPAF($lang));
   @mysql_query('SET time_zone = ' . _LRAFO(_LRPAF($lang)), $_QLttI);
 }
 
 function _LRPAF($lang = ""){
   global $INTERFACE_LANGUAGE;
   if(empty($lang)) $lang = $INTERFACE_LANGUAGE;
   if($lang == "de") {
      return "Europe/Berlin";
   } else{
      return "Europe/London";
   }
 }

 function SetHTMLHeaders($_QLo06, $_6QCt8 = true, $_6QCli = "text/html") {
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
   if($_6QCt8)
     @header('X-Frame-Options: SAMEORIGIN');

   if($_6QCli == "")
     $_6QCli = "text/html";
   // Set the response format.
   @header( 'Content-Type: ' . $_6QCli . '; charset='.$_QLo06 ) ;
 }

 function SetHTTPResponseCode($_6QiQi, $_jfO0t){
   if($_6QiQi > 0){
       $_6QioQ    = substr(php_sapi_name(), 0, 3);
       if ($_6QioQ == 'cgi' || $_6QioQ == 'fpm') {
          @header('Status: '.$_6QiQi.' '.$_jfO0t);
       } else {
          $_6QiLO = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
          @header($_6QiLO.' '.$_6QiQi.' '.$_jfO0t);
       }
   }
 }

 function ClearLastError(){
   if(function_exists('error_clear_last'))
     error_clear_last();
     else{
       set_error_handler('var_dump', 0);
       restore_error_handler();
     }
 }

 function _LRAE6($_6QL68) {
     if(is_array($_6QL68))
       if( version_compare(PHP_VERSION, "5.0.0", "<") )
         return array_map(__FUNCTION__, $_6QL68);
         else
         return array_map(__METHOD__, $_6QL68);

     if(!empty($_6QL68) && is_string($_6QL68)) {
         return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $_6QL68);
     }

     return $_6QL68;
 }

 function _LRAFO($_QLJfI) {
   global $_QLttI;

   if( $_QLJfI == null ) 
    $_QLJfI = "";
   
   if(function_exists("mysql_real_escape_string") && isset($_QLttI) && $_QLttI)
     $_QLJfI = mysql_real_escape_string($_QLJfI, $_QLttI);
     else {
       $_QLJfI = _LRAE6($_QLJfI);
     }

   $_QLJfI = "'".$_QLJfI."'";

   return $_QLJfI;
 }

 function _LRBC0($_QLJfI) {
   $_QLJfI = str_replace ('"', '\"', $_QLJfI);
   $_QLJfI = str_replace ("'", "\\'", $_QLJfI);
   $_QLJfI = '"'.$_QLJfI.'"';
   return $_QLJfI;
 }

 function _LRCOC($_6QLLQ = 300) {
   @ignore_user_abort(true);
   @set_time_limit($_6QLLQ);
 }

 // \\ problem
 if ( (version_compare(PHP_VERSION, "5.4") < 0) && get_magic_quotes_gpc() && !function_exists("stripslashes_deep") ) {
    function stripslashes_deep($_QltJO) {
        $_QltJO = is_array($_QltJO) ? array_map('stripslashes_deep', $_QltJO) : (isset($_QltJO) ? stripslashes($_QltJO) : null);
        # urldecode wegen ctek server
#        if(!is_array($_QltJO)) {
#          $_I016j = stripos($_QltJO, "http:");
#          if($_I016j !== false && $_I016j == 0)
#             $_QltJO = urldecode($_QltJO);
#        }
        return $_QltJO;
    }
 }

# // \\ problem
# if ( !get_magic_quotes_gpc() ) {
#    function addslashes_deep($_QltJO) {
#        $_QltJO = is_array($_QltJO) ? array_map('addslashes_deep', $_QltJO) : (isset($_QltJO) ? addslashes($_QltJO) : null);
#        return $_QltJO;
#    }
# }

 function _LRCEO($_IOCjL) {
   if ( (version_compare(PHP_VERSION, "5.4") < 0) && get_magic_quotes_gpc() )
      return stripslashes_deep($_IOCjL);
      else
      return $_IOCjL;
 }

# function AddSlashs($_IOCjL) {
#   if ( !get_magic_quotes_gpc() )
#      return addslashes_deep($_IOCjL);
#      else
#      return $_IOCjL;
# }

 function _LRD81($_6QlI0, $_6I0QL, $_6I1QQ) {
   return str_replace ($_6QlI0, $_6I0QL, $_6I1QQ);
 }

 function _LRDB8($_6I1l6, $_6IQC6) { # return <> 0 then dec 1 !!!!!!!!!!
   $_IOO6C = strpos ($_6IQC6, $_6I1l6);
   if ($_IOO6C !== false)
     return $_IOO6C + 1;
     else
     return 0;
 }

 function strpos_reverse($_6IQC6, $_6IIIl, $_6IjIJ = -1) {
     // from http://de.php.net/manual/en/function.strpos.php
     // modified
     // $_6IjIJ = strpos($_6IQC6,$relativeChar);
     if($_6IjIJ < 0)
       $_6IjIJ = strlen($_6IQC6);
     $_6IJ1C = $_6IjIJ;
     $_6IJJ6 = '';
     //
     while ($_6IJJ6 != $_6IIIl) {
         $_6IJlI = $_6IJ1C-1;
         $_6IJJ6 = substr($_6IQC6,$_6IJlI,strlen($_6IIIl));
         if($_6IJJ6 === false) return FALSE;
         $_6IJ1C = $_6IJlI;
     }
     //
     if (!empty($_6IJJ6)) {
         //
         return $_6IJ1C;
         return TRUE;
     }
     else {
         return FALSE;
     }
     //
 }

 function _LRFOJ($_6IQC6, $_6IIIl, $_6IjIJ = -1) {
       // from http://de.php.net/manual/en/function.strpos.php
       // modified
       // $_6IjIJ = strpos($_6IQC6,$relativeChar);
       if($_6IjIJ < 0)
         $_6IjIJ = strlen($_6IQC6);
       $_6IJ1C = $_6IjIJ;
       $_6IJJ6 = '';
       $_6IIIl = strtolower($_6IIIl);
       //
       while (strtolower($_6IJJ6) != $_6IIIl) {
           $_6IJlI = $_6IJ1C-1;
           $_6IJJ6 = substr($_6IQC6,$_6IJlI,strlen($_6IIIl));
           if($_6IJJ6 === false) return FALSE;
           $_6IJ1C = $_6IJlI;
       }
       //
       if (!empty($_6IJJ6)) {
           //
           return $_6IJ1C;
           return TRUE;
       }
       else {
           return FALSE;
       }
       //
 }
 
 function _LRFCO($_6I68j, $_6If80, $_6IQC6){ 
   if (strpos($_6IQC6, $_6I68j) !== false){ 
      $_6I8Q1 = strpos($_6IQC6, $_6I68j); 
      return substr_replace($_6IQC6, $_6If80, strpos($_6IQC6, $_6I68j), strlen($_6I68j)); 
   } 
  return $_6IQC6; 
 }  
 
 function _L80R1($_6I68j, $_6If80, $_6IQC6){ 
   if (stripos($_6IQC6, $_6I68j) !== false){ 
      $_6I8Q1 = stripos($_6IQC6, $_6I68j); 
      return substr_replace($_6IQC6, $_6If80, stripos($_6IQC6, $_6I68j), strlen($_6I68j)); 
   } 
  return $_6IQC6; 
 }  

 function _L80DF($_QLoli, $_6It18, $_6ItL1 = "") {
   if($_6ItL1 == "")
     $_6ItL1 = substr_replace($_6It18, "/", 1, 0);

    if (strpos($_QLoli, $_6It18) === false || strpos($_QLoli, $_6ItL1) === false )
      return $_QLoli;
    $_I016j = strpos($_QLoli, $_6It18);
    $_6IOQj = substr($_QLoli, 0, $_I016j);
    $_6IOj1 = substr($_QLoli, strpos($_QLoli, $_6ItL1, $_I016j) + strlen($_6ItL1));
    return _L80DF($_6IOQj.$_6IOj1, $_6It18, $_6ItL1);
 }

 function _L81BJ($_QLoli, $_6It18, $_6ItL1, $_QltJO, $_6IOC6 = false) {
   if($_6ItL1 == "")
     $_6ItL1 = substr_replace($_6It18, "/", 1, 0);

    if (strpos($_QLoli, $_6It18) === false || strpos($_QLoli, $_6ItL1) === false )
      return $_QLoli;
    $_I016j = strpos($_QLoli, $_6It18);
    $_6IOQj = substr($_QLoli, 0, $_I016j);
    $_6IOj1 = substr($_QLoli, strpos($_QLoli, $_6ItL1, $_I016j) + strlen($_6ItL1));
    if(!$_6IOC6)
      return _L81BJ($_6IOQj.$_QltJO.$_6IOj1, $_6It18, $_6ItL1, $_QltJO);
      else
      return $_6IOQj.$_QltJO.$_6IOj1;
 }

 function _L81DB($_QLoli, $_6It18, $_6ItL1 = "") {
   if($_6ItL1 == "")
     $_6ItL1 = substr_replace($_6It18, "/", 1, 0);

    if (strpos($_QLoli, $_6It18) === false || strpos($_QLoli, $_6ItL1) === false )
      return "";
    $_QLoli = substr($_QLoli, strpos($_QLoli, $_6It18) + strlen($_6It18));
    $_QLoli = substr($_QLoli, 0, strpos($_QLoli, $_6ItL1)  );
    return $_QLoli;
 }

 function _L8QJA(&$_QLoli, $_6It18, $_6ItL1 = "") {
   $_6IOiI = _L81DB($_QLoli, $_6It18, $_6ItL1);
   $_QLoli = _L80DF($_QLoli, $_6It18, $_6ItL1);
   return $_6IOiI;
 }  

 function _L8Q6J($_QLoli, $_6It18, $_6ItL1, $_QltJO, $_6IoC1 = false) {
   if($_6ItL1 == "")
     $_6ItL1 = substr_replace($_6It18, "/", 1, 0);
   
   $_QLoli = _LPFQD($_6It18, $_6ItL1, $_QLoli, $_QltJO);
   if($_6IoC1)
      $_QLoli = _L8Q6J($_QLoli, $_6It18, $_6ItL1, $_QltJO, $_6IoC1);
   return $_QLoli;
 }

 function _L8Q6B($_6IoCL, $_6IC61, $_6Ii1j, $_6IilQ){
   if($_6Ii1j == "")
     $_6Ii1j = substr_replace($_6IC61, "/", 1, 0);
   $_ILJjL = _L81BJ($_6IoCL, $_6IC61, $_6Ii1j, $_6IC61 . $_6IilQ . $_6Ii1j, True);
   return $_ILJjL;
 }

 function _L8O0L($_6IoCL, $_6ILLJ, $_6IlJ1, $_6IllO, $_6j0O1 = "utf-8"){
    $_ILJjL = $_6IoCL;
    $_6j11L = _L81DB($_ILJjL, $_6ILLJ);
    if( strtolower($_6j0O1) == "utf-8")
      $_6j11L = str_replace($_6IlJ1, UTF8ToEntities($_6IllO), $_6j11L);
      else
      $_6j11L = str_replace($_6IlJ1, htmlspecialchars($_6IllO, ENT_COMPAT, $_6j0O1), $_6j11L);
    $_ILJjL = _L8Q6B($_ILJjL, $_6ILLJ, '', $_6j11L);
    return $_ILJjL;
 }
 
 function _L8OF8($_QLoli, $_6jQ0j){
   $_QLoli = str_ireplace($_6jQ0j, "", $_QLoli);
   $_QLoli = str_ireplace(substr_replace($_6jQ0j, "/", 1, 0), "", $_QLoli);
   return $_QLoli;
 }

 function _L8L0C($_QLoli, $_6jQ0j, $_6jQJl){
   $_QLoli = str_ireplace($_6jQ0j, "", $_QLoli);
   $_QLoli = str_ireplace($_6jQJl, "", $_QLoli);
   return $_QLoli;
 }

 function _L8L8D($_6IQC6, $_6jQOQ, $_6jIQL = "..."){
   global $_QLo06;
   $_6IQC6 = _LCRC8($_6IQC6);
   if(mbfunctionsExists){
     if(mb_strlen($_6IQC6, $_QLo06) > $_6jQOQ)
        return mb_substr($_6IQC6, 0, $_6jQOQ, $_QLo06) . $_6jIQL;
        else
        return $_6IQC6;
   }else{  // bytes
     if(strlen($_6IQC6) > $_6jQOQ)
        return substr($_6IQC6, 0, $_6jQOQ) . $_6jIQL;
        else
        return $_6IQC6;
   }
 }
 
 function _L8JLR($_ILotJ) {
   if ( preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $_ILotJ) ){
        return 1;
   }else {
        return 0;
   }
 }

 function _L8JAD(&$_ILotJ) {
   if(strpos($_ILotJ, '[') !== false && strpos($_ILotJ, ']') !== false && strpos($_ILotJ, '[') < strpos($_ILotJ, ']'))
      return 1;
   if ( _L8JEL($_ILotJ) ){
         $_ILotJ = _L86JE($_ILotJ);
         return 1;
   }else {
        return 0;
   }
 }

 function _L8JEL($_ILotJ, $_6jIji = true) {  // with IDN support
   if ( _L8JLR($_ILotJ) ){
        return 1;
   }else {
        if($_6jIji) {
          include_once(PEAR_PATH . "IDNA.php");

          $_6jICf = new Net_IDNA();
          try{
            $_6jj8C = $_6jICf->singleton();
            $_6jjl6 = $_6jj8C->encode($_ILotJ);
          } catch (Exception $_IjO6t) {
            return 0;
          }  
          if(isset($_6jjl6) && $_ILotJ != $_6jjl6)
             return _L8JLR($_6jjl6);
        }
        return 0;
   }
 }
 
 function _L86JE($_ILotJ){
    include_once(PEAR_PATH . "IDNA.php");

    $_6jICf = new Net_IDNA();
    $_6jj8C = $_6jICf->singleton();
    try{
      $_6jjl6 = $_6jj8C->encode($_ILotJ);
    } catch (Exception $_IjO6t) {
      return $_ILotJ;
    }  
    
    if(isset($_6jjl6) && $_ILotJ != $_6jjl6)
      return $_6jjl6;
      else
      return $_ILotJ;
 }

 function _L86B8($url) {
    $_IOO6C = strpos($url, 'http://');
    $_6jJi1 = strpos($url, 'https://');
    if($_IOO6C === false && $_6jJi1 === false)
      return false;
    if($_IOO6C !== false && $_IOO6C > 0)
       return false;
    if($_6jJi1 !== false && $_6jJi1 > 0)
       return false;
    if($_IOO6C !== false)
       $url = trim(substr($url, 7));
       else
       $url = trim(substr($url, 8));
    if($url == "")
      return false;

    $_IOO6C = strpos($url, 'www.');
    if($_IOO6C !== false )
       $url = trim(substr($url, 4));
    if($url == "")
      return false;

    $_IOO6C = strpos($url, '/');
    if($_IOO6C !== false )
       $url = substr($url, 0, $_IOO6C);
    if($url == "localhost") return true; // for local testing
    return _L8JEL("info@".$url);
 }

 function _L8R8E(&$_6j61J) {
    if (!(strpos($_6j61J, '@aol.de') === False)) {
      $_6j61J=_LRD81('@aol.de', "@aol.com", $_6j61J);
    }
    if (!(strpos($_6j61J, '@tonline.de') === False)) {
      $_6j61J=_LRD81('@tonline.de', "@t-online.de", $_6j61J);
    }
    if (!(strpos($_6j61J, '@-tonline.de') === False)) {
      $_6j61J=_LRD81('@-tonline.de', "@t-online.de", $_6j61J);
    }
    if ((strpos("a".$_6j61J, "www.") > 0)) {
       $_6j61J = substr($_6j61J, 4, strlen($_6j61J));
    }
 }

 function _L88RR($_I8I6o, $EMail) {
   global $_QLttI;
   $_QLfol = "SELECT `id` FROM `$_I8I6o` WHERE `u_EMail`="._LRAFO($EMail);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
     if($_QL8i1)
       mysql_free_result($_QL8i1);
     return false;
   } else {
     if($_QL8i1)
       mysql_free_result($_QL8i1);
     return true;
   }
 }

 function _L8P6B($_6j6if, $_6jfJ6, $_ILi8o, $_6jfit, $_6j8I0 = False, $_ji10i = "") {
   global $AppName, $php_errormsg, $_QLttI;

    # Demo version
    if(defined("DEMO") || defined("SimulateMailSending"))
       return true;

   // use SMTP, when MTAsTableName is given
   if($_ji10i != ""){
     $_QLfol = "SELECT * FROM `$_ji10i` WHERE `Type`='smtp'";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     
     include_once("mailer.php");

     while($_6j88I = mysql_fetch_assoc($_QL8i1)){
       $_j10IJ = new _LEFO8(mtInternalMail);
       $_j10IJ->DisableECGListCheck();
       $_j10IJ->From[] = array("address" => $_6j6if);
       $_j10IJ->To[] = array("address" => $_6jfJ6);
       $_j10IJ->Subject = $_ILi8o . " via smtp";
       $_j10IJ->TextPart = utf8_encode($_6jfit);
       $_j10IJ->charset = "utf-8";

       $_j10IJ->Sendvariant = $_6j88I["Type"]; 
       $_j10IJ->HELOName = $_6j88I["HELOName"];
       $_j10IJ->SMTPTimeout = $_6j88I["SMTPTimeout"];
       $_j10IJ->SMTPServer = $_6j88I["SMTPServer"];
       $_j10IJ->SMTPPort = $_6j88I["SMTPPort"];
       $_j10IJ->SMTPAuth = (bool)$_6j88I["SMTPAuth"];
       $_j10IJ->SMTPUsername = $_6j88I["SMTPUsername"];
       $_j10IJ->SMTPPassword = $_6j88I["SMTPPassword"];
       if(isset($_6j88I["SMTPSSL"]))
         $_j10IJ->SSLConnection = (bool)$_6j88I["SMTPSSL"];
       
       if($_j10IJ->_LEJE8($_j108i, $_j10O1)) {
         $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);
         if($_I1o8o) 
           return $_I1o8o;
       }  
       $_j10IJ = null;
     }
     mysql_free_result($_QL8i1);
   }
   
   // PHP mail()
  if($_6j6if == "")
    $_I6C0o = "From: webmaster@{$_SERVER['SERVER_NAME']}";
    else
    $_I6C0o = "From: $_6j6if";

  if($_6j8I0)
   $_I1jfC = "-fwebmaster@{$_SERVER['SERVER_NAME']}";
   else
   $_I1jfC = "";

   ClearLastError();
   $_6j8O1 = @ini_set('track_errors', 1);
   if($_I1jfC != "")
     $_I1o8o = mail($_6jfJ6, $_ILi8o, $_6jfit, $_I6C0o, $_I1jfC);
     else
     $_I1o8o = mail($_6jfJ6, $_ILi8o, $_6jfit, $_I6C0o);

   if(!$_I1o8o && !defined("CRONS_PHP")){
     if(function_exists("error_get_last"))
       print join(" ", error_get_last());
     else
       if(isset($php_errormsg))
         print $php_errormsg;
   }
   @ini_set('track_errors', $_6j8O1);
   return $_I1o8o;
 }

 function _L8AOB($_6jtI0, $_6jtfI, $_QLoli, $_6jOJO="missingvaluebackground") {
  for($_Qli6J=0; $_Qli6J<count($_6jtI0); $_Qli6J++) {
    $_QLoli = str_replace ('name="'.$_6jtI0[$_Qli6J].'"', 'name="'.$_6jtI0[$_Qli6J].'" class="' . $_6jOJO . '"', $_QLoli);
    $_QLoli = str_replace ('name="'.$_6jtI0[$_Qli6J].'[]"', 'name="'.$_6jtI0[$_Qli6J].'[]" class="' . $_6jOJO . '"', $_QLoli);
  }

  // Slash wegmachen
  foreach ($_6jtfI as $key => $_QltJO){
     $_6jtfI[$key] = _LRCEO($_QltJO);
     if( (strpos($key, "Subject") !== false || strpos($key, "MailPreHeaderText") !== false) && isset($_QltJO) ){
        $_6jtfI[$key] = str_replace('"', '&quot;', $_QltJO);
     }
  }

  if(isset($_6jtfI[SMLSWM_TOKEN_COOKIE_NAME]))
    unset($_6jtfI[SMLSWM_TOKEN_COOKIE_NAME]);

  reset($_6jtfI);
  foreach($_6jtfI as $_IOLil => $_IOCjL) {
    $_6jOOQ = false;
    $_I016j = strpos ($_QLoli, 'name="'.$_IOLil.'"');
    if($_I016j === false) {
       $_I016j = strpos ($_QLoli, 'name="'.$_IOLil.'[]"');
       if($_I016j === false) continue;
       $_6jOOQ = is_array($_IOCjL);
    }

    if($_I016j === false) continue;
    $_jJjQi = strpos_reverse($_QLoli, "<", $_I016j);
    if($_jJjQi == false)
      $_QLoli = str_replace ('name="'.$_IOLil.'"', 'name="'.$_IOLil.'" value="'.$_IOCjL.'"', $_QLoli);
      else {
       $_Ql0fO = substr($_QLoli, $_jJjQi);
       $_6jo8j = trim(substr($_Ql0fO, 1, strpos($_Ql0fO, " ") - 1));

       if($_6jo8j == "textarea") {
        $_Ql0fO = substr($_Ql0fO, 0, strpos($_Ql0fO, ">") + 1);
        $_6joLQ = $_Ql0fO;
        $_QLoli = str_replace ($_Ql0fO, $_6joLQ.$_IOCjL, $_QLoli);
       }
       elseif($_6jo8j == "select") {
         $_Ql0fO = substr($_Ql0fO, 0, strpos($_Ql0fO, "</select>") + strlen("</select>"));
         $_6joLQ = $_Ql0fO;
         if(!$_6jOOQ)
            $_6joLQ = str_replace ('value="'.$_IOCjL.'"', 'value="'.$_IOCjL.'" selected="selected"', $_6joLQ);
            else {
              for($_Qli6J=0; $_Qli6J<count($_IOCjL); $_Qli6J++) {
                $_6joLQ = str_replace ('value="'.$_IOCjL[$_Qli6J].'"', 'value="'.$_IOCjL[$_Qli6J].'" selected="selected"', $_6joLQ);
              }
            }
         $_QLoli = str_replace ($_Ql0fO, $_6joLQ, $_QLoli);
       }
       else {// normal <input
          $_Ql0fO = substr($_Ql0fO, 0, strpos($_Ql0fO, ">"));
          if( !(stripos($_Ql0fO, 'type="submit"') === false) ) continue;
          if( !(stripos($_Ql0fO, 'type="reset"') === false) ) continue;
          if( !(stripos($_Ql0fO, 'type="button"') === false) ) continue;
          if( !(stripos($_Ql0fO, 'type="image"') === false) ) continue;

          if( !(stripos($_Ql0fO, 'type="radio"') === false) ) { // wichtig immer name="" value="" sonst klappt es nicht
            $_QLoli = str_replace ('name="'.$_IOLil.'" value="'.$_IOCjL.'"', 'name="'.$_IOLil.'" value="'.$_IOCjL.'" checked="checked"', $_QLoli);
          } elseif( !(stripos($_Ql0fO, 'type="checkbox"') === false) ) {
            if(!$_6jOOQ) {
              if( isset($_IOCjL) && $_IOCjL != "")
                $_QLoli = str_replace ('name="'.$_IOLil.'"', 'name="'.$_IOLil.'" checked="checked" value="1"', $_QLoli);
              } else {
                for($_Qli6J=0; $_Qli6J<count($_IOCjL); $_Qli6J++)
                   $_QLoli = str_replace ('name="'.$_IOLil.'[]"', 'name="'.$_IOLil.'[]" checked="checked" value="1"', $_QLoli);
              }

          } else
             $_QLoli = str_replace ('name="'.$_IOLil.'"', 'name="'.$_IOLil.'" value="'.$_IOCjL.'"', $_QLoli);
         }
      }
  }
  return $_QLoli;
 }

 function _L8A8P($_IOCjL) {
   $_IOCjL = trim($_IOCjL);
   $_6jCIQ = preg_quote('\/:;*?"<>|=@[]^`´-', '/');
   $_IOCjL = preg_replace("/([\\x00-\\x2f\\x7b-\\xff{$_6jCIQ}])/", "", $_IOCjL);
   if($_IOCjL == "") 
     $_IOCjL = _LBQB1(16);

   if(ord($_IOCjL[0]) >= 0x30 && ord($_IOCjL[0]) <= 0x39){
     $_IOCjL = 'x'.$_IOCjL;
   }

   if (strlen($_IOCjL) > 63)
      $_IOCjL = substr($_IOCjL, 40);
      
   return strtolower($_IOCjL);
 }

 function _L8B1P($_6jCC0) {
   global $_QLttI;
   if($_6jCC0 == "") return false;
   $_QL8i1 = mysql_query("SHOW TABLES LIKE "._LRAFO($_6jCC0), $_QLttI);
   if( mysql_num_rows( $_QL8i1 ) == 0 )
      return false;
   while($_QLO0f = mysql_fetch_row($_QL8i1)) {
     if($_QLO0f[0] == $_6jCC0) {
       mysql_free_result($_QL8i1);
       return true;
     }
   }
   mysql_free_result($_QL8i1);
   return false;
 }

 function _L8BCR($_6jij6, $INTERFACE_LANGUAGE) {
   if(!isset($_6jij6) || $_6jij6 == "") return "";
   if(strpos($_6jij6, " ") !== false)
     $_6jij6 = substr($_6jij6, 0, strpos($_6jij6, " ") );
   if($INTERFACE_LANGUAGE == "de") // 2008-09-18
     return substr($_6jij6, 8, 2).".".substr($_6jij6, 5, 2).".".substr($_6jij6, 0, 4);
     else
     return $_6jij6;
 }

 function _L8CJF($_6jij6, $INTERFACE_LANGUAGE) {
   if(!isset($_6jij6) || $_6jij6 == "") return "";
   if(strpos($_6jij6, " ") !== false)
     $_6jij6 = substr($_6jij6, 0, strpos($_6jij6, " ") );
   if($INTERFACE_LANGUAGE == "de") // 2008-09-18
     return substr($_6jij6, 6, 4)."-".substr($_6jij6, 3, 2)."-".substr($_6jij6, 0, 2);
     else
     return $_6jij6;
 }

 function _L8CE0($_6jij6, $INTERFACE_LANGUAGE) {
   if(!isset($_6jij6) || $_6jij6 == "") return "";
   if(strpos($_6jij6, " ") === false)
     return _L8CJF($_6jij6, $INTERFACE_LANGUAGE);
   if($INTERFACE_LANGUAGE == "de") // 2008-09-18 00:00:00
     return substr($_6jij6, 6, 4)."-".substr($_6jij6, 3, 2)."-".substr($_6jij6, 0, 2).substr($_6jij6, strpos($_6jij6, " "));
     else
     return $_6jij6;
 }

 function _L8D00($_6jCC0) {
  $_QLJfI = _L8A8P(strtolower($_6jCC0));
  if(TablePrefix != "" && strpos($_QLJfI, TablePrefix) === false)
     $_QLJfI = TablePrefix.$_QLJfI;

  $_QLJfI = str_replace("-", "", $_QLJfI);
  $_QLJfI = str_replace("`", "", $_QLJfI);
  $_QLJfI = str_replace("´", "", $_QLJfI);
  $_QLJfI = str_replace("'", "", $_QLJfI);
  $_QLJfI = str_replace("\"", "", $_QLJfI);
  $_QLJfI = str_replace(" ", "_", $_QLJfI);
  $_6jCC0 = $_QLJfI;

  if(strlen($_6jCC0) >= 64) {
    $_6jCC0 = TablePrefix.substr($_6jCC0, 40);
    $_QLJfI = $_6jCC0;
  }

  $_Qli6J = 0;
  while ( _L8B1P($_QLJfI) ) {
   $_Qli6J++;
   $_QLJfI = $_6jCC0."_".$_Qli6J;
   if(strlen($_QLJfI) > 64) {
     $_6jCC0 = TablePrefix.substr($_6jCC0, 40);
     $_QLJfI = $_6jCC0;
   }
  }
  return $_QLJfI;
 }

 function _L8D88($_6jiij, $_6jLfL = null, $_6jLif = 0) {
   global $UserType, $Username, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;
   if($_6jLif == 0) {
       $_QLJfI = mysql_error($_QLttI);
       $_JJl1I = mysql_errno($_QLttI);
     }
     else{
       $_QLJfI = mysql_error($_6jLif);
       $_JJl1I = mysql_errno($_6jLif);
     }
   if($_QLJfI == "") return;

   if(defined("API")){
     if($_6jLfL != null) {
        return $_6jLfL->api_ShowSQLError($_6jiij);
       }
       else {
         print $_QLJfI." ".$_6jiij;
         exit;
       }
   }

   $_QLoli = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000017"], $resourcestrings[$INTERFACE_LANGUAGE]["000017"], 'DISABLED', 'sql_error_snipped.htm');
   $_QLoli = _L81BJ($_QLoli, "<SQL:ERROR>", "</SQL:ERROR>", $_QLJfI." ".$_JJl1I);
   $_QLoli = _L81BJ($_QLoli, "<SQL:QUERY>", "</SQL:QUERY>", $_6jiij);
   print $_QLoli;
   if($_6jLif == 0)
     mysql_query("ROLLBACK", $_QLttI);
     else
     mysql_query("ROLLBACK", $_6jLif);
   exit; // cancel script
 }

 function _L8DAO($_J0COJ, $_6jLfL = null) {
   global $UserType, $Username, $resourcestrings, $INTERFACE_LANGUAGE;

   if(defined("API")){
     if($_6jLfL != null) {
        return $_6jLfL->api_ShowSQLError($_J0COJ);
       }
       else {
         print $_J0COJ;
         exit;
       }
   }

   $_QLoli = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["ERROR"], $resourcestrings[$INTERFACE_LANGUAGE]["ERROR"], 'DISABLED', 'error_snipped.htm');
   $_QLoli = _L81BJ($_QLoli, "<ERROR>", "</ERROR>", $_J0COJ);
   print $_QLoli;
   exit; // cancel script
 }

 function _L8EOB($_jf1IJ, &$_Iflj0, $_6jl61 = array()) {
   global $_QLttI;
   if(!isset($_Iflj0))
      $_Iflj0 = array();
      else
      array_splice($_Iflj0, 0, count($_Iflj0) );

   $_QLfol = "SHOW COLUMNS FROM `$_jf1IJ`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if (!$_QL8i1) {
       _L8D88($_QLfol);
       exit;
   }
   if (mysql_num_rows($_QL8i1) > 0) {
       while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          foreach ($_QLO0f as $key => $_QltJO) {
             if ($key == "Field")  {
                if(isset($_6jl61))
                  if ( in_array($_QltJO, $_6jl61) ) break; // ignore
                $_Iflj0[] = $_QltJO;
                break;
             }
          }
       }
       mysql_free_result($_QL8i1);
   }
 }

 function _L8EC8($_6jl6i, $_jf1IJ, &$_Iflj0, $_6jl61 = array()) {
   if(!isset($_Iflj0))
      $_Iflj0 = array();
      else
      array_splice($_Iflj0, 0, count($_Iflj0) );

   $_QLfol = "SHOW COLUMNS FROM `$_jf1IJ`";
   $_QL8i1 = mysql_query($_QLfol, $_6jl6i);
   if (!$_QL8i1) {
       _L8D88($_QLfol, null, $_6jl6i);
       exit;
   }
   if (mysql_num_rows($_QL8i1) > 0) {
       while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          foreach ($_QLO0f as $key => $_QltJO) {
             if ($key == "Field")  {
                if(isset($_6jl61))
                  if ( in_array($_QltJO, $_6jl61) ) break; // ignore
                $_Iflj0[] = $_QltJO;
                break;
             }
          }
       }
       mysql_free_result($_QL8i1);
   }
 }

 function _L8FPJ($_jf1IJ, &$_Iflj0, $_6jl61 = array(), $_6jl86 = array()) {
   global $_QLttI;
   if(!isset($_Iflj0))
      $_Iflj0 = array();
      else
      array_splice($_Iflj0, 0, count($_Iflj0) );

   $_QLfol = "SHOW COLUMNS FROM `$_jf1IJ`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if (!$_QL8i1) {
       _L8D88($_QLfol);
       exit;
   }
   if (mysql_num_rows($_QL8i1) > 0) {
       while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          if ( in_array($_QLO0f["Field"], $_6jl61) ) continue; // ignore
          if(count($_6jl86)){
            $_QLCt1 = false;
            for($_Qli6J=0;$_Qli6J<count($_6jl86) && !$_QLCt1; $_Qli6J++){
              $_QLCt1 = stripos($_QLO0f["Type"], $_6jl86[$_Qli6J]) !== false;
            }
            if(!$_QLCt1)
              continue;
          } 
          $_Iflj0[$_QLO0f["Field"]] = $_QLO0f["Field"];
       }
       mysql_free_result($_QL8i1);
   }
 }

 function _L8FCO($_jf1IJ, &$_Iflj0, $_6jl61 = array()) {
   global $_QLttI;
   if(!isset($_Iflj0))
      $_Iflj0 = array();
      else
      array_splice($_Iflj0, 0, count($_Iflj0) );

   $_QLfol = "SHOW COLUMNS FROM `$_jf1IJ`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if (!$_QL8i1) {
       _L8D88($_QLfol);
       exit;
   }
   if (mysql_num_rows($_QL8i1) > 0) {
       while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          // Fieldname
          foreach ($_QLO0f as $key => $_QltJO) {
             if ($key == "Field")  {
                if(isset($_6jl61))
                  if ( in_array($_QltJO, $_6jl61) ) {
                     break; // ignore
                  }
                $_IliOC = $_QltJO;
             }

             if ($key == "Type")  {
                if(strpos($_QltJO, "tinyint") === false) {
                  $_IliOC = "";
                }
                break;
             }
          }
          if($_IliOC != "")
            $_Iflj0[] = $_IliOC;
       }
       mysql_free_result($_QL8i1);
   }
 }

 function _LP006($_jf1IJ, $_IliOC) {
   global $_QLttI;
   $_QLfol = "SHOW COLUMNS FROM `$_jf1IJ`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if (!$_QL8i1) {
       _L8D88($_QLfol);
       exit;
   }
   $_QLCt1 = false;
   $_6jlCO = "";
   if (mysql_num_rows($_QL8i1) > 0) {
       while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          foreach ($_QLO0f as $key => $_QltJO) {
             if ($key == "Field" && $_IliOC == $_QltJO)  {
                $_QLCt1 = true;
             }
             if($_QLCt1 && $key == "Type") {
               $_6jlCO = $_QltJO;
               break;
             }

          }
          if($_QLCt1 && $_6jlCO != "")
            break;
       }
       mysql_free_result($_QL8i1);
   }
   return $_6jlCO;
 }

 function _LP0PL($_jf1IJ, $_IliOC) {
   global $_QLttI;
   if($_jf1IJ == "") return true;
   $_QLfol = "SHOW COLUMNS FROM `$_jf1IJ` WHERE Field="._LRAFO($_IliOC);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_error($_QLttI) != "")
     return false;
   if (mysql_num_rows($_QL8i1) > 0) {
     mysql_free_result($_QL8i1);
     return true;
   }
   mysql_free_result($_QL8i1);
   return false;
 }

  function _LP0CO($_jf1IJ, $_6J001, $_6jl61 = array()){
    global $_QLttI;
    
    if(!_L8B1P($_jf1IJ)) return false;
    
    if(strpos(_LP006($_jf1IJ, $_6J001), 'varchar') !== false){
      $_Iflj0 = array();
      _L8FPJ($_jf1IJ, $_Iflj0, $_6jl61, array('varchar'));
      
      $_QLfol = "ALTER TABLE `$_jf1IJ` ";
      $_Qli6J=0;
      foreach($_Iflj0 as $key => $_QltJO){
        if($_Qli6J > 0)
          $_QLfol = $_QLfol . ",";
        $_Qli6J++;
        $_QLfol = $_QLfol . "CHANGE `$key` `$key` TEXT NOT NULL";
      }
      
      mysql_query("BEGIN", $_QLttI);
      mysql_query($_QLfol, $_QLttI);
      //print $_QLfol . '<br>';
      mysql_query("COMMIT", $_QLttI);
    }
  }
 
 function _LP1AQ($_jf1IJ, $_6J0Jf = array(), $_6J0iI = array()){
   global $_QLttI;
   if(!_L8B1P($_jf1IJ)) {return;}
   $_QLfol = "SELECT * FROM `$_jf1IJ`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     foreach($_QLO0f as $key => $_QltJO) {
       if($_QltJO == "" || strpos($key, "TableName") === false || in_array($key, $_6J0Jf) || in_array($_QltJO, $_6J0iI)) continue; // only Tables!!
       _LP1AQ($_QltJO, $_6J0Jf, $_6J0iI);
       mysql_query("DROP TABLE IF EXISTS `$_QltJO`", $_QLttI);
     }
   }
   mysql_free_result($_QL8i1);
 }

 function _LPQ08($_IfLJj, $MailingListId, $FormId) {
     // create confirmation IdentString
     $_6J1Qo = sprintf("%02X", $_IfLJj);
     $_6J1L0 = sprintf("%02X", $MailingListId);
     $_6JQot = sprintf("%02X", $FormId);

     if(empty($_SERVER["REMOTE_ADDR"])) // PHP error undefined index
       $_SERVER["REMOTE_ADDR"] = date("U");
     $_6JIjo = strtoupper( $_6J1Qo."-".$_6J1L0."-".$_6JQot."-".md5($_SERVER["REMOTE_ADDR"])."-".md5(date("r"))."-".md5($_6J1Qo.$_6J1L0.$_6JQot) );
     if(strlen($_6JIjo) > 64)
        $_6JIjo = substr($_6JIjo, 0, 63);
     return $_6JIjo;
 }

 function _LPQ8Q($_6JItl, $_IfLJj, $MailingListId, $FormId, $_I8I6o="") {
    global $_QL88I, $_QLttI;
    $_IfLJj = intval($_IfLJj);
    $MailingListId = intval($MailingListId);
    $FormId = intval($FormId);
    $_6JIjo = $_6JItl;
    $_6JjfO = true;
    if($_6JItl != "") {
      $_I1OoI = explode("-", $_6JItl);
      if( count($_I1OoI)> 3 && hexdec($_I1OoI[0]) == $_IfLJj && hexdec($_I1OoI[1]) == $MailingListId && hexdec($_I1OoI[2]) == $FormId )
        $_6JjfO = false;
    }

    if($_6JjfO) {
      $_6JIjo = _LPQ08($_IfLJj, $MailingListId, $FormId);
      if($_I8I6o == "") {
        $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE id=$MailingListId";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
        $_I8I6o = $_QLO0f["MaillistTableName"];
      }
      $_QLfol = "UPDATE `$_I8I6o` SET `IdentString`="._LRAFO($_6JIjo)." WHERE `id`=$_IfLJj";
      mysql_query($_QLfol, $_QLttI);
    }
    return $_6JIjo;
 }

 function _LPQEP($key, &$_IfLJj, &$MailingListId, &$FormId, $rid = "") {
   $_IfLJj = 0;
   $MailingListId = 0;
   $FormId = 0;
   $_I1OoI = explode("-", $key);
   if(count($_I1OoI) < 3) return false;
   $_6J1Qo = $_I1OoI[0];
   $_6J1L0 = $_I1OoI[1];
   $_6JQot = $_I1OoI[2];
   $_IfLJj = hexdec($_6J1Qo);
   $MailingListId = hexdec($_6J1L0);
   $FormId = hexdec($_6JQot);

   // rid can override form_id to get correct form
   if(!empty($rid)){
      $_I1OoI = explode("_", $rid);
      if(count($_I1OoI) > 4){
        if($_I1OoI[4][0] == "x" && hexdec(substr($_I1OoI[4], 1)))
          $FormId = hexdec(substr($_I1OoI[4], 1));
      }
   }

   return true;
 }

 function _LPO6C($_6JJ6L){
    if($_6JJ6L == "AutoResponder")
      return rtAutoResponders;
    if($_6JJ6L == "BirthdayResponder")
      return rtBirthdayResponders;
    if($_6JJ6L == "EventResponder")
      return rtEventResponders;
    if($_6JJ6L == "FollowUpResponder")
      return rtFUResponders;
    if($_6JJ6L == "Campaign")
      return rtCampaigns;
    if($_6JJ6L == "RSS2EMailResponder")
      return rtRSS2EMailResponders;
    if($_6JJ6L == "DistributionList")
      return rtDistributionLists;
    return 0;
 }

 function _LPORA($_6JJt1){
  switch ($_6JJt1) {
    case rtAutoResponders:
    return "AutoResponder";
    break;
    case rtBirthdayResponders:
    return "BirthdayResponder";
    break;
    case rtEventResponders:
    return "EventResponder";
    break;
    case rtFUResponders:
    return "FollowUpResponder";
    break;
    case rtCampaigns:
    return "Campaign";
    break;
    case rtRSS2EMailResponders:
    return "RSS2EMailResponder";
    case rtDistributionLists:
    return "DistributionList";
    break;
  }
  return "";
 }


 /**
  * Gets table name field in users table
  *
  */

 function _LPOD8($_6JJt1){
   $_jfJJ0 = "";
   switch ($_6JJt1) {
     case rtAutoResponders:
     $_jfJJ0 = "AutoresponderMailsTableName";
     break;
     case rtBirthdayResponders:
     $_jfJJ0 = "BirthdayMailsTableName";
     break;
     case rtEventResponders:
     $_jfJJ0 = "EventMailsTableName";
     break;
     case rtFUResponders:
     $_jfJJ0 = "FollowUpMailsTableName";
     break;
     case rtCampaigns:
     $_jfJJ0 = "CampaignsTableName";
     break;
     case rtRSS2EMailResponders:
     $_jfJJ0 = "RSS2EMailMailsTableName";
     break;
     case rtDistributionLists:
     $_jfJJ0 = "DistributionListsTableName";
     break;
   }
  return $_jfJJ0;
 }

 /**
  * Gets statistics table name field in users table
  *
  */

 function _LPL8A($_6JJt1){
   $_jfJJ0 = "";
   switch ($_6JJt1) {
     case rtAutoResponders:
     $_jfJJ0 = "AutoresponderStatisticsTableName";
     break;
     case rtBirthdayResponders:
     $_jfJJ0 = "BirthdayMailsStatisticsTableName";
     break;
     case rtEventResponders:
     $_jfJJ0 = "EventMailsStatisticsTableName";
     break;
     case rtFUResponders:
     $_jfJJ0 = "";
     break;
     case rtCampaigns:
     $_jfJJ0 = "";
     break;
     case rtRSS2EMailResponders:
     $_jfJJ0 = "RSS2EMailMailsStatisticsTableName";
     break;
     case rtDistributionLists:
     $_jfJJ0 = "";
     break;
   }
  return $_jfJJ0;
 }

 /**
  * Gets table name for responder
  *
  */

 function _LPLBQ($_6JJt1){
   global $_IoCo0, $_ICo0J, $_j6Ql8, $_I616t, $_QLi60, $_jJLQo, $_IjC0Q;
   $_jfJJ0 = "";
   switch ($_6JJt1) {
     case rtAutoResponders:
     $_jfJJ0 = $_IoCo0;
     break;
     case rtBirthdayResponders:
     $_jfJJ0 = $_ICo0J;
     break;
     case rtEventResponders:
     $_jfJJ0 = $_j6Ql8;
     break;
     case rtFUResponders:
     $_jfJJ0 = $_I616t;
     break;
     case rtCampaigns:
     $_jfJJ0 = $_QLi60;
     break;
     case rtRSS2EMailResponders:
     $_jfJJ0 = $_jJLQo;
     break;
     case rtDistributionLists:
     $_jfJJ0 = $_IjC0Q;
     break;
   }
  return $_jfJJ0;
 }

 function _LPJ1D($rid, $_6JJLQ, $ListId = "") {
   global $_I18lo, $_QLl1Q, $_QLttI;

   $_I1OoI = explode("_", $rid);
   if(count($_I1OoI) < 4) {
     return false;
   }
   $_jfQLo = intval( hexdec($_I1OoI[0]) );
   $_jfIoi = hexdec($_I1OoI[1]);
   $ResponderType = hexdec($_I1OoI[2]);
   $ResponderId = intval( hexdec($_I1OoI[3]) );

   if(!defined("SWM") && $ResponderType != rtDistributionLists) { // 6=DistribList, SML can't do others
    return false;
   }

   if($ResponderType == rtAutoResponders)
    return false;
   
   $_jfJJ0 = _LPOD8($ResponderType);

   if($_jfJJ0 == "") {
     return false;
   }
   
   $_6J6o0 = _LPL8A($ResponderType);
   $_ji080 = "";

   $_QLfol = "SELECT `$_jfJJ0`";
   if(!empty($_6J6o0))
     $_QLfol .= ", `$_6J6o0`";
   $_QLfol .= " FROM `$_I18lo` WHERE `id`=".intval($_jfIoi);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
     return false;
   } else {
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_jfJJ0 = $_QLO0f[0];
     if(count($_QLO0f) > 1)
       $_ji080 = $_QLO0f[1];
     mysql_free_result($_QL8i1);
   }

   if($ResponderType == rtCampaigns || $ResponderType == rtDistributionLists) { // Campaign, DistributionList
     $_QLfol = "SELECT `CurrentSendTableName` FROM `$_jfJJ0` WHERE `id`=" . $ResponderId;
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
       return false;
     } else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
     }

     if($_6JJLQ)
       $_QLfol = "UPDATE `$_QLO0f[CurrentSendTableName]` SET `HardBouncesCount`=`HardBouncesCount`+1 WHERE `id`=" . $_jfQLo;
       else
       $_QLfol = "UPDATE `$_QLO0f[CurrentSendTableName]` SET `SoftBouncesCount`=`SoftBouncesCount`+1 WHERE `id`=" . $_jfQLo;
   } else {
     if($_6JJLQ)
       $_QLfol = "UPDATE `$_jfJJ0` SET `HardBouncesCount`=`HardBouncesCount`+1 WHERE `id`=" . $ResponderId;
       else
       $_QLfol = "UPDATE `$_jfJJ0` SET `SoftBouncesCount`=`SoftBouncesCount`+1 WHERE `id`=" . $ResponderId;
   }

   mysql_query($_QLfol, $_QLttI);
   
   if($_6JJLQ && !empty($ListId)){
     $ListId = explode("-", $ListId);
     if(count($ListId) == 2){ //MailingListId-RecipientsId
       $ListId[0] = intval($ListId[0]);
       $ListId[1] = intval($ListId[1]);
       if($ListId[0] && $ListId[1]){
         
         // check MailingList is always assigned
         if($ResponderType == rtCampaigns || $ResponderType == rtDistributionLists || $ResponderType == rtFUResponders) { 
           $_QLfol = "SELECT `RStatisticsTableName` FROM `$_jfJJ0` WHERE `id`=" . $ResponderId . " AND `maillists_id`=" . $ListId[0];
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           
           if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
             $_ji080 = $_QLO0f["RStatisticsTableName"];
             mysql_free_result($_QL8i1);
           } else
             return false;
           
           
         }else{
           if(empty($_ji080))
             return false;
           $_QLfol = "SELECT COUNT(`id`) FROM `$_jfJJ0` WHERE `id`=" . $ResponderId . " AND `maillists_id`=" . $ListId[0];
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if($_QLO0f = mysql_fetch_row($_QL8i1)){
             mysql_free_result($_QL8i1);
             if($_QLO0f[0] != 1)
               return false;
           }else
             return false;
         }  
         // check MailingList is always assigned /

         if(empty($_ji080)) return false;
         
         $_QLfol = "UPDATE $_ji080 SET `Send`='Hardbounced', `SendResult`=" . _LRAFO("hard bounced back") . " WHERE `recipients_id`=" . $ListId[1];
         
         switch ($ResponderType) {
           case rtAutoResponders:
           // not supported here;
           break;
           case rtBirthdayResponders:
           $_QLfol .= " AND `birthdayresponders_id`=$ResponderId";
           break;
           case rtEventResponders:
           $_QLfol .= " AND `eventresponders_id`=$ResponderId";
           break;
           case rtFUResponders:
           //  with have no `fumails_id`, so all emails for this recipient will be failed
           break;
           case rtCampaigns:
           $_QLfol .= " AND `SendStat_id`=$_jfQLo";
           break;
           case rtRSS2EMailResponders:
           $_QLfol .= " AND `rss2emailresponders_id`=$ResponderId";
           break;
           case rtDistributionLists:
           $_QLfol .= " AND `SendStat_id`=$_jfQLo";
           break;
         }
         
         mysql_query($_QLfol, $_QLttI);
         
       }
     }
   }

   return true;
 }

 function _LPJPD($rid) {
   global $_I18lo, $_QLl1Q, $_QLttI;

   $_I1OoI = explode("_", $rid);
   if(count($_I1OoI) < 4) {
     return false;
   }
   $_jfQLo = hexdec($_I1OoI[0]);
   $_jfIoi = hexdec($_I1OoI[1]);
   $ResponderType = hexdec($_I1OoI[2]);
   $ResponderId = hexdec($_I1OoI[3]);

   if(!defined("SWM") && $ResponderType != rtDistributionLists) { // 6=DistribList, SML can't do others
    return false;
   }

   $_jfJJ0 = _LPOD8($ResponderType);

   if($_jfJJ0 == "") {
     return false;
   }

   $_QLfol = "SELECT `$_jfJJ0` FROM `$_I18lo` WHERE `id`=".intval($_jfIoi);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
     return false;
   } else {
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_jfJJ0 = $_QLO0f[0];
     mysql_free_result($_QL8i1);
   }

   if($ResponderType == rtCampaigns || $ResponderType == rtDistributionLists) { // Campaign, DistributionList
     $_QLfol = "SELECT `CurrentSendTableName` FROM `$_jfJJ0` WHERE id=".intval($ResponderId);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
       return false;
     } else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
     }

     $_QLfol = "UPDATE `$_QLO0f[CurrentSendTableName]` SET `UnsubscribesCount`=`UnsubscribesCount`+1 WHERE id=".intval($_jfQLo);
   } else {
     $_QLfol = "UPDATE `$_jfJJ0` SET `UnsubscribesCount`=`UnsubscribesCount`+1 WHERE id=".intval($ResponderId);
   }
   mysql_query($_QLfol, $_QLttI);

   return true;
 }

 function _LP6JL($MailingListId, $_jO6t1){
   global $_QLttI, $_QL88I;
   global $_QLi60, $_ICo0J, $_IoCo0, $_I616t, $_j6Ql8;
   global $_jJLQo;
   global $_jJL88;
   global $_jJLLf;
   global $_IjC0Q;

   $_QLfol = "SELECT `forms_id` FROM `$_QL88I` WHERE `id`=$MailingListId AND `forms_id`=$_jO6t1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0){
     mysql_free_result($_QL8i1);
     return true;
   } else
     if($_QL8i1)
       mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `forms_id` FROM `$_IjC0Q` WHERE `maillists_id`=$MailingListId AND `forms_id`=$_jO6t1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0){
     mysql_free_result($_QL8i1);
     return true;
   } else
     if($_QL8i1)
       mysql_free_result($_QL8i1);


   if( defined("SWM") ) {
     $_J1QfQ = array($_IoCo0, $_ICo0J, $_QLi60, $_I616t, $_j6Ql8, $_jJLQo, $_jJL88, $_jJLLf);

     reset($_J1QfQ);
     foreach($_J1QfQ as $key => $_QltJO) {
       if(!_L8B1P($_QltJO)) continue;
       $_QLfol = "SELECT `forms_id` FROM `$_QltJO` WHERE `maillists_id`=$MailingListId AND `forms_id`=$_jO6t1";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(mysql_num_rows($_QL8i1) > 0){
         mysql_free_result($_QL8i1);
         return true;
       } else
         if($_QL8i1)
           mysql_free_result($_QL8i1);
     }
   }

   return false;
 }

// function IsUTF8string($_6IQC6)
// {
//   return (utf8_encode(utf8_decode($_6IQC6)) == $_6IQC6);
//   return (utf8_decode($_6IQC6) != $_6IQC6) && (utf8_encode($_6IQC6) != $_6IQC6);
// }

 function _LPROA($_6I1QQ) {
     $_6JfQj = strlen($_6I1QQ);
     for($_Qli6J = 0; $_Qli6J < $_6JfQj; $_Qli6J++){
         $_Ift08 = ord($_6I1QQ[$_Qli6J]);
         if ($_Ift08 > 128) {
             if (($_Ift08 > 247)) return false;
             elseif ($_Ift08 > 239) $_6JfJ6 = 4;
             elseif ($_Ift08 > 223) $_6JfJ6 = 3;
             elseif ($_Ift08 > 191) $_6JfJ6 = 2;
             else return false;
             if (($_Qli6J + $_6JfJ6) > $_6JfQj) return false;
             while ($_6JfJ6 > 1) {
                 $_Qli6J++;
                 $_jl0Ii = ord($_6I1QQ[$_Qli6J]);
                 if ($_jl0Ii < 128 || $_jl0Ii > 191) return false;
                 $_6JfJ6--;
             }
         }
     }
     return true;
 } // end of check_utf8

 function IsUtf8String( $_QLJfI ) {
     return _LPROA($_QLJfI);

     # problem segmentation fault one some apache webserver because of bug in libs for text > 10KB
     $_6Jfot  = '[\x00-\x7F]';
     $_6J8JQ = '[\xC2-\xDF][\x80-\xBF]';
     $_6J8lJ = '[\xE0-\xEF][\x80-\xBF]{2}';
     $_6JtJQ = '[\xF0-\xF4][\x80-\xBF]{3}';
     $_6Jt6I = '[\xF8-\xFB][\x80-\xBF]{4}';
     $_6JOJo = '[\xFC-\xFD][\x80-\xBF]{5}';
     $_QL8i1 = preg_match("/^($_6Jfot|$_6J8JQ|$_6J8lJ|$_6JtJQ|$_6Jt6I|$_6JOJo)*$/s", $_QLJfI);

     if($_QL8i1)
       $_QL8i1 = (utf8_decode($_QLJfI) != "");

     return $_QL8i1;
 }

 function _LP8LJ ($_6IQC6) {
   return UTF8ToEntities($_6IQC6);
 }

 function _LP8C1($_6JOiO, $_6JoOj, $_IO08l) {
   $_6JOiO = strtoupper($_6JOiO);
   $_6JoOj = strtoupper($_6JoOj);
   if ( ($_6JOiO == "UTF-8") && ( ($_6JoOj == "ISO-8859-1") || ($_6JoOj == "ISO-8859-15") ) ) {
     if(_LC6RR($_IO08l))
       return false; 
     $_IO08l = _LP8LJ($_IO08l);
     if(strpos($_IO08l, "&#") === false) return true;
     $_I016j = explode("&#", $_IO08l);
     for($_Qli6J=0; $_Qli6J<count($_I016j); $_Qli6J++) {
        if(strpos($_I016j[$_Qli6J], ";") === false) continue;
        while( strpos($_I016j[$_Qli6J], ";") !== false ){
          $_I016j[$_Qli6J] = substr($_I016j[$_Qli6J], 0, strpos($_I016j[$_Qli6J], ";"));
        }
        if(strlen($_I016j[$_Qli6J]) > 2 && intval($_I016j[$_Qli6J]) > 255 && $_I016j[$_Qli6J] == (string)intval($_I016j[$_Qli6J]) ) {
          return false;
        }
     }
     return true;
   } else
     return true;
 }

 include_once("utf8converter.inc.php");

 function ConvertString($_6JOiO, $_6JoOj, $_IO08l, $_6JC6j) {
   $_6JOiO = strtoupper($_6JOiO);
   $_6JoOj = strtoupper($_6JoOj);
   if($_6JOiO != $_6JoOj) {
     if($_6JoOj ==  "WINDOWS-1255" && $_6JOiO == "UTF-8")
        $_IO08l = utf8_to_windows1255($_IO08l);
        else
        if ( iconvExists ) {
            $_6JiJ6 = rtrim(@iconv($_6JOiO, $_6JoOj . "//TRANSLIT", $_IO08l)); // can give notice convert error
            if($_6JiJ6 != "")
              $_IO08l = $_6JiJ6;
              else
                if( mbfunctionsExists ) {
                  $_6JiJ6 = mb_convert_encoding($_IO08l, $_6JoOj, $_6JOiO);
                  if($_6JiJ6 != "")
                    $_IO08l = $_6JiJ6;
                }
        }
        else
        if( mbfunctionsExists ) {
          $_6JiJ6 = mb_convert_encoding($_IO08l, $_6JoOj, $_6JOiO);
          if($_6JiJ6 != "")
            $_IO08l = $_6JiJ6;
        }
        else {
          if ( ($_6JOiO == "UTF-8") && ( ($_6JoOj == "ISO-8859-1") || ($_6JoOj == "ISO-8859-15") ) )
             $_IO08l = utf8_decode($_IO08l);
          if ( ($_6JoOj == "UTF-8") && ( ($_6JOiO == "ISO-8859-1") || ($_6JOiO == "ISO-8859-15") ) )
             $_IO08l = utf8_encode($_IO08l);
        }
   }

   if($_6JC6j && !(stripos($_IO08l, "charset=$_6JOiO") === false)) {
      $_IO08l = str_ireplace("charset=$_6JOiO", "charset=$_6JoOj", $_IO08l);
   }
   return $_IO08l;
 }

 function _LPAOD($_6JL1l, $_IO08l) {
   global $AppName, $_Ijt0i;
   if(strpos($_6JL1l, "[") !== false) // no placeholders!
     return;
   _L8P6B($_6JL1l, $_6JL1l, $AppName." - problems while email creating / sending", $_IO08l, False, !empty($_Ijt0i) ? $_Ijt0i : "");
 }

 function _LPALQ($UserId) {
   global $_I18lo, $_QLttI;
   if($UserId == 0)
     return array();
   $_QLfol = "SELECT * FROM `$_I18lo` WHERE `id`=".intval($UserId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   // SuperAdmin can only create admin users
   if($_QLO0f["UserType"] == "SuperAdmin") {
      $_ItI0o = Array ();
      _L8FCO($_I18lo, $_ItI0o);
      for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++) {
        if(strpos($_ItI0o[$_Qli6J], "Privilege") !== false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeUser") === false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeBrandingEdit") === false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeOptionsEdit") === false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeDbRepair") === false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeSystemTest") === false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeViewProcessLog") === false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeCron") === false &&
           strpos($_ItI0o[$_Qli6J], "PrivilegeOnlineUpdate") === false
          )
           $_QLO0f[$_ItI0o[$_Qli6J]] = 0;
      }

   }
   return $_QLO0f;
 }

 function _LPABA($_6JL81, $_6JLlC)
 {
     if($_6JL81 == "") return false; // empty string can force overflow, why?
     is_dir(dirname($_6JL81)) || _LPABA(dirname($_6JL81), $_6JLlC);
     return is_dir($_6JL81) || @mkdir($_6JL81, $_6JLlC);
 }

 function _LPB6Q($url){
   if(stripos($url, "http://") !== false)
     return substr($url, 7);
   if(stripos($url, "https://") !== false)
     return substr($url, 8);
   return $url;
 }

 function _LPBCC($_6JL81) {
   if(substr($_6JL81, strlen($_6JL81) - 1) == "/")
      return substr($_6JL81, 0, strlen($_6JL81) - 1);
      else
      return $_6JL81;
 }

 function _LPC1C($_6JL81) {
   if(substr($_6JL81, strlen($_6JL81) - 1) == "/")
      return $_6JL81;
      else
      return $_6JL81."/";
 }

 function ExtractFileExt($_JfIIf){
   $_JfIIf = basename($_JfIIf);
   if(strrpos($_JfIIf, '.') !== false)
      $_6JlLi = substr($_JfIIf, strrpos($_JfIIf, '.'));
      else
      $_6JlLi = "";
   return $_6JlLi;
 }

 function _LPDDC($_6IQC6, $_660ij = array()) {
   $_6610I = "\\[\\]";

   if (!empty($_660ij)) {
     foreach ($_660ij as $_QltJO) {
       $_6610I .= "\\$value";
     }
   }

   $_661t1 = $_6IQC6;
   if(IsUTF8string($_661t1))
     $_661t1 =  utf8_decode($_661t1);
   $_QLJfI = "";
   $_661t1 = str_replace(" ", "_", $_661t1);
   $_661t1 = str_replace("-", "_", $_661t1);
   for($_Qli6J=0; $_Qli6J<strlen($_661t1); $_Qli6J++) {
      if (
          (ord($_661t1[$_Qli6J]) >= 0x30 && ord($_661t1[$_Qli6J]) <= 0x39) ||
          (ord($_661t1[$_Qli6J]) >= 0x41 && ord($_661t1[$_Qli6J]) <= 0x5A) ||
          (ord($_661t1[$_Qli6J]) >= 0x61 && ord($_661t1[$_Qli6J]) <= 0x7A) ||
          (ord($_661t1[$_Qli6J]) == 0x5F) ||
          ($_661t1[$_Qli6J] == '.') || ($_661t1[$_Qli6J] == '[') || ($_661t1[$_Qli6J] == ']')
         ) {
           $_QLJfI = $_QLJfI . $_661t1[$_Qli6J];
         } else {
           switch(ord($_661t1[$_Qli6J])) {
             case 0xC4: $_QLJfI = $_QLJfI . "Ae";
                   break;
             case 0xDC: $_QLJfI = $_QLJfI . "Ue";
                   break;
             case 0xD6: $_QLJfI = $_QLJfI . "Oe";
                   break;
             case 0xE4: $_QLJfI = $_QLJfI . "ae";
                   break;
             case 0xFC: $_QLJfI = $_QLJfI . "ue";
                   break;
             case 0xF6: $_QLJfI = $_QLJfI . "oe";
                   break;
             case 0xDF: $_QLJfI = $_QLJfI . "ss";
                   break;
           }
         }
   }
   $_6IQC6 = $_QLJfI;

   include_once("functions_charmapping.inc.php");


   $_66QQ1 = _LBRA6();

   if (is_array($_6IQC6)) {

     $_66QlC = array();

     foreach ($_6IQC6 as $key => $_66IQ1) {
       $_66IQ1 = strtr($_66IQ1, $_66QQ1);
       $_66IQ1 = preg_replace("/[^{$_6610I}_a-zA-Z0-9]/", '', $_66IQ1);
       $_66QlC[$key] = preg_replace('/[_]+/', '_', $_66IQ1); // remove double underscore
     }
   } else {
     $_6IQC6 = strtr($_6IQC6, $_66QQ1);
     $_6IQC6 = preg_replace("/[^{$_6610I}_a-zA-Z0-9]/", '', $_6IQC6);
     $_66QlC = preg_replace('/[_]+/', '_', $_6IQC6); // remove double underscore
   }
   return $_66QlC;
 }

 function GetUniqueFileNameInPath($_IJL6o, $_JfIIf){
   $_JfIIf = basename($_JfIIf);

   if(strrpos($_JfIIf, '.') !== false) {
     $_6JlLi = substr($_JfIIf, strrpos($_JfIIf, '.'));
     $_JfIIf = substr($_JfIIf, 0, strrpos($_JfIIf, '.'));
   } else
     $_6JlLi = "";

   if($_JfIIf == "")
     $_JfIIf = "file";

   $_JfIIf = _LPDDC($_JfIIf);
   if($_JfIIf == "")
     $_JfIIf = "file";
   if($_6JlLi != "")
     $_JfIIf .= $_6JlLi;

   if(!file_exists(_LPC1C($_IJL6o) . $_JfIIf)) {
     return $_JfIIf;
   }

   if(strrpos($_JfIIf, '.') !== false) {
     $_6JlLi = substr($_JfIIf, strrpos($_JfIIf, '.'));
     $_JfIIf = substr($_JfIIf, 0, strrpos($_JfIIf, '.'));
   } else
     $_6JlLi = "";
   if($_JfIIf == "")
     $_JfIIf = "file";

   $_Qli6J=0;
   $_66IQI=2147483647;
   for($_Qli6J=0; $_Qli6J<$_66IQI; $_Qli6J++){
     $_66IJl = $_JfIIf.$_Qli6J.$_6JlLi;
     if(!file_exists(_LPC1C($_IJL6o) . $_66IJl))
       return $_66IJl;
   }
   // can't do anything
   return $_JfIIf.$_6JlLi;
 }

 /*
   <title></title>
   <title>A title</title>
 */
 function _LPFQD($_66jQJ, $_66jC8, $_QLoli, $_IO08l) {
   if(strpos($_QLoli, $_66jQJ) === false || strpos($_QLoli, $_66jC8) === false )
     return $_QLoli;
   $_I016j = strpos($_QLoli, $_66jQJ);
   $_Ql0fO = substr($_QLoli, 0, $_I016j + strlen($_66jQJ));
   $_6joLQ = substr($_QLoli, strpos($_QLoli, $_66jC8, $_I016j));
   return $_Ql0fO.$_IO08l.$_6joLQ;
 }

 function _LPFR0($_66jQJ, $_66jC8, $_QLoli) {
   return _L81DB($_QLoli, $_66jQJ, $_66jC8);
 }

 function GetHTMLCharSet($_QLoli) {
   if(stripos($_QLoli, '</head>') === false)
     if(IsUtf8String($_QLoli)) return "utf-8"; else return "iso-8859-1";
   $_6IOiI = $_QLoli;
   $_QLoli = substr($_QLoli, 0, stripos($_QLoli, '</head>') - 1);
   $_I016j = stripos ($_QLoli, 'content="text/html;');
   if($_I016j !== false) {
     $_jJjQi = strpos_reverse($_QLoli, "<", $_I016j);
     $_QLoli = substr($_QLoli, $_jJjQi);
     $_66JoO = strpos($_QLoli, ">");
     $_QLoli = substr($_QLoli, 0, $_66JoO);
     $_QLoli = substr($_QLoli, stripos($_QLoli, "charset=") + strlen("charset="));
     if(strpos($_QLoli, '"') !== false)
        $_QLoli = substr($_QLoli, 0, strpos($_QLoli, '"'));
     $_QLoli = str_replace('"', '', $_QLoli);
     $_QLoli = str_replace(' ', '', $_QLoli);
     $_QLoli = str_replace('/', '', $_QLoli);
     return strtolower( $_QLoli );
   } else {
      // <meta charset="UTF-8">
      if(stripos ($_QLoli, 'charset=') === false)
        if(IsUtf8String($_6IOiI)) return "utf-8"; else return "iso-8859-1";
      $_I1OoI = explode("<meta", $_QLoli);
      for($_Qli6J=0; $_Qli6J < count($_I1OoI); $_Qli6J++){
        $_jJjQi=stripos($_I1OoI[$_Qli6J], "charset=");
        if($_jJjQi !== false){
          $_QLoli = $_I1OoI[$_Qli6J];
          $_66JoO = strpos($_QLoli, "<");
          if($_66JoO !== false)
            $_QLoli = substr($_QLoli, 0, $_66JoO);
          if(count(explode("=", $_QLoli)) > 2)
           continue;

          $_66JoO = strpos($_QLoli, ">");
          $_QLoli = substr($_QLoli, 0, $_66JoO);
          $_QLoli = substr($_QLoli, stripos($_QLoli, "charset=") + strlen("charset="));
          $_QLoli = str_replace('"', '', $_QLoli);
          $_QLoli = str_replace(' ', '', $_QLoli);
          $_QLoli = str_replace('/', '', $_QLoli);
          if($_QLoli != "")
            return strtolower( $_QLoli );
        }
      }
   }
   if(IsUtf8String($_6IOiI)) return "utf-8"; else return "iso-8859-1";
 }

 function SetHTMLCharSet($_QLoli, $charset, $_66611 = false) {
   if(stripos($_QLoli, "<head>") === false) {
     return $_QLoli;
   }
   $_666CL = '<meta http-equiv="content-type" content="text/html; charset='.strtolower($charset);

   if(!$_66611)
     $_666CL .= '">';
     else
     $_666CL .= '" />';

   $_I016j = stripos ($_QLoli, 'content="text/html;');
   $_66fj1=true;

   if($_I016j === false) {
      $_66f61 = false;
      $_IiOfO=substr($_QLoli, 0, stripos($_QLoli, '</head>') - 1);
      $_66f61=stripos ($_IiOfO, 'charset=');
      if($_66f61 !== false){
        $_I016j=$_66f61;
        $_66f61=true;
        $_66fj1=false;
      } else
       $_66f61=false;

      if(!$_66f61) {
        $_QLoli = str_ireplace("<head>", "<head>$_666CL", $_QLoli);
        return $_QLoli;
      }
   }

   while($_I016j !== false) {
     // search vor <meta
     $_jJjQi = strpos_reverse($_QLoli, "<", $_I016j);
     $_Ql0fO = substr($_QLoli, 0, $_jJjQi);
     $_6joLQ = substr($_QLoli, $_jJjQi + 1);
     $_66JoO = strpos($_6joLQ, ">");
     $_6joLQ = substr($_6joLQ, $_66JoO + 1);

     $_QLoli = $_Ql0fO.$_6joLQ;
     if($_66fj1)
       $_I016j = stripos ($_QLoli, 'content="text/html;');
       else
       $_I016j = stripos ($_QLoli, 'charset=');
   }
   $_QLoli = str_ireplace("<head>", "<head>$_666CL", $_QLoli);
   return $_QLoli;
 }

 function _LA0BA($_QLoli){
   //<html lang="de">
   if(stripos($_QLoli, '<html') !== false){
     $_QLJfI = substr($_QLoli, stripos($_QLoli, '<html'));
     $_QLJfI = substr($_QLJfI, 0, stripos($_QLJfI, '>'));
     if(stripos($_QLJfI, 'lang=') !== false){
       $_QLJfI = substr($_QLJfI, stripos($_QLJfI, 'lang=') + strlen('lang='));
       $_QLJfI = trim(str_replace('"', '', $_QLJfI));
       if($_QLJfI !== ""){
         $_QLJfI = explode(',', $_QLJfI);
         $_QLJfI = trim($_QLJfI[0]);
         $_QLJfI = explode('-', $_QLJfI);
         return $_QLJfI[0];
       }
     }
   }

   //<meta http-equiv="content-language" content="de-DE">
   if(stripos($_QLoli, "content-language") !== false){
     $_I016j = _LRFOJ($_QLoli, '<meta', stripos($_QLoli, "content-language"));
     if($_I016j === false) return "";
     $_QLoli = substr($_QLoli, $_I016j);
     $_QLoli = substr($_QLoli, 0, strpos($_QLoli, '>'));
     if(stripos($_QLoli, 'content=') === false) return "";

     $_QLoli = substr($_QLoli, stripos($_QLoli, 'content=') + strlen('content='));
     $_QLoli = trim(str_replace('"', '', $_QLoli));
     $_QLoli = explode(',', $_QLoli);
     $_QLoli = trim($_QLoli[0]);
     $_QLoli = explode('-', $_QLoli);
     return trim($_QLoli[0]);
   }
   
   return "";
 }

 function _LA1Q8($_6IoCL){
   // removes ALL comments also <!--[if   <![endif]-->
   $_6IoCL = preg_replace('/' . preg_quote('<!--[if', '/') . '(.*?)' .  preg_quote('<![endif]-->', '/') . '/is', "", $_6IoCL);
   $_6IoCL = preg_replace('/' . preg_quote('<!--', '/') . '(.*?)' .  preg_quote('-->', '/') . '/is', "", $_6IoCL);
   return $_6IoCL;
 }
 
 function GetInlineFiles($_66flC, &$_JiI11, $_668iI = true) {
   global $_jft6l;
   
   while(count($_JiI11) > 0)
     unset($_JiI11[0]);

   $_668lI = array();
   // href
   preg_match_all('/[ \r\n]href\=([\"\']*)(.*?)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( $_66tJo[$_Qli6J][2] == "" ) continue;
     if( !preg_match("/^(\/\/)/i", $_66tJo[$_Qli6J][2]) && !preg_match("/http:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/https:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/mailto:/i", $_66tJo[$_Qli6J][2]) && !preg_match("/\#/i", $_66tJo[$_Qli6J][2]) && !preg_match("/chrome:/i", $_66tJo[$_Qli6J][2]) && !preg_match("/javascript:/i", $_66tJo[$_Qli6J][2]) && !preg_match("/file:\/\//i", $_66tJo[$_Qli6J][2]) )
       if( preg_match("/\.css/i", $_66tJo[$_Qli6J][2]) || preg_match("/\.js/i", $_66tJo[$_Qli6J][2]) ) { // only css and js, ignore other files

          $_66OQI = false;
          reset($_jft6l);
          foreach($_jft6l as $_I016j => $key) {
            if( preg_match("/".$key."/i", $_66tJo[$_Qli6J][2]) ) {
              $_66OQI = true;
              break;
            }
          }

          if(!$_66OQI)
            $_668lI[] = $_66tJo[$_Qli6J][2];
       }
   }

   // src
   preg_match_all('/[ \r\n]src\=([\"\']*)(.*?)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( $_66tJo[$_Qli6J][1] == '""' || $_66tJo[$_Qli6J][1] == "''") continue;
     if( !preg_match("/^(\/\/)/i", $_66tJo[$_Qli6J][2]) && !preg_match("/http:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/https:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/chrome:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/javascript:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/file:\/\//i", $_66tJo[$_Qli6J][2]) )
     $_668lI[] = $_66tJo[$_Qli6J][2];
   }

   // background
   preg_match_all('/[ \r\n]background\=([\"\']*)(.*?)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( $_66tJo[$_Qli6J][1] == '""' || $_66tJo[$_Qli6J][1] == "''") continue;
     if( !preg_match("/^(\/\/)/i", $_66tJo[$_Qli6J][2]) && !preg_match("/http:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/https:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/chrome:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/javascript:\/\//i", $_66tJo[$_Qli6J][2]) && !preg_match("/file:\/\//i", $_66tJo[$_Qli6J][2]) )
       $_668lI[] = $_66tJo[$_Qli6J][2];
   }

   // background-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n\;\"]background-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( !preg_match("/^(\/\/)/i", $_66tJo[$_Qli6J][1]) && !preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/chrome:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/javascript:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/file:\/\//i", $_66tJo[$_Qli6J][1]) ){
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   // background: color url() // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n\;\"]background\s*:.*?url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( !preg_match("/^(\/\/)/i", $_66tJo[$_Qli6J][1]) && !preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/chrome:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/javascript:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/file:\/\//i", $_66tJo[$_Qli6J][1]) ){
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   // list-style-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n\;\"]list-style-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( !preg_match("/^(\/\/)/i", $_66tJo[$_Qli6J][1]) && !preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/chrome:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/javascript:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/file:\/\//i", $_66tJo[$_Qli6J][1]) ){
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   // content: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n\;\"]content\s*:.*?url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( !preg_match("/^(\/\/)/i", $_66tJo[$_Qli6J][1]) && !preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/chrome:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/javascript:\/\//i", $_66tJo[$_Qli6J][1]) && !preg_match("/file:\/\//i", $_66tJo[$_Qli6J][1]) ){
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   for($_Qli6J=0; $_Qli6J<count($_668lI); $_Qli6J++) {
     $_668lI[$_Qli6J] = str_replace("'", "", $_668lI[$_Qli6J]);
     $_668lI[$_Qli6J] = str_replace('"', "", $_668lI[$_Qli6J]);
     $_668lI[$_Qli6J] = str_replace('&quot;', "", $_668lI[$_Qli6J]);
   }

   // only unique files
   $_I016j = array_unique($_668lI);
   foreach ($_I016j as $key => $_QltJO)
     if( !preg_match("/\.ph.*?/i", $_QltJO) && !preg_match("/\.pl.*?/i", $_QltJO) && !preg_match("/\.cg.*?/i", $_QltJO) ){ # no php, pl, cgi files
        if($_668iI && !(stripos($_QltJO, "data:image") === false)) continue;
        $_JiI11[] = $_QltJO;
     }

 }

 function _LAQDB($_66flC, &$_IjQI8, $_66OLo = false, $_66oC6 = false) {
   while(count($_IjQI8) > 0)
     unset($_IjQI8[0]);
   $_66Co0 = array();
      
   // href
   preg_match_all('/<a.*?href\=([\"\']*)(.*?)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( $_66tJo[$_Qli6J][1] == '""' || $_66tJo[$_Qli6J][1] == "''") continue;
     if( $_66tJo[$_Qli6J][2] == "" ) continue;

     if( preg_match("/^http:\/\//i", $_66tJo[$_Qli6J][2]) || preg_match("/^https:\/\//i", $_66tJo[$_Qli6J][2]) ){
          $_QLJfI = $_66tJo[$_Qli6J][2];
          $_QLJfI = _LPB6Q($_QLJfI);
          if(strpos(_LPBCC($_QLJfI), "/") === false) // normal URL with / than remove trailing Slash
             $_66Co0[] = _LPBCC( $_66tJo[$_Qli6J][2] );
             else
             $_66Co0[] = $_66tJo[$_Qli6J][2];
     } else {
       if($_66oC6)
          $_66Co0[] = $_66tJo[$_Qli6J][2];
     }
   }

   // only unique links
   if(!$_66OLo){
     $_I016j = array_unique($_66Co0);
     foreach ($_I016j as $key => $_QltJO)
       $_IjQI8[] = $_QltJO;
   } else {
     $_IjQI8 = array_merge($_IjQI8, $_66Co0);
   }
 }

 function _LAO86($_66flC, &$_IjQI8, $_66OLo = false) {
   while(count($_IjQI8) > 0)
     unset($_IjQI8[0]);
   $_66Co0 = array();

   // href
   preg_match_all('/<a.*?href\=([\"\']*)(.*?)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( $_66tJo[$_Qli6J][1] == '""' || $_66tJo[$_Qli6J][1] == "''") continue;

     if( preg_match("/^mailto:/i", $_66tJo[$_Qli6J][2]) )
          $_66Co0[] = $_66tJo[$_Qli6J][2];
   }

   // only unique links
   if(!$_66OLo){
     $_I016j = array_unique($_66Co0);
     foreach ($_I016j as $key => $_QltJO)
       $_IjQI8[] = $_QltJO;
   } else {
     $_IjQI8 = array_merge($_IjQI8, $_66Co0);
   }
 }

 function _LAL0C($_66flC, &$_IjQI8, &$_IjQCO, $_66OLo = false, $_66oC6 = false) {
   global $_QLo06;

   $_66flC = _LA1Q8($_66flC);

   _LAQDB($_66flC, $_IjQI8, $_66OLo, $_66oC6);

   while(count($_IjQCO) > 0)
     unset($_IjQCO[0]);

   for($_Qli6J=0;$_Qli6J<count($_IjQI8);$_Qli6J++) {
     $_QLJfI = $_IjQI8[$_Qli6J];
     $_QLJfI = preg_quote($_QLJfI, "/");

     if ( preg_match('/[ \r\n]href\=[\"\']'.$_QLJfI.'[\"\'].*?>(.*?)<\/a>/is', $_66flC, $_66tJo) || preg_match('/[ \r\n]href\=[\"\']'.$_QLJfI."\/".'[\"\'].*?>(.*?)<\/a>/is', $_66flC, $_66tJo) ) {

          $_I016j = trim(_LBDA8($_66tJo[1], $_QLo06));
          if(!isset($_I016j) || $_I016j == "" || strpos($_I016j, "http:/") !== false || strpos($_I016j, "https:/") !== false) {
               if(isset($_66tJo[1]) && preg_match_all('/<img.*?alt\=([\"\']*)(.*?)\1[\s\/>]/i', $_66tJo[1], $_66it8, PREG_SET_ORDER) && isset($_66it8[0][2]) ){ # linked images alt tag
                   $_I016j = trim(_LC6CP(_LBDA8($_66it8[0][2], $_QLo06)));
                }

             if(!isset($_I016j) || $_I016j == "")
               $_I016j = trim($_IjQI8[$_Qli6J]);

             }

          if(!$_66OLo && !$_66oC6){
             // for tracking
             $_I016j = preg_replace("/\&(?!\#)/", " ", $_I016j); // replaces & with " ", but not for emojis!
             //$_I016j = str_replace("&", " ", $_I016j);
             $_I016j = str_replace("\r\n", " ", $_I016j);
             $_I016j = str_replace("\r", " ", $_I016j);
             $_I016j = str_replace("\n", " ", $_I016j);
          }

          $_IjQCO[] = trim($_I016j);
          if(!$_66OLo) continue;

          $_66flC = preg_replace("/".preg_quote($_66tJo[0], "/")."/", "dummy />", $_66flC, 1);
      }
      else
      $_IjQCO[] = trim($_IjQI8[$_Qli6J]);
   }
 }

 function _LALQO($_66flC, &$_jlJOo, $_66L0l = false) {

   while(count($_jlJOo) > 0)
     unset($_jlJOo[0]);

   $_668lI = array();

   // src
   preg_match_all('/[ \r\n]src\=([\"\']*)(.*?)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( (preg_match("/http:\/\//i", $_66tJo[$_Qli6J][2]) || $_66L0l && preg_match("/https:\/\//i", $_66tJo[$_Qli6J][2])) && !( preg_match("/\.css/i", $_66tJo[$_Qli6J][2]) || preg_match("/\.js/i", $_66tJo[$_Qli6J][2]) || preg_match("/\.ph.*?/i", $_66tJo[$_Qli6J][2]) || preg_match("/\.pl.*?/i", $_66tJo[$_Qli6J][2]) || preg_match("/\.cg.*?/i", $_66tJo[$_Qli6J][2]) ) )
       $_668lI[] = $_66tJo[$_Qli6J][2];
   }

   // background
   preg_match_all('/[ \r\n]background\=([\"\']*)(.*?)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( $_66tJo[$_Qli6J][1] == '""' || $_66tJo[$_Qli6J][1] == "''") continue;
     if( preg_match("/http:\/\//i", $_66tJo[$_Qli6J][2]) || $_66L0l && preg_match("/https:\/\//i", $_66tJo[$_Qli6J][2]) )
       $_668lI[] = $_66tJo[$_Qli6J][2];
   }

   // background-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n\;\"]background-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) || $_66L0l && preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) ) {
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   // background: color url() // immer auf ' " pruefen, die muessen weg
   //preg_match_all('/background:[\s*|a-z|0-9|\#|\:|\-]{0,}url\s*\(([\"\']*)(.*?)\)\1[\s\/>]/is', $_66flC, $_66tJo, PREG_SET_ORDER);
   preg_match_all('/[ \r\n\;\"]background\s*:.*?url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) || $_66L0l && preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) ) {
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   // list-style-image: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n\;\"]list-style-image\s*:\s*url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) || $_66L0l && preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) ) {
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   // content: // immer auf ' " pruefen, die muessen weg
   preg_match_all('/[ \r\n\;\"]content\s*:.*?url\(\s*["\']?([^"\']+)["\']?\s*\)/i', $_66flC, $_66tJo, PREG_SET_ORDER);
   for($_Qli6J=0;$_Qli6J<count($_66tJo);$_Qli6J++) {
     if( preg_match("/http:\/\//i", $_66tJo[$_Qli6J][1]) || $_66L0l && preg_match("/https:\/\//i", $_66tJo[$_Qli6J][1]) ) {
       $_QLJfI = $_66tJo[$_Qli6J][1];
       if(strpos($_QLJfI, ");") !== false)
         $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ");"));
       $_668lI[] = $_QLJfI;
     }
   }

   for($_Qli6J=0; $_Qli6J<count($_668lI); $_Qli6J++) {
     $_668lI[$_Qli6J] = str_replace("'", "", $_668lI[$_Qli6J]);
     $_668lI[$_Qli6J] = str_replace('"', "", $_668lI[$_Qli6J]);
     $_668lI[$_Qli6J] = str_replace('&quot;', "", $_668lI[$_Qli6J]);
   }

   // only unique files
   $_I016j = array_unique($_668lI);
   foreach ($_I016j as $key => $_QltJO)
     $_jlJOo[] = $_QltJO;

 }

 function _LALJ6($_JfIIf) {
   global $Mimetypes;

   if(function_exists("mime_content_type")){
     $_66Lfi = mime_content_type($_JfIIf);
   
     if($_66Lfi !== false)
       return $_66Lfi;
   }  
   
   if(strrpos($_JfIIf, '.') !== false)
      $_6JlLi = substr($_JfIIf, strrpos($_JfIIf, '.') + 1);
      else
      $_6JlLi = "";
   $_IOO6C = stripos($Mimetypes, $_6JlLi."=");
   if($_IOO6C === false)
     return "application/octet-stream";
     else {
       $_QLJfI = substr($Mimetypes, $_IOO6C + strlen($_6JlLi."="));
       if(strpos($_QLJfI, ",") !== false)
          $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, ","));
       return $_QLJfI;
     }

 }

 function CheckFileNameForUTF8($_66lIi) {
   global $_QLo06;
   $_66lIi = unhtmlentities($_66lIi, $_QLo06); 
   if(IsUtf8String($_66lIi))
      return utf8_decode($_66lIi);
      else
      return $_66lIi;
 }

 function _LAJRC($_66l8o, $_66llJ = 2, $_6f0OQ = false, $_6f0Co = false) {
  $_6f10t = $_66l8o;

  if ($_6f0OQ)
    $_6f1i1 = 'Byte';
    else
    if ($_6f0Co)
       $_6f1i1 = 'MB';
   else
    if ($_6f10t < 100)
        $_6f1i1 = 'Byte';
        else
        if ($_6f10t >= 100 && $_6f10t < 1024 * 1024) {
         $_6f1i1 = 'KB';
         $_6f10t = $_6f10t / 1024;
        }
        else
         if ($_6f10t < 1024 * 1024 * 1024) {
          $_6f1i1 = 'MB';
          $_6f10t = $_6f10t / (1024 * 1024);
          if ($_6f10t == 0)
            $_6f1i1 = 'Byte';
         }
         else
         {
          $_6f1i1 = 'GB';
          $_6f10t = $_6f10t / (1024 * 1024 * 1024);
          if ($_6f10t == 0)
            $_6f1i1 = 'Byte';
         }

  if(!$_6f0Co) {
    if ($_6f10t > 0) {
      if ($_6f1i1 != 'Byte')
        return trim(sprintf('%16.'.$_66llJ.'f %s', $_6f10t, $_6f1i1));
        else
        return trim(sprintf('%16.0f %s', $_6f10t, $_6f1i1));
     }else
      return '0' . ' ' . $_6f1i1;
  }
  else
    return trim(sprinf('%16.'.$_66llJ.'f %s', $_6f10t / (1024 * 1024), $_6f1i1));

 }

 function _LA61D($_QLoli, &$errors) {
   global $resourcestrings, $INTERFACE_LANGUAGE;

    while(count($errors) > 0)
      unset($errors[0]);

    $_JiI11 = array();
    GetInlineFiles($_QLoli, $_JiI11);
    $_Jf1C8 = InstallPath;

    for($_Qli6J=0; $_Qli6J< count($_JiI11); $_Qli6J++) {
      if(!@file_exists($_JiI11[$_Qli6J])) {
        $_QLJfI = _LA6ED($_JiI11[$_Qli6J]);
        $_JiI11[$_Qli6J] = $_QLJfI;
      }
      if(!file_exists($_JiI11[$_Qli6J])) {
        $errors[] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ImageOrFileNotFound"], $_JiI11[$_Qli6J]);
      }
    }
 }

 function _LA6ED($_IJOfj) {

   if(strpos($_IJOfj, BasePath) === false)
     return $_IJOfj;

   if(strpos(InstallPath, BasePath) !== false) {
      if(BasePath != "/") { # not installed in root?
        $_Jf1C8 = substr(InstallPath, 0, strpos(InstallPath, BasePath));
        $_Jf1C8 .= $_IJOfj;
        return $_Jf1C8;
      } else{
        return InstallPath.$_IJOfj;
      }
   }

   return $_IJOfj;

 }

 function IsHTTPS(){
   if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https"){ return true; }
   if( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == "on" || $_SERVER['HTTPS'] == "1") ){ return true; }
   if($_SERVER['SERVER_PORT'] == 443){ return true; }
   return false;
 }
 
 // http://php.net/manual/de/function.ip2long.php
 function IPv6ToLongFormat($_6f1LJ){
    if(!function_exists("filter_var"))
      return $_6f1LJ;
    $_I0iti = explode(':', $_6f1LJ);
    // If this is mixed IPv6/IPv4, convert end to IPv6 value
    if(filter_var($_I0iti[count($_I0iti) - 1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
        $_6fQJJ = explode('.', $_I0iti[count($_I0iti) - 1]);
        for($_Qli6J = 0; $_Qli6J < 4; $_Qli6J++) {
            $_6fQJJ[$_Qli6J] = str_pad(dechex($_6fQJJ[$_Qli6J]), 2, '0', STR_PAD_LEFT);
        }
        $_I0iti[count($_I0iti) - 1] = $_6fQJJ[0].$_6fQJJ[1];
        $_I0iti[] = $_6fQJJ[2].$_6fQJJ[3];
    }
    $_6fI0L = 8 - count($_I0iti);
    $_6fIIO = array();
    $_6fIio = false;
    foreach($_I0iti as $_6fjiJ) {
        if(!$_6fIio && $_6fjiJ == '') {
            for($_Qli6J = 0; $_Qli6J <= $_6fI0L; $_Qli6J++) {
                $_6fIIO[] = '0000';
            }
            $_6fIio = true;
        }
        else {
            $_6fIIO[] = $_6fjiJ;
        }
    }
    foreach($_6fIIO as $key => $_6fjiJ) {
        $_6fIIO[$key] = str_pad($_6fjiJ, 4, '0', STR_PAD_LEFT);
    }
    $_6fjl1 = join(':', $_6fIIO);
    $_6fJ0j = join('', $_6fIIO);

    if(!filter_var($_6fjl1, FILTER_VALIDATE_IP)) {
      return $_6f1LJ;
     }
    return $_6fjl1;
 }

 function getOwnIP($_6fJIJ = true) {
    if(defined("ip_address_mask_length"))
      $_6fJlI = ip_address_mask_length;
      else
      $_6fJlI = 0;
    if(!$_6fJIJ)
      $_6fJlI = 0;

    $_6f6Qo = $_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      $_6f6Qo = $_SERVER['HTTP_X_FORWARDED_FOR'];
      else
      if(isset($_SERVER['HTTP_X_REAL_IP']))
        $_6f6Qo = $_SERVER['HTTP_X_REAL_IP'];

    if (defined('AF_INET6') && isset($_6f6Qo) && function_exists("filter_var")) {
     if(filter_var($_6f6Qo, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
       $_6f6Qo = str_replace(" ", "", $_6f6Qo);

       if($_6fJlI > 0) {
         // Dual - IPv6 plus IPv4 formats
         // e.g. 2001 : db8: 3333 : 4444 : 5555 : 6666 : 1 . 2 . 3 . 4
         if(strpos($_6f6Qo, '.') !== false){
           $_6f6oC = substr($_6f6Qo, strpos_reverse($_6f6Qo, ':') + 1);
           $_6f6Qo = substr( $_6f6Qo, 0, strpos_reverse($_6f6Qo, ':') + 1);

           if($_6fJlI < 5){
             $_6ff60 = ip2long($_6f6oC);
             if($_6ff60 > 0) {
               switch ($_6fJlI) {
                 case 1:
                 $_6ff60 = $_6ff60 & 0xFFFFFF00;
                 break;
                 case 2:
                 $_6ff60 = $_6ff60 & 0xFFFF0000;
                 break;
                 case 3:
                 $_6ff60 = $_6ff60 & 0xFF000000;
                 break;
                 case 4:
                 $_6ff60 = $_6ff60 & 0x00000000;
                 break;
               }
               $_6f6oC = long2ip($_6ff60);
             }
           } else{
             if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
               $lang = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
               else
               $lang = "de";
             if(isset($_SERVER["HTTP_USER_AGENT"]))
               $_jOjI0 = $_SERVER["HTTP_USER_AGENT"];
               else
               $_jOjI0 = AppName;
             $_6f6oC = md5($_6f6oC . $_jOjI0 . $lang);
             $_6f6oC = implode('.', str_split($_6f6oC, 2));
             return $_6f6Qo . $_6f6oC; // always return this invalid ipv6 address without checking
           }
           $_6f6Qo = $_6f6Qo . $_6f6oC;
         } else{
           // normal IPv6
           $_6f6Qo = IPv6ToLongFormat($_6f6Qo);
           if($_6fJlI < 5){
             $_I0iti = explode(':', $_6f6Qo);
             for($_Qli6J=count($_I0iti); $_Qli6J > count($_I0iti) - $_6fJlI - 1; $_Qli6J--){
               $_I0iti[$_Qli6J] = "0000";
             }
             $_6f6Qo = join(':', $_I0iti);
           } else {
             if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
               $lang = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
               else
               $lang = "de";
             if(isset($_SERVER["HTTP_USER_AGENT"]))
               $_jOjI0 = $_SERVER["HTTP_USER_AGENT"];
               else
               $_jOjI0 = AppName;
             $_6f6Qo = md5($_6f6Qo . $_jOjI0 . $lang);
             $_6f6Qo = implode(':', str_split($_6f6Qo, 4));
             return $_6f6Qo; // always return this invalid ipv6 address without checking
           }
         }
       }
       return IPv6ToLongFormat($_6f6Qo);
     }
    }

    if(!isset($_SERVER['REMOTE_ADDR']))
      if(isset($_SERVER['HTTP_X_REAL_IP']))
        $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];
        else
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
          else
          $_SERVER['REMOTE_ADDR'] = "127.0.0.1";

    /* php-magazin 05-07 (de) S. 38, by C.Fraunholz */
    /* modified version from internet */
    if ( !( isset($_SERVER['HTTP_VIA']) || isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) ) {
        $_6f6oC = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
    } else {
       $_6ffoo = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
       $_6ffCi = "0.0.0.0";
       if (isset($_SERVER['HTTP_CLIENT_IP'])) {
          $_6ffCi = intval(substr($_SERVER['HTTP_CLIENT_IP'], 0, strpos($_SERVER['HTTP_CLIENT_IP'],".")));
       } else {
           if (isset($_SERVER['HTTP_VIA'])) {
              $_6ffCi = intval(substr($_SERVER['HTTP_VIA'], 0, strpos($_SERVER['HTTP_VIA'], ".")));
           }
       }
       if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
         $_6f8tl = intval(substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ".")));
         else
         $_6f8tl = "";
       if ($_6f8tl != "" && !($_6f8tl == 10 || $_6f8tl == 192 || $_6f8tl == 127 || $_6f8tl == 224)) { // Proxy
         $_6f6oC = long2ip(ip2long($_SERVER['HTTP_X_FORWARDED_FOR']));
       } elseif(isset($_SERVER['HTTP_CLIENT_IP']) && !($_6ffCi == 10 || $_6ffCi == 192 || $_6ffCi == 127 || $_6ffCi == 224)) {
         $_6f6oC = long2ip(ip2long($_SERVER['HTTP_CLIENT_IP']));
       } else {
         $_6f6oC = $_6ffoo;
       }
    }
    $_Ift08 = strpos($_6f6oC, ",");
    if ($_Ift08 !== false) $_6f6oC = substr($_6f6oC, 0, $_Ift08);
    if(strpos($_6f6oC, "'") !== false)
       $_6f6oC = str_replace("'", "", $_6f6oC);
    if($_6f6oC == "0.0.0.0" || empty($_6f6oC)){
      if(isset($_SERVER['HTTP_X_REAL_IP']))
        $_6f6oC = $_SERVER['HTTP_X_REAL_IP'];
        else
          if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
             $_6f6oC = $_SERVER['HTTP_X_FORWARDED_FOR'];
           else
             $_6f6oC = $_SERVER['REMOTE_ADDR'];
    }

    if($_6fJlI > 0) {
       if($_6fJlI < 5){
         $_6ff60 = ip2long($_6f6oC);
         if($_6ff60 > 0) {
           switch ($_6fJlI) {
             case 1:
             $_6ff60 = $_6ff60 & 0xFFFFFF00;
             break;
             case 2:
             $_6ff60 = $_6ff60 & 0xFFFF0000;
             break;
             case 3:
             $_6ff60 = $_6ff60 & 0xFF000000;
             break;
             case 4:
             $_6ff60 = $_6ff60 & 0x00000000;
             break;
           }
           $_6f6oC = long2ip($_6ff60);
         }
       } else {
         if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
           $lang = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
           else
           $lang = "de";
         if(isset($_SERVER["HTTP_USER_AGENT"]))
           $_jOjI0 = $_SERVER["HTTP_USER_AGENT"];
           else
           $_jOjI0 = AppName;
         $_6f6oC = md5($_6f6oC . $_jOjI0 . $lang);
         $_6f6oC = implode(':', str_split($_6f6oC, 4));
       }
    }
    return $_6f6oC;
 }

 function CleanUpHTML($_QLoli, $_6f8C6 = true){
   $_QLoli = str_replace('ï»¿', '', $_QLoli); # remove BOM
   $_QLoli = stripslashes($_QLoli);
   $_QLoli = str_replace('"file:///', '"_DO_NOT_USE_FILE_PROTOCOL_', $_QLoli);
   $_QLoli = str_replace('"file://', '"_DO_NOT_USE_FILE_PROTOCOL_', $_QLoli);
   $_QLoli = str_replace("?".ini_get("session.name")."=".session_id(), "", $_QLoli);
   $_QLoli = str_replace("&".ini_get("session.name")."=".session_id(), "", $_QLoli);
   $_QLoli = str_replace("&amp;".ini_get("session.name")."=".session_id(), "", $_QLoli);
   if(!$_6f8C6)
     $_QLoli = str_replace('<script src="chrome://skype_ff_toolbar_win/content/injection_graph_func.js" id="injection_graph_func" charset="utf-8" type="text/javascript"></script>', "", $_QLoli);

   // remove all scripts
   if($_6f8C6){
     $_QLoli = _LA8F6($_QLoli);
   }

   // FF bug url(&quot; &quot;);!!
   if(strpos($_QLoli, " url(&quot;") !== false) {
     $_QLoli = str_replace(" url(&quot;", " url('", $_QLoli);
     $_QLoli = str_replace("&quot;);", "');", $_QLoli);
   }

   $_QLoli = FixCKEditorStyleProtectionForCSS($_QLoli);
   $_QLoli = preg_replace('/<meta name="author"(.*?)>/is', '', $_QLoli); // detecting as spam possible
   return $_QLoli;
 }

 function FixCKEditorStyleProtectionForCSS($_QLoli){
  $_QLoli = str_ireplace("<style", "<style", $_QLoli);
  $_QLoli = str_ireplace("</style>", "</style>", $_QLoli);
  $_I016j = explode("<style", $_QLoli);
  for($_Qli6J=0; $_Qli6J<count($_I016j); $_Qli6J++) {
     if(strpos($_I016j[$_Qli6J], "</style>") === false) continue;
     $_66JoO = explode("</style>", $_I016j[$_Qli6J]);
     $_66JoO[0] = str_replace("<!--", "", $_66JoO[0]);
     $_66JoO[0] = str_replace("//-->", "", $_66JoO[0]);
     $_66JoO[0] = str_replace("-->", "", $_66JoO[0]);
     $_I016j[$_Qli6J] = implode("</style>", $_66JoO);
  }
  $_QLoli = implode("<style", $_I016j);
  return $_QLoli;
 }

 function _LA8F6($_6ftOo){
   // remove all scripts
   $_6ftOo = preg_replace('/<script(.*?)>(.*?)<\/script>/is', '', $_6ftOo);
   $_6ftOo = preg_replace('/<object(.*?)>(.*?)<\/object>/is', '', $_6ftOo);
   $_6ftOo = preg_replace('/<embed(.*?)>(.*?)<\/embed>/is', '', $_6ftOo);

   $_6ftOo = preg_replace('/<iframe(.*?)>(.*?)<\/iframe>/is', '', $_6ftOo);
   $_6ftOo = preg_replace('/<frameset(.*?)>(.*?)<\/frameset>/is', '', $_6ftOo);
   $_6ftOo = preg_replace('/<frame(.*?)>(.*?)<\/frame>/is', '', $_6ftOo);
   $_6ftOo = preg_replace('/<video(.*?)>(.*?)<\/video>/is', '', $_6ftOo);
   $_6ftOo = preg_replace('/<svg(.*?)>(.*?)<\/svg>/is', '', $_6ftOo);

   $_6ftOo = str_ireplace("<script", "", $_6ftOo);
   $_6ftOo = str_ireplace("<object", "", $_6ftOo);
   $_6ftOo = str_ireplace("<embed", "", $_6ftOo);
   $_6ftOo = str_ireplace("<iframe", "", $_6ftOo);
   $_6ftOo = str_ireplace("<frameset", "", $_6ftOo);
   $_6ftOo = str_ireplace("<frame", "", $_6ftOo);
   $_6ftOo = str_ireplace("<video", "", $_6ftOo);
   $_6ftOo = str_ireplace("<svg", "", $_6ftOo);

   $_6ftOo = str_ireplace("javascript:", "", $_6ftOo);

   return _LCLCP($_6ftOo);

   $_6fOtl = array('onabort', 'onblur', 'onchange', 'onclick', 'ondblclick', 'onerror', 'onfocus', 'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup',  'onreset', 'onselect', 'onsubmit', 'onunload');

   reset($_6fOtl);
   foreach($_6fOtl as $key => $_QltJO)
     $_6ftOo = preg_replace('/'.$_QltJO.'\=([\"\']*)(.*?)\1[\s\/>]/is', '', $_6ftOo);

   return $_6ftOo;
 }

 if(!function_exists("sha1"))
   require_once("sha1.php");

 require_once("ecg-liste.inc.php");  
 
 function _LAPE1(){
   $_6foQC = "";
   mt_srand(time());
   $_j1881 = mt_rand(32, 64);

   for ($_Qli6J = 0; $_Qli6J < $_j1881; $_Qli6J++) {
     do {
      $_Ift08 = chr(mt_rand(48, 122));
     } while ( ($_Ift08 == '`') || ($_Ift08 == "'") || ($_Ift08 == "+") || ($_Ift08 == '"') || ($_Ift08 == "%") || ($_Ift08 == "&") || ($_Ift08 == "*") || ($_Ift08 == "?") || ($_Ift08 == "\\") || ($_Ift08 == '/') || ($_Ift08 == '"') || ($_Ift08 == '~') || ($_Ift08 == '{') || ($_Ift08 == '}') || ($_Ift08 == '[') || ($_Ift08 == ']') || ($_Ift08 == '_') || ($_Ift08 == '-') );
     $_6foQC .= $_Ift08;
   }

   if(version_compare(phpversion(), "5.0.0", "<") && !function_exists("sha1_str2blks_SHA1") ) { //sha1_str2blks_SHA1 is defined in sha1.php
     $_6foQC = sha1($_6foQC);
   } else {
     $_6foQC = sha1($_6foQC, false);
   }

   if(mt_rand(1, 1024) % 2 == 0)
     $_QLJfI = $_6foQC.substr($_6foQC, 0, 20).substr($_6foQC, 0, 20);
     else
     $_QLJfI = $_6foQC.substr($_6foQC, 20).substr($_6foQC, 20);
   while(strlen($_QLJfI) < 80)
     $_QLJfI = '0'.$_QLJfI;
   if(strlen($_QLJfI) > 80)
     $_QLJfI = substr($_QLJfI, 0, 80);
   return $_QLJfI;
 }

 function DoHTTPRequest($_J60tC,$_I06t6,$_IJL6o,$_I0QjQ,$_6foIt, $_J608j, $_6foL1, $_6fCJQ, $_6fi18, &$_JJl1I, &$_J600J) {
     global $_JQjlt;
     $_6fift = false;

     while (true){
       $_6fiLj = "";
       if (empty($_I06t6))
           $_I06t6 = 'GET';
       $_I06t6 = strtoupper($_I06t6);

       $_6fLCt = $_J60tC;
       if($_J608j == 443 && strpos($_6fLCt, 'ssl://') === false)
          $_6fLCt = 'ssl://'.$_6fLCt;

       $_I60fo = fsockopen($_6fLCt, $_J608j, $_JJl1I, $_J600J, 30);
       if(!$_I60fo) {
         $_JJl1I = 600;
         $_J600J = "Can't connect to server.";
         return false;
       }

       if ($_I06t6 == 'GET')
           $_IJL6o .= '?' . $_I0QjQ;
       $_6fl6I = "";
       $_6fl6I .= "$_I06t6 $_IJL6o HTTP/1.0\r\n"; # for 1.1 we must http_chunked_decode() => wordpress plugin
       $_6fl6I .= "Host: $_J60tC\r\n";

       if($_6foL1 && $_6fift){
         $_6fl6I .= "Authorization: Basic ".base64_encode($_6fCJQ . ":" . $_6fi18)."\r\n";
       }

       if ($_I06t6 == 'POST'){
         $_6fl6I .= "Content-type: application/x-www-form-urlencoded\r\n";
         $_6fl6I .= "Content-length: " . strlen($_I0QjQ) . "\r\n";
       }
       if ($_6foIt)
           $_6fl6I .= "User-Agent: $_6foIt\r\n";
           else
           $_6fl6I .= "User-Agent: $_JQjlt\r\n";

       $_6fl6I .= "Connection: close\r\n\r\n";
       if ($_I06t6 == 'POST')
          $_6fl6I .= $_I0QjQ . "\r\n\r\n";

       fputs($_I60fo, $_6fl6I);

       if(function_exists("stream_set_timeout") && function_exists("stream_set_blocking") && function_exists("stream_get_meta_data") )  {
          stream_set_blocking($_I60fo, TRUE);
          stream_set_timeout($_I60fo, 20);
          $_6flLC = stream_get_meta_data($_I60fo);
          while ((!feof($_I60fo)) && (!$_6flLC['timed_out'])) {
           $_6fiLj .= fgets($_I60fo, 128);
           $_6flLC = stream_get_meta_data($_I60fo);
          }
        } else {
          sleep(2);
          while (!feof($_I60fo)) {
           $_6fiLj .= fgets($_I60fo, 128);
          }
        }
       fclose($_I60fo);
       $_6fllJ = trim(substr($_6fiLj, 0, strpos($_6fiLj, " ")));
       $_680O6 = trim(substr($_6fiLj, strpos($_6fiLj, " ")));
       $_J600J = $_680O6;
       $_J600J = substr($_J600J, 0, strpos($_J600J, "\r\n"));
       $_680O6 = trim(substr($_680O6, 0, strpos($_680O6, " ")));
       if($_680O6 != 200)
         $_JJl1I = $_680O6;

       if($_680O6 == 401 && $_6foL1 && !$_6fift){
         $_6fift = true;
         continue;
       } else
         return $_6fiLj;
     }
 }

 function _LABJA($_J60tC,$_I06t6,$_IJL6o,$_I0QjQ,$_6foIt, $_J608j, $_6foL1, $_6fCJQ, $_6fi18, &$_JJl1I, &$_J600J) {
      global $_JQjlt;

   // don't wait for a result
       if (empty($_I06t6))
           $_I06t6 = 'GET';
       $_I06t6 = strtoupper($_I06t6);

       $_6fLCt = $_J60tC;
       if($_J608j == 443 && strpos($_6fLCt, 'ssl://') === false)
          $_6fLCt = 'ssl://'.$_6fLCt;

       $_I60fo = fsockopen($_6fLCt, $_J608j, $_JJl1I, $_J600J, 30);
       if(!$_I60fo) {
         $_JJl1I = 600;
         $_J600J = "Can't connect to server.";
         return false;
       }

       if ($_I06t6 == 'GET')
           $_IJL6o .= '?' . $_I0QjQ;
       $_6fl6I = "";
       $_6fl6I .= "$_I06t6 $_IJL6o HTTP/1.0\r\n"; # for 1.1 we must http_chunked_decode() => wordpress plugin
       $_6fl6I .= "Host: $_J60tC\r\n";

       if($_6foL1 && $_6fift){
         $_6fl6I .= "Authorization: Basic ".base64_encode($_6fCJQ . ":" . $_6fi18)."\r\n";
       }

       if ($_I06t6 == 'POST'){
         $_6fl6I .= "Content-type: application/x-www-form-urlencoded\r\n";
         $_6fl6I .= "Content-length: " . strlen($_I0QjQ) . "\r\n";
       }
       if ($_6foIt)
           $_6fl6I .= "User-Agent: $_6foIt\r\n";
           else
           $_6fl6I .= "User-Agent: $_JQjlt\r\n";

       $_6fl6I .= "Connection: close\r\n\r\n";
       if ($_I06t6 == 'POST')
          $_6fl6I .= $_I0QjQ . "\r\n\r\n";

       fputs($_I60fo, $_6fl6I);

       fclose($_I60fo);

       return false;
 }

 if(function_exists("stream_context_create")){

   function DoHTTPPOSTRequest($_J60tC, $_IJL6o, $_I0QjQ, $_J608j=80, $_681tL = array(), $_6QCli = "application/x-www-form-urlencoded"){
     global $_68QIQ;
     
     $_68QtO = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';

     $_68QC0 = "";
     foreach($_681tL as $key => $_QltJO)
       $_68QC0 .= "$key: $_QltJO\r\n";

     if(is_array($_I0QjQ))
       $_I0QjQ = http_build_query($_I0QjQ);

     $_IO6iJ = array(
             'http' => array(
                 'header' => "Content-type: $_6QCli\r\n".$_68QC0,
                 'method' => "POST",
                 'content' => $_I0QjQ,
                 'Connection: close',
                 // Force the peer to validate (not needed in 5.6.0+, but still works
                 'verify_peer' => false,
                 'timeout' => 30,
                 $_68QtO => $_J60tC,
             ),
             'ssl' => array(
               'verify_peer' => false,
               'verify_peer_name' => false,
             ),
         );

     $_I016j = strpos($_IJL6o, "/");
     if($_I016j !== false && $_I016j == 0)
       $URL = _LPBCC($_J60tC).$_IJL6o;
       else
       $URL = _LPC1C($_J60tC).$_IJL6o;

     if($_J608j != 443)
       $URL = "http://".$URL;
       else
       $URL = "https://".$URL;


     $_Jl00o = stream_context_create($_IO6iJ);
     $_Ijj6Q = file_get_contents($URL, false, $_Jl00o);
     if($_Ijj6Q === false){
       $_68QIQ = array();
       $_68QIQ[0] = "HTTP/1.1 500 Bad request";
       return false;
     }else{
       $_68QIQ = $http_response_header; // PHP internal local var for file_get_contents()
       return $_Ijj6Q;
     }  
   }

 }

 function _LADQC($_68II8, &$_IoLj0, $_68Ij6 = ""){
    global $_IIlfi;

    $_IooIi = $_68II8[$_68Ij6."MailFormat"];
    $_IoLOt = strlen($_68II8[$_68Ij6."MailSubject"]) + 150; //150 for date:, subject:, from:, to:....
    if ($_IooIi == "Multipart") {
      $_IoLOt += strlen($_68II8[$_68Ij6."MailHTMLText"]) + strlen($_68II8[$_68Ij6."MailHTMLText"]) * 15 / 100;
      $_IoLOt += strlen($_68II8[$_68Ij6."MailPlainText"]) + strlen($_68II8[$_68Ij6."MailPlainText"]) * 15 / 100;
    }
    if ($_IooIi == "PlainText") {
      $_IoLOt += strlen($_68II8[$_68Ij6."MailPlainText"]) + strlen($_68II8[$_68Ij6."MailPlainText"]) * 15 / 100;
    }
    if ($_IooIi == "HTML") {
      $_IoLOt += strlen($_68II8[$_68Ij6."MailHTMLText"]) + strlen($_68II8[$_68Ij6."MailHTMLText"]) * 15 / 100;
    }

    if ($_IooIi == "Multipart" || $_IooIi == "HTML" ) {

       $_JiI11 = array();
       GetInlineFiles($_68II8[$_68Ij6."MailHTMLText"], $_JiI11);
       $_Jf1C8 = InstallPath;

       for($_Qli6J=0; $_Qli6J< count($_JiI11); $_Qli6J++) {
         $_IoLOt += strlen( basename( $_JiI11[$_Qli6J] ) );
         if(!@file_exists($_JiI11[$_Qli6J])) {
           $_QLJfI = _LA6ED($_JiI11[$_Qli6J]);
           $_JiI11[$_Qli6J] = $_QLJfI;
         }
         $_It18j = filesize($_JiI11[$_Qli6J]);
         $_IoLOt += $_It18j + $_It18j * 30 / 100;
       }
    }


    if(isset($_68II8[$_68Ij6."Attachments"]))
       for($_Qli6J=0; $_Qli6J<count($_68II8[$_68Ij6."Attachments"]); $_Qli6J++) {
          $_IoLOt += strlen( $_68II8[$_68Ij6."Attachments"][$_Qli6J] );
          $_It18j = filesize($_IIlfi.$_68II8[$_68Ij6."Attachments"][$_Qli6J]);
          $_IoLOt += $_It18j + $_It18j * 30 / 100;
       }

    $_IoLj0 = ini_get("memory_limit");
    if(empty($_IoLj0))
      if( version_compare(phpversion(), "5.2.0", "<") )
         $_IoLj0 = "8M";
         else
         $_IoLj0 = "16M";
    if($_IoLj0 == -1)
       $_IoLj0 = "2GB"; # 32bit integer

    if(!(strpos($_IoLj0, "G") === false))
       $_IoLj0 = intval($_IoLj0) * 1024 * 1024 * 1024;
       else
       if(!(strpos($_IoLj0, "M") === false))
          $_IoLj0 = intval($_IoLj0) * 1024 * 1024;
          else
          if(!(strpos($_IoLj0, "K") === false))
             $_IoLj0 = intval($_IoLj0) * 1024;
             else
             $_IoLj0 = intval($_IoLj0) * 1;
    if($_IoLj0 == 0)
      if( version_compare(phpversion(), "5.2.0", "<") )
         $_IoLj0 = 8 * 1024 * 1024;
         else
         $_IoLj0 = 16 * 1024 * 1024;
    return $_IoLOt;
 }

 function _LADFO($_I1OoI, $_jl0Ii){
   if( !is_array($_I1OoI) || !is_array($_jl0Ii) || count($_I1OoI) != count($_jl0Ii) )
     return false;
   $_QL8i1 = array_diff($_I1OoI, $_jl0Ii);
   if(count($_QL8i1) == 0)
      $_QL8i1 = array_diff($_jl0Ii, $_I1OoI);
   if(count($_QL8i1) == 0)
     return true;
     else
     return false;
 }

 function _LAEJL($MailingListId){
   global $_QLttI, $_QL88I, $_QlQot, $OwnerUserId, $UserId;
   if($UserId == 0 || defined("CRONS_PHP") || defined("UserNewsletterPHP") || defined("DefaultNewsletterPHP")) return true;
   if(!isset($MailingListId) || empty($MailingListId) || is_array($MailingListId)) return true;
   $MailingListId = intval($MailingListId);
   $_QLfol = "SELECT `$_QL88I`.`id` FROM `$_QL88I`";
   if($OwnerUserId == 0) // ist es ein Admin?
      $_QLfol .= " WHERE (`users_id`=$UserId)";
      else {
       $_QLfol .= " LEFT JOIN `$_QlQot` ON `$_QL88I`.`id`=`$_QlQot`.`maillists_id` WHERE (`$_QlQot`.`users_id`=$UserId) AND (`$_QL88I`.`users_id`=$OwnerUserId)";
      }
   $_QLfol .= " AND (`$_QL88I`.`id` = $MailingListId)";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && ($_QLO0f = mysql_fetch_assoc($_QL8i1))) {
     mysql_free_result($_QL8i1);
     return $_QLO0f["id"] == $MailingListId;
   } else
     return false;
 }

 function _LAF0F($_IOCjL){
   if( strtolower($_IOCjL) == "true" )
     return 1;
   if( strtolower($_IOCjL) == "false" )
     return 0;
   $_IOCjL = intval($_IOCjL);
   if($_IOCjL<0) $_IOCjL = 0;
   if($_IOCjL>1) $_IOCjL = 1;

   return $_IOCjL;
 }

 function _LAFQC($_j661I){
   global $_QLttI;
   $IP = getOwnIP();
   if($_j661I["LimitSubUnsubScripts"] == "Unlimited" || $IP == "") return false;
   // remove old records
   $_QLfol = "DELETE FROM $_j661I[LimitSubUnsubScriptsTableName] WHERE NOW() >= DATE_ADD(`CreateDate`, INTERVAL 1 $_j661I[LimitSubUnsubScriptsLimitedRequestsInterval])";
   mysql_query($_QLfol, $_QLttI);
   //_L8D88($_QLfol);

   $_QLfol = "SELECT COUNT(*) FROM $_j661I[LimitSubUnsubScriptsTableName] WHERE `IP`=". _LRAFO($IP);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QL8i1 = $_QLO0f[0] > $_j661I["LimitSubUnsubScriptsLimitedRequests"];

   if(!$_QL8i1){
     $_QLfol = "INSERT INTO $_j661I[LimitSubUnsubScriptsTableName] SET `CreateDate`=NOW(), `IP`="._LRAFO($IP);
     mysql_query($_QLfol, $_QLttI);
   }

   return $_QL8i1;
 }

 function _LAFFA($_IO08l, $_68IJO, &$_J0COJ){
   global $_QLttI, $_QL88I, $_IolCJ, $resourcestrings, $INTERFACE_LANGUAGE;
   $_J0COJ = "";

   if(!is_array($_68IJO) && intval($_68IJO) > 0){
      $_QLfol = "SELECT `SubscriptionUnsubscription` FROM `$_QL88I` WHERE `$_QL88I`.`id`=".intval($_68IJO);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && ($_68IJO = mysql_fetch_assoc($_QL8i1))) {
        mysql_free_result($_QL8i1);
      }
   }

   if(!is_array($_68IJO) || !isset($_68IJO["SubscriptionUnsubscription"]) || $_68IJO["SubscriptionUnsubscription"] == "Allowed")
     return true;

   if($_68IJO["SubscriptionUnsubscription"] == 'SubscribeOnly' || $_68IJO["SubscriptionUnsubscription"] == 'Denied'){
     if (stripos($_IO08l, $_IolCJ["UnsubscribeLink"]) !== false){
       $_J0COJ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["PlaceholderBecauseOfMailingListRuleNotAllowed"], $_IolCJ["UnsubscribeLink"]);
       return false;
     }
   }

   if($_68IJO["SubscriptionUnsubscription"] == 'UnsubscribeOnly' || $_68IJO["SubscriptionUnsubscription"] == 'Denied'){
     if (stripos($_IO08l, $_IolCJ["EditLink"]) !== false){
       $_J0COJ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["PlaceholderBecauseOfMailingListRuleNotAllowed"], $_IolCJ["EditLink"]);
       return false;
     }
   }

   return true;
 }

 function _LAFFB($_I1Q0I, $_IO6iJ = 0){
   if(version_compare(PHP_VERSION, "5.3") >= 0){
     return json_encode($_I1Q0I, $_IO6iJ);
   }
   /*if(version_compare(PHP_VERSION, "5.2") >= 0){
     //return json_encode($_I1Q0I); // 5.2 doesn't support $_IO6iJ
     return internal_json_encode($_I1Q0I, $_IO6iJ);
   } */
   // older PHP versions
   // function in jsonencode.inc.php
   return internal_json_encode($_I1Q0I, $_IO6iJ);
 }

 # PHP 5.4 and newer PEAR check
 if(!function_exists("IsPEARError")){
   function IsPEARError($_I0QjQ){
     return is_a($_I0QjQ, 'PEAR_Error');
   }
 }

 if(!function_exists("PEARraiseError")){
   function PEARraiseError($_ILlOQ, $_I6llO=null){
     // PEAR::raiseError() is not PHP 5.4 compatible
     return new PEAR_Error($_ILlOQ, $_I6llO);
   }
 }

 function _LB1D8() {
    if(!function_exists("memory_get_usage")) return "";
    $_68jQL = memory_get_usage(true);

   /* if ($_68jQL < 1024)*/
        return $_68jQL." bytes";
/*    elseif ($_68jQL < 1048576)
        return round($_68jQL/1024,2)." kilobytes";
    else
        return round($_68jQL/1048576,2)." megabytes"; */

 }

 function _LBQB1($_Jo8iI, $_68jil = ""){
   $_Jo8Q0 = 'abcdefghijklmnopqrstuvwxyz0123456789' . $_68jil;
   if($_Jo8iI < 1) $_Jo8iI = 32;
   
    mt_srand(time());
    $_JotjI = array();
    for($_Qli6J=0; $_Qli6J<$_Jo8iI; $_Qli6J++){
      $_JotjI[] = mt_rand() * 256;
    }

    $token = "";
    for($_Qli6J=0; $_Qli6J<count($_JotjI); $_Qli6J++){
      $_JottI = $_Jo8Q0[ abs($_JotjI[$_Qli6J] % strlen($_Jo8Q0)) ];
      $token .= (mt_rand(1, 100) / 100 > 0.5) ? strtoupper($_JottI) : $_JottI;
    }
    return $token;
 }

 function _LBQFJ($_Jf1C8, $MailingListId=0, $_68JJo=0, $_j01OI=0, $_68Jfj=0, $ResponderType="", $ResponderId=0, $_JO006=0, $_686JJ=".eml"){
   global $OwnerUserId, $UserId, $_J1t6J;
   if($_Jf1C8 == ""){
     if(ini_get("open_basedir") == "")
       $_Jf1C8 = ini_get("session.save_handler") == "files" ? ini_get("session.save_path") : $_J1t6J;
       else{
         $_Jf1C8 = $_J1t6J;
       }

     if(!is_writable($_Jf1C8))
       $_Jf1C8 = $_J1t6J;
   }
   $_QLJfI = empty($ResponderType) ? "EMail" . '-' . '0' : $ResponderType . '-' . $ResponderId . '-';
   $_QLJfI .= $OwnerUserId . '-' . $UserId . '-' . $MailingListId . '-'. $_68JJo . '-' . $_j01OI . '-' . $_68Jfj . '-' . $_JO006;
   return _LPC1C($_Jf1C8) . $_QLJfI . $_686JJ;
 }

 function _LBOOC($_IttOL, $UserId, $_jf0jO, $ResponderType, $ResponderId){
   $_J881f = _LBQFJ("", $_IttOL, 0, $UserId, $_jf0jO, $ResponderType, $ResponderId, 0, ".mime");
   @unlink($_J881f);
   ClearLastError();
 }

 function _LBOL6($Code, $Text, $_68flt) {
    if($Code == 250) return false;

    if($Code == 11001) return true; // host not found

    if($Code == MonthlySendQuotaExceeded || $Code == SendQuotaExceeded || $Code == RecipientIsInECGList){
      return true;
    }

    if($_68flt == "mail")
      if (strpos($Text, "returned failure") !== false) // PHP mail error
         return true;
    if($_68flt == "SMS")
       return true;

    // SMTP errors

    if($Code == null || $Code == 0 || $Code == 999){
      if(stripos($Text, "no valid recipient") !== false || stripos($Text, "unable to connect") !== false || stripos($Text, "Failed to connect") !== false)
        return true;
    }

    if($Code == 450) return false; // z.B. too much email
    if($Code == 553) return false; // Auth
    if($Code == 535) return false; // Auth
    if($Code == 421) return true;
    if($Code >= 451 && $Code <= 452 ) return true;
    if(
       ($Code >= 500 && $Code <= 534) ||
       ($Code >= 536 && $Code <= 552) ||
       ($Code >= 554 && $Code <= 699)
      )
        return true;

    /*if(stripos($Text, 'harvester') !== false)
       return true;
    if(stripos($Text, ' spam ') !== false)
       return true;*/

    if($_68flt == 'smtpmx') {
      if(stripos($Text, 'relaying') !== false)
        return true;
      if(stripos($Text, 'dynamic') !== false)
        return true;
      if(stripos($Text, '11001') !== false)
        return true;
    }

    return false;
 }

 function _LBL0A(){
   global $_QLttI;
   
   $_QL8i1 = mysql_query('SELECT VERSION()', $_QLttI);
   $_688Ct = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   return $_688Ct[0];
 }

 
 function _LBLPR(&$_j8l18){
    $_j8l18 = array();
    
    if(!defined("SWM")) return;
    
    $_68tQ1 = join("", file(InstallPath . "altbrowserlink/_AltBrowserLinkLangs.txt"));  

    $_68tQ1 = explode("\n", $_68tQ1);
    for($_Qli6J=0; $_Qli6J<count($_68tQ1); $_Qli6J++){
      if(strpos($_68tQ1[$_Qli6J], ";") !== false)
         $_68tQ1[$_Qli6J] = substr($_68tQ1[$_Qli6J], 0, strpos($_68tQ1[$_Qli6J], ";"));
      $_68tQ1[$_Qli6J] = trim($_68tQ1[$_Qli6J]);
      if($_68tQ1[$_Qli6J] == "") continue;
      $_IoLOO = explode("=", $_68tQ1[$_Qli6J]);
      if(count($_IoLOO) != 2) continue;
      $_j8l18[] = array("Name" => $_IoLOO[1], "code" => $_IoLOO[0]);
    }
 }

 function _LBLPC(&$_j8l18, &$_j8LoO){
    global $INTERFACE_LANGUAGE, $resourcestrings;
    
    _LBLPR($_j8l18);
    
    $_j8LoO = array();
    
    if(!defined("SWM")) return;
    
    $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
    
    $_68tJO = array($_jfLj1->abtUnsubscribe, $_jfLj1->abtTranslate);
    for($_Qli6J=$_jfLj1->abtSubscribe; $_Qli6J<=$_jfLj1->abtTranslate; $_Qli6J++){
     $_jfCoo = new TAltBrowserLinkInfoBarLink();
     $_jfCoo->LinkType = $_Qli6J;
     $_jfCoo->Checked = in_array($_Qli6J, $_68tJO);
     
     switch($_jfCoo->LinkType){
       case $_jfLj1->abtSubscribe:
          $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarSubscribeCaption"];
          $_jfCoo->URL = '';
          $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarSubscribeHint"];
          $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarSubscribeCaption"];
       break;
       case $_jfLj1->abtUnsubscribe: 
           $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarUnsubscribeCaption"];
           $_jfCoo->URL = "[UnsubscribeLink]";
           $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarUnsubscribeHint"];
           $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarUnsubscribeCaption"];
       break;                
       case $_jfLj1->abtFacebook: 
           $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarFacebookCaption"];
           $_jfCoo->URL = "https://www.facebook.com/sharer/sharer.php?u=*ALTBROWSERLINKURLANONYM*";
           $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarFacebookHint"];
           $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarFacebookCaption"];
       break;                
       case $_jfLj1->abtTwitter: 
           $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarTwitterCaption"];
           $_jfCoo->URL = "https://twitter.com/intent/tweet?url=*ALTBROWSERLINKURLANONYM*";
           $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarTwitterHint"];
           $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarTwitterCaption"];
       break;                
       case $_jfLj1->abtTranslate: 
           $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarTranslateCaption"];
           $_jfCoo->URL = $resourcestrings[$INTERFACE_LANGUAGE]["rsMenu"];
           $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarTranslateHint"];
           $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarTranslateCaption"];
       break;                
       case $_jfLj1->abtArchieve: 
           $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarNewsletterArchieveCaption"];
           $_jfCoo->URL = "";
           $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarNewsletterArchieveHint"];
           $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarNewsletterArchieveText"];
       break;                
       case $_jfLj1->abtRSS: 
           $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarRSSCaption"];
           $_jfCoo->URL = "";
           $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarRSSHint"];
           $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarRSSCaption"];
       break;                
     }
     
     $_j8LoO[] = $_jfCoo;
    }
    $_jfCoo = new TAltBrowserLinkInfoBarLink();
    $_jfCoo->LinkType = $_jfLj1->abtAttachments;
    $_jfCoo->Checked = true;
    $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarAttachmentsCaption"];
    $_jfCoo->URL = $resourcestrings[$INTERFACE_LANGUAGE]["rsMenu"];
    $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarAttachmentsHint"];
    $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarAttachmentsCaption"];
    $_j8LoO[] = $_jfCoo;
 }
 
 function _LBLDQ($_68tlt = ""){
    global $INTERFACE_LANGUAGE;
    $_j816L = array();
    if($_68tlt == "")
      $_Ift08 = join("", file(InstallPath . "altbrowserlink/_AltBrowserLinkStyle.txt"));  
      else
      $_Ift08 = join("", file( _LPC1C($_68tlt) . "na_start.htm"));  
    $_Ift08 = _L81DB($_Ift08, "<!--COLORSCHEME", "/COLORSCHEME-->");
    $_Ift08 = explode("\n", $_Ift08);
    for($_Qli6J=0; $_Qli6J<count($_Ift08); $_Qli6J++){
      $_Ift08[$_Qli6J] = str_replace(" ", "", trim($_Ift08[$_Qli6J]));
      if($_Ift08[$_Qli6J] == "") continue;
      $_IoLOO = explode("=", $_Ift08[$_Qli6J]);
      if(count($_IoLOO) < 2) continue;
      
      if($_IoLOO[1][strlen($_IoLOO[1]) - 1] == ";")
        $_IoLOO[1] = substr($_IoLOO[1], 0, strlen($_IoLOO[1]) - 1);
      
      if($INTERFACE_LANGUAGE == "de")
        $_j816L[$_IoLOO[0]] = $_IoLOO[1];
        else
        $_j816L[$_IoLOO[0]] = $_IoLOO[0];
    }
    if(!count($_j816L))
      $_j816L["black"] = "black";
    return $_j816L;  
 }

  function _LBJ6B(&$_j8l18, &$_j8LoO){
     global $INTERFACE_LANGUAGE, $resourcestrings;

     _LBLPC($_j8l18, $_j8LoO);

     if(!defined("SWM")) return;

     $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3

     $_J6f1t = true;
     while(true && $_J6f1t){
       $_J6f1t = false;
       foreach($_j8LoO as $_Qli6J => $_QltJO){
         if($_j8LoO[$_Qli6J]->LinkType == $_jfLj1->abtUnsubscribe || $_j8LoO[$_Qli6J]->LinkType == $_jfLj1->abtArchieve){
           array_splice($_j8LoO, $_Qli6J, 1);
           $_J6f1t = true;
           break;
         }
       }
     }
     
     foreach($_j8LoO as $_Qli6J => $_QltJO){
         $_j8LoO[$_Qli6J]->URL = str_replace("*ALTBROWSERLINKURLANONYM*", "*SHARELINKURL*", $_j8LoO[$_Qli6J]->URL);
         $_j8LoO[$_Qli6J]->URL = str_replace("*ALTBROWSERLINKURL*", "*SHARELINKURL*", $_j8LoO[$_Qli6J]->URL);
         if($_j8LoO[$_Qli6J]->LinkType == $_jfLj1->abtRSS)
            $_j8LoO[$_Qli6J]->URL = $resourcestrings[$INTERFACE_LANGUAGE]["rsDefault"];
     }
     
     
    for($_Qli6J=$_jfLj1->abtEntries; $_Qli6J>=$_jfLj1->abtHome; $_Qli6J--){
     $_jfCoo = new TAltBrowserLinkInfoBarLink();
     $_jfCoo->LinkType = $_Qli6J;
     $_jfCoo->Checked = true;
     
     switch($_jfCoo->LinkType){
       case $_jfLj1->abtHome:
          $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarHomeCaption"];
          $_jfCoo->URL = $resourcestrings[$INTERFACE_LANGUAGE]["rsDefault"];
          $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarHomeHint"];
          $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarHomeLinkText"];
       break;
       case $_jfLj1->abtYears:
          $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarYearsCaption"];
          $_jfCoo->URL = $resourcestrings[$INTERFACE_LANGUAGE]["rsMenu"];
          $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsDefault"];
          $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsDefault"];
       break;
       case $_jfLj1->abtEntries:
          $_jfCoo->internalCaption = $resourcestrings[$INTERFACE_LANGUAGE]["rsInfoBarEntriesCaption"];
          $_jfCoo->URL = $resourcestrings[$INTERFACE_LANGUAGE]["rsMenu"];
          $_jfCoo->Title = $resourcestrings[$INTERFACE_LANGUAGE]["rsDefault"];
          $_jfCoo->Text = $resourcestrings[$INTERFACE_LANGUAGE]["rsDefault"];
       break;
     }
     
     array_unshift($_j8LoO, $_jfCoo);
    }
  }
 
 if(!function_exists("in_arrayi")){
    function in_arrayi($_jOjt8, $_jOjjt) {
      return in_array(strtolower($_jOjt8), array_map('strtolower', $_jOjjt));
    }
 }
 
 function _LB6CD(&$_Io6Lf, &$_Ioftt, $_6JJt1, $_68OC1, $_jf0jO = 0){
   global $_QL88I, $_QLttI, $_Ijt0i;
   
   if($_6JJt1 == No_rtMailingLists || $_6JJt1 == No_rtMailingListForms){
     $_QLfol = "SELECT $_QL88I.SenderFromAddress, $_QL88I.ReturnPathEMailAddress, $_QL88I.`MTAsTableName`, $_QL88I.`AllowOverrideSenderEMailAddressesWhileMailCreating`, $_QL88I.`FormsTableName` FROM $_QL88I";
     $_QLfol .= " WHERE $_QL88I.id=$_68OC1";
   }else{
   
     $_jfJJ0 = _LPLBQ($_6JJt1);

     
     $_QLfol = "SELECT `$_jfJJ0`.`id`, ";

     if($_6JJt1 == rtFUResponders){
       $_QLfol .= "$_jfJJ0.`FUMailsTableName`, ";
     }  
     
     if($_6JJt1 == rtCampaigns || $_6JJt1 == rtDistributionLists){
       $_QLfol .= "$_jfJJ0.`MTAsTableName`, ";
     }else{
       $_QLfol .= "$_jfJJ0.`mtas_id`, ";
     }  

     if($_6JJt1 != rtAutoResponders){
       $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_jfJJ0.SenderFromAddress <> '', $_jfJJ0.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
       $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_jfJJ0.ReturnPathEMailAddress, $_jfJJ0.ReturnPathEMailAddress) AS ReturnPathEMailAddress,";
       $_QLfol .= " $_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating";
       $_QLfol .= " FROM $_jfJJ0 LEFT JOIN $_QL88I ON $_QL88I.id=$_jfJJ0.maillists_id";
     }else{
       $_QLfol .= "$_jfJJ0.SenderFromAddress, $_jfJJ0.ReturnPathEMailAddress FROM $_jfJJ0";
     }

     $_QLfol .= " WHERE $_jfJJ0.id=$_68OC1";
   }
   
   
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   _L8D88($_QLfol);
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   
     
   if( ($_6JJt1 == rtFUResponders || $_6JJt1 == No_rtMailingListForms) && $_jf0jO){
     if($_6JJt1 == rtFUResponders)
        $_QLfol = "SELECT SenderFromAddress, ReturnPathEMailAddress FROM `$_QLO0f[FUMailsTableName]` WHERE `id`=$_jf0jO";
        else
        $_QLfol = "SELECT SenderFromAddress, ReturnPathEMailAddress FROM `$_QLO0f[FormsTableName]` WHERE `id`=$_jf0jO";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     $_I1OfI = mysql_fetch_assoc($_I1O6j);
     mysql_free_result($_I1O6j);

     if(!$_QLO0f["AllowOverrideSenderEMailAddressesWhileMailCreating"])
        $_QLO0f = array_merge($_I1OfI, $_QLO0f); // override it
        else
        $_QLO0f = array_merge($_QLO0f, $_I1OfI); // override it
   }  
     
   if($_6JJt1 == rtCampaigns || $_6JJt1 == rtDistributionLists || 
      $_6JJt1 == No_rtMailingLists || $_6JJt1 == No_rtMailingListForms){
      $_QLfol = "SELECT `mtas_id` FROM $_QLO0f[MTAsTableName]";
      if($_6JJt1 == rtCampaigns)
        $_QLfol .= " WHERE `Campaigns_id`=" . $_68OC1;
      
      $_QLfol .= " ORDER BY `sortorder`";
      
      $_j0L0O = mysql_query($_QLfol, $_QLttI);
      if($_j0L0O && $_IQIfC = mysql_fetch_assoc($_j0L0O)){
        $_QLO0f["mtas_id"] = $_IQIfC["mtas_id"];
      }else
        $_QLO0f["mtas_id"] = 0;
      mysql_free_result($_j0L0O);
   }
   
   // check if there is a default MTA email address
   $_QLfol = "SELECT `MTASenderEMailAddress`, `Type`, `PHPMailParams` FROM `$_Ijt0i` WHERE id=" . $_QLO0f["mtas_id"];
   $_j0L0O = mysql_query($_QLfol, $_QLttI);
   if($_j0L0O && $_IQIfC = mysql_fetch_assoc($_j0L0O)){
     if(!empty($_IQIfC["MTASenderEMailAddress"]))
        $_QLO0f["SenderFromAddress"] = $_IQIfC["MTASenderEMailAddress"];
     if($_IQIfC["Type"] == "mail" && !empty($_IQIfC["PHPMailParams"])){
       if(strpos($_IQIfC["PHPMailParams"], "-f") !== false){
         $_QLJfI = trim(substr($_IQIfC["PHPMailParams"], strpos($_IQIfC["PHPMailParams"], "-f") + 2));
         if(_L8JLR($_QLJfI))
           $_QLO0f["ReturnPathEMailAddress"] = $_QLJfI;
       }
     }   
   }
   mysql_free_result($_j0L0O);  
  
   if(empty($_QLO0f["ReturnPathEMailAddress"]))
     $_QLO0f["ReturnPathEMailAddress"] = $_QLO0f["SenderFromAddress"];
   
   $_Io6Lf = $_QLO0f["SenderFromAddress"];
   $_Ioftt = $_QLO0f["ReturnPathEMailAddress"];
   
   $_68ot0 = strtolower( substr($_Io6Lf, strpos($_Io6Lf, '@') + 1) ) == 
          strtolower( substr($_Ioftt, strpos($_Ioftt, '@') + 1) );

   if(!$_68ot0){ // subdomains allowed, e.g. bounces.domain.xyz, sender email address domain.xyz

            function _LB6FO($_QLJfI) {
              $_j1881 = 0;
              while(strpos($_QLJfI, ".") !== false) {
                $_j1881++;
                $_QLJfI = substr($_QLJfI, strpos($_QLJfI, ".") + 1);
              }
              return $_j1881;
            }

     $_jjOlo = substr($_Ioftt, strpos($_Ioftt, '@') + 1);
     while(_LB6FO($_jjOlo) > 1){
       $_QlOjt = strpos($_jjOlo, ".");
       if($_QlOjt !== false) 
         $_jjOlo = substr($_jjOlo, $_QlOjt + 1);
     }  

     $_68ot0 = strtolower( substr($_Io6Lf, strpos($_Io6Lf, '@') + 1) ) == 
         strtolower( $_jjOlo );

   } 
   
   return $_68ot0;      
   
 }

?>
