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

include_once('../savedoptions.inc.php');

class api_base{
  /**
   * @access private
   *
	  */
  var $_Q8Q1Q = false;

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
    $this->_Q8Q1Q = true;
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
   global $_Q61I1;
   global $UserId, $OwnerUserId, $OwnerOwnerUserId, $_Q8J1j, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE, $ProductLogoURL;
   global $resourcestrings, $_Q8f1L, $_Q880O, $_Q88iO;

   if(empty($apikey)) return false;

   $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE `apikey`="._OPQLR($apikey)." AND (`UserType`='Admin' OR `UserType`='SuperAdmin')";
   $_Q8to6 = mysql_query($_QJlJ0, $_Q61I1);

   if($_Q8to6 && ($_Q6Q1C = mysql_fetch_assoc($_Q8to6)) ) {

      $UserId = $_Q6Q1C["id"];
      $OwnerUserId = 0; // ever admin or superadmin
      $Username = $_Q6Q1C["Username"];
      $UserType = $_Q6Q1C["UserType"];
      $AccountType = $_Q6Q1C["AccountType"];
      $INTERFACE_THEMESID = $_Q6Q1C["ThemesId"];
      $INTERFACE_LANGUAGE = $_Q6Q1C["Language"];

      $_QJlJ0 = "SELECT `Theme` FROM `$_Q880O` WHERE `id`=$INTERFACE_THEMESID";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
      $INTERFACE_STYLE = $_Q8OiJ[0];
      mysql_free_result($_Q8Oj8);

      _OP0D0($_Q6Q1C);

      _OP0AF($UserId);

      _OP10J($INTERFACE_LANGUAGE);
      _LQLRQ($INTERFACE_LANGUAGE);

      mysql_free_result($_Q8to6);

      $_QJlJ0 = "SELECT * FROM `$_Q88iO` LIMIT 0,1";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q6Q1C = mysql_fetch_assoc($_Q60l1))
       return false;
      mysql_free_result($_Q60l1);
      $ProductLogoURL = $_Q6Q1C["ProductLogoURL"];
      $_Q8otJ = _LQEQR($_Q6Q1C);
      if($_Q8otJ === false)
        return false;
      if(isset($_Q8otJ["DashboardTag"])){
        $OwnerOwnerUserId = ord( substr($_Q8otJ["DashboardTag"], strlen($_Q8otJ["DashboardTag"]) - 1, 1) );
        $_Q8J1j = sprintf("%06X", intval(strrev(substr(substr($_Q8otJ["DashboardTag"], 0, 13), 7))));
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
	  * @param string $_QJlJ0
   * @return void
	  */
   function api_ShowSQLError($_QJlJ0){
     global $AppName, $_Q61I1;
     $_QJCJi = mysql_error($_Q61I1);
     if($_QJCJi == "") return;
     return $this->api_Error("An SQL error occurs: ".$_QJCJi." ".$_QJlJ0);
   }

  /**
   * Shows an error text
   * @access public
   *
	  * @param string $_Q8oLt
   * @return void
	  */
  function api_Error($_Q8oLt){
     global $AppName, $apiserver;
     $_Q8C08 = $AppName." - Error: " . $_Q8oLt;
     return $apiserver->fault("1", $_Q8C08, "", $_Q8C08);
  }


}

?>
