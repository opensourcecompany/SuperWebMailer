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

include_once('../savedoptions.inc.php');

class api_base{
  /**
   * @access private
   *
	  */
  var $_I1jfC = false;

  /**
   * constructor PHP 5 style
   * @return void
	  */
/*  function __construct() {
    $this->api_base();
  } */

  /**
   * constructor PHP 4 style
   * @return void
	  */
  function __construct() {
    global $apiserver;

    # authentification check
    isset($apiserver->requestHeader["APIToken"]) ? $APIToken = $apiserver->requestHeader["APIToken"] : $APIToken = "";
    if($APIToken == "" || !$this->CheckAPIKey($APIToken)) {
      throw new Exception("Authentification required or authentification incorrect.");
    }
    $this->_I1jfC = true;
  }

  function api_base() {
    self::__construct();
  }

 /**
  * Internal Authentication function
  * sets all environment parameters for user
  *
  * @param string $apikey
  * @return boolean
  * @access private
  */
 function CheckAPIKey($apikey){
   global $_QLttI;
   global $UserId, $OwnerUserId, $OwnerOwnerUserId, $_I16ll, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE, $ProductLogoURL;
   global $resourcestrings, $_I18lo, $_I1tQf, $_I1O0i;

   if(empty($apikey)) return false;

   $_QLfol = "SELECT * FROM `$_I18lo` WHERE `apikey`="._LRAFO($apikey)." AND (`UserType`='Admin' OR `UserType`='SuperAdmin')";
   $_I1OQL = mysql_query($_QLfol, $_QLttI);

   if($_I1OQL && ($_QLO0f = mysql_fetch_assoc($_I1OQL)) ) {

      $UserId = $_QLO0f["id"];
      $OwnerUserId = 0; // ever admin or superadmin
      $Username = $_QLO0f["Username"];
      $UserType = $_QLO0f["UserType"];
      $AccountType = $_QLO0f["AccountType"];
      $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
      $INTERFACE_LANGUAGE = $_QLO0f["Language"];

      $_QLfol = "SELECT `Theme` FROM `$_I1tQf` WHERE `id`=$INTERFACE_THEMESID";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      $_I1OfI = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_I1OfI[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);

      _LRPQ6($INTERFACE_LANGUAGE);
      _JQRLR($INTERFACE_LANGUAGE);

      mysql_free_result($_I1OQL);

      $_QLfol = "SELECT * FROM `$_I1O0i` LIMIT 0,1";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QLO0f = mysql_fetch_assoc($_QL8i1))
       return false;
      mysql_free_result($_QL8i1);
      $ProductLogoURL = $_QLO0f["ProductLogoURL"];
      $_I1OoI = _JOLOA($_QLO0f);
      if($_I1OoI === false)
        return false;
      if(isset($_I1OoI["DashboardTag"])){
        $OwnerOwnerUserId = ord( substr($_I1OoI["DashboardTag"], strlen($_I1OoI["DashboardTag"]) - 1, 1) );
        $_I16ll = sprintf("%06X", intval(strrev(substr(substr($_I1OoI["DashboardTag"], 0, 13), 7))));
      }

      return true;
   } else {
     return false;
   }
 }

  /**
   * Shows SQL error
   * @access public
   *
	  * @param string $_QLfol
   * @return void
	  */
   function api_ShowSQLError($_QLfol){
     global $AppName, $_QLttI;
     $_QLJfI = mysql_error($_QLttI);
     if($_QLJfI == "") return;
     return $this->api_Error("An SQL error occurs: ".$_QLJfI." ".$_QLfol);
   }

  /**
   * Shows an error text
   * @access public
   *
	  * @param string $_I1OLj
   * @return void
	  */
  function api_Error($_I1OLj){
     global $AppName, $apiserver;
     $_I1Ilj = $AppName." - Error: " . $_I1OLj;
     return $apiserver->fault("1", $_I1Ilj, "", $_I1Ilj);
  }


}

?>
