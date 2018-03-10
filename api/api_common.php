<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

/**
 * Common API
 */
class api_Common extends api_base {

 /**
  * simple API test function
  * returns as sample data an array of APIVersion, ScriptName, ScriptVersion
  *
	 * @return array
  * @access public
	 */
 function api_testAPI() {
   return array("APIVersion" => api_getAPIVersion(), "ScriptName" => api_getScriptName(), "ScriptVersion" => api_getScriptVersion() );
 }

 /**
  * gets API version
  *
	 * @return string
  * @access public
	 */
 function api_getAPIVersion() {
   global $APIVersion;
   return $APIVersion;
 }

 /**
  * gets script name
  *
	 * @return string
  * @access public
	 */
 function api_getScriptName() {
   global $AppName;
   return $AppName;
 }

 /**
  * gets script version
  *
	 * @return string
  * @access public
	 */
 function api_getScriptVersion() {
   global $_QoJ8j;
   return $_QoJ8j;
 }

 /**
  * gets defined themes id and name
  *
	 * @return array
  * @access public
	 */
 function api_getThemes() {
   global $_Q61I1, $_Q880O;
   $_QJlJ0 = "SELECT * FROM $_Q880O";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8COf = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1))
      $_Q8COf[] = $_Q6Q1C;
   mysql_free_result($_Q60l1);
   return $_Q8COf;
 }

 /**
  * gets defined languages id and name
  *
	 * @return array
  * @access public
	 */
 function api_getLanguages() {
   global $_Q61I1, $_Qo6Qo;
   $_QJlJ0 = "SELECT * FROM $_Qo6Qo";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8COf = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1))
      $_Q8COf[] = $_Q6Q1C;
   mysql_free_result($_Q60l1);
   return $_Q8COf;
 }

 /**
  * gets all defined fieldnames in mailinglists for one recipient and localized text for specified language
  *
  * @param string $apiLanguageCode
	 * @return array
  * @access public
	 */
 function api_getRecipientsFieldnames($apiLanguageCode) {
   global $_Q61I1, $_Qofjo;
   if($apiLanguageCode == "") $apiLanguageCode = "en";
   $_QJlJ0 = "SELECT `fieldname`, `text` FROM $_Qofjo WHERE `language`="._OPQLR($apiLanguageCode);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8COf = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_Q8COf[] = array("fieldname" => $_Q6Q1C["fieldname"], "text" => $_Q6Q1C["text"]);
   }
   mysql_free_result($_Q60l1);
   return $_Q8COf;
 }

 /**
  * gets all defined MTAs
  *
	 * @return array
  * @access public
	 */
 function api_getMTAs() {
   global $_Q61I1, $_Qofoi;
   $_QJlJ0 = "SELECT * FROM $_Qofoi";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8COf = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1))
      $_Q8COf[] = $_Q6Q1C;
   mysql_free_result($_Q60l1);
   return $_Q8COf;
 }

 /**
  * gets all available email encodings
  *
	 * @return array
  * @access public
	 */
 function api_getSupportedMailEncodings() {
   global $_Qo8OO;

   $_Qot0C[] = "iso-8859-1";
   $_Qot0C[] = "utf-8";
   if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
     reset($_Qo8OO);
     foreach($_Qo8OO as $key => $_Q6ClO) {
        $_Qot0C[] = $key;
     }
   }

   return $_Qot0C;
 }

 /**
  * gets firm logo, allowed as user superadmin only
  *
	 * @return string
  * @access public
 */
 function api_getFirmLogo() {
  global $UserType;
  global $_Q61I1, $_Q88iO;
		if($UserType != "SuperAdmin" || defined("DEMO")) {
    return $this->api_Error("User superadmin can use this function only.");
  }
  $_QJlJ0 = "SELECT ProductLogoURL FROM $_Q88iO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1))
     return $_Q6Q1C["ProductLogoURL"];
     else
     return "";
	}

 /**
  * set firm logo, allowed as user superadmin only
  *
  * @param string $apiURL
	 * @return boolean
  * @access public
	 */
	function api_setFirmLogo($apiURL) {
  global $UserType;
  global $_Q61I1, $_Q88iO, $ProductLogoURL;
		if($UserType != "SuperAdmin" || defined("DEMO")) {
    return $this->api_Error("User superadmin can use this function only.");
  }

  $apiURL = _OPQLR($apiURL);
  $apiURL = str_replace("'", "", $apiURL);
  $apiURL = str_replace('"', "", $apiURL);
  $apiURL = str_replace('<', "", $apiURL);
  $apiURL = str_replace('>', "", $apiURL);
  $apiURL = str_replace("\\", "", $apiURL);

  $_QJlJ0 = "UPDATE $_Q88iO SET ";
  $_QJlJ0 .= "ProductLogoURL="._OPQLR($apiURL);
  mysql_query($_QJlJ0, $_Q61I1);
  $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;

  $ProductLogoURL = $apiURL;
  return mysql_affected_rows($_Q61I1) > 0;
	}

}
