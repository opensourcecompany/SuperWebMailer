<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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
   return array("APIVersion" => $this->api_getAPIVersion(), "ScriptName" => $this->api_getScriptName(), "ScriptVersion" => $this->api_getScriptVersion() );
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
   global $_Ij6Lj;
   return $_Ij6Lj;
 }

 /**
  * gets defined themes id and name
  *
	 * @return array
  * @access public
	 */
 function api_getThemes() {
   global $_QLttI, $_I1tQf;
   $_QLfol = "SELECT * FROM $_I1tQf";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1o8o = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1))
      $_I1o8o[] = $_QLO0f;
   mysql_free_result($_QL8i1);
   return $_I1o8o;
 }

 /**
  * gets defined languages id and name
  *
	 * @return array
  * @access public
	 */
 function api_getLanguages() {
   global $_QLttI, $_Ijf8l;
   $_QLfol = "SELECT * FROM $_Ijf8l";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1o8o = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1))
      $_I1o8o[] = $_QLO0f;
   mysql_free_result($_QL8i1);
   return $_I1o8o;
 }

 /**
  * gets all defined fieldnames in mailinglists for one recipient and localized text for specified language
  *
  * @param string $apiLanguageCode
	 * @return array
  * @access public
	 */
 function api_getRecipientsFieldnames($apiLanguageCode) {
   global $_QLttI, $_Ij8oL;
   if($apiLanguageCode == "") $apiLanguageCode = "en";
   $_QLfol = "SELECT `fieldname`, `text` FROM $_Ij8oL WHERE `language`="._LRAFO($apiLanguageCode);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1o8o = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_I1o8o[] = array("fieldname" => $_QLO0f["fieldname"], "text" => $_QLO0f["text"]);
   }
   mysql_free_result($_QL8i1);
   return $_I1o8o;
 }

 /**
  * gets all defined MTAs
  *
	 * @return array
  * @access public
	 */
 function api_getMTAs() {
   global $_QLttI, $_Ijt0i;
   $_QLfol = "SELECT * FROM $_Ijt0i";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1o8o = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1))
      $_I1o8o[] = $_QLO0f;
   mysql_free_result($_QL8i1);
   return $_I1o8o;
 }

 /**
  * gets all available email encodings
  *
	 * @return array
  * @access public
	 */
 function api_getSupportedMailEncodings() {
   global $_Ijt8j;

   $_IjO6t[] = "iso-8859-1";
   $_IjO6t[] = "utf-8";
   if ( iconvExists || mbfunctionsExists ) {
     reset($_Ijt8j);
     foreach($_Ijt8j as $key => $_QltJO) {
        $_IjO6t[] = $key;
     }
   }

   return $_IjO6t;
 }

 /**
  * gets firm logo
  *
	 * @return string
  * @access public
 */
 function api_getFirmLogo() {
  global $_QLttI, $_I1O0i, $_I18lo, $UserId, $UserType;

  $_IjOiO = "";
  
  if($UserType == "Admin"){
    $_QLfol = "SELECT ProductLogoURL FROM $_I18lo WHERE id=$UserId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1))
       $_IjOiO = $_QLO0f["ProductLogoURL"];
    mysql_free_result($_QL8i1);
  }

  if($_IjOiO == ""){
    $_QLfol = "SELECT ProductLogoURL FROM $_I1O0i";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1))
       $_IjOiO = $_QLO0f["ProductLogoURL"];
    mysql_free_result($_QL8i1);
  }
  return $_IjOiO;   
	}

 /**
  * set firm logo
  *
  * @param string $apiURL
	 * @return boolean
  * @access public
	 */
	function api_setFirmLogo($apiURL) {
  global $_I18lo, $UserId, $UserType;
  global $_QLttI, $_I1O0i, $ProductLogoURL;
		if(defined("DEMO")) {
    return $this->api_Error("DEMO version function not available.");
  }

  $apiURL = _LRAFO($apiURL);
  $apiURL = str_replace("'", "", $apiURL);
  $apiURL = str_replace('"', "", $apiURL);
  $apiURL = str_replace('<', "", $apiURL);
  $apiURL = str_replace('>', "", $apiURL);
  $apiURL = str_replace("\\", "", $apiURL);

  if($UserType == "SuperAdmin"){
    $_QLfol = "UPDATE $_I1O0i SET ";
    $_QLfol .= "ProductLogoURL="._LRAFO($apiURL);
    mysql_query($_QLfol, $_QLttI);
    $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;

    $ProductLogoURL = $apiURL;
    return mysql_affected_rows($_QLttI) > 0;
  }else{
    $_QLfol = "UPDATE $_I18lo SET ";
    $_QLfol .= "ProductLogoURL="._LRAFO($apiURL);
    $_QLfol .= " WHERE id=$UserId";
    mysql_query($_QLfol, $_QLttI);
    $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;

    $ProductLogoURL = $apiURL;
    return mysql_affected_rows($_QLttI) > 0;
  }
  
	}

}
